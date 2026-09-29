@extends('admin.layout', ['pageTitle' => 'AUDIT LOG AKTIVITAS'])

@section('content')
<div class="space-y-6">

    <!-- Header, Filter & Purge All -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-lg text-white">Log Aktivitas & Jejak Audit Administratif</h2>
            <p class="text-xs text-slate-400 font-mono mt-0.5">Mencatat seluruh aksi modifikasi data, perubahan status booking, upload branding, dan sesi login.</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <!-- Purge All Button -->
            @if($logs->total() > 0)
                <form method="POST" action="{{ route('admin.audit-logs.clear') }}" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SEMUA riwayat audit log? Tindakan ini permanen.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2 rounded-lg bg-rose-950/60 hover:bg-rose-900/80 text-rose-300 border border-rose-800/50 text-xs font-mono flex items-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                        <span>Bersihkan Semua Log</span>
                    </button>
                </form>
            @endif

            <!-- Filter by Action -->
            <form method="GET" action="{{ route('admin.audit-logs') }}" class="flex items-center gap-2">
                <select name="action" onchange="this.form.submit()" class="bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-xs font-mono text-slate-300 focus:outline-none focus:border-amber-400">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
                @if(request('action'))
                    <a href="{{ route('admin.audit-logs') }}" class="p-2 rounded-lg bg-slate-800 text-slate-400 hover:text-white" title="Reset Filter">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0C111D] border-b border-slate-800 text-slate-400 font-mono uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Aktor / Admin</th>
                        <th class="p-4">Aksi</th>
                        <th class="p-4">Objek Terkait</th>
                        <th class="p-4">IP Address</th>
                        <th class="p-4">Detail Metadata</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 font-mono text-[11px]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#161F2C] transition-colors">
                            <td class="p-4 text-slate-400 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>

                            <td class="p-4 text-white whitespace-nowrap">
                                {{ $log->user ? $log->user->name : 'Sistem' }}
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-amber-950/60 text-amber-400 border border-amber-800/40 text-[10px] uppercase font-semibold">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td class="p-4 text-slate-300 whitespace-nowrap">
                                {{ $log->subject ?: '-' }}
                            </td>

                            <td class="p-4 text-slate-400 whitespace-nowrap">
                                {{ $log->ip ?: '-' }}
                            </td>

                            <td class="p-4 text-slate-400 max-w-xs truncate" title="{{ json_encode($log->metadata) }}">
                                @if(!empty($log->metadata))
                                    {{ json_encode($log->metadata) }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-4 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.audit-logs.delete', $log->id) }}" onsubmit="return confirm('Hapus entri audit log ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded bg-slate-800/80 hover:bg-rose-950/50 text-slate-400 hover:text-rose-400 transition-colors" title="Hapus Riwayat Ini">
                                        <span class="material-symbols-outlined text-[15px]">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500 font-mono">
                                Belum ada riwayat aktivitas administratif tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-800 bg-[#0C111D]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
