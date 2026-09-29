@extends('layouts.app', ['title' => 'Pusat Bantuan & Pertanyaan Umum (FAQ) — INULIN'])

@section('content')
<div class="relative overflow-hidden pt-12 pb-24" x-data="{ 
    search: '', 
    activeCategory: 'all',
    openFaq: null,
    toggle(id) {
        this.openFaq = this.openFaq === id ? null : id;
    }
}">
    <!-- Ambient Gradients -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-amber-500/10 via-transparent to-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute top-48 right-10 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-8 animate-fade-up">
            <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">INULIN</a>
            <span class="text-slate-600">/</span>
            <span class="text-amber-400">Pusat Bantuan & FAQ</span>
        </nav>

        <!-- Page Header -->
        <div class="text-center mb-12 animate-fade-up delay-100">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-950/60 border border-amber-800/40 text-amber-400 text-xs font-mono font-medium mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>KNOWLEDGE BASE & PUSAT BANTUAN</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-bold text-white tracking-tight leading-tight mb-4">
                Pertanyaan yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-300">Sering Ditanyakan</span>
            </h1>
            <p class="text-base text-slate-300 font-sans max-w-2xl mx-auto leading-relaxed">
                Temukan jawaban lengkap seputar keamanan partisi data, master OS resmi, alur booking, dan masa garansi laboratorium kami.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mb-10 animate-fade-up delay-150">
            <div class="relative">
                <input type="text" x-model="search" placeholder="Ketik kata kunci pertanyaan (misal: data, partisi, garansi, aktivasi)..." class="w-full bg-[#121822] border border-[#222F3E] focus:border-[#F59E0B] focus:ring-2 focus:ring-[#F59E0B]/20 rounded-2xl pl-12 pr-4 py-4 text-sm text-white placeholder-slate-500 font-mono transition-all shadow-xl">
                <span class="material-symbols-outlined text-2xl text-amber-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">search</span>
                <button type="button" x-show="search.length > 0" @click="search = ''" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white text-xs font-mono">
                    Reset
                </button>
            </div>
        </div>

        <!-- FAQ Accordion List -->
        <div class="space-y-4 mb-20 animate-fade-up delay-200">
            @forelse($faqs as $f)
                <div x-show="search === '' || '{{ strtolower($f->question . ' ' . $f->answer) }}'.includes(search.toLowerCase())" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="rounded-2xl bg-[#121822] border border-[#222F3E] transition-all overflow-hidden"
                     :class="openFaq === {{ $f->id }} ? 'border-amber-500/50 shadow-[0_0_20px_rgba(245,158,11,0.1)]' : 'hover:border-slate-700'">
                    
                    <button type="button" @click="toggle({{ $f->id }})" class="w-full p-5 sm:p-6 text-left flex items-center justify-between gap-4 select-none">
                        <span class="font-heading font-bold text-base sm:text-lg text-white" :class="openFaq === {{ $f->id }} ? 'text-amber-400' : ''">
                            {{ $f->question }}
                        </span>
                        <div class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center shrink-0 text-amber-400 transition-transform duration-200" :class="openFaq === {{ $f->id }} ? 'rotate-180 bg-amber-950/80 border-amber-800' : ''">
                            <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </button>

                    <div x-show="openFaq === {{ $f->id }}" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-5 pb-6 sm:px-6 sm:pb-6 pt-1 text-sm text-slate-300 leading-relaxed font-sans border-t border-[#222F3E]/60 bg-[#070A0F]/40">
                        {!! nl2br(e($f->answer)) !!}
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-[#121822] border border-[#222F3E] text-center text-slate-400 text-xs font-mono">
                    Belum ada data pertanyaan umum yang dimasukkan.
                </div>
            @endforelse
        </div>

        <!-- Helpdesk Contact Card -->
        <div class="p-8 sm:p-10 rounded-3xl bg-[#0F141D] border border-amber-500/30 flex flex-col md:flex-row items-center justify-between gap-6 reveal-on-scroll">
            <div class="space-y-1 text-center md:text-left">
                <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">TIDAK MENEMUKAN JAWABAN?</span>
                <h3 class="text-xl font-heading font-bold text-white">Konsultasi Langsung dengan Tim Teknisi</h3>
                <p class="text-xs text-slate-400 font-sans">Kami siap menjawab pertanyaan spesifik mengenai laptop atau sistem Anda.</p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp ?: '6285165017620') }}?text={{ rawurlencode('Halo INULIN, saya mau tanya seputar layanan instal ulang komputer.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-heading font-bold text-xs tracking-wide transition-all shadow-[0_0_16px_rgba(16,185,129,0.3)]">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    <span>Chat WhatsApp Kami</span>
                </a>
                <a href="{{ route('bookings.general') }}" class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl bg-[#18212E] hover:bg-[#222F3E] text-white border border-[#222F3E] font-heading font-semibold text-xs transition-colors">
                    <span>Booking Langsung &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
