<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PdfImportController extends Controller
{
    public function create()
    {
        return view('admin.pdf-imports.create', [
            'elections' => Election::orderBy('name')->get(),
            'wards' => Ward::orderBy('ward_number')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'election_id' => [
                'required',
                'integer',
                'exists:elections,id',
            ],
            'ward_id' => [
                'required',
                'integer',
                'exists:wards,id',
            ],
            'pdf_file' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:51200',
            ],
        ]);

        // Verify that the selected ward belongs to the election.
        $ward = Ward::where('id', $validated['ward_id'])
            ->where('election_id', $validated['election_id'])
            ->first();

        if (!$ward) {
            return back()->withInput()->withErrors([
                'ward_id' => 'Selected ward does not belong to this election.',
            ]);
        }

        $file = $request->file('pdf_file');

        $hash = hash_file('sha256', $file->getRealPath());

        // Prevent duplicate PDF uploads.
        $duplicate = DB::table('pdf_imports')
            ->where('election_id', $validated['election_id'])
            ->where('ward_id', $validated['ward_id'])
            ->where('file_hash', $hash)
            ->exists();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'pdf_file' => 'This PDF has already been uploaded for this ward.',
            ]);
        }

        $path = $file->store('voter-pdfs', 'private');

        try {
            DB::table('pdf_imports')->insert([
                'election_id' => $validated['election_id'],
                'ward_id' => $validated['ward_id'],
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_hash' => $hash,
                'total_pages' => 0,
                'total_records' => 0,
                'imported_records' => 0,
                'failed_records' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Storage::disk('private')->delete($path);

            report($e);

            return back()->withInput()->withErrors([
                'pdf_file' => 'Upload could not be saved. Please try again.',
            ]);
        }

        return redirect()
            ->route('admin.pdf-imports.create')
            ->with('success', 'PDF uploaded successfully. Import is pending.');
    }
}
