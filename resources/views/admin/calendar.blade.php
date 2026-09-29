@extends('admin.layout', ['pageTitle' => 'JADWAL & KALENDER WORKSHOP'])

@section('content')
<div class="space-y-6">

    <!-- Header & Mode Switcher Bar -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Current Period & Navigation -->
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1 bg-[#070A0F] border border-slate-800 rounded-lg p-1">
                @php
                    $prevDate = match($mode) {
                        'day' => $currentDate->copy()->subDay()->toDateString(),
                        'week' => $currentDate->copy()->subWeek()->toDateString(),
                        default => $currentDate->copy()->subMonth()->toDateString(),
                    };
                    $nextDate = match($mode) {
                        'day' => $currentDate->copy()->addDay()->toDateString(),
                        'week' => $currentDate->copy()->addWeek()->toDateString(),
                        default => $currentDate->copy()->addMonth()->toDateString(),
                    };
                @endphp
                <a href="{{ route('admin.calendar', ['mode' => $mode, 'date' => $prevDate]) }}" class="p-1.5 rounded hover:bg-slate-800 text-slate-400 hover:text-white" title="Sebelumnya">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
                <a href="{{ route('admin.calendar', ['mode' => $mode, 'date' => now()->toDateString()]) }}" class="px-2.5 py-1 rounded hover:bg-slate-800 text-xs font-mono text-slate-300 hover:text-white">
                    Hari Ini
                </a>
                <a href="{{ route('admin.calendar', ['mode' => $mode, 'date' => $nextDate]) }}" class="p-1.5 rounded hover:bg-slate-800 text-slate-400 hover:text-white" title="Berikutnya">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            </div>

            <h2 class="font-heading font-bold text-lg text-white">
                @if($mode === 'day')
                    {{ $currentDate->translatedFormat('l, d F Y') }}
                @elseif($mode === 'week')
                    Minggu: {{ $startRange->translatedFormat('d M') }} - {{ $endRange->translatedFormat('d M Y') }}
                @else
                    {{ $currentDate->translatedFormat('F Y') }}
                @endif
            </h2>
        </div>

        <!-- Mode Toggle Tabs (Day / Week / Month) -->
        <div class="flex items-center gap-2">
            <div class="bg-[#070A0F] border border-slate-800 rounded-lg p-1 flex items-center font-mono text-xs">
                <a href="{{ route('admin.calendar', ['mode' => 'day', 'date' => $currentDate->toDateString()]) }}" class="px-3 py-1.5 rounded {{ $mode === 'day' ? 'bg-[#F59E0B] text-slate-950 font-bold' : 'text-slate-400 hover:text-white' }}">
                    Harian
                </a>
                <a href="{{ route('admin.calendar', ['mode' => 'week', 'date' => $currentDate->toDateString()]) }}" class="px-3 py-1.5 rounded {{ $mode === 'week' ? 'bg-[#F59E0B] text-slate-950 font-bold' : 'text-slate-400 hover:text-white' }}">
                    Mingguan
                </a>
                <a href="{{ route('admin.calendar', ['mode' => 'month', 'date' => $currentDate->toDateString()]) }}" class="px-3 py-1.5 rounded {{ $mode === 'month' ? 'bg-[#F59E0B] text-slate-950 font-bold' : 'text-slate-400 hover:text-white' }}">
                    Bulanan
                </a>
            </div>

            <!-- Date Picker Jump -->
            <form method="GET" action="{{ route('admin.calendar') }}" class="hidden sm:block">
                <input type="hidden" name="mode" value="{{ $mode }}">
                <input type="date" name="date" value="{{ $currentDate->toDateString() }}" onchange="this.form.submit()" class="bg-[#070A0F] border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs font-mono text-slate-300">
            </form>
        </div>

    </div>

    <!-- CALENDAR CONTENT BY MODE -->

    @if($mode === 'month')
        <!-- ================= MONTH VIEW ================= -->
        <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
            <!-- Day of Week Header -->
            <div class="grid grid-cols-7 bg-[#0C111D] border-b border-slate-800 text-center font-mono text-xs text-slate-400 font-semibold py-3">
                <span>SENIN</span>
                <span>SELASA</span>
                <span>RABU</span>
                <span>KAMIS</span>
                <span>JUMAT</span>
                <span>SABTU</span>
                <span class="text-rose-400">MINGGU</span>
            </div>

            <!-- Calendar Days Grid -->
            @php
                $dayIterator = $startRange->copy();
                $todayDate = now()->toDateString();
            @endphp
            <div class="grid grid-cols-7 divide-x divide-y divide-slate-800/60 text-xs">
                @while($dayIterator <= $endRange)
                    @php
                        $dayStr = $dayIterator->toDateString();
                        $isCurrentMonth = $dayIterator->month === $currentDate->month;
                        $isToday = $dayStr === $todayDate;
                        $dayBookings = $bookings->filter(fn($b) => $b->starts_at->toDateString() === $dayStr);
                    @endphp
                    <div class="min-h-[110px] p-2 flex flex-col justify-between transition-colors {{ $isCurrentMonth ? 'bg-[#121822]' : 'bg-[#070A0F]/50 text-slate-600' }} {{ $isToday ? 'ring-1 ring-inset ring-[#F59E0B]' : '' }}">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold {{ $isToday ? 'text-[#F59E0B]' : ($isCurrentMonth ? 'text-slate-300' : 'text-slate-600') }}">
                                {{ $dayIterator->day }}
                            </span>
                            @if($dayBookings->count() > 0)
                                <span class="font-mono text-[10px] px-1 rounded bg-slate-800 text-amber-400">
                                    {{ $dayBookings->count() }}
                                </span>
                            @endif
                        </div>

                        <!-- Bookings list on this day -->
                        <div class="space-y-1 my-1">
                            @foreach($dayBookings->take(2) as $b)
                                <div class="p-1 rounded text-[10px] font-mono truncate {{ 
                                    $b->status === 'completed' ? 'bg-emerald-950/60 text-emerald-300 border border-emerald-800/40' : (
                                    $b->status === 'in_progress' ? 'bg-amber-950/60 text-amber-300 border border-amber-800/40' : (
                                    $b->status === 'confirmed' ? 'bg-amber-950/60 text-amber-300 border border-amber-800/40' : 'bg-amber-950/60 text-amber-300 border border-amber-800/40'
                                )) }}" title="{{ $b->starts_at->format('H:i') }} - {{ $b->customer_name }} ({{ $b->service_name }})">
                                    {{ $b->starts_at->format('H:i') }} {{ $b->customer_name }}
                                </div>
                            @endforeach
                            @if($dayBookings->count() > 2)
                                <span class="text-[9px] font-mono text-slate-500 block">+{{ $dayBookings->count() - 2 }} lainnya</span>
                            @endif
                        </div>

                        <!-- Quick link to day view -->
                        <div class="text-right">
                            <a href="{{ route('admin.calendar', ['mode' => 'day', 'date' => $dayStr]) }}" class="text-[10px] font-mono text-slate-500 hover:text-amber-400">
                                Buka &rarr;
                            </a>
                        </div>
                    </div>
                    @php $dayIterator->addDay(); @endphp
                @endwhile
            </div>
        </div>

    @elseif($mode === 'week')
        <!-- ================= WEEK VIEW ================= -->
        <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
            @php
                $weekIterator = $startRange->copy();
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-7 divide-y md:divide-y-0 md:divide-x divide-slate-800 text-xs">
                @for($i = 0; $i < 7; $i++)
                    @php
                        $dayStr = $weekIterator->toDateString();
                        $isToday = $dayStr === now()->toDateString();
                        $dayBookings = $bookings->filter(fn($b) => $b->starts_at->toDateString() === $dayStr);
                    @endphp
                    <div class="p-4 flex flex-col gap-3 min-h-[350px] {{ $isToday ? 'bg-amber-950/10' : '' }}">
                        <div class="pb-2 border-b border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="font-mono text-[10px] text-slate-500 uppercase">{{ $weekIterator->translatedFormat('l') }}</span>
                                <h4 class="font-mono font-bold text-sm {{ $isToday ? 'text-[#F59E0B]' : 'text-white' }}">{{ $weekIterator->translatedFormat('d M') }}</h4>
                            </div>
                            <span class="font-mono text-[11px] text-slate-400">{{ $dayBookings->count() }} Slot</span>
                        </div>

                        <div class="space-y-2 flex-1">
                            @forelse($dayBookings as $b)
                                <div class="p-2.5 rounded-lg bg-[#070A0F] border border-slate-800 text-xs space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono font-bold text-amber-400">{{ $b->starts_at->format('H:i') }}</span>
                                        <span class="font-mono text-[10px] uppercase {{ $b->status === 'completed' ? 'text-emerald-400' : ($b->status === 'in_progress' ? 'text-amber-400' : 'text-amber-400') }}">
                                            • {{ $b->status }}
                                        </span>
                                    </div>
                                    <p class="font-medium text-white truncate">{{ $b->customer_name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $b->service_name }}</p>
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-600 text-[11px] font-mono">
                                    Tidak ada jadwal
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @php $weekIterator->addDay(); @endphp
                @endfor
            </div>
        </div>

    @else
        <!-- ================= DAY VIEW ================= -->
        <div class="rounded-xl border border-slate-800 bg-[#121822] p-6 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div>
                    <h3 class="font-heading font-bold text-lg text-white">Daftar Slot Penanganan Lab</h3>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">Jam Buka: {{ substr($settings->opening_time, 0, 5) }} - {{ substr($settings->closing_time, 0, 5) }} WIB</p>
                </div>
                <span class="font-mono text-xs px-3 py-1 rounded bg-[#070A0F] border border-slate-800 text-amber-400 font-semibold">
                    Total: {{ $bookings->count() }} Pesanan
                </span>
            </div>

            <!-- Time Slots Matrix -->
            <div class="space-y-3">
                @php
                    $slots = ['09:00', '10:30', '13:00', '14:30', '16:00', '17:00'];
                @endphp

                @foreach($slots as $slotTime)
                    @php
                        $slotBookings = $bookings->filter(fn($b) => $b->starts_at->format('H:i') === $slotTime);
                    @endphp
                    <div class="p-4 rounded-xl bg-[#070A0F] border border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <span class="font-mono font-bold text-sm text-[#F59E0B] w-16">{{ $slotTime }}</span>
                            <div class="h-8 w-px bg-slate-800 hidden md:block"></div>
                            
                            @if($slotBookings->isNotEmpty())
                                <div class="space-y-1">
                                    @foreach($slotBookings as $b)
                                        <div class="flex flex-wrap items-center gap-2 text-xs">
                                            <span class="font-semibold text-white font-mono">{{ $b->customer_name }}</span>
                                            <span class="text-slate-400">({{ $b->device }} - {{ $b->os }})</span>
                                            <span class="text-amber-400 font-medium">&bull; {{ $b->service_name }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-mono {{ $b->status === 'completed' ? 'bg-emerald-950/60 text-emerald-400' : 'bg-amber-950/60 text-amber-400' }}">
                                                {{ $b->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-500 font-mono text-xs">Slot Kosong / Tersedia</span>
                            @endif
                        </div>

                        <div>
                            @if($slotBookings->isNotEmpty())
                                <span class="font-mono text-[11px] text-emerald-400">Terjadwal</span>
                            @else
                                <span class="font-mono text-[11px] text-slate-500">Tersedia {{ $settings->max_bookings_per_slot }} Kuota</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
