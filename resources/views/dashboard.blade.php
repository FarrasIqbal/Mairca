<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Selamat datang, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-sm text-gray-500 mt-2">
                            Anda login sebagai 
                            <span class="px-3 py-1 bg-primary-50 text-primary-600 rounded-md text-xs font-bold uppercase tracking-wider border border-primary-100">
                                {{ Auth::user()->role == 'hr' ? 'HRD Superadmin' : 'Reviewer / Lead' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

           <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition duration-200">
    <div>
        <p class="text-sm font-medium text-gray-500">Total Lowongan</p>
        <p class="text-3xl font-bold text-gray-800">{{ $totalPositions }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition duration-200">
    <div>
        <p class="text-sm font-medium text-gray-500">Kandidat Aktif</p>
        <p class="text-3xl font-bold text-gray-800">{{ $activeCandidates }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4 hover:shadow-md transition duration-200">
    <div>
        <p class="text-sm font-medium text-gray-500">Menunggu Evaluasi</p>
        <p class="text-3xl font-bold text-gray-800">{{ $pendingEvaluations }}</p>
    </div>
</div>

        </div>
    </div>
</x-app-layout>