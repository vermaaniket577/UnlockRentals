<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_name',
        'author_role',
        'author_avatar',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category',
        'tags',
        'read_time',
        'is_featured',
        'is_published',
        'published_at',
        'views_count',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * The author user relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for published blogs only
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope for featured blogs
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeCategory($query, $category)
    {
        if (!empty($category) && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $term)
    {
        if (!empty($term)) {
            $term = trim($term);
            return $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('excerpt', 'like', "%{$term}%")
                  ->orWhere('content', 'like', "%{$term}%")
                  ->orWhere('category', 'like', "%{$term}%");
            });
        }
        return $query;
    }

    /**
     * Get the cover image URL (handles external URL, storage file path, or default fallback)
     */
    public function getCoverImageUrlAttribute()
    {
        if (empty($this->image)) {
            return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Get author display name
     */
    public function getAuthorDisplayNameAttribute()
    {
        if (!empty($this->author_name)) {
            return $this->author_name;
        }

        if ($this->user) {
            return $this->user->name;
        }

        return 'UnlockRentals Editorial';
    }

    /**
     * Get author role
     */
    public function getAuthorRoleTitleAttribute()
    {
        if (!empty($this->author_role)) {
            return $this->author_role;
        }

        return 'Real Estate Advisor';
    }

    /**
     * Get author avatar URL
     */
    public function getAuthorAvatarUrlAttribute()
    {
        if (!empty($this->author_avatar)) {
            if (str_starts_with($this->author_avatar, 'http://') || str_starts_with($this->author_avatar, 'https://')) {
                return $this->author_avatar;
            }
            return asset('storage/' . $this->author_avatar);
        }

        $name = urlencode($this->author_display_name);
        return "https://ui-avatars.com/api/?name={$name}&background=2563EB&color=fff&rounded=true&bold=true";
    }

    /**
     * Get estimated read time (auto calculates if not explicitly set)
     */
    public function getEstimatedReadTimeAttribute()
    {
        if (!empty($this->read_time)) {
            return $this->read_time;
        }

        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        $minutes = max(1, (int) ceil($wordCount / 200));
        return "{$minutes} min read";
    }

    /**
     * Formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('M d, Y') : now()->format('M d, Y');
    }

    /**
     * Increment views count safely
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Boot method to auto-generate unique slug if not supplied
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->title);
            }
            if ($blog->is_published && empty($blog->published_at)) {
                $blog->published_at = now();
            }
        });
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
