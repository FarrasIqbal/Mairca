<x-app-layout title="Profil Saya" subtitle="Kelola informasi profil akun Anda dan ubah kata sandi keamanan">

    <div class="space-y-6">
        
        <!-- Two Columns Layout for Profile & Security -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Card: Profile Information -->
            <div class="data-card">
                <div class="card-header bg-slate-50/20">
                    <h3 class="card-title">Informasi Profil</h3>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Detail Akun</span>
                </div>
                
                <div class="p-6">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                                       class="form-input ps-10" />
                            </div>
                            @if($errors->get('name'))
                                <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </div>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                       class="form-input ps-10" />
                            </div>
                            @if($errors->get('email'))
                                <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 pt-3 border-t border-slate-50">
                            <button type="submit" class="btn-primary">
                                Simpan Profil
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                                   class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-1.5 shadow-sm">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Tersimpan
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Card: Security / Password -->
            <div class="data-card">
                <div class="card-header bg-slate-50/20">
                    <h3 class="card-title">Keamanan Akun</h3>
                    <span class="text-[10px] font-bold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Ubah Password</span>
                </div>
                
                <div class="p-6">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('put')

                        <div>
                            <label for="update_password_current_password" class="form-label">Password Saat Ini</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                                       class="form-input ps-10" />
                            </div>
                            @if($errors->updatePassword->get('current_password'))
                                <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="update_password_password" class="form-label">Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                                       class="form-input ps-10" />
                            </div>
                            @if($errors->updatePassword->get('password'))
                                <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                       class="form-input ps-10" />
                            </div>
                            @if($errors->updatePassword->get('password_confirmation'))
                                <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 pt-3 border-t border-slate-50">
                            <button type="submit" class="btn-primary">
                                Simpan Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                                   class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-1.5 shadow-sm">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Tersimpan
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
        </div>

        <!-- Bottom: Danger Zone (Delete Account) -->
        <div class="data-card border-rose-100 hover:border-rose-200">
            <div class="card-header bg-rose-50/10 flex items-center justify-between">
                <h3 class="card-title text-rose-600">Danger Zone</h3>
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Hapus Akun</span>
            </div>
            
            <div class="p-6">
                <p class="text-xs text-slate-500 leading-relaxed">
                    Setelah akun Anda dihapus, semua data dan riwayat di dalamnya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="mt-4 pt-3 border-t border-slate-50">
                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="btn-danger">
                        Hapus Akun Saya
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Deletion -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
                @csrf
                @method('delete')

                <h3 class="font-bold text-slate-800 text-sm">
                    Apakah Anda yakin ingin menghapus akun Anda?
                </h3>

                <p class="text-xs text-slate-400 leading-relaxed">
                    Setelah akun Anda dihapus, semua data Anda akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
                </p>

                <div>
                    <label for="password" class="form-label">Password Konfirmasi</label>
                    <input id="password" name="password" type="password" placeholder="Masukkan password Anda"
                           class="form-input" />
                    @if($errors->userDeletion->get('password'))
                        <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-danger">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </x-modal>

    </div>

</x-app-layout>
