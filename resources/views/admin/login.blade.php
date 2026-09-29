<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Keamanan Admin — INULIN</title>
    
    <link rel="icon" type="image/png" href="{{ $settings->faviconUrl() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        canvas: '#090D16',
                        card: '#121822',
                        border: '#1E293B',
                    },
                    fontFamily: {
                        sans: ['Geist', 'sans-serif'],
                        heading: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #090D16;
            color: #E2E8F0;
            font-family: 'Geist', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Space Grotesk', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        *:focus-visible {
            outline: 2px solid #F59E0B;
            outline-offset: 2px;
        }
        @keyframes subtle-glow {
            0%, 100% { opacity: 0.15; transform: scale(1); }
            50% { opacity: 0.25; transform: scale(1.05); }
        }
        .ambient-glow {
            animation: subtle-glow 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-amber-500/20 selection:text-amber-300">

    <!-- Subtle Ambient Glow -->
    <div class="ambient-glow absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[480px] h-[380px] bg-amber-500/10 blur-[130px] rounded-full pointer-events-none -z-10"></div>
    <div class="ambient-glow absolute bottom-10 right-10 w-64 h-64 bg-emerald-500/5 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    <div class="w-full max-w-md relative z-10" x-data="{ showPassword: false, submitting: false }">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-4 group">
                <img src="{{ $settings->logoUrl() }}" alt="Logo INULIN" class="h-10 w-10 object-contain rounded-xl p-1 bg-black/40 border border-slate-800 group-hover:border-amber-500/40 transition-colors">
                <div class="text-left">
                    <span class="font-heading font-bold text-2xl text-white tracking-tight group-hover:text-amber-400 transition-colors">INULIN</span>
                    <span class="block font-mono text-[10px] text-amber-400 font-semibold tracking-wider uppercase">LAB SECURITY PORTAL</span>
                </div>
            </a>
            <h1 class="text-xl font-bold text-white font-heading">Autentikasi Akses Administrator</h1>
            <p class="text-xs text-slate-400 mt-1 font-mono">Area Terbatas • Autentikasi Terenkripsi & Diaudit</p>
        </div>

        <!-- Login Card -->
        <div class="rounded-2xl bg-[#121822] border border-[#1E293B] p-6 sm:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.6)] space-y-6">
            
            <!-- Security Status Pill -->
            <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[#070A0F] border border-slate-800 text-[11px] font-mono text-slate-400">
                <span class="flex items-center gap-1.5 text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>WAF & Rate Limiting Aktif</span>
                </span>
                <span class="text-slate-500">TLS 1.3 / AES-256</span>
            </div>

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/40 border border-rose-600/40 text-rose-200 text-xs flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-rose-400 text-[18px]">gpp_bad</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-600/40 text-emerald-200 text-xs flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-emerald-400 text-[18px]">verified_user</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.authenticate') }}" class="space-y-4" @submit="submitting = true">
                @csrf

                <!-- Email Field -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-mono text-slate-300">
                        EMAIL ADMINISTRATOR
                    </label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@domain.id" class="w-full bg-[#070A0F] border border-[#1E293B] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-slate-600 font-mono transition-colors">
                    </div>
                </div>

                <!-- Password Field with View/Hide Toggle -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-mono text-slate-300">
                            KATA SANDI
                        </label>
                        <button type="button" @click="showPassword = !showPassword" class="text-[11px] font-mono text-amber-400 hover:text-amber-300 transition-colors flex items-center gap-1 select-none">
                            <span class="material-symbols-outlined text-[15px]" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                            <span x-text="showPassword ? 'Sembunyikan' : 'Lihat Sandi'">Lihat Sandi</span>
                        </button>
                    </div>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password" placeholder="••••••••••••" class="w-full bg-[#070A0F] border border-[#1E293B] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-lg pl-3.5 pr-11 py-2.5 text-sm text-white placeholder-slate-600 font-mono transition-colors">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors" tabindex="-1">
                            <span class="material-symbols-outlined text-[18px]" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Return -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-400 select-none">
                        <input type="checkbox" name="remember" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B] focus:ring-0 w-4 h-4">
                        <span>Ingat sesi ini</span>
                    </label>

                    <a href="{{ route('home') }}" class="text-xs font-mono text-slate-500 hover:text-amber-400 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                        <span>Web Publik</span>
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" :disabled="submitting" class="w-full py-3 px-4 rounded-xl bg-[#F59E0B] hover:bg-amber-300 disabled:opacity-50 text-slate-950 font-heading font-bold text-sm tracking-wide transition-all shadow-[0_0_16px_rgba(245,158,11,0.25)] hover:shadow-[0_0_24px_rgba(245,158,11,0.45)] flex items-center justify-center gap-2 mt-2">
                    <span class="material-symbols-outlined text-[18px]" x-show="!submitting">lock</span>
                    <span class="material-symbols-outlined text-[18px] animate-spin" x-show="submitting" x-cloak>sync</span>
                    <span x-text="submitting ? 'Memverifikasi...' : 'Verifikasi & Masuk'">Verifikasi & Masuk</span>
                </button>
            </form>

            <!-- Bottom Security Note -->
            <div class="pt-4 border-t border-slate-800/80 text-center">
                <p class="text-[11px] font-mono text-slate-500 leading-relaxed">
                    Setiap aktivitas login dan kegagalan autentikasi dicatat dalam Audit Log berserta stempel waktu, IP address, dan identitas perangkat.
                </p>
            </div>
        </div>

    </div>

</body>
</html>
