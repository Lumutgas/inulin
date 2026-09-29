@extends('admin.layout', ['pageTitle' => 'MANAJEMEN PESANAN'])

@section('content')
<div class="space-y-6" x-data="{
    detailModal: false,
    rescheduleModal: false,
    createManualModal: false,
    proofModal: false,
    proofUrl: '',
    proofBookingRef: '',
    activeBooking: null,

    openDetail(booking) {
        this.activeBooking = booking;
        this.detailModal = true;
    },

    openReschedule(booking) {
        this.activeBooking = booking;
        this.rescheduleModal = true;
    },

    openProof(url, ref) {
        this.proofUrl = url;
        this.proofBookingRef = ref;
        this.proofModal = true;
    }
}">

    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 rounded-xl bg-[#121822] border border-slate-800">
        <div>
            <h2 class="text-base font-heading font-bold text-white flex items-center gap-2">
                <span>Daftar Seluruh Pesanan Layanan</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-950/80 border border-amber-800/40 text-amber-400 font-mono text-xs">
                    {{ $bookings->total() }} Pesanan
                </span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola verifikasi ACC pembayaran QRIS, update status teknis, jadwal, atau tambah pesanan walk-in langsung.</p>
        </div>

        <button type="button" @click="createManualModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-xs transition-all shadow-[0_0_15px_rgba(245,158,11,0.25)] shrink-0">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            <span>+ Pesanan Walk-in / Manual</span>
        </button>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 space-y-4">
        <form method="GET" action="{{ route('admin.bookings') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label for="search" class="sr-only">Cari</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-[18px]">search</span>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor WA, atau kode ref..." class="w-full bg-[#070A0F] border border-slate-800 focus:border-[#F59E0B] rounded-lg pl-9 pr-3.5 py-2 text-xs text-white placeholder-slate-500">
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" onchange="this.form.submit()" class="w-full bg-[#070A0F] border border-slate-800 focus:border-[#F59E0B] rounded-lg px-3 py-2 text-xs font-mono text-slate-300">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Payment Filter -->
            <div>
                <select name="payment_status" onchange="this.form.submit()" class="w-full bg-[#070A0F] border border-slate-800 focus:border-[#F59E0B] rounded-lg px-3 py-2 text-xs font-mono text-slate-300">
                    <option value="">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Bayar / Menunggu ACC</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas / Terverifikasi</option>
                </select>
            </div>

            <!-- Submit Button & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs font-semibold transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'payment_status', 'sort']))
                    <a href="{{ route('admin.bookings') }}" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white" title="Reset filter">
                        <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0C111D] border-b border-slate-800 text-slate-400 font-mono uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-4">Kode Ref</th>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4">Layanan & OS</th>
                        <th class="p-4">Jadwal Slot</th>
                        <th class="p-4">Tarif</th>
                        <th class="p-4">Metode & Bukti</th>
                        <th class="p-4">Status Progres</th>
                        <th class="p-4">Status Bayar & ACC</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-[#161F2C] transition-colors">
                            <!-- Ref -->
                            <td class="p-4 font-mono font-semibold text-amber-400 whitespace-nowrap">
                                #{{ $booking->reference }}
                            </td>

                            <!-- Pelanggan -->
                            <td class="p-4">
                                <div class="font-medium text-white">{{ $booking->customer_name }}</div>
                                <a href="https://wa.me/{{ $booking->customer_whatsapp }}" target="_blank" class="font-mono text-slate-400 hover:text-emerald-400 transition-colors flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[12px]">chat</span>
                                    <span>{{ $booking->customer_whatsapp }}</span>
                                </a>
                            </td>

                            <!-- Layanan & OS -->
                            <td class="p-4 max-w-[200px]">
                                <div class="text-white truncate font-medium">{{ $booking->service_name }}</div>
                                <div class="font-mono text-[11px] text-slate-400 mt-0.5 truncate">
                                    {{ $booking->device }} &bull; {{ $booking->os }} ({{ $booking->os_version }})
                                </div>
                            </td>

                            <!-- Jadwal -->
                            <td class="p-4 whitespace-nowrap font-mono">
                                <div class="text-slate-200">{{ $booking->starts_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $booking->starts_at->format('H:i') }} - {{ $booking->ends_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Tarif -->
                            <td class="p-4 font-mono whitespace-nowrap font-semibold text-white">
                                Rp{{ number_format($booking->service_price, 0, ',', '.') }}
                            </td>

                            <!-- Metode & Bukti -->
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ $booking->payment_method === 'qris' ? 'bg-amber-950/80 text-amber-300 border border-amber-800/40' : 'bg-slate-800 text-slate-300 border border-slate-700' }}">
                                            {{ $booking->payment_method === 'qris' ? 'QRIS' : 'TUNAI' }}
                                        </span>

                                        @if($booking->payment_method === 'qris')
                                            <span class="text-[10px] font-mono {{ $booking->payment_timing === 'paylater' ? 'text-purple-400' : 'text-amber-400' }}">
                                                {{ $booking->payment_timing === 'paylater' ? 'Paylater' : 'Langsung' }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($booking->payment_proof)
                                        <button type="button" @click="openProof('{{ $booking->paymentProofUrl() }}', '{{ $booking->reference }}')" class="self-start px-1.5 py-0.5 rounded bg-emerald-950/50 hover:bg-emerald-900/50 border border-emerald-800/40 text-emerald-400 hover:text-emerald-300 transition-colors flex items-center gap-1 text-[10px] font-mono" title="Lihat Foto Bukti Pembayaran">
                                            <span class="material-symbols-outlined text-[13px]">photo_camera</span>
                                            <span>Bukti Foto</span>
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Dropdown Form -->
                            <td class="p-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="bg-[#070A0F] border text-[11px] font-mono rounded px-2 py-1 focus:outline-none {{ 
                                        $booking->status === 'completed' ? 'border-emerald-600/40 text-emerald-400' : (
                                        $booking->status === 'in_progress' ? 'border-amber-600/40 text-amber-400' : (
                                        $booking->status === 'confirmed' ? 'border-amber-600/40 text-amber-400' : (
                                        $booking->status === 'cancelled' ? 'border-slate-700 text-slate-500' : 'border-amber-600/40 text-amber-400'
                                    ))) }}">
                                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>• Pending</option>
                                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>• Confirmed</option>
                                        <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>• In Progress</option>
                                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>• Completed</option>
                                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>• Cancelled</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Payment & ACC Button -->
                            <td class="p-4 whitespace-nowrap">
                                @if($booking->payment_status === 'paid')
                                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="payment_status" value="unpaid">
                                        <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-mono font-semibold bg-emerald-950/60 text-emerald-400 border border-emerald-800/40 hover:bg-emerald-900/60 transition-colors" title="Klik untuk membatalkan verifikasi lunas">
                                            ✓ LUNAS (ACC)
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <!-- Form ACC & Selesai -->
                                        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="payment_status" value="paid">
                                            <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-mono font-bold transition-all shadow-sm flex items-center gap-1 {{ $booking->payment_method === 'qris' ? 'bg-[#F59E0B] hover:bg-amber-300 text-slate-950 shadow-[0_0_10px_rgba(245,158,11,0.3)]' : 'bg-emerald-600 hover:bg-emerald-500 text-white' }}" title="{{ $booking->payment_method === 'qris' ? 'ACC Verifikasi Bukti QRIS' : 'Tandai Lunas Tunai' }}">
                                                <span class="material-symbols-outlined text-[13px]">verified</span>
                                                <span>{{ $booking->payment_method === 'qris' ? 'ACC QRIS' : 'LUNASI' }}</span>
                                            </button>
                                        </form>
                                        <span class="text-[10px] font-mono text-amber-400">Belum Bayar</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- View Detail -->
                                    <button type="button" @click="openDetail({{ json_encode($booking) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white" title="Lihat Rincian">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    </button>

                                    <!-- Reschedule -->
                                    <button type="button" @click="openReschedule({{ json_encode($booking) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400 hover:text-amber-300" title="Jadwalkan Ulang">
                                        <span class="material-symbols-outlined text-[16px]">edit_calendar</span>
                                    </button>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.bookings.delete', $booking) }}" onsubmit="return confirm('Hapus booking ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/40 text-slate-400 hover:text-rose-400" title="Hapus Booking">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500 font-mono">
                                Tidak ada data pesanan yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-800 bg-[#0C111D]">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- DETAIL MODAL -->
    <div x-show="detailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div @click.away="detailModal = false" class="w-full max-w-lg rounded-2xl bg-[#121822] border border-slate-800 p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">receipt_long</span>
                    <h3 class="font-heading font-bold text-base text-white">Detail Pesanan</h3>
                </div>
                <button type="button" @click="detailModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <template x-if="activeBooking">
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-lg bg-[#070A0F] border border-slate-800">
                        <div>
                            <span class="text-slate-500 font-mono text-[10px] block">NOMOR REFERENSI</span>
                            <span class="font-mono text-amber-400 font-bold" x-text="'#' + activeBooking.reference"></span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-mono text-[10px] block">STATUS PROGRES</span>
                            <span class="font-mono uppercase font-semibold text-white" x-text="activeBooking.status"></span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400">Nama Pelanggan:</span>
                        <div class="font-semibold text-white text-sm" x-text="activeBooking.customer_name"></div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400">Nomor WhatsApp:</span>
                        <div class="font-mono text-emerald-400" x-text="activeBooking.customer_whatsapp"></div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400">Layanan:</span>
                        <div class="font-medium text-white" x-text="activeBooking.service_name"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-slate-400">Perangkat:</span>
                            <div class="text-slate-200" x-text="activeBooking.device"></div>
                        </div>
                        <div>
                            <span class="text-slate-400">OS Target:</span>
                            <div class="font-mono text-amber-300" x-text="activeBooking.os + ' (' + activeBooking.os_version + ')'"></div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400">Jadwal Kedatangan:</span>
                        <div class="font-mono text-white" x-text="activeBooking.starts_at"></div>
                    </div>

                    <!-- Payment Method & Proof Info -->
                    <div class="p-3 rounded-lg bg-[#070A0F] border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-mono text-[11px]">Metode Pembayaran:</span>
                            <span class="font-mono font-bold text-amber-400 uppercase" x-text="activeBooking.payment_method === 'qris' ? ('QRIS (' + (activeBooking.payment_timing === 'paylater' ? 'Paylater / Pasca Servis' : 'Langsung') + ')') : 'Tunai di Workshop'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-mono text-[11px]">Status Bayar:</span>
                            <span class="font-mono font-bold uppercase" :class="activeBooking.payment_status === 'paid' ? 'text-emerald-400' : 'text-amber-400'" x-text="activeBooking.payment_status === 'paid' ? 'LUNAS (ACC)' : 'BELUM BAYAR'"></span>
                        </div>

                        <!-- Image Preview in Modal if Available -->
                        <template x-if="activeBooking.payment_proof_url">
                            <div class="pt-2 border-t border-slate-800/80 flex items-center justify-between">
                                <span class="text-slate-400 font-mono text-[11px]">Foto Bukti Transfer:</span>
                                <button type="button" @click="openProof(activeBooking.payment_proof_url, activeBooking.reference)" class="px-2.5 py-1 rounded bg-amber-950/60 border border-amber-700/60 text-amber-400 hover:text-amber-300 flex items-center gap-1.5 font-mono text-[11px]">
                                    <span class="material-symbols-outlined text-[14px]">photo</span>
                                    <span>Buka Bukti Foto</span>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400">Catatan Khusus:</span>
                        <div class="p-2.5 rounded bg-[#070A0F] border border-slate-800 text-slate-300 italic" x-text="activeBooking.notes || '-'"></div>
                    </div>

                    <div class="p-3 rounded-lg bg-amber-950/30 border border-amber-800/40 flex items-center justify-between">
                        <span class="text-slate-300">Tarif Layanan:</span>
                        <span class="font-mono font-bold text-base text-[#F59E0B]" x-text="'Rp' + Number(activeBooking.service_price).toLocaleString('id-ID')"></span>
                    </div>
                </div>
            </template>

            <div class="pt-3 border-t border-slate-800 flex items-center justify-between gap-3">
                <template x-if="activeBooking && activeBooking.payment_status === 'unpaid'">
                    <form method="POST" :action="'/admin/bookings/' + activeBooking.id">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="payment_status" value="paid">
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-mono text-xs font-bold flex items-center gap-1.5 shadow">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            <span>ACC & Konfirmasi Selesai</span>
                        </button>
                    </form>
                </template>

                <button type="button" @click="detailModal = false" class="ml-auto px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- RESCHEDULE MODAL -->
    <div x-show="rescheduleModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div @click.away="rescheduleModal = false" class="w-full max-w-md rounded-2xl bg-[#121822] border border-slate-800 p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">edit_calendar</span>
                    <h3 class="font-heading font-bold text-base text-white">Jadwalkan Ulang</h3>
                </div>
                <button type="button" @click="rescheduleModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <template x-if="activeBooking">
                <form method="POST" :action="'/admin/bookings/' + activeBooking.id + '/reschedule'" class="space-y-4">
                    @csrf
                    
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">Pelanggan:</span>
                        <div class="font-semibold text-white text-xs font-mono" x-text="activeBooking.customer_name + ' (#' + activeBooking.reference + ')'"></div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300">Tanggal Baru</label>
                        <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300">Jam Slot Baru</label>
                        <select name="time" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="09:00">09:00 WIB</option>
                            <option value="10:30">10:30 WIB</option>
                            <option value="13:00">13:00 WIB</option>
                            <option value="14:30">14:30 WIB</option>
                            <option value="16:00">16:00 WIB</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex justify-end gap-2">
                        <button type="button" @click="rescheduleModal = false" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-mono">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- PROOF LIGHTBOX MODAL -->
    <div x-show="proofModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" @keydown.escape.window="proofModal = false">
        <div @click.away="proofModal = false" class="relative max-w-2xl w-full bg-[#121822] border border-amber-500/40 rounded-2xl overflow-hidden shadow-2xl animate-fade-up">
            <div class="p-4 border-b border-slate-800 flex items-center justify-between bg-[#070A0F]">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <h3 class="font-heading font-bold text-white text-sm">Bukti Pembayaran QRIS — Ref #<span x-text="proofBookingRef"></span></h3>
                </div>
                <button type="button" @click="proofModal = false" class="p-1 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <div class="max-h-[70vh] bg-black flex items-center justify-center p-3 overflow-hidden">
                <img :src="proofUrl" alt="Foto Bukti Pembayaran" class="max-h-[65vh] w-auto max-w-full object-contain rounded-lg shadow-lg">
            </div>
            <div class="p-4 bg-[#0F141D] border-t border-slate-800 flex items-center justify-between">
                <a :href="proofUrl" target="_blank" class="text-xs font-mono text-amber-400 hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                    <span>Buka Foto Asli Tab Baru</span>
                </a>
                <button type="button" @click="proofModal = false" class="px-4 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-mono text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- CREATE MANUAL / WALK-IN BOOKING MODAL -->
    <div x-show="createManualModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm">
        <div @click.away="createManualModal = false" class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-[#121822] border border-amber-800/40 p-6 sm:p-8 shadow-2xl space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-950/80 border border-amber-800/50 text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">add_circle</span>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-lg text-white">Tambah Pesanan Walk-in / Manual</h3>
                        <p class="text-xs text-slate-400">Input transaksi pelanggan yang datang langsung ke meja lab INULIN.</p>
                    </div>
                </div>
                <button type="button" @click="createManualModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.bookings.manual') }}" class="space-y-4">
                @csrf

                <!-- Pilih Layanan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-medium text-slate-300">Pilih Paket Layanan <span class="text-rose-400">*</span></label>
                    <select name="service_id" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3.5 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                        @foreach($services ?? [] as $srv)
                            <option value="{{ $srv->id }}">
                                {{ $srv->name }} — Rp{{ number_format($srv->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Pelanggan -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Nama Pelanggan <span class="text-rose-400">*</span></label>
                        <input type="text" name="customer_name" required placeholder="Nama lengkap pelanggan" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Nomor WhatsApp <span class="text-rose-400">*</span></label>
                        <input type="tel" name="customer_whatsapp" required placeholder="08xxxxxxxxxx" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Perangkat -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Tipe Perangkat <span class="text-rose-400">*</span></label>
                        <select name="device" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="Laptop">Laptop</option>
                            <option value="PC">PC Desktop</option>
                        </select>
                    </div>

                    <!-- OS Family -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Sistem Operasi <span class="text-rose-400">*</span></label>
                        <select name="os" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="Windows">Windows</option>
                            <option value="Linux">Linux</option>
                            <option value="Dual Boot">Dual Boot</option>
                        </select>
                    </div>

                    <!-- OS Version -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Versi / Distro</label>
                        <input type="text" name="os_version" placeholder="Windows 11 Pro / Ubuntu 24.04" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Tanggal Booking <span class="text-rose-400">*</span></label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <!-- Jam -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Jam Slot <span class="text-rose-400">*</span></label>
                        <select name="time" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="09:00">09:00 WIB</option>
                            <option value="10:30">10:30 WIB</option>
                            <option value="13:00">13:00 WIB</option>
                            <option value="14:30">14:30 WIB</option>
                            <option value="16:00">16:00 WIB</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Status Transaksi -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Status Progres <span class="text-rose-400">*</span></label>
                        <select name="status" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="in_progress" selected>In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Metode Bayar <span class="text-rose-400">*</span></label>
                        <select name="payment_method" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="cash" selected>Tunai di Workshop</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <!-- Status Bayar -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-slate-300">Status Pembayaran <span class="text-rose-400">*</span></label>
                        <select name="payment_status" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="unpaid">Belum Lunas</option>
                            <option value="paid" selected>Lunas (ACC)</option>
                        </select>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-medium text-slate-300">Catatan Khusus</label>
                    <textarea name="notes" rows="2" placeholder="Catatan partisi, keluhan awal, dll." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-2.5 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                    <button type="button" @click="createManualModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-mono text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-xs shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                        Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
