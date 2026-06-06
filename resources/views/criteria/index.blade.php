<x-app-layout title="Kriteria: {{ $position->name }}" subtitle="Atur parameter dan bobot penilaian untuk posisi ini">

    <div class="page-fade space-y-6"
        x-data="{
            showCreateModal: false,
            showEditModal: false,
            editFormAction: '',
            editName: '',
            editType: 'benefit',
            editWeight: '0.00'
        }">

        {{-- Back Link --}}
        <div>
            <a href="{{ route('positions.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Posisi
            </a>
        </div>

        {{-- Weight Status Card --}}
        <div class="data-card">
            <div class="px-6 py-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-1">
                        <h4 class="text-sm font-semibold text-slate-800">Status Akumulasi Bobot</h4>
                        <p class="text-xs text-slate-500">Sesuai aturan MAIRCA, total bobot kriteria pada satu posisi wajib bernilai mutlak <strong>1.00</strong> (100%).</p>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <div class="text-right">
                            <span class="text-xs text-slate-400 block">Total Saat Ini</span>
                            <span class="text-2xl font-black {{ round($totalWeight, 2) == 1.0 ? 'text-emerald-600' : 'text-amber-500' }}">
                                {{ number_format($totalWeight, 2) }} / 1.00
                            </span>
                        </div>
                        @if(round($totalWeight, 2) == 1.0)
                            <span class="badge badge-green">✓ Valid</span>
                        @else
                            <span class="badge badge-amber">⚠ Belum Valid</span>
                        @endif
                    </div>
                </div>
                {{-- Progress Bar --}}
                <div class="mt-4">
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500 {{ round($totalWeight, 2) == 1.0 ? 'bg-emerald-500' : ($totalWeight > 1.0 ? 'bg-red-500' : 'bg-amber-400') }}"
                             style="width: {{ min($totalWeight * 100, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between mt-1.5">
                        <span class="text-[11px] text-slate-400">0%</span>
                        <span class="text-[11px] font-medium {{ round($totalWeight, 2) == 1.0 ? 'text-emerald-600' : 'text-slate-400' }}">{{ number_format($totalWeight * 100, 0) }}%</span>
                        <span class="text-[11px] text-slate-400">100%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Criteria Table --}}
        <div class="data-card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kriteria Penilaian</h3>
                <button @click="showCreateModal = true" class="btn-primary">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kriteria
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Tipe</th>
                            <th>Bobot</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($criteria as $index => $item)
                            <tr>
                                <td>
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-xs font-bold text-indigo-700" style="background: linear-gradient(135deg, #eef2ff, #e0e7ff);">
                                        C{{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-medium text-slate-800">{{ $item->name }}</span>
                                </td>
                                <td>
                                    @if($item->type === 'benefit')
                                        <span class="badge badge-blue">Benefit</span>
                                    @else
                                        <span class="badge" style="background-color: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;">Cost</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-mono text-sm text-slate-700">{{ number_format($item->weight, 2) }}</span>
                                    <span class="text-xs text-slate-400 ml-1">({{ number_format($item->weight * 100, 0) }}%)</span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="showEditModal = true; editFormAction = '{{ route('positions.criteria.update', [$position->id, $item->id]) }}'; editName = '{{ addslashes($item->name) }}'; editType = '{{ $item->type }}'; editWeight = '{{ $item->weight }}'"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('positions.criteria.destroy', [$position->id, $item->id]) }}" method="POST" class="inline" 
                                              @submit.prevent="window.dispatchEvent(new CustomEvent('confirm-modal', { detail: { title: 'Hapus Kriteria', message: 'Apakah Anda yakin ingin menghapus kriteria ini? Nilai matriks pelamar yang tersimpan juga akan hilang.', type: 'danger', confirmBtnText: 'Ya, Hapus', callback: () => $el.submit() } }))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="py-8">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <p class="text-sm text-slate-500">Belum ada kriteria penilaian untuk posisi ini.</p>
                                        <button @click="showCreateModal = true" class="mt-3 text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Tambah kriteria pertama</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-cloak x-show="showCreateModal" class="modal-overlay" @click.self="showCreateModal = false">
            <div x-show="showCreateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-box w-full max-w-lg" @click.stop>
                <form action="{{ route('positions.criteria.store', $position->id) }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Tambah Kriteria Baru</h3>
                            <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="name" required class="form-input w-full" placeholder="Contoh: Portofolio Coding">
                        </div>
                        <div>
                            <label class="form-label">Tipe Parameter</label>
                            <select name="type" required class="form-select w-full">
                                <option value="benefit">Benefit (Semakin tinggi semakin bagus)</option>
                                <option value="cost">Cost (Semakin rendah semakin bagus)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Bobot Preferensi (Desimal)</label>
                            <input type="number" name="weight" step="0.01" min="0.01" max="1.00" required class="form-input w-full" placeholder="Contoh: 0.25">
                            <p class="text-xs text-slate-400 mt-1">Masukkan nilai antara 0.01 — 1.00</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showCreateModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-cloak x-show="showEditModal" class="modal-overlay" @click.self="showEditModal = false">
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-box w-full max-w-lg" @click.stop>
                <form x-bind:action="editFormAction" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-800">Ubah Kriteria</h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="name" x-model="editName" required class="form-input w-full">
                        </div>
                        <div>
                            <label class="form-label">Tipe Parameter</label>
                            <select name="type" x-model="editType" required class="form-select w-full">
                                <option value="benefit">Benefit (Semakin tinggi semakin bagus)</option>
                                <option value="cost">Cost (Semakin rendah semakin bagus)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Bobot Preferensi</label>
                            <input type="number" name="weight" step="0.01" min="0.01" max="1.00" x-model="editWeight" required class="form-input w-full">
                            <p class="text-xs text-slate-400 mt-1">Masukkan nilai antara 0.01 — 1.00</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Update Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>