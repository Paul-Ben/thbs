<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [

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

    public function application()
    {
        return $this->belongsTo(Application::class, 'reference', 'payment_reference');
    }

    public function isSuccessful()
    {
        return $this->status === 'successful';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function hasApplication()
    {
        return $this->application()->exists();
    }
} 