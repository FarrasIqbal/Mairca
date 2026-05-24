<x-app-layout>
    <div x-data="{ 
        activeTab: 'ranking', // Default langsung ke ranking agar Bos/HR instant melihat hasil
        showCriteriaCreate: false,
        showCriteriaEdit: false,
        showCandidateCreate: false,
        showCandidateEdit: false,
        
        // Form Bindings
        criteriaAction: '', criteriaName: '', criteriaType: 'benefit', criteriaWeight: '0.00',
        candidateAction: '', candidateName: '', candidateEmail: '', candidateStatus: ''
    }">
        
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('positions.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Control Center: <span class="text-primary-600">{{ $position->name }}</span>
                    </h2>
                </div>
                
                <div>
                    <button x-show="activeTab === 'kriteria' && '{{ Auth::user()->role }}' === 'hr'" @click="showCriteriaCreate = true" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        + Tambah Kriteria
                    </button>
                    <button x-show="activeTab === 'kandidat' && '{{ Auth::user()->role }}' === 'hr'" @click="showCandidateCreate = true" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                        + Tambah Kandidat
                    </button>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl relative">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl relative">{{ session('error') }}</div>
                @endif

                <div class="border-b border-gray-200 bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
                    <nav class="flex divide-x divide-gray-100" aria-label="Tabs">
                        <button @click="activeTab = 'ranking'" :class="activeTab === 'ranking' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="w-1/4 py-4 text-center text-sm transition">
                            🏆 Peringkat MAIRCA
                        </button>
                        <button @click="activeTab = 'input_nilai'" :class="activeTab === 'input_nilai' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="w-1/4 py-4 text-center text-sm transition">
                            📝 Input Matriks Nilai
                        </button>
                        @if(Auth::user()->role === 'hr')
                            <button @click="activeTab = 'kandidat'" :class="activeTab === 'kandidat' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="w-1/4 py-4 text-center text-sm transition">
                                👥 Pipeline Pelamar
                            </button>
                            <button @click="activeTab = 'kcriteria'" @click.prevent="activeTab = 'kriteria'" :class="activeTab === 'kcriteria' || activeTab === 'kcriteria' ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="w-1/4 py-4 text-center text-sm transition">
                                ⚙ Kriteria & Bobot
                            </button>
                        @endif
                    </nav>
                </div>

                <div x-show="activeTab === 'ranking'" x-transition>
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                        @if(!$maircaResult)
                            <div class="p-12 text-center text-gray-500">Kalkulasi belum siap. Pastikan kriteria sudah ada dan status pelamar sudah dipindahkan ke tahap Evaluasi SPK.</div>
                        @else
                            <div class="p-6 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-100 rounded-xl">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase w-24">Rank</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Skor ($Q_i$)</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Rekomendasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($maircaResult['ranked'] as $idx => $cand)
                                            <tr class="{{ $idx == 0 ? 'bg-yellow-50/40' : '' }}">
                                                <td class="px-6 py-4 text-center font-bold text-lg">{{ $idx == 0 ? '🥇' : ($idx == 1 ? '🥈' : ($idx == 2 ? '🥉' : $idx + 1)) }}</td>
                                                <td class="px-6 py-4 font-bold text-gray-900">{{ $cand->name }}</td>
                                                <td class="px-6 py-4 text-center font-mono font-bold text-primary-600">{{ number_format($cand->mairca_score, 4) }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $idx == 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                        {{ $idx == 0 ? 'Sangat Direkomendasikan' : 'Alternatif' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div x-show="activeTab === 'input_nilai'" x-transition x-cloak>
                    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                        @if($evalCandidates->isEmpty())
                            <div class="p-12 text-center text-gray-500">Belum ada kandidat di tahap "3. Evaluasi SPK". Pindahkan status mereka terlebih dahulu di tab Pipeline Pelamar.</div>
                        @elseif($criteria->isEmpty())
                            <div class="p-12 text-center text-amber-600 font-medium">Kriteria belum diatur untuk posisi ini.</div>
                        @else
                            <form action="{{ route('evaluations.storeBulk') }}" method="POST">
                                @csrf
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase bg-gray-50">Nama Kandidat</th>
                                                @foreach($criteria as $index => $crit)
                                                    <th class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase min-w-[140px] bg-gray-50">
                                                        <div class="flex flex-col items-center">
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded font-bold bg-blue-50 text-blue-700 uppercase">C{{ $index + 1 }} • {{ $crit->type }}</span>
                                                            <span class="text-xs mt-1 leading-tight font-medium text-gray-700">{{ $crit->name }}</span>
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach($evalCandidates as $candidate)
                                                <tr class="hover:bg-gray-50/50 transition">
                                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 border-r border-gray-100">{{ $candidate->name }}</td>
                                                    @foreach($criteria as $crit)
                                                        <td class="px-2 py-3 text-center border-r border-gray-100 last:border-0">
                                                            <input type="number" name="scores[{{ $candidate->id }}][{{ $crit->id }}]" value="{{ $existingScores[$candidate->id][$crit->id] ?? '' }}" min="1" max="100" placeholder="-" class="w-20 text-center border-gray-300 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm transition">Simpan Semua Nilai</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                @if(Auth::user()->role === 'hr')
                    <div x-show="activeTab === 'kandidat'" x-transition x-cloak>
                        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kandidat</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($allCandidates as $cand)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $cand->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $cand->email }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full {{ $cand->status === 'evaluasi_spk' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $cand->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                                <button @click="showCandidateEdit = true; candidateAction = '/candidates/{{ $cand->id }}'; candidateName = '{{ addslashes($cand->name) }}'; candidateEmail = '{{ $cand->email }}'; candidateStatus = '{{ $cand->status }}'" class="text-indigo-600 hover:text-indigo-900">Update Status</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="p-10 text-center text-gray-500">Belum ada pelamar di posisi ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="activeTab === 'kriteria'" x-transition x-cloak>
                        <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between mb-6">
                            <div>
                                <h4 class="text-sm font-bold text-gray-500 uppercase">Akumulasi Bobot Kriteria</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Wajib bernilai mutlak 1.00 (100%) untuk dapat mengaktifkan sistem ranking.</p>
                            </div>
                            <span class="text-xl font-black {{ $totalWeight == 1.0 ? 'text-green-600' : 'text-amber-500' }}">{{ number_format($totalWeight, 2) }} / 1.00</span>
                        </div>
                        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kode</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Bobot</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($criteria as $index => $crit)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 font-bold text-gray-500">C{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $crit->name }}</td>
                                            <td class="px-6 py-4 uppercase text-xs font-bold {{ $crit->type === 'benefit' ? 'text-blue-600' : 'text-orange-600' }}">{{ $crit->type }}</td>
                                            <td class="px-6 py-4 font-mono">{{ $crit->weight }}</td>
                                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                                <button @click="showCriteriaEdit = true; criteriaAction = '{{ route('positions.criteria.update', [$position->id, $crit->id]) }}'; criteriaName = '{{ addslashes($crit->name) }}'; criteriaType = '{{ $crit->type }}'; criteriaWeight = '{{ $crit->weight }}'" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-10 text-center text-gray-500">Belum ada kriteria penilaian.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div x-cloak x-show="showCriteriaCreate" class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/70 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-xl overflow-hidden" @click.away="showCriteriaCreate = false">
                <form action="{{ route('positions.criteria.store', $position->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <h3 class="text-lg font-bold text-gray-900">Tambah Parameter Kriteria</h3>
                    <div><label class="text-sm font-medium text-gray-700">Nama Kriteria</label><input type="text" name="name" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div><label class="text-sm font-medium text-gray-700">Tipe Parameter</label><select name="type" required class="mt-1 w-full border-gray-300 rounded-md"><option value="benefit">Benefit</option><option value="cost">Cost</option></select></div>
                    <div><label class="text-sm font-medium text-gray-700">Bobot Preferensi</label><input type="number" name="weight" step="0.01" min="0.01" max="1.00" required class="mt-1 w-full border-gray-300 rounded-md" placeholder="0.25"></div>
                    <div class="flex justify-end space-x-2"><button type="button" @click="showCriteriaCreate = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 bg-white">Batal</button><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Simpan</button></div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showCriteriaEdit" class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/70 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-xl overflow-hidden" @click.away="showCriteriaEdit = false">
                <form x-bind:action="criteriaAction" method="POST" class="p-6 space-y-4">
                    @csrf @method('PUT')
                    <h3 class="text-lg font-bold text-gray-900">Ubah Parameter Kriteria</h3>
                    <div><label class="text-sm font-medium text-gray-700">Nama Kriteria</label><input type="text" name="name" x-model="criteriaName" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div><label class="text-sm font-medium text-gray-700">Tipe</label><select name="type" x-model="criteriaType" required class="mt-1 w-full border-gray-300 rounded-md"><option value="benefit">Benefit</option><option value="cost">Cost</option></select></div>
                    <div><label class="text-sm font-medium text-gray-700">Bobot</label><input type="number" name="weight" step="0.01" x-model="criteriaWeight" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div class="flex justify-end space-x-2"><button type="button" @click="showCriteriaEdit = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 bg-white">Batal</button><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Update</button></div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showCandidateCreate" class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/70 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-xl overflow-hidden" @click.away="showCandidateCreate = false">
                <form action="{{ route('candidates.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="position_id" value="{{ $position->id }}">
                    <h3 class="text-lg font-bold text-gray-900">Tambah Data Kandidat Pelamar</h3>
                    <div><label class="text-sm font-medium text-gray-700">Nama Lengkap</label><input type="text" name="name" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div><label class="text-sm font-medium text-gray-700">Email</label><input type="email" name="email" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div class="flex justify-end space-x-2"><button type="button" @click="showCandidateCreate = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 bg-white">Batal</button><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Simpan</button></div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showCandidateEdit" class="fixed inset-0 z-50 overflow-y-auto bg-gray-500/70 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-xl overflow-hidden" @click.away="showCandidateEdit = false">
                <form x-bind:action="candidateAction" method="POST" class="p-6 space-y-4">
                    @csrf @method('PUT')
                    <input type="hidden" name="position_id" value="{{ $position->id }}">
                    <h3 class="text-lg font-bold text-gray-900">Update Profil & Status Pipeline</h3>
                    <div><label class="text-sm font-medium text-gray-700">Nama</label><input type="text" name="name" x-model="candidateName" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div><label class="text-sm font-medium text-gray-700">Email</label><input type="email" name="email" x-model="candidateEmail" required class="mt-1 w-full border-gray-300 rounded-md"></div>
                    <div>
                        <label class="text-sm font-bold text-gray-700">Status Pipeline</label>
                        <select name="status" x-model="candidateStatus" required class="mt-1 w-full border-gray-300 rounded-md bg-gray-50">
                            <option value="berkas">1. Seleksi Berkas</option>
                            <option value="tes_praktis">2. Tes Praktis</option>
                            <option value="evaluasi_spk">3. Evaluasi SPK (Siap Dinilai MAIRCA)</option>
                            <option value="hired">Lolos (Hired)</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2"><button type="button" @click="showCandidateEdit = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 bg-white">Batal</button><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Update Data</button></div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>