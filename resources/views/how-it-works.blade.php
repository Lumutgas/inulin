@extends('layouts.app', ['title' => 'Cara Kerja & Protokol Pengerjaan — INULIN'])

@section('content')
<div class="relative overflow-hidden pt-12 pb-24">
    <!-- Ambient Background Gradients -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-amber-500/10 via-transparent to-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute top-48 right-12 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-8 animate-fade-up">
            <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">INULIN</a>
            <span class="text-slate-600">/</span>
            <span class="text-amber-400">Cara Kerja & Protokol</span>
        </nav>

        <!-- Page Header & Photo Element -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center mb-16 animate-fade-up delay-100">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-950/60 border border-amber-800/40 text-amber-400 text-xs font-mono font-medium mb-4">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>{{ $introSection->badge ?? 'PROTOKOL TEKNIS SISTEMATIS' }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-bold text-white tracking-tight leading-tight mb-4">
                    {!! $introSection->title ?? 'Alur Pengerjaan <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-300">Laboratorium INULIN</span>' !!}
                </h1>
                <p class="text-base text-slate-300 font-sans leading-relaxed mb-4">
                    {{ $introSection->subtitle ?? 'Dari meja pemesanan online hingga serah terima perangkat, kami menerapkan SOP (Standard Operating Procedure) yang presisi untuk menjamin integritas data dan kestabilan sistem operasi laptop Anda.' }}
                </p>
                @if(!empty($introSection?->content))
                    <p class="text-sm text-slate-400 font-sans leading-relaxed">
                        {{ $introSection->content }}
                    </p>
                @endif
            </div>

            @if($introSection && $introSection->imageUrl())
                <div class="lg:col-span-5">
                    <div class="relative rounded-2xl overflow-hidden border border-amber-500/30 bg-[#0F141D] shadow-[0_0_30px_rgba(245,158,11,0.1)] group">
                        <img 
                            src="{{ $introSection->imageUrl() }}" 
                            alt="{{ $introSection->title }}" 
                            class="w-full aspect-[16/10] object-cover group-hover:scale-105 transition-transform duration-500" 
                            loading="lazy" 
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-5">
                            <span class="text-xs font-mono text-amber-400 font-semibold tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">memory</span>
                                WORKSHOP SOP & PROSEDUR
                            </span>
                            <span class="text-xs text-slate-300 mt-1">Uji kestabilan driver & proteksi partisi D:/E:</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Interactive Workflow Roadmap from Database -->
        <div class="mb-24 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($steps as $s)
                    <div class="p-6 sm:p-7 rounded-2xl bg-[#121822] border border-[#222F3E] relative flex flex-col justify-between tech-card reveal-on-scroll">
                        <div>
                            <!-- Header Number & Badge -->
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-amber-950/70 border border-amber-800/40 text-amber-400">
                                    LANGKAH {{ sprintf('%02d', $s->step_number) }}
                                </span>
                                <span class="text-[11px] font-mono text-slate-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-amber-400">timer</span>
                                    <span>{{ $s->duration }}</span>
                                </span>
                            </div>

                            @if($s->imageUrl())
                                <div class="mb-4 rounded-xl overflow-hidden border border-[#222F3E]">
                                    <img src="{{ $s->imageUrl() }}" alt="{{ $s->title }}" class="w-full h-32 object-cover" loading="lazy" />
                                </div>
                            @endif

                            <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-amber-400 mb-4">
                                <span class="material-symbols-outlined text-xl">{{ $s->icon ?: 'terminal' }}</span>
                            </div>

                            <h3 class="text-lg font-heading font-bold text-white mb-2">{{ $s->title }}</h3>
                            <p class="text-xs text-slate-300 leading-relaxed font-sans mb-4">{{ $s->description }}</p>
                        </div>

                        @if($s->note)
                            <div class="pt-4 border-t border-[#222F3E]/60 text-[11px] font-mono text-amber-400/90 flex items-start gap-1.5">
                                <span class="text-amber-400 font-bold">&bull;</span>
                                <span>{{ $s->note }}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-400 font-mono text-sm">
                        Belum ada langkah alur kerja yang ditambahkan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Customer Preparation Checklist Box -->
        <div class="p-8 sm:p-10 rounded-3xl bg-[#0F141D] border border-amber-600/30 mb-20 relative overflow-hidden reveal-on-scroll">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-amber-950/80 border border-amber-800/50 text-amber-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">fact_check</span>
                </div>
                <div>
                    <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">PERSIAPAN PELANGGAN</span>
                    <h2 class="text-xl sm:text-2xl font-heading font-bold text-white mt-1">Checklist Sebelum Datang ke Workshop</h2>
                    <p class="text-xs text-slate-400 mt-1">Demi kelancaran pengerjaan dan keamanan dokumen Anda, mohon perhatikan hal-hal berikut:</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-sans text-slate-300">
                <div class="p-4 rounded-xl bg-[#121822] border border-[#222F3E] flex items-start gap-3">
                    <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0 mt-0.5">check_box</span>
                    <div>
                        <strong class="text-white block mb-0.5">Amankan File di Drive C (Desktop, Downloads, Documents)</strong>
                        <span>File yang berada di folder sistem akan terhapus saat instalasi ulang. Pindahkan dokumen penting ke flashdisk, Google Drive, atau partisi D:\.</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#121822] border border-[#222F3E] flex items-start gap-3">
                    <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0 mt-0.5">check_box</span>
                    <div>
                        <strong class="text-white block mb-0.5">Bawa Charger / Adaptor Laptop Original</strong>
                        <span>Proses flashing OS dan kalibrasi driver membutuhkan suplai daya stabil agar laptop tidak mati mendadak di tengah proses instalasi.</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#121822] border border-[#222F3E] flex items-start gap-3">
                    <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0 mt-0.5">check_box</span>
                    <div>
                        <strong class="text-white block mb-0.5">Catat Akun Microsoft / Email Penting</strong>
                        <span>Jika Anda memiliki lisensi Windows digital atau akun Office 365, siapkan email dan password untuk aktivasi mandiri setelah pengerjaan.</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#121822] border border-[#222F3E] flex items-start gap-3">
                    <span class="material-symbols-outlined text-emerald-400 text-lg shrink-0 mt-0.5">check_box</span>
                    <div>
                        <strong class="text-white block mb-0.5">Informasikan Kendala Khusus Sejak Awal</strong>
                        <span>Misal: laptop sering restart saat panas, touchpad tidak responsif, atau butuh partisi khusus untuk programming & dual-boot.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="text-center p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-amber-950/60 via-[#121822] to-amber-950/60 border border-amber-500/30 reveal-on-scroll">
            <h3 class="text-2xl sm:text-3xl font-heading font-bold text-white mb-4">
                Siap Melakukan Reservasi Instal Ulang?
            </h3>
            <p class="text-slate-300 text-sm max-w-xl mx-auto mb-8 font-sans">
                Dapatkan slot pengerjaan terjadwal tanpa antre panjang. Teknisi kami langsung siap menangani unit Anda saat tiba.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('bookings.general') }}" class="px-8 py-3.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-sm transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)]">
                    Isi Formulir Booking Sekarang
                </a>
                <a href="{{ route('services.index') }}" class="px-6 py-3.5 rounded-xl bg-[#18212E] hover:bg-[#222F3E] text-white border border-[#222F3E] font-heading font-semibold text-sm transition-all">
                    Lihat Daftar Biaya Layanan
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
