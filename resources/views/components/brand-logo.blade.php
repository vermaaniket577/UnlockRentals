@props([
    'href' => route('home'),
    'showText' => true,
    'imageClass' => 'h-10 w-auto',
    'textClass' => 'text-lg font-semibold tracking-tight text-zinc-900',
    'accentClass' => 'text-[#2563EB]',
    'alt' => 'UnlockRentals logo',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <img
        src="{{ asset('images/logo.png') }}"
        alt="{{ $alt }}"
        class="{{ $imageClass }}"
    >

    @if($showText)
        <span class="{{ $textClass }}">
            Unlock<span class="{{ $accentClass }}">Rentals</span>
        </span>
    @endif
</a>
