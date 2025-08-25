<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdmissionTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'application_number',
            'payment_reference',
            'applicant_surname',
            'applicant_othernames',
            'date_of_birth',
            'gender',
            'state_of_origin',
            'lga',
            'nationality',
            'religion',
            'marital_status',
            'home_town',
            'email',
            'phone',
            'correspondence_address',
            'employment_status',
            'permanent_home_address',
            'parent_guardian_name',
            'parent_guardian_phone',
            'parent_guardian_address',
            'parent_guardian_occupation',
            'programme_name',
            'application_session',
            'admission_status',
        ];
    }
}
