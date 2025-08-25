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
}
