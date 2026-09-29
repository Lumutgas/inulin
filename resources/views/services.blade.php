@extends('layouts.app', ['title' => 'Katalog Layanan & Spesifikasi — INULIN'])

@section('content')
<div class="relative overflow-hidden pt-12 pb-24" x-data="{ activeFilter: 'all', search: '' }">
    <!-- Ambient Glow Backgrounds -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-amber-500/10 via-transparent to-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute top-64 left-10 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-8 animate-fade-up">
            <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">INULIN</a>
            <span class="text-slate-600">/</span>
            <span class="text-amber-400">Katalog Layanan</span>
        </nav>

        <!-- Page Header -->
        <div class="max-w-3xl mb-12 animate-fade-up delay-100">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-950/60 border border-amber-800/40 text-amber-400 text-xs font-mono font-medium mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>KATALOG RESMI & TRANSPARAN</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-bold text-white tracking-tight leading-tight mb-4">
                Paket Layanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-300">Sistem Operasi & Hardware</span>
            </h1>
            <p class="text-base text-slate-300 font-sans leading-relaxed">
                Seluruh tarif di bawah ini terhubung langsung dengan basis data resmi kami. Bebas biaya tersembunyi, master ISO resmi original tanpa modifikasi berbahaya, dan garansi purnajual 14 hari.
            </p>
        </div>

        <!-- Filter & Search Controls -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10 p-2 sm:p-3 rounded-2xl bg-[#121822] border border-[#222F3E] animate-fade-up delay-150">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-1 overflow-x-auto w-full md:w-auto p-1 text-xs font-mono">
                <button type="button" @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
                    Semua ({{ $services->count() }})
                </button>
                <button type="button" @click="activeFilter = 'windows'" :class="activeFilter === 'windows' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
                    Windows
                </button>
                <button type="button" @click="activeFilter = 'linux'" :class="activeFilter === 'linux' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
                    Linux & Dual-Boot
                </button>
                <button type="button" @click="activeFilter = 'hardware'" :class="activeFilter === 'hardware' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap">
                    Maintenance & Hardware
                </button>
            </div>

            <!-- Live Search Input -->
            <div class="relative w-full md:w-72">
                <input type="text" x-model="search" placeholder="Cari nama layanan..." class="w-full bg-[#070A0F] border border-[#222F3E] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-xl pl-9 pr-4 py-2 text-xs font-mono text-white placeholder-slate-500 transition-colors">
                <span class="material-symbols-outlined text-[18px] text-slate-500 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-24">
            @foreach($services as $index => $s)
                @php
                    $isWindows = str_contains(strtolower($s->name), 'windows');
                    $isLinux = str_contains(strtolower($s->name), 'linux') || str_contains(strtolower($s->name), 'dual boot');
                    $isHardware = str_contains(strtolower($s->name), 'thermal') || str_contains(strtolower($s->name), 'malware') || str_contains(strtolower($s->name), 'upgrade') || str_contains(strtolower($s->name), 'diagnosis');
                    $categoryTag = $isWindows ? 'windows' : ($isLinux ? 'linux' : 'hardware');
                @endphp
                <div x-show="(activeFilter === 'all' || activeFilter === '{{ $categoryTag }}') && ('{{ strtolower($s->name) }}'.includes(search.toLowerCase()) || search === '')" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex flex-col justify-between rounded-2xl bg-[#121822] border border-[#222F3E] p-6 tech-card animate-fade-up delay-{{ min(($index + 1) * 100, 700) }} relative group">
                    
                    <div>
                        <!-- Header badge & Duration -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-semibold 
                                @if($isWindows) bg-amber-950/80 text-amber-400 border border-amber-800/40 
                                @elseif($isLinux) bg-emerald-950/80 text-emerald-400 border border-emerald-800/40 
                                @else bg-purple-950/80 text-purple-400 border border-purple-800/40 @endif">
                                @if($isWindows) WINDOWS CORE @elseif($isLinux) LINUX & OPEN-SOURCE @else HARDWARE & TWEAK @endif
                            </span>

                            <span class="flex items-center gap-1 text-[11px] font-mono text-slate-400">
                                <span class="material-symbols-outlined text-[15px] text-amber-400">schedule</span>
                                <span>~{{ $s->duration }} Menit</span>
                            </span>
                        </div>

                        <!-- Service Title -->
                        <h2 class="text-xl font-heading font-bold text-white mb-2 group-hover:text-amber-400 transition-colors">
                            {{ $s->name }}
                        </h2>

                        <!-- Description -->
                        <p class="text-xs text-slate-400 leading-relaxed font-sans mb-6 line-clamp-3">
                            {{ $s->description }}
                        </p>

                        <!-- Inclusions List Preview -->
                        <div class="space-y-2 mb-6 pt-4 border-t border-[#222F3E]/60">
                            <span class="text-[11px] font-mono text-slate-500 uppercase tracking-wider block">Spesifikasi Cakupan:</span>
                            @foreach(array_slice($s->included_items ?? [], 0, 4) as $item)
                                <div class="flex items-start gap-2 text-xs text-slate-300">
                                    <span class="material-symbols-outlined text-amber-400 text-[16px] shrink-0 mt-0.5">check_circle</span>
                                    <span>{{ $item }}</span>
                                </div>
                            @endforeach
                            @if(count($s->included_items ?? []) > 4)
                                <div class="text-[11px] font-mono text-amber-400/80 pl-6">
                                    + {{ count($s->included_items) - 4 }} item checklist lainnya
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Price & Actions Footer -->
                    <div class="pt-5 border-t border-[#222F3E] mt-4">
                        <div class="flex items-baseline justify-between mb-4">
                            <span class="text-xs font-mono text-slate-500">Biaya Laboratorium:</span>
                            <div class="text-right">
                                <span class="font-heading font-bold text-2xl text-white tracking-tight">Rp{{ number_format($s->price, 0, ',', '.') }}</span>
                                <span class="block text-[10px] font-mono text-emerald-400">Fixed Price • Bergaransi</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('services.show', $s->slug) }}" class="inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#18212E] hover:bg-[#222F3E] text-slate-300 hover:text-white border border-[#222F3E] text-xs font-mono transition-colors">
                                <span>Detail Prosedur</span>
                                <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                            </a>
                            <a href="{{ route('bookings.create', $s->slug) }}" class="inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-semibold text-xs transition-all shadow-[0_0_12px_rgba(245,158,11,0.25)] hover:shadow-[0_0_18px_rgba(245,158,11,0.4)]">
                                <span class="material-symbols-outlined text-[15px]">calendar_month</span>
                                <span>Pesan Slot</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Comparative Specs Table -->
        <div class="p-8 sm:p-10 rounded-3xl bg-[#0F141D] border border-[#222F3E] mb-20 reveal-on-scroll">
            <div class="max-w-2xl mb-8">
                <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">ARSITEKTUR & PERBANDINGAN</span>
                <h3 class="text-2xl font-heading font-bold text-white mt-1">Panduan Memilih Sistem Operasi</h3>
                <p class="text-sm text-slate-400 mt-2">Masih bingung sistem mana yang cocok untuk perangkat Anda? Lihat perbandingan parameter teknis berikut:</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-sans">
                    <thead>
                        <tr class="border-b border-[#222F3E] text-slate-400 font-mono text-[11px] uppercase">
                            <th class="py-3 px-4">Parameter</th>
                            <th class="py-3 px-4 text-amber-400">Windows 11 / 10</th>
                            <th class="py-3 px-4 text-emerald-400">Linux (Ubuntu / Fedora / Arch)</th>
                            <th class="py-3 px-4 text-purple-400">Dual-Boot (Windows + Linux)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#222F3E]/60 text-slate-300">
                        <tr>
                            <td class="py-3.5 px-4 font-mono text-white font-medium">Kesesuaian Penggunaan</td>
                            <td class="py-3.5 px-4">Kerja kantoran, Gaming DirectX/Anti-Cheat, Adobe CC, MS Office.</td>
                            <td class="py-3.5 px-4">Programming, Server DevOps, Docker, privasi tinggi, PC spek lawas.</td>
                            <td class="py-3.5 px-4">Fleksibilitas maksimal: Gaming di Windows, ngoding di Linux.</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 px-4 font-mono text-white font-medium">Beban RAM & Storage</td>
                            <td class="py-3.5 px-4">Min. RAM 8GB direkomendasikan, SSD min. 120GB.</td>
                            <td class="py-3.5 px-4">Sangat ringan (RAM 4GB lancar), SSD min. 64GB.</td>
                            <td class="py-3.5 px-4">Min. SSD 256GB / 512GB untuk pembagian partisi leluasa.</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 px-4 font-mono text-white font-medium">Driver & Hardware</td>
                            <td class="py-3.5 px-4">Dukungan vendor 100% lengkap dari pabrik.</td>
                            <td class="py-3.5 px-4">Kernel Linux modern sudah bawaan driver mayoritas Wi-Fi & GPU.</td>
                            <td class="py-3.5 px-4">Konfigurasi bootloader GRUB UEFI terkalibrasi aman.</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 px-4 font-mono text-white font-medium">Garansi Purnajual</td>
                            <td class="py-3.5 px-4 text-emerald-400 font-mono">14 Hari Penuh</td>
                            <td class="py-3.5 px-4 text-emerald-400 font-mono">14 Hari Penuh</td>
                            <td class="py-3.5 px-4 text-emerald-400 font-mono">14 Hari Penuh</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Help Consultation Banner -->
        <div class="p-8 rounded-2xl bg-[#121822] border border-[#222F3E] flex flex-col md:flex-row items-center justify-between gap-6 reveal-on-scroll">
            <div class="space-y-1">
                <h4 class="text-lg font-heading font-bold text-white">Butuh Konsultasi Kondisi Laptop Anda Dulu?</h4>
                <p class="text-xs text-slate-400 font-sans">Kirimkan spesifikasi atau kendala Anda via WhatsApp ke tim teknisi lab kami. Gratis konsultasi.</p>
            </div>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp ?: '6285165017620') }}?text={{ rawurlencode('Halo INULIN, mau tanya konsultasi rekomendasi instal ulang untuk laptop saya.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-heading font-bold text-xs tracking-wide transition-all shadow-[0_0_16px_rgba(16,185,129,0.3)] shrink-0">
                <span class="material-symbols-outlined text-[18px]">chat</span>
                <span>Chat WhatsApp Teknisi</span>
            </a>
        </div>

    </div>
</div>
@endsection
