<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Posisi Lowongan') }}
            </h2>
            <button @click="$dispatch('open-create-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out shadow-sm">
                + Tambah Posisi
            </button>
        </div>
    </x-slot>

    <div class="py-12"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            editFormAction: '', 
            editName: '', 
            editIsActive: false
        }" 
        @open-create-modal.window="showCreateModal = true"
        @open-edit-modal.window="showEditModal = true; editFormAction = $event.detail.action; editName = $event.detail.name; editIsActive = $event.detail.isActive == 1">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Posisi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($positions as $index => $position)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $positions->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            <a href="{{ route('positions.show', $position->id) }}" class="text-primary-600 hover:text-primary-900 transition-colors">
                                                {{ $position->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($position->is_active)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditutup</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <a href="{{ route('positions.criteria.index', $position->id) }}" class="text-blue-600 hover:text-blue-900 font-medium bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-colors">
                                                Kelola Kriteria
                                            </a>

                                            <button @click="$dispatch('open-edit-modal', { action: '/positions/{{ $position->id }}', name: '{{ addslashes($position->name) }}', isActive: {{ $position->is_active ? 1 : 0 }} })" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</button>
                                            
                                            <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus posisi ini? Kriteria yang terikat juga akan terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada data posisi lowongan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $positions->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCreateModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showCreateModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('positions.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Posisi Lowongan</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Posisi</label>
                                    <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Status Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showEditModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form x-bind:action="editFormAction" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Posisi Lowongan</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Posisi</label>
                                    <input type="text" name="name" id="edit_name" x-model="editName" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" x-model="editIsActive" class="h-4 w-4 border-gray-300 rounded">
                                    <label for="edit_is_active" class="ml-2 block text-sm text-gray-900">Status Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                            <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>