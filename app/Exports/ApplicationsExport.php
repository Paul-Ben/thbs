<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\ApplicationSession;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Application::with('programme', 'aptitudeTestPayments')
            ->where('is_filled', 1)
            ->whereHas('aptitudeTestPayments', function ($query) {
                $query->where('status', 'successful');
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Application Number',
            'Payment Reference',
            'Applicant Surname',
            'Applicant Othernames',
            'Date of Birth',
            'Gender',
            'State of Origin',
            'LGA',
            'Nationality',
            'Religion',
            'Marital Status',
            'Home Town',
            'Email',
            'Phone',
            'Correspondence Address',
            'Employment Status',
            'Permanent Home Address',
            'Parent/Guardian Name',
            'Parent/Guardian Phone',
            'Parent/Guardian Address',
            'Parent/Guardian Occupation',
            'Programme Name',
            'Application Session',
            'Application Status',
            'Application Date'
        ];
    }

    public function map($application): array
    {
        $aptitudeTestPaymentStatus = optional($application->aptitudeTestPayments->where('status', 'successful')->first())->status;
        $currentApplicationSessionName = optional(ApplicationSession::where('is_current', true)->first())->session_name;

        return [
            $application->application_number,
            $application->payment_reference,
            $application->applicant_surname,
            $application->applicant_othernames,
            $application->date_of_birth,
            $application->gender,
            $application->state_of_origin,
            $application->lga,
            $application->nationality,
            $application->religion,
            $application->marital_status,
            $application->home_town,
            $application->email,
            $application->phone,
            $application->correspondence_address,
            $application->employment_status,
            $application->permanent_home_address,
            $application->parent_guardian_name,
            $application->parent_guardian_phone,
            $application->parent_guardian_address,
            $application->parent_guardian_occupation,
            optional($application->programme)->name,
            $currentApplicationSessionName,
            $application->status,
            $application->created_at->format('d/m/Y')
        ];
    }
}
