<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreApplicationRequest;
use App\Services\ApplicationService;
use Illuminate\Http\RedirectResponse;
use App\Services\PaymentService;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Application;
use App\Models\Programme;
use App\Models\ApplicationFeePayment;
use App\Models\AptitudeTestFee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplicationsExport;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationService $applicationService, private NotificationService $notificationService)
    {
    }

    public function landing()
    {
        $programmes = Programme::with('applicationFees')->get();

        $aptitudeTestFee = AptitudeTestFee::where('is_active', true)->first();

        return view('application.landing', compact('programmes', 'aptitudeTestFee'));
    }

    public function create(string $txRef): View|RedirectResponse
    {

        $payment = ApplicationFeePayment::where('reference', $txRef)->first();

        if ($payment->application->is_filled == 1) {
            return redirect()->route('application.printout', $payment->application);
        }

        if (!$payment || !$payment->isSuccessful()) {
            return redirect()->route('application.landing')
                ->with('error', 'Please complete payment before proceeding with your application.');
        }

        $programmes = Programme::all();

        $applicant = $payment->application->load('programme');

        return view('application.apply', compact('programmes', 'payment', 'applicant'));
    }

    public function store(StoreApplicationRequest $request, string $txRef): RedirectResponse
    {
        $payment = ApplicationFeePayment::where('reference', $txRef)->first();

        if (!$payment || !$payment->isSuccessful()) {
            return redirect()->route('application.landing')
                ->with('error', 'Payment verification failed. Please try again.');
        }

        $application = DB::transaction(function () use ($request, $txRef, $payment) {
            $applicationData = $request->validated();
            $application = $this->applicationService->create($applicationData, $txRef);

            $payment->update(['application_id' => $application->id]);

            return $application;
        });


        $this->notificationService->sendApplicationSubmittedNotification($application);

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

    public function continueApplication(string $txRef): RedirectResponse
    {
        $payment = ApplicationFeePayment::where('reference', $txRef)->with('application')->first();

        if (
            $payment &&
            $payment->application &&
            $payment->reference === $payment->application->payment_reference &&
            $payment->isSuccessful()
        ) {
            return redirect()->route('application.create', $payment->reference)
                ->with('success', 'Please proceed with your application.');
        }

        return redirect()->route('application.landing')
            ->with('error', 'Invalid Reference. Please try again.');
    }

    public function retrieveApplication(string $applicationNumber): View|RedirectResponse
    {
        $application = Application::where('application_number', $applicationNumber)->first();

        if (!$application) {
            return redirect()->route('application.landing')
                ->with('error', 'Application not found. Try again!');
        }

        $application->load('academicRecords');
        $oLevelRecords = $application->academicRecords->where('level', 'O/LEVEL')->values();
        $aLevelRecords = $application->academicRecords->where('level', 'A/LEVEL')->values();

        return view('application.printout', compact('application', 'oLevelRecords', 'aLevelRecords'));
    }


    public function applications(): view
    {
        $authUser = Auth::user();
        $applications = Application::with('programme')
            ->where('is_filled', 1)
            ->get();
        return view('admission_officer.applications', compact('authUser', 'applications'));
    }

    public function show(Application $application): View
    {
        $application->load('academicRecords');
        $oLevelRecords = $application->academicRecords->where('level', 'O/LEVEL')->values();
        $aLevelRecords = $application->academicRecords->where('level', 'A/LEVEL')->values();
        return view('admission_officer.show', compact('application', 'oLevelRecords', 'aLevelRecords'));
    }

    public function exportApplications()
    {
        return Excel::download(new ApplicationsExport, 'applications.xlsx');
    }
}
