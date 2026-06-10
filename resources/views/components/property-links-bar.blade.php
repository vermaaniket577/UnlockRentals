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
    font-size: 0.75rem;
    font-weight: 900;
    color: #a1a1aa;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    text-decoration: none;
    transition: all 0.3s;
    position: relative;
    padding-bottom: 0.25rem;
}

.ur-links-bar__link:hover { color: #52525b; }

.ur-links-bar__link--active {
    color: #2563eb;
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
                    ['label' => 'Flats for Sale', 'active' => true],
                    ['label' => 'Flats for Rent', 'active' => false],
                    ['label' => 'PG / Hostels', 'active' => false],
                    ['label' => 'Commercial Properties', 'active' => false],
                    ['label' => 'New Projects', 'active' => false],
                    ['label' => 'Independent Houses', 'active' => false],
                    ['label' => 'Plots / Land', 'active' => false],
                ];
            @endphp
            @foreach($links as $link)
            <a href="#" class="ur-links-bar__link {{ $link['active'] ? 'ur-links-bar__link--active' : '' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>
