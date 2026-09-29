@extends('layouts.app', ['title' => 'Tentang Kami — INULIN Laboratorium Sistem Operasi'])

@section('content')
<div class="relative overflow-hidden pt-12 pb-24">
    <!-- Ambient Background Gradients -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-amber-500/10 via-transparent to-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute top-40 right-10 w-72 h-72 bg-emerald-500/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb Header -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-8 animate-fade-up">
            <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">INULIN</a>
            <span class="text-slate-600">/</span>
            <span class="text-amber-400">Tentang Kami</span>
        </nav>

        <!-- Hero Section with Lab Photo Element -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center mb-16 animate-fade-up delay-100">
            <div class="lg:col-span-7 space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-950/60 border border-amber-800/40 text-amber-400 text-xs font-mono font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>{{ ($introSection && $introSection->badge) ? $introSection->badge : 'FILOSOFI & PROFIL LABORATORIUM' }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-bold text-white tracking-tight leading-tight">
                    {{ ($introSection && $introSection->title) ? $introSection->title : 'Menghadirkan Standar Baru untuk Instalasi Sistem Operasi.' }}
                </h1>
                <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-sans">
                    {{ ($introSection && $introSection->subtitle) ? $introSection->subtitle : 'INULIN berawal dari keresahan terhadap maraknya jasa instal ulang komputer abal-abal yang memasang Windows modifikasi penuh bloatware, memakai crack activator yang mengandung Trojan, serta sembrono memformat seluruh harddisk pelanggan.' }}
                </p>
                <p class="text-sm sm:text-base text-slate-400 leading-relaxed font-sans">
                    {{ ($introSection && $introSection->content) ? $introSection->content : 'Kami hadir sebagai laboratorium spesialis instalasi OS modern yang mengedepankan ketelitian teknis, isolasi keselamatan data partisi, dan kejujuran harga.' }}
                </p>
            </div>
            <div class="lg:col-span-5">
                <div class="rounded-2xl overflow-hidden border border-[#222F3E] bg-[#070A0F] shadow-2xl relative group">
                    <img src="{{ ($introSection && $introSection->imageUrl()) ? $introSection->imageUrl() : asset('images/hero/slide-workbench.jpg') }}" alt="Meja Kerja Laboratorium INULIN" class="w-full aspect-[4/3] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#070A0F]/80 via-transparent to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-3 left-4 right-4 text-[11px] font-mono text-amber-300 backdrop-blur-md bg-black/60 px-3 py-1.5 rounded-lg border border-white/10 flex items-center justify-between">
                        <span>Lab Workstation ESD-Safe</span>
                        <span class="text-emerald-400 font-bold">Terverifikasi SOP</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Highlight Bar -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-20 animate-fade-up delay-200">
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] relative overflow-hidden tech-card">
                <div class="text-2xl sm:text-3xl font-heading font-bold text-amber-400 mb-1">100%</div>
                <div class="text-xs font-mono text-slate-400">Master ISO Resmi Murni</div>
                <p class="text-[11px] text-slate-500 mt-2">Bebas modifikasi & tanpa backdoor berbahaya</p>
            </div>
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] relative overflow-hidden tech-card">
                <div class="text-2xl sm:text-3xl font-heading font-bold text-emerald-400 mb-1">0%</div>
                <div class="text-xs font-mono text-slate-400">Toleransi Partisi Ceroboh</div>
                <p class="text-[11px] text-slate-500 mt-2">Verifikasi ganda label volume sebelum format C:\</p>
            </div>
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] relative overflow-hidden tech-card">
                <div class="text-2xl sm:text-3xl font-heading font-bold text-amber-300 mb-1">14 Hari</div>
                <div class="text-xs font-mono text-slate-400">Garansi Purnajual Penuh</div>
                <p class="text-[11px] text-slate-500 mt-2">Dukungan teknis jika terjadi kendala driver atau boot</p>
            </div>
            <div class="p-6 rounded-xl bg-[#121822] border border-[#222F3E] relative overflow-hidden tech-card">
                <div class="text-2xl sm:text-3xl font-heading font-bold text-white mb-1">60 Menit</div>
                <div class="text-xs font-mono text-slate-400">Rata-rata Durasi Instalasi</div>
                <p class="text-[11px] text-slate-500 mt-2">Menggunakan USB 3.2 NVMe Master Media</p>
            </div>
        </div>

        <!-- 4 Core Pillars Section (Database-Driven) -->
        <div class="mb-24 reveal-on-scroll">
            <div class="mb-10 text-center max-w-2xl mx-auto">
                <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">STANDAR OPERASIONAL</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-bold text-white mt-2">Pilar Integritas INULIN</h2>
                <p class="text-sm text-slate-400 mt-2">Setiap unit laptop atau PC yang masuk ke meja kerja kami diperlakukan dengan standar teknisi profesional.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($pillars as $p)
                    <div class="p-8 rounded-2xl bg-[#121822] border border-[#222F3E] relative group hover:border-amber-500/40 transition-all tech-card">
                        <div class="w-12 h-12 rounded-xl bg-amber-950/80 border border-amber-800/40 text-amber-400 flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-2xl">{{ $p->icon ?: 'shield' }}</span>
                        </div>
                        <h3 class="text-xl font-heading font-bold text-white mb-2">{{ $p->title }}</h3>
                        @if($p->subtitle)
                            <span class="text-xs font-mono text-amber-400 block mb-3">{{ $p->subtitle }}</span>
                        @endif
                        <p class="text-sm text-slate-300 leading-relaxed font-sans mb-4">
                            {{ $p->description }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-2 p-8 rounded-xl bg-[#121822] border border-[#222F3E] text-center text-slate-400 text-xs font-mono">
                        Belum ada pilar operasional yang dikonfigurasi.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Laboratory Equipment & Toolkit (Database-Driven) -->
        <div class="mb-24 p-8 sm:p-12 rounded-3xl bg-[#0F141D] border border-[#222F3E] relative overflow-hidden reveal-on-scroll">
            <div class="max-w-2xl mb-10">
                <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">LABORATORY SPECIFICATIONS</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-bold text-white mt-2">Toolkit & Fasilitas Laboratorium</h2>
                <p class="text-sm text-slate-400 mt-2">Kami berinvestasi pada peralatan berkualitas agar perangkat Anda aman dari risiko lonjakan listrik statis dan kerusakan fisik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($equipments as $index => $eq)
                    <div class="p-5 rounded-xl bg-[#121822] border border-[#222F3E]/80">
                        <div class="text-amber-400 text-sm font-mono font-semibold mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">{{ $eq->icon ?: 'build' }}</span>
                            <span>0{{ $index + 1 }}. {{ $eq->title }}</span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed mb-3">
                            {{ $eq->description }}
                        </p>
                        @if($eq->subtitle)
                            <span class="text-[11px] font-mono text-slate-500">{{ $eq->subtitle }}</span>
                        @endif
                    </div>
                @empty
                    <div class="col-span-3 p-8 rounded-xl bg-[#121822] border border-[#222F3E] text-center text-slate-400 text-xs font-mono">
                        Belum ada peralatan fasilitas yang dikonfigurasi.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-amber-950/60 via-[#121822] to-amber-950/60 border border-amber-500/30 text-center relative overflow-hidden reveal-on-scroll">
            <h3 class="text-2xl sm:text-3xl font-heading font-bold text-white mb-4">
                Siap Mengembalikan Performa Laptop atau PC Anda?
            </h3>
            <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto mb-8 font-sans">
                Pilih paket layanan yang Anda butuhkan, tentukan jadwal kedatangan, dan nikmati sistem operasi yang kembali cepat, bersih, dan stabil.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('services.index') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-semibold text-sm transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)]">
                    Lihat Katalog Layanan
                </a>
                <a href="{{ route('bookings.general') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-[#18212E] hover:bg-[#222F3E] text-white border border-[#222F3E] font-heading font-semibold text-sm transition-all">
                    Langsung Isi Formulir Booking &rarr;
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
