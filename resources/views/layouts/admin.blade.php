<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RJ2TX883V4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-RJ2TX883V4');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin CRM - UnlockRentals')</title>

    {{-- Performance & Fonts --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20260831">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192x192.png') }}?v=20260831">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=20260831">

    {{-- Google Fonts: Plus Jakarta Sans & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Phosphor Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">

    {{-- Precompiled Tailwind CSS --}}
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-build.css') }}?v={{ file_exists(public_path('css/tailwind-build.css')) ? filemtime(public_path('css/tailwind-build.css')) : time() }}">
    @endif
    
    {{-- Custom App Styles --}}
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v={{ file_exists(public_path('css/unlock-rental.css')) ? filemtime(public_path('css/unlock-rental.css')) : time() }}&cb=20260920-btn-white">
    <style>
        body { font-family: 'Plus Jakarta Sans', Inter, sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* ===================================================
           HOVER-EXPANDABLE MINI SIDEBAR SYSTEM
           =================================================== */
        .admin-sidebar {
            width: 72px;
            min-width: 72px;
            height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s ease, transform 0.28s ease;
            overflow-x: hidden;
            will-change: width, transform;
        }

        .admin-sidebar-spacer {
            width: 72px;
            min-width: 72px;
            flex-shrink: 0;
            transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hover Expansion or Pinned State (Desktop) */
        @media (min-width: 1024px) {
            .admin-sidebar:hover,
            .admin-sidebar.is-pinned {
                width: 260px;
                min-width: 260px;
                box-shadow: 14px 0 35px -5px rgba(2, 6, 23, 0.7), 4px 0 15px -4px rgba(2, 6, 23, 0.5);
            }

            .admin-sidebar.is-pinned + .admin-sidebar-spacer {
                width: 260px;
                min-width: 260px;
            }
        }

        /* Mobile Drawer State */
        @media (max-width: 1023px) {
            .admin-sidebar {
                width: 260px !important;
                min-width: 260px !important;
                transform: translateX(-100%);
            }
            .admin-sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 20px 0 50px rgba(0, 0, 0, 0.8);
            }
            .admin-sidebar-spacer {
                display: none;
            }
        }

        /* Elements visibility toggling during collapsed vs hover */
        .sidebar-item-label,
        .sidebar-badge-count,
        .sidebar-brand-text,
        .sidebar-footer-text,
        .sidebar-pin-btn {
            opacity: 0;
            max-width: 0;
            visibility: hidden;
            white-space: nowrap;
            overflow: hidden;
            display: inline-block;
            transition: opacity 0.18s ease, max-width 0.26s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.18s;
        }

        .admin-sidebar:hover .sidebar-item-label,
        .admin-sidebar.is-pinned .sidebar-item-label,
        .admin-sidebar.mobile-open .sidebar-item-label,
        .admin-sidebar:hover .sidebar-brand-text,
        .admin-sidebar.is-pinned .sidebar-brand-text,
        .admin-sidebar.mobile-open .sidebar-brand-text,
        .admin-sidebar:hover .sidebar-footer-text,
        .admin-sidebar.is-pinned .sidebar-footer-text,
        .admin-sidebar.mobile-open .sidebar-footer-text,
        .admin-sidebar:hover .sidebar-pin-btn,
        .admin-sidebar.is-pinned .sidebar-pin-btn {
            opacity: 1;
            max-width: 180px;
            visibility: visible;
        }

        .admin-sidebar:hover .sidebar-badge-count,
        .admin-sidebar.is-pinned .sidebar-badge-count,
        .admin-sidebar.mobile-open .sidebar-badge-count {
            opacity: 1;
            max-width: 60px;
            visibility: visible;
        }

        /* Section Headings */
        .sidebar-group-title {
            opacity: 0;
            height: 0;
            margin: 0;
            overflow: hidden;
            transition: opacity 0.18s ease, height 0.2s ease, margin 0.2s ease;
        }

        .admin-sidebar:hover .sidebar-group-title,
        .admin-sidebar.is-pinned .sidebar-group-title,
        .admin-sidebar.mobile-open .sidebar-group-title {
            opacity: 1;
            height: auto;
            margin-bottom: 0.375rem;
        }

        /* Subtle divider when collapsed, hidden when expanded */
        .sidebar-mini-divider {
            display: block;
            height: 1px;
            background-color: rgba(51, 65, 85, 0.4);
            margin: 0.75rem 0.5rem;
            transition: opacity 0.2s ease;
        }

        .admin-sidebar:hover .sidebar-mini-divider,
        .admin-sidebar.is-pinned .sidebar-mini-divider,
        .admin-sidebar.mobile-open .sidebar-mini-divider {
            display: none;
        }

        /* Centered Icon Container in Navigation Links */
        .sidebar-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        /* Notification mini-dot when collapsed */
        .sidebar-mini-dot {
            position: absolute;
            top: 7px;
            right: 14px;
            width: 7px;
            height: 7px;
            border-radius: 9999px;
            transition: opacity 0.15s ease;
        }

        .admin-sidebar:hover .sidebar-mini-dot,
        .admin-sidebar.is-pinned .sidebar-mini-dot,
        .admin-sidebar.mobile-open .sidebar-mini-dot {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body class="bg-slate-50/80 text-slate-800 font-sans antialiased h-screen overflow-hidden flex selection:bg-blue-500 selection:text-white">

    {{-- Page Loader --}}
    @include('components.page-loader')

    {{-- Mobile Sidebar Overlay Backdrop --}}
    <div id="mobileSidebarBackdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    {{-- Left Sidebar Navigation (Hover Expandable / Auto-Closing) --}}
    <aside id="admin-sidebar" class="admin-sidebar bg-slate-950 border-r border-slate-800/80 flex flex-col justify-between text-slate-400">
        <div class="flex flex-col flex-1 overflow-y-auto custom-scrollbar">
            
            {{-- CRM Header / Branding --}}
            <div class="px-4 py-4 border-b border-slate-800/80 flex items-center justify-between h-16 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden group" title="UnlockRentals Admin CRM">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-500 flex items-center justify-center text-white font-extrabold text-base shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform flex-shrink-0">
                        <i class="ph-bold ph-buildings text-lg"></i>
                    </div>
                    <div class="sidebar-brand-text">
                        <div class="text-sm font-bold text-white tracking-tight leading-tight flex items-center gap-1.5">
                            UnlockRentals
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">CRM</span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Management Hub</span>
                    </div>
                </a>
                <button id="sidebarPinBtn" type="button" class="sidebar-pin-btn p-1.5 text-slate-400 hover:text-white hover:bg-slate-800/80 rounded-lg transition-colors flex-shrink-0" title="Pin Sidebar Open">
                    <i id="sidebarPinIcon" class="ph-bold ph-push-pin text-sm"></i>
                </button>
            </div>

            {{-- Nav Menu Groups --}}
            <div class="px-3 py-4 space-y-4">
                
                {{-- Group 1: Core Operations --}}
                <div class="space-y-1">
                    <div class="sidebar-group-title">
                        <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Core Platform</span>
                    </div>
                    <div class="sidebar-mini-divider"></div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Dashboard">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-squares-four text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.properties') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.properties*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Properties Review">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-buildings text-base"></i>
                            </div>
                            <span class="sidebar-item-label">Properties Review</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Users Directory">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-users text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Users Directory</span>
                    </a>

                    <a href="{{ route('admin.locations') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.locations*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Locations & Cities">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-map-pin text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Locations & Cities</span>
                    </a>

                    <a href="{{ route('admin.blogs.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.blogs*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Blog Articles">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-newspaper text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Blog Articles</span>
                    </a>
                </div>

                {{-- Group 2: CRM & Leads --}}
                <div class="space-y-1">
                    <div class="sidebar-group-title">
                        <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Visitor & Lead CRM</span>
                    </div>
                    <div class="sidebar-mini-divider"></div>
                    
                    <a href="{{ route('admin.visitors.index') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.visitors*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Visitors Analytics">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-chart-polar text-base text-blue-400"></i>
                            </div>
                            <span class="sidebar-item-label">Visitors Analytics</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.leads.index') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.leads*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Leads CRM">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-funnel text-base text-indigo-400"></i>
                            </div>
                            <span class="sidebar-item-label">Leads CRM</span>
                        </div>
                        @php
                            try {
                                $newLeadsCount = \Illuminate\Support\Facades\Schema::hasTable('leads') ? \App\Models\Lead::where('lead_status', 'new')->count() : 0;
                            } catch (\Throwable $e) {
                                $newLeadsCount = 0;
                            }
                        @endphp
                        @if($newLeadsCount > 0)
                            <span class="sidebar-badge-count bg-blue-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $newLeadsCount }}</span>
                            <span class="sidebar-mini-dot bg-blue-400 animate-pulse"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.follow-ups.index') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.follow-ups*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Follow-ups Hub">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-calendar-check text-base text-amber-400"></i>
                            </div>
                            <span class="sidebar-item-label">Follow-ups Hub</span>
                        </div>
                        @php
                            try {
                                $dueFollowUps = \Illuminate\Support\Facades\Schema::hasTable('lead_follow_ups') ? \App\Models\LeadFollowUp::where('status', 'pending')->where('scheduled_at', '<=', now()->endOfDay())->count() : 0;
                            } catch (\Throwable $e) {
                                $dueFollowUps = 0;
                            }
                        @endphp
                        @if($dueFollowUps > 0)
                            <span class="sidebar-badge-count bg-amber-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $dueFollowUps }}</span>
                            <span class="sidebar-mini-dot bg-amber-400 animate-pulse"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.callbacks') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.callbacks*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Callback Leads">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-phone-call text-base"></i>
                            </div>
                            <span class="sidebar-item-label">Callback Leads</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['new_callbacks'] > 0)
                            <span class="sidebar-badge-count bg-rose-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['new_callbacks'] }}</span>
                            <span class="sidebar-mini-dot bg-rose-400"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.chats') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.chats*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Chat Inquiries">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-chat-circle-dots text-base"></i>
                            </div>
                            <span class="sidebar-item-label">Chat Inquiries</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['unread_chats'] > 0)
                            <span class="sidebar-badge-count bg-amber-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['unread_chats'] }}</span>
                            <span class="sidebar-mini-dot bg-amber-400"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.feedback') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.feedback*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="User Feedback">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-chat-centered-text text-base"></i>
                            </div>
                            <span class="sidebar-item-label">User Feedback</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['new_feedbacks'] > 0)
                            <span class="sidebar-badge-count bg-blue-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['new_feedbacks'] }}</span>
                            <span class="sidebar-mini-dot bg-blue-400"></span>
                        @endif
                    </a>

                    <a href="{{ route('admin.push-notifications.index') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.push-notifications*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Push Notifications">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-bell-ringing text-base text-blue-400"></i>
                            </div>
                            <span class="sidebar-item-label">Push Alerts</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.resets') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.resets*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Password Resets">
                        <div class="flex items-center gap-3">
                            <div class="sidebar-icon-box">
                                <i class="ph-bold ph-key text-base"></i>
                            </div>
                            <span class="sidebar-item-label">Password Resets</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['pending_resets'] > 0)
                            <span class="sidebar-badge-count bg-rose-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['pending_resets'] }}</span>
                            <span class="sidebar-mini-dot bg-rose-400"></span>
                        @endif
                    </a>
                </div>

                {{-- Group 3: Monetization --}}
                <div class="space-y-1">
                    <div class="sidebar-group-title">
                        <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Monetization & Plans</span>
                    </div>
                    <div class="sidebar-mini-divider"></div>
                    
                    <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.plans*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Pricing Plans">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-crown text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Pricing Plans</span>
                    </a>

                    <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.subscriptions*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Subscriptions">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-receipt text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Subscriptions</span>
                    </a>

                    <a href="{{ route('admin.process-steps') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.process-steps*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Process Steps">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-git-merge text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Process Steps</span>
                    </a>
                </div>

                {{-- Group 4: Settings --}}
                <div class="space-y-1">
                    <div class="sidebar-group-title">
                        <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Settings & Admin</span>
                    </div>
                    <div class="sidebar-mini-divider"></div>
                    
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.settings*') && !request()->routeIs('admin.crm-settings*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="Site Settings">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-gear text-base"></i>
                        </div>
                        <span class="sidebar-item-label">Site Settings</span>
                    </a>

                    <a href="{{ route('admin.crm-settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all relative {{ request()->routeIs('admin.crm-settings*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}" title="WhatsApp & CRM Settings">
                        <div class="sidebar-icon-box">
                            <i class="ph-bold ph-whatsapp-logo text-base text-emerald-400"></i>
                        </div>
                        <span class="sidebar-item-label">WhatsApp & CRM Settings</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Sidebar Footer Profile & Site Link --}}
        <div class="p-3 border-t border-slate-800/80 bg-slate-950/90 space-y-2.5 flex-shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2.5 w-full py-2 px-2.5 rounded-xl bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white text-xs font-bold border border-slate-800 transition-all overflow-hidden" title="Open Live Website">
                <div class="sidebar-icon-box">
                    <i class="ph-bold ph-arrow-square-out text-base text-blue-400"></i>
                </div>
                <span class="sidebar-footer-text">Open Live Website</span>
            </a>

            <div class="flex items-center justify-between p-2 bg-slate-900/70 border border-slate-800 rounded-xl overflow-hidden">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-extrabold shadow-sm flex-shrink-0" title="{{ auth()->user()->name ?? 'Admin' }}">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="sidebar-footer-text overflow-hidden">
                        <p class="text-xs font-bold text-white truncate leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <span class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Super Admin
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-footer-text">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Sign Out">
                        <i class="ph-bold ph-sign-out text-base"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Spacer for Desktop Fixed Mini Sidebar --}}
    <div class="admin-sidebar-spacer hidden lg:block"></div>

    {{-- Main Workspace Container --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
        
        {{-- CRM Topbar --}}
        <header class="h-16 bg-white border-b border-slate-200/90 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                {{-- Mobile Hamburger Toggle --}}
                <button id="mobileSidebarToggle" type="button" class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors" title="Open Navigation">
                    <i class="ph-bold ph-list text-xl"></i>
                </button>

                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="text-slate-400 hidden sm:inline">UnlockRentals</span>
                    <i class="ph ph-caret-right text-[10px] text-slate-300 hidden sm:inline"></i>
                    <span class="text-slate-900 font-bold">@yield('topbar_title', 'Admin Dashboard')</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3.5 py-2 rounded-xl transition-all border border-slate-200 hover:border-blue-200" title="View Live Website">
                    <i class="ph-bold ph-globe text-sm text-blue-600"></i>
                    <span class="hidden sm:inline">Live Website</span>
                </a>

                <div class="h-5 w-px bg-slate-200"></div>

                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 px-3.5 py-2 rounded-xl border border-rose-200 hover:border-rose-600 transition-all shadow-xs">
                        <i class="ph-bold ph-sign-out text-sm"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- Main Scrollable Content Area --}}
        <main class="flex-1 overflow-y-auto bg-slate-50/70 p-4 lg:p-8 custom-scrollbar">
            
            {{-- Flash Alert Messages --}}
            @if(session('success'))
            <div class="max-w-7xl mx-auto mb-6">
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3 text-sm font-semibold">
                        <i class="ph-fill ph-check-circle text-xl text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="max-w-7xl mx-auto mb-6">
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3 text-sm font-semibold">
                        <i class="ph-fill ph-warning-circle text-xl text-rose-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('components.idle-logout')

    {{-- Interactive Sidebar Controls Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const pinBtn = document.getElementById('sidebarPinBtn');
            const pinIcon = document.getElementById('sidebarPinIcon');
            const mobileToggle = document.getElementById('mobileSidebarToggle');
            const mobileBackdrop = document.getElementById('mobileSidebarBackdrop');

            // 1. Desktop Pin Preference (defaults to false -> hover to expand, auto-close on mouseleave)
            const isPinned = localStorage.getItem('unlockrentals_sidebar_pinned') === 'true';
            if (isPinned && sidebar) {
                sidebar.classList.add('is-pinned');
                if (pinIcon) {
                    pinIcon.classList.remove('ph-push-pin');
                    pinIcon.classList.add('ph-push-pin-slash');
                }
                if (pinBtn) pinBtn.title = "Unpin Sidebar (Auto-collapse on mouse leave)";
            }

            if (pinBtn && sidebar) {
                pinBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sidebar.classList.toggle('is-pinned');
                    const nowPinned = sidebar.classList.contains('is-pinned');
                    localStorage.setItem('unlockrentals_sidebar_pinned', nowPinned ? 'true' : 'false');

                    if (pinIcon) {
                        if (nowPinned) {
                            pinIcon.classList.remove('ph-push-pin');
                            pinIcon.classList.add('ph-push-pin-slash');
                            pinBtn.title = "Unpin Sidebar (Auto-collapse on mouse leave)";
                        } else {
                            pinIcon.classList.remove('ph-push-pin-slash');
                            pinIcon.classList.add('ph-push-pin');
                            pinBtn.title = "Pin Sidebar Open";
                        }
                    }
                });
            }

            // 2. Mobile Drawer Controls
            function toggleMobileSidebar() {
                if (!sidebar) return;
                const isOpen = sidebar.classList.toggle('mobile-open');
                if (mobileBackdrop) {
                    if (isOpen) {
                        mobileBackdrop.classList.remove('hidden');
                    } else {
                        mobileBackdrop.classList.add('hidden');
                    }
                }
            }

            if (mobileToggle) {
                mobileToggle.addEventListener('click', toggleMobileSidebar);
            }
            if (mobileBackdrop) {
                mobileBackdrop.addEventListener('click', toggleMobileSidebar);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
