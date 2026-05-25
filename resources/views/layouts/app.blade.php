<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Ultimate Education ATS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f0f2f8] antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ═══════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════ --}}
    <aside class="w-[240px] flex-shrink-0 flex flex-col"
           style="background: linear-gradient(180deg, #0f172a 0%, #111827 60%, #0f172a 100%);">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 pt-6 pb-5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: linear-gradient(135deg, #6366f1, #3b82f6); box-shadow: 0 4px 14px rgba(99,102,241,0.5);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-white leading-tight tracking-tight">Ultimate Education</p>
                <p class="text-[10px] text-slate-500 font-medium tracking-wide uppercase mt-0.5">Recruitment System</p>
            </div>
        </div>

        {{-- Divider --}}
        <div class="mx-5 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent mb-4"></div>

        {{-- User Badge --}}
        <div class="mx-4 mb-5 px-3 py-3 rounded-xl" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                     style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-[13px] font-semibold text-white truncate leading-tight">{{ Auth::user()->name }}</p>
                    <div class="mt-0.5">
                        @if(Auth::user()->role === 'hr')
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-md"
                              style="background: rgba(59,130,246,0.2); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25);">
                            HRD
                        </span>
                        @else
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-md"
                              style="background: rgba(139,92,246,0.2); color: #c4b5fd; border: 1px solid rgba(139,92,246,0.25);">
                            Reviewer
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto sidebar-scroll pb-3">

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('interviews.index') }}" class="nav-item {{ request()->routeIs('interviews.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal Wawancara
                {{-- Badge count jadwal hari ini --}}
            </a>

            <a href="{{ route('evaluations.index') }}" class="nav-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Input Penilaian
            </a>

            @if(Auth::user()->role === 'hr')

            <div class="pt-5 pb-2 px-3">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Manajemen</p>
            </div>

            <a href="{{ route('candidates.index') }}" class="nav-item {{ request()->routeIs('candidates.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Kandidat
            </a>

            <a href="{{ route('positions.index') }}" class="nav-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Posisi & Kriteria
            </a>

            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Manajemen User
            </a>

            <div class="pt-5 pb-2 px-3">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Analisis</p>
            </div>

            <a href="{{ route('rankings.index') }}" class="nav-item {{ request()->routeIs('rankings.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Laporan MAIRCA
            </a>

            @endif
        </nav>

        {{-- Bottom: Settings + Logout --}}
        <div class="px-3 py-4 space-y-0.5 border-t" style="border-color: rgba(255,255,255,0.07);">
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-left hover:!text-red-400" style="color: #64748b;">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

    </aside>

    {{-- ═══════════════════════════════════
         MAIN AREA
    ═══════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Bar --}}
        <header class="flex-shrink-0 flex items-center justify-between px-7 py-4"
                style="background: rgba(240,242,248,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226,232,240,0.8);">
            <div>
                <h1 class="text-[17px] font-bold text-slate-800 leading-tight">{{ $title ?? 'Dashboard' }}</h1>
                @isset($subtitle)
                <p class="text-xs text-slate-400 mt-0.5 font-medium">{{ $subtitle }}</p>
                @endisset
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-medium text-slate-500"
                     style="background: rgba(255,255,255,0.7); border: 1px solid rgba(226,232,240,0.8);">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </header>

        {{-- Flash Notifications --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4500)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="mx-7 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-emerald-800"
             style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid #a7f3d0;">
            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            {{ session('success') }}
            <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5500)"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="mx-7 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-800"
             style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fca5a5;">
            <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            {{ session('error') }}
            <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="mx-7 mt-4 flex items-start gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-800"
             style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fca5a5;">
            <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <ul class="space-y-0.5">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-7 page-fade">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
