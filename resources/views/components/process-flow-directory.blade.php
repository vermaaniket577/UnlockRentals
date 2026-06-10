{{-- ============================================================
     UNLOCK RENTALS — PREMIUM PROCESS FLOW (APPLE/STRIPE STYLE)
     ============================================================ --}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

.ur-process {
    padding: 10rem 0;
    background: #ffffff;
    font-family: 'Outfit', sans-serif;
    position: relative;
    overflow: hidden;
}

/* Ambient Background Elements */
.ur-process__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    z-index: 1;
    pointer-events: none;
    opacity: 0.4;
}
.ur-process__orb--1 {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
    top: -10%;
    left: -10%;
}
.ur-process__orb--2 {
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    bottom: -15%;
    right: -10%;
}

.ur-process__container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}

.ur-process__header {
    text-align: center;
    max-width: 45rem;
    margin: 0 auto 8rem;
}

.ur-process__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: rgba(37, 99, 235, 0.05);
    border: 1px solid rgba(37, 99, 235, 0.1);
    border-radius: 100px;
    color: #2563eb;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.ur-process__title {
    font-size: 3.5rem;
    font-weight: 900;
    color: #111827;
    letter-spacing: -0.04em;
    line-height: 1.1;
}

.ur-process__title span {
    color: #2563eb;
}

/* Process Grid & Connected Line */
.ur-process__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 4rem;
    position: relative;
}

@media (min-width: 1024px) {
    .ur-process__grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 6rem;
    }
}

.ur-process__line {
    display: none;
}

@media (min-width: 1024px) {
    .ur-process__line {
        display: block;
        position: absolute;
        top: 64px;
        left: 15%;
        right: 15%;
        height: 1px;
        background: linear-gradient(to right, 
            rgba(37, 99, 235, 0.05) 0%, 
            rgba(37, 99, 235, 0.3) 50%, 
            rgba(37, 99, 235, 0.05) 100%);
        z-index: 1;
    }
    
    .ur-process__line-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0%;
        background: #2563eb;
        box-shadow: 0 0 15px rgba(37, 99, 235, 0.5);
        animation: ur-line-fill 4s ease-in-out infinite alternate;
    }
}

@keyframes ur-line-fill {
    0% { width: 0%; left: 0; }
    100% { width: 100%; left: 0; }
}

/* Process Step Cards */
.ur-process__step {
    position: relative;
    z-index: 5;
    text-align: center;
}

.ur-process__visual {
    width: 128px;
    height: 128px;
    margin: 0 auto 3rem;
    position: relative;
}

.ur-process__card {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 2px solid #bfdbfe;
    border-radius: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #2563eb;
    box-shadow: 
        0 20px 40px -10px rgba(37, 99, 235, 0.1),
        0 8px 16px -4px rgba(37, 99, 235, 0.05);
    transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.ur-process__step:hover .ur-process__card {
    transform: translateY(-1rem) rotate(4deg);
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    border-color: #2563eb;
    box-shadow: 
        0 40px 80px -20px rgba(37, 99, 235, 0.4),
        0 20px 40px -10px rgba(37, 99, 235, 0.2);
}

.ur-process__step:hover .ur-process__card svg {
    filter: brightness(0) invert(1);
}

.ur-process__number {
    position: absolute;
    top: -0.75rem;
    right: -0.75rem;
    width: 2.5rem;
    height: 2.5rem;
    background: #2563eb;
    color: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 900;
    border: 4px solid #ffffff;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    z-index: 10;
    transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.ur-process__step:hover .ur-process__number {
    transform: scale(1.1) translateY(-0.5rem);
    background: #111827;
}

.ur-process__step-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.ur-process__step-desc {
    font-size: 1rem;
    color: #6b7280;
    line-height: 1.6;
    font-weight: 400;
    max-width: 18rem;
    margin: 0 auto;
}

/* Floating Particles */
.ur-process__particle {
    position: absolute;
    width: 8px;
    height: 8px;
    background: #2563eb;
    border-radius: 50%;
    opacity: 0.15;
    pointer-events: none;
    animation: ur-particle-float 10s infinite linear;
}

@keyframes ur-particle-float {
    0% { transform: translate(0, 0) rotate(0); }
    100% { transform: translate(100px, -100px) rotate(360deg); }
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
                'icon_svg' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 3.75A6.75 6.75 0 1010.5 17.25 6.75 6.75 0 0010.5 3.75zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" /></svg>',
            ],
            (object) [
                'step_number' => '02',
                'title' => 'Concierge',
                'description' => 'Connect with premium owners or leverage our elite viewing concierge support.',
                'icon_svg' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" /></svg>',
            ],
            (object) [
                'step_number' => '03',
                'title' => 'Finalize',
                'description' => 'Complete secure digital legalities and move into your handpicked luxury space.',
                'icon_svg' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25c0 1.942.868 3.659 2.146 4.815a1 1 0 01.354.757v4.928a2 2 0 002 2h3a2 2 0 002-2v-1h2a2 2 0 002-2v-1h2a2 2 0 002-2v-2.5a.75.75 0 00-.75-.75h-7.766a1 1 0 01-.84-.457 5.25 5.25 0 00-6.142-2.043zM10.5 5.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>',
            ]
        ]);
    }
@endphp

<section class="ur-process" id="how-it-works">
    {{-- Background Glows --}}
    <div class="ur-process__orb ur-process__orb--1"></div>
    <div class="ur-process__orb ur-process__orb--2"></div>

    {{-- Floating Decorative Particles --}}
    <div class="ur-process__particle" style="top: 20%; left: 5%;"></div>
    <div class="ur-process__particle" style="top: 60%; left: 85%; animation-delay: -2s;"></div>
    <div class="ur-process__particle" style="top: 80%; left: 15%; animation-delay: -5s;"></div>

    <div class="ur-process__container">
        
        <header class="ur-process__header">
            <div class="ur-process__badge">
                <i class="ph-bold ph-lightning"></i>
                Simplified Journey
            </div>
            <h2 class="ur-process__title">
                The Standard of <br><span>Excellence</span> in Moving
            </h2>
        </header>

        <div class="ur-process__grid">
            {{-- Connecting Line --}}
            <div class="ur-process__line">
                <div class="ur-process__line-progress"></div>
            </div>

            @foreach($processSteps as $index => $step)
                <div class="ur-process__step" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="ur-process__visual">
                        <div class="ur-process__card">
                            @if(str_starts_with(trim($step->icon_svg), '<svg'))
                                {!! $step->icon_svg !!}
                            @elseif($step->icon_svg)
                                <i class="{{ $step->icon_svg }} text-[2.5rem] text-blue-600"></i>
                            @else
                                <i class="ph ph-circle text-[2.5rem] text-blue-600"></i>
                            @endif
                        </div>
                        <div class="ur-process__number">{{ $step->step_number ?? sprintf('%02d', $index + 1) }}</div>
                    </div>
                    <h3 class="ur-process__step-title">{{ $step->title }}</h3>
                    <p class="ur-process__step-desc">
                        {{ $step->description }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</section>

{{-- SEO Resource Directory (Kept from original for structure) --}}
@include('components.resource-directory')
