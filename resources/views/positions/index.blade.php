<x-app-layout title="Posisi & Kriteria" subtitle="Kelola posisi lowongan dan kriteria penilaian MAIRCA">

    <div class="page-fade"
        x-data="{
            showCreateModal: false,
            showEditModal: false,
            editFormAction: '',
            editName: '',
            editIsActive: false
        }">

        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Posisi Lowongan</h3>
                <p class="text-sm text-slate-500 mt-0.5">Total {{ $positions->total() }} posisi terdaftar</p>
            </div>
            <button @click="showCreateModal = true" class="btn-primary">
                <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Posisi
            </button>
        </div>

        {{-- Table Card --}}
        <div class="data-card">
            <div class="card-header">
                <h4 class="card-title">Semua Posisi</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-16">No</th>
                            <th>Nama Posisi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jumlah Kriteria</th>
                            <th class="text-center">Jumlah Kandidat</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($positions as $index => $position)
                            <tr>
                                <td class="text-slate-500 font-medium">{{ $positions->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ route('positions.show', $position->id) }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                        {{ $position->name }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($position->is_active)
                                        <span class="badge badge-green">Aktif</span>
                                    @else
                                        <span class="badge badge-red">Ditutup</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-blue">{{ $position->criteria_count }} Kriteria</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-purple">{{ $position->candidates_count }} Kandidat</span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('positions.criteria.index', $position->id) }}" class="zoom-btn">
                                            <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Kriteria
                                        </a>

                                        <button @click="showEditModal = true; editFormAction = '{{ route('positions.update', $position->id) }}'; editName = '{{ addslashes($position->name) }}'; editIsActive = {{ $position->is_active ? 'true' : 'false' }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>

                                        <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus posisi ini? Kriteria yang terikat juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        </div>
                                        <p class="text-sm text-slate-500 font-medium">Belum ada data posisi lowongan.</p>
                                        <button @click="showCreateModal = true" class="btn-primary text-xs !px-4 !py-1.5">+ Tambah Posisi Pertama</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($positions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $positions->links() }}
                </div>
            @endif
        </div>

        {{-- ======================== MODAL: Tambah Posisi ======================== --}}
        <div x-cloak x-show="showCreateModal" class="modal-overlay" @keydown.escape.window="showCreateModal = false">
            <div x-show="showCreateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="modal-box w-full max-w-lg" @click.away="showCreateModal = false">

                <form action="{{ route('positions.store') }}" method="POST">
                    @csrf
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Tambah Posisi Lowongan</h3>
                                <p class="text-sm text-slate-500 mt-0.5">Buat posisi lowongan baru untuk penilaian</p>
                            </div>
                            <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label for="name" class="form-label">Nama Posisi</label>
                            <input type="text" name="name" id="name" required placeholder="Contoh: Software Engineer" class="form-input w-full mt-1">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_active" class="text-sm font-medium text-slate-700">Status Aktif</label>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" @click="showCreateModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ======================== MODAL: Edit Posisi ======================== --}}
        <div x-cloak x-show="showEditModal" class="modal-overlay" @keydown.escape.window="showEditModal = false">
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="modal-box w-full max-w-lg" @click.away="showEditModal = false">

                <form x-bind:action="editFormAction" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Edit Posisi Lowongan</h3>
                                <p class="text-sm text-slate-500 mt-0.5">Perbarui detail posisi yang dipilih</p>
                            </div>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label for="edit_name" class="form-label">Nama Posisi</label>
                            <input type="text" name="name" id="edit_name" x-model="editName" required class="form-input w-full mt-1">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" x-model="editIsActive" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="edit_is_active" class="text-sm font-medium text-slate-700">Status Aktif</label>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>