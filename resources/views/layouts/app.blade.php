<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'INULIN — Instal Ulang Tanpa Ribet' }}</title>
    <meta name="description" content="Jasa instal ulang Windows & Linux untuk laptop dan PC. Proses cepat, bergaransi 14 hari, tanpa bloatware, dan partisi data aman terisolasi.">
    
    <link rel="icon" type="image/png" href="{{ \App\Models\BusinessSetting::current()->faviconUrl() }}">
    
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
                        canvas: '#0B0F17',
                        surface: {
                            DEFAULT: '#121822',
                            subtle: '#18212E',
                            border: '#222F3E',
                            inset: '#070A0F',
                            cardHover: '#161F2C',
                        },
                        amber: {
                            glow: '#F59E0B',
                            core: '#0284C7',
                            light: '#38BDF8',
                            muted: '#9CF0FF',
                        }
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
            background-color: #0B0F17;
            color: #DFE2EE;
            font-family: 'Geist', sans-serif;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        *:focus-visible {
            outline: 2px solid #F59E0B;
            outline-offset: 3px;
        }

        /* Page Loading Indicator */
        #page-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 2.5px;
            width: 0%;
            background: linear-gradient(90deg, #0284C7, #F59E0B, #38BDF8);
            box-shadow: 0 0 10px rgba(0, 229, 255, 0.8), 0 0 20px rgba(0, 229, 255, 0.5);
            z-index: 9999;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
            pointer-events: none;
        }

        /* Page Transition Animations */
        @keyframes pageFadeIn {
            0% {
                opacity: 0;
                transform: translateY(14px) scale(0.995);
                filter: blur(4px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }
        @keyframes pageFadeOut {
            0% {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
            100% {
                opacity: 0;
                transform: translateY(-10px);
                filter: blur(3px);
            }
        }

        main {
            opacity: 1;
            transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), filter 0.25s ease;
        }
        main.page-enter-active {
            animation: pageFadeIn 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        main.page-exit-active {
            animation: pageFadeOut 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Varied Content Element Keyframes */
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(24px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            0% { opacity: 0; transform: translateX(-24px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            0% { opacity: 0; transform: translateX(24px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes zoomIn {
            0% { opacity: 0; transform: scale(0.94); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-7px); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(0, 229, 255, 0.15); }
            50% { box-shadow: 0 0 30px rgba(0, 229, 255, 0.35); }
        }
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(1000%); }
        }

        /* Utility classes */
        .animate-fade-up {
            opacity: 0;
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-left {
            opacity: 0;
            animation: fadeInLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-right {
            opacity: 0;
            animation: fadeInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-zoom-in {
            opacity: 0;
            animation: zoomIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-float {
            animation: floatY 4.5s ease-in-out infinite;
        }
        .animate-pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        /* Stagger Delays */
        .delay-100 { animation-delay: 100ms !important; }
        .delay-150 { animation-delay: 150ms !important; }
        .delay-200 { animation-delay: 200ms !important; }
        .delay-300 { animation-delay: 300ms !important; }
        .delay-400 { animation-delay: 400ms !important; }
        .delay-500 { animation-delay: 500ms !important; }
        .delay-600 { animation-delay: 600ms !important; }
        .delay-700 { animation-delay: 700ms !important; }
        .delay-800 { animation-delay: 800ms !important; }

        /* Scroll-triggered reveal */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Technical Interactive Card hover */
        .tech-card {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .tech-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 229, 255, 0.4);
            box-shadow: 0 12px 30px -10px rgba(0, 229, 255, 0.15);
        }

        /* Solid Star Fill for Material Symbols */
        .star-filled {
            font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24 !important;
        }
        .star-empty {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 !important;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col selection:bg-amber-500/20 selection:text-amber-300" x-data="{ mobileNav: false }">

    <!-- Top Glow Progress Bar for Page Transitions -->
    <div id="page-progress-bar"></div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-[#0B0F17]/90 backdrop-blur-md border-b border-[#222F3E]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ \App\Models\BusinessSetting::current()->logoUrl() }}" alt="Logo INULIN" class="h-9 w-9 object-contain rounded-lg p-0.5 bg-black/40 border border-[#222F3E] group-hover:border-amber-400/50 transition-colors">
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="font-heading font-bold text-xl text-white tracking-tight group-hover:text-amber-400 transition-colors">INULIN</span>
                        <span class="font-mono text-[10px] font-semibold text-amber-400 bg-amber-950/60 border border-amber-800/40 px-1.5 py-0.5 rounded">OS.LAB</span>
                    </div>
                    <span class="font-mono text-[11px] text-slate-400 tracking-wider hidden sm:block">{{ \App\Models\BusinessSetting::current()->tagline ?: 'Instal Ulang Tanpa Ribet.' }}</span>
                </div>
            </a>

            <!-- Desktop Navigation Links (Multi-page) -->
            <nav class="hidden lg:flex items-center gap-7 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}" class="relative py-1 hover:text-amber-400 transition-colors {{ request()->routeIs('home') ? 'text-amber-400 font-semibold' : '' }}">
                    Beranda
                    @if(request()->routeIs('home'))
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_8px_rgba(245,158,11,0.8)] rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('services.index') }}" class="relative py-1 hover:text-amber-400 transition-colors {{ request()->routeIs('services.*') ? 'text-amber-400 font-semibold' : '' }}">
                    Layanan
                    @if(request()->routeIs('services.*'))
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_8px_rgba(245,158,11,0.8)] rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('how-it-works') }}" class="relative py-1 hover:text-amber-400 transition-colors {{ request()->routeIs('how-it-works') ? 'text-amber-400 font-semibold' : '' }}">
                    Cara Kerja
                    @if(request()->routeIs('how-it-works'))
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_8px_rgba(245,158,11,0.8)] rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('about') }}" class="relative py-1 hover:text-amber-400 transition-colors {{ request()->routeIs('about') ? 'text-amber-400 font-semibold' : '' }}">
                    Tentang Kami
                    @if(request()->routeIs('about'))
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_8px_rgba(245,158,11,0.8)] rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('gallery') }}" class="relative py-1 hover:text-amber-400 transition-colors {{ request()->routeIs('gallery') ? 'text-amber-400 font-semibold' : '' }}">
                    Portofolio
                    @if(request()->routeIs('gallery'))
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_8px_rgba(245,158,11,0.8)] rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('faq') }}" class="relative py-1 hover:text-amber-400 transition-colors {{ request()->routeIs('faq') ? 'text-amber-400 font-semibold' : '' }}">
                    FAQ
                    @if(request()->routeIs('faq'))
                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gradient-to-r from-amber-500 to-amber-300 shadow-[0_0_8px_rgba(245,158,11,0.8)] rounded-full"></span>
                    @endif
                </a>
            </nav>

            <!-- Action CTAs -->
            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.general') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-semibold text-xs sm:text-sm tracking-wide transition-all shadow-[0_0_16px_rgba(245,158,11,0.25)] hover:shadow-[0_0_24px_rgba(245,158,11,0.45)] hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    <span>Booking Sekarang</span>
                </a>

                <!-- Mobile Hamburger Toggle -->
                <button type="button" @click="mobileNav = !mobileNav" class="lg:hidden w-10 h-10 rounded-lg bg-[#18212E] border border-[#222F3E] text-slate-300 flex items-center justify-center hover:text-white hover:border-amber-400/50 transition-colors" :aria-expanded="mobileNav" aria-label="Menu Navigasi">
                    <span class="material-symbols-outlined" x-text="mobileNav ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileNav" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden border-b border-[#222F3E] bg-[#0F141D] px-6 py-6 space-y-4">
            <nav class="flex flex-col space-y-3 text-sm font-medium text-slate-300">
                <a @click="mobileNav = false" href="{{ route('home') }}" class="py-2.5 hover:text-amber-400 border-b border-[#222F3E]/50 flex items-center justify-between {{ request()->routeIs('home') ? 'text-amber-400 font-semibold' : '' }}">
                    <span>Beranda</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a @click="mobileNav = false" href="{{ route('services.index') }}" class="py-2.5 hover:text-amber-400 border-b border-[#222F3E]/50 flex items-center justify-between {{ request()->routeIs('services.*') ? 'text-amber-400 font-semibold' : '' }}">
                    <span>Katalog Layanan</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a @click="mobileNav = false" href="{{ route('how-it-works') }}" class="py-2.5 hover:text-amber-400 border-b border-[#222F3E]/50 flex items-center justify-between {{ request()->routeIs('how-it-works') ? 'text-amber-400 font-semibold' : '' }}">
                    <span>Cara Kerja & Protokol</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a @click="mobileNav = false" href="{{ route('about') }}" class="py-2.5 hover:text-amber-400 border-b border-[#222F3E]/50 flex items-center justify-between {{ request()->routeIs('about') ? 'text-amber-400 font-semibold' : '' }}">
                    <span>Tentang Kami</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a @click="mobileNav = false" href="{{ route('gallery') }}" class="py-2.5 hover:text-amber-400 border-b border-[#222F3E]/50 flex items-center justify-between {{ request()->routeIs('gallery') ? 'text-amber-400 font-semibold' : '' }}">
                    <span>Portofolio & Galeri</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
                <a @click="mobileNav = false" href="{{ route('faq') }}" class="py-2.5 hover:text-amber-400 border-b border-[#222F3E]/50 flex items-center justify-between {{ request()->routeIs('faq') ? 'text-amber-400 font-semibold' : '' }}">
                    <span>Pertanyaan Umum (FAQ)</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
            </nav>
            <div class="pt-2">
                <a @click="mobileNav = false" href="{{ route('bookings.general') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 rounded-lg bg-[#F59E0B] text-slate-950 font-heading font-semibold text-sm shadow-[0_0_16px_rgba(245,158,11,0.25)]">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    <span>Isi Formulir Booking</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Global Notification Banner -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 animate-fade-up">
            <div class="rounded-xl border border-emerald-500/30 bg-emerald-950/40 p-4 flex items-center gap-3 text-emerald-200 text-sm shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                <span class="material-symbols-outlined text-emerald-400">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 animate-fade-up">
            <div class="rounded-xl border border-rose-500/30 bg-rose-950/40 p-4 flex items-center gap-3 text-rose-200 text-sm shadow-[0_0_15px_rgba(244,63,94,0.15)]">
                <span class="material-symbols-outlined text-rose-400">error</span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content Slot with Page Transition Animation -->
    <main id="main-content" class="flex-grow page-enter-active">
        @yield('content')
    </main>

    <!-- Footer (Multi-page Links) -->
    <footer class="mt-24 border-t border-[#222F3E] bg-[#070A0F] text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <!-- Col 1: Brand & Tagline -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ \App\Models\BusinessSetting::current()->logoUrl() }}" alt="Logo INULIN" class="h-8 w-8 object-contain rounded p-0.5 bg-black/40 border border-[#222F3E]">
                        <span class="font-heading font-bold text-xl text-white tracking-tight">INULIN</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                        {{ \App\Models\BusinessSetting::current()->description ?: 'Laboratorium instalasi sistem operasi Windows & Linux terpercaya. Standar teknis pengerjaan transparan, driver resmi vendor, dan perlindungan partisi data pribadi.' }}
                    </p>
                    <div class="font-mono text-xs text-slate-500">
                        Workshop Jam Kerja: {{ substr(\App\Models\BusinessSetting::current()->opening_time, 0, 5) }} - {{ substr(\App\Models\BusinessSetting::current()->closing_time, 0, 5) }} WIB (Senin - Sabtu)
                    </div>
                </div>

                <!-- Col 2: Multi-Page Navigation -->
                <div>
                    <h3 class="text-xs font-mono uppercase tracking-wider text-slate-200 font-semibold mb-4">Navigasi Halaman</h3>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('services.index') }}" class="hover:text-amber-400 transition-colors">Katalog Layanan</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-amber-400 transition-colors">Cara Kerja & Protokol</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('gallery') }}" class="hover:text-amber-400 transition-colors">Portofolio & Galeri</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-amber-400 transition-colors">Pertanyaan Umum (FAQ)</a></li>
                        <li><a href="{{ route('bookings.general') }}" class="hover:text-amber-400 transition-colors">Formulir Booking</a></li>
                    </ul>
                </div>

                <!-- Col 3: Contact & Workshop -->
                <div>
                    <h3 class="text-xs font-mono uppercase tracking-wider text-slate-200 font-semibold mb-4">Kontak & Workshop</h3>
                    <div class="space-y-2.5 text-xs text-slate-400 leading-relaxed">
                        <p class="text-slate-300 font-medium">{{ \App\Models\BusinessSetting::current()->address ?: 'Jl. Kaliurang KM 5, Sleman, D.I. Yogyakarta' }}</p>
                        <div class="pt-1">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\BusinessSetting::current()->whatsapp ?: '6285165017620') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-amber-400 hover:text-amber-300 font-mono">
                                <span class="material-symbols-outlined text-[15px]">chat</span>
                                <span>+{{ \App\Models\BusinessSetting::current()->whatsapp ?: '6285165017620' }}</span>
                            </a>
                        </div>
                        <div>
                            <a href="mailto:{{ \App\Models\BusinessSetting::current()->email ?: 'halo@inulin.id' }}" class="text-slate-400 hover:text-slate-200">
                                {{ \App\Models\BusinessSetting::current()->email ?: 'halo@inulin.id' }}
                            </a>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('admin.login') }}" class="text-[11px] font-mono text-slate-600 hover:text-slate-400 transition-colors">
                                Portal Akses Teknisi &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-8 border-t border-[#222F3E]/60 flex flex-col sm:flex-row items-center justify-between gap-4 font-mono text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} INULIN. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex items-center gap-6">
                    <span class="inline-flex items-center gap-1.5 text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Lab Sistem Operasi Aktif
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Page Transition & Scroll Reveal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const main = document.getElementById('main-content');
            const progressBar = document.getElementById('page-progress-bar');

            // 1. Initial Page Load Animation
            if (progressBar) {
                progressBar.style.width = '35%';
                setTimeout(() => {
                    progressBar.style.width = '100%';
                    setTimeout(() => {
                        progressBar.style.opacity = '0';
                        setTimeout(() => {
                            progressBar.style.width = '0%';
                        }, 300);
                    }, 200);
                }, 100);
            }

            // 2. Smooth Navigation Page Transition on Internal Links
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('mailto:') && !href.startsWith('tel:') && !href.startsWith('https://wa.me') && !link.target && !link.hasAttribute('download')) {
                    const currentOrigin = window.location.origin;
                    try {
                        const targetUrl = new URL(link.href, currentOrigin);
                        if (targetUrl.origin === currentOrigin && targetUrl.pathname !== window.location.pathname) {
                            link.addEventListener('click', (e) => {
                                if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
                                e.preventDefault();
                                
                                if (progressBar) {
                                    progressBar.style.opacity = '1';
                                    progressBar.style.width = '65%';
                                }
                                if (main) {
                                    main.classList.remove('page-enter-active');
                                    main.classList.add('page-exit-active');
                                }
                                setTimeout(() => {
                                    window.location.href = href;
                                }, 180);
                            });
                        }
                    } catch (err) {}
                }
            });

            // 3. Scroll Reveal Observer with Staggering
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -40px 0px',
                threshold: 0.12
            };

            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry, idx) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('is-visible');
                        }, idx * 75);
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-on-scroll').forEach(el => {
                revealObserver.observe(el);
            });
        });
    </script>
</body>
</html>
