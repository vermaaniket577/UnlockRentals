<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value. Reads from the in-memory settings cache first.
     */
    public static function get($key, $default = null)
    {
        // Use the cached settings array loaded at boot time
        $settings = Cache::get('site_settings', []);
        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        // Fallback: direct DB query (cold cache only)
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
