<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    /**
     * Use application_number for route model binding.
     */
    public function getRouteKeyName()
    {
        return 'application_number';
    }

    use HasFactory;

    protected $casts = [
    'declaration_check' => 'boolean',
];
    protected $fillable = [
        'application_number',
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
        'programme_id',
        'status',
        'passport',
        'credential',
        'declaration_check',
    ];

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }
}
