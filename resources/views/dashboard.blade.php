<x-app-layout title="Dashboard" subtitle="Selamat datang kembali — ini ringkasan rekrutmen hari ini">

<div class="space-y-6">

    {{-- ── STAT CARDS ── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Lowongan --}}
        <div class="stat-card group">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5 -mr-8 -mt-8"
                 style="background: radial-gradient(circle, #6366f1, transparent)"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #eef2ff, #e0e7ff);">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#eef2ff; color:#6366f1;">Aktif</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalPositions }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Lowongan Terbuka</p>
        </div>

        {{-- Kandidat Aktif --}}
        <div class="stat-card group">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5 -mr-8 -mt-8"
                 style="background: radial-gradient(circle, #3b82f6, transparent)"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#eff6ff; color:#3b82f6;">In Pipeline</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $activeCandidates }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Kandidat Aktif</p>
        </div>

        {{-- Menunggu Evaluasi --}}
        <div class="stat-card group">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5 -mr-8 -mt-8"
                 style="background: radial-gradient(circle, #f59e0b, transparent)"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #fffbeb, #fef3c7);">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#fffbeb; color:#d97706;">Perlu Dinilai</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $pendingEvaluations }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Menunggu Evaluasi SPK</p>
        </div>

        {{-- Diterima Bulan Ini --}}
        <div class="stat-card group">
            <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-5 -mr-8 -mt-8"
                 style="background: radial-gradient(circle, #10b981, transparent)"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #ecfdf5, #d1fae5);">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:#ecfdf5; color:#059669;">Bulan Ini</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $hiredThisMonth }}</p>
            <p class="text-xs font-medium text-slate-400 mt-1">Kandidat Diterima</p>
        </div>

    </div>

    {{-- ── JADWAL HARI INI ── --}}
    <div class="data-card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background: linear-gradient(135deg, #4f46e5, #3b82f6);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="card-title">Wawancara Hari Ini</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <a href="{{ route('interviews.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($todaySchedules->isEmpty())
        <div class="py-14 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Tidak ada jadwal wawancara hari ini</p>
            <p class="text-xs text-slate-400 mt-1">Nikmati hari yang tenang 🎉</p>
        </div>
        @else
        <div class="divide-y divide-slate-50">
            @foreach($todaySchedules as $schedule)
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-indigo-50/30 transition-colors">
                {{-- Time Block --}}
                <div class="w-16 flex-shrink-0 text-center">
                    <p class="text-lg font-black text-slate-800">{{ $schedule->scheduled_at->format('H:i') }}</p>
                    <p class="text-[10px] font-semibold text-slate-400 uppercase">WIB</p>
                </div>

                {{-- Vertical Divider --}}
                <div class="w-px h-10 bg-slate-100 flex-shrink-0"></div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ $schedule->candidate->name }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <p class="text-xs text-slate-400 truncate">{{ $schedule->candidate->position->name ?? '—' }}</p>
                        <span class="w-1 h-1 rounded-full bg-slate-300 flex-shrink-0"></span>
                        <p class="text-xs text-slate-400 flex-shrink-0">{{ $schedule->interviewer->name ?? '—' }}</p>
                    </div>
                </div>

                {{-- Type Badge --}}
                <span class="badge flex-shrink-0 {{ $schedule->type === 'hr' ? 'badge-blue' : 'badge-purple' }}">
                    {{ $schedule->type === 'hr' ? 'Wawancara HR' : 'Wawancara User' }}
                </span>

                {{-- Zoom --}}
                @if($schedule->zoom_link)
                <a href="{{ $schedule->zoom_link }}" target="_blank" class="zoom-btn flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                    </svg>
                    Join Zoom
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── BOTTOM GRID ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- Jadwal Mendatang --}}
        <div class="data-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         style="background: linear-gradient(135deg, #7c3aed, #6366f1);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="card-title">Jadwal 7 Hari Ke Depan</p>
                </div>
            </div>

            @if($upcomingSchedules->isEmpty())
            <div class="py-10 text-center text-sm text-slate-400">Tidak ada jadwal mendatang</div>
            @else
            <div class="divide-y divide-slate-50">
                @foreach($upcomingSchedules as $s)
                <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-violet-50/30 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex flex-col items-center justify-center flex-shrink-0"
                         style="background: linear-gradient(135deg, #f5f3ff, #ede9fe);">
                        <p class="text-sm font-black text-violet-700 leading-none">{{ $s->scheduled_at->format('d') }}</p>
                        <p class="text-[9px] font-bold text-violet-500 uppercase">{{ $s->scheduled_at->format('M') }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $s->candidate->name }}</p>
                        <p class="text-xs text-slate-400">{{ $s->scheduled_at->format('H:i') }} WIB · {{ $s->getTypeLabel() }}</p>
                    </div>
                    @if($s->zoom_link)
                    <a href="{{ $s->zoom_link }}" target="_blank"
                       class="text-indigo-400 hover:text-indigo-600 transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Kandidat Terbaru --}}
        <div class="data-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                         style="background: linear-gradient(135deg, #059669, #10b981);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <p class="card-title">Kandidat Terbaru</p>
                </div>
                @if(Auth::user()->role === 'hr')
                <a href="{{ route('candidates.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                    Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>

            @if($recentCandidates->isEmpty())
            <div class="py-10 text-center text-sm text-slate-400">Belum ada kandidat</div>
            @else
            @php
            $badgeMap = [
                'berkas'         => 'badge-gray',
                'tes_praktis'    => 'badge-blue',
                'wawancara_hr'   => 'badge-purple',
                'wawancara_user' => 'badge-indigo',
                'evaluasi_spk'   => 'badge-amber',
                'hired'          => 'badge-green',
                'rejected'       => 'badge-red',
            ];
            @endphp
            <div class="divide-y divide-slate-50">
                @foreach($recentCandidates as $c)
                <div class="flex items-center gap-3.5 px-6 py-3.5 hover:bg-emerald-50/30 transition-colors">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                         style="background: linear-gradient(135deg, #94a3b8, #64748b);">
                        {{ strtoupper(substr($c->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $c->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $c->position->name ?? '—' }}</p>
                    </div>
                    <span class="badge {{ $badgeMap[$c->status] ?? 'badge-gray' }} flex-shrink-0">{{ $c->getStatusLabel() }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

</div>

</x-app-layout>