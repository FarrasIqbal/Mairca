<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Ultimate Education ATS</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo-ue.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head><body class="bg-[#f8fafc] dark:bg-[#090d16] antialiased text-slate-800 dark:text-slate-200">

<div class="flex h-screen overflow-hidden">

    {{-- ═══════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════ --}}
    <aside class="w-[260px] flex-shrink-0 flex flex-col bg-white border-r border-slate-200/80 shadow-sm z-[60]">

        {{-- Brand --}}
        <div class="flex flex-col gap-1 px-6 pt-7 pb-6">
            <img src="{{ asset('assets/logo-ue.webp') }}" alt="Ultimate Education Logo" class="h-10 w-auto object-contain self-start">
            <p class="text-[9px] text-indigo-600 font-bold tracking-widest uppercase mt-1">Recruitment System</p>
        </div>

        {{-- Divider --}}
        <div class="mx-6 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-5"></div>

        {{-- User Badge --}}
        <div class="mx-4 mb-6 px-4 py-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0 bg-gradient-to-tr from-indigo-500 to-violet-500 shadow-sm border border-indigo-400/20">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-[13px] font-bold text-slate-800 dark:text-slate-200 truncate leading-tight">{{ Auth::user()->name }}</p>
                    <div class="mt-1">
                        @if(Auth::user()->role === 'hr')
                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                            HRD
                        </span>
                        @else
                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-purple-500/10 dark:bg-purple-500/25 text-purple-600 dark:text-purple-400 border border-purple-500/20 dark:border-purple-500/30">
                            Reviewer
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 space-y-1 overflow-y-auto sidebar-scroll pb-4">

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
            </a>

            <a href="{{ route('evaluations.index') }}" class="nav-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Input Penilaian
            </a>

            @php
                $pendingTestsCount = \App\Models\Candidate::where('status', 'tes_praktis')
                    ->whereHas('practicalTest', function($query) {
                        $query->whereNotNull('submitted_at')->whereNull('reviewer_notes');
                    })->count();
            @endphp

            <a href="{{ route('admin.practical-tests.index') }}" class="nav-item {{ request()->routeIs('admin.practical-tests.*') ? 'active' : '' }} flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    <span>Tes Praktis</span>
                </div>
                @if($pendingTestsCount > 0)
                    <span class="w-5 h-5 rounded-full bg-rose-500 text-white font-black text-[9px] flex items-center justify-center shadow-sm shadow-rose-500/30 animate-pulse mr-2">{{ $pendingTestsCount }}</span>
                @endif
            </a>

            @if(Auth::user()->role === 'hr')

            <div class="pt-6 pb-2 px-4">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Manajemen</p>
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

            @endif

            <div class="pt-6 pb-2 px-4">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Analisis</p>
            </div>

            <a href="{{ route('rankings.index') }}" class="nav-item {{ request()->routeIs('rankings.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Laporan MAIRCA
            </a>
        </nav>

        {{-- Bottom: Settings + Logout --}}
        <div class="px-4 py-5 space-y-1 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-left hover:!text-rose-600 group text-slate-600 dark:text-slate-400">
                    <svg class="nav-icon group-hover:text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="flex-1 flex flex-col overflow-hidden min-w-0 z-10">

        {{-- Top Bar --}}
        <header class="flex-shrink-0 flex items-center justify-between px-8 py-5 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm shadow-slate-100/50">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight leading-tight">{{ $title ?? 'Dashboard' }}</h1>
                @isset($subtitle)
                <p class="text-xs text-slate-400 mt-0.5 font-medium">{{ $subtitle }}</p>
                @endisset
            </div>
            <div class="flex items-center gap-3">
                <!-- Dark Mode Toggle Button -->
                <button id="theme-toggle" class="flex items-center justify-center w-9 h-9 rounded-xl bg-slate-50/80 border border-slate-100 text-slate-500 hover:text-indigo-600 transition-colors shadow-inner dark:bg-slate-800/40 dark:border-slate-800 dark:text-slate-400 dark:hover:text-indigo-400">
                    <!-- Sun Icon (visible in dark mode) -->
                    <svg id="theme-toggle-light-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-11.314l.707.707m11.314 11.314l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"/>
                    </svg>
                    <!-- Moon Icon (visible in light mode) -->
                    <svg id="theme-toggle-dark-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <div class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-50/80 border border-slate-100 shadow-inner">
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
             class="mx-8 mt-4 flex items-center gap-3 px-5 py-3.5 rounded-2xl text-sm font-medium text-emerald-800 dark:text-emerald-300 shadow-md shadow-emerald-500/5 border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50/50 dark:bg-emerald-950/20">
            <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0 shadow shadow-emerald-500/50">
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
             class="mx-8 mt-4 flex items-center gap-3 px-5 py-3.5 rounded-2xl text-sm font-medium text-rose-800 dark:text-rose-300 shadow-md shadow-rose-500/5 border border-rose-200 dark:border-rose-900/40 bg-rose-50/50 dark:bg-rose-950/20">
            <div class="w-6 h-6 rounded-full bg-rose-500 flex items-center justify-center flex-shrink-0 shadow shadow-rose-500/50">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            {{ session('error') }}
            <button @click="show = false" class="ml-auto text-rose-400 hover:text-rose-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="mx-8 mt-4 flex items-start gap-3 px-5 py-3.5 rounded-2xl text-sm font-medium text-rose-800 dark:text-rose-300 shadow-md border border-rose-200 dark:border-rose-900/40 bg-rose-50/50 dark:bg-rose-950/20">
            <div class="w-6 h-6 rounded-full bg-rose-500 flex items-center justify-center flex-shrink-0 mt-0.5 shadow shadow-rose-500/50">
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
        <main class="flex-1 overflow-y-auto p-8 page-fade">
            {{ $slot }}
        </main>

    </div>
