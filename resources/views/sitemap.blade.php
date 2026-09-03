<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    {{-- Homepage --}}
    <url>
        <loc>{{ $baseUrl }}/</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- HTML Site Map Directory --}}
    <url>
        <loc>{{ $baseUrl }}/sitemap</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Properties Directory --}}
    <url>
        <loc>{{ $baseUrl }}/properties</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.95</priority>
    </url>

    {{-- Post Free Advertise --}}
    <url>
        <loc>{{ $baseUrl }}/post-free-advertise</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.95</priority>
    </url>
    <url>
        <loc>{{ $baseUrl }}/post-free-property</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.95</priority>
    </url>
    <url>
        <loc>{{ $baseUrl }}/properties/create</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.90</priority>
    </url>

    {{-- Membership Plans --}}
    <url>
        <loc>{{ $baseUrl }}/plans</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>

    {{-- How It Works / Process --}}
    <url>
        <loc>{{ $baseUrl }}/how-it-works</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- App Download Landing Page --}}
    <url>
        <loc>{{ $baseUrl }}/app</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Blog Index --}}
    <url>
        <loc>{{ $baseUrl }}/blog</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Legal Pages --}}
    <url>
        <loc>{{ $baseUrl }}/privacy-policy</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ $baseUrl }}/terms-and-conditions</loc>
        <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Individual Blog Articles --}}
    @if(isset($blogs))
        @foreach ($blogs as $blog)
            <url>
                <loc>{{ $baseUrl }}/blog/{{ is_array($blog) ? $blog['slug'] : $blog->slug }}</loc>
                <lastmod>{{ !empty($blog->updated_at) ? $blog->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif

    {{-- Approved Property Detail Pages with Google Image sitemap tags --}}
    @if(isset($properties))
        @foreach ($properties as $property)
            <url>
                <loc>{{ $baseUrl }}/properties/{{ $property->id }}</loc>
                <lastmod>{{ $property->updated_at ? $property->updated_at->tz('UTC')->toAtomString() : now()->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.85</priority>
                @if($property->primaryImage)
                    <image:image>
                        <image:loc>{{ $property->primaryImage->image_path ? asset($property->primaryImage->image_path) : route('property.image', $property->primaryImage->id) }}</image:loc>
                        <image:title>{{ htmlspecialchars($property->title, ENT_XML1, 'UTF-8') }}</image:title>
                    </image:image>
                @endif
            </url>
        @endforeach
    @endif

    {{-- Programmatic SEO City / Locality Landing Pages --}}
    @if(isset($programmaticUrls))
        @foreach ($programmaticUrls as $url)
            <url>
                <loc>{{ $baseUrl }}{{ $url }}</loc>
                <lastmod>{{ now()->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>0.85</priority>
            </url>
        @endforeach
    @endif
</urlset>