<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Homepage --}}
    <url>
        <loc>{{ $baseUrl }}/</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Properties Directory --}}
    <url>
        <loc>{{ $baseUrl }}/properties</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Plans --}}
    <url>
        <loc>{{ $baseUrl }}/plans</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- How It Works / Process --}}
    <url>
        <loc>{{ $baseUrl }}/how-it-works</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Blog Index --}}
    <url>
        <loc>{{ $baseUrl }}/blog</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>

    {{-- Individual Blog Articles --}}
    @if(isset($blogs))
        @foreach ($blogs as $blog)
            <url>
                <loc>{{ $baseUrl }}/blog/{{ $blog['slug'] }}</loc>
                <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.75</priority>
            </url>
        @endforeach
    @endif

    {{-- Approved Property Detail Pages --}}
    @foreach ($properties as $property)
        <url>
            <loc>{{ $baseUrl }}/properties/{{ $property->id }}</loc>
            <lastmod>{{ $property->updated_at ? $property->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    {{-- Programmatic SEO City / Locality Landing Pages --}}
    @foreach ($programmaticUrls as $url)
        <url>
            <loc>{{ $baseUrl }}{{ $url }}</loc>
            <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.85</priority>
        </url>
    @endforeach
</urlset>