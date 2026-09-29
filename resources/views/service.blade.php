@extends('layouts.app')

@section('content')
<div class="border-b border-[#1E293B] bg-[#0F141D]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex items-center gap-2 font-mono text-xs text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a>
            <span class="text-slate-600">/</span>
            <a href="{{ route('home') }}#layanan" class="hover:text-amber-400 transition-colors">Layanan</a>
            <span class="text-slate-600">/</span>
            <span class="text-white">{{ $service->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
    <!-- Header Block -->
    <div class="mb-10 lg:mb-12">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#18212E] border border-amber-800/40 text-xs font-medium text-amber-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Laboratorium Teknis Terverifikasi
            </span>
            <span class="text-xs font-mono text-slate-400">REF: {{ strtoupper(str_replace('-', '', substr($service->slug, 0, 8))) }}-LAB</span>
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white mb-4">
            {{ $service->name }}
        </h1>
        <p class="text-base sm:text-lg text-slate-400 max-w-3xl leading-relaxed">
            {{ $service->description }}
        </p>
    </div>

    <!-- Main Grid: 8 Cols Content + 4 Cols Sticky Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        
        <!-- Left Column: Specs & Procedures -->
        <div class="lg:col-span-8 space-y-10">
            
            <!-- Quick Specs Row -->
            <div class="rounded-xl border border-[#222F3E] bg-[#121822] overflow-hidden">
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-[#222F3E] bg-[#151C27]">
                    <div class="p-5 flex items-center gap-3.5">
                        <span class="material-symbols-outlined text-amber-400 text-2xl">schedule</span>
                        <div>
                            <div class="text-xs text-slate-400">Estimasi Durasi</div>
                            <div class="text-sm font-semibold text-white">{{ $service->duration ?: 60 }} – {{ ($service->duration ?: 60) + 30 }} Menit</div>
                        </div>
                    </div>
                    <div class="p-5 flex items-center gap-3.5">
                        <span class="material-symbols-outlined text-emerald-400 text-2xl">folder_managed</span>
                        <div>
                            <div class="text-xs text-slate-400">Proteksi Data D/E</div>
                            <div class="text-sm font-semibold text-emerald-400">100% Aman Terisolasi</div>
                        </div>
                    </div>
                    <div class="p-5 flex items-center gap-3.5">
                        <span class="material-symbols-outlined text-amber-400 text-2xl">verified_user</span>
                        <div>
                            <div class="text-xs text-slate-400">Masa Jaminan</div>
                            <div class="text-sm font-semibold text-white">Garansi 14 Hari Penuh</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prosedur Pengerjaan Teknis -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-400 text-2xl">precision_manufacturing</span>
                    <h2 class="text-xl sm:text-2xl font-semibold text-white">Prosedur Pengerjaan Teknis</h2>
                </div>
                <div class="p-6 sm:p-7 rounded-xl bg-[#121822] border border-[#222F3E] text-slate-300 space-y-3 leading-relaxed text-sm">
                    <p>
                        Pendekatan lab kami murni mengutamakan integritas fungsional dan performa jangka panjang komputer Anda. Kami tidak menggunakan proses reset pabrikan instan yang rentan mempertahankan sisa registry korup atau file temporary sampah. Partisi sistem diformat bersih dan direkonstruksi dari sektor awal menggunakan master image ISO terverifikasi resmi.
                    </p>
                    <p class="text-slate-400">
                        Seluruh injeksi driver hardware dilakukan secara terstruktur dari manufaktur resmi (chipset motherboard, GPU NVIDIA/AMD/Intel, audio Realtek, Wi-Fi 6, dan touchpad presisi). Seluruh bloatware bawaan vendor yang membebani memori kerja ditiadakan untuk menjamin latensi booting minimal dan efisiensi konsumsi daya.
                    </p>
                </div>
            </div>

            <!-- Inclusions: Paket Sudah Termasuk -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-400 text-2xl">fact_check</span>
                        <h2 class="text-xl sm:text-2xl font-semibold text-white">Paket Layanan Sudah Termasuk</h2>
                    </div>
                    <span class="font-mono text-xs px-2.5 py-1 rounded bg-[#18212E] text-slate-300 border border-[#222F3E]">STANDAR LAB</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($service->included_items ?? [] as $item)
                        <div class="p-4 sm:p-5 rounded-xl bg-[#121822] border border-[#222F3E] flex items-start gap-3">
                            <span class="material-symbols-outlined text-amber-400 text-[20px] mt-0.5">check_circle</span>
                            <div>
                                <h3 class="font-medium text-white text-sm">{{ $item }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Terstandarisasi dan diuji stabilitasnya di lab sebelum penyerahan unit.</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-6 rounded-xl bg-[#121822] border border-[#222F3E] text-slate-500 text-xs text-center">
                            Detail rincian paket belum diatur.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Edisi & Sistem Operasi yang Didukung -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-400 text-2xl">terminal</span>
                    <h2 class="text-xl sm:text-2xl font-semibold text-white">Edisi & Distribusi yang Didukung</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($service->supported_os ?? [] as $osItem)
                        <div class="p-5 rounded-xl bg-[#121822] border border-[#222F3E] flex flex-col justify-between">
                            <div>
                                <span class="font-mono text-[10px] text-amber-400 uppercase tracking-wider block mb-1">KOMPATIBEL</span>
                                <h3 class="font-heading font-semibold text-sm text-white">{{ $osItem }}</h3>
                                <p class="text-xs text-slate-400 mt-1">Dukungan arsitektur 64-bit UEFI & Legacy GPT/MBR.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Panduan & Catatan Keamanan Data (From Rangka-Web) -->
            <div class="p-6 rounded-xl bg-amber-950/20 border border-amber-800/40 text-amber-200 space-y-2">
                <div class="flex items-center gap-2 font-heading font-semibold text-sm text-amber-300">
                    <span class="material-symbols-outlined text-[20px]">warning</span>
                    <span>Panduan Keamanan Data Pelanggan</span>
                </div>
                <p class="text-xs sm:text-sm text-amber-200/90 leading-relaxed">
                    Kami tidak menyediakan jasa backup otomatis untuk file di Drive C (Desktop, Downloads, Documents). Pastikan data penting di folder tersebut sudah dipindahkan ke partisi D atau flashdisk sebelum perangkat diantar ke lab. Partisi D dan E tetap 100% aman dan tidak tersentuh.
                </p>
            </div>

            <!-- Ulasan Pelanggan -->
            @if($testimonials->isNotEmpty())
                <div class="space-y-4 pt-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-400 text-2xl">rate_review</span>
                        <h2 class="text-xl sm:text-2xl font-semibold text-white">Ulasan Pelanggan</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($testimonials as $testi)
                            <div class="p-5 rounded-xl bg-[#121822] border border-[#222F3E] hover:border-amber-500/40 transition-all">
                                <div class="flex items-center gap-1 text-amber-400 mb-2">
                                    @php $r = (int) ($testi->rating ?? 5); @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $r)
                                            <span class="material-symbols-outlined text-[16px] text-amber-400 star-filled" style="font-variation-settings: 'FILL' 1;">star</span>
                                        @else
                                            <span class="material-symbols-outlined text-[16px] text-slate-600 star-empty" style="font-variation-settings: 'FILL' 0;">star</span>
                                        @endif
                                    @endfor
                                    <span class="font-mono text-xs text-amber-400 font-bold ml-1">({{ $r }}.0)</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-300 italic leading-relaxed">“{{ $testi->quote }}”</p>
                                <div class="mt-3 pt-2.5 border-t border-[#222F3E]/60 flex items-center justify-between text-xs">
                                    <span class="font-heading font-semibold text-white">{{ $testi->name }}</span>
                                    <span class="text-amber-400 font-mono text-[11px]">{{ $testi->role ?: 'Terverifikasi' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Column: Sticky Pricing Card & Instant Booking CTA -->
        <aside class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
            <div class="rounded-xl bg-[#121822] border border-[#222F3E] p-6 sm:p-7 shadow-2xl space-y-6">
                
                <!-- Price Section (Database-Driven) -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Tarif Resmi Lab</span>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-mono font-medium text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-800/40">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Slot Hari Ini Tersedia
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2 pt-2">
                        <span class="font-mono text-3xl sm:text-4xl font-bold text-white">
                            Rp{{ number_format($service->price, 0, ',', '.') }}
                        </span>
                        <span class="text-slate-400 text-xs font-mono">/ unit</span>
                    </div>
                </div>

                <!-- Spec Info List -->
                <div class="p-4 rounded-lg bg-[#0F141D] border border-[#222F3E] text-xs space-y-2.5">
                    <div class="flex items-center justify-between font-mono">
                        <span class="text-slate-400">Layanan</span>
                        <span class="text-white font-medium truncate max-w-[170px] text-right">{{ $service->name }}</span>
                    </div>
                    <div class="flex items-center justify-between font-mono">
                        <span class="text-slate-400">Estimasi Waktu</span>
                        <span class="text-white font-medium">{{ $service->duration ?: 60 }} Menit</span>
                    </div>
                    <div class="flex items-center justify-between font-mono">
                        <span class="text-slate-400">Garansi Layanan</span>
                        <span class="text-emerald-400 font-medium">14 Hari Penuh</span>
                    </div>
                </div>

                <!-- Feature Highlights -->
                <ul class="space-y-2 text-xs text-slate-300">
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-[16px]">check</span>
                        <span>Instalasi bersih tanpa aplikasi pihak ketiga</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-[16px]">check</span>
                        <span>Driver GPU, audio, dan Wi-Fi lengkap</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-[16px]">check</span>
                        <span>Pembayaran tunai / QRIS setelah selesai</span>
                    </li>
                </ul>

                <!-- Primary Booking CTA -->
                <div class="space-y-3 pt-2">
                    <a href="{{ route('bookings.create', $service) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-semibold text-sm tracking-wide transition-all shadow-[0_0_16px_rgba(245,158,11,0.25)] hover:shadow-[0_0_20px_rgba(245,158,11,0.4)]">
                        <span>Pesan Sekarang</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp ?: '6285165017620') }}?text={{ rawurlencode('Halo INULIN, saya tertarik dengan layanan ' . $service->name . '. Apakah ada slot hari ini?') }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-[#18212E] hover:bg-[#1E293B] text-slate-300 hover:text-white border border-[#222F3E] font-heading font-medium text-xs transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-amber-400">chat</span>
                        <span>Tanya Dulu via WhatsApp</span>
                    </a>
                </div>

                <div class="pt-3 border-t border-[#222F3E]/60 text-[11px] font-mono text-slate-500 text-center">
                    Tarif tersinkronisasi otomatis dengan database pusat.
                </div>
            </div>

            <!-- Other Services Quick List -->
            @if(isset($allServices) && $allServices->count() > 1)
                <div class="rounded-xl bg-[#121822] border border-[#222F3E] p-5">
                    <h3 class="font-heading font-semibold text-xs text-white uppercase tracking-wider mb-3">Layanan Lainnya</h3>
                    <div class="space-y-2">
                        @foreach($allServices->where('id', '!=', $service->id)->take(3) as $other)
                            <a href="{{ route('services.show', $other) }}" class="flex items-center justify-between p-2.5 rounded-lg bg-[#0F141D] hover:bg-[#18212E] border border-[#222F3E] transition-colors text-xs">
                                <span class="text-slate-300 font-medium truncate max-w-[150px]">{{ $other->name }}</span>
                                <span class="font-mono text-amber-400 font-semibold">Rp{{ number_format($other->price, 0, ',', '.') }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>

    </div>
</div>
@endsection
