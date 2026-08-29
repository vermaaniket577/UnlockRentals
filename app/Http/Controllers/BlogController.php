<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Blog listing page with category filter, search & pagination.
     */
    public function index(Request $request)
    {
        $query = Blog::published()->with('user');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->category($request->category);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Distinct published categories
        $categories = Blog::published()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        // Primary featured post for hero banner (if on page 1 without active search)
        $featuredPost = null;
        if (!$request->filled('search') && (!$request->filled('category') || $request->category === 'all') && ($request->get('page', 1) == 1)) {
            $featuredPost = Blog::published()
                ->featured()
                ->latest('published_at')
                ->first();

            if (!$featuredPost) {
                $featuredPost = Blog::published()->latest('published_at')->first();
            }
        }

        // Paginate results
        $posts = $query->latest('published_at')->paginate(9)->withQueryString();

        return view('blog.index', compact('posts', 'categories', 'featuredPost'));
    }

    /**
     * Single blog post reading view.
     */
    public function show($slug)
    {
        // Allow administrators to preview draft articles
        if (auth()->check() && auth()->user()->isAdmin()) {
            $post = Blog::where('slug', $slug)->first();
        } else {
            $post = Blog::published()->where('slug', $slug)->first();
        }

        if (!$post) {
            abort(404, 'Blog post not found.');
        }

        // Increment view count
        $post->incrementViews();

        // Related posts in same category, fallback to latest
        $relatedPosts = Blog::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $needed = 3 - $relatedPosts->count();
            $excludeIds = $relatedPosts->pluck('id')->push($post->id)->all();
            $fallbackPosts = Blog::published()
                ->whereNotIn('id', $excludeIds)
                ->latest('published_at')
                ->take($needed)
                ->get();
            $relatedPosts = $relatedPosts->concat($fallbackPosts);
        }

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
