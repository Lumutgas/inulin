@extends('admin.layout', ['pageTitle' => 'DATABASE PELANGGAN'])

@section('content')
<div class="space-y-6">

    <!-- Header & Search Toolbar -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-lg text-white">Database Pelanggan Lab</h2>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Dihimpun otomatis dari pesanan yang masuk dengan nomor WhatsApp ternormalisasi.</p>
        </div>

        <form method="GET" action="{{ route('admin.customers') }}" class="w-full sm:w-72">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor WA..." class="w-full bg-[#070A0F] border border-slate-800 focus:border-[#F59E0B] rounded-lg pl-9 pr-3.5 py-2 text-xs text-white placeholder-slate-500">
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0C111D] border-b border-slate-800 text-slate-400 font-mono uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-4">Nama Pelanggan</th>
                        <th class="p-4">Nomor WhatsApp</th>
                        <th class="p-4">Total Pesanan</th>
                        <th class="p-4">Total Transaksi Realized</th>
                        <th class="p-4">Pesanan Terakhir</th>
                        <th class="p-4 text-right">Kontak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-[#161F2C] transition-colors">
                            <td class="p-4 font-semibold text-white">
                                {{ $customer->name }}
                            </td>

                            <td class="p-4 font-mono text-slate-300">
                                {{ $customer->whatsapp }}
                            </td>

                            <td class="p-4 font-mono text-amber-400 font-semibold">
                                {{ $customer->bookings_count }}x Booking
                            </td>

                            <td class="p-4 font-mono font-bold text-emerald-400">
                                Rp{{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="p-4 font-mono text-slate-400 text-[11px]">
                                {{ $customer->bookings_max_starts_at ? \Carbon\Carbon::parse($customer->bookings_max_starts_at)->translatedFormat('d M Y') : '-' }}
                            </td>

                            <td class="p-4 text-right">
                                <a href="https://wa.me/{{ $customer->whatsapp }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-950/60 hover:bg-emerald-900/60 text-emerald-400 border border-emerald-800/40 text-xs font-mono transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">chat</span>
                                    <span>Chat WA</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 font-mono">
                                Belum ada database pelanggan tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-800 bg-[#0C111D]">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
