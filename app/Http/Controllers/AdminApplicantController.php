<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Exports\ApplicantsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminApplicantController extends Controller
{
    public function dashboard()
    {
        // Redirect ke dashboard panitia yang baru
        return redirect()->route('admin.dashboard');
    }

    public function index()
    {
        $applicants = Applicant::with(['user', 'major', 'wave'])->get();
        return view('admin.applicants.index', compact('applicants'));
    }

    public function show($id)
    {
        $applicant = Applicant::with(['user', 'major', 'wave', 'files'])->findOrFail($id);
        return view('admin.applicants.show', compact('applicant'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:SUBMIT,VERIFIED,REJECTED'
        ]);
        
        $applicant = Applicant::findOrFail($id);
        $applicant->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Status pendaftar berhasil diupdate menjadi ' . $request->status);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['status', 'major_id', 'wave_id']);
        return Excel::download(new ApplicantsExport($filters), 'data-pendaftar-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Applicant::with(['user', 'major', 'wave']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->major_id) {
            $query->where('major_id', $request->major_id);
        }
        if ($request->wave_id) {
            $query->where('wave_id', $request->wave_id);
        }

        $applicants = $query->get();
        
        $pdf = Pdf::loadView('admin.applicants.export-pdf', compact('applicants', 'request'));
        return $pdf->download('data-pendaftar-' . date('Y-m-d') . '.pdf');
    }
}