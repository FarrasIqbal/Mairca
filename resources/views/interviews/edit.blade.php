<x-app-layout title="Edit Jadwal Wawancara" subtitle="Perbarui detail sesi wawancara">

<div class="max-w-xl">
    <div class="data-card">

        {{-- Header with amber/orange gradient --}}
        <div class="px-6 py-5 border-b border-slate-100"
             style="background: linear-gradient(135deg, #fff7ed, #ffedd5);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #f59e0b, #f97316); box-shadow: 0 4px 12px rgba(245,158,11,0.3);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800">Edit Jadwal Wawancara</p>
                    <p class="text-xs text-slate-500 mt-0.5">Kandidat: <span class="font-medium">{{ $interview->candidate->name }}</span></p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('interviews.update', $interview) }}" class="px-6 py-6 space-y-5">
            @csrf @method('PUT')

            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <ul class="space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Kandidat --}}
            <div>
                <label class="form-label">Kandidat <span class="text-red-500">*</span></label>
                <select name="candidate_id" required class="form-input">
                    @foreach($candidates as $c)
                    <option value="{{ $c->id }}" {{ old('candidate_id', $interview->candidate_id)==$c->id?'selected':'' }}>
                        {{ $c->name }} — {{ $c->position->name ?? '—' }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipe Wawancara --}}
            <div>
                <label class="form-label">Tipe Wawancara <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="hr" {{ old('type', $interview->type)==='hr'?'checked':'' }} class="peer sr-only">
                        <div class="border-2 border-slate-200 peer-checked:border-indigo-500 rounded-xl p-4 transition-all peer-checked:bg-indigo-50/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #eff6ff;">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Wawancara HR</p>
                                    <p class="text-[11px] text-slate-400">Tahap pertama</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="user" {{ old('type', $interview->type)==='user'?'checked':'' }} class="peer sr-only">
                        <div class="border-2 border-slate-200 peer-checked:border-violet-500 rounded-xl p-4 transition-all peer-checked:bg-violet-50/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: #f5f3ff;">
                                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Wawancara User</p>
                                    <p class="text-[11px] text-slate-400">Tahap kedua</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Pewawancara --}}
            <div>
                <label class="form-label">Pewawancara <span class="text-red-500">*</span></label>
                <select name="interviewer_id" required class="form-input">
                    @foreach($interviewers as $u)
                    <option value="{{ $u->id }}" {{ old('interviewer_id', $interview->interviewer_id)==$u->id?'selected':'' }}>
                        {{ $u->name }} ({{ $u->getRoleLabel() }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal & Waktu --}}
            <div>
                <label class="form-label">Tanggal & Waktu <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="scheduled_at"
                       value="{{ old('scheduled_at', $interview->scheduled_at->format('Y-m-d\TH:i')) }}" required class="form-input">
            </div>

            {{-- Status --}}
            <div>
                <label class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="form-input">
                    <option value="scheduled" {{ old('status', $interview->status)==='scheduled'?'selected':'' }}>Terjadwal</option>
                    <option value="completed" {{ old('status', $interview->status)==='completed'?'selected':'' }}>Selesai</option>
                    <option value="cancelled" {{ old('status', $interview->status)==='cancelled'?'selected':'' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Link Zoom --}}
            <div>
                <label class="form-label">
                    Link Zoom
                    <span class="text-slate-400 font-normal text-xs ml-1">(opsional)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    </div>
                    <input type="url" name="zoom_link" value="{{ old('zoom_link', $interview->zoom_link) }}"
                           placeholder="https://zoom.us/j/..." class="form-input pl-10">
                </div>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="form-label">
                    Catatan
                    <span class="text-slate-400 font-normal text-xs ml-1">(opsional)</span>
                </label>
                <textarea name="notes" rows="3" placeholder="Agenda wawancara, hal yang perlu disiapkan, dll."
                          class="form-input resize-none">{{ old('notes', $interview->notes) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('interviews.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>
