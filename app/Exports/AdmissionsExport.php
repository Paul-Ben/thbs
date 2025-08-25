<?php

namespace App\Exports;

use App\Models\Admission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdmissionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Admission::with(['programme', 'applicationSession'])->get();
    }

    public function headings(): array
    {
        return [
            'S/N',
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
            'Admission Status',
            'Created At',
        ];
    }

    public function map($admission): array
    {
        return [
            $admission->id,
            $admission->application_number,
            $admission->payment_reference,
            $admission->applicant_surname,
            $admission->applicant_othernames,
            $admission->date_of_birth,
            $admission->gender,
            $admission->state_of_origin,
            $admission->lga,
            $admission->nationality,
            $admission->religion,
            $admission->marital_status,
            $admission->home_town,
            $admission->email,
            $admission->phone,
            $admission->correspondence_address,
            $admission->employment_status,
            $admission->permanent_home_address,
            $admission->parent_guardian_name,
            $admission->parent_guardian_phone,
            $admission->parent_guardian_address,
            $admission->parent_guardian_occupation,
            optional($admission->programme)->name,
            optional($admission->applicationSession)->session_name,
            $admission->status,
            $admission->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
