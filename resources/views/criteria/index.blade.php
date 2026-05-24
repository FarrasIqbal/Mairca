<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data>
            <div class="flex items-center space-x-3">
                <a href="{{ route('positions.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kriteria: <span class="text-primary-600">{{ $position->name }}</span>
                </h2>
            </div>
            <button @click="$dispatch('open-create-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 shadow-sm">
                + Tambah Kriteria
            </button>
        </div>
    </x-slot>

    <div class="py-12"
        x-data="{ 
            showCreateModal: false, 
            showEditModal: false, 
            editFormAction: '', 
            editName: '', 
            editType: 'benefit', 
            editWeight: '0.00'
        }"
        @open-create-modal.window="showCreateModal = true"
        @open-edit-modal.window="showEditModal = true; editFormAction = $event.detail.action; editName = $event.detail.name; editType = $event.detail.type; editWeight = $event.detail.weight">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Status Akumulasi Bobot Posisi</h4>
                    <p class="text-xs text-gray-400 mt-1">Sesuai aturan MAIRCA, total bobot kriteria pada satu posisi wajib bernilai mutlak 1.00 (100%).</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block">Total Saat Ini</span>
                        <span class="text-2xl font-black {{ $totalWeight == 1.0 ? 'text-green-600' : 'text-amber-500' }}">
                            {{ number_format($totalWeight, 2) }} / 1.00
                        </span>
                    </div>
                    <div>
                        @if($totalWeight == 1.0)
                            <span class="px-4 py-2 inline-flex text-xs font-bold rounded-xl bg-green-50 text-green-700 border border-green-200 uppercase">✓ Struktur Valid</span>
                        @else
                            <span class="px-4 py-2 inline-flex text-xs font-bold rounded-xl bg-amber-50 text-amber-700 border border-amber-200 uppercase">⚠ Belum Valid</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kriteria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bobot Preferensi</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($criteria as $index => $item)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600">
                                            C{{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($item->type === 'benefit')
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-100 uppercase">Benefit</span>
                                            @else
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-orange-50 text-orange-700 border border-orange-100 uppercase">Cost</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                            {{ $item->weight }} ({{ $item->weight * 100 }}%)
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <button @click="$dispatch('open-edit-modal', { 
                                                action: '{{ route('positions.criteria.update', [$position->id, $item->id]) }}', 
                                                name: '{{ addslashes($item->name) }}', 
                                                type: '{{ $item->type }}', 
                                                weight: '{{ $item->weight }}' 
                                            })" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</button>
                                            
                                            <form action="{{ route('positions.criteria.destroy', [$position->id, $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kriteria ini? Nilai matriks pelamar yang tersimpan juga akan hilang.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-sm text-gray-500 text-center">
                                            Belum ada kriteria penilaian untuk posisi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div x-show="showCreateModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('positions.criteria.store', $position->id) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900">Tambah Parameter Kriteria</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
                                    <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Contoh: Portofolio Coding">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Parameter</label>
                                    <select name="type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="benefit">Benefit (Semakin tinggi semakin bagus)</option>
                                        <option value="cost">Cost (Semakin rendah semakin bagus)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bobot Preferensi (Desimal)</label>
                                    <input type="number" name="weight" step="0.01" min="0.01" max="1.00" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Contoh: 0.25">
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

        <div x-cloak x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div x-show="showEditModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form x-bind:action="editFormAction" method="POST">
                        @csrf
                        @dynamic
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900">Ubah Parameter Kriteria</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
                                    <input type="text" name="name" x-model="editName" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Parameter</label>
                                    <select name="type" x-model="editType" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="benefit">Benefit</option>
                                        <option value="cost">Cost</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Bobot Preferensi</label>
                                    <input type="number" name="weight" step="0.01" min="0.01" max="1.00" x-model="editWeight" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
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