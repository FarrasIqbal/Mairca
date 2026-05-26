<x-app-layout title="Evaluasi & Penilaian Tes Praktis" subtitle="Beri skor evaluasi manual dan tentukan kelayakan kandidat">

<div class="space-y-6">

    {{-- Back navigation --}}
    <div>
        <a href="{{ route('admin.practical-tests.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Manajemen Tes Praktis
        </a>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Lembar Jawaban --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="data-card">
                <div class="card-header bg-slate-50/10">
                    <h3 class="card-title flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lembar Jawaban Ujian Praktis
                    </h3>
                    <span class="text-xs font-semibold text-slate-400">Pengiriman: {{ $test->submitted_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                <div class="p-6 space-y-6 divide-y divide-slate-100">
                    @if(is_array($test->answers))
                        @foreach($test->answers as $qId => $answer)
                            <div class="space-y-3 {{ $loop->first ? '' : 'pt-6' }}">
                                <div class="flex items-start gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs flex-shrink-0">
                                        {{ $loop->iteration }}
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 leading-relaxed pt-0.5">
                                        {{ is_array($questionsList[$qId] ?? null) ? ($questionsList[$qId]['text'] ?? '') : ($questionsList[$qId] ?? 'Pertanyaan Kustom #' . $qId) }}
                                    </p>
                                </div>
                                
                                {{-- Panduan Penilaian / Kunci Jawaban --}}
                                @if(is_array($questionsList[$qId] ?? null) && !empty($questionsList[$qId]['guide']))
                                    <div class="ml-10 bg-emerald-50/50 border border-emerald-100/50 p-4 rounded-xl text-xs text-emerald-800 space-y-1">
                                        <div class="flex items-center gap-1.5 font-bold text-emerald-900">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Panduan Penilaian / Kunci Jawaban:
                                        </div>
                                        <div class="whitespace-pre-wrap pl-5 text-slate-600 font-medium">{{ $questionsList[$qId]['guide'] }}</div>
                                    </div>
                                @endif

                                <div class="ml-10 bg-slate-50 border border-slate-100 p-4.5 rounded-2xl">
                                    <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap font-medium">
                                        {{ $answer }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-8 text-center text-slate-400 text-xs">Rincian lembar jawaban tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right 1 Column: Panel Penilaian --}}
        <div class="space-y-6">
            <div class="data-card p-6 space-y-5 sticky top-24">
                
                {{-- Candidate Profile Summary --}}
                <div class="flex items-center gap-3 pb-4.5 border-b border-slate-100">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0 bg-gradient-to-tr from-indigo-500 to-violet-500 shadow-md">
                        {{ strtoupper(substr($candidate->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-extrabold text-slate-900 truncate text-sm leading-tight">{{ $candidate->name }}</h4>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $candidate->position->name ?? '—' }}</p>
                    </div>
                </div>

                {{-- Automated Grading Status --}}
                <div class="bg-indigo-50/50 border border-indigo-100/50 p-4 rounded-2xl space-y-2.5">
                    <p class="text-[9px] font-bold text-indigo-700 uppercase tracking-widest leading-none">Skor Penilaian Awal</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-black text-indigo-600 leading-none">{{ $test->score }}</span>
                        @if($test->is_suitable)
                            <span class="badge badge-green text-[9px] shadow-sm">Lolos Nilai</span>
                        @else
                            <span class="badge badge-amber text-[9px] shadow-sm">Di Bawah Standar</span>
                        @endif
                    </div>
                    <p class="text-[10px] text-slate-400 leading-relaxed">
                        Skor otomatis dihitung oleh sistem berdasarkan tingkat detail dan komprehensif jawaban kandidat. Anda dapat mengubah nilai ini secara manual.
                    </p>
                </div>

                {{-- Grading Form --}}
                <form action="{{ route('admin.practical-tests.store-evaluation', $candidate->id) }}" method="POST" class="space-y-4.5">
                    @csrf
                    
                    {{-- Individual Question Scores --}}
                    <div class="space-y-3 p-4 bg-slate-50/50 border border-slate-100 rounded-2xl">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Penilaian Per Soal (Poin)</p>
                        @foreach($questionsList as $qId => $qInfo)
                            @php
                                $maxVal = $qInfo['max_score'] ?? 100;
                                $savedScore = $test->question_scores[$qId] ?? null;
                            @endphp
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-semibold text-slate-600 truncate max-w-[150px]">Soal #{{ $loop->iteration }} (Maks {{ $maxVal }}):</span>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <input type="number" 
                                           name="question_scores[{{ $qId }}]" 
                                           value="{{ $savedScore }}" 
                                           min="0" 
                                           max="{{ $maxVal }}" 
                                           class="form-input w-20 text-center font-bold text-slate-800 text-xs py-1"
                                           placeholder="0">
                                    <span class="text-xs text-slate-400">/ {{ $maxVal }}</span>
                                </div>
                            </div>
                        @endforeach
                        <p class="text-[9px] text-slate-400 mt-1 italic leading-relaxed">
                            💡 Total nilai persentase kelulusan otomatis dikalkulasi dari akumulasi poin di atas jika diisi.
                        </p>
                    </div>

                    <div>
                        <label class="form-label">Skor Evaluasi Final / Overwrite (0 - 100) <span class="text-red-500">*</span></label>
                        <input type="number" name="score" value="{{ $test->score }}" min="0" max="100" class="form-input w-full font-bold text-slate-800">
                        <p class="text-[10px] text-slate-400 mt-1">Passing score kelulusan minimum bernilai <b>70</b>.</p>
                    </div>

                    <div>
                        <label class="form-label">Catatan Evaluasi / Ulasan Reviewer</label>
                        <textarea name="reviewer_notes" rows="4" class="form-input text-xs" placeholder="Tuliskan ulasan, kelebihan, dan kelemahan jawaban kompetensi kandidat...">{{ $test->reviewer_notes ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Tindakan Kelanjutan Tahapan <span class="text-red-500">*</span></label>
                        <select name="status" required class="form-select w-full text-xs font-bold">
                            <option value="wawancara_hr" {{ $test->is_suitable ? 'selected' : '' }}>Loloskan ke Wawancara HR</option>
                            <option value="rejected" {{ !$test->is_suitable ? 'selected' : '' }}>Tolak Kandidat (Proses Selesai)</option>
                            <option value="tes_praktis">Pertahankan di Tahap Tes Praktis</option>
                        </select>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit" class="btn-primary w-full py-3 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Simpan & Kirim Evaluasi
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</div>

</x-app-layout>
