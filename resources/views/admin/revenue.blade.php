@extends('admin.layout', ['pageTitle' => 'LAPORAN KEUANGAN'])

@section('content')
<div class="space-y-8">

    <!-- KPI Revenue Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Hari Ini -->
        <div class="p-5 rounded-xl bg-[#121822] border border-slate-800 flex flex-col justify-between">
            <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Hari Ini</span>
            <div class="mt-4">
                <span class="font-mono text-2xl font-bold text-white">
                    Rp{{ number_format($todayRevenue, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-slate-500 font-mono block mt-1">Realized hari ini</span>
            </div>
        </div>

        <!-- Minggu Ini -->
        <div class="p-5 rounded-xl bg-[#121822] border border-slate-800 flex flex-col justify-between">
            <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Minggu Ini</span>
            <div class="mt-4">
                <span class="font-mono text-2xl font-bold text-white">
                    Rp{{ number_format($weekRevenue, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-slate-500 font-mono block mt-1">Realized 7 hari berjalan</span>
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="p-5 rounded-xl bg-[#121822] border border-slate-800 flex flex-col justify-between">
            <span class="text-xs font-mono uppercase tracking-wider text-slate-400">Bulan Ini</span>
            <div class="mt-4">
                <span class="font-mono text-2xl font-bold text-amber-400">
                    Rp{{ number_format($monthRevenue, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-slate-500 font-mono block mt-1">{{ date('F Y') }}</span>
            </div>
        </div>

        <!-- Total Sepanjang Waktu -->
        <div class="p-5 rounded-xl bg-[#121822] border border-emerald-900/40 flex flex-col justify-between">
            <span class="text-xs font-mono uppercase tracking-wider text-emerald-400">Total Akumulasi Realized</span>
            <div class="mt-4">
                <span class="font-mono text-3xl font-bold text-emerald-400">
                    Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-slate-500 font-mono block mt-1">Status Selesai & Lunas</span>
            </div>
        </div>

    </div>

    <!-- Pipeline & Accounting Rules Note -->
    <div class="p-4 rounded-xl bg-[#0F141D] border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-mono">
        <div class="flex items-center gap-2 text-slate-300">
            <span class="material-symbols-outlined text-amber-400 text-[18px]">verified</span>
            <span>Prinsip Keuangan: Pendapatan hanya diakui jika pesanan telah selesai dan pembayaran telah lunas.</span>
        </div>
        <div class="flex items-center gap-4 text-slate-400">
            <span>Pipeline Berjalan: <strong class="text-white">Rp{{ number_format($pipelineRevenue, 0, ',', '.') }}</strong></span>
            <span>Piutang Selesai: <strong class="text-amber-400">Rp{{ number_format($unpaidCompletedRevenue, 0, ',', '.') }}</strong></span>
        </div>
    </div>

    <!-- Itemized Realized Transactions Ledger -->
    <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden space-y-4">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-base text-white">Buku Transaksi Realized</h3>
                <p class="text-xs text-slate-400 font-mono mt-0.5">Daftar pengerjaan yang sudah berstatus selesai dan lunas.</p>
            </div>
            <span class="font-mono text-xs text-slate-400">{{ $transactions->total() }} Transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0C111D] border-b border-slate-800 text-slate-400 font-mono uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-4">Waktu Selesai</th>
                        <th class="p-4">Kode Referensi</th>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4">Layanan</th>
                        <th class="p-4">Nominal</th>
                        <th class="p-4 text-right">Status Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-[#161F2C] transition-colors">
                            <td class="p-4 font-mono text-slate-300 whitespace-nowrap">
                                {{ $t->completed_at ? $t->completed_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                            </td>

                            <td class="p-4 font-mono font-semibold text-amber-400 whitespace-nowrap">
                                #{{ $t->reference }}
                            </td>

                            <td class="p-4 text-white font-medium">
                                {{ $t->customer_name }}
                            </td>

                            <td class="p-4 text-slate-300">
                                {{ $t->service_name }}
                            </td>

                            <td class="p-4 font-mono font-bold text-emerald-400 whitespace-nowrap">
                                Rp{{ number_format($t->service_price, 0, ',', '.') }}
                            </td>

                            <td class="p-4 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-emerald-950/60 text-emerald-400 border border-emerald-800/40 font-mono text-[10px] font-semibold">
                                    ✓ LUNAS
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 font-mono">
                                Belum ada transaksi yang diselesaikan dan ditandai lunas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-800 bg-[#0C111D]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
