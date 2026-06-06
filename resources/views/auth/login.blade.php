<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk — Mairca</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo-ue.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes float-slow-reverse {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(10px); }
        }
        .animate-bounce-slow {
            animation: float-slow 6s ease-in-out infinite;
        }
        .animate-pulse-slow {
            animation: float-slow-reverse 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center m-0 p-0 overflow-x-hidden">

    <div class="min-h-screen w-full flex">
        
        <!-- Left Side: Aesthetic Premium Info & Mock Dashboard -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white flex-col justify-between p-16 relative overflow-hidden">
            <!-- Glowing Orbs -->
            <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] rounded-full bg-indigo-500/10 blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-violet-500/10 blur-[120px] pointer-events-none"></div>
            
            <!-- Top Header Logo -->
            <div class="flex flex-col gap-1 z-10">
                <img src="{{ asset('assets/logo-ue.webp') }}" alt="Ultimate Education Logo" class="h-10 w-auto object-contain bg-white rounded-xl px-3 py-1.5 shadow-lg self-start">
                <div class="mt-2 flex items-center gap-2">
                    <h3 class="text-sm font-extrabold uppercase tracking-widest text-indigo-400">Mairca</h3>
                    <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                    <p class="text-[10px] text-slate-400 font-semibold">Ultimate Recruitment System</p>
                </div>
            </div>

            <!-- Main Content & Mock Dashboard -->
            <div class="z-10 my-auto py-12 flex flex-col justify-center">
                <h1 class="text-4xl font-black tracking-tight leading-tight bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
                    Achieve your best score & fulfill the Dream.
                </h1>
                <p class="text-slate-400 text-sm mt-3 max-w-md leading-relaxed">
                    Sistem rekrutmen berbasis keputusan MAIRCA terintegrasi untuk menemukan talenta terbaik secara objektif dan efisien.
                </p>

                <!-- Mock Dashboard Card -->
                <div class="mt-12 relative">
                    <!-- Glass Container -->
                    <div class="w-full bg-white/[0.03] backdrop-blur-xl border border-white/[0.08] rounded-2xl p-6 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] relative overflow-hidden transition-all duration-500 hover:border-indigo-500/20">
                        <div class="flex items-center justify-between pb-4 border-b border-white/[0.08] mb-4">
                            <div>
                                <h4 class="text-xs font-bold text-slate-300 uppercase tracking-widest">Kandidat Teratas</h4>
                                <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Updated just now</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[9px] font-bold text-indigo-400">Live Stage</span>
                        </div>

                        <!-- Candidates Rows -->
                        <div class="space-y-3.5">
                            <!-- Candidate 1 -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/[0.02] border border-white/[0.04] transition-all hover:bg-white/[0.05]">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 border border-indigo-500/35 flex items-center justify-center text-xs font-bold text-indigo-300">
                                        AP
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-200">Aria Pratama</p>
                                        <p class="text-[9px] text-slate-400 font-medium">Laravel Developer</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-[9px] font-extrabold uppercase tracking-wide text-amber-400">Tes Praktis</span>
                            </div>

                            <!-- Candidate 2 -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/[0.02] border border-white/[0.04] transition-all hover:bg-white/[0.05]">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 border border-emerald-500/35 flex items-center justify-center text-xs font-bold text-emerald-300">
                                        SR
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-200">Siti Rahma</p>
                                        <p class="text-[9px] text-slate-400 font-medium">SEO Specialist</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[9px] font-extrabold uppercase tracking-wide text-emerald-400">Hired</span>
                            </div>

                            <!-- Candidate 3 -->
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/[0.02] border border-white/[0.04] transition-all hover:bg-white/[0.05]">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-violet-500/20 border border-violet-500/35 flex items-center justify-center text-xs font-bold text-violet-300">
                                        BS
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-200">Budi Santoso</p>
                                        <p class="text-[9px] text-slate-400 font-medium">UI/UX Designer</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[9px] font-extrabold uppercase tracking-wide text-indigo-400">Wawancara HR</span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Mini Card 1: Metrics -->
                    <div class="absolute -right-6 -bottom-6 bg-slate-900/80 backdrop-blur-xl border border-white/[0.1] rounded-2xl p-4 shadow-xl flex items-center gap-3 animate-bounce-slow hover:scale-105 transition-transform duration-300">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Akurasi SPK</p>
                            <p class="text-xs font-black text-slate-200">MAIRCA 98.4%</p>
                        </div>
                    </div>

                    <!-- Floating Mini Card 2: Applicants -->
                    <div class="absolute -left-6 -top-6 bg-slate-900/80 backdrop-blur-xl border border-white/[0.1] rounded-2xl p-4 shadow-xl flex items-center gap-3 animate-pulse-slow hover:scale-105 transition-transform duration-300">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50/10 flex items-center justify-center text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Pelamar</p>
                            <p class="text-xs font-black text-slate-200">1,280 Kandidat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="z-10 text-slate-500 text-[10px] font-semibold flex items-center gap-1.5">
                <span>Mairca System</span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-800"></span>
                <span>© 2026 Ultimate Education</span>
            </div>
        </div>

        <!-- Right Side: Clean Premium Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-16 bg-white relative">
            
            <!-- Abstract background shape on mobile/tablet -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-slate-50 rounded-full blur-[100px] pointer-events-none lg:hidden"></div>

            <div class="w-full max-w-md mx-auto z-10">
                
                <!-- Form Header -->
                <div class="mb-8">
                    <!-- Mobile Logo only -->
                    <div class="flex items-center gap-2.5 mb-6 lg:hidden">
                        <img src="{{ asset('assets/logo-ue.webp') }}" alt="Ultimate Education Logo" class="h-9 w-auto object-contain bg-white rounded-lg px-2.5 py-1 shadow-md border border-slate-100">
                        <span class="text-sm font-black uppercase tracking-wider text-slate-800">Mairca</span>
                    </div>

                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang</h2>
                    <p class="text-slate-400 text-xs font-semibold mt-1.5">Masuk untuk mengelola rekrutmen dan ujian praktis.</p>
                </div>
                        
                @if(session('status'))
                    <div class="mb-5 text-xs font-bold text-emerald-700 bg-emerald-50/80 p-4 rounded-xl border border-emerald-100 shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 text-xs font-bold text-rose-700 bg-rose-50/80 p-4 rounded-xl border border-rose-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-1.5 text-rose-800">
                            <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Mohon koreksi kesalahan berikut:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-[11px] font-medium text-rose-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                   placeholder="Masukkan alamat email Anda"
                                   class="w-full border border-slate-200 rounded-xl ps-10 pe-4 py-3.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 ps-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                   placeholder="Masukkan password Anda"
                                   class="w-full border border-slate-200 rounded-xl ps-10 pe-10 py-3.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm" />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 end-0 px-3 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.956 9.956 0 012.223-3.488m2.36-1.873A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.132 5.25" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center text-xs text-slate-500 cursor-pointer select-none font-bold">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-4 focus:ring-indigo-500/10 cursor-pointer" />
                            <span class="ms-2">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2.5">
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl py-3.5 font-bold uppercase tracking-wider text-xs shadow-md shadow-indigo-600/20 hover:shadow-lg hover:shadow-indigo-600/35 transition-all duration-200 active:scale-[0.99] border border-indigo-500/20">
                            Masuk ke Aplikasi
                        </button>
                    </div>
                </form>


                
            </div>
        </div>

    </div>

    <!-- Password visibility toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (!pwd || !toggle) return;

            toggle.addEventListener('click', function () {
                if (pwd.type === 'password') {
                    pwd.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    pwd.type = 'password';
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>