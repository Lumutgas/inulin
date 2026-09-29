@extends('layouts.app')

@section('content')
<div class="space-y-20 lg:space-y-28" x-data="{
    reviewModal: false,
    ratingVal: 5,
    ratingHover: 5,
    ratingLabel() {
        const labels = {
            1: '⭐ (1.0) Perlu Perbaikan',
            2: '⭐⭐ (2.0) Kurang Memuaskan',
            3: '⭐⭐⭐ (3.0) Cukup Baik & Normal',
            4: '⭐⭐⭐⭐ (4.0) Sangat Bagus & Cepat',
            5: '⭐⭐⭐⭐⭐ (5.0) Sempurna & Sangat Direkomendasikan!'
        };
        return labels[this.ratingHover || this.ratingVal] || '';
    }
}">

    <!-- Success Flash Alert -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mb-12 pt-6">
            <div class="p-4 rounded-xl bg-emerald-950/70 border border-emerald-500/50 flex items-center justify-between text-xs text-emerald-300 animate-fade-up shadow-lg">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-emerald-400 text-lg">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- =========================================================================
     * HERO SECTION
     * ========================================================================= -->
    <section class="relative overflow-hidden pt-12 pb-16 lg:py-20 border-b border-[#222F3E]">
        <!-- Ambient Glow -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[350px] bg-amber-500/10 blur-[130px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Headline & Value Prop -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-[#18212E] border border-amber-800/40 text-amber-400">
                        <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                        <span class="font-mono text-xs tracking-wider uppercase font-semibold">{{ ($heroHeadline && $heroHeadline->badge) ? $heroHeadline->badge : 'Solusi Cepat & Terpercaya Laptop / PC' }}</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-[1.1]">
                        @if(isset($heroHeadline) && $heroHeadline->title)
                            {!! nl2br(e($heroHeadline->title)) !!}
                        @else
                            PC lemot? <br>
                            <span class="text-[#F59E0B]">Instal Ulang Tanpa Ribet.</span>
                        @endif
                    </h1>

                    <p class="text-base sm:text-lg text-slate-400 max-w-2xl leading-relaxed">
                        {{ ($heroHeadline && $heroHeadline->subtitle) ? $heroHeadline->subtitle : 'Jasa instal ulang sistem operasi Windows dan Linux untuk laptop dan PC. Proses jelas, partisi data aman terisolasi, driver resmi pabrikan, dan dilindungi garansi 14 hari penuh.' }}
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('bookings.general') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-semibold text-sm tracking-wide transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_25px_rgba(245,158,11,0.5)]">
                            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                            <span>Booking Sekarang</span>
                        </a>

                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp ?: '6285165017620') }}?text={{ rawurlencode('Halo INULIN, saya ingin konsultasi mengenai servis komputer saya.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-lg bg-[#18212E] hover:bg-[#1E293B] text-slate-200 border border-[#222F3E] font-heading font-medium text-sm transition-all">
                            <span class="material-symbols-outlined text-[20px] text-amber-400">chat</span>
                            <span>Chat WhatsApp</span>
                        </a>
                    </div>

                    <!-- 4 Trust Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 border-t border-[#222F3E]/60">
                        <div class="p-3.5 rounded-lg bg-[#121822] border border-[#222F3E] flex flex-col">
                            <span class="font-heading font-bold text-lg text-amber-400">14 Hari</span>
                            <span class="font-mono text-[11px] text-slate-400 mt-0.5">Garansi Resmi Lab</span>
                        </div>
                        <div class="p-3.5 rounded-lg bg-[#121822] border border-[#222F3E] flex flex-col">
                            <span class="font-heading font-bold text-lg text-emerald-400">100% Aman</span>
                            <span class="font-mono text-[11px] text-slate-400 mt-0.5">Partisi Data D/E Terisolasi</span>
                        </div>
                        <div class="p-3.5 rounded-lg bg-[#121822] border border-[#222F3E] flex flex-col">
                            <span class="font-heading font-bold text-lg text-white">Zero Bloat</span>
                            <span class="font-mono text-[11px] text-slate-400 mt-0.5">Bebas Aplikasi Iklan</span>
                        </div>
                        <div class="p-3.5 rounded-lg bg-[#121822] border border-[#222F3E] flex flex-col">
                            <span class="font-heading font-bold text-lg text-amber-400">60 - 90m</span>
                            <span class="font-mono text-[11px] text-slate-400 mt-0.5">Estimasi Pengerjaan</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Interactive Terminal Simulator -->
                @php
                    $firstWin = $services->firstWhere('slug', 'instal-ulang-windows') ?? $services->first();
                    $firstLin = $services->firstWhere('slug', 'instal-linux-dual-boot') ?? $services->skip(1)->first() ?? $services->first();
                @endphp
                <div class="lg:col-span-5" x-data="{
                    selectedProfile: 'win',
                    winPrice: 'Rp{{ number_format($firstWin ? $firstWin->price : 50000, 0, ',', '.') }}',
                    linPrice: 'Rp{{ number_format($firstLin ? $firstLin->price : 65000, 0, ',', '.') }}',
                    winUrl: '{{ $firstWin ? route('bookings.create', $firstWin) : route('bookings.general') }}',
                    linUrl: '{{ $firstLin ? route('bookings.create', $firstLin) : route('bookings.general') }}'
                }">
                    <div class="rounded-xl bg-[#070A0F] border border-[#222F3E] p-5 sm:p-6 shadow-2xl relative">
                        <!-- Terminal Bar -->
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#222F3E]">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500/80"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500/80"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500/80"></span>
                                <span class="font-mono text-xs text-slate-400 ml-2">inulin-lab@terminal:~$</span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-emerald-950/60 text-emerald-400 font-mono text-[10px] font-bold uppercase tracking-wider border border-emerald-800/40">
                                Lab Ready
                            </span>
                        </div>

                        <p class="font-mono text-xs text-slate-400 mb-3">Pilih profil deployment sistem operasi:</p>

                        <!-- Option 1: Windows -->
                        <div @click="selectedProfile = 'win'" :class="selectedProfile === 'win' ? 'border-[#F59E0B] bg-amber-950/20' : 'border-[#222F3E] bg-[#121822] hover:border-slate-600'" class="cursor-pointer p-4 rounded-lg border transition-all flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-500/10 text-[#F59E0B] flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">desktop_windows</span>
                                </div>
                                <div>
                                    <h4 class="font-heading font-semibold text-sm text-white">Windows 11 / 10 Pro Clean</h4>
                                    <p class="font-mono text-xs text-slate-400">Master ISO Resmi • Driver Komplit</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-sm text-[#F59E0B]" x-text="winPrice"></span>
                                <span class="block font-mono text-[10px] text-emerald-400">Siap Pakai</span>
                            </div>
                        </div>

                        <!-- Option 2: Linux -->
                        <div @click="selectedProfile = 'lin'" :class="selectedProfile === 'lin' ? 'border-[#F59E0B] bg-amber-950/20' : 'border-[#222F3E] bg-[#121822] hover:border-slate-600'" class="cursor-pointer p-4 rounded-lg border transition-all flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">terminal</span>
                                </div>
                                <div>
                                    <h4 class="font-heading font-semibold text-sm text-white">Linux & Dual Boot</h4>
                                    <p class="font-mono text-xs text-slate-400">Ubuntu • Debian • Mint • Kali • Arch</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-sm text-amber-400" x-text="linPrice"></span>
                                <span class="block font-mono text-[10px] text-slate-400">GRUB Safe</span>
                            </div>
                        </div>

                        <!-- Live Diagnostic Console Output -->
                        <div class="p-3.5 rounded bg-[#0B0F17] border border-[#222F3E] font-mono text-[11px] text-slate-400 space-y-1 mb-4">
                            <div class="text-emerald-400 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[13px]">check</span>
                                <span>[OK] Storage Target: NVMe / SATA SSD (Partition Safe)</span>
                            </div>
                            <div class="text-slate-300 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[13px]">tune</span>
                                <span>[SYS] Partition Scheme: GPT / UEFI Mode</span>
                            </div>
                            <div class="text-amber-400 flex items-center gap-1.5 animate-pulse">
                                <span class="material-symbols-outlined text-[13px]">play_arrow</span>
                                <span x-show="selectedProfile === 'win'">[TARGET] Profil aktif: Windows Master ISO Resmi</span>
                                <span x-show="selectedProfile === 'lin'">[TARGET] Profil aktif: Linux Distro / Dual Boot GRUB</span>
                            </div>
                        </div>

                        <!-- Bottom Link Button -->
                        <div class="flex items-center justify-between pt-2 border-t border-[#222F3E]">
                            <span class="font-mono text-xs text-slate-400">Termasuk Driver & Software Dasar</span>
                            <a :href="selectedProfile === 'win' ? winUrl : linUrl" class="font-heading font-semibold text-xs text-[#F59E0B] hover:text-amber-300 flex items-center gap-1 transition-colors">
                                <span>Booking Preset Ini</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Auto-sliding Hero Banner Carousel (Managed via Admin) -->
            @if(isset($heroSlides) && $heroSlides->isNotEmpty())
                <div class="mt-12 rounded-2xl border border-[#222F3E] bg-[#070A0F] overflow-hidden shadow-2xl relative" x-data="{
                    activeSlide: 0,
                    slidesCount: {{ $heroSlides->count() }},
                    paused: false,
                    timer: null,
                    next() {
                        this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
                    },
                    goTo(idx) {
                        this.activeSlide = idx;
                    }
                }" x-init="
                    timer = setInterval(() => {
                        if (!paused) next();
                    }, 4500);
                " @mouseenter="paused = true" @mouseleave="paused = false">
                    
                    <!-- Slides Track -->
                    <div class="relative w-full h-[320px] sm:h-[390px] lg:h-[440px] overflow-hidden">
                        @foreach($heroSlides as $idx => $slide)
                            <div x-show="activeSlide === {{ $idx }}"
                                 x-transition:enter="transition ease-out duration-700 transform"
                                 x-transition:enter-start="opacity-0 scale-95 translate-x-12"
                                 x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-500 transform absolute inset-0"
                                 x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 scale-105 -translate-x-12"
                                 class="absolute inset-0 w-full h-full">
                                
                                <img src="{{ $slide->imageUrl() }}" alt="{{ $slide->title }}" class="w-full h-full object-cover select-none" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#070A0F] via-[#070A0F]/60 to-transparent"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-[#070A0F]/90 via-[#070A0F]/40 to-transparent"></div>

                                <div class="absolute inset-0 p-6 sm:p-10 lg:p-12 flex flex-col justify-end max-w-2xl">
                                    @if($slide->badge)
                                        <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-amber-950/80 border border-amber-500/50 text-amber-400 font-mono text-[11px] font-bold tracking-wider uppercase mb-3 backdrop-blur-md">
                                            <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                                            <span>{{ $slide->badge }}</span>
                                        </div>
                                    @endif
                                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-heading font-bold text-white tracking-tight leading-snug drop-shadow-md">
                                        {{ $slide->title }}
                                    </h3>
                                    @if($slide->subtitle)
                                        <p class="text-xs sm:text-sm text-slate-300 mt-2 font-sans leading-relaxed line-clamp-2 drop-shadow">
                                            {{ $slide->subtitle }}
                                        </p>
                                    @endif

                                    <div class="mt-4 flex items-center gap-3">
                                        <a href="{{ $slide->button_link ?: route('bookings.general') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-xs tracking-wide shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all">
                                            <span>{{ $slide->button_text ?: 'Pesan Layanan' }}</span>
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </a>
                                        <span class="font-mono text-[11px] text-slate-400 hidden sm:inline-block">Auto-Slide 4.5s • Pause saat kursor disentuh</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Slider Controls & Dots -->
                    <div class="absolute top-4 right-4 flex items-center gap-2 z-20">
                        <button type="button" @click="prev()" aria-label="Slide sebelumnya" class="w-8 h-8 rounded-lg bg-black/60 hover:bg-black/90 text-white flex items-center justify-center border border-white/10 transition-colors backdrop-blur">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </button>
                        <button type="button" @click="next()" aria-label="Slide berikutnya" class="w-8 h-8 rounded-lg bg-black/60 hover:bg-black/90 text-white flex items-center justify-center border border-white/10 transition-colors backdrop-blur">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </button>
                    </div>

                    <div class="absolute bottom-4 right-6 flex items-center gap-2 z-20">
                        <template x-for="(slide, idx) in slidesCount" :key="idx">
                            <button type="button" @click="goTo(idx)" :aria-label="'Buka slide ' + (idx + 1)" class="h-2 rounded-full transition-all duration-300" :class="activeSlide === idx ? 'w-7 bg-[#F59E0B] shadow-[0_0_8px_rgba(245,158,11,0.8)]' : 'w-2 bg-white/30 hover:bg-white/60'"></button>
                        </template>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- =========================================================================
     * PC LEMOT SHOWCASE & DIAGNOSTIK MASALAH (DATABASE-DRIVEN & EDITABLE)
     * ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">
        <div class="rounded-3xl bg-gradient-to-b from-[#121822] to-[#0A0E17] border border-[#222F3E] p-6 sm:p-10 lg:p-12 overflow-hidden shadow-2xl relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left: Visual Comparison Photo Element -->
                <div class="lg:col-span-6 relative group">
                    <div class="relative rounded-2xl overflow-hidden border border-[#222F3E] shadow-2xl bg-[#070A0F]">
                        <img src="{{ ($pcLemotSection && $pcLemotSection->imageUrl()) ? $pcLemotSection->imageUrl() : asset('images/pclemot-showcase.jpg') }}" alt="PC Lemot vs Clean Install INULIN" class="w-full aspect-[16/10] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#070A0F]/80 via-transparent to-transparent pointer-events-none"></div>
                        <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between text-[11px] font-mono text-slate-300 backdrop-blur-md bg-black/50 px-3 py-1.5 rounded-lg border border-white/10">
                            <span class="text-rose-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> Laptop Lemot & Bloatware</span>
                            <span class="text-amber-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> INULIN Fast Boot & Fresh OS</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Explanatory Diagnostic Copy (From Database) -->
                <div class="lg:col-span-6 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 font-mono text-xs font-semibold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[16px]">speed</span>
                        <span>{{ ($pcLemotSection && $pcLemotSection->badge) ? $pcLemotSection->badge : 'DIAGNOSTIK MASALAH UTAMA' }}</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-bold text-white tracking-tight leading-tight">
                        {{ ($pcLemotSection && $pcLemotSection->title) ? $pcLemotSection->title : 'Mengapa PC & Laptop Anda Menjadi Sangat Lemot?' }}
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-sans">
                        {{ ($pcLemotSection && $pcLemotSection->subtitle) ? $pcLemotSection->subtitle : 'Penumpukan bloatware bawaan pabrik, fragmented registry, malware berkedok crack, dan sistem operasi usang membebani kerja prosesor serta RAM.' }}
                    </p>

                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed font-sans">
                        {{ ($pcLemotSection && $pcLemotSection->content) ? $pcLemotSection->content : 'Clean install master murni di INULIN menghapus tuntas seluruh beban tak terlihat tersebut tanpa mengorbankan partisi dokumen pribadi Anda (D:\ / E:\). Laptop kembali segar dan ringan seperti baru keluar dari kardus.' }}
                    </p>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3 rounded-xl bg-[#0B0F17] border border-[#222F3E]">
                            <span class="font-mono font-bold text-sm text-rose-400 block mb-0.5">&minus; 70% Beban RAM</span>
                            <span class="text-[11px] text-slate-400">Tanpa aplikasi trialware & iklan</span>
                        </div>
                        <div class="p-3 rounded-xl bg-[#0B0F17] border border-[#222F3E]">
                            <span class="font-mono font-bold text-sm text-[#F59E0B] block mb-0.5">Boot 8 Detik</span>
                            <span class="text-[11px] text-slate-400">UEFI GPT Mode NVMe murni</span>
                        </div>
                    </div>

                    <div class="pt-3 flex flex-wrap items-center gap-3">
                        <a href="{{ route('bookings.general') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-xs tracking-wide shadow transition-all">
                            <span>Atasi Laptop Lemot Sekarang</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('how-it-works') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-[#18212E] hover:bg-[#222F3E] text-slate-300 font-heading font-medium text-xs border border-[#222F3E] transition-all">
                            <span>Pelajari Alur Servis</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- =========================================================================
     * SERVICES CATALOG BENTO GRID (DATABASE-DRIVEN)
     * ========================================================================= -->
    <section id="layanan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 pb-4 border-b border-[#222F3E]">
            <div>
                <span class="font-mono text-xs uppercase tracking-widest text-amber-400 font-semibold">Katalog Layanan Pilihan</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mt-1">Paket Instalasi & Servis Komputer</h2>
            </div>
            <div class="flex flex-col items-start md:items-end gap-2">
                <p class="text-slate-400 text-sm max-w-md">
                    Seluruh tarif di bawah diambil langsung dari database sistem secara transparan.
                </p>
                <a href="{{ route('services.index') }}" class="font-mono text-xs text-amber-400 hover:text-amber-300 flex items-center gap-1 transition-colors">
                    <span>Lihat Semua 6 Layanan & Spesifikasi</span>
                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                </a>
            </div>
        </div>

        @if($services->isEmpty())
            <div class="rounded-xl border border-[#222F3E] bg-[#121822] p-12 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl text-slate-500 mb-2">inventory_2</span>
                <p class="text-base font-semibold text-slate-300">Belum ada katalog layanan aktif saat ini.</p>
                <p class="text-sm mt-1">Silakan tambahkan layanan melalui dashboard admin.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $index => $service)
                    <div class="rounded-xl bg-[#121822] border border-[#222F3E] p-6 sm:p-7 flex flex-col justify-between hover:border-amber-500/50 hover:bg-[#161F2C] transition-all duration-200 group">
                        <div>
                            <!-- Header / Badge -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-2.5 py-1 rounded bg-[#18212E] border border-[#222F3E] font-mono text-[11px] font-semibold text-amber-400 uppercase tracking-wider">
                                    {{ $index === 0 ? 'Paling Populer' : ($index === 1 ? 'Dev Choice' : 'Paket Lab') }}
                                </span>
                                <span class="material-symbols-outlined text-slate-400 group-hover:text-amber-400 transition-colors">
                                    {{ $index === 0 ? 'desktop_windows' : ($index === 1 ? 'terminal' : ($index === 3 ? 'mode_fan' : 'build')) }}
                                </span>
                            </div>

                            <h3 class="font-heading font-bold text-xl text-white group-hover:text-amber-300 transition-colors">
                                {{ $service->name }}
                            </h3>

                            <p class="text-xs sm:text-sm text-slate-400 mt-2.5 leading-relaxed line-clamp-3">
                                {{ $service->description }}
                            </p>

                            <!-- Inclusions List -->
                            <div class="mt-5 pt-4 border-t border-[#222F3E]/60">
                                <span class="font-mono text-[11px] text-slate-400 uppercase tracking-wider font-semibold block mb-2">Yang Didapatkan:</span>
                                <ul class="space-y-1.5 text-xs text-slate-300">
                                    @foreach(array_slice($service->included_items ?? [], 0, 3) as $item)
                                        <li class="flex items-start gap-2">
                                            <span class="material-symbols-outlined text-amber-400 text-[16px] shrink-0 mt-0.5">check_circle</span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Footer / Price from DB & CTA -->
                        <div class="mt-7 pt-4 border-t border-[#222F3E] flex items-center justify-between gap-3">
                            <div>
                                <span class="font-mono text-[10px] text-slate-500 uppercase block">Tarif Layanan</span>
                                <span class="font-mono font-bold text-xl text-white">
                                    Rp{{ number_format($service->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('services.show', $service) }}" class="px-3 py-2 rounded-lg bg-[#18212E] hover:bg-[#1E293B] text-slate-300 hover:text-white border border-[#222F3E] text-xs font-semibold font-heading transition-colors" title="Lihat detail spesifikasi">
                                    Detail
                                </a>
                                <a href="{{ route('bookings.create', $service) }}" class="px-3.5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 text-xs font-semibold font-heading transition-colors shadow-sm">
                                    Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- =========================================================================
     * STANDAR KEAMANAN & KUALITAS (4 PILLARS)
     * ========================================================================= -->
    <section id="keamanan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="font-mono text-xs uppercase tracking-widest text-amber-400 font-semibold">Standar Operasional Lab</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-white mt-1">Mengapa Memilih INULIN?</h2>
            <p class="text-slate-400 text-sm mt-2">
                Kami memperlakukan laptop dan file kerja Anda dengan standar kehati-hatian teknis tinggi tanpa kompromi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pillar 1 -->
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] flex flex-col gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">shield_with_heart</span>
                </div>
                <h3 class="font-heading font-semibold text-lg text-white">Data Safety First</h3>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Prioritas mutlak partisi D, E, dan folder kerja Anda tidak tersentuh. Kami mengonfirmasi mapping drive di depan Anda sebelum proses format dimulai.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] flex flex-col gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">verified_user</span>
                </div>
                <h3 class="font-heading font-semibold text-lg text-white">Driver Resmi Vendor</h3>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Tidak menggunakan driver asal-asalan. Kami mengunduh driver resmi langsung dari manufaktur laptop (ASUS, Lenovo, HP, Dell, Acer, MSI, Apple).
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] flex flex-col gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">speed</span>
                </div>
                <h3 class="font-heading font-semibold text-lg text-white">100% Tanpa Bloatware</h3>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Sistem operasi bersih tanpa software iklan tersembunyi yang membebani RAM. Laptop terasa ringan, booting cepat, dan hemat baterai.
                </p>
            </div>

            <!-- Pillar 4 -->
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] flex flex-col gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">receipt_long</span>
                </div>
                <h3 class="font-heading font-semibold text-lg text-white">Transparansi Biaya</h3>
                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                    Satu layanan, satu harga jelas sesuai yang tercantum di web. Pembayaran dilakukan secara tunai atau QRIS setelah pengerjaan selesai dicek bersama di lab.
                </p>
            </div>
        </div>
    </section>

    <!-- =========================================================================
     * CARA KERJA LAB (WORKFLOW)
     * ========================================================================= -->
    <section id="cara-kerja" class="border-y border-[#222F3E] bg-[#070A0F] py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="font-mono text-xs uppercase tracking-widest text-amber-400 font-semibold">Alur Pengerjaan Sederhana</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mt-1">Cara Kerja INULIN</h2>
                <p class="text-slate-400 text-sm mt-2">
                    Empat langkah mudah dari booking online hingga laptop kembali siap digunakan.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="p-6 rounded-xl bg-[#0F141D] border border-[#222F3E] flex flex-col justify-between">
                    <div>
                        <span class="font-mono text-xs font-bold text-[#F59E0B] px-2.5 py-1 rounded bg-amber-950/50 border border-amber-800/40">
                            LANGKAH 01
                        </span>
                        <h3 class="font-heading font-bold text-lg text-white mt-4">Pilih Layanan & Jadwal</h3>
                        <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                            Tentukan sistem operasi (Windows / Linux), jenis perangkat, serta tanggal dan jam kedatangan yang Anda inginkan.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-xl bg-[#0F141D] border border-[#222F3E] flex flex-col justify-between">
                    <div>
                        <span class="font-mono text-xs font-bold text-amber-400 px-2.5 py-1 rounded bg-amber-950/50 border border-amber-800/40">
                            LANGKAH 02
                        </span>
                        <h3 class="font-heading font-bold text-lg text-white mt-4">Kirim Booking ke WhatsApp</h3>
                        <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                            Sistem menyusun ringkasan rapi. Cukup klik tombol WhatsApp dan tekan kirim agar teknisi kami mengonfirmasi slot kedatangan Anda.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-xl bg-[#0F141D] border border-[#222F3E] flex flex-col justify-between">
                    <div>
                        <span class="font-mono text-xs font-bold text-amber-400 px-2.5 py-1 rounded bg-amber-950/50 border border-amber-800/40">
                            LANGKAH 03
                        </span>
                        <h3 class="font-heading font-bold text-lg text-white mt-4">Drop Unit di Workshop</h3>
                        <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                            Bawa laptop atau PC ke workshop kami. Kami memverifikasi partisi data aman bersama Anda sebelum pengerjaan dimulai.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="p-6 rounded-xl bg-[#0F141D] border border-[#222F3E] flex flex-col justify-between">
                    <div>
                        <span class="font-mono text-xs font-bold text-emerald-400 px-2.5 py-1 rounded bg-emerald-950/50 border border-emerald-800/40">
                            LANGKAH 04
                        </span>
                        <h3 class="font-heading font-bold text-lg text-white mt-4">Selesai & Garansi 14 Hari</h3>
                        <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                            Cek seluruh fungsi perangkat dan driver di lab. Bayar setelah puas, dan Anda otomatis dilindungi garansi 14 hari penuh.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('how-it-works') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#121822] hover:bg-[#18212E] text-amber-400 border border-amber-800/40 text-xs font-mono transition-all hover:border-amber-400/50">
                    <span class="material-symbols-outlined text-[16px]">menu_book</span>
                    <span>Pelajari 6 Protokol Teknis & Checklist Sebelum Datang &rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- =========================================================================
     * PENGALAMAN PELANGGAN & DOKUMENTASI GALERI (DATABASE-DRIVEN)
     * ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- Left: Testimonials -->
            <div>
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="font-mono text-xs uppercase tracking-widest text-amber-400 font-semibold">Ulasan Pelanggan</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white mt-1">Pengalaman di INULIN</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="reviewModal = true" class="px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-heading font-bold text-xs flex items-center gap-1.5 shadow transition-all">
                            <span class="material-symbols-outlined text-[15px] star-filled" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span>Tulis Ulasan</span>
                        </button>
                        <a href="{{ route('gallery') }}" class="text-xs font-mono text-amber-400 hover:text-amber-300">Semua &rarr;</a>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($testimonials->take(3) as $item)
                        <div class="p-5 rounded-xl bg-[#121822] border border-[#222F3E] hover:border-amber-500/40 transition-all">
                            <div class="flex items-center gap-1 text-amber-400 mb-2">
                                @php $rating = (int) ($item->rating ?? 5); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <span class="material-symbols-outlined text-[16px] text-amber-400 star-filled" style="font-variation-settings: 'FILL' 1;">star</span>
                                    @else
                                        <span class="material-symbols-outlined text-[16px] text-slate-600 star-empty" style="font-variation-settings: 'FILL' 0;">star</span>
                                    @endif
                                @endfor
                                <span class="font-mono text-xs text-amber-400 font-bold ml-1">({{ $rating }}.0)</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed italic">
                                “{{ $item->quote }}”
                            </p>
                            <div class="mt-3 pt-3 border-t border-[#222F3E]/60 flex items-center justify-between">
                                <span class="font-heading font-semibold text-xs text-white">{{ $item->name }}</span>
                                <span class="font-mono text-[11px] text-amber-400">{{ $item->role ?: 'Terverifikasi Lab' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 rounded-xl bg-[#121822] border border-[#222F3E] text-center text-slate-500 text-sm">
                            Belum ada ulasan yang diterbitkan.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Gallery -->
            <div>
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <span class="font-mono text-xs uppercase tracking-widest text-amber-400 font-semibold">Dokumentasi Lab</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white mt-1">Studi Kasus Lab</h2>
                    </div>
                    <a href="{{ route('gallery') }}" class="text-xs font-mono text-amber-400 hover:text-amber-300">Buka Galeri &rarr;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($galleries->take(4) as $item)
                        <div class="rounded-xl overflow-hidden bg-[#121822] border border-[#222F3E] group hover:border-amber-500/40 transition-all">
                            <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" class="aspect-video w-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="p-3 bg-[#0F141D]">
                                <h4 class="font-heading font-semibold text-xs text-white truncate">{{ $item->title }}</h4>
                                @if($item->description)
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-8 rounded-xl bg-[#121822] border border-[#222F3E] text-center text-slate-500 text-sm">
                            Foto dokumentasi pengerjaan akan segera diperbarui melalui dashboard.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    <!-- =========================================================================
     * PERTANYAAN UMUM (FAQ) (DATABASE-DRIVEN)
     * ========================================================================= -->
    <section id="faq" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 reveal-on-scroll">
        <div class="text-center mb-10">
            <span class="font-mono text-xs uppercase tracking-widest text-amber-400 font-semibold">Bantuan & Informasi</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-white mt-1">Pertanyaan Umum (FAQ)</h2>
            <p class="text-slate-400 text-sm mt-2">Jawaban seputar keamanan data, estimasi pengerjaan, dan lisensi.</p>
        </div>

        <div class="space-y-3" x-data="{ activeAccordion: null }">
            @forelse($faqs->take(4) as $index => $faq)
                <div class="rounded-xl bg-[#121822] border border-[#222F3E] overflow-hidden transition-colors">
                    <button type="button" @click="activeAccordion = (activeAccordion === {{ $index }} ? null : {{ $index }})" class="w-full p-5 text-left flex items-center justify-between gap-4 font-heading font-semibold text-sm sm:text-base text-white hover:text-amber-400 transition-colors">
                        <span>{{ $faq->question }}</span>
                        <span class="material-symbols-outlined text-slate-400 text-[20px] transition-transform duration-200" :class="activeAccordion === {{ $index }} ? 'rotate-180 text-amber-400' : ''">expand_more</span>
                    </button>
                    <div x-show="activeAccordion === {{ $index }}" x-cloak x-collapse class="px-5 pb-5 text-xs sm:text-sm text-slate-400 leading-relaxed border-t border-[#222F3E]/60 pt-3">
                        {{ $faq->answer }}
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-xl bg-[#121822] border border-[#222F3E] text-center text-slate-500 text-sm">
                    Belum ada pertanyaan umum yang diterbitkan.
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('faq') }}" class="inline-flex items-center gap-1.5 text-xs font-mono text-amber-400 hover:text-amber-300 transition-colors">
                <span>Lihat Seluruh Pertanyaan & Buka Pencarian FAQ</span>
                <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
            </a>
        </div>
    </section>

    <!-- =========================================================================
     * BOTTOM CONSULTATION BANNER
     * ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="rounded-2xl bg-gradient-to-r from-[#121822] via-[#161F2C] to-[#121822] border border-amber-800/40 p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <div class="space-y-2 max-w-xl text-center md:text-left">
                <span class="font-mono text-xs font-semibold text-amber-400 uppercase tracking-wider">Konsultasi Bebas Biaya</span>
                <h3 class="text-2xl sm:text-3xl font-bold text-white">Butuh Konsultasi Kondisi Laptop Anda?</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Belum yakin apakah laptop Anda lebih cocok memakai Windows 10 LTSC, Windows 11, atau distribusi Linux? Hubungi teknisi kami untuk rekomendasi terbaik.
                </p>
            </div>
            <div class="flex flex-wrap gap-4 shrink-0">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp ?: '6285165017620') }}?text={{ rawurlencode('Halo INULIN, saya mau tanya-tanya dulu tentang kondisi laptop saya sebelum booking.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-semibold text-sm transition-all shadow-[0_0_16px_rgba(245,158,11,0.25)]">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    <span>Chat Teknisi via WhatsApp</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Public Review Submission Modal -->
    <div x-show="reviewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in" @keydown.escape.window="reviewModal = false">
        <div class="relative w-full max-w-lg rounded-2xl bg-[#0F141D] border border-amber-800/40 shadow-2xl overflow-hidden animate-zoom-in" @click.outside="reviewModal = false">
            <div class="p-6 border-b border-[#222F3E] flex items-center justify-between">
                <div>
                    <span class="text-xs font-mono text-amber-400 font-semibold uppercase tracking-wider block">Beri Penilaian Layanan</span>
                    <h3 class="text-lg font-heading font-bold text-white mt-0.5">Tulis Ulasan & Rating Bintang</h3>
                </div>
                <button type="button" @click="reviewModal = false" class="w-8 h-8 rounded-lg bg-[#18212E] hover:bg-[#222F3E] text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <form method="POST" action="{{ route('reviews.store') }}" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <!-- Interactive Star Picker -->
                <div>
                    <label class="block font-mono text-slate-300 font-semibold mb-2">Berapa bintang untuk kepuasan layanan INULIN? *</label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3.5 rounded-xl bg-[#121822] border border-[#222F3E]">
                        <div class="flex items-center gap-1.5 cursor-pointer" @mouseleave="ratingHover = ratingVal">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                <button type="button" @mouseenter="ratingHover = star" @click="ratingVal = star; ratingHover = star" class="p-0.5 text-2xl transition-transform hover:scale-125 focus:outline-none">
                                    <span class="material-symbols-outlined text-[26px]" :class="(ratingHover || ratingVal) >= star ? 'text-amber-400 star-filled' : 'text-slate-600 star-empty'" :style="(ratingHover || ratingVal) >= star ? 'font-variation-settings: \'FILL\' 1;' : 'font-variation-settings: \'FILL\' 0;'">star</span>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="rating" :value="ratingVal">
                        <span class="font-mono text-xs font-bold text-amber-400" x-text="ratingLabel()"></span>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label class="block font-mono text-slate-300 font-semibold mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-lg bg-[#121822] border border-[#222F3E] text-white focus:border-[#F59E0B] focus:outline-none">
                </div>

                <!-- Role / Device -->
                <div>
                    <label class="block font-mono text-slate-300 font-semibold mb-1.5">Peran / Model Laptop atau PC</label>
                    <input type="text" name="role" placeholder="Contoh: Mahasiswa / ASUS TUF Gaming F15" class="w-full px-3.5 py-2.5 rounded-lg bg-[#121822] border border-[#222F3E] text-white focus:border-[#F59E0B] focus:outline-none">
                </div>

                <!-- Review Quote -->
                <div>
                    <label class="block font-mono text-slate-300 font-semibold mb-1.5">Ulasan & Pengalaman Servis *</label>
                    <textarea name="quote" rows="4" required minlength="10" placeholder="Ceritakan bagaimana performa laptop Anda setelah diinstal ulang di lab INULIN..." class="w-full px-3.5 py-2.5 rounded-lg bg-[#121822] border border-[#222F3E] text-white focus:border-[#F59E0B] focus:outline-none resize-none"></textarea>
                    <span class="text-[10px] text-slate-500 font-mono mt-1 block">Minimal 10 karakter. Ulasan Anda akan langsung tampil di halaman website.</span>
                </div>

                <div class="pt-3 border-t border-[#222F3E] flex items-center justify-end gap-3">
                    <button type="button" @click="reviewModal = false" class="px-4 py-2.5 rounded-lg bg-[#18212E] hover:bg-[#222F3E] text-slate-300 text-xs font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-heading font-bold text-xs tracking-wide shadow transition-all">
                        Kirim Ulasan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
