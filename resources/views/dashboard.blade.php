<x-app-layout title="Dashboard" subtitle="Selamat datang kembali — ini ringkasan rekrutmen hari ini">

<div class="space-y-6">

    {{-- ── STAT CARDS ── --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Total Lowongan --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/25 border border-indigo-400/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="badge badge-indigo">Aktif</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight leading-none">{{ $totalPositions }}</p>
            <p class="text-xs font-semibold text-slate-400 mt-2">Lowongan Terbuka</p>
        </div>

        {{-- Kandidat Aktif --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-tr from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/25 border border-emerald-400/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="badge badge-green">In Pipeline</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight leading-none">{{ $activeCandidates }}</p>
            <p class="text-xs font-semibold text-slate-400 mt-2">Kandidat Aktif</p>
        </div>

        {{-- Menunggu Evaluasi --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-tr from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/25 border border-amber-400/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="badge badge-amber">Perlu Dinilai</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight leading-none">{{ $pendingEvaluations }}</p>
            <p class="text-xs font-semibold text-slate-400 mt-2">Menunggu Evaluasi SPK</p>
        </div>

        {{-- Diterima Bulan Ini --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-tr from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25 border border-blue-400/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="badge badge-blue">Bulan Ini</span>
            </div>
            <p class="text-3xl font-black text-slate-800 tracking-tight leading-none">{{ $hiredThisMonth }}</p>
            <p class="text-xs font-semibold text-slate-400 mt-2">Kandidat Diterima</p>
        </div>

    </div>

    {{-- ── JADWAL HARI INI ── --}}
    <div class="data-card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-tr from-indigo-500 to-violet-600 shadow-md shadow-indigo-500/20 border border-indigo-400/10">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="card-title">Wawancara Hari Ini</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <a href="{{ route('interviews.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 bg-indigo-50/50 hover:bg-indigo-50 px-3.5 py-1.5 rounded-xl border border-indigo-100/50">
                Lihat Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($todaySchedules->isEmpty())
        <div class="py-16 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gradient-to-b from-slate-50 to-slate-100 border border-slate-200/50 shadow-inner">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-600">Tidak ada jadwal wawancara hari ini</p>
            <p class="text-xs text-slate-400 mt-1">Nikmati hari yang tenang 🎉</p>
        </div>
        @else
        <div class="divide-y divide-slate-100/65">
            @foreach($todaySchedules as $schedule)
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-indigo-50/20 transition-colors">
                {{-- Time Block --}}
                <div class="w-16 flex-shrink-0 text-center">
                    <p class="text-lg font-black text-slate-800 leading-none">{{ $schedule->scheduled_at->format('H:i') }}</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">WIB</p>
                </div>

                {{-- Vertical Divider --}}
                <div class="w-px h-10 bg-slate-100 flex-shrink-0"></div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ $schedule->candidate->name }}</p>
                    <div class="flex items-center gap-2.5 mt-1">
                        <p class="text-xs text-slate-400 truncate">{{ $schedule->candidate->position->name ?? '—' }}</p>
                        <span class="w-1 h-1 rounded-full bg-slate-300 flex-shrink-0"></span>
                        <p class="text-xs text-slate-400 flex-shrink-0 font-medium">{{ $schedule->interviewer->name ?? '—' }}</p>
                    </div>
                </div>

                {{-- Type Badge --}}
                <span class="badge flex-shrink-0 {{ $schedule->type === 'hr' ? 'badge-blue' : 'badge-purple' }} shadow-sm">
                    {{ $schedule->type === 'hr' ? 'Wawancara HR' : 'Wawancara User' }}
                </span>

                {{-- Zoom --}}
                @if($schedule->zoom_link)
                <a href="{{ $schedule->zoom_link }}" target="_blank" class="zoom-btn flex-shrink-0 flex items-center gap-1.5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-300"></span>
                    </span>
                    Join Zoom
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── HASIL TES PRAKTIS TERBARU ── --}}
    <div class="data-card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-tr from-indigo-500 to-emerald-600 shadow-md border border-indigo-400/10">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </div>
                <div>
                    <p class="card-title">Hasil Tes Praktis Terbaru</p>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Kandidat yang telah menyelesaikan dan mengumpulkan lembar jawaban tes praktis</p>
                </div>
            </div>
        </div>
        
        @if($submittedTests->isEmpty())
        <div class="py-10 text-center">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-slate-50 border border-slate-100 shadow-inner">
                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-500">Belum ada tes praktis yang dikumpulkan</p>
            <p class="text-[11px] text-slate-400 mt-1 max-w-lg mx-auto leading-relaxed">
                Buka menu <b class="text-indigo-600">Kandidat</b> atau daftar di bawah → pilih kandidat berstatus <b class="text-slate-600">Tes Praktis</b> → klik namanya → salin & kirimkan <b>Link Ujian</b> ke kandidat.
            </p>
        </div>
        @else
        <div class="divide-y divide-slate-100/65">
            @foreach($submittedTests as $candidate)
            @php $test = $candidate->practicalTest; @endphp
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50/40 transition-colors">
                {{-- Left: Candidate detail --}}
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0 bg-gradient-to-tr from-indigo-500 to-violet-500 shadow-sm">
                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <a href="{{ route('candidates.show', $candidate->id) }}" class="text-sm font-bold text-slate-800 hover:text-indigo-600 hover:underline truncate block">{{ $candidate->name }}</a>
                        <p class="text-xs text-slate-400 truncate mt-0.5">{{ $candidate->position->name ?? '—' }}</p>
                    </div>
                </div>

                {{-- Middle: Score & Suitability --}}
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="text-center px-4 py-1.5 rounded-xl bg-slate-50 border border-slate-100/80">
                        <span class="block text-[9px] font-bold text-slate-400 uppercase leading-none">Skor Tes</span>
                        <span class="text-base font-black text-slate-800 leading-none mt-1 inline-block">{{ $test->score }}</span>
                    </div>
                    
                    @if($test->is_suitable)
                    <span class="badge badge-green flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Sesuai Standar
                    </span>
                    @else
                    <span class="badge badge-amber flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Di Bawah Standar
                    </span>
                    @endif
                </div>

                {{-- Right: Quick progression actions (Saves HR/User hassle) --}}
                <div class="flex items-center gap-2">
                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="position_id" value="{{ $candidate->position_id }}">
                        <input type="hidden" name="name" value="{{ $candidate->name }}">
                        <input type="hidden" name="email" value="{{ $candidate->email }}">
                        <input type="hidden" name="phone" value="{{ $candidate->phone }}">
                        <input type="hidden" name="status" value="wawancara_hr">
                        
                        <button type="submit" class="btn-primary py-2 px-3.5 rounded-xl text-[10px] font-bold tracking-wider uppercase flex items-center gap-1">
                            Loloskan ke HR
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </form>

                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak kandidat ini?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="position_id" value="{{ $candidate->position_id }}">
                        <input type="hidden" name="name" value="{{ $candidate->name }}">
                        <input type="hidden" name="email" value="{{ $candidate->email }}">
                        <input type="hidden" name="phone" value="{{ $candidate->phone }}">
                        <input type="hidden" name="status" value="rejected">
                        
                        <button type="submit" class="btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50 border-red-100 hover:border-red-200 py-2 px-3 rounded-xl text-[10px] font-bold tracking-wider uppercase">
                            Tolak
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── BOTTOM GRID ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Jadwal Mendatang --}}
        <div class="data-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-tr from-violet-500 to-indigo-600 shadow-md shadow-violet-500/20 border border-violet-400/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="card-title">Jadwal 7 Hari Ke Depan</p>
                </div>
            </div>

            @if($upcomingSchedules->isEmpty())
            <div class="py-12 text-center text-sm font-semibold text-slate-400">Tidak ada jadwal mendatang</div>
            @else
            <div class="divide-y divide-slate-100/50">
                @foreach($upcomingSchedules as $s)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-violet-50/20 transition-colors">
                    <div class="w-10 h-10 rounded-2xl flex flex-col items-center justify-center flex-shrink-0 shadow-sm border border-violet-100"
                         style="background: linear-gradient(135deg, #f5f3ff, #ede9fe);">
                        <p class="text-sm font-extrabold text-violet-700 leading-none">{{ $s->scheduled_at->format('d') }}</p>
                        <p class="text-[9px] font-bold text-violet-500 uppercase mt-0.5">{{ $s->scheduled_at->format('M') }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $s->candidate->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $s->scheduled_at->format('H:i') }} WIB · <span class="text-violet-600 font-semibold">{{ $s->getTypeLabel() }}</span></p>
                    </div>
                    @if($s->zoom_link)
                    <a href="{{ $s->zoom_link }}" target="_blank"
                       class="w-8 h-8 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 hover:text-indigo-800 flex items-center justify-center transition-colors flex-shrink-0 border border-indigo-100/30 shadow-sm">
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
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-tr from-emerald-500 to-teal-600 shadow-md shadow-emerald-500/20 border border-emerald-400/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <p class="card-title">Kandidat Terbaru</p>
                </div>
                @if(Auth::user()->role === 'hr')
                <a href="{{ route('candidates.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 bg-indigo-50/50 hover:bg-indigo-50 px-3.5 py-1.5 rounded-xl border border-indigo-100/50">
                    Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>

            @if($recentCandidates->isEmpty())
            <div class="py-12 text-center text-sm font-semibold text-slate-400">Belum ada kandidat</div>
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
            <div class="divide-y divide-slate-100/50">
                @foreach($recentCandidates as $c)
                <div class="flex items-center gap-3.5 px-6 py-4 hover:bg-emerald-50/20 transition-colors">
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-sm"
                         style="background: linear-gradient(135deg, #cbd5e1, #94a3b8);">
                        {{ strtoupper(substr($c->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $c->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $c->position->name ?? '—' }}</p>
                    </div>
                    <span class="badge {{ $badgeMap[$c->status] ?? 'badge-gray' }} flex-shrink-0 shadow-sm">{{ $c->getStatusLabel() }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- ═══ CHART ANALYTICS ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- Donut Chart: Status Distribution --}}
        <div class="data-card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    Distribusi Pipeline
                </h3>
            </div>
            <div class="p-6">
                <div style="max-width: 320px; margin: 0 auto;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Bar Chart: Candidates per Position --}}
        <div class="data-card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Kandidat per Posisi
                </h3>
            </div>
            <div class="p-6">
                <canvas id="positionChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Line Chart: Hiring Trend (full width) --}}
    <div class="data-card mt-6">
        <div class="card-header">
            <h3 class="card-title">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Trend Rekrutmen 6 Bulan Terakhir
            </h3>
        </div>
        <div class="p-6">
            <canvas id="hiringTrendChart" style="max-height: 280px;"></canvas>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status labels & colors
        const statusMap = {
            'berkas': { label: 'Seleksi Berkas', color: '#94a3b8' },
            'tes_praktis': { label: 'Tes Praktis', color: '#3b82f6' },
            'wawancara_hr': { label: 'Wawancara HR', color: '#a78bfa' },
            'wawancara_user': { label: 'Wawancara User', color: '#6366f1' },
            'evaluasi_spk': { label: 'Evaluasi SPK', color: '#f59e0b' },
            'hired': { label: 'Diterima', color: '#10b981' },
            'rejected': { label: 'Ditolak', color: '#f43f5e' },
        };

        const statusData = @json($statusCounts);
        const statusLabels = [];
        const statusValues = [];
        const statusColors = [];
        for (const [key, val] of Object.entries(statusData)) {
            statusLabels.push(statusMap[key]?.label || key);
            statusValues.push(val);
            statusColors.push(statusMap[key]?.color || '#94a3b8');
        }

        // Donut Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{ data: statusValues, backgroundColor: statusColors, borderWidth: 3, borderColor: '#ffffff' }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 18, usePointStyle: true, pointStyleWidth: 10, font: { size: 12, family: 'Inter', weight: '500' } } }
                },
                cutout: '70%'
            }
        });

        // Bar Chart: Position breakdown
        const positionData = @json($positionBreakdown);
        const ctxBar = document.getElementById('positionChart').getContext('2d');
        
        // Gradient for Bar chart
        const gradientBar = ctxBar.createLinearGradient(0, 0, 0, 300);
        gradientBar.addColorStop(0, 'rgba(99, 102, 241, 0.95)');
        gradientBar.addColorStop(1, 'rgba(139, 92, 246, 0.4)');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: positionData.map(p => p.name.length > 20 ? p.name.substring(0, 20) + '…' : p.name),
                datasets: [{
                    label: 'Kandidat',
                    data: positionData.map(p => p.count),
                    backgroundColor: gradientBar,
                    borderColor: '#6366f1',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter', size: 11, weight: '500' } }, grid: { color: 'rgba(241, 245, 249, 0.8)' } },
                    x: { ticks: { font: { family: 'Inter', size: 11, weight: '500' } }, grid: { display: false } }
                }
            }
        });

        // Line Chart: Hiring Trend
        const trendData = @json($hiringTrend);
        const ctxLine = document.getElementById('hiringTrendChart').getContext('2d');
        
        // Gradients for Line Chart
        const gradSuccess = ctxLine.createLinearGradient(0, 0, 0, 250);
        gradSuccess.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
        gradSuccess.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        const gradDanger = ctxLine.createLinearGradient(0, 0, 0, 250);
        gradDanger.addColorStop(0, 'rgba(244, 63, 94, 0.15)');
        gradDanger.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: trendData.map(t => t.month),
                datasets: [
                    {
                        label: 'Diterima',
                        data: trendData.map(t => t.hired),
                        borderColor: '#10b981',
                        backgroundColor: gradSuccess,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Ditolak',
                        data: trendData.map(t => t.rejected),
                        borderColor: '#f43f5e',
                        backgroundColor: gradDanger,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top', labels: { padding: 18, usePointStyle: true, font: { size: 12, family: 'Inter', weight: '500' } } } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter', size: 11, weight: '500' } }, grid: { color: 'rgba(241, 245, 249, 0.8)' } },
                    x: { ticks: { font: { family: 'Inter', size: 11, weight: '500' } }, grid: { display: false } }
                }
            }
        });
    });
    </script>

</div>

</x-app-layout>