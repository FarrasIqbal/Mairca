<x-app-layout title="Laporan Ranking MAIRCA" subtitle="Hasil kalkulasi Multi-Attributive Ideal-Real Comparative Analysis">

<div x-data="{ showCalculationModal: false, showConfirmModal: false, confirmAction: '', confirmCandidateName: '', confirmFormId: '' }" class="space-y-5">

    {{-- Selector --}}
    <div class="data-card">
        <div class="px-6 py-4 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-700">Pilih Posisi untuk Dikalkulasi</p>
        </div>
        <form action="{{ route('rankings.index') }}" method="GET" class="px-6 py-4 flex items-end gap-3 flex-wrap">
            <div class="flex-1 min-w-52">
                <select name="position_id" class="form-select">
                    <option value="">— Pilih Posisi —</option>
                    @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" {{ request('position_id')==$pos->id?'selected':'' }}>{{ $pos->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Hitung Ranking
            </button>
        </form>
    </div>

    @if($selectedPosition)
    <div class="data-card">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-br from-indigo-50/50 to-indigo-100/30 dark:from-slate-900 dark:to-slate-950">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-lg font-black text-slate-800 dark:text-slate-100">{{ $selectedPosition->name }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Skor Q<sub>i</sub> terkecil = kandidat paling mendekati kondisi ideal</p>
                </div>
                @if($result)
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" @click="showCalculationModal = true" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-400 border border-slate-200 dark:border-slate-700 transition-colors shadow-sm cursor-pointer">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-600 dark:text-indigo-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Detail Perhitungan MAIRCA
                    </button>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-700 dark:text-indigo-400 bg-white dark:bg-slate-800 border border-indigo-100 dark:border-indigo-900/50">
                        <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $result['ranked']->count() }} Kandidat Dievaluasi
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if(!$result)
        <div class="py-16 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
                 style="background: linear-gradient(135deg, #fffbeb, #fef3c7);">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Data belum tersedia</p>
            <p class="text-xs text-slate-400 mt-1">Pastikan kriteria sudah diatur dan ada kandidat berstatus <span class="font-bold text-amber-600">Evaluasi SPK</span> yang sudah dinilai</p>
        </div>

        @else

        {{-- ── PODIUM TOP 3 ── --}}
        @if($result['ranked']->count() >= 2)
        <div class="px-6 pt-8 pb-4">
            <div class="flex items-end justify-center gap-6">

                {{-- 2nd --}}
                @if(isset($result['ranked'][1]))
                @php $p2 = $result['ranked'][1]; @endphp
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-full border-4 border-slate-200 dark:border-slate-800 flex items-center justify-center text-lg font-black text-slate-600 dark:text-slate-300"
                         style="background: linear-gradient(135deg, #e2e8f0, #cbd5e1);">
                        {{ strtoupper(substr($p2->name,0,1)) }}
                    </div>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300 mt-2 max-w-[90px] text-center truncate">{{ $p2->name }}</p>
                    <p class="text-xs font-mono text-slate-400 dark:text-slate-400 mt-0.5">{{ number_format($p2->mairca_score, 4) }}</p>
                    <div class="w-20 h-14 rounded-t-xl mt-3 flex items-end justify-center pb-2"
                         style="background: linear-gradient(180deg, #e2e8f0, #cbd5e1);">
                        <span class="text-2xl">🥈</span>
                    </div>
                </div>
                @endif

                {{-- 1st --}}
                @php $p1 = $result['ranked'][0]; @endphp
                <div class="flex flex-col items-center -mb-0">
                    <div class="text-2xl mb-1">👑</div>
                    <div class="w-20 h-20 rounded-full border-4 flex items-center justify-center text-2xl font-black text-white"
                         style="background: linear-gradient(135deg, #4f46e5, #3b82f6); border-color: #a5b4fc; box-shadow: 0 8px 24px rgba(79,70,229,0.4);">
                        {{ strtoupper(substr($p1->name,0,1)) }}
                    </div>
                    <p class="text-base font-black text-slate-800 dark:text-white mt-2 max-w-[110px] text-center truncate">{{ $p1->name }}</p>
                    <p class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 mt-0.5">{{ number_format($p1->mairca_score, 4) }}</p>
                    <span class="badge badge-green mt-2 text-[11px]">✓ Sangat Direkomendasikan</span>
                    <div class="w-24 h-20 rounded-t-xl mt-3 flex items-end justify-center pb-2"
                         style="background: linear-gradient(180deg, #6366f1, #4f46e5);">
                        <span class="text-2xl">🥇</span>
                    </div>
                </div>

                {{-- 3rd --}}
                @if(isset($result['ranked'][2]))
                @php $p3 = $result['ranked'][2]; @endphp
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full border-4 border-amber-200 dark:border-amber-950/20 flex items-center justify-center text-base font-black text-amber-700 dark:text-amber-500"
                         style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                        {{ strtoupper(substr($p3->name,0,1)) }}
                    </div>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300 mt-2 max-w-[80px] text-center truncate">{{ $p3->name }}</p>
                    <p class="text-xs font-mono text-slate-400 dark:text-slate-400 mt-0.5">{{ number_format($p3->mairca_score, 4) }}</p>
                    <div class="w-16 h-10 rounded-t-xl mt-3 flex items-end justify-center pb-2"
                         style="background: linear-gradient(180deg, #fde68a, #fbbf24);">
                        <span class="text-xl">🥉</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
        <div class="mx-6 h-px bg-slate-100 dark:bg-slate-800"></div>
        @endif

        {{-- ── FULL TABLE ── --}}
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-center w-16">Rank</th>
                        <th>Kandidat</th>
                        <th>Email</th>
                        <th class="text-center">Skor Q<sub>i</sub></th>
                        <th class="text-center">Keputusan MAIRCA</th>
                        @if(Auth::user()->role === 'hr')
                        <th class="text-center w-40">Aksi Keputusan</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['ranked'] as $i => $c)
                    @php
                        $isFirst = $i === 0;
                        $minQ = $result['ranked']->min('mairca_score');
                        $maxQ = $result['ranked']->max('mairca_score');
                        $threshold = $minQ + ($maxQ - $minQ) * 0.33;
                        $isRec = $c->mairca_score <= $threshold;
                    @endphp
                    <tr class="{{ $isFirst ? 'bg-gradient-to-r from-indigo-50/50 to-transparent dark:from-indigo-950/20 dark:to-transparent' : '' }}">
                        <td class="text-center">
                            @if($i===0) <span class="text-xl">🥇</span>
                            @elseif($i===1) <span class="text-xl">🥈</span>
                            @elseif($i===2) <span class="text-xl">🥉</span>
                            @else <span class="text-sm font-bold text-slate-400 dark:text-slate-500">{{ $i+1 }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                     style="{{ $isFirst ? 'background: linear-gradient(135deg, #4f46e5, #3b82f6);' : 'background: linear-gradient(135deg, #94a3b8, #64748b);' }}">
                                    {{ strtoupper(substr($c->name,0,1)) }}
                                </div>
                                <p class="font-bold {{ $isFirst ? 'text-indigo-700 dark:text-indigo-400' : 'text-slate-700 dark:text-slate-300' }}">{{ $c->name }}</p>
                            </div>
                        </td>
                        <td class="text-slate-500 dark:text-slate-400 text-xs">{{ $c->email }}</td>
                        <td class="text-center">
                            <span class="font-mono font-black text-base {{ $isFirst ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-350' }}">
                                {{ number_format($c->mairca_score, 4) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($isFirst)
                                <span class="badge badge-green">✓ Sangat Direkomendasikan</span>
                            @elseif($isRec)
                                <span class="badge badge-blue">Direkomendasikan</span>
                            @else
                                <span class="badge badge-gray">Alternatif</span>
                            @endif
                        </td>
                        @if(Auth::user()->role === 'hr')
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form id="form-hired-{{ $c->id }}" action="{{ route('candidates.bulkUpdateStatus') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="ids" value="{{ $c->id }}">
                                    <input type="hidden" name="status" value="hired">
                                    <button type="button" @click="confirmAction = 'MENERIMA'; confirmCandidateName = '{{ addslashes($c->name) }}'; confirmFormId = 'form-hired-{{ $c->id }}'; showConfirmModal = true" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold rounded-lg bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 transition-colors cursor-pointer" title="Terima Kandidat">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Terima
                                    </button>
                                </form>
                                <form id="form-rejected-{{ $c->id }}" action="{{ route('candidates.bulkUpdateStatus') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="ids" value="{{ $c->id }}">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="button" @click="confirmAction = 'MENOLAK'; confirmCandidateName = '{{ addslashes($c->name) }}'; confirmFormId = 'form-rejected-{{ $c->id }}'; showConfirmModal = true" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors cursor-pointer" title="Tolak Kandidat">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Info Box --}}
        <div class="px-6 py-5 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100/30 dark:border-indigo-900/20">
                    <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tentang Metode MAIRCA</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        <b>MAIRCA</b> menghitung <i>gap (kesenjangan)</i> antara kondisi teoretis ideal dan kondisi riil setiap kandidat.
                        Skor Q<sub>i</sub> yang <b>lebih kecil</b> berarti kandidat lebih mendekati kondisi ideal.
                        Nilai 0 berarti sempurna. Nilai dihitung dari <b>rata-rata</b> semua penilai (HR + User).
                    </p>
                </div>
            </div>
        </div>

        @endif
    </div>
    @endif

    @if($selectedPosition && $result)
    {{-- Modal: Detail Perhitungan --}}
    <div x-cloak x-show="showCalculationModal" class="modal-overlay" @keydown.escape.window="showCalculationModal = false" style="z-index: 100;">
        <div x-show="showCalculationModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="modal-box w-full max-w-5xl" style="max-height: 90vh; overflow-y: auto;" @click.away="showCalculationModal = false">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white dark:bg-slate-900 z-10">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Langkah Perhitungan Metode MAIRCA</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Detail langkah demi langkah proses SPK MAIRCA untuk posisi {{ $selectedPosition->name }}</p>
                </div>
                <button @click="showCalculationModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-8">
                {{-- Langkah 1 --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-xs font-semibold">1</span>
                        Langkah 1: Mengumpulkan Nilai Evaluasi (Matriks Keputusan X) & Batas Min-Max
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                        <div class="md:col-span-2 space-y-2 text-xs text-slate-600 dark:text-slate-400">
                            <p><strong>Apa artinya?</strong> Di tahap pertama ini, sistem mengumpulkan semua nilai rata-rata yang diberikan oleh para penilai untuk masing-masing kriteria kandidat, membentuk Matriks Keputusan (X).</p>
                            <p><strong>Batas Min-Max:</strong> Nilai tertinggi (Maksimum) dan terendah (Minimum) pada masing-masing kolom kriteria dicari untuk digunakan sebagai tolok ukur pembanding di langkah berikutnya.</p>
                        </div>
                        <div class="flex flex-col justify-center bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm font-mono text-xs">
                            <div class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider mb-2">Rumus Batas Min-Max</div>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">x<sub>j</sub><sup>+</sup></span>
                                    <span class="text-slate-400 dark:text-slate-600">=</span>
                                    <span class="text-slate-880 dark:text-slate-200">max(x<sub>ij</sub>)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-rose-600 dark:text-rose-400 font-bold">x<sub>j</sub><sup>-</sup></span>
                                    <span class="text-slate-400 dark:text-slate-600">=</span>
                                    <span class="text-slate-880 dark:text-slate-200">min(x<sub>ij</sub>)</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/60 text-[9px] text-slate-400 dark:text-slate-500 font-sans space-y-1">
                                <div class="font-semibold text-slate-500 dark:text-slate-400">Keterangan:</div>
                                <div>• <span class="font-mono font-bold text-indigo-500">x<sub>j</sub><sup>+</sup></span>: Nilai Maksimum kriteria j</div>
                                <div>• <span class="font-mono font-bold text-rose-500">x<sub>j</sub><sup>-</sup></span>: Nilai Minimum kriteria j</div>
                                <div>• <span class="font-mono">x<sub>ij</sub></span>: Nilai alternatif i pada kriteria j</div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">Alternatif / Kriteria</th>
                                    @foreach($result['criteria'] as $c)
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">
                                        {{ $c->name }}<br>
                                        <span class="text-[10px] font-normal text-slate-400">({{ $c->type === 'benefit' ? 'Menguntungkan / Benefit' : 'Merugikan / Cost' }})</span>
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['candidates'] as $cand)
                                <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50/50">
                                    <td class="p-3 font-medium text-slate-800 dark:text-slate-200">{{ $cand->name }}</td>
                                    @foreach($result['criteria'] as $c)
                                    <td class="p-3 text-center text-slate-600 dark:text-slate-400 font-mono">
                                        {{ number_format($result['matrixX'][$cand->id][$c->id] ?? 0, 2) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr class="bg-indigo-50/20 font-bold border-b border-slate-100 dark:border-slate-800">
                                    <td class="p-3 text-indigo-700 dark:text-indigo-400">Nilai Maksimum (Batas Atas)</td>
                                    @foreach($result['criteria'] as $c)
                                    <td class="p-3 text-center text-indigo-700 dark:text-indigo-400 font-mono">
                                        {{ number_format($result['minMax'][$c->id]['max'] ?? 0, 2) }}
                                    </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-rose-50/20 font-bold">
                                    <td class="p-3 text-rose-700 dark:text-rose-400">Nilai Minimum (Batas Bawah)</td>
                                    @foreach($result['criteria'] as $c)
                                    <td class="p-3 text-center text-rose-700 dark:text-rose-400 font-mono">
                                        {{ number_format($result['minMax'][$c->id]['min'] ?? 0, 2) }}
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Langkah 2 --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-xs font-semibold">2</span>
                        Langkah 2: Menghitung Nilai Harapan Ideal (Matriks Evaluasi Teoretis Tp)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                        <div class="md:col-span-2 space-y-2 text-xs text-slate-600 dark:text-slate-400">
                            <p><strong>Apa artinya?</strong> Kita berasumsi bahwa semua alternatif/kandidat memiliki peluang awal yang sama besar untuk terpilih (Probabilitas P<sub>a<sub>i</sub></sub> = 1 dibagi jumlah kandidat).</p>
                            <p><strong>Nilai Harapan Ideal (T<sub>p</sub>):</strong> Dihitung dengan mengalikan bobot kriteria w<sub>j</sub> dengan probabilitas awal P<sub>a<sub>i</sub></sub>. Ini adalah standar nilai acuan awal yang adil bagi seluruh peserta.</p>
                        </div>
                        <div class="flex flex-col justify-center bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm font-mono text-xs">
                            <div class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider mb-2">Rumus Nilai Harapan</div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">P<sub>a<sub>i</sub></sub></span>
                                    <span class="text-slate-400 dark:text-slate-600">=</span>
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="border-b border-slate-400 dark:border-slate-600 px-2 pb-0.5">1</span>
                                        <span class="pt-0.5">m</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">t<sub>p<sub>ij</sub></sub></span>
                                    <span class="text-slate-400 dark:text-slate-600">=</span>
                                    <span class="text-slate-880 dark:text-slate-200">P<sub>a<sub>i</sub></sub> &times; w<sub>j</sub></span>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/60 text-[9px] text-slate-400 dark:text-slate-500 font-sans space-y-1">
                                <div class="font-semibold text-slate-500 dark:text-slate-400">Keterangan:</div>
                                <div>• <span class="font-mono">P<sub>a<sub>i</sub></sub></span>: Peluang awal tiap kandidat</div>
                                <div>• <span class="font-mono">m</span>: Jumlah total kandidat</div>
                                <div>• <span class="font-mono font-bold text-indigo-500">t<sub>p<sub>ij</sub></sub></span>: Nilai harapan teoretis</div>
                                <div>• <span class="font-mono">w<sub>j</sub></span>: Bobot kepentingan kriteria j</div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">Kriteria</th>
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">Bobot Kriteria</th>
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">Peluang Awal (Probabilitas)</th>
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">Nilai Harapan Ideal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['criteria'] as $c)
                                <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50/50">
                                    <td class="p-3 font-medium text-slate-800 dark:text-slate-200">{{ $c->name }}</td>
                                    <td class="p-3 text-center text-slate-600 dark:text-slate-400 font-mono">{{ number_format($c->weight, 2) }}</td>
                                    <td class="p-3 text-center text-slate-600 dark:text-slate-400 font-mono">{{ number_format($result['Pai'], 4) }}</td>
                                    <td class="p-3 text-center font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                                        {{ number_format($result['Tp'][$c->id] ?? 0, 4) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Langkah 3 --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-xs font-semibold">3</span>
                        Langkah 3: Menghitung Capaian Riil Peserta (Matriks Evaluasi Riil Tr)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                        <div class="md:col-span-2 space-y-2 text-xs text-slate-600 dark:text-slate-400">
                            <p><strong>Apa artinya?</strong> Nilai asli kandidat (x<sub>ij</sub>) dikonversi ke skala proporsional menggunakan perbandingan batas Min-Max (dari Langkah 1). Tahap ini menentukan persentase capaian riil kandidat terhadap nilai harapan ideal (t<sub>p<sub>ij</sub></sub>).</p>
                            <p>Perhitungannya dibedakan berdasarkan jenis kriteria:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Benefit (Keuntungan):</strong> Semakin tinggi nilai riil kandidat, semakin mendekati nilai harapan ideal.</li>
                                <li><strong>Cost (Biaya):</strong> Semakin rendah nilai riil kandidat, semakin mendekati nilai harapan ideal.</li>
                            </ul>
                        </div>
                        <div class="flex flex-col justify-center bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm font-mono text-xs space-y-3">
                            <div class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider">Rumus Capaian Riil (T<sub>r</sub>)</div>
                            
                            <div>
                                <div class="text-[9px] font-sans text-slate-400 mb-1">Kriteria Benefit:</div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">t<sub>r<sub>ij</sub></sub></span>
                                    <span class="text-slate-400 dark:text-slate-600">=</span>
                                    <span class="text-slate-880 dark:text-slate-200">t<sub>p<sub>ij</sub></sub> &times;</span>
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="border-b border-slate-400 dark:border-slate-600 px-2 pb-0.5">x<sub>ij</sub> - x<sub>j</sub><sup>-</sup></span>
                                        <span class="pt-0.5">x<sub>j</sub><sup>+</sup> - x<sub>j</sub><sup>-</sup></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-dashed border-slate-200 dark:border-slate-800">
                                <div class="text-[9px] font-sans text-slate-400 mb-1">Kriteria Cost:</div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">t<sub>r<sub>ij</sub></sub></span>
                                    <span class="text-slate-400 dark:text-slate-600">=</span>
                                    <span class="text-slate-880 dark:text-slate-200">t<sub>p<sub>ij</sub></sub> &times;</span>
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="border-b border-slate-400 dark:border-slate-600 px-2 pb-0.5">x<sub>j</sub><sup>+</sup> - x<sub>ij</sub></span>
                                        <span class="pt-0.5">x<sub>j</sub><sup>+</sup> - x<sub>j</sub><sup>-</sup></span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 dark:border-slate-800/60 text-[9px] text-slate-400 dark:text-slate-500 font-sans space-y-1">
                                <div class="font-semibold text-slate-500 dark:text-slate-400">Keterangan:</div>
                                <div>• <span class="font-mono font-bold">t<sub>r<sub>ij</sub></sub></span>: Nilai evaluasi riil kandidat</div>
                                <div>• <span class="font-mono text-indigo-500">t<sub>p<sub>ij</sub></sub></span>: Nilai harapan (Langkah 2)</div>
                                <div>• <span class="font-mono">x<sub>ij</sub></span>: Nilai asli evaluasi kandidat</div>
                                <div>• <span class="font-mono text-rose-500">x<sub>j</sub><sup>-</sup></span> & <span class="font-mono text-indigo-500">x<sub>j</sub><sup>+</sup></span>: Batas Min & Max</div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">Alternatif / Kriteria</th>
                                    @foreach($result['criteria'] as $c)
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">
                                        {{ $c->name }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['candidates'] as $cand)
                                <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50/50">
                                    <td class="p-3 font-medium text-slate-800 dark:text-slate-200">{{ $cand->name }}</td>
                                    @foreach($result['criteria'] as $c)
                                    <td class="p-3 text-center text-slate-600 dark:text-slate-400 font-mono">
                                        {{ number_format($result['Tr'][$cand->id][$c->id] ?? 0, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Langkah 4 --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-xs font-semibold">4</span>
                        Langkah 4: Menghitung Selisih / Kesenjangan Nilai (Matriks Gap G)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                        <div class="md:col-span-2 space-y-2 text-xs text-slate-600 dark:text-slate-400">
                            <p><strong>Apa artinya?</strong> Kami menghitung selisih (gap) antara nilai harapan ideal teoretis t<sub>p<sub>ij</sub></sub> (Langkah 2) dan capaian riil t<sub>r<sub>ij</sub></sub> (Langkah 3).</p>
                            <p><strong>Prinsip Utama:</strong> Semakin kecil nilai selisih/gap ini, berarti kemampuan riil kandidat tersebut semakin dekat dengan standar ideal yang diharapkan perusahaan. Jika nilai riil sama dengan nilai ideal teoretis, maka gap akan bernilai 0 (sempurna).</p>
                        </div>
                        <div class="flex flex-col justify-center bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm font-mono text-xs">
                            <div class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider mb-2">Rumus Matriks Gap (G)</div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 dark:text-slate-200">g<sub>ij</sub></span>
                                <span class="text-slate-400 dark:text-slate-600">=</span>
                                <span class="text-indigo-650 dark:text-indigo-400">t<sub>p<sub>ij</sub></sub></span>
                                <span class="text-slate-400 dark:text-slate-600">-</span>
                                <span class="text-slate-800 dark:text-slate-200">t<sub>r<sub>ij</sub></sub></span>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/60 text-[9px] text-slate-400 dark:text-slate-500 font-sans space-y-1">
                                <div class="font-semibold text-slate-500 dark:text-slate-400">Keterangan:</div>
                                <div>• <span class="font-mono font-bold text-indigo-500">g<sub>ij</sub></span>: Selisih/Gap kandidat</div>
                                <div>• <span class="font-mono text-indigo-500">t<sub>p<sub>ij</sub></sub></span>: Nilai harapan ideal</div>
                                <div>• <span class="font-mono">t<sub>r<sub>ij</sub></sub></span>: Nilai riil capaian</div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">Alternatif / Kriteria</th>
                                    @foreach($result['criteria'] as $c)
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">
                                        {{ $c->name }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['candidates'] as $cand)
                                <tr class="border-b border-slate-100 dark:border-slate-800/50 hover:bg-slate-50/50">
                                    <td class="p-3 font-medium text-slate-800 dark:text-slate-200">{{ $cand->name }}</td>
                                    @foreach($result['criteria'] as $c)
                                    <td class="p-3 text-center text-slate-600 dark:text-slate-400 font-mono">
                                        {{ number_format($result['G'][$cand->id][$c->id] ?? 0, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Langkah 5 --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-xs font-semibold">5</span>
                        Langkah 5: Penjumlahan Total Kesenjangan (Skor Qi) & Perangkingan Akhir
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                        <div class="md:col-span-2 space-y-2 text-xs text-slate-600 dark:text-slate-400">
                            <p><strong>Apa artinya?</strong> Nilai kesenjangan (gap) g<sub>ij</sub> dari seluruh kriteria dijumlahkan untuk masing-masing kandidat menjadi satu skor final, yaitu <strong>Skor Q<sub>i</sub></strong>.</p>
                            <p><strong>Hasil Perangkingan:</strong> Kandidat diurutkan dari skor Q<sub>i</sub> terkecil ke terbesar. Skor terkecil (mendekati 0) ditempatkan pada ranking teratas karena mereka memiliki total selisih paling sedikit dari standar ideal perusahaan.</p>
                        </div>
                        <div class="flex flex-col justify-center bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-sm font-mono text-xs">
                            <div class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wider mb-2">Rumus Nilai Akhir (Q<sub>i</sub>)</div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 dark:text-slate-200">Q<sub>i</sub></span>
                                <span class="text-slate-400 dark:text-slate-600">=</span>
                                <div class="flex items-center text-slate-800 dark:text-slate-200 font-bold">
                                    <span class="text-lg font-sans mr-1">&sum;</span>
                                    <div class="flex flex-col text-[8px] leading-none justify-center -mt-0.5 mr-1 text-slate-500 font-sans font-normal">
                                        <span>n</span>
                                        <span>j=1</span>
                                    </div>
                                    <span>g<sub>ij</sub></span>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-800/60 text-[9px] text-slate-400 dark:text-slate-500 font-sans space-y-1">
                                <div class="font-semibold text-slate-500 dark:text-slate-400">Keterangan:</div>
                                <div>• <span class="font-mono font-bold">Q<sub>i</sub></span>: Total skor akhir (Peringkat)</div>
                                <div>• <span class="font-mono">g<sub>ij</sub></span>: Selisih/Gap per kriteria</div>
                                <div>• <span class="font-mono">n</span>: Jumlah seluruh kriteria</div>
                                <div class="text-[8px] italic mt-0.5 text-amber-600 dark:text-amber-500">(Qi terkecil = Terbaik)</div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-800 rounded-xl">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">Nama Kandidat</th>
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">Total Selisih (Skor Qi)</th>
                                    <th class="p-3 font-semibold text-slate-600 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800 text-center">Urutan Ranking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['ranked'] as $rank => $cand)
                                <tr class="border-b border-slate-100 dark:border-slate-800/50 {{ $rank === 0 ? 'bg-indigo-50/10' : '' }}">
                                    <td class="p-3 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $cand->name }}
                                        @if($rank === 0)
                                        <span class="ml-2 text-xs text-indigo-600 dark:text-indigo-400 font-semibold">(Rekomendasi Terbaik)</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center font-mono font-black text-indigo-600 dark:text-indigo-400">{{ number_format($cand->mairca_score, 4) }}</td>
                                    <td class="p-3 text-center font-bold text-slate-800 dark:text-slate-200">#{{ $rank + 1 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex justify-end">
                <button @click="showCalculationModal = false" class="btn-secondary">Tutup Detail</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Custom Confirm Modal --}}
    <div x-cloak x-show="showConfirmModal" class="modal-overlay animate-fade-in" @keydown.escape.window="showConfirmModal = false" style="z-index: 110; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);">
        <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="modal-box w-full max-w-md p-6 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800" @click.away="showConfirmModal = false">
            
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                     :class="confirmAction === 'MENERIMA' ? 'bg-green-50 text-green-600 dark:bg-green-950/50 dark:text-green-400' : 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400'">
                    <template x-if="confirmAction === 'MENERIMA'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="confirmAction === 'MENOLAK'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </template>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-850 dark:text-slate-100" x-text="'Konfirmasi ' + (confirmAction === 'MENERIMA' ? 'Terima Kandidat' : 'Tolak Kandidat')"></h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Apakah Anda yakin ingin <span class="font-semibold" :class="confirmAction === 'MENERIMA' ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400'" x-text="confirmAction.toLowerCase()"></span> kandidat <span class="font-bold text-slate-800 dark:text-slate-200" x-text="confirmCandidateName"></span>?
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2.5">
                <button @click="showConfirmModal = false" class="px-4 py-2 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer">Batal</button>
                <button @click="document.getElementById(confirmFormId).submit()" 
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold rounded-xl text-white transition-colors cursor-pointer shadow-sm border"
                        :class="confirmAction === 'MENERIMA' ? 'bg-green-600 hover:bg-green-700 border-green-600' : 'bg-rose-600 hover:bg-rose-700 border-rose-600'">
                    Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>

</div>

</x-app-layout>