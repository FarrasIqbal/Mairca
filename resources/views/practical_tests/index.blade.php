<x-app-layout title="Manajemen Tes Praktis" subtitle="Kelola soal kompetensi posisi dan lakukan penilaian lembar jawaban pelamar">

<div class="space-y-6" x-data="{ 
    tab: '{{ request()->has('position_id') ? 'questions' : 'candidates' }}',
    showCreateQuestion: false,
    showEditQuestion: false,
    editAction: '',
    editQuestionText: '',
    editPlaceholderText: '',
    editGradingGuide: '',
    editMaxScore: 100
}">

    {{-- Tabs Header --}}
    <div class="flex items-center border-b border-slate-200 gap-1">
        <button @click="tab = 'candidates'" :class="tab === 'candidates' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-400 font-semibold hover:text-slate-600 hover:border-slate-300'" class="px-5 py-3 border-b-2 text-sm transition-all duration-150 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Peserta Ujian Praktis
            <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-[10px] text-indigo-600 font-extrabold">{{ $candidates->count() }}</span>
        </button>
        <button @click="tab = 'questions'" :class="tab === 'questions' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-400 font-semibold hover:text-slate-600 hover:border-slate-300'" class="px-5 py-3 border-b-2 text-sm transition-all duration-150 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
            Kelola Soal per Posisi
        </button>
    </div>

    {{-- ═══════════════════════════════════
         TAB 1: PESERTA UJIAN
    ═══════════════════════════════════ --}}
    <div x-show="tab === 'candidates'" class="space-y-4" x-transition>
        
        {{-- Search Header --}}
        <div class="flex items-center justify-between gap-4 flex-wrap bg-white p-4.5 rounded-2xl border border-slate-100 shadow-sm">
            <form method="GET" action="{{ route('admin.practical-tests.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kandidat..." class="form-input pl-9 text-xs">
                </div>
                @if(request()->filled('search'))
                <a href="{{ route('admin.practical-tests.index') }}" class="btn-secondary py-2 px-3 text-xs">✕ Reset</a>
                @endif
            </form>
            <p class="text-xs text-slate-400 font-semibold uppercase">Tahap Seleksi: Tes Praktis</p>
        </div>

        {{-- Candidates Table --}}
        <div class="data-card">
            @if($candidates->isEmpty())
            <div class="py-16 text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-slate-50 border border-slate-100 shadow-inner">
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-500">Tidak ada peserta ujian praktis saat ini</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Daftarkan kandidat baru atau pindahkan status pelamar ke Tes Praktis.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th>Posisi</th>
                            <th class="text-center">Status Ujian</th>
                            <th class="text-center">Skor Akhir</th>
                            <th class="text-center">Kelayakan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($candidates as $candidate)
                        @php $test = $candidate->practicalTest; @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0 bg-gradient-to-tr from-indigo-500 to-violet-500 shadow-sm">
                                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('candidates.show', $candidate->id) }}" class="font-bold text-slate-800 hover:text-indigo-600 hover:underline block leading-tight">{{ $candidate->name }}</a>
                                        <span class="text-[10px] text-slate-400 block font-semibold mt-0.5">{{ $candidate->email }}</span>
                                    </div>
                                </div>
                              <td class="text-center">
                                @if(!$test || !$test->submitted_at)
                                    @if($test && $test->expires_at && \Carbon\Carbon::now()->greaterThan($test->expires_at))
                                        <span class="badge badge-red text-[9px] block mx-auto w-max">Kedaluwarsa</span>
                                        <span class="text-[9px] text-slate-400 font-semibold block mt-1">Batas: {{ $test->expires_at->translatedFormat('d M') }}</span>
                                    @else
                                        <span class="badge badge-gray text-[9px] block mx-auto w-max">Menunggu Jawaban</span>
                                        @if($test && $test->expires_at)
                                            <span class="text-[9px] text-slate-500 font-semibold block mt-1">s/d {{ $test->expires_at->translatedFormat('d M, H:i') }}</span>
                                        @endif
                                    @endif
                                @else
                                    <span class="badge badge-blue text-[9px]">Sudah Dikirim</span>
                                @endif
                            </td>
                            <td class="text-center font-black text-indigo-600 text-sm">
                                {{ ($test && $test->score !== null) ? $test->score : '—' }}
                            </td>
                            <td class="text-center">
                                @if(!$test || !$test->submitted_at)
                                    <span class="text-xs text-slate-300">—</span>
                                @elseif($test->is_suitable)
                                    <span class="badge badge-green text-[9px]">✓ Sesuai</span>
                                @else
                                    <span class="badge badge-red text-[9px]">⚠ Di Bawah Standar</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($test && $test->submitted_at)
                                    <a href="{{ route('admin.practical-tests.evaluate', $candidate->id) }}" class="btn-primary py-1.5 px-3 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                        Beri Penilaian
                                    </a>
                                @else
                                    @if($test && $test->expires_at && \Carbon\Carbon::now()->greaterThan($test->expires_at))
                                        <form action="{{ route('admin.practical-tests.candidates.extend', $candidate->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="btn-secondary py-1.5 px-3 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-amber-200 text-amber-700 hover:bg-amber-50">
                                                Perpanjang 3 Hari
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="btn-secondary py-1.5 px-3 rounded-lg text-[10px] font-bold uppercase tracking-wider opacity-40 cursor-not-allowed">
                                            Belum Submit
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>

    {{-- ──══════════════════════════════════
         TAB 2: KELOLA SOAL UJIAN
    ═══════════════════════════════════ --}}
    <div x-show="tab === 'questions'" class="space-y-4" x-transition>
        
        {{-- Position Selector --}}
        <div class="flex items-center justify-between gap-4 flex-wrap bg-white p-4.5 rounded-2xl border border-slate-100 shadow-sm">
            <form method="GET" action="{{ route('admin.practical-tests.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="position_id" value="{{ $activePositionId }}">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest flex-shrink-0">Pilih Posisi Jabatan:</label>
                <select name="position_id" onchange="this.form.submit()" class="form-select w-64 text-xs font-bold">
                    @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" {{ $activePositionId == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                    @endforeach
                </select>
            </form>
            
            @if($activePosition)
            <button @click="showCreateQuestion = true" class="btn-primary flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Soal Baru
            </button>
            @endif
        </div>

        @if($activePosition)
        {{-- Position Test Configuration Settings --}}
        <div class="bg-white p-4.5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-800 text-xs">Pengaturan Waktu Ujian Praktis</h4>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Batas durasi waktu mundur untuk posisi pelamar ini.</p>
                </div>
            </div>
            <form action="{{ route('admin.practical-tests.positions.duration', $activePosition->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <div class="flex items-center gap-1.5">
                    <input type="number" 
                           name="test_duration" 
                           value="{{ $activePosition->test_duration ?? 60 }}" 
                           min="5" 
                           max="480" 
                           required 
                           class="form-input w-24 text-center font-extrabold text-slate-800 text-xs py-1.5"
                           placeholder="60">
                    <span class="text-xs font-bold text-slate-500">Menit</span>
                </div>
                <button type="submit" class="btn-secondary py-1.5 px-3 rounded-xl text-xs font-bold shadow-sm">
                    Simpan Durasi
                </button>
            </form>
        </div>
        @endif

        {{-- Questions Card --}}
        <div class="data-card">
            <div class="card-header bg-slate-50/20">
                <h3 class="card-title">Daftar Pertanyaan Ujian: <span class="text-indigo-600 font-extrabold normal-case">{{ $activePosition->name ?? 'Belum ada posisi' }}</span></h3>
                <span class="text-xs font-semibold text-slate-400">Total: {{ $activePosition->testQuestions->count() ?? 0 }} Soal</span>
            </div>

            @if(!$activePosition || $activePosition->testQuestions->isEmpty())
            <div class="py-16 text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 bg-slate-50 border border-slate-100 shadow-inner">
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-500">Belum ada soal ujian kustom untuk posisi ini</p>
                <p class="text-[10px] text-slate-400 mt-1 max-w-md mx-auto leading-relaxed">
                    💡 Sistem otomatis menyajikan **soal fallback default** berkualitas tinggi saat kandidat ujian jika belum ada soal kustom.
                </p>
                <button type="button" @click="showCreateQuestion = true" class="mt-3 text-xs text-indigo-600 hover:text-indigo-800 font-extrabold hover:underline">+ Buat Soal Kustom Pertama</button>
            </div>
            @else
            <div class="divide-y divide-slate-100">
                @foreach($activePosition->testQuestions as $question)
                <div class="p-6 flex items-start gap-4 hover:bg-slate-50/20 transition-all">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs flex-shrink-0 mt-0.5">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-grow space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-semibold text-slate-800 leading-relaxed">{{ $question->question_text }}</p>
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] text-slate-600 font-bold border border-slate-200">Bobot: {{ $question->max_score ?? 100 }} Poin</span>
                        </div>
                        @if($question->placeholder_text)
                            <div class="p-3 rounded-xl bg-slate-50/50 border border-slate-100 text-[11px] text-slate-500 italic whitespace-pre-wrap">
                                <b>Petunjuk/Placeholder Jawaban:</b><br>{{ $question->placeholder_text }}
                            </div>
                        @endif
                        @if($question->grading_guide)
                            <div class="p-3 rounded-xl bg-emerald-50/40 border border-emerald-100/50 text-[11px] text-emerald-800 whitespace-pre-wrap">
                                <b>Panduan Penilaian / Kunci Jawaban:</b><br>{{ $question->grading_guide }}
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0 ml-4">
                        <button type="button" 
                                @click="
                                    showEditQuestion = true;
                                    editAction = '/admin/practical-tests/questions/{{ $question->id }}';
                                    editQuestionText = '{{ addslashes($question->question_text) }}';
                                    editPlaceholderText = '{{ addslashes($question->placeholder_text) }}';
                                    editGradingGuide = '{{ addslashes($question->grading_guide) }}';
                                    editMaxScore = {{ $question->max_score ?? 100 }};
                                "
                                class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" title="Edit Soal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form action="{{ route('admin.practical-tests.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Hapus soal ujian kustom ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Soal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- ── MODAL: TAMBAH SOAL ── --}}
    <div x-cloak x-show="showCreateQuestion" class="modal-overlay" @click.self="showCreateQuestion = false">
        <div class="modal-box max-w-lg w-full overflow-hidden" x-show="showCreateQuestion" x-transition>
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800">Tambah Soal Ujian Kustom</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Soal kustom untuk: {{ $activePosition->name ?? '—' }}</p>
                </div>
                <button type="button" @click="showCreateQuestion = false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.practical-tests.questions.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="position_id" value="{{ $activePositionId }}">
                <div>
                    <label class="form-label">Isi Soal Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question_text" required rows="4" class="form-input text-xs" placeholder="Tuliskan pertanyaan kompetensi profesional di sini..."></textarea>
                </div>
                <div>
                    <label class="form-label">Petunjuk/Placeholder Jawaban (Opsional)</label>
                    <textarea name="placeholder_text" rows="3" class="form-input text-xs" placeholder="Tuliskan petunjuk penyusunan jawaban atau kata kunci yang dicari..."></textarea>
                </div>
                <div>
                    <label class="form-label">Panduan Penilaian / Kunci Jawaban (Opsional)</label>
                    <textarea name="grading_guide" rows="3" class="form-input text-xs" placeholder="Tuliskan kriteria penilaian, jawaban ideal, atau poin penting yang harus dinilai oleh reviewer..."></textarea>
                </div>
                <div>
                    <label class="form-label">Bobot Skor Maksimal <span class="text-red-500">*</span></label>
                    <input type="number" name="max_score" value="100" required min="1" max="100" class="form-input text-xs">
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan bobot poin ini untuk penilaian proporsional per soal (default: 100).</p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showCreateQuestion = false" class="btn-secondary py-2 px-4 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="btn-primary py-2 px-4 rounded-xl text-xs font-bold shadow-md">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: EDIT SOAL ── --}}
    <div x-cloak x-show="showEditQuestion" class="modal-overlay" @click.self="showEditQuestion = false">
        <div class="modal-box max-w-lg w-full overflow-hidden" x-show="showEditQuestion" x-transition>
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800">Update Soal Ujian Kustom</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Soal kustom untuk: {{ $activePosition->name ?? '—' }}</p>
                </div>
                <button type="button" @click="showEditQuestion = false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="editAction" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="form-label">Isi Soal Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question_text" x-model="editQuestionText" required rows="4" class="form-input text-xs"></textarea>
                </div>
                <div>
                    <label class="form-label">Petunjuk/Placeholder Jawaban (Opsional)</label>
                    <textarea name="placeholder_text" x-model="editPlaceholderText" rows="3" class="form-input text-xs"></textarea>
                </div>
                <div>
                    <label class="form-label">Panduan Penilaian / Kunci Jawaban (Opsional)</label>
                    <textarea name="grading_guide" x-model="editGradingGuide" rows="3" class="form-input text-xs" placeholder="Tuliskan kriteria penilaian atau jawaban ideal..."></textarea>
                </div>
                <div>
                    <label class="form-label">Bobot Skor Maksimal <span class="text-red-500">*</span></label>
                    <input type="number" name="max_score" x-model="editMaxScore" required min="1" max="100" class="form-input text-xs">
                </div>
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showEditQuestion = false" class="btn-secondary py-2 px-4 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="btn-primary py-2 px-4 rounded-xl text-xs font-bold shadow-md shadow-indigo-600/10">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>
