<x-app-layout title="Buat Jadwal Wawancara" subtitle="Tentukan kandidat, tipe, waktu, dan link Zoom">

<div class="max-w-xl">
    <div class="data-card">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100"
             style="background: linear-gradient(135deg, #f0f2ff, #e8ecff);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, #4f46e5, #3b82f6); box-shadow: 0 4px 12px rgba(79,70,229,0.3);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-slate-800">Jadwal Wawancara Baru</p>
                    <p class="text-xs text-slate-500 mt-0.5">Isi semua detail sesi wawancara</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('interviews.store') }}" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Kandidat --}}
            <div>
                <label class="form-label">Kandidat <span class="text-red-500">*</span></label>
                <select name="candidate_id" required class="form-input">
                    <option value="">— Pilih Kandidat —</option>
                    @foreach($candidates as $c)
                    <option value="{{ $c->id }}" {{ old('candidate_id', $selectedCandidateId)==$c->id?'selected':'' }}>
                        {{ $c->name }} — {{ $c->position->name ?? '—' }} ({{ $c->getStatusLabel() }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipe Wawancara --}}
            <div>
                <label class="form-label">Tipe Wawancara <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="hr" {{ old('type','hr')==='hr'?'checked':'' }} class="peer sr-only">
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
                        <input type="radio" name="type" value="user" {{ old('type')==='user'?'checked':'' }} class="peer sr-only">
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
                    <option value="">— Pilih Pewawancara —</option>
                    @foreach($interviewers as $u)
                    <option value="{{ $u->id }}" {{ old('interviewer_id')==$u->id?'selected':'' }}>
                        {{ $u->name }} ({{ $u->getRoleLabel() }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal & Waktu --}}
            <div>
                <label class="form-label">Tanggal & Waktu <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required class="form-input">
            </div>

            {{-- Zoom --}}
            <div>
                <label class="form-label">
                    Link Zoom
                    <span class="text-slate-400 font-normal text-xs ml-1">(opsional)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    </div>
                    <input type="url" name="zoom_link" value="{{ old('zoom_link') }}"
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
                          class="form-input resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('interviews.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>
