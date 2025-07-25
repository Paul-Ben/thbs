<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'course_id', 'semester_id'];

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
}
