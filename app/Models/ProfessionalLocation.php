<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalLocation extends Model
{
    use HasFactory;

    protected $table = 'professional_locations';

    protected $fillable = [
        'professional_id',
        'state',
        'district',
        'city',
        'locality',
        'pincode',
        'latitude',
        'longitude',
        'service_radius_km',
    ];

    /**
     * Professional parent.
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }
}
