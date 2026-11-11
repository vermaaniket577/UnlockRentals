@props([
    'href' => url('/'),
    'showText' => true,
    'imageClass' => 'h-9 w-auto',
    'textClass' => 'text-[18px] sm:text-xl font-extrabold tracking-tight text-slate-900 dark:text-white',
    'accentClass' => 'text-amber-500',
    'alt' => 'UnlockRentals logo',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 group']) }} title="{{ $showText ? 'UnlockRentals' : '' }}">
    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white flex items-center justify-center p-1 shadow-xs border border-slate-200/90 dark:border-slate-700/80 flex-shrink-0 transition-transform duration-200 group-hover:scale-105">
        <picture>
            <source srcset="{{ asset('images/logo-icon.webp') }}" type="image/webp">
            <img
                src="{{ asset('images/logo-icon.png') }}"
                alt="{{ $alt }}"
                title="{{ $alt }}"
                width="32"
                height="32"
                class="w-full h-full object-contain"
                fetchpriority="high"
                decoding="async"
            >
        </picture>
    </div>

    @if($showText)
        <div class="flex flex-col justify-center">
            <span class="{{ $textClass }} leading-none">
                Unlock<span class="text-amber-500 font-black">Rentals</span>
            </span>
            <span class="text-[9.5px] sm:text-[10px] font-semibold text-slate-500 dark:text-slate-400 tracking-[0.08em] uppercase mt-1 leading-none">Rent &bull; Buy &bull; Sell</span>
        </div>
    @endif
</a>
