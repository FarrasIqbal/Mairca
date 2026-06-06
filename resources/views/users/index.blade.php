<x-app-layout title="Manajemen User" subtitle="Tambah dan kelola akun pengguna sistem">

<div x-data="{
    showCreate: false,
    showEdit: false,
    editId: null,
    editName: '',
    editEmail: '',
    editRole: '',
    editDepartment: '',
    createRole: 'reviewer'
}" class="space-y-5">

    {{-- Header --}}
    <div class="flex justify-end">
        <button @click="showCreate = true" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah User
        </button>
    </div>

    {{-- Table --}}
    <div class="data-card">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th>Departemen</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                                     style="background: linear-gradient(135deg, {{ $u->role==='hr' ? '#3b82f6, #6366f1' : '#8b5cf6, #a78bfa' }});">
                                    {{ strtoupper(substr($u->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $u->name }}</p>
                                    @if($u->id === Auth::id())
                                    <span class="text-xs text-indigo-500 font-semibold">● Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-500 dark:text-slate-400">{{ $u->email }}</td>
                        <td class="font-medium text-slate-700 dark:text-slate-300">
                            {{ $u->department ?? '—' }}
                        </td>
                        <td>
                            <span class="badge {{ $u->role==='hr' ? 'badge-blue' : 'badge-purple' }}">
                                {{ $u->role==='hr' ? 'HRD' : 'Reviewer' }}
                            </span>
                        </td>
                        <td class="text-slate-400 dark:text-slate-500 text-xs">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="showEdit=true; editId={{ $u->id }}; editName='{{ addslashes($u->name) }}'; editEmail='{{ $u->email }}'; editRole='{{ $u->role }}'; editDepartment='{{ addslashes($u->department) }}'"
                                        class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($u->id !== Auth::id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}" 
                                      @submit.prevent="window.dispatchEvent(new CustomEvent('confirm-modal', { detail: { title: 'Hapus User', message: 'Apakah Anda yakin ingin menghapus akun {{ addslashes($u->name) }}?', type: 'danger', confirmBtnText: 'Ya, Hapus', callback: () => $el.submit() } }))">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-rose-400 hover:bg-red-50 dark:hover:bg-rose-950/30 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-50">{{ $users->links() }}</div>
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
                    <p class="font-bold text-slate-800">Tambah Pengguna Baru</p>
                    <p class="text-xs text-slate-400 mt-0.5">Buat akun untuk HRD atau Reviewer</p>
                </div>
                <button @click="showCreate=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('users.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="form-input" placeholder="Nama pengguna">
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="form-input" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="form-label">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required class="form-input" placeholder="Min. 8 karakter">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required class="form-input">
                    </div>
                    <div class="col-span-2">
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="form-select" x-model="createRole">
                            <option value="reviewer">Reviewer (User / Lead Departemen)</option>
                            <option value="hr">HRD (Admin)</option>
                        </select>
                    </div>
                    <div class="col-span-2" x-show="createRole === 'reviewer'">
                        <label class="form-label">Departemen <span class="text-red-500">*</span></label>
                        <input type="text" name="department" list="existing-departments" :required="createRole === 'reviewer'" class="form-input" placeholder="Contoh: SEO, IT, Marketing">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button" @click="showCreate=false" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Buat Akun</button>
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
                    <p class="font-bold text-slate-800">Edit Pengguna</p>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui data akun pengguna</p>
                </div>
                <button @click="showEdit=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <template x-if="showEdit">
                <form :action="'/users/' + editId" method="POST" class="px-6 py-5 space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" :value="editName" required class="form-input">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" :value="editEmail" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Password Baru <span class="text-slate-400 text-xs font-normal">(kosongkan jika tidak diubah)</span></label>
                            <input type="password" name="password" class="form-input" placeholder="Biarkan kosong">
                        </div>
                        <div>
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-input">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Role</label>
                            <select name="role" required class="form-select" x-model="editRole">
                                <option value="reviewer" :selected="editRole === 'reviewer'">Reviewer (User / Lead)</option>
                                <option value="hr" :selected="editRole === 'hr'">HRD (Admin)</option>
                            </select>
                        </div>
                        <div class="col-span-2" x-show="editRole === 'reviewer'">
                            <label class="form-label">Departemen <span class="text-red-500">*</span></label>
                            <input type="text" name="department" :value="editDepartment" list="existing-departments" :required="editRole === 'reviewer'" class="form-input" placeholder="Contoh: SEO, IT, Marketing">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <button type="button" @click="showEdit=false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 2px 8px rgba(245,158,11,0.3);">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    {{-- Datalist untuk Autocomplete Departemen --}}
    <datalist id="existing-departments">
        @foreach($existingDepartments as $dept)
            <option value="{{ $dept }}">
        @endforeach
    </datalist>

</div>

</x-app-layout>
