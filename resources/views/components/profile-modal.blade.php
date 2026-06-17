@auth
<div id="profile-settings-modal" class="fixed inset-0 z-[10000] hidden flex items-center justify-center overflow-hidden p-4" style="background: rgba(241, 245, 249, 0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
    {{-- Modal Card --}}
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-8 shadow-2xl animate-[successScaleIn_0.4s_cubic-bezier(0.21,1.02,0.73,1)_both]" style="box-shadow: 0 25px 80px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.04);">
        {{-- Top accent gradient line --}}
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

        {{-- Close button --}}
        <button onclick="window.closeProfileModal()" class="absolute top-5 right-5 text-zinc-400 hover:text-zinc-600 transition-colors">
            <i class="ph-bold ph-x text-lg"></i>
        </button>

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="mx-auto w-12 h-12 rounded-2xl bg-blue-50 text-blue-650 flex items-center justify-center mb-3">
                <i class="ph-bold ph-user-gear text-2xl"></i>
            </div>
            <h2 class="text-xl font-extrabold tracking-tight text-slate-900">Profile Settings</h2>
            <p class="text-xs text-slate-500 mt-1 font-medium">Update your account name and mobile number</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5 text-left">
            @csrf
            <div>
                <label for="profile-name" class="block text-xs font-bold text-zinc-450 uppercase tracking-wider mb-2">Full Name</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"><i class="ph ph-user"></i></span>
                    <input type="text" name="name" id="profile-name" value="{{ auth()->user()->name }}" required
                           class="w-full pl-10 pr-4 py-3.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-semibold">
                </div>
            </div>

            <div>
                <label for="profile-phone" class="block text-xs font-bold text-zinc-450 uppercase tracking-wider mb-2">Mobile Number</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"><i class="ph ph-phone"></i></span>
                    <input type="tel" name="phone" id="profile-phone" value="{{ auth()->user()->phone }}" required placeholder="e.g. +91 98765 43210"
                           class="w-full pl-10 pr-4 py-3.5 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-semibold">
                </div>
                <p class="text-[10px] text-zinc-400 mt-1.5 font-medium leading-normal">This phone number will be shared with other verified users when they unlock contact details.</p>
            </div>

            {{-- Submit CTA --}}
            <button type="submit" class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-4 text-sm font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-650 hover:brightness-105 transition-all shadow-lg shadow-blue-500/10 cursor-pointer">
                <i class="ph-bold ph-floppy-disk text-base"></i>
                Save Profile
            </button>
        </form>
    </div>
</div>

<script>
window.openProfileModal = function() {
    const modal = document.getElementById('profile-settings-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
};

window.closeProfileModal = function() {
    const modal = document.getElementById('profile-settings-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

// Close on outside click
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('profile-settings-modal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                window.closeProfileModal();
            }
        });
    }
});
</script>
@endauth
