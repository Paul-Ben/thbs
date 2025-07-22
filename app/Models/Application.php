<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;
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
    ];

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }
}
