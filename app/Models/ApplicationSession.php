<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationSession extends Model
{
    use HasFactory;
    protected $fillable = ['session_name', 'year', 'is_current'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
} 