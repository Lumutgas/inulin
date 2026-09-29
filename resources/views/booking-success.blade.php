@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
    copied: false,
    copyText() {
        navigator.clipboard.writeText(`{{ addslashes($messageText) }}`);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}">

    <div class="rounded-2xl bg-[#121822] border border-[#222F3E] p-6 sm:p-10 shadow-2xl relative overflow-hidden">
        
        <!-- Top Status Banner -->
        <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-[#222F3E]">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px]">task_alt</span>
                </div>
                <div>
                    <span class="font-mono text-xs text-slate-400">NOMOR REFERENSI ORDER</span>
                    <h2 class="font-mono font-bold text-xl text-white">#{{ $booking->reference }}</h2>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="font-mono text-xs px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Menunggu Konfirmasi
                </span>
            </div>
        </div>

        <!-- Main Title & Instructions -->
        <div class="py-8 space-y-3">
            <h1 class="font-heading text-2xl sm:text-3xl font-bold text-white">
                Booking Berhasil Disimpan ke Sistem!
            </h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl">
                Data pengerjaan Anda telah tercatat dengan aman di database. Untuk mengunci slot jadwal dan menerima konfirmasi teknisi, silakan klik tombol di bawah untuk membuka WhatsApp.
            </p>
        </div>

        <!-- Primary WhatsApp CTA Button -->
        <div class="p-6 rounded-xl bg-[#0F141D] border border-emerald-500/40 space-y-4 mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <span class="text-xs font-mono font-semibold text-emerald-400 uppercase tracking-wider block">Langkah Terakhir</span>
                    <p class="text-sm text-white font-medium mt-0.5">Kirimkan rincian pesanan langsung ke teknisi INULIN:</p>
                </div>
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-heading font-bold text-sm tracking-wide transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)]">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    <span>Pesan via WhatsApp</span>
                </a>
            </div>

            <div class="pt-3 border-t border-[#222F3E] flex items-center justify-between text-[11px] font-mono text-slate-400">
                <span>WhatsApp Workshop: +{{ $settings->whatsapp ?: '6285165017620' }}</span>
                <span class="text-amber-400">*Tekan 'Send' di WhatsApp</span>
            </div>
        </div>

        <!-- Itemized Order Receipt -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="font-mono text-xs uppercase tracking-wider text-slate-400 font-semibold">Rincian Nota Booking</span>
                <span class="font-mono text-xs text-slate-500">Database Snapshot</span>
            </div>

            <div class="rounded-xl border border-[#222F3E] bg-[#070A0F] divide-y divide-[#222F3E] text-xs">
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Nama Pelanggan</span>
                    <span class="font-medium text-white text-right">{{ $booking->customer_name }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Nomor WhatsApp</span>
                    <span class="font-mono text-white text-right">{{ $booking->customer_whatsapp }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Perangkat</span>
                    <span class="text-white text-right">{{ $booking->device }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Sistem Operasi</span>
                    <span class="font-mono text-amber-400 text-right">{{ $booking->os }} ({{ $booking->os_version }})</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Jadwal Kedatangan</span>
                    <span class="font-mono text-white text-right">{{ $booking->starts_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Layanan Dipilih</span>
                    <span class="font-medium text-white text-right">{{ $booking->service_name }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Metode Pembayaran</span>
                    <span class="font-medium {{ $booking->payment_method === 'qris' ? 'text-amber-400' : 'text-slate-200' }} text-right">
                        @if($booking->payment_method === 'qris')
                            {{ $booking->payment_timing === 'paylater' ? 'QRIS Paylater (Bayar Setelah Jadi)' : 'QRIS Langsung (Bukti Terlampir)' }}
                        @else
                            Tunai di Workshop
                        @endif
                    </span>
                </div>
                @if($booking->payment_method === 'qris' && $booking->payment_timing === 'paylater')
                    <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#0B0F17]">
                        <div>
                            <span class="text-white font-semibold block text-xs">QRIS Paylater (Simpan untuk Dibayar Nanti)</span>
                            <span class="text-[11px] text-slate-400">Unduh gambar QRIS resmi untuk discan saat unit laptop selesai dicek di lab:</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ $settings->qrisImageUrl() }}" download="QRIS-INULIN.png" class="px-3 py-1.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono font-bold text-xs flex items-center gap-1 transition-colors shadow">
                                <span class="material-symbols-outlined text-[15px]">download</span>
                                <span>Unduh QRIS</span>
                            </a>
                        </div>
                    </div>
                @endif
                @if($booking->payment_proof)
                    <div class="p-4 flex items-center justify-between bg-[#0B0F17]">
                        <div>
                            <span class="text-slate-400 block">Bukti Transfer QRIS</span>
                            <span class="text-[11px] text-amber-400 font-mono">Menunggu ACC Teknisi</span>
                        </div>
                        <a href="{{ $booking->paymentProofUrl() }}" target="_blank" class="flex items-center gap-2 group p-1 rounded-lg bg-[#121822] border border-amber-800/40 hover:border-amber-400 transition-colors">
                            <img src="{{ $booking->paymentProofUrl() }}" alt="Bukti Transfer" class="w-12 h-12 object-cover rounded">
                            <span class="text-xs font-mono text-amber-400 group-hover:underline flex items-center gap-1 pr-2">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                <span>Lihat Foto</span>
                            </span>
                        </a>
                    </div>
                @endif
                <div class="p-4 flex items-center justify-between">
                    <span class="text-slate-400">Catatan Khusus</span>
                    <span class="text-slate-300 italic text-right max-w-sm">{{ $booking->notes ?: '-' }}</span>
                </div>
                <div class="p-4 flex items-center justify-between bg-[#0F141D]">
                    <div>
                        <span class="font-heading font-semibold text-sm text-white">Total Biaya Layanan</span>
                        <span class="block text-[11px] text-slate-400">
                            @if($booking->payment_method === 'qris')
                                {{ $booking->payment_timing === 'paylater' ? 'Bayar nanti via QRIS saat serah terima di lab' : 'Dibayarkan via QRIS (Menunggu ACC)' }}
                            @else
                                Bayar setelah selesai dicek di lab
                            @endif
                        </span>
                    </div>
                    <span class="font-mono font-bold text-xl text-[#F59E0B]">
                        Rp{{ number_format($booking->service_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Plain WhatsApp Snippet Preview for manual copy -->
        <div class="mt-8 p-5 rounded-xl bg-[#0F141D] border border-[#222F3E] space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono text-slate-400">TEKS PESAN WHATSAPP</span>
                <button type="button" @click="copyText()" class="text-xs font-mono text-amber-400 hover:text-amber-300 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">content_copy</span>
                    <span x-text="copied ? 'Tersalin!' : 'Salin Pesan'">Salin Pesan</span>
                </button>
            </div>
            <pre class="p-3 rounded-lg bg-[#070A0F] border border-[#222F3E] text-[11px] font-mono text-slate-300 whitespace-pre-wrap leading-relaxed select-all">{{ $messageText }}</pre>
        </div>

        <!-- Action Links -->
        <div class="mt-8 pt-6 border-t border-[#222F3E] flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-mono text-slate-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali ke Halaman Depan</span>
            </a>

            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#18212E] hover:bg-[#1E293B] border border-[#222F3E] text-xs font-mono text-slate-300 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-[16px]">print</span>
                <span>Cetak Nota</span>
            </button>
        </div>

    </div>

</div>
@endsection
