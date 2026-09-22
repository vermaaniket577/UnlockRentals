<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class District extends Model
{
    protected $fillable = ['state_id', 'name', 'slug'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('indian_location_data'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('indian_location_data'));
    }

    public function getSlugAttribute(): string
    {
        return !empty($this->attributes['slug']) 
            ? $this->attributes['slug'] 
            : Str::slug($this->name);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }
}
