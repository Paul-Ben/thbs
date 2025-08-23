<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_name',
        'school_session_id',
        'is_current',
        'registration_start_date',
        'registration_end_date'
    ];

    protected $casts = [
        'registration_start_date' => 'date',
        'registration_end_date' => 'date',
        'is_current' => 'boolean'
    ];

    public function schoolSession()
    {
        return $this->belongsTo(SchoolSession::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}