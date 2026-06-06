<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terima Kasih — Ultimate Education</title>
    <link rel="icon" type="image/webp" href="{{ asset('assets/logo-ue.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 antialiased text-slate-800 font-sans min-h-screen flex flex-col justify-between">

    {{-- Header --}}
    <header class="bg-white border-b border-slate-100 py-5 px-6 shadow-sm">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logo-ue.webp') }}" alt="Ultimate Education Logo" class="h-10 w-auto object-contain">
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <h1 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Recruitment Portal</h1>
                </div>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-100 shadow-xl text-center space-y-6">
            <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            
            <div class="space-y-2">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Jawaban Berhasil Terkirim!</h2>
                <p class="text-slate-500 text-sm">
                    Terima kasih, <b>{{ $candidate->name }}</b>. Jawaban tes praktis kompetensi Anda untuk posisi <b>{{ $candidate->position->name ?? '—' }}</b> telah sukses terdaftar di sistem rekrutmen kami.
                </p>
            </div>
            
            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left text-xs text-slate-500 space-y-2">
                <p class="font-bold text-slate-700 uppercase tracking-wider text-[9px]">Langkah Selanjutnya:</p>
                <p class="leading-relaxed">
                    1. Tim Reviewer dan HR kami akan mengevaluasi kesesuaian nilai tes kompetensi Anda.
                </p>
                <p class="leading-relaxed">
                    2. Jika nilai memenuhi standar kualifikasi internal kami, Anda akan dihubungi untuk tahapan selanjutnya: <b>Wawancara HR</b>.
                </p>
                <p class="leading-relaxed">
                    3. Undangan resmi beserta jadwal akan dikirimkan langsung ke email terdaftar Anda (<b>{{ $candidate->email }}</b>).
                </p>
            </div>

            <p class="text-[10px] text-slate-400 font-semibold">Anda dapat menutup tab halaman browser ini sekarang.</p>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-100 py-6 text-center text-xs text-slate-400">
        <p>© 2026 Ultimate Education. Seluruh Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>
