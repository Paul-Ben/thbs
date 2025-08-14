<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationFee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'programme_id',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
