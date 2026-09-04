<?php

namespace App\Services;

use App\Models\SeoKeyword;
use Illuminate\Support\Facades\File;

class SeoKeywordService
{
    protected static ?array $keywordsBySlug = null;
    protected static ?array $allKeywords = null;

    /**
     * Load all keywords from JSON storage or DB.
     */
    protected static function load(): void
    {
        if (self::$keywordsBySlug !== null) {
            return;
        }

        self::$keywordsBySlug = [];
        self::$allKeywords = [];

        $jsonPath = database_path('data/seo_keywords.json');
        if (!File::exists($jsonPath)) {
            $jsonPath = storage_path('app/seo_keywords.json');
        }

        if (File::exists($jsonPath)) {
            $raw = File::get($jsonPath);
            if (str_starts_with($raw, "\xEF\xBB\xBF")) {
                $raw = substr($raw, 3);
            }
            $data = json_decode($raw, true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    $slug = ltrim($item['url_slug'] ?? '', '/');
                    self::$keywordsBySlug[$slug] = $item;
                    self::$allKeywords[] = $item;
                }
                return;
            }
        }

        // Fallback to database if JSON file is missing
        if (\Illuminate\Support\Facades\Schema::hasTable('seo_keywords')) {
            $dbItems = SeoKeyword::all()->toArray();
            foreach ($dbItems as $item) {
                $slug = ltrim($item['url_slug'] ?? '', '/');
                self::$keywordsBySlug[$slug] = $item;
                self::$allKeywords[] = $item;
            }
        }
    }

    /**
     * Find an SEO keyword record by slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        self::load();
        $clean = strtolower(trim(ltrim($slug, '/')));
        return self::$keywordsBySlug[$clean] ?? null;
    }

    /**
     * Get all keyword records.
     */
    public static function getAll(): array
    {
        self::load();
        return self::$allKeywords ?? [];
    }

    /**
     * Get all URL slugs.
     */
    public static function getAllSlugs(): array
    {
        self::load();
        return array_keys(self::$keywordsBySlug ?? []);
    }

    /**
     * Get keywords grouped by recommended page type.
     */
    public static function getByPageType(string $pageType): array
    {
        self::load();
        return array_filter(self::$allKeywords ?? [], function ($item) use ($pageType) {
            return strcasecmp($item['recommended_page'] ?? '', $pageType) === 0;
        });
    }

    /**
     * Get keywords by category.
     */
    public static function getByCategory(string $category): array
    {
        self::load();
        return array_filter(self::$allKeywords ?? [], function ($item) use ($category) {
            return strcasecmp($item['category'] ?? '', $category) === 0;
        });
    }

    /**
     * Get high-priority keywords for footer links and sitemap.
     */
    public static function getHighPriority(int $limit = 50): array
    {
        self::load();
        $filtered = array_filter(self::$allKeywords ?? [], function ($item) {
            return ($item['priority'] ?? '') === 'High';
        });
        return array_slice($filtered, 0, $limit);
    }
}
