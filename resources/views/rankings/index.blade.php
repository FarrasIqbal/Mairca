<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Hasil Seleksi MAIRCA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100">
                <form action="{{ route('rankings.index') }}" method="GET" class="flex items-end gap-4">
                    <div class="flex-1 max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Posisi untuk Dilihat Hasilnya</label>
                        <select name="position_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" {{ (request('position_id') == $pos->id) ? 'selected' : '' }}>
                                    {{ $pos->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                        Kalkulasi & Lihat Peringkat
                    </button>
                </form>
            </div>

            @if($selectedPosition)
                <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Leaderboard: {{ $selectedPosition->name }}</h3>
                            <p class="text-sm text-gray-500">Diurutkan berdasarkan nilai Kesenjangan (Gap) terkecil.</p>
                        </div>
                    </div>

                    @if(!$result)
                        <div class="p-10 text-center">
                            <p class="text-amber-600 font-medium">Data belum lengkap. Pastikan Kriteria sudah diatur dan ada Kandidat berstatus Evaluasi SPK.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto p-6">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-primary-50">
                                    <tr>
                                        <th class="px-6 py-4 text-center text-xs font-extrabold text-primary-800 uppercase w-20">Peringkat</th>
                                        <th class="px-6 py-4 text-left text-xs font-extrabold text-primary-800 uppercase">Nama Kandidat</th>
                                        <th class="px-6 py-4 text-left text-xs font-extrabold text-primary-800 uppercase">Email</th>
                                        <th class="px-6 py-4 text-center text-xs font-extrabold text-primary-800 uppercase">Skor MAIRCA ($Q_i$)</th>
                                        <th class="px-6 py-4 text-center text-xs font-extrabold text-primary-800 uppercase">Status Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($result['ranked'] as $index => $candidate)
                                        <tr class="{{ $index == 0 ? 'bg-yellow-50/50' : 'hover:bg-gray-50' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($index == 0)
                                                    <span class="text-2xl">🥇</span>
                                                @elseif($index == 1)
                                                    <span class="text-2xl">🥈</span>
                                                @elseif($index == 2)
                                                    <span class="text-2xl">🥉</span>
                                                @else
                                                    <span class="text-lg font-bold text-gray-500">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ $candidate->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $candidate->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-mono font-bold {{ $index == 0 ? 'text-primary-600' : 'text-gray-700' }}">
                                                {{ number_format($candidate->mairca_score, 4) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($index == 0)
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 uppercase">Sangat Direkomendasikan</span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Alternatif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>