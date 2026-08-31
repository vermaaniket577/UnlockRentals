<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-T7LQ1PB17L"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-T7LQ1PB17L');
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
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/icon-192x192.png') }}">

    {{-- Google Fonts: Plus Jakarta Sans & Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Phosphor Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        brand: {
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            500: '#3B82F6',
                            600: '#2563EB',
                            700: '#1D4ED8',
                            900: '#1E3A8A',
                        },
                        slate: {
                            750: '#243247',
                            850: '#152032',
                            950: '#0B111E',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'card': '0 0 0 1px rgba(226, 232, 240, 0.8), 0 1px 3px 0 rgba(0, 0, 0, 0.05)',
                        'card-hover': '0 0 0 1px rgba(37, 99, 235, 0.2), 0 12px 28px -4px rgba(37, 99, 235, 0.08)',
                    }
                }
            }
        }
    </script>
    
    {{-- Custom App Styles --}}
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v=20260830-admin-clean">
    <style>
        body { font-family: 'Plus Jakarta Sans', Inter, sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="bg-slate-50/80 text-slate-800 font-sans antialiased h-screen overflow-hidden flex selection:bg-blue-500 selection:text-white">

    {{-- Page Loader --}}
    @include('components.page-loader')

    {{-- Left Sidebar Navigation --}}
    <aside class="w-64 bg-slate-950 border-r border-slate-800/80 flex flex-col justify-between text-slate-400 flex-shrink-0 z-40">
        <div class="flex flex-col flex-1 overflow-y-auto custom-scrollbar">
            
            {{-- CRM Header / Branding --}}
            <div class="px-5 py-5 border-b border-slate-800/80 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group" title="UnlockRentals Admin CRM">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-500 flex items-center justify-center text-white font-extrabold text-base shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                        <i class="ph-bold ph-buildings"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white tracking-tight leading-tight flex items-center gap-1.5">
                            UnlockRentals
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">CRM</span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">Management Hub</span>
                    </div>
                </a>
            </div>

            {{-- Nav Menu Groups --}}
            <div class="px-3.5 py-5 space-y-6">
                
                {{-- Group 1: Core Operations --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Core Platform</span>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-squares-four text-base"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.properties') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.properties*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-buildings text-base"></i>
                            <span>Properties Review</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-users text-base"></i>
                        <span>Users Directory</span>
                    </a>

                    <a href="{{ route('admin.locations') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.locations*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-map-pin text-base"></i>
                        <span>Locations & Cities</span>
                    </a>

                    <a href="{{ route('admin.blogs.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.blogs*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-newspaper text-base"></i>
                        <span>Blog Articles</span>
                    </a>
                </div>

                {{-- Group 2: CRM & Leads --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Inquiries & CRM</span>
                    
                    <a href="{{ route('admin.callbacks') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.callbacks*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-phone-call text-base"></i>
                            <span>Callback Leads</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['new_callbacks'] > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['new_callbacks'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.chats') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.chats*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-chat-circle-dots text-base"></i>
                            <span>Chat Inquiries</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['unread_chats'] > 0)
                            <span class="bg-amber-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['unread_chats'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.feedback') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.feedback*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-chat-centered-text text-base"></i>
                            <span>User Feedback</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['new_feedbacks'] > 0)
                            <span class="bg-blue-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['new_feedbacks'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.resets') }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.resets*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph-bold ph-key text-base"></i>
                            <span>Password Resets</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['pending_resets'] > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full">{{ $adminNotifications['pending_resets'] }}</span>
                        @endif
                    </a>
                </div>

                {{-- Group 3: Monetization --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Monetization & Plans</span>
                    
                    <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.plans*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-crown text-base"></i>
                        <span>Pricing Plans</span>
                    </a>

                    <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.subscriptions*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-receipt text-base"></i>
                        <span>Subscriptions</span>
                    </a>

                    <a href="{{ route('admin.process-steps') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.process-steps*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-git-merge text-base"></i>
                        <span>Process Steps</span>
                    </a>
                </div>

                {{-- Group 4: Settings --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block mb-1.5">Settings & Admin</span>
                    
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.settings*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-bold' : 'hover:text-white hover:bg-slate-900/80 text-slate-400' }}">
                        <i class="ph-bold ph-gear text-base"></i>
                        <span>Site Settings</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Sidebar Footer Profile --}}
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/80 space-y-3">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 px-3 rounded-xl bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white text-xs font-bold border border-slate-800 transition-all">
                <i class="ph-bold ph-arrow-square-out text-sm"></i>
                <span>Open Live Website</span>
            </a>

            <div class="flex items-center justify-between p-2.5 bg-slate-900/70 border border-slate-800 rounded-xl">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-extrabold shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <span class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Super Admin
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors" title="Sign Out">
                        <i class="ph-bold ph-sign-out text-base"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Workspace Container --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        {{-- CRM Topbar --}}
        <header class="h-16 bg-white border-b border-slate-200/90 flex items-center justify-between px-6 lg:px-8 flex-shrink-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="text-slate-400">UnlockRentals</span>
                    <i class="ph ph-caret-right text-[10px] text-slate-300"></i>
                    <span class="text-slate-900 font-bold">@yield('topbar_title', 'Admin Dashboard')</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3.5 py-2 rounded-xl transition-all border border-slate-200 hover:border-blue-200" title="View Live Website">
                    <i class="ph-bold ph-globe text-sm text-blue-600"></i>
                    <span>Live Website</span>
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
        <main class="flex-1 overflow-y-auto bg-slate-50/70 p-6 lg:p-8 custom-scrollbar">
            
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

    @stack('scripts')
</body>
</html>
