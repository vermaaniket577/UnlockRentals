<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = ['code', 'name'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::saved(fn () => \App\Providers\AppServiceProvider::clearLocationCache());
        static::deleted(fn () => \App\Providers\AppServiceProvider::clearLocationCache());
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
