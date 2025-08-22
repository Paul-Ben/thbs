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
        'application_id'
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
}
