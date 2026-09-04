<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    protected $table = 'seo_keywords';

    protected $fillable = [
        'keyword',
        'search_intent',
        'category',
        'priority',
        'city',
        'locality',
        'recommended_page',
        'seo_title',
        'meta_description',
        'url_slug',
    ];

    /**
     * Scope for filtering by clean slug (ignoring leading slash).
     */
    public function scopeSlug($query, $slug)
    {
        $clean = '/' . ltrim($slug, '/');
        return $query->where('url_slug', $clean);
    }

    /**
     * Scope for priority.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'High');
    }

    /**
     * Scope for recommended page type.
     */
    public function scopeRecommendedPage($query, $pageType)
    {
        return $query->where('recommended_page', $pageType);
    }
}
