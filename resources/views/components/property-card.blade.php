{{-- Property Card Component --}}
{{-- Usage: <x-property-card :property="$property" /> --}}

<div class="glass-card" id="property-card-{{ $property->id }}">
    {{-- Image Section --}}
    <div class="card-img-wrap">
        @if($property->primaryImage)
            <img src="{{ asset('storage/' . $property->primaryImage->path) }}"
                 alt="{{ $property->title }}"
                 loading="lazy">
        @else
            <img src="{{ asset('images/luxury_sunlit.png') }}"
                 alt="Premium Property Placeholder">
        @endif

        {{-- Top Badges --}}
        <div class="absolute top-4 left-4 flex gap-2 z-10" style="position: absolute; top: 16px; left: 16px; display: flex; gap: 8px;">
            <span style="background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); color: var(--primary); padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                {{ ucfirst($property->type) }}
            </span>
            @if($property->is_featured)
                <span style="background: linear-gradient(135deg, #2563EB 0%, #1d4ed8 100%); color: #fff; padding: 6px 12px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 12px rgba(37,99,235,0.3); display: flex; align-items: center; gap: 4px;">
                    <i class="ph-fill ph-star"></i> Featured
                </span>
            @endif
        </div>

        {{-- Price Badge --}}
        <div class="card-price">
            {{ $property->formatted_price }}
            @if($property->purpose === 'rent')
                <span class="price-period">/mo</span>
            @endif
        </div>
    </div>

    {{-- Content Section --}}
    <div class="card-body">
        <h3 title="{{ $property->title }}">
            <a href="{{ route('properties.show', $property) }}">
                {{ $property->title }}
            </a>
        </h3>

        <div class="location-text">
            <i class="ph-fill ph-map-pin"></i>
            <span>{{ $property->location }}</span>
        </div>

        <div class="card-features">
            @if($property->bedrooms)
                <span title="Bedrooms">
                    <i class="ph-fill ph-bed"></i>
                    {{ $property->bedrooms }} Bed
                </span>
            @endif
            @if($property->bathrooms)
                <span title="Bathrooms">
                    <i class="ph-fill ph-drop"></i>
                    {{ $property->bathrooms }} Bath
                </span>
            @endif
            @if($property->area_sqft)
                <span title="Area">
                    <i class="ph-fill ph-square-half"></i>
                    {{ number_format($property->area_sqft) }} sq.ft
                </span>
            @endif
        </div>

        <div class="card-footer">
            <div class="owner-info">
                @if($property->owner)
                    <div class="owner-avatar">
                        {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 10px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Owner</span>
                        <span style="font-size: 13px; color: var(--text-dark); font-weight: 700;">{{ $property->owner->name }}</span>
                    </div>
                @endif
            </div>
            <a href="{{ route('properties.show', $property) }}" class="btn-text-link" style="color: var(--primary);">
                Explore <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
