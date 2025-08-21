<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AptitudeTestFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'currency',
        'description',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Note: Aptitude test payments are linked to applications, not directly to fees.
    // Since we now have a single fee for all programs, payment statistics are
    // calculated directly in the controller using the AptitudeTestPayment model.
}
