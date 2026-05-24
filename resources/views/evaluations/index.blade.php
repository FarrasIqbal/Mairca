<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bulk Evaluation Grid (Input Matriks)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100">
                <form action="{{ route('evaluations.index') }}" method="GET" class="flex items-end gap-4">
                    <div class="flex-1 max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Posisi untuk Dinilai</label>
                        <select name="position_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ (request('position_id') == $pos->id) ? 'selected' : '' }}>
                                    {{ $pos->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition duration-150 shadow-sm">
                        Tampilkan Grid
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl relative">
                    {{ session('success') }}
                </div>
            @endif

            @if($selectedPosition)
                <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Lembar Penilaian: {{ $selectedPosition->name }}</h3>
                            <p class="text-sm text-gray-500">Skala pengisian nilai adalah 1 hingga 100.</p>
                        </div>
                    </div>

                    @if($candidates->isEmpty())
                        <div class="p-10 text-center">
                            <p class="text-gray-500">Belum ada kandidat yang mencapai tahap Evaluasi SPK di posisi ini.</p>
                        </div>
                    @elseif($criteria->isEmpty())
                        <div class="p-10 text-center">
                            <p class="text-amber-600 font-medium">HRD belum mengatur kriteria penilaian untuk posisi ini.</p>
                        </div>
                    @else
                        <form action="{{ route('evaluations.storeBulk') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto max-h-[600px]">
                                <table class="min-w-full divide-y divide-gray-200 relative">
                                   <thead class="bg-gray-100 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider bg-gray-100 border-b">Nama Kandidat</th>
                                            @foreach($criteria as $index => $crit)
                                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider bg-gray-100 border-b min-w-[140px] align-top">
                                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                                        <span class="text-[10px] px-2 py-0.5 rounded font-bold {{ $crit->type == 'benefit' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                                            C{{ $index + 1 }} • {{ $crit->type }}
                                                        </span>
                                                        <span class="text-xs whitespace-normal break-words leading-tight text-center">
                                                            {{ $crit->name }}
                                                        </span>
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($candidates as $candidate)
                                            <tr class="hover:bg-blue-50/50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100">
                                                    {{ $candidate->name }}
                                                </td>
                                                @foreach($criteria as $crit)
                                                    @php
                                                        // Ambil nilai sebelumnya jika sudah ada
                                                        $val = $existingScores[$candidate->id][$crit->id] ?? '';
                                                    @endphp
                                                    <td class="px-2 py-3 text-center border-r border-gray-100 last:border-r-0">
                                                        <input type="number" 
                                                               name="scores[{{ $candidate->id }}][{{ $crit->id }}]" 
                                                               value="{{ $val }}" 
                                                               min="1" max="100" 
                                                               placeholder="-"
                                                               class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-6 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex justify-end">
                                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-bold shadow-md transition-transform active:scale-95">
                                    Simpan Semua Nilai
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>