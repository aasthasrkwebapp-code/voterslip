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

        return view('voter.search', [
            'epic' => $epic,
            'voter' => $voter,
            'notFound' => $notFound,
        ]);
    }

    public function slip(Request $request, Voter $voter)
    {
        $voter->load(['election', 'ward', 'pollingStation', 'photo']);

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];

        $fontDirs[] = storage_path('fonts');
        $fontData['nirmala'] = ['R' => 'Nirmala.ttf'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A5',
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

        $mpdf->SetTitle('E-Slip '.$voter->epic_number);
        $mpdf->WriteHTML(view('voter.slip', ['voter' => $voter])->render());

        $filename = 'e-slip-'.$voter->epic_number.'.pdf';
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
        ]);
    }
}
