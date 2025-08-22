<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'semester_id',
        'name',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    /**
     * Get the semester that owns the level.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the courses for the level.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the school fees for the level.
     */
    public function schoolFees(): HasMany
    {
        return $this->hasMany(SchoolFee::class);
    }

    /**
     * Get the results for the level.
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
