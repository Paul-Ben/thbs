<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 
        'title', 
        'credit_units', 
        'programme_id', 
        'level_id', 
        'semester_id'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    
    public function level()
    {
        return $this->belongsTo(Level::class);
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