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
        static::saved(fn () => \App\Providers\AppServiceProvider::clearLocationCache());
        static::deleted(fn () => \App\Providers\AppServiceProvider::clearLocationCache());
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
