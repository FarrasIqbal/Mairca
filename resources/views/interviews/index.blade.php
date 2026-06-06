<x-app-layout title="Jadwal Wawancara" subtitle="Kelola semua sesi wawancara HR dan User/Reviewer">

<div class="space-y-5">

    {{-- ── HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
        <form method="GET" action="{{ route('interviews.index') }}" class="flex items-center gap-2 flex-wrap">
            <select name="type" onchange="this.form.submit()" class="form-select w-auto">
                <option value="">Semua Tipe</option>
                <option value="hr" {{ request('type')==='hr'?'selected':'' }}>Wawancara HR</option>
                <option value="user" {{ request('type')==='user'?'selected':'' }}>Wawancara User</option>
            </select>
            <select name="status" onchange="this.form.submit()" class="form-select w-auto">
                <option value="">Semua Status</option>
                <option value="scheduled" {{ request('status')==='scheduled'?'selected':'' }}>Terjadwal</option>
                <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Selesai</option>
                <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Dibatalkan</option>
            </select>
            @if(request('type') || request('status'))
            <a href="{{ route('interviews.index') }}" class="btn-secondary text-xs px-3 py-2">✕ Reset</a>
            @endif
        </form>
        @if(Auth::user()->role === 'hr')
        <a href="{{ route('interviews.create') }}" class="btn-primary flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Buat Jadwal
        </a>
        @endif
    </div>

    {{-- ── TABLE ── --}}
    <div class="data-card">
        @if($schedules->isEmpty())
        <div class="py-20 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                 style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-500">Belum ada jadwal wawancara</p>
            <p class="text-xs text-slate-400 mt-1">
                {{ Auth::user()->role === 'hr' ? 'Klik "Buat Jadwal" untuk menambahkan jadwal baru.' : 'Belum ada jadwal yang ditetapkan untuk Anda.' }}
            </p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Kandidat & Posisi</th>
                        <th>Tipe</th>
                        <th>Waktu</th>
                        <th>Pewawancara</th>
                        <th>Status</th>
                        <th>Link Zoom</th>
                        @if(Auth::user()->role === 'hr')
                        <th class="text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $s)
                    <tr>
                        <td>
                            <p class="font-semibold text-slate-800">{{ $s->candidate->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $s->candidate->position->name ?? '—' }}</p>
                        </td>
                        <td>
                            <span class="badge {{ $s->type === 'hr' ? 'badge-blue' : 'badge-purple' }}">
                                {{ $s->type === 'hr' ? 'HR' : 'User' }}
                            </span>
                        </td>
                        <td>
                            <p class="font-semibold text-slate-700">{{ $s->scheduled_at->format('d M Y') }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $s->scheduled_at->format('H:i') }} WIB</p>
                        </td>
                        <td>
                            <p class="text-slate-700">{{ $s->interviewer->name ?? '—' }}</p>
                            <p class="text-xs text-slate-400">{{ $s->interviewer ? $s->interviewer->getRoleLabel() : '' }}</p>
                        </td>
                        <td>
                            @php
                            $statusStyle = match($s->status) {
                                'scheduled' => 'badge-amber',
                                'completed' => 'badge-green',
                                'cancelled' => 'badge-red',
                                default => 'badge-gray'
                            };
                            @endphp
                            <span class="badge {{ $statusStyle }}">{{ $s->getStatusLabel() }}</span>
                        </td>
                        <td>
                            @if($s->zoom_link)
                            <a href="{{ $s->zoom_link }}" target="_blank" class="zoom-btn">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                                Join Zoom
                            </a>
                            @else
                            <span class="text-xs text-slate-300 italic">Tidak ada</span>
                            @endif
                        </td>
                        @if(Auth::user()->role === 'hr')
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('interviews.edit', $s) }}"
                                   class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('interviews.destroy', $s) }}" 
                                      @submit.prevent="window.dispatchEvent(new CustomEvent('confirm-modal', { detail: { title: 'Hapus Jadwal', message: 'Apakah Anda yakin ingin menghapus jadwal wawancara untuk kandidat {{ addslashes($s->candidate->name) }}?', type: 'danger', confirmBtnText: 'Ya, Hapus', callback: () => $el.submit() } }))">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
        @if($schedules->hasPages())
        <div class="px-6 py-4 border-t border-slate-50">{{ $schedules->links() }}</div>
        @endif
        @endif
    </div>

</div>

</x-app-layout>
