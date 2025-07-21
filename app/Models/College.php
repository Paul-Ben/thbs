<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function programmes()
    {
        return $this->hasMany(Programme::class);
    }
}
