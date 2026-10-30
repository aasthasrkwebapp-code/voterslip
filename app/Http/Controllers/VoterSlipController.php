<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class VoterSlipController extends Controller
{
    public function search(Request $request)
    {
        $epic = Str::upper(trim((string) $request->query('epic', '')));

        $voter = null;
        $notFound = false;

        if ($epic !== '') {
            $voter = Voter::with(['election', 'ward', 'pollingStation', 'photo'])
                ->whereRaw('UPPER(epic_number) = ?', [$epic])
                ->first();

            $notFound = !$voter;
        }

        $pageCount = (int) ceil(Voter::whereHas('ward', fn ($query) => $query->where('ward_number', '1'))->count() / 8);

        return view('voter.search', [
            'epic' => $epic,
            'voter' => $voter,
            'notFound' => $notFound,
            'pageCount' => $pageCount,
        ]);
    }

    public function slip(Request $request, Voter $voter)
    {
        $voter->load(['election', 'ward', 'pollingStation', 'photo']);

        $mpdf = $this->makePdf('A5');
        $mpdf->SetTitle('E-Slip '.$voter->epic_number);
        $mpdf->WriteHTML(view('voter.slip', ['voter' => $voter])->render());

        $filename = 'e-slip-'.$voter->epic_number.'.pdf';
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
        ]);
    }

    public function sheet(Request $request)
    {
        $wardNumber = (string) $request->query('ward', '1');
        $page = max(1, (int) $request->query('page', '1'));
        $perPage = 8;

        $voters = Voter::with(['election', 'ward', 'pollingStation', 'photo'])
            ->whereHas('ward', fn ($query) => $query->where('ward_number', $wardNumber))
            ->get()
            ->sortBy(fn (Voter $voter) => (int) $voter->serial_number)
            ->values()
            ->forPage($page, $perPage)
            ->values();

        abort_if($voters->isEmpty(), 404);

        $mpdf = $this->makePdf('A4');
        $mpdf->SetTitle('Voter slips ward '.$wardNumber);
        $mpdf->WriteHTML(view('voter.sheet', ['voters' => $voters])->render());

        $filename = 'voter-slips-ward-'.$wardNumber.'-page-'.$page.'.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    private function makePdf(string $format): Mpdf
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];

        $fontDirs[] = storage_path('fonts');
        $fontData['nirmala'] = ['R' => 'NotoSansDevanagari-Regular.ttf'];

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => $format,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'fontDir' => $fontDirs,
            'fontdata' => $fontData,
            'default_font' => env('SLIP_FONT', 'nirmala'),
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
    }
}
