<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;
    protected $fillable = ['semester_name', 'school_session_id'];

    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'school_session_id');
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
