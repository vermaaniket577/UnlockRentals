{{-- ============================================================
     UNLOCK RENTALS — PREMIUM HOW IT WORKS (LUXURY REGISTRY)
     ============================================================ --}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap');

.ur-how {
    padding: 10rem 0;
    background: #ffffff;
    position: relative;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', sans-serif;
}

/* Background elements */
.ur-how__blob {
    position: absolute;
    width: 50rem;
    height: 50rem;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.04) 0%, transparent 70%);
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
}
.ur-how__blob--1 { top: -10%; left: -20%; }
.ur-how__blob--2 { bottom: -10%; right: -20%; }

.ur-how__grid-bg {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(#e5e7eb 0.5px, transparent 0.5px);
    background-size: 40px 40px;
    opacity: 0.2;
    mask-image: radial-gradient(circle at center, black, transparent 80%);
    z-index: 1;
}

.ur-how__container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}

/* ─── HEADER ────────────────────────────── */
.ur-how__header {
    text-align: center;
    max-width: 50rem;
    margin: 0 auto 8rem;
}

.ur-how__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: #eff6ff;
    color: #2563eb;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    margin-bottom: 2rem;
}

.ur-how__title {
    font-size: 3rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.04em;
    line-height: 1.1;
}

@media (min-width: 768px) {
    .ur-how__title { font-size: 4.5rem; }
}

.ur-how__title span {
    background: linear-gradient(135deg, #2563eb, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ─── JOURNEY GRID ──────────────────────── */
.ur-how__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 4rem;
    position: relative;
}

@media (min-width: 1024px) {
    .ur-how__grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 3rem;
    }
}

/* Connecting lines (Desktop only) */
.ur-how__line {
    display: none;
    position: absolute;
    top: 4rem;
    left: 12%;
    right: 12%;
    height: 2px;
    background: linear-gradient(to right, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
    z-index: 0;
}

.ur-how__line-progress {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, #2563eb, #6366f1);
    transform-origin: left;
    transform: scaleX(0);
    transition: transform 1.5s ease;
}

.ur-how:hover .ur-how__line-progress { transform: scaleX(1); }

@media (min-width: 1024px) {
    .ur-how__line { display: block; }
}

/* ─── STEP CARD ─────────────────────────── */
.ur-how__step {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.ur-how__icon-wrap {
    position: relative;
    margin-bottom: 2.5rem;
}

.ur-how__icon-box {
    width: 8rem;
    height: 8rem;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 2.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    box-shadow: 
        0 8px 24px rgba(37, 99, 235, 0.08),
        0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.ur-how__step:hover .ur-how__icon-box {
    transform: translateY(-10px) rotate(5deg);
    background: #0f172a;
    border-color: #0f172a;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
}

.ur-how__icon-box svg {
    width: 64px !important;
    height: 64px !important;
    transition: all 0.4s ease;
    display: block;
}

.ur-how__icon-box svg path {
    fill: #2563eb;
    transition: fill 0.4s ease;
}

.ur-how__step:hover .ur-how__icon-box svg path {
    fill: #ffffff;
}

.ur-how__icon-box i {
    font-size: 3.5rem;
    color: #2563eb;
    transition: all 0.4s ease;
}

.ur-how__step:hover .ur-how__icon-box i {
    color: #ffffff;
}

.ur-how__icon-svg {
    width: 64px !important;
    height: 64px !important;
    object-fit: contain;
    transition: all 0.4s ease;
    display: block;
    mix-blend-mode: multiply;
}

.ur-how__step:hover .ur-how__icon-svg {
    filter: invert(1) grayscale(1) brightness(200%);
    mix-blend-mode: screen;
    transform: scale(1.05);
}

.ur-how__number {
    position: absolute;
    top: -0.75rem;
    right: -0.75rem;
    width: 2.5rem;
    height: 2.5rem;
    background: #2563eb;
    color: #ffffff;
    border: 4px solid #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 800;
    box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
}

.ur-how__s-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 1.25rem;
    letter-spacing: -0.02em;
}

.ur-how__s-desc {
    font-size: 1.125rem;
    color: #64748b;
    line-height: 1.6;
    font-weight: 400;
    max-width: 20rem;
}

.ur-how__step:hover .ur-how__s-title { color: #2563eb; }

/* Glowing point underneath */
.ur-how__point {
    position: absolute;
    bottom: -1rem;
    left: 50%;
    transform: translateX(-50%);
    width: 0.5rem;
    height: 0.5rem;
    background: #2563eb;
    border-radius: 50%;
    opacity: 0;
    transition: all 0.3s;
}

.ur-how__step:hover .ur-how__point {
    opacity: 1;
    transform: translateX(-50%) translateY(10px);
    box-shadow: 0 0 15px #2563eb;
}
</style>

@php
    $processSteps = \Illuminate\Support\Facades\Cache::remember('home_process_steps', 3600, function () {
        return \App\Models\ProcessStep::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    });

    if ($processSteps->isEmpty()) {
        $processSteps = collect([
            (object) [
                'step_number' => '01',
                'title' => 'Discover',
                'description' => "Explore India's most exclusive rental registry with intelligent lifestyle filters.",
                'icon_svg' => 'images/icons/discover.png',
            ],
            (object) [
                'step_number' => '02',
                'title' => 'Concierge',
                'description' => 'Connect with premium owners or leverage our elite viewing concierge support.',
                'icon_svg' => 'images/icons/concierge.png',
            ],
            (object) [
                'step_number' => '03',
                'title' => 'Finalize',
                'description' => 'Complete secure digital legalities and move into your handpicked luxury space.',
                'icon_svg' => 'images/icons/finalize.png',
            ]
        ]);
    }
@endphp

<section class="ur-how" id="how-it-works">
    <div class="ur-how__blob ur-how__blob--1"></div>
    <div class="ur-how__blob ur-how__blob--2"></div>
    <div class="ur-how__grid-bg"></div>

    <div class="ur-how__container">
        
        <div class="ur-how__header">
            <span class="ur-how__badge">Seamless Experience</span>
            <h2 class="ur-how__title">Your Journey to <br><span>Excellence</span></h2>
        </div>

        <div class="ur-how__grid">
            {{-- Connected Line --}}
            <div class="ur-how__line">
                <div class="ur-how__line-progress"></div>
            </div>

            @foreach($processSteps as $index => $step)
                <div class="ur-how__step">
                    <div class="ur-how__icon-wrap">
                        <div class="ur-how__icon-box">
                            @if(str_starts_with(trim($step->icon_svg), '<svg'))
                                {!! $step->icon_svg !!}
                            @elseif(Str::endsWith($step->icon_svg, ['.png', '.jpg', '.jpeg', '.gif', '.svg']) || str_contains($step->icon_svg, '/'))
                                <img class="ur-how__icon-svg" src="{{ asset($step->icon_svg) }}" alt="{{ $step->title }}" width="64" height="64">
                            @elseif($step->icon_svg)
                                <i class="{{ $step->icon_svg }}"></i>
                            @else
                                <i class="ph ph-circle"></i>
                            @endif
                        </div>
                        <span class="ur-how__number">{{ $step->step_number ?? sprintf('%02d', $index + 1) }}</span>
                        <div class="ur-how__point"></div>
                    </div>
                    <h4 class="ur-how__s-title">{{ $step->title }}</h4>
                    <p class="ur-how__s-desc">{{ $step->description }}</p>
                </div>
            @endforeach
        </div>

    </div>
</section>
