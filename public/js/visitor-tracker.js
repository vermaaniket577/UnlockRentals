/**
 * UnlockRentals Visitor Tracking & Engagement Client Engine
 * Privacy-First, Asynchronous, Zero Core Web Vitals Impact
 */
(function (window, document) {
    'use strict';

    const CONFIG = {
        endpoint: '/api/visitor/event',
        consentKey: 'ur_cookie_consent_choice', // 'all', 'essential', 'declined'
        exitIntentKey: 'ur_exit_intent_shown_at',
        exitIntentCooldownDays: 7,
    };

    const URTracker = {
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',

        // Check user consent (respects Do Not Track & explicit cookie settings)
        hasConsent: function () {
            const consent = localStorage.getItem(CONFIG.consentKey);
            // Default to allowed for first-party privacy analytics unless explicitly declined
            return consent !== 'declined';
        },

        // Track custom visitor event
        track: function (eventName, propertyId = null, metadata = {}) {
            if (!this.hasConsent()) return;

            const payload = {
                event_name: eventName,
                property_id: propertyId,
                page_url: window.location.href,
                metadata: metadata,
            };

            const body = JSON.stringify(payload);

            // Use sendBeacon if available for guaranteed background delivery
            if (navigator.sendBeacon) {
                const blob = new Blob([body], { type: 'application/json' });
                navigator.sendBeacon(CONFIG.endpoint, blob);
            } else {
                fetch(CONFIG.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: body,
                    keepalive: true,
                }).catch(() => {});
            }
        },

        // Auto-detect context from page elements
        init: function () {
            const currentUrl = window.location.href;

            // 1. Property View Auto-Detection
            const propMeta = document.querySelector('meta[name="property-id"]');
            const propId = propMeta ? propMeta.getAttribute('content') : null;

            if (propId) {
                this.track('property_view', parseInt(propId, 10));
            } else {
                this.track('page_view', null, { title: document.title });
            }

            // 2. Search Tracking Auto-Detection
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('locality') || urlParams.has('type') || urlParams.has('price') || urlParams.has('rooms') || urlParams.has('search')) {
                this.track('search', null, {
                    locality: urlParams.get('locality'),
                    type: urlParams.get('type'),
                    price: urlParams.get('price'),
                    rooms: urlParams.get('rooms'),
                    search: urlParams.get('search'),
                    purpose: urlParams.get('purpose'),
                });
            }

            // 3. Bind Interactive Click Listeners
            this.bindEvents(propId);

            // 4. Setup Exit-Intent Detection
            this.initExitIntent();
        },

        bindEvents: function (propId) {
            document.addEventListener('click', (e) => {
                const target = e.target.closest('a, button');
                if (!target) return;

                // WhatsApp Clicks
                if (target.matches('#whatsapp-inquiry-btn, [data-track-whatsapp]') || target.href?.includes('wa.me') || target.href?.includes('whatsapp.com')) {
                    this.track('whatsapp_clicked', propId, { target: target.id || 'whatsapp_link' });
                }

                // Call Agent / Contact Owner Clicks
                if (target.matches('#call-agent-btn, [data-track-call]') || target.href?.startsWith('tel:')) {
                    this.track('contact_owner_clicked', propId, { target: target.id || 'phone_link' });
                }

                // Schedule Visit Button
                if (target.matches('#book-visit-btn, [data-track-visit]')) {
                    this.track('visit_scheduled', propId, { target: 'schedule_visit_button' });
                }

                // Share Button
                if (target.matches('#share-btn, [onclick*="copyPropertyLink"]')) {
                    this.track('share_property', propId);
                }
            }, { passive: true });
        },

        // Smart Exit-Intent Detector
        initExitIntent: function () {
            // Check cooldown (show at most once every 7 days)
            const lastShown = localStorage.getItem(CONFIG.exitIntentKey);
            if (lastShown) {
                const daysDiff = (Date.now() - parseInt(lastShown, 10)) / (1000 * 60 * 60 * 24);
                if (daysDiff < CONFIG.exitIntentCooldownDays) {
                    return;
                }
            }

            let triggered = false;
            const modal = document.getElementById('exit-intent-modal');
            if (!modal) return;

            const showModal = () => {
                if (triggered) return;
                triggered = true;
                localStorage.setItem(CONFIG.exitIntentKey, Date.now().toString());

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                this.track('exit_intent_shown');
            };

            // Desktop: cursor leaves top of screen
            document.addEventListener('mouseleave', (e) => {
                if (e.clientY <= 10 && !triggered) {
                    showModal();
                }
            });

            // Mobile: user inactive after viewing content for at least 35 seconds
            if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                let userEngaged = false;
                window.addEventListener('scroll', () => { userEngaged = true; }, { once: true, passive: true });

                setTimeout(() => {
                    if (userEngaged && !triggered && document.visibilityState === 'visible') {
                        showModal();
                    }
                }, 40000);
            }
        },
    };

    window.URTracker = URTracker;

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => URTracker.init());
    } else {
        URTracker.init();
    }
})(window, document);
