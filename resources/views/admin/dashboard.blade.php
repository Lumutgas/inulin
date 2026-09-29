@extends('admin.layout', ['pageTitle' => 'RINGKASAN OPERASIONAL'])

@section('content')
<div class="space-y-8">
    
    <!-- Top KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Bookings -->
        <div class="p-5 rounded-xl bg-[#121822] border border-slate-800 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-mono uppercase tracking-wider">Total Pesanan</span>
                <span class="material-symbols-outlined text-[20px] text-amber-400">receipt_long</span>
            </div>
            <div class="mt-4">
                <span class="font-mono text-3xl font-bold text-white">{{ $counts['total'] }}</span>
                <span class="text-[11px] text-slate-400 font-mono block mt-1">Sepanjang waktu</span>
            </div>
        </div>

        <!-- Pending Bookings -->
        <div class="p-5 rounded-xl bg-[#121822] border border-amber-900/40 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-mono uppercase tracking-wider text-amber-400">Menunggu Konfirmasi</span>
                <span class="material-symbols-outlined text-[20px] text-amber-400">pending_actions</span>
            </div>
            <div class="mt-4">
                <span class="font-mono text-3xl font-bold text-amber-400">{{ $counts['pending'] }}</span>
                <span class="text-[11px] text-slate-400 font-mono block mt-1">Perlu respon teknisi</span>
            </div>
        </div>

        <!-- Today Bookings -->
        <div class="p-5 rounded-xl bg-[#121822] border border-slate-800 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-mono uppercase tracking-wider">Jadwal Hari Ini</span>
                <span class="material-symbols-outlined text-[20px] text-amber-400">today</span>
            </div>
            <div class="mt-4">
                <span class="font-mono text-3xl font-bold text-white">{{ $counts['today'] }}</span>
                <span class="text-[11px] text-slate-400 font-mono block mt-1">Slot aktif hari ini</span>
            </div>
        </div>

        <!-- Realized Revenue -->
        <div class="p-5 rounded-xl bg-[#121822] border border-emerald-900/40 flex flex-col justify-between">
            <div class="flex items-center justify-between text-slate-400">
                <span class="text-xs font-mono uppercase tracking-wider text-emerald-400">Pendapatan Realized</span>
                <span class="material-symbols-outlined text-[20px] text-emerald-400">payments</span>
            </div>
            <div class="mt-4">
                <span class="font-mono text-2xl font-bold text-emerald-400">
                    Rp{{ number_format($revenue['realized'], 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-slate-400 font-mono block mt-1">
                    Hari ini: Rp{{ number_format($revenue['today'], 0, ',', '.') }}
                </span>
            </div>
        </div>

    </div>

    <!-- Status Breakdown Bar -->
    <div class="p-4 rounded-xl bg-[#0F141D] border border-slate-800/80 flex flex-wrap items-center justify-between gap-3 text-xs font-mono">
        <span class="text-slate-400 uppercase font-semibold">Distribusi Status:</span>
        <div class="flex flex-wrap items-center gap-3">
            <span class="px-2.5 py-1 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">
                Pending: {{ $counts['pending'] }}
            </span>
            <span class="px-2.5 py-1 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">
                Dikonfirmasi: {{ $counts['confirmed'] }}
            </span>
            <span class="px-2.5 py-1 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">
                Dikerjakan: {{ $counts['in_progress'] }}
            </span>
            <span class="px-2.5 py-1 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                Selesai: {{ $counts['completed'] }}
            </span>
            <span class="px-2.5 py-1 rounded bg-slate-800 text-slate-400">
                Dibatalkan: {{ $counts['cancelled'] }}
            </span>
        </div>
    </div>

    <!-- 2 Column Workspace: Upcoming Appointments & Recent Bookings -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Upcoming Appointments (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400 text-lg">event_upcoming</span>
                    <h2 class="font-heading font-bold text-base text-white">Jadwal Penanganan Mendatang</h2>
                </div>
                <a href="{{ route('admin.calendar') }}" class="text-xs font-mono text-amber-400 hover:text-amber-300">Lihat Kalender &rarr;</a>
            </div>

            <div class="rounded-xl border border-slate-800 bg-[#121822] divide-y divide-slate-800/80 overflow-hidden">
                @forelse($upcoming as $item)
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-[#161F2C] transition-colors">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-semibold text-white">{{ $item->customer_name }}</span>
                                <span class="text-xs text-slate-400 font-mono">({{ $item->device }})</span>
                            </div>
                            <div class="text-xs text-slate-400">
                                <span>{{ $item->service_name }}</span> &bull; 
                                <span class="text-amber-400 font-mono">{{ $item->os }} ({{ $item->os_version }})</span>
                            </div>
                            <div class="font-mono text-[11px] text-slate-500 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">schedule</span>
                                <span>{{ $item->starts_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if($item->status === 'pending')
                                <form method="POST" action="{{ route('admin.bookings.update', $item) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-mono text-xs font-semibold transition-colors">
                                        Konfirmasi
                                    </button>
                                </form>
                            @elseif($item->status === 'confirmed')
                                <form method="POST" action="{{ route('admin.bookings.update', $item) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-semibold transition-colors">
                                        Mulai Kerja
                                    </button>
                                </form>
                            @elseif($item->status === 'in_progress')
                                <form method="POST" action="{{ route('admin.bookings.update', $item) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-mono text-xs font-semibold transition-colors">
                                        Selesai
                                    </button>
                                </form>
                            @endif

                            <a href="https://wa.me/{{ $item->customer_whatsapp }}" target="_blank" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-emerald-400 transition-colors" title="Chat WhatsApp Pelanggan">
                                <span class="material-symbols-outlined text-[18px]">chat</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs font-mono">
                        Tidak ada antrean jadwal mendatang saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Recent Bookings List (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400 text-lg">history</span>
                    <h2 class="font-heading font-bold text-base text-white">Aktivitas Booking Terbaru</h2>
                </div>
                <a href="{{ route('admin.bookings') }}" class="text-xs font-mono text-amber-400 hover:text-amber-300">Semua Data &rarr;</a>
            </div>

            <div class="rounded-xl border border-slate-800 bg-[#121822] divide-y divide-slate-800/80 overflow-hidden">
                @forelse($recent as $item)
                    <div class="p-4 flex items-center justify-between gap-3 text-xs hover:bg-[#161F2C] transition-colors">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-white font-semibold">{{ $item->customer_name }}</span>
                                <span class="font-mono text-[10px] text-slate-500">#{{ substr($item->reference, 4, 8) }}</span>
                            </div>
                            <p class="text-slate-400 truncate max-w-[180px]">{{ $item->service_name }}</p>
                            <span class="font-mono text-amber-400 font-semibold block">Rp{{ number_format($item->service_price, 0, ',', '.') }}</span>
                        </div>

                        <div class="text-right space-y-1.5 shrink-0">
                            <!-- Status Chip -->
                            @if($item->status === 'pending')
                                <span class="inline-block px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/30 text-[10px] font-mono">
                                    Pending
                                </span>
                            @elseif($item->status === 'confirmed')
                                <span class="inline-block px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/30 text-[10px] font-mono">
                                    Dikonfirmasi
                                </span>
                            @elseif($item->status === 'in_progress')
                                <span class="inline-block px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/30 text-[10px] font-mono">
                                    Dikerjakan
                                </span>
                            @elseif($item->status === 'completed')
                                <span class="inline-block px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 text-[10px] font-mono">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-block px-2 py-0.5 rounded bg-slate-800 text-slate-400 text-[10px] font-mono">
                                    Batal
                                </span>
                            @endif

                            <!-- Payment Chip -->
                            <div>
                                <span class="text-[10px] font-mono {{ $item->payment_status === 'paid' ? 'text-emerald-400' : 'text-slate-500' }}">
                                    • {{ $item->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs font-mono">
                        Belum ada riwayat pesanan tercatat di database.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
