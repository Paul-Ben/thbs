<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'course_id', 
        'school_session_id',
        'semester_id', 
        'level_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function schoolSession()
    {
        return $this->belongsTo(SchoolSession::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
