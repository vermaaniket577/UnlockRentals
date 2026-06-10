<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin CRM - UnlockRentals')</title>

    {{-- Performance: DNS prefetch for external resources --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">


    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1">
    <link rel="icon" type="image/png" sizes="256x256" href="{{ asset('favicon.png') }}?v=1">

    {{-- Premium Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Phosphor Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.0/src/bold/style.css" integrity="sha384-nblAP2mo2pVPyMQZDw9Xy9Cwgs9lowushAYep4w5+Q9kF4Ibf3n0B/gCMVdR+Vqy" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">

    {{-- Tailwind CSS for local/dev reliability without requiring Vite --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        'zinc-850': '#121214',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    
    {{-- Custom App Styles --}}
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v=20260521-admin-layout">
</head>
<body class="bg-stone-50 text-zinc-800 font-sans antialiased font-light selection:bg-[#2563EB]/30 selection:text-zinc-900 h-screen overflow-hidden flex">

    {{-- Premium Page Loader --}}
    @include('components.page-loader')

    {{-- Left Sidebar CRM Navigation --}}
    <aside class="w-64 bg-zinc-950 border-r border-zinc-850 flex flex-col justify-between text-zinc-400 flex-shrink-0 z-40">
        <div class="flex flex-col flex-1 overflow-y-auto">
            
            {{-- CRM Header / Branding --}}
            <div class="p-6 border-b border-zinc-900 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-sm flex items-center justify-center text-white font-bold text-lg">
                        U
                    </div>
                    <div>
                        <h1 class="text-sm font-semibold text-white tracking-tight leading-none">UnlockRentals</h1>
                        <span class="text-[10px] text-zinc-500 uppercase tracking-widest font-semibold">CRM Panel</span>
                    </div>
                </a>
            </div>

            {{-- Nav Menu Groups --}}
            <div class="px-4 py-6 space-y-6">
                
                {{-- Group 1: General Core --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold text-zinc-600 uppercase tracking-widest block mb-2">Core</span>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-squares-four text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.properties') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.properties*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-buildings text-lg"></i>
                        <span>Properties Review</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.users*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-users text-lg"></i>
                        <span>Users Directory</span>
                    </a>
                </div>

                {{-- Group 2: CRM & Engagement --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold text-zinc-600 uppercase tracking-widest block mb-2">CRM Leads</span>
                    
                    <a href="{{ route('admin.feedback') }}" class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.feedback*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-chat-centered-text text-lg"></i>
                            <span>Feedback</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['new_feedbacks'] > 0)
                            <span class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_feedbacks'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.chats') }}" class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.chats*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-chat-circle-dots text-lg"></i>
                            <span>Chat History</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['unread_chats'] > 0)
                            <span class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['unread_chats'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.callbacks') }}" class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.callbacks*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-phone-call text-lg"></i>
                            <span>Callback Leads</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['new_callbacks'] > 0)
                            <span class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_callbacks'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.resets') }}" class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.resets*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-key text-lg"></i>
                            <span>Password Resets</span>
                        </div>
                        @if(isset($adminNotifications) && $adminNotifications['pending_resets'] > 0)
                            <span class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['pending_resets'] }}</span>
                        @endif
                    </a>
                </div>

                {{-- Group 3: Plan & Subscription --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold text-zinc-600 uppercase tracking-widest block mb-2">Monetization</span>
                    <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.plans*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-crown text-lg"></i>
                        <span>Manage Plans</span>
                    </a>
                    <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.subscriptions*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-receipt text-lg"></i>
                        <span>Subscriptions</span>
                    </a>
                    <a href="{{ route('admin.process-steps') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.process-steps*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-git-merge text-lg"></i>
                        <span>Process Steps</span>
                    </a>
                </div>

                {{-- Group 4: Settings --}}
                <div class="space-y-1">
                    <span class="px-3 text-[10px] font-bold text-zinc-600 uppercase tracking-widest block mb-2">System</span>
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-sm transition-all {{ request()->routeIs('admin.settings*') ? 'bg-zinc-900 text-white font-semibold' : 'hover:text-white hover:bg-zinc-900/50' }}">
                        <i class="ph ph-gear text-lg"></i>
                        <span>Content & Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full block">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-medium text-red-400/90 hover:text-white hover:bg-red-500/10 rounded-sm transition-all text-left">
                            <i class="ph ph-sign-out text-lg"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Sidebar Footer --}}
        <div class="p-4 border-t border-zinc-900 space-y-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-zinc-500 hover:text-white hover:bg-zinc-900 rounded-sm transition-all">
                <i class="ph ph-arrow-left"></i>
                <span>Back to Website</span>
            </a>

            <div class="flex items-center justify-between p-2 bg-zinc-900/50 border border-zinc-900 rounded-sm">
                <div class="flex items-center gap-2 overflow-hidden">
                    <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-[10px] font-semibold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[11px] font-semibold text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                        <span class="text-[9px] text-zinc-500 block">Admin Mode</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1 hover:text-red-400 transition-colors" title="Sign Out">
                        <i class="ph ph-sign-out text-base"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Area Container --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        {{-- CRM Topbar --}}
        <header class="h-16 bg-white border-b border-stone-200/50 flex items-center justify-between px-8 flex-shrink-0 z-30 shadow-sm shadow-stone-100/30">
            <div>
                <h2 class="text-sm font-semibold text-zinc-900">UnlockRentals CRM Dashboard</h2>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-xs bg-stone-100 hover:bg-stone-200 text-zinc-600 px-3.5 py-1.5 rounded-sm font-semibold transition-all">
                    View Live Site
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="text-xs bg-red-500/10 hover:bg-red-600 text-red-600 hover:text-white border border-red-500/20 hover:border-red-600 px-3.5 py-1.5 rounded-sm font-semibold transition-all flex items-center gap-1.5 shadow-sm shadow-red-500/5">
                        <i class="ph ph-sign-out text-sm"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- CRM Content Container --}}
        <main class="flex-1 overflow-y-auto bg-stone-50">
            
            {{-- Success / Error Alerts --}}
            @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-sm flex items-center justify-between">
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600"><i class="ph ph-x"></i></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-sm flex items-center justify-between">
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600"><i class="ph ph-x"></i></button>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
