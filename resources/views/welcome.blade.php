<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MAIRCA ATS - Ultimate Education</title>

        <!-- Google Fonts Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Tailwind & Alpine via Vite / CDN Fallback -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    darkMode: 'class',
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Inter', 'sans-serif'],
                                outfit: ['Outfit', 'sans-serif'],
                            }
                        }
                    }
                }
            </script>
        @endif
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }
            .glassmorphism {
                background: rgba(15, 23, 42, 0.65);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .glassmorphism-light {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(0, 0, 0, 0.05);
            }
        </style>
    </head>
    <body class="bg-[#0b0f19] text-slate-100 min-h-screen overflow-x-hidden relative" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

        <!-- Background Gradient Orbs -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none -translate-x-1/2"></div>
        <div class="absolute top-[20%] right-1/4 w-[30rem] h-[30rem] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none translate-x-1/2"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-purple-600/10 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- NAVBAR -->
        <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300" 
             :class="scrolled ? 'glassmorphism py-3 shadow-lg' : 'bg-transparent py-5'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-blue-600 flex items-center justify-center shadow-md shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-outfit font-black text-xl bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">MAIRCA <span class="text-indigo-400">ATS</span></span>
                        <span class="block text-[9px] text-slate-400 tracking-wider font-semibold uppercase">Ultimate Education</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-semibold text-sm shadow-lg shadow-indigo-500/25 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                                <span>Ke Dashboard</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white transition-colors">
                                Masuk (Login)
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-sm transition-all border border-slate-700/80 hover:border-slate-600 shadow-md">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- HERO SECTION -->
        <section class="pt-32 pb-20 relative flex items-center justify-center overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <!-- Branded Tag -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/25 text-indigo-400 text-xs font-semibold uppercase tracking-wider mb-6 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                    Sistem Rekrutmen Cerdas Terintegrasi
                </div>

                <!-- Main Heading -->
                <h1 class="font-outfit font-black text-4xl sm:text-6xl lg:text-7xl tracking-tight leading-none mb-6">
                    Temukan Talent Terbaik<br />
                    Dengan Metode <span class="bg-gradient-to-r from-indigo-400 via-blue-400 to-emerald-400 bg-clip-text text-transparent">MAIRCA</span>
                </h1>

                <!-- Subheading -->
                <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-400 leading-relaxed mb-10">
                    Ubah proses rekrutmen Ultimate Education menjadi lebih transparan, objektif, dan terstandarisasi. Ambil keputusan perekrutan terbaik berdasarkan data analitik yang akurat.
                </p>

                <!-- Action Button -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-base shadow-xl shadow-indigo-500/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                            Mulai Kelola Rekrutmen
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-base shadow-xl shadow-indigo-500/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                            Masuk ke Sistem
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14.5"/></svg>
                        </a>
                        <a href="#fitur" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-900/60 hover:bg-slate-800 text-slate-300 hover:text-white font-semibold text-base transition-all border border-slate-800 hover:border-slate-700 flex items-center justify-center">
                            Pelajari Selengkapnya
                        </a>
                    @endauth
                </div>

                <!-- Stats Bar -->
                <div class="mt-20 max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 p-6 rounded-3xl glassmorphism">
                    <div class="text-center p-3">
                        <p class="font-outfit font-black text-3xl text-white mb-1">50+</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kandidat Terkelola</p>
                    </div>
                    <div class="text-center p-3 border-l border-slate-800/80">
                        <p class="font-outfit font-black text-3xl text-indigo-400 mb-1">7 Tahap</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pipeline Rekrutmen</p>
                    </div>
                    <div class="text-center p-3 border-l border-slate-800/80">
                        <p class="font-outfit font-black text-3xl text-emerald-400 mb-1">Objektif</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Analisis MAIRCA</p>
                    </div>
                    <div class="text-center p-3 border-l border-slate-800/80">
                        <p class="font-outfit font-black text-3xl text-blue-400 mb-1">Real-time</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dashboard & Grafik</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section id="fitur" class="py-20 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-outfit font-black text-3xl sm:text-4xl text-white mb-4">Fitur Premium Unggulan</h2>
                    <p class="text-slate-400">Dirancang khusus untuk mempermudah HR dan Reviewer dalam menemukan kecocokan kandidat secara akurat.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1 -->
                    <div class="glassmorphism rounded-2xl p-6 hover:-translate-y-2 transition-all duration-300 group hover:border-indigo-500/30">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white mb-2">Pelacakan Pipeline</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">Pantau setiap proses rekrutmen dari seleksi berkas, tes praktis, interview hingga keputusan akhir.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glassmorphism rounded-2xl p-6 hover:-translate-y-2 transition-all duration-300 group hover:border-emerald-500/30">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white mb-2">Keputusan MAIRCA</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">Metode pengambilan keputusan Multi-Attributive Ideal-Real Comparative Analysis untuk ranking kandidat yang 100% objektif.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glassmorphism rounded-2xl p-6 hover:-translate-y-2 transition-all duration-300 group hover:border-blue-500/30">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white mb-2">Jadwal Wawancara</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">Kelola agenda interview secara terintegrasi dengan tautan Zoom dan notifikasi jadwal real-time.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="glassmorphism rounded-2xl p-6 hover:-translate-y-2 transition-all duration-300 group hover:border-purple-500/30">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white mb-2">Kolaborasi Tim</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">Role HR & Reviewer terintegrasi penuh untuk memberikan skor evaluasi multi-perspektif tanpa bias.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS SECTION -->
        <section class="py-20 bg-slate-950/40 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <h2 class="font-outfit font-black text-3xl sm:text-4xl text-white mb-4">Cara Kerja Sistem</h2>
                    <p class="text-slate-400">Ikuti 4 langkah sederhana dari pendaftaran hingga pengangkatan kandidat terbaik.</p>
                </div>

                <div class="relative">
                    <!-- Connector line for desktop -->
                    <div class="hidden lg:block absolute top-1/2 left-4 right-4 h-0.5 bg-gradient-to-r from-indigo-500/25 via-blue-500/25 to-emerald-500/25 -translate-y-12"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
                        <!-- Step 1 -->
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-900 border-2 border-indigo-500 text-indigo-400 flex items-center justify-center font-outfit font-black text-xl mx-auto mb-6 shadow-lg shadow-indigo-500/10">
                                01
                            </div>
                            <h3 class="font-outfit font-bold text-lg text-white mb-2">Daftarkan Kandidat</h3>
                            <p class="text-slate-400 text-xs sm:text-sm px-4">HR mengunggah data calon pelamar beserta file resume digitalnya.</p>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-900 border-2 border-blue-500 text-blue-400 flex items-center justify-center font-outfit font-black text-xl mx-auto mb-6 shadow-lg shadow-blue-500/10">
                                02
                            </div>
                            <h3 class="font-outfit font-bold text-lg text-white mb-2">Jadwalkan Wawancara</h3>
                            <p class="text-slate-400 text-xs sm:text-sm px-4">HR mengatur sesi interview (HR & User) secara real-time.</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-900 border-2 border-purple-500 text-purple-400 flex items-center justify-center font-outfit font-black text-xl mx-auto mb-6 shadow-lg shadow-purple-500/10">
                                03
                            </div>
                            <h3 class="font-outfit font-bold text-lg text-white mb-2">Input Penilaian</h3>
                            <p class="text-slate-400 text-xs sm:text-sm px-4">Reviewer memberikan skor kriteria berbasis kompetensi kerja.</p>
                        </div>

                        <!-- Step 4 -->
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-900 border-2 border-emerald-500 text-emerald-400 flex items-center justify-center font-outfit font-black text-xl mx-auto mb-6 shadow-lg shadow-emerald-500/10">
                                04
                            </div>
                            <h3 class="font-outfit font-bold text-lg text-white mb-2">Analisis & Ranking</h3>
                            <p class="text-slate-400 text-xs sm:text-sm px-4">Metode MAIRCA memproses semua data dan menyajikan ranking terbaik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="py-20 relative overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="rounded-3xl p-8 sm:p-14 text-center relative overflow-hidden border border-slate-800/80 shadow-2xl"
                     style="background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), rgba(15, 23, 42, 0.8));">
                    <h2 class="font-outfit font-black text-3xl sm:text-5xl text-white mb-6">Mulai Rekrutmen Lebih Efisien</h2>
                    <p class="max-w-xl mx-auto text-slate-300 text-sm sm:text-base mb-10 leading-relaxed">
                        Tingkatkan kualitas rekrutmen di Ultimate Education dengan standar penilaian modern berbasis sains pengambilan keputusan.
                    </p>
                    <div>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-4 rounded-2xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-base shadow-lg shadow-white/10 transition-all hover:-translate-y-1 inline-flex items-center gap-2">
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-base shadow-lg shadow-white/10 transition-all hover:-translate-y-1 inline-flex items-center gap-2">
                                Masuk & Kelola Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="py-8 border-t border-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500 font-medium">
                <p class="mb-2">© 2026 Ultimate Education. Seluruh Hak Cipta Dilindungi.</p>
                <p>Sistem Pengambil Keputusan Rekrutmen Karyawan dengan Metode MAIRCA.</p>
            </div>
        </footer>

    </body>
</html>
