<x-app-layout title="Detail Kandidat" subtitle="Profil lengkap dan riwayat rekrutmen kandidat">

<div class="space-y-6" x-data="{ tab: 'timeline', statusModalOpen: false }">

    {{-- ── 1. HEADER CARD & QUICK ACTIONS ── --}}
    <div class="data-card overflow-hidden">
        {{-- Decorative Header Accent --}}
        <div class="h-2 w-full bg-gradient-to-r from-violet-500 via-indigo-500 to-blue-500"></div>
        
        <div class="p-6 sm:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            {{-- Left: Profile Details --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-black text-white shadow-lg flex-shrink-0"
                     style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                    {{ strtoupper(substr($candidate->name, 0, 1)) }}
                </div>
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $candidate->name }}</h1>
                        <span class="badge badge-indigo text-xs py-1">{{ $candidate->position->name ?? '—' }}</span>
                        <span class="badge {{ $candidate->status === 'rejected' ? 'badge-red' : ($candidate->status === 'hired' ? 'badge-green' : 'badge-' . $candidate->getStatusColor()) }} text-xs py-1">
                            {{ $candidate->getStatusLabel() }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-y-1 gap-x-4 text-xs font-semibold text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $candidate->email }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $candidate->phone ?? 'Tidak ada nomor telepon' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Terdaftar: {{ $candidate->created_at->translatedFormat('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right: Actions --}}
            <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-center sm:justify-start lg:justify-end">
                <a href="{{ route('candidates.index') }}" class="btn-secondary py-2.5 px-4 rounded-xl flex items-center gap-2 text-xs font-bold shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>

                @if($candidate->resume_path)
                <a href="{{ route('candidates.resume', $candidate->id) }}" class="btn-primary py-2.5 px-4 rounded-xl flex items-center gap-2 text-xs font-bold shadow-md shadow-indigo-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Resume
                </a>
                @endif

                <button @click="statusModalOpen = true" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs flex items-center gap-2 shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Ubah Status
                </button>

                <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat ini dari sistem? Semua data relasi (skor, log, wawancara) akan ikut dihapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger py-2.5 px-4 rounded-xl flex items-center gap-2 text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── 2. STATUS PIPELINE VISUALIZATION ── --}}
    <div class="data-card p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5">Tahapan Pipeline</h3>
        
        @php
            $orderedStatuses = ['berkas', 'tes_praktis', 'wawancara_hr', 'wawancara_user', 'evaluasi_spk', 'hired'];
            $currentIndex = array_search($candidate->status, $orderedStatuses);
            
            // if candidate is rejected, we show it with red styling instead
            $isRejected = $candidate->status === 'rejected';
        @endphp

        <div class="relative flex items-center justify-between w-full max-w-4xl mx-auto px-4 py-3">
            {{-- Background track line --}}
            <div class="absolute left-6 right-6 top-1/2 h-1 bg-slate-100 -translate-y-1/2 rounded-full z-0"></div>
            {{-- Completed progress line --}}
            @if(!$isRejected && $currentIndex !== false)
                <div class="absolute left-6 top-1/2 h-1 bg-indigo-500 -translate-y-1/2 rounded-full z-0 transition-all duration-500"
                     style="width: {{ ($currentIndex / (count($orderedStatuses) - 1)) * 100 }}%;"></div>
            @endif

            @foreach($orderedStatuses as $index => $statKey)
                @php
                    $statLabel = $statuses[$statKey] ?? $statKey;
                    $isCompleted = !$isRejected && ($currentIndex !== false && $index <= $currentIndex);
                    $isCurrent = !$isRejected && ($currentIndex !== false && $index === $currentIndex);
                @endphp
                <div class="flex flex-col items-center z-10 relative">
                    {{-- Status Orb --}}
                    <div class="w-10 h-10 rounded-full border-4 flex items-center justify-center transition-all duration-300
                        {{ $isCurrent ? 'bg-indigo-600 border-indigo-200 text-white animate-pulse' : ($isCompleted ? 'bg-indigo-500 border-indigo-100 text-white' : 'bg-white border-slate-200 text-slate-400') }}">
                        @if($isCompleted && !$isCurrent)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                        @endif
                    </div>
                    <span class="mt-2 text-[10px] sm:text-xs font-semibold text-center leading-tight
                        {{ $isCurrent ? 'text-indigo-600 font-bold' : ($isCompleted ? 'text-slate-700' : 'text-slate-400') }}">
                        {{ $statLabel }}
                    </span>
                </div>
            @endforeach
        </div>

        @if($isRejected)
            <div class="mt-6 p-4 rounded-xl bg-red-50/50 border border-red-100 text-center">
                <p class="text-xs font-bold text-red-700 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Proses Dihentikan: Kandidat berstatus Ditolak (Rejected)
                </p>
            </div>
        @endif
    </div>

    {{-- ── 2.2 PRACTICAL TEST DETAIL WIDGET (IF STAGE IS TES PRAKTIS OR TEST IS SUBMITTED) ── --}}
    @php $test = $candidate->ensurePracticalTest(); @endphp
    <div class="data-card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    Modul Evaluasi Tes Praktis
                </h3>
                <p class="text-xs text-slate-400">Kelola pengerjaan lembar ujian praktis kompetensi kandidat.</p>
            </div>
            
            @if($test->submitted_at)
                <div class="flex items-center gap-2">
                    <div class="text-center px-4 py-1 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="block text-[8px] font-bold text-slate-400 uppercase leading-none">Skor Akhir</span>
                        <span class="text-sm font-black text-slate-800 leading-none mt-1 inline-block">{{ $test->score }}</span>
                    </div>
                    @if($test->is_suitable)
                        <span class="badge badge-green text-[9px]">Sesuai Standar</span>
                    @else
                        <span class="badge badge-amber text-[9px]">Di Bawah Standar</span>
                    @endif
                </div>
            @else
                <span class="badge badge-blue text-[9px]">Menunggu Pengerjaan</span>
            @endif
        </div>

        @if(!$test->submitted_at)
            {{-- Candidate has not submitted the test yet --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-5 bg-slate-50 border border-slate-100 p-4.5 rounded-2xl">
                <div class="space-y-1 text-center md:text-left">
                    <p class="text-xs font-bold text-slate-700">Link Ujian Praktis Kandidat</p>
                    <p class="text-[11px] text-slate-400 max-w-md leading-relaxed">
                        Salin link unik di bawah dan kirimkan ke kandidat agar mereka dapat mengerjakan tes praktis secara mandiri.
                    </p>
                </div>
                
                <div x-data="{ copied: false }" class="flex items-center gap-2 w-full md:w-auto">
                    <input type="text" readonly value="{{ route('public.test.show', $test->token) }}" 
                           id="testLinkInput"
                           class="form-input text-xs w-full md:w-64 py-2 px-3 bg-white border-slate-200 rounded-lg select-all">
                    
                    <button type="button" 
                            @click="
                                navigator.clipboard.writeText('{{ route('public.test.show', $test->token) }}');
                                copied = true;
                                setTimeout(() => copied = false, 2000);
                            "
                            class="btn-primary py-2 px-4 rounded-xl text-xs font-bold flex-shrink-0 flex items-center gap-1.5 shadow-sm">
                        <span x-text="copied ? 'Disalin! ✓' : 'Salin Link'"></span>
                    </button>
                </div>
            </div>
        @else
            {{-- Candidate has submitted the test --}}
            <div class="space-y-4">
                <div class="p-4 bg-slate-50 border border-slate-100/80 rounded-2xl text-xs text-slate-500 space-y-1.5">
                    <p class="font-bold text-slate-700 uppercase tracking-wider text-[9px]">Informasi Penilaian:</p>
                    <p>Dikirimkan pada: <span class="font-bold text-slate-700">{{ $test->submitted_at->translatedFormat('d M Y, H:i') }} WIB</span></p>
                    <p>Ambang Batas Kelulusan: <span class="font-bold text-slate-700">{{ $test->passing_score }}</span></p>
                    <p class="flex items-center gap-1.5">
                        Rekomendasi Hasil: 
                        @if($test->is_suitable)
                            <span class="text-emerald-600 font-extrabold bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">LULOS / SESUAI</span>
                        @else
                            <span class="text-amber-600 font-extrabold bg-amber-50 border border-amber-100 px-2 py-0.5 rounded">DI BAWAH STANDAR</span>
                        @endif
                    </p>
                </div>

                {{-- Display Answers --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lembar Jawaban Kandidat:</p>
                    
                    @php
                        // Dynamically resolve questions text based on position to show alongside responses
                        $positionName = strtolower($candidate->position->name ?? '');
                        if (str_contains($positionName, 'seo') || str_contains($positionName, 'search engine')) {
                            $qTexts = [
                                1 => 'Bagaimana metodologi Anda dalam melakukan audit teknis SEO pada website e-commerce yang memiliki ribuan halaman dengan masalah duplikasi konten?',
                                2 => 'Deskripsikan alur riset kata kunci (keyword research) Anda untuk menemukan peluang kata kunci yang berpotensi mendatangkan trafik transaksional berkualitas tinggi.',
                                3 => 'Sebutkan 3 metrik utama di Google Search Console yang paling sering Anda pantau, dan jelaskan bagaimana Anda menindaklanjuti fluktuasi negatif dari masing-masing metrik tersebut.',
                            ];
                        } elseif (str_contains($positionName, 'developer') || str_contains($positionName, 'programmer') || str_contains($positionName, 'engineer') || str_contains($positionName, 'tech')) {
                            $qTexts = [
                                1 => 'Bagaimana Anda merancang arsitektur database yang efisien dan berskala besar untuk fitur real-time chat pada aplikasi berbasis web?',
                                2 => 'Jelaskan perbedaan mendasar antara RESTful API dan GraphQL, serta berikan contoh skenario nyata di mana Anda akan memilih salah satunya dibanding yang lain.',
                                3 => 'Bagaimana cara Anda mendeteksi, mendiagnosis, dan menyelesaikan bottleneck performa (seperti N+1 query) pada aplikasi berbasis PHP/Laravel?',
                            ];
                        } else {
                            $qTexts = [
                                1 => 'Jelaskan rencana kerja taktis dan strategis 30-60-90 hari pertama Anda jika diterima bergabung di posisi ini.',
                                2 => 'Bagaimana cara Anda menyikapi situasi kerja di mana Anda harus menyelesaikan tugas dengan tenggat waktu ketat, namun terjadi perubahan ruang lingkup kerja (scope creep) di tengah jalan?',
                                3 => 'Berikan satu contoh studi kasus nyata dari pengalaman kerja Anda sebelumnya di mana Anda berhasil memecahkan masalah kompleks atau konflik profesional secara objektif.',
                            ];
                        }
                    @endphp

                    @if(is_array($test->answers))
                        @foreach($test->answers as $qId => $answer)
                            <div class="bg-slate-50/50 hover:bg-slate-50 border border-slate-100 p-4.5 rounded-2xl transition-colors space-y-2">
                                <p class="text-xs font-bold text-slate-800 leading-relaxed">
                                    Soal {{ $loop->iteration }}: {{ $qTexts[$qId] ?? 'Pertanyaan ' . $qId }}
                                </p>
                                <p class="text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-100/60 leading-relaxed whitespace-pre-wrap">
                                    {{ $answer }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-slate-400">Tidak ada rincian jawaban.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    {{-- ── 2.5 QUICK STATUS PROGRESS ACTION (MAKES REC PROCESS TIGHT & EASY) ── --}}
    @if(!$isRejected && $candidate->status !== 'hired')
        @php
            $nextStageMap = [
                'berkas' => 'tes_praktis',
                'tes_praktis' => 'wawancara_hr',
                'wawancara_hr' => 'wawancara_user',
                'wawancara_user' => 'evaluasi_spk',
                'evaluasi_spk' => 'hired',
            ];
            $nextStageKey = $nextStageMap[$candidate->status] ?? null;
        @endphp

        @if($nextStageKey)
            <div class="bg-white rounded-xl p-5 border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-center sm:text-left">
                    <p class="text-sm font-bold text-slate-800">Tindakan Cepat Tahap Berikutnya</p>
                    <p class="text-xs text-slate-400">Pindahkan status kandidat ini secara instan ke tahap berikutnya setelah menyelesaikan kualifikasi.</p>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="position_id" value="{{ $candidate->position_id }}">
                        <input type="hidden" name="name" value="{{ $candidate->name }}">
                        <input type="hidden" name="email" value="{{ $candidate->email }}">
                        <input type="hidden" name="phone" value="{{ $candidate->phone }}">
                        <input type="hidden" name="status" value="{{ $nextStageKey }}">
                        
                        <button type="submit" class="btn-primary flex items-center gap-1.5 py-2 px-4 text-xs font-bold uppercase tracking-wider">
                            <span>Loloskan ke {{ $statuses[$nextStageKey] }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </form>

                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak kandidat ini?');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="position_id" value="{{ $candidate->position_id }}">
                        <input type="hidden" name="name" value="{{ $candidate->name }}">
                        <input type="hidden" name="email" value="{{ $candidate->email }}">
                        <input type="hidden" name="phone" value="{{ $candidate->phone }}">
                        <input type="hidden" name="status" value="rejected">
                        
                        <button type="submit" class="btn-secondary text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200 hover:border-red-300 py-2 px-4 text-xs font-bold uppercase tracking-wider">
                            Tolak Kandidat
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif

    {{-- ── 3. TABS NAVIGATION ── --}}
    <div class="flex items-center border-b border-slate-200 gap-1 mt-6">
        <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-400 font-semibold hover:text-slate-600 hover:border-slate-300'" class="px-5 py-3 border-b-2 text-sm transition-all duration-150">
            Riwayat Aktivitas
        </button>
        <button @click="tab = 'wawancara'" :class="tab === 'wawancara' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-400 font-semibold hover:text-slate-600 hover:border-slate-300'" class="px-5 py-3 border-b-2 text-sm transition-all duration-150 relative">
            Jadwal Wawancara
            @if($candidate->interviewSchedules->count() > 0)
                <span class="absolute top-2 right-1.5 w-4 h-4 bg-indigo-500 rounded-full text-[9px] text-white flex items-center justify-center font-bold">{{ $candidate->interviewSchedules->count() }}</span>
            @endif
        </button>
        <button @click="tab = 'evaluasi'" :class="tab === 'evaluasi' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-400 font-semibold hover:text-slate-600 hover:border-slate-300'" class="px-5 py-3 border-b-2 text-sm transition-all duration-150 relative">
            Skor Penilaian
            @if($candidate->evaluations->count() > 0)
                <span class="absolute top-2 right-1.5 w-4 h-4 bg-amber-500 rounded-full text-[9px] text-white flex items-center justify-center font-bold">{{ $candidate->evaluations->count() }}</span>
            @endif
        </button>
    </div>

    {{-- ── 4. TAB CONTENTS ── --}}
    
    {{-- TAB: TIMELINE --}}
    <div x-show="tab === 'timeline'" class="space-y-4" x-transition>
        <div class="data-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="card-title">Timeline & Audit Trail</h2>
                <span class="text-xs font-semibold text-slate-400">Total {{ $candidate->activityLogs->count() }} log aktivitas</span>
            </div>

            @if($candidate->activityLogs->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-sm font-semibold text-slate-400">Belum ada riwayat aktivitas.</p>
                </div>
            @else
                <div class="relative border-l-2 border-slate-100 ml-3.5 space-y-6 py-2">
                    @foreach($candidate->activityLogs->sortByDesc('created_at') as $log)
                        @php
                            $dotColor = match($log->action) {
                                'created'         => '#10b981',
                                'status_changed'  => '#3b82f6',
                                'resume_uploaded' => '#8b5cf6',
                                default           => '#64748b'
                            };
                        @endphp
                        <div class="relative pl-6">
                            {{-- Timeline Indicator Dot --}}
                            <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full border-2 border-white shadow-sm"
                                 style="background-color: {{ $dotColor }};"></div>
                            
                            <div class="bg-slate-50/50 hover:bg-slate-50 border border-slate-100 rounded-xl p-4 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-1.5">
                                    <p class="text-sm font-bold text-slate-700">{{ $log->description }}</p>
                                    <span class="text-[10px] font-semibold text-slate-400">{{ $log->created_at->translatedFormat('d M Y - H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Operator: {{ $log->user->name ?? 'System' }}</span>
                                </div>

                                @if($log->action === 'status_changed' && $log->old_value && $log->new_value)
                                    <div class="mt-2.5 flex items-center gap-2 text-xs">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold">{{ $statuses[$log->old_value] ?? $log->old_value }}</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 font-semibold">{{ $statuses[$log->new_value] ?? $log->new_value }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- TAB: WAWANCARA --}}
    <div x-show="tab === 'wawancara'" class="space-y-4" x-transition>
        <div class="data-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="card-title">Jadwal Wawancara</h2>
                <a href="{{ route('interviews.index') }}" class="btn-primary py-2 px-3.5 rounded-lg flex items-center gap-1.5 text-xs font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Kelola Jadwal
                </a>
            </div>

            @if($candidate->interviewSchedules->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-sm font-semibold text-slate-400">Belum ada jadwal wawancara.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($candidate->interviewSchedules->sortBy('scheduled_at') as $s)
                        <div class="bg-slate-50/50 border border-slate-100 hover:bg-slate-50 rounded-xl p-5 space-y-4 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <span class="badge {{ $s->type === 'hr' ? 'badge-blue' : 'badge-purple' }} text-[10px]">
                                        {{ $s->type === 'hr' ? 'Interview HR' : 'Interview User' }}
                                    </span>
                                    <p class="text-sm font-bold text-slate-700">Pewawancara: {{ $s->interviewer->name ?? '—' }}</p>
                                </div>

                                @php
                                    $schedColor = match($s->status) {
                                        'scheduled' => 'badge-blue',
                                        'completed' => 'badge-green',
                                        'cancelled' => 'badge-red',
                                        default     => 'badge-gray',
                                    };
                                @endphp
                                <span class="badge {{ $schedColor }} text-[10px]">{{ ucfirst($s->status) }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 py-1.5 border-y border-slate-100 text-xs">
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-0.5">Tanggal</span>
                                    <span class="font-bold text-slate-700">{{ $s->scheduled_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-0.5">Waktu</span>
                                    <span class="font-bold text-slate-700">{{ $s->scheduled_at->format('H:i') }} WIB</span>
                                </div>
                            </div>

                            @if($s->notes)
                                <div class="text-xs">
                                    <span class="block text-slate-400 font-semibold mb-1">Catatan</span>
                                    <p class="text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100">{{ $s->notes }}</p>
                                </div>
                            @endif

                            @if($s->zoom_link)
                                <a href="{{ $s->zoom_link }}" target="_blank" class="w-full inline-flex justify-center items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/10 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                                    Join Zoom Meeting
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- TAB: EVALUASI --}}
    <div x-show="tab === 'evaluasi'" class="space-y-4" x-transition>
        <div class="data-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="card-title">Skor Penilaian Reviewer</h2>
                @if(Auth::user()->role === 'hr')
                    <a href="{{ route('rankings.index') }}" class="btn-secondary py-2 px-3.5 rounded-lg flex items-center gap-1.5 text-xs font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Analisis Ranking
                    </a>
                @else
                    <a href="{{ route('evaluations.index') }}" class="btn-primary py-2 px-3.5 rounded-lg flex items-center gap-1.5 text-xs font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Beri Penilaian
                    </a>
                @endif
            </div>

            @if($candidate->evaluations->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-sm font-semibold text-slate-400">Belum ada data evaluasi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Bobot</th>
                                <th>Penilai</th>
                                <th class="text-center">Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($candidate->evaluations->sortBy('criteria.name') as $eval)
                                <tr>
                                    <td class="font-bold text-slate-700">{{ $eval->criteria->name ?? '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ ($eval->criteria->type ?? 'benefit') === 'benefit' ? 'badge-green' : 'badge-amber' }} text-[10px]">
                                            {{ strtoupper($eval->criteria->type ?? 'benefit') }}
                                        </span>
                                    </td>
                                    <td class="text-center font-semibold text-slate-500">{{ $eval->criteria->weight ?? '0' }}</td>
                                    <td class="font-semibold text-slate-600">{{ $eval->user->name ?? '—' }}</td>
                                    <td class="text-center font-black text-indigo-600">{{ $eval->score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ── 5. CHANGE STATUS MODAL (ALPINE.JS) ── --}}
    <div x-show="statusModalOpen" class="modal-overlay" style="display: none;" x-transition>
        <div class="modal-box max-w-md w-full p-6 space-y-5" @click.away="statusModalOpen = false">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-slate-800">Ubah Status Kandidat</h3>
                <button @click="statusModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Retain position, name, email, phone --}}
                <input type="hidden" name="position_id" value="{{ $candidate->position_id }}">
                <input type="hidden" name="name" value="{{ $candidate->name }}">
                <input type="hidden" name="email" value="{{ $candidate->email }}">
                <input type="hidden" name="phone" value="{{ $candidate->phone }}">

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Status Baru</label>
                    <select name="status" class="form-select" required>
                        @foreach($statuses as $key => $val)
                            <option value="{{ $key }}" {{ $candidate->status === $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Perbarui Resume (Opsional)</label>
                    <input type="file" name="resume" class="form-input text-xs" accept=".pdf,.doc,.docx">
                    <p class="text-[10px] text-slate-400">Format: PDF, DOC, DOCX. Max: 5MB</p>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="statusModalOpen = false" class="btn-secondary py-2 px-4 rounded-xl text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary py-2 px-4 rounded-xl text-xs font-bold shadow-md shadow-indigo-500/10">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>
