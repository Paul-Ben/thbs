<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AptitudeTestPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'reference',
        'status',
        'payment_date',
        'description',
        'metadata'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'metadata' => 'array',
        'amount' => 'decimal:2'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'paymentable');
    }
}
