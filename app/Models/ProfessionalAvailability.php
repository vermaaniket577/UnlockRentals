<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalAvailability extends Model
{
    use HasFactory;

    protected $table = 'professional_availability';

    protected $fillable = [
        'professional_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Parent Professional.
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    /**
     * Human-readable day label.
     */
    public function getDayNameAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }
}
