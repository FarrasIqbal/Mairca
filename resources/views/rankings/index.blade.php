<x-app-layout title="Laporan Ranking MAIRCA" subtitle="Hasil kalkulasi Multi-Attributive Ideal-Real Comparative Analysis">

<div class="space-y-5">

    {{-- Selector --}}
    <div class="data-card">
        <div class="px-6 py-4 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-700">Pilih Posisi untuk Dikalkulasi</p>
        </div>
        <form action="{{ route('rankings.index') }}" method="GET" class="px-6 py-4 flex items-end gap-3 flex-wrap">
            <div class="flex-1 min-w-52">
                <select name="position_id" class="form-input">
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
        <div class="px-6 py-5 border-b border-slate-100"
             style="background: linear-gradient(135deg, #f0f0ff, #e8ecff);">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-lg font-black text-slate-800">{{ $selectedPosition->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Skor Q<sub>i</sub> terkecil = kandidat paling mendekati kondisi ideal</p>
                </div>
                @if($result)
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-700"
                     style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $result['ranked']->count() }} Kandidat Dievaluasi
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
                    <div class="w-14 h-14 rounded-full border-4 border-slate-200 flex items-center justify-center text-lg font-black text-slate-600"
                         style="background: linear-gradient(135deg, #e2e8f0, #cbd5e1);">
                        {{ strtoupper(substr($p2->name,0,1)) }}
                    </div>
                    <p class="text-sm font-bold text-slate-600 mt-2 max-w-[90px] text-center truncate">{{ $p2->name }}</p>
                    <p class="text-xs font-mono text-slate-400 mt-0.5">{{ number_format($p2->mairca_score, 4) }}</p>
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
                    <p class="text-base font-black text-slate-800 mt-2 max-w-[110px] text-center truncate">{{ $p1->name }}</p>
                    <p class="text-xs font-mono font-bold text-indigo-600 mt-0.5">{{ number_format($p1->mairca_score, 4) }}</p>
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
                    <div class="w-12 h-12 rounded-full border-4 border-amber-200 flex items-center justify-center text-base font-black text-amber-700"
                         style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                        {{ strtoupper(substr($p3->name,0,1)) }}
                    </div>
                    <p class="text-sm font-bold text-slate-600 mt-2 max-w-[80px] text-center truncate">{{ $p3->name }}</p>
                    <p class="text-xs font-mono text-slate-400 mt-0.5">{{ number_format($p3->mairca_score, 4) }}</p>
                    <div class="w-16 h-10 rounded-t-xl mt-3 flex items-end justify-center pb-2"
                         style="background: linear-gradient(180deg, #fde68a, #fbbf24);">
                        <span class="text-xl">🥉</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
        <div class="mx-6 h-px bg-slate-100"></div>
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
                    <tr style="{{ $isFirst ? 'background: linear-gradient(to right, #eef2ff, transparent);' : '' }}">
                        <td class="text-center">
                            @if($i===0) <span class="text-xl">🥇</span>
                            @elseif($i===1) <span class="text-xl">🥈</span>
                            @elseif($i===2) <span class="text-xl">🥉</span>
                            @else <span class="text-sm font-bold text-slate-400">{{ $i+1 }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                     style="{{ $isFirst ? 'background: linear-gradient(135deg, #4f46e5, #3b82f6);' : 'background: linear-gradient(135deg, #94a3b8, #64748b);' }}">
                                    {{ strtoupper(substr($c->name,0,1)) }}
                                </div>
                                <p class="font-bold {{ $isFirst ? 'text-indigo-700' : 'text-slate-700' }}">{{ $c->name }}</p>
                            </div>
                        </td>
                        <td class="text-slate-500 text-xs">{{ $c->email }}</td>
                        <td class="text-center">
                            <span class="font-mono font-black text-base {{ $isFirst ? 'text-indigo-600' : 'text-slate-600' }}">
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Info Box --}}
        <div class="px-6 py-5 border-t border-slate-100">
            <div class="flex items-start gap-3 p-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="background: linear-gradient(135deg, #eef2ff, #e0e7ff);">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-700 mb-1">Tentang Metode MAIRCA</p>
                    <p class="text-xs text-slate-500 leading-relaxed">
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

</div>

</x-app-layout>