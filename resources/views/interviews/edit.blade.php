<x-app-layout title="Edit Jadwal Wawancara" subtitle="Perbarui detail sesi wawancara">

@php
    $candidatesJson = $candidates->map(fn($c) => [
        'id' => $c->id . '',
        'name' => $c->name,
        'position_name' => $c->position->name ?? '—',
        'department' => $c->position && $c->position->department ? strtolower($c->position->department) : '',
        'status_label' => $c->getStatusLabel()
    ]);

    $interviewersJson = $interviewers->map(fn($u) => [
        'id' => $u->id . '',
        'name' => $u->name,
        'role' => $u->role,
        'role_label' => $u->getRoleLabel(),
        'department' => $u->department ? strtolower($u->department) : ''
    ]);
@endphp

<div x-data="interviewForm()" class="w-full space-y-6">
    {{-- Back Link --}}
    <div>
        <a href="{{ route('interviews.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Jadwal
        </a>
    </div>

    <div class="data-card">
        {{-- Header with subtle amber tint --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-amber-50/10 dark:bg-amber-950/10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Edit Jadwal Wawancara</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kandidat: <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $interview->candidate->name }}</span></p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('interviews.update', $interview) }}" class="px-6 py-6 space-y-6 bg-white dark:bg-slate-950/10">
            @csrf @method('PUT')

            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 rounded-xl text-red-700 dark:text-red-400 text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <ul class="space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div class="space-y-5">
                    {{-- Kandidat --}}
                    <div>
                        <label class="form-label">Kandidat <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <select name="candidate_id" required class="form-select pl-10" x-model="selectedCandidate">
                                @foreach($candidates as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }} — {{ $c->position->name ?? '—' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tipe Wawancara --}}
                    <div>
                        <label class="form-label">Tipe Wawancara <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="hr" x-model="interviewType" class="peer sr-only">
                                <div class="border-2 border-slate-100 dark:border-slate-800 peer-checked:border-indigo-600 dark:peer-checked:border-indigo-500 rounded-2xl p-4 transition-all hover:bg-slate-50/50 dark:hover:bg-slate-900/30 peer-checked:bg-indigo-50/30 dark:peer-checked:bg-indigo-950/20">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Wawancara HR</p>
                                            <p class="text-[11px] text-slate-400">Tahap pertama</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="user" x-model="interviewType" class="peer sr-only">
                                <div class="border-2 border-slate-100 dark:border-slate-800 peer-checked:border-violet-600 dark:peer-checked:border-violet-500 rounded-2xl p-4 transition-all hover:bg-slate-50/50 dark:hover:bg-slate-900/30 peer-checked:bg-violet-50/30 dark:peer-checked:bg-violet-950/20">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-violet-50 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Wawancara User</p>
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
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <select name="interviewer_id" required class="form-select pl-10" x-model="selectedInterviewer">
                                <option value="">— Pilih Pewawancara —</option>
                                <template x-for="u in filteredInterviewers" :key="u.id">
                                    <option :value="u.id" :selected="u.id == selectedInterviewer" x-text="u.name + ' (' + u.role_label + (u.department ? ' - ' + u.department.toUpperCase() : '') + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-5">
                    {{-- Tanggal & Waktu --}}
                    <div>
                        <label class="form-label">Tanggal & Waktu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="datetime-local" name="scheduled_at"
                                   value="{{ old('scheduled_at', $interview->scheduled_at->format('Y-m-d\TH:i')) }}" required class="form-input pl-10">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="form-label">Status <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <select name="status" required class="form-select pl-10">
                                <option value="scheduled" {{ old('status', $interview->status)==='scheduled'?'selected':'' }}>Terjadwal</option>
                                <option value="completed" {{ old('status', $interview->status)==='completed'?'selected':'' }}>Selesai</option>
                                <option value="cancelled" {{ old('status', $interview->status)==='cancelled'?'selected':'' }}>Dibatalkan</option>
                            </select>
                        </div>
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
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
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

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('interviewForm', () => ({
        selectedCandidate: '{{ old('candidate_id', $interview->candidate_id) }}',
        interviewType: '{{ old('type', $interview->type) }}',
        selectedInterviewer: '{{ old('interviewer_id', $interview->interviewer_id) }}',
        candidatesList: @json($candidatesJson),
        interviewersList: @json($interviewersJson),
        get filteredInterviewers() {
            const cand = this.candidatesList.find(c => c.id == this.selectedCandidate);
            const candDept = cand ? cand.department : '';
            return this.interviewersList.filter(u => {
                if (this.interviewType === 'user') {
                    return u.department === candDept;
                }
                return true;
            });
        },
        init() {
            this.$watch('selectedCandidate', () => this.checkInterviewer());
            this.$watch('interviewType', () => this.checkInterviewer());
        },
        checkInterviewer() {
            const availableIds = this.filteredInterviewers.map(u => u.id);
            if (this.selectedInterviewer && !availableIds.includes(this.selectedInterviewer)) {
                this.selectedInterviewer = '';
            }
        }
    }));
});
</script>
