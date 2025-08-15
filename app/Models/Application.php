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
        'is_filled' => 'boolean',
    ];
    protected $fillable = [
        'payment_reference',
        'application_session_id',
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
        'tx_ref',
        'is_filled',
    ];

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function schoolSession()
    {
        return $this->belongsTo(SchoolSession::class);
    }

    public function applicationFeePayments()
    {
        return $this->hasMany(ApplicationFeePayment::class, 'reference', 'payment_reference');
    }

    public function hasSuccessfulPayment()
    {
        return $this->applicationFeePayments()->where('status', 'successful')->exists();
    }

    public function isFilled()
    {
        return $this->is_filled == 1;
    }

    public function isNotFilled()
    {
        return $this->is_filled == 0;
    }
}
