<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolSession extends Model
{
    use HasFactory;
    
    protected $fillable = ['session_name', 'year'];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
