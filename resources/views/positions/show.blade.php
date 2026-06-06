<x-app-layout title="Control Center: {{ $position->name }}" subtitle="Kelola kriteria, kandidat pipeline, penilaian, dan hasil ranking dalam satu tempat">

<div x-data="{
    tab: 'ranking',
    showCriteriaAdd: false,
    showCriteriaEdit: false,
    showCandAdd: false,
    showCandEdit: false,
    cAction: '', cName: '', cType: 'benefit', cWeight: '0.00',
    kAction: '', kName: '', kEmail: '', kStatus: ''
}" class="space-y-5">

    {{-- Tabs --}}
    <div class="data-card">
        <div class="flex">
            <button @click="tab='ranking'"
                    :class="tab==='ranking' ? 'text-indigo-700 border-b-2 border-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-3.5 text-sm text-center transition-all">
                🏆 Ranking MAIRCA
            </button>
            <button @click="tab='nilai'"
                    :class="tab==='nilai' ? 'text-indigo-700 border-b-2 border-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-3.5 text-sm text-center transition-all">
                📝 Input Nilai
            </button>
            @if(Auth::user()->role === 'hr')
            <button @click="tab='pipeline'"
                    :class="tab==='pipeline' ? 'text-indigo-700 border-b-2 border-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-3.5 text-sm text-center transition-all">
                👥 Pipeline Kandidat
            </button>
            <button @click="tab='kriteria'"
                    :class="tab==='kriteria' ? 'text-indigo-700 border-b-2 border-indigo-600 font-bold bg-indigo-50/50' : 'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-3.5 text-sm text-center transition-all">
                ⚙ Kriteria & Bobot
            </button>
            @endif
        </div>
    </div>

    {{-- ══════════════ TAB: RANKING ══════════════ --}}
    <div x-show="tab==='ranking'" x-transition class="data-card">
        @if(!$maircaResult)
        <div class="py-16 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
                 style="background: linear-gradient(135deg, #fffbeb, #fef3c7);">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Kalkulasi belum siap</p>
            <p class="text-xs text-slate-400 mt-1">Pastikan kriteria sudah ada dan ada kandidat berstatus <b class="text-amber-600">Evaluasi SPK</b> yang sudah dinilai.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-center w-16">Rank</th>
                        <th>Kandidat</th>
                        <th class="text-center">Skor Q<sub>i</sub></th>
                        <th class="text-center">Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maircaResult['ranked'] as $i => $c)
                    @php $isFirst = $i === 0; @endphp
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
                                <div>
                                    <p class="font-bold {{ $isFirst ? 'text-indigo-700' : 'text-slate-700' }}">{{ $c->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $c->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center font-mono font-black {{ $isFirst ? 'text-indigo-600' : 'text-slate-600' }}">
                            {{ number_format($c->mairca_score, 4) }}
                        </td>
                        <td class="text-center">
                            @if($isFirst)
                            <span class="badge badge-green">✓ Sangat Direkomendasikan</span>
                            @else
                            <span class="badge badge-gray">Alternatif</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ══════════════ TAB: INPUT NILAI ══════════════ --}}
    <div x-show="tab==='nilai'" x-transition x-cloak class="data-card">
        @if($evalCandidates->isEmpty())
        <div class="py-16 text-center">
            <p class="text-sm font-semibold text-slate-500">Belum ada kandidat di tahap Evaluasi SPK</p>
            <p class="text-xs text-slate-400 mt-1">Pindahkan status kandidat di tab Pipeline terlebih dahulu.</p>
        </div>
        @elseif($criteria->isEmpty())
        <div class="py-16 text-center">
            <p class="text-sm font-semibold text-amber-600">Kriteria belum diatur untuk posisi ini.</p>
        </div>
        @else
        <form action="{{ route('evaluations.storeBulk') }}" method="POST">
            @csrf
            <div class="overflow-x-auto" style="max-height: 500px;">
                <table class="w-full text-sm">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr class="border-b border-slate-100" style="background: #f8fafc;">
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider px-6 py-3 min-w-[180px]">Kandidat</th>
                            @foreach($criteria as $idx => $crit)
                            <th class="text-center text-xs font-bold text-slate-500 uppercase tracking-wider px-4 py-3 min-w-[130px]">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $crit->type==='benefit' ? 'badge-blue' : '' }}" style="{{ $crit->type!=='benefit' ? 'background:#fff7ed; color:#c2410c;' : '' }}">
                                        C{{ $idx+1 }} · {{ $crit->type }}
                                    </span>
                                    <span class="text-[11px] normal-case font-semibold text-slate-600">{{ $crit->name }}</span>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($evalCandidates as $candidate)
                        <tr class="hover:bg-indigo-50/20 transition-colors">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-slate-800">{{ $candidate->name }}</p>
                                <p class="text-xs text-slate-400">{{ $candidate->email }}</p>
                            </td>
                            @foreach($criteria as $crit)
                            @php $val = $existingScores[$candidate->id][$crit->id] ?? ''; @endphp
                            <td class="px-4 py-3.5 text-center">
                                <input type="number" name="scores[{{ $candidate->id }}][{{ $crit->id }}]"
                                       value="{{ $val }}" min="1" max="100" placeholder="—"
                                       class="w-20 text-center text-sm rounded-xl px-2 py-2 border transition-all focus:outline-none"
                                       style="{{ $val ? 'border-color: #818cf8; background: #eef2ff;' : 'border-color: #e2e8f0;' }}"
                                       onfocus="this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'; this.style.borderColor='#6366f1';"
                                       onblur="this.style.boxShadow=''; if(!this.value){this.style.borderColor='#e2e8f0'; this.style.background='white';}else{this.style.borderColor='#818cf8'; this.style.background='#eef2ff';}">
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end" style="background: #fafafa;">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Semua Nilai
                </button>
            </div>
        </form>
        @endif
    </div>

    @if(Auth::user()->role === 'hr')
    {{-- ══════════════ TAB: PIPELINE ══════════════ --}}
    <div x-show="tab==='pipeline'" x-transition x-cloak>
        <div class="flex justify-end mb-3">
            <button @click="showCandAdd=true" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Kandidat
            </button>
        </div>
        <div class="data-card">
            @if($allCandidates->isEmpty())
            <div class="py-14 text-center text-sm text-slate-400">Belum ada kandidat di posisi ini.</div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full data-table">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th>Status Pipeline</th>
                            <th>Jadwal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allCandidates as $cand)
                        @php
                            $badgeMap = ['berkas'=>'badge-gray','tes_praktis'=>'badge-blue','wawancara_hr'=>'badge-purple','wawancara_user'=>'badge-indigo','evaluasi_spk'=>'badge-amber','hired'=>'badge-green','rejected'=>'badge-red'];
                            $nextInterview = $cand->interviewSchedules()->where('status','scheduled')->orderBy('scheduled_at')->first();
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                         style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                        {{ strtoupper(substr($cand->name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $cand->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $cand->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge {{ $badgeMap[$cand->status] ?? 'badge-gray' }}">{{ $cand->getStatusLabel() }}</span></td>
                            <td>
                                @if($nextInterview)
                                <span class="text-xs text-slate-600">{{ $nextInterview->scheduled_at->format('d M, H:i') }}</span>
                                @else
                                <span class="text-xs text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('interviews.create', ['candidate_id'=>$cand->id]) }}"
                                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Jadwalkan Wawancara">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </a>
                                    <button @click="showCandEdit=true; kAction='/candidates/{{ $cand->id }}'; kName='{{ addslashes($cand->name) }}'; kEmail='{{ $cand->email }}'; kStatus='{{ $cand->status }}'"
                                            class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Update Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════ TAB: KRITERIA ══════════════ --}}
    <div x-show="tab==='kriteria'" x-transition x-cloak>
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ round($totalWeight, 2) == 1.0 ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }} border">
                <span class="text-sm font-black {{ round($totalWeight, 2) == 1.0 ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ number_format($totalWeight, 2) }} / 1.00
                </span>
                <span class="badge {{ round($totalWeight, 2) == 1.0 ? 'badge-green' : 'badge-amber' }}">
                    {{ round($totalWeight, 2) == 1.0 ? '✓ Valid' : '⚠ Belum Valid' }}
                </span>
            </div>
            <button @click="showCriteriaAdd=true" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Kriteria
            </button>
        </div>
        <div class="data-card">
            @if($criteria->isEmpty())
            <div class="py-14 text-center text-sm text-slate-400">Belum ada kriteria penilaian.</div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full data-table">
                    <thead>
                        <tr>
                            <th class="w-16">Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Tipe</th>
                            <th>Bobot</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteria as $idx => $crit)
                        <tr>
                            <td class="font-bold text-slate-500">C{{ $idx+1 }}</td>
                            <td class="font-medium text-slate-800">{{ $crit->name }}</td>
                            <td>
                                <span class="badge {{ $crit->type==='benefit' ? 'badge-blue' : '' }}" style="{{ $crit->type!=='benefit' ? 'background:#fff7ed; color:#c2410c;' : '' }}">
                                    {{ ucfirst($crit->type) }}
                                </span>
                            </td>
                            <td class="font-mono font-semibold text-slate-700">{{ $crit->weight }} <span class="text-slate-400 text-xs">({{ $crit->weight * 100 }}%)</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="showCriteriaEdit=true; cAction='{{ route('positions.criteria.update', [$position->id, $crit->id]) }}'; cName='{{ addslashes($crit->name) }}'; cType='{{ $crit->type }}'; cWeight='{{ $crit->weight }}'"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('positions.criteria.destroy', [$position->id, $crit->id]) }}" 
                                          @submit.prevent="window.dispatchEvent(new CustomEvent('confirm-modal', { detail: { title: 'Hapus Kriteria', message: 'Apakah Anda yakin ingin menghapus kriteria {{ addslashes($crit->name) }}?', type: 'danger', confirmBtnText: 'Ya, Hapus', callback: () => $el.submit() } }))">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ══════════════ MODALS ══════════════ --}}

    {{-- Add Criteria --}}
    <div x-show="showCriteriaAdd" x-cloak class="modal-overlay" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0" @click="showCriteriaAdd=false"></div>
        <div class="modal-box" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-slate-100"><p class="font-bold text-slate-800">Tambah Kriteria</p></div>
            <form action="{{ route('positions.criteria.store', $position->id) }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <div><label class="form-label">Nama Kriteria <span class="text-red-500">*</span></label><input type="text" name="name" required class="form-input" placeholder="Contoh: Kemampuan Coding"></div>
                <div><label class="form-label">Tipe <span class="text-red-500">*</span></label><select name="type" required class="form-select"><option value="benefit">Benefit (Semakin tinggi = bagus)</option><option value="cost">Cost (Semakin rendah = bagus)</option></select></div>
                <div><label class="form-label">Bobot <span class="text-red-500">*</span></label><input type="number" name="weight" step="0.01" min="0.01" max="1.00" required class="form-input" placeholder="0.25"></div>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100"><button type="button" @click="showCriteriaAdd=false" class="btn-secondary">Batal</button><button type="submit" class="btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>

    {{-- Edit Criteria --}}
    <div x-show="showCriteriaEdit" x-cloak class="modal-overlay" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0" @click="showCriteriaEdit=false"></div>
        <div class="modal-box" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-slate-100"><p class="font-bold text-slate-800">Edit Kriteria</p></div>
            <form :action="cAction" method="POST" class="px-6 py-5 space-y-4">
                @csrf @method('PUT')
                <div><label class="form-label">Nama Kriteria</label><input type="text" name="name" x-model="cName" required class="form-input"></div>
                <div><label class="form-label">Tipe</label><select name="type" x-model="cType" required class="form-select"><option value="benefit">Benefit</option><option value="cost">Cost</option></select></div>
                <div><label class="form-label">Bobot</label><input type="number" name="weight" step="0.01" x-model="cWeight" required class="form-input"></div>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100"><button type="button" @click="showCriteriaEdit=false" class="btn-secondary">Batal</button><button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 2px 8px rgba(245,158,11,0.3);">Update</button></div>
            </form>
        </div>
    </div>

    {{-- Add Candidate --}}
    <div x-show="showCandAdd" x-cloak class="modal-overlay" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0" @click="showCandAdd=false"></div>
        <div class="modal-box" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-slate-100"><p class="font-bold text-slate-800">Tambah Kandidat ke {{ $position->name }}</p></div>
            <form action="{{ route('candidates.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="position_id" value="{{ $position->id }}">
                <div><label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" name="name" required class="form-input"></div>
                <div><label class="form-label">Email <span class="text-red-500">*</span></label><input type="email" name="email" required class="form-input"></div>
                <div><label class="form-label">No. HP</label><input type="text" name="phone" class="form-input" placeholder="08xx"></div>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100"><button type="button" @click="showCandAdd=false" class="btn-secondary">Batal</button><button type="submit" class="btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>

    {{-- Edit Candidate Status --}}
    <div x-show="showCandEdit" x-cloak class="modal-overlay" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0" @click="showCandEdit=false"></div>
        <div class="modal-box" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-slate-100"><p class="font-bold text-slate-800">Update Status Kandidat</p></div>
            <form :action="kAction" method="POST" class="px-6 py-5 space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="position_id" value="{{ $position->id }}">
                <div><label class="form-label">Nama</label><input type="text" name="name" x-model="kName" required class="form-input"></div>
                <div><label class="form-label">Email</label><input type="email" name="email" x-model="kEmail" required class="form-input"></div>
                <div>
                    <label class="form-label">Status Pipeline</label>
                    <select name="status" x-model="kStatus" required class="form-select">
                        <option value="berkas">1. Seleksi Berkas</option>
                        <option value="tes_praktis">2. Tes Praktis</option>
                        <option value="wawancara_hr">3. Wawancara HR</option>
                        <option value="wawancara_user">4. Wawancara User</option>
                        <option value="evaluasi_spk">5. Evaluasi SPK (Siap MAIRCA)</option>
                        <option value="hired">✓ Diterima (Hired)</option>
                        <option value="rejected">✗ Ditolak (Rejected)</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5 bg-amber-50 px-3 py-2 rounded-lg border border-amber-100">💡 Set ke <b class="text-amber-700">Evaluasi SPK</b> agar kandidat masuk ke form penilaian MAIRCA.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100"><button type="button" @click="showCandEdit=false" class="btn-secondary">Batal</button><button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 2px 8px rgba(245,158,11,0.3);">Update Status</button></div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>