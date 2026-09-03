@auth
{{-- Session Inactivity Warning Modal --}}
<div id="idle-warning-modal" class="fixed inset-0 z-[999999] hidden items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 animate-fade-in" style="display: none;">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl shadow-inner">
            <i class="ph-bold ph-hourglass-medium animate-pulse"></i>
        </div>
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Are you still there?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
            You've been inactive for a while. For your security, you will be automatically logged out in <span id="idle-countdown" class="font-extrabold text-rose-500">60</span> seconds.
        </p>
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-2.5">
            <button type="button" id="btn-idle-stay" class="w-full sm:w-auto flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-md active:scale-95">
                Stay Logged In
            </button>
            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl transition-all">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    // 15 minutes total timeout (900,000 ms), warning appears at 14 minutes (840,000 ms)
    const IDLE_TIMEOUT_MS = 15 * 60 * 1000;
    const WARNING_TIME_MS = 14 * 60 * 1000;
    const COUNTDOWN_SECONDS = 60;
    const STORAGE_KEY = 'ur_last_user_activity';

    let warningTimer = null;
    let logoutTimer = null;
    let countdownInterval = null;

    function resetActivity() {
        const now = Date.now();
        localStorage.setItem(STORAGE_KEY, now.toString());
        hideWarning();
        scheduleTimers();
    }

    function scheduleTimers() {
        if (warningTimer) clearTimeout(warningTimer);
        if (logoutTimer) clearTimeout(logoutTimer);

        const lastActivity = parseInt(localStorage.getItem(STORAGE_KEY) || Date.now(), 10);
        const elapsed = Date.now() - lastActivity;

        if (elapsed >= IDLE_TIMEOUT_MS) {
            performAutoLogout();
            return;
        }

        const remainingToWarning = Math.max(0, WARNING_TIME_MS - elapsed);
        const remainingToLogout = Math.max(0, IDLE_TIMEOUT_MS - elapsed);

        warningTimer = setTimeout(() => {
            showWarning();
        }, remainingToWarning);

        logoutTimer = setTimeout(() => {
            performAutoLogout();
        }, remainingToLogout);
    }

    function showWarning() {
        const modal = document.getElementById('idle-warning-modal');
        const countdownEl = document.getElementById('idle-countdown');
        if (!modal) return;

        modal.style.display = 'flex';
        modal.classList.remove('hidden');

        let remaining = COUNTDOWN_SECONDS;
        if (countdownEl) countdownEl.textContent = remaining;

        if (countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            remaining--;
            if (countdownEl) countdownEl.textContent = remaining;
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                performAutoLogout();
            }
        }, 1000);
    }

    function hideWarning() {
        const modal = document.getElementById('idle-warning-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }

    function performAutoLogout() {
        hideWarning();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        fetch('{{ route("logout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        }).finally(() => {
            localStorage.removeItem(STORAGE_KEY);
            window.location.href = '{{ route("login") }}?inactivity=1';
        });
    }

    // Throttled activity detection on any interaction
    let throttleTimeout = null;
    function onUserActivity() {
        if (!throttleTimeout) {
            throttleTimeout = setTimeout(() => {
                throttleTimeout = null;
                resetActivity();
            }, 3000); // Check/update at most once every 3 seconds
        }
    }

    // Capture standard user interactions
    const events = ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'];
    events.forEach(ev => window.addEventListener(ev, onUserActivity, { passive: true }));

    // Synchronize across multiple open browser tabs
    window.addEventListener('storage', function(e) {
        if (e.key === STORAGE_KEY) {
            hideWarning();
            scheduleTimers();
        }
    });

    const stayBtn = document.getElementById('btn-idle-stay');
    if (stayBtn) {
        stayBtn.addEventListener('click', function() {
            resetActivity();
        });
    }

    // Initialize timer
    resetActivity();
})();
</script>
@endauth
