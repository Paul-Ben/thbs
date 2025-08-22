<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolFeePayment extends Model
{
    protected $fillable = [
        'school_fee_id',
        'student_id',
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

    /**
     * Get the school fee that owns this payment.
     */
    public function schoolFee(): BelongsTo
    {
        return $this->belongsTo(SchoolFee::class);
    }

    /**
     * Get the student that made this payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get all transactions for this payment.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'paymentable');
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful()
    {
        return $this->status === 'successful';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
