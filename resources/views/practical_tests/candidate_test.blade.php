<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tes Praktis Kompetensi — Ultimate Education</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 antialiased text-slate-800 font-sans min-h-screen flex flex-col">

    {{-- Top Simple Brand Header --}}
    <header class="bg-white border-b border-slate-100 py-5 px-6 shadow-sm sticky top-0 z-30">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-gradient-to-tr from-indigo-500 to-violet-600 shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Ultimate Education</h1>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Recruitment Portal</p>
                </div>
            </div>
            <div class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                Pengerjaan Mandiri
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="flex-grow py-10 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto space-y-8">
            
            {{-- Countdown Timer Banner --}}
            <div id="timer-banner" class="sticky top-24 z-20 bg-white border border-slate-100 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-4 flex-wrap transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div id="timer-icon-bg" class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center transition-colors duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-800 text-xs">Sisa Waktu Pengerjaan Ujian</h4>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Durasi Total: {{ $durationMinutes }} Menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span id="countdown-text" class="text-2xl font-black text-indigo-600 tracking-tight transition-colors duration-300">00:00</span>
                    <div class="w-32 bg-slate-100 rounded-full h-2 overflow-hidden hidden sm:block">
                        <div id="progress-bar" class="bg-indigo-600 h-full rounded-full transition-all duration-1000" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            {{-- Auto-submit Overlay Popup --}}
            <div id="timeout-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
                <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center space-y-4 shadow-2xl scale-95 transition-all">
                    <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Batas Waktu Telah Habis!</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Sistem sedang mengunci lembar jawaban Anda dan mengirimkannya secara otomatis. Mohon tunggu beberapa saat...
                    </p>
                    <div class="w-8 h-8 border-4 border-rose-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                </div>
            </div>
            
            {{-- Candidate Welcome Banner --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/5 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100/30">
                            Lembar Soal & Jawaban
                        </span>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Evaluasi Tes Praktis Kompetensi</h2>
                        <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs font-semibold text-slate-400">
                            <p class="text-slate-600">Nama: <span class="font-extrabold text-slate-800">{{ $candidate->name }}</span></p>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                            <p class="text-slate-600">Posisi: <span class="font-extrabold text-indigo-600">{{ $candidate->position->name ?? '—' }}</span></p>
                        </div>
                    </div>
                    
                    {{-- Guidelines Summary --}}
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl text-xs text-slate-500 space-y-2 max-w-xs w-full">
                        <p class="font-bold text-slate-700 uppercase tracking-wider text-[9px] mb-1">Panduan Pengisian:</p>
                        <div class="flex gap-2">
                            <span class="text-indigo-500">✓</span>
                            <span>Jawablah secara lengkap & detail.</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-500">✓</span>
                            <span>Nilai kelayakan minimum adalah <b>70</b>.</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="text-indigo-500">✓</span>
                            <span>Penilaian otomatis berbasis analisis kedalaman jawaban.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form for Questions --}}
            <form action="{{ route('public.test.submit', $test->token) }}" method="POST" class="space-y-6">
                @csrf
                
                @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800 space-y-1">
                    <p class="font-bold">Gagal Mengirimkan Tes:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="space-y-6">
                    @foreach($questions as $index => $q)
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-sm space-y-4 transition-all duration-200 hover:border-indigo-100/80">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="space-y-1">
                                <label for="q-{{ $q['id'] }}" class="text-sm font-bold text-slate-800 block leading-relaxed">
                                    {{ $q['text'] }}
                                </label>
                                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Pertanyaan Wajib Dijawab</span>
                            </div>
                        </div>
                        
                        <textarea 
                            id="q-{{ $q['id'] }}" 
                            name="answers[{{ $q['id'] }}]" 
                            required 
                            rows="6"
                            class="form-input w-full text-slate-700 placeholder-slate-400 bg-slate-50 border-slate-100/80 transition-all focus:bg-white resize-y"
                            placeholder="{{ $q['placeholder'] }}">{{ old("answers." . $q['id']) }}</textarea>
                    </div>
                    @endforeach
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
                    <p class="text-xs text-slate-400 font-medium px-2">💡 Pastikan jawaban Anda lengkap sebelum dikirimkan.</p>
                    <button type="submit" class="btn-primary py-3 px-6 rounded-xl flex items-center gap-2 text-xs font-bold tracking-wider shadow-lg shadow-indigo-600/20">
                        Kirim Jawaban Tes
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </form>
            
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-100 py-6 text-center text-xs text-slate-400">
        <p>© 2026 Ultimate Education. Seluruh Hak Cipta Dilindungi.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let remainingSeconds = {{ $remainingSeconds }};
            const totalDurationSeconds = {{ $durationMinutes }} * 60;
            const countdownText = document.getElementById('countdown-text');
            const progressBar = document.getElementById('progress-bar');
            const timerBanner = document.getElementById('timer-banner');
            const timerIconBg = document.getElementById('timer-icon-bg');
            const timeoutOverlay = document.getElementById('timeout-overlay');
            const testForm = document.querySelector('form');

            function updateTimer() {
                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    countdownText.textContent = "00:00";
                    progressBar.style.width = "0%";
                    
                    // Show auto-submit overlay
                    timeoutOverlay.classList.remove('hidden');
                    
                    // Disable all textareas to prevent editing
                    document.querySelectorAll('textarea').forEach(el => el.readOnly = true);
                    
                    // Submit the form automatically after a short delay
                    setTimeout(() => {
                        testForm.submit();
                    }, 1500);
                    return;
                }

                // Calculate formatting
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;
                
                // Format text representation
                const minStr = String(minutes).padStart(2, '0');
                const secStr = String(seconds).padStart(2, '0');
                countdownText.textContent = `${minStr}:${secStr}`;

                // Calculate progress bar percentage
                const percentLeft = (remainingSeconds / totalDurationSeconds) * 100;
                progressBar.style.width = `${percentLeft}%`;

                // Alert colors for near timeout threshold
                if (remainingSeconds <= 180) { // 3 minutes or less (Critical)
                    // Rose Theme
                    countdownText.className = "text-2xl font-black text-rose-600 tracking-tight animate-pulse";
                    progressBar.className = "bg-rose-500 h-full rounded-full";
                    timerIconBg.className = "w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center";
                    timerBanner.className = "sticky top-24 z-20 bg-rose-50/50 border border-rose-100 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-4 flex-wrap transition-all duration-300";
                } else if (remainingSeconds <= 600) { // 10 minutes or less (Warning)
                    // Amber Theme
                    countdownText.className = "text-2xl font-black text-amber-600 tracking-tight";
                    progressBar.className = "bg-amber-500 h-full rounded-full";
                    timerIconBg.className = "w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center";
                    timerBanner.className = "sticky top-24 z-20 bg-amber-50/40 border border-amber-100 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-4 flex-wrap transition-all duration-300";
                }

                remainingSeconds--;
            }

            // Run first immediately
            updateTimer();
            // Interval loop
            const timerInterval = setInterval(updateTimer, 1000);
        });
    </script>
</body>
</html>
