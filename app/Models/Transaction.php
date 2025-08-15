<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'paymentable_id',
        'paymentable_type',
        'type',
        'status',
        'amount',
        'currency_code',
        'is_reconciled'
    ];


    public function paymentable()
    {
        return $this->morphTo();
    }

    public function isApplicationFeePayment()
    {
        return $this->paymentable_type === ApplicationFeePayment::class;
    }

    public function isSchoolFeePayment()
    {
        return $this->paymentable_type === SchoolFeePayment::class;
    }

}
