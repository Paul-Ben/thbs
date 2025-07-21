<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programme extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'level', 'college_id'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
