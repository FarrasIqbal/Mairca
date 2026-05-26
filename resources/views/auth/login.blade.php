<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk — Mairca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center m-0 p-0 overflow-x-hidden">

    <div class="min-h-screen w-full flex">
        
        <div class="hidden md:flex w-7/12 bg-gradient-to-br from-[#1e40af] to-[#2563eb] text-white items-center justify-center p-16">
            <div class="max-w-xl w-full">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md" style="background: linear-gradient(135deg,#4f46e5,#3b82f6);">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold tracking-wider text-blue-200 uppercase">Ultimate Education Recruitment System</p>
                        <h2 class="text-3xl font-extrabold mt-0.5 tracking-tight">System Recruitment</h2>
                    </div>
                </div>

                <p class="text-slate-100/90 leading-relaxed mb-8">
                    Achieve your best score and fulfill the Dream of Going Abroad.
                </p>

                <div class="w-full mt-6">
                    <div class="w-full h-64 rounded-2xl shadow-2xl transition-transform hover:scale-[1.01] duration-300" 
                         style="background: linear-gradient(135deg, #60a5fa, #4f46e5);">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center p-8 sm:p-12 md:p-16 bg-white">
            <div class="w-full max-w-md mx-auto">
                        
                @if(session('status'))
                    <div class="mb-5 text-sm text-green-700 bg-green-50 p-4 rounded-xl border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 text-sm text-red-600 bg-red-50 p-4 rounded-xl border border-red-200">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                               placeholder="example@gmail.com"
                               class="w-full border border-slate-300 rounded-xl px-4 py-3 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm" />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required class="form-input pr-10" />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 end-0 px-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.275 4.057-5.065 7-9.543 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.956 9.956 0 012.223-3.488m2.36-1.873A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.132 5.25" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center text-sm text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" />
                            <span class="ms-2 font-medium">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 hover:underline font-semibold transition-colors">Lupa password?</a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl py-3 font-semibold transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.99]">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm text-slate-500 font-medium">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 hover:underline font-semibold transition-colors">Daftar</a>
                </div>
                
            </div>
        </div>

    </div>

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