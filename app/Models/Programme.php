<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programme extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'level', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
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

    public function applicationFees()
    {
        return $this->hasMany(ApplicationFee::class);
    }
}
