<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AptitudeTestFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'amount',
        'currency',
        'description',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function aptitudeTestPayments()
    {
        return $this->hasMany(AptitudeTestPayment::class, 'aptitude_test_fee_id');
    }
}
