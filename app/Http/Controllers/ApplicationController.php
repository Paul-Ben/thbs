<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreApplicationRequest;
use App\Services\ApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Application;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationService $applicationService) {}

    public function landing()
    {
        return view('application.landing');
    }

    public function create()
    {
        $programmes = Programme::all();
        return view('application.apply', compact('programmes'));
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $application = $this->applicationService->create($request->validated());

        $notification = [
            'alert-type' => 'success',
            'message' => 'Application submitted successfully!'
        ];
        return redirect()->route('application.printout', $application)->with($notification);
    }

    public function printout(Application $application): View
    {
        $application->load('academicRecords');
        $oLevelRecords = $application->academicRecords->where('level', 'O/LEVEL')->values();
        $aLevelRecords = $application->academicRecords->where('level', 'A/LEVEL')->values();
        return view('application.printout', compact('application', 'oLevelRecords', 'aLevelRecords'));
    }

    public function downloadPrintout(Application $application): Response
    {
        $result = $this->applicationService->printoutPdfDownload($application);
        return $result['pdf']->download($result['filename']);
    }

    public function applications()
    {
        $authUser = Auth::user();
        return view('admission_officer.applications', compact('authUser'));
    }
}
