<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Programme;
use App\Models\ApplicationSession;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Exports\AdmissionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdmissionsImport;
use Illuminate\Support\Facades\Log;
use App\Exports\AdmissionTemplateExport;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class AdmissionController extends Controller
{
    public function index(): View
    {
        $admissions = Admission::with(['programme', 'applicationSession'])->latest()->get();
        return view('admission_officer.admissions.index', compact('admissions'));
    }

    public function show(Admission $admission): View
    {
        $admission = Admission::with(['programme', 'applicationSession'])->find($admission->id);
        return view('admission_officer.admissions.show', compact('admission'));
    }

    public function exportAdmissions()
    {
        return Excel::download(new AdmissionsExport, 'admissions.xlsx');
    }

    public function importAdmissions(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt,xlsx'
        ]);

        $import = new AdmissionsImport();
        Excel::import($import, $request->file('csv_file'));

        if ($import->hasErrors()) {
            ToastMagic::error($import->getFirstError());
            return redirect()->route('admission_officer.admissions');
        }

        ToastMagic::success('Admission list imported successfully!');
        return redirect()->route('admission_officer.admissions');
    }

    public function destroy(Admission $admission): RedirectResponse
    {
        $admission->delete();
        ToastMagic::success('Admission deleted successfully!');
        return redirect()->route('admission_officer.admissions');
    }

    public function downloadTemplate()
    {
        return Excel::download(new AdmissionTemplateExport, 'admission_template.xlsx');
    }
}
