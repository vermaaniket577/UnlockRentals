<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorDailyStatistic extends Model
{
    protected $fillable = [
        'date',
        'city',
        'source',
        'visitors_count',
        'sessions_count',
        'page_views_count',
        'property_views_count',
        'leads_count',
        'whatsapp_clicks_count',
        'enquiries_count',
        'scheduled_visits_count',
        'conversions_count',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'visitors_count' => 'integer',
            'sessions_count' => 'integer',
            'page_views_count' => 'integer',
            'property_views_count' => 'integer',
            'leads_count' => 'integer',
            'whatsapp_clicks_count' => 'integer',
            'enquiries_count' => 'integer',
            'scheduled_visits_count' => 'integer',
            'conversions_count' => 'integer',
        ];
    }
}
