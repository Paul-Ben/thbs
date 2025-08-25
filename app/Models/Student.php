<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'applicant_name',
        'matric_number',
        'email',
        'gender',
        'phone',
        'address',
        'country',
        'state_of_origin',
        'lga',
        'programme_id',
        'date_of_birth',
        'passport',
        'credential',
        'user_id',
        'application_id',
        'level_id'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function schoolFeePayments()
    {
        return $this->hasMany(SchoolFeePayment::class);
    }

    /**
     * Calculate GPA for the student
     */
    public function calculateGPA(int $semesterId = null): float
    {
        $resultsQuery = $this->results()->where('level_id', $this->level_id)->whereHas('course', function ($query) {
            $query->whereNotNull('credit_units');
        });

        if ($semesterId) {
            $resultsQuery->where('semester_id', $semesterId);
        }

        $results = $resultsQuery->get();
        if ($results->isEmpty()) {
            return 0;
        }

        $totalGradePoints = $results->sum(function ($result) {
            return $result->grade_point * $result->course->credit_units;
        });

        $totalCreditUnits = $results->sum(function ($result) {
            return $result->course->credit_units;
        });

        return $totalCreditUnits > 0 ? round($totalGradePoints / $totalCreditUnits, 2) : 0;
    }

    /**
     * Check if student has paid fees for current semester
     */
    public function hasPaidFeesForCurrentSemester(): bool
    {
        $currentSession = SchoolSession::where('is_current', true)->first();
        $currentSemester = Semester::where('is_current', true)->first();

        if (!$currentSession || !$currentSemester) {
            return true; // No current session/semester defined, allow registration
        }

        // Check if a fee record exists for this student, session, and semester
        $requiredFee = SchoolFee::where('programme_id', $this->programme_id)
            ->where('school_session_id', $currentSession->id)
            ->where('semester_id', $currentSemester->id)
            ->when($this->level_id, function($query) {
                $query->where('level_id', $this->level_id);
            })
            ->where('is_active', true)
            ->first();

        if (!$requiredFee) {
            return true; // No fee defined, so student can proceed
        }

        $totalPaid = SchoolFeePayment::where('student_id', $this->id)
            ->where('school_fee_id', $requiredFee->id)
            ->where('status', 'successful')
            ->sum('amount');

        return $totalPaid >= $requiredFee->amount;
    }

    /**
     * Get outstanding fees for student
     */
    public function getOutstandingFees(): float
    {
        $currentSession = SchoolSession::where('is_current', true)->first();
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSession || !$currentSemester) {
            return 0;
        }
        
        $activeFees = SchoolFee::where('programme_id', $this->programme_id)
            ->where('school_session_id', $currentSession->id)
            ->where('semester_id', $currentSemester->id)
            ->when($this->level_id, function($query) {
                $query->where('level_id', $this->level_id);
            })
            ->where('is_active', true)
            ->get();
            
        $totalOutstanding = 0;
        foreach ($activeFees as $fee) {
            $totalPaid = SchoolFeePayment::where('student_id', $this->id)
                ->where('school_fee_id', $fee->id)
                ->where('status', 'successful')
                ->sum('amount');
                
            $outstanding = $fee->amount - $totalPaid;
            if ($outstanding > 0) {
                $totalOutstanding += $outstanding;
            }
        }
        
        return $totalOutstanding;
    }
}