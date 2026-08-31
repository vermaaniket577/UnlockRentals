@props([
    'href' => url('/'),
    'showText' => true,
    'imageClass' => 'h-9 w-auto',
    'textClass' => 'text-lg font-bold tracking-tight text-zinc-900 dark:text-white',
    'accentClass' => 'text-[#2563EB]',
    'alt' => 'UnlockRentals logo',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 group']) }} title="{{ $showText ? 'UnlockRentals' : '' }}">
    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white flex items-center justify-center p-1.5 shadow-sm shadow-blue-950/20 ring-1 ring-slate-900/10 dark:ring-white/25 flex-shrink-0 transition-transform duration-200 group-hover:scale-105">
        <img
            src="{{ asset('images/logo-icon.png') }}"
            alt="{{ $alt }}"
            title="{{ $alt }}"
            class="w-full h-full object-contain"
            fetchpriority="high"
            decoding="async"
        >
    </div>

    @if($showText)
        <span class="{{ $textClass }}">
            Unlock<span class="{{ $accentClass }}">Rentals</span>
        </span>
    @endif
</a>
