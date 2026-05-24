<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pipeline Kandidat Pelamar') }}
            </h2>
            <button @click="$dispatch('open-create-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out shadow-sm">
                + Tambah Kandidat
            </button>
        </div>
    </x-slot>

    <div class="py-12"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            editFormAction: '', 
            editName: '', 
            editEmail: '',
            editPosition: '',
            editStatus: ''
        }" 
        @open-create-modal.window="showCreateModal = true"
        @open-edit-modal.window="
            showEditModal = true; 
            editFormAction = $event.detail.action; 
            editName = $event.detail.name; 
            editEmail = $event.detail.email; 
            editPosition = $event.detail.position;
            editStatus = $event.detail.status;
        ">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandidat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi Dilamar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pipeline</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($candidates as $kandidat)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $kandidat->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $kandidat->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                            {{ $kandidat->position->name ?? 'Posisi Dihapus' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $badges = [
                                                    'berkas' => 'bg-gray-100 text-gray-800',
                                                    'tes_praktis' => 'bg-blue-100 text-blue-800',
                                                    'evaluasi_spk' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                                    'hired' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ];
                                                $labels = [
                                                    'berkas' => '1. Seleksi Berkas',
                                                    'tes_praktis' => '2. Tes Praktis',
                                                    'evaluasi_spk' => '3. Evaluasi SPK (MAIRCA)',
                                                    'hired' => 'Lolos (Hired)',
                                                    'rejected' => 'Ditolak',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $badges[$kandidat->status] }}">
                                                {{ $labels[$kandidat->status] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <button @click="$dispatch('open-edit-modal', { 
                                                action: '/candidates/{{ $kandidat->id }}', 
                                                name: '{{ addslashes($kandidat->name) }}', 
                                                email: '{{ $kandidat->email }}',
                                                position: '{{ $kandidat->position_id }}',
                                                status: '{{ $kandidat->status }}'
                                            })" class="text-indigo-600 hover:text-indigo-900 font-medium">Update Status</button>
                                            
                                            <form action="{{ route('candidates.destroy', $kandidat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kandidat ini dari sistem?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada kandidat pelamar yang masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $candidates->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div x-show="showCreateModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('candidates.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Data Kandidat</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                    <input type="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Posisi Dilamar</label>
                                    <select name="position_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">-- Pilih Posisi Aktif --</option>
                                        @foreach($positions as $pos)
                                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan Data</button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div x-show="showEditModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form x-bind:action="editFormAction" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Update Profil & Status Pipeline</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="name" x-model="editName" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                    <input type="email" name="email" x-model="editEmail" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Posisi Dilamar</label>
                                    <select name="position_id" x-model="editPosition" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @foreach($positions as $pos)
                                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pt-3 border-t border-gray-200">
                                    <label class="block text-sm font-bold text-gray-700">Status Pipeline Saat Ini</label>
                                    <select name="status" x-model="editStatus" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 focus:border-primary-500 focus:ring-primary-500">
                                        <option value="berkas">1. Seleksi Berkas</option>
                                        <option value="tes_praktis">2. Tes Praktis</option>
                                        <option value="evaluasi_spk" class="font-bold text-purple-600">3. Evaluasi SPK (Siap Dinilai MAIRCA)</option>
                                        <option value="hired" class="text-green-600">Lolos (Hired)</option>
                                        <option value="rejected" class="text-red-600">Ditolak (Rejected)</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Pindahkan status ke <b>Evaluasi SPK</b> agar kandidat ini muncul di panel penilaian Reviewer.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Update Data</button>
                            <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>