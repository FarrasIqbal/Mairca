<x-app-layout title="Input Penilaian" subtitle="Isi matriks nilai kandidat setelah sesi wawancara selesai">

<div class="space-y-5">

    {{-- Info Banner --}}
    @php $isHr = Auth::user()->role === 'hr'; @endphp
    <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border text-sm font-medium"
         style="{{ $isHr ? 'background: linear-gradient(135deg, #eff6ff, #dbeafe); border-color: #bfdbfe; color: #1d4ed8;' : 'background: linear-gradient(135deg, #f5f3ff, #ede9fe); border-color: #c4b5fd; color: #6d28d9;' }}">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
             style="{{ $isHr ? 'background: #2563eb;' : 'background: #7c3aed;' }}">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold">Sesi Penilaian: {{ $isHr ? 'Wawancara HR (Tahap 1)' : 'Wawancara User / Reviewer (Tahap 2)' }}</p>
            <p class="text-xs opacity-75 mt-0.5">Nilai yang Anda masukkan akan digunakan dalam kalkulasi metode MAIRCA</p>
        </div>
    </div>

    {{-- Filter Posisi --}}
    <div class="data-card">
        <div class="px-6 py-4 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-700">Pilih Posisi yang Akan Dinilai</p>
        </div>
        <form action="{{ route('evaluations.index') }}" method="GET" class="px-6 py-4 flex items-end gap-3 flex-wrap">
            <div class="flex-1 min-w-48">
                <select name="position_id" class="form-input">
                    <option value="">— Pilih Posisi —</option>
                    @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" {{ request('position_id')==$pos->id?'selected':'' }}>{{ $pos->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Tampilkan
            </button>
        </form>
    </div>

    @if($selectedPosition)
    <div class="data-card">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <p class="font-bold text-slate-800">{{ $selectedPosition->name }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Skala nilai: <span class="font-semibold text-slate-600">1 – 100</span> · Tersimpan otomatis per pengisian</p>
            </div>
            <span class="badge {{ $isHr ? 'badge-blue' : 'badge-purple' }}">
                Sesi {{ $isHr ? 'HR' : 'User' }}
            </span>
        </div>

        @if($candidates->isEmpty())
        <div class="py-16 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
                 style="background: linear-gradient(135deg, #fffbeb, #fef3c7);">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Belum ada kandidat siap dinilai</p>
            <p class="text-xs text-slate-400 mt-1">Kandidat harus berstatus <span class="font-bold text-amber-600">Evaluasi SPK</span></p>
        </div>

        @elseif($criteria->isEmpty())
        <div class="py-16 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3" style="background: linear-gradient(135deg, #fef2f2, #fee2e2);">
                <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Kriteria penilaian belum diatur</p>
            <p class="text-xs text-slate-400 mt-1">HRD perlu menambahkan kriteria untuk posisi ini</p>
        </div>

        @else
        <form action="{{ route('evaluations.storeBulk') }}" method="POST">
            @csrf
            <input type="hidden" name="interview_type" value="{{ $interviewType }}">

            <div class="overflow-x-auto" style="max-height: 520px;">
                <table class="w-full text-sm">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr class="border-b border-slate-100" style="background: #f8fafc;">
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider px-6 py-3 min-w-[200px]">Kandidat</th>
                            @foreach($criteria as $i => $crit)
                            <th class="text-center text-xs font-bold text-slate-500 uppercase tracking-wider px-4 py-3 min-w-[140px]">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $crit->type==='benefit' ? 'badge-blue' : '' }}" style="{{ $crit->type!=='benefit' ? 'background:#fff7ed; color:#c2410c;' : '' }}">
                                        C{{ $i+1 }} · {{ $crit->type }}
                                    </span>
                                    <span class="text-[11px] normal-case font-semibold text-slate-600 leading-tight text-center max-w-[120px]">{{ $crit->name }}</span>
                                    <span class="text-[10px] normal-case text-slate-400 font-normal">bobot: {{ $crit->weight }}</span>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($candidates as $candidate)
                        <tr class="hover:bg-indigo-50/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                         style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                        {{ strtoupper(substr($candidate->name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $candidate->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $candidate->email }}</p>
                                    </div>
                                </div>
                            </td>
                            @foreach($criteria as $crit)
                            @php $val = $existingScores[$candidate->id][$crit->id] ?? ''; @endphp
                            <td class="px-4 py-4 text-center">
                                <input type="number"
                                       name="scores[{{ $candidate->id }}][{{ $crit->id }}]"
                                       value="{{ $val }}" min="1" max="100" placeholder="—"
                                       class="w-20 text-center text-sm rounded-xl px-2 py-2 border transition-all focus:outline-none"
                                       style="{{ $val ? 'border-color: #818cf8; background: #eef2ff; color: #3730a3;' : 'border-color: #e2e8f0; background: white;' }}"
                                       onfocus="this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'; this.style.borderColor='#6366f1';"
                                       onblur="this.style.boxShadow=''; if(!this.value){this.style.borderColor='#e2e8f0'; this.style.background='white';}else{this.style.borderColor='#818cf8'; this.style.background='#eef2ff';}">
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between" style="background: #fafafa;">
                <p class="text-xs text-slate-400 font-medium">
                    <span class="font-semibold text-slate-600">{{ $candidates->count() }}</span> kandidat ·
                    <span class="font-semibold text-slate-600">{{ $criteria->count() }}</span> kriteria
                </p>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Semua Nilai
                </button>
            </div>
        </form>
        @endif
    </div>
    @endif

</div>

</x-app-layout>