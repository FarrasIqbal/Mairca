<x-app-layout title="Pipeline Kandidat" subtitle="Pantau dan kelola status setiap kandidat pelamar">

<div x-data="{
    showCreate: false,
    showEdit: false,
    editAction: '',
    editName: '',
    editEmail: '',
    editPhone: '',
    editPosition: '',
    editStatus: ''
}" class="space-y-5">

    {{-- ── HEADER ROW ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
        <form method="GET" action="{{ route('candidates.index') }}" class="flex items-center gap-2 flex-wrap">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kandidat..."
                       class="form-input pl-9 w-52">
            </div>
            <select name="status" onchange="this.form.submit()" class="form-input w-auto">
                <option value="">Semua Status</option>
                @foreach($statuses as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="position_id" onchange="this.form.submit()" class="form-input w-auto">
                <option value="">Semua Posisi</option>
                @foreach($positions as $pos)
                <option value="{{ $pos->id }}" {{ request('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                @endforeach
            </select>
            @if(request()->hasAny(['search','status','position_id']))
            <a href="{{ route('candidates.index') }}" class="btn-secondary text-xs px-3 py-2">✕ Reset</a>
            @endif
        </form>
        <button @click="showCreate = true" class="btn-primary flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Kandidat
        </button>
    </div>

    {{-- ── PIPELINE STATUS BAR ── --}}
    @php
    $pipelineSteps = [
        'berkas'         => ['label'=>'Seleksi Berkas',   'color'=>'#94a3b8', 'light'=>'#f8fafc'],
        'tes_praktis'    => ['label'=>'Tes Praktis',      'color'=>'#3b82f6', 'light'=>'#eff6ff'],
        'wawancara_hr'   => ['label'=>'Wawancara HR',     'color'=>'#8b5cf6', 'light'=>'#f5f3ff'],
        'wawancara_user' => ['label'=>'Wawancara User',   'color'=>'#6366f1', 'light'=>'#eef2ff'],
        'evaluasi_spk'   => ['label'=>'Evaluasi SPK',     'color'=>'#f59e0b', 'light'=>'#fffbeb'],
        'hired'          => ['label'=>'Diterima',         'color'=>'#10b981', 'light'=>'#ecfdf5'],
        'rejected'       => ['label'=>'Ditolak',          'color'=>'#ef4444', 'light'=>'#fef2f2'],
    ];
    @endphp
    <div class="grid grid-cols-7 gap-2">
        @foreach($pipelineSteps as $val => $step)
        @php $count = \App\Models\Candidate::where('status', $val)->count(); @endphp
        <a href="{{ route('candidates.index', ['status' => $val]) }}"
           class="group rounded-xl p-3 text-center border transition-all duration-150 hover:-translate-y-0.5"
           style="background: {{ $step['light'] }}; border-color: {{ $step['color'] }}20;"
           title="{{ $step['label'] }}">
            <p class="text-2xl font-black" style="color: {{ $step['color'] }};">{{ $count }}</p>
            <p class="text-[10px] font-semibold text-slate-500 mt-1 leading-tight">{{ $step['label'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- ── TABLE ── --}}
    <div class="data-card">
        @if($candidates->isEmpty())
        <div class="py-20 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Belum ada kandidat ditemukan</p>
            <p class="text-xs text-slate-400 mt-1">Coba ubah filter atau tambahkan kandidat baru.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Kandidat</th>
                        <th>Posisi</th>
                        <th>Status</th>
                        <th>Jadwal Berikutnya</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($candidates as $k)
                    @php
                        $badgeMap = [
                            'berkas'=>'badge-gray','tes_praktis'=>'badge-blue',
                            'wawancara_hr'=>'badge-purple','wawancara_user'=>'badge-indigo',
                            'evaluasi_spk'=>'badge-amber','hired'=>'badge-green','rejected'=>'badge-red',
                        ];
                        $nextInterview = $k->interviewSchedules()->where('status','scheduled')->orderBy('scheduled_at')->first();
                    @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                     style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                    {{ strtoupper(substr($k->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $k->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $k->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="font-medium text-slate-700">{{ $k->position->name ?? '—' }}</td>
                        <td><span class="badge {{ $badgeMap[$k->status] ?? 'badge-gray' }}">{{ $k->getStatusLabel() }}</span></td>
                        <td>
                            @if($nextInterview)
                            <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">{{ $nextInterview->scheduled_at->format('d M, H:i') }}</span>
                            </div>
                            @else
                            <span class="text-xs text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('interviews.create', ['candidate_id'=>$k->id]) }}"
                                   class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Jadwalkan Wawancara">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </a>
                                <button @click="showEdit=true; editAction='/candidates/{{ $k->id }}'; editName='{{ addslashes($k->name) }}'; editEmail='{{ $k->email }}'; editPhone='{{ $k->phone }}'; editPosition='{{ $k->position_id }}'; editStatus='{{ $k->status }}'"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('candidates.destroy', $k->id) }}" onsubmit="return confirm('Hapus kandidat ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
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
        @if($candidates->hasPages())
        <div class="px-6 py-4 border-t border-slate-50">{{ $candidates->links() }}</div>
        @endif
        @endif
    </div>

    {{-- ── MODAL: TAMBAH ── --}}
    <div x-show="showCreate" x-cloak class="modal-overlay"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0" @click="showCreate=false"></div>
        <div class="modal-box"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="font-bold text-slate-800">Tambah Kandidat Baru</p>
                    <p class="text-xs text-slate-400 mt-0.5">Daftarkan kandidat ke sistem pipeline</p>
                </div>
                <button @click="showCreate=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('candidates.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <div>
                    <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Nama kandidat">
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="email@example.com">
                </div>
                <div>
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xx-xxxx-xxxx">
                </div>
                <div>
                    <label class="form-label">Posisi yang Dilamar <span class="text-red-500">*</span></label>
                    <select name="position_id" required class="form-input">
                        <option value="">— Pilih Posisi —</option>
                        @foreach($positions as $pos)
                        <option value="{{ $pos->id }}" {{ old('position_id')==$pos->id?'selected':'' }}>{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button" @click="showCreate=false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Kandidat</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: EDIT ── --}}
    <div x-show="showEdit" x-cloak class="modal-overlay"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0" @click="showEdit=false"></div>
        <div class="modal-box"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="font-bold text-slate-800">Update Status Kandidat</p>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui data dan status pipeline</p>
                </div>
                <button @click="showEdit=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="editAction" method="POST" class="px-6 py-5 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editName" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" x-model="editEmail" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" x-model="editPhone" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Posisi</label>
                        <select name="position_id" x-model="editPosition" required class="form-input">
                            @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status Pipeline</label>
                        <select name="status" x-model="editStatus" required class="form-input">
                            @foreach($statuses as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-xs text-slate-400 bg-amber-50 px-3 py-2 rounded-lg border border-amber-100">
                    💡 Set ke <b class="text-amber-700">Evaluasi SPK</b> agar kandidat muncul di form penilaian MAIRCA.
                </p>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button" @click="showEdit=false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 2px 8px rgba(245,158,11,0.35);">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>