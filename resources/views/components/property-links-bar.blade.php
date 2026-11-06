{{-- ============================================================
     UNLOCK RENTALS — PROPERTY LINKS BAR (VANILLA CSS VERSION)
     ============================================================ --}}

<style>
.ur-links-bar {
    background-color: #fafaf9;
    border-top: 1px solid #e7e5e4;
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
    overflow: hidden;
    font-family: 'Inter', sans-serif;
}

.ur-links-bar__container {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1rem;
    padding-right: 1rem;
}

.ur-links-bar__flex {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 2.5rem;
}

.ur-links-bar__link {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: none;
    letter-spacing: normal;
    text-decoration: none;
    transition: all 0.2s;
    position: relative;
    padding-bottom: 0.25rem;
}

.ur-links-bar__link:hover { color: #0f172a; }

.ur-links-bar__link--active {
    color: #2563eb;
    font-weight: 700;
}

.ur-links-bar__link--active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #2563eb;
}
</style>

<div class="ur-links-bar" id="property-links-bar">
    <div class="ur-links-bar__container">
        <div class="ur-links-bar__flex">
            @php
                $links = [
                    ['label' => 'Flats for Sale', 'url' => route('properties.index', ['purpose' => 'sale', 'type' => 'apartment']), 'active' => true],
                    ['label' => 'Flats for Rent', 'url' => route('properties.index', ['purpose' => 'rent', 'type' => 'apartment']), 'active' => false],
                    ['label' => 'PG / Hostels', 'url' => route('properties.index', ['type' => 'pg']), 'active' => false],
                    ['label' => 'Commercial Properties', 'url' => route('properties.index', ['type' => 'commercial']), 'active' => false],
                    ['label' => 'New Projects', 'url' => route('properties.index', ['purpose' => 'sale']), 'active' => false],
                    ['label' => 'Independent Houses', 'url' => route('properties.index', ['type' => 'house']), 'active' => false],
                    ['label' => 'Plots / Land', 'url' => route('properties.index', ['type' => 'plot']), 'active' => false],
                ];
            @endphp
            @foreach($links as $link)
            <a href="{{ $link['url'] }}" class="ur-links-bar__link {{ $link['active'] ? 'ur-links-bar__link--active' : '' }}" aria-label="{{ $link['label'] }}">
                {{ $link['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>