</div>

    {{-- Reusable Global Custom Confirm Modal --}}
    <div x-data="{
        open: false,
        title: 'Konfirmasi Tindakan',
        message: '',
        confirmCallback: null,
        confirmBtnText: 'Ya, Lanjutkan',
        cancelBtnText: 'Batal',
        confirmType: 'danger',
        triggerConfirm(e) {
            this.title = e.detail.title || 'Konfirmasi Tindakan';
            this.message = e.detail.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            this.confirmBtnText = e.detail.confirmBtnText || 'Ya, Lanjutkan';
            this.cancelBtnText = e.detail.cancelBtnText || 'Batal';
            this.confirmType = e.detail.type || 'danger';
            this.confirmCallback = e.detail.callback;
            this.open = true;
        },
        confirm() {
            if (this.confirmCallback) {
                this.confirmCallback();
            }
            this.open = false;
        }
    }"
    @confirm-modal.window="triggerConfirm($event)"
    x-cloak
    x-show="open"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fade-in"
    style="display: none;">
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 scale-100" 
             x-transition:leave-end="opacity-0 scale-95"
             class="w-full max-w-md p-6 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800"
             @click.away="open = false">
            
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                     :class="{
                         'bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-400': confirmType === 'danger',
                         'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400': confirmType === 'warning',
                         'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400': confirmType === 'primary',
                         'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400': confirmType === 'success'
                     }">
                    <template x-if="confirmType === 'danger' || confirmType === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </template>
                    <template x-if="confirmType === 'primary' || confirmType === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                </div>
                
                <div class="space-y-1 flex-1">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100" x-text="title"></h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed" x-text="message"></p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2.5">
                <button @click="open = false" class="px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer">
                    <span x-text="cancelBtnText"></span>
                </button>
                <button @click="confirm()" 
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-white transition-colors cursor-pointer shadow-sm border"
                        :class="{
                            'bg-red-600 hover:bg-red-700 border-red-600': confirmType === 'danger',
                            'bg-amber-600 hover:bg-amber-700 border-amber-600': confirmType === 'warning',
                            'bg-indigo-600 hover:bg-indigo-700 border-indigo-600': confirmType === 'primary',
                            'bg-emerald-600 hover:bg-emerald-700 border-emerald-600': confirmType === 'success'
                        }">
                    <span x-text="confirmBtnText"></span>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (!themeToggleBtn) return;

            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');

            // Set icon state initially
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                themeToggleLightIcon.classList.toggle('hidden');
                themeToggleDarkIcon.classList.toggle('hidden');

                if (localStorage.getItem('theme')) {
                    if (localStorage.getItem('theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                }
            });
        });
    </script>
</body>
</html>
