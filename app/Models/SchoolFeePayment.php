<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolFeePayment extends Model
{
    /**
     * Get all transactions for this payment.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'paymentable');
    }
}
