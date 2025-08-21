<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolFee extends Model
{
    protected $fillable = [
        'programme_id',
        'school_session_id',
        'semester_id',
        'level_id',
        'name',
        'amount',
        'currency',
        'description',
        'is_active',
        'fee_type',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'due_date' => 'date',
    ];

    /**
     * Get the programme that owns the school fee.
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * Get the school session that owns the school fee.
     */
    public function schoolSession(): BelongsTo
    {
        return $this->belongsTo(SchoolSession::class);
    }

    /**
     * Get the semester that owns the school fee.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the level that owns the school fee.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Scope a query to only include active fees.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by fee type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('fee_type', $type);
    }
}
