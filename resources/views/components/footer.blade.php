{{-- Footer Component --}}
<footer class="bg-white border-t border-stone-200/50 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <x-brand-logo
                    href="{{ route('home') }}"
                    class="mb-4"
                    imageClass="h-10 w-auto"
                    textClass="text-xl font-medium tracking-tight text-zinc-900"
                    accentClass="text-[#2563EB]"
                />
                <p class="text-sm text-zinc-500 leading-relaxed mb-6">
                    Find your perfect rental property. Browse houses and shops from verified owners across all locations.
                </p>
                <div class="flex gap-3">
                    <a href="{{ $site_settings['social_twitter'] ?? '#' }}" target="_blank" class="w-10 h-10 bg-stone-50 hover:bg-[#2563EB]/10 border border-stone-200/50 rounded-sm flex items-center justify-center text-zinc-500 hover:text-[#2563EB] transition-all">
                        <i class="ph ph-twitter-logo"></i>
                    </a>
                    <a href="{{ $site_settings['social_facebook'] ?? '#' }}" target="_blank" class="w-10 h-10 bg-stone-50 hover:bg-[#2563EB]/10 border border-stone-200/50 rounded-sm flex items-center justify-center text-zinc-500 hover:text-[#2563EB] transition-all">
                        <i class="ph ph-facebook-logo"></i>
                    </a>
                    <a href="{{ $site_settings['social_instagram'] ?? '#' }}" target="_blank" class="w-10 h-10 bg-stone-50 hover:bg-[#2563EB]/10 border border-stone-200/50 rounded-sm flex items-center justify-center text-zinc-500 hover:text-[#2563EB] transition-all">
                        <i class="ph ph-instagram-logo"></i>
                    </a>
                    <a href="{{ $site_settings['social_linkedin'] ?? '#' }}" target="_blank" class="w-10 h-10 bg-stone-50 hover:bg-[#2563EB]/10 border border-stone-200/50 rounded-sm flex items-center justify-center text-zinc-500 hover:text-[#2563EB] transition-all">
                        <i class="ph ph-linkedin-logo"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-5">Explore</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('properties.index') }}" class="text-sm text-zinc-500 hover:text-[#2563EB] transition-colors">All Properties</a></li>
                    <li><a href="{{ route('properties.index', ['type' => 'house']) }}" class="text-sm text-zinc-500 hover:text-[#2563EB] transition-colors">Houses for Rent</a></li>
                    <li><a href="{{ route('properties.index', ['type' => 'shop']) }}" class="text-sm text-zinc-500 hover:text-[#2563EB] transition-colors">Shops for Rent</a></li>
                </ul>
            </div>

            {{-- For Owners --}}
            <div>
                <h4 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-5">For Owners</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('register') }}" class="text-sm text-zinc-500 hover:text-[#2563EB] transition-colors">List Your Property</a></li>
                    <li><a href="{{ route('login') }}" class="text-sm text-zinc-500 hover:text-[#2563EB] transition-colors">Owner Dashboard</a></li>
                    <li><a href="#" class="text-sm text-zinc-500 hover:text-[#2563EB] transition-colors">Pricing Plans</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-5">Contact</h4>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-sm text-zinc-500">
                        <i class="ph ph-envelope text-[#2563EB]"></i>
                        {{ $site_settings['site_email'] ?? 'support@unlockrentals.com' }}
                    </li>
                    <li class="flex items-center gap-2 text-sm text-zinc-500">
                        <i class="ph ph-phone text-[#2563EB]"></i>
                        {{ $site_settings['site_phone'] ?? '+91 7974164274' }}
                    </li>
                    <li class="flex items-center gap-2 text-sm text-zinc-500">
                        <i class="ph ph-map-pin text-[#2563EB]"></i>
                        {{ $site_settings['site_address'] ?? 'Mumbai, India' }}
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="mt-12 pt-8 border-t border-stone-200/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-zinc-500">&copy; {{ date('Y') }} UnlockRentals. All rights reserved.</p>
            <div class="flex gap-6 text-sm text-zinc-500">
                <a href="#" class="hover:text-[#2563EB] transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-[#2563EB] transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
