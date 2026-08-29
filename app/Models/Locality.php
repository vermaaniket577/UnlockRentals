<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locality extends Model
{
    protected $fillable = ['district_id', 'name'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('indian_location_data'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('indian_location_data'));
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
