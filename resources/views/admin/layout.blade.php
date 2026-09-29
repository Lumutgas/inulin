<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'INULIN OPS — Portal Operasional Lab' }}</title>
    
    <link rel="icon" type="image/png" href="{{ \App\Models\BusinessSetting::current()->faviconUrl() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        canvas: '#090D16',
                        sidebar: '#0C111D',
                        card: '#121822',
                        border: '#1E293B',
                        amber: {
                            glow: '#F59E0B',
                            core: '#0284C7',
                            light: '#38BDF8',
                        }
                    },
                    fontFamily: {
                        sans: ['Geist', 'sans-serif'],
                        heading: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #090D16;
            color: #E2E8F0;
            font-family: 'Geist', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        *:focus-visible {
            outline: 2px solid #F59E0B;
            outline-offset: 2px;
        }
        .star-filled {
            font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24 !important;
        }
        .star-empty {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 !important;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #090D16;
        }
        ::-webkit-scrollbar-thumb {
            background: #1E293B;
            border-radius: 9999px;
        }
    </style>
</head>
<body class="min-h-screen bg-[#090D16] text-slate-200 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        
        <!-- Sidebar Backdrop for Mobile -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/70 md:hidden backdrop-blur-sm"></div>

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed md:sticky top-0 left-0 z-50 h-screen w-64 shrink-0 bg-[#0C111D] border-r border-slate-800/80 flex flex-col justify-between p-5 transition-transform duration-200 ease-in-out overflow-y-auto">
            
            <div class="space-y-6">
                <!-- App Header / Brand -->
                <div class="flex items-center justify-between px-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ \App\Models\BusinessSetting::current()->logoUrl() }}" alt="Logo" class="h-8 w-8 object-contain rounded p-0.5 bg-black/40 border border-slate-800">
                        <div>
                            <div class="flex items-center gap-1.5 leading-none">
                                <span class="font-heading font-bold text-white tracking-tight text-base">INULIN</span>
                                <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-amber-950/80 text-amber-400 font-semibold border border-amber-800/40">OPS</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 font-mono">Sistem Bengkel OS</p>
                        </div>
                    </a>

                    <button type="button" @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex flex-col gap-1 text-[13px]">
                    <div class="px-2 py-1 text-[11px] font-mono uppercase tracking-wider text-slate-500">Operasional</div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.dashboard') ? 'text-amber-400' : 'text-slate-400' }}">space_dashboard</span>
                            <span>Dashboard</span>
                        </div>
                        @if(request()->routeIs('admin.dashboard'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <!-- Bookings -->
                    @php $pendingCount = \App\Models\Booking::where('status', 'pending')->count(); @endphp
                    <a href="{{ route('admin.bookings') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.bookings') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.bookings') ? 'text-amber-400' : 'text-slate-400' }}">receipt_long</span>
                            <span>Bookings</span>
                        </div>
                        @if($pendingCount > 0)
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-400 font-bold border border-amber-500/20">
                                {{ $pendingCount }} Pending
                            </span>
                        @endif
                    </a>

                    <!-- Calendar -->
                    <a href="{{ route('admin.calendar') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.calendar') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.calendar') ? 'text-amber-400' : 'text-slate-400' }}">calendar_today</span>
                            <span>Jadwal & Kalender</span>
                        </div>
                        @if(request()->routeIs('admin.calendar'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <!-- Services -->
                    <a href="{{ route('admin.services') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.services') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.services') ? 'text-amber-400' : 'text-slate-400' }}">tune</span>
                            <span>Katalog Layanan</span>
                        </div>
                        @if(request()->routeIs('admin.services'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <!-- Customers -->
                    <a href="{{ route('admin.customers') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.customers') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.customers') ? 'text-amber-400' : 'text-slate-400' }}">group</span>
                            <span>Data Pelanggan</span>
                        </div>
                        @if(request()->routeIs('admin.customers'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <!-- Revenue -->
                    <a href="{{ route('admin.revenue') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.revenue') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.revenue') ? 'text-amber-400' : 'text-slate-400' }}">account_balance_wallet</span>
                            <span>Laporan Keuangan</span>
                        </div>
                        @if(request()->routeIs('admin.revenue'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <div class="pt-4 pb-1 px-2 text-[11px] font-mono uppercase tracking-wider text-slate-500">Konfigurasi</div>

                    <!-- Content -->
                    <a href="{{ route('admin.content') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.content') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.content') ? 'text-amber-400' : 'text-slate-400' }}">feed</span>
                            <span>Konten Web (FAQ & Galeri)</span>
                        </div>
                        @if(request()->routeIs('admin.content'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('admin.settings') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.settings') ? 'text-amber-400' : 'text-slate-400' }}">settings</span>
                            <span>Pengaturan & Logo</span>
                        </div>
                        @if(request()->routeIs('admin.settings'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>

                    <!-- Audit Log -->
                    <a href="{{ route('admin.audit-logs') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs') ? 'bg-slate-800/90 text-white font-medium shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.audit-logs') ? 'text-amber-400' : 'text-slate-400' }}">history</span>
                            <span>Audit Log</span>
                        </div>
                        @if(request()->routeIs('admin.audit-logs'))
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Bottom Mini Status & Logout -->
            <div class="space-y-3 pt-4 border-t border-slate-800/80">
                <div class="p-3 bg-slate-900/60 rounded-xl border border-slate-800/80 flex flex-col gap-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-mono text-[11px]">Database Link</span>
                        <span class="text-emerald-400 font-mono text-[11px] flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Aktif
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono truncate">{{ auth()->user()->email ?? 'Administrator' }}</span>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg bg-slate-800/60 hover:bg-rose-950/40 text-slate-400 hover:text-rose-300 border border-slate-800 text-xs font-mono transition-colors">
                        <span class="material-symbols-outlined text-[16px]">logout</span>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- Top Control Bar -->
            <header class="sticky top-0 z-30 h-16 bg-[#090D16]/90 backdrop-blur border-b border-slate-800/80 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = true" class="md:hidden text-slate-400 hover:text-white p-1">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="flex items-center gap-2 text-xs font-mono text-slate-400">
                        <span>ADMIN</span>
                        <span>/</span>
                        <span class="text-slate-200 uppercase font-semibold">{{ $pageTitle ?? 'DASHBOARD' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-mono text-amber-400 hover:text-amber-300 bg-amber-950/40 border border-amber-800/40 px-2.5 py-1.5 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                        <span>Lihat Website</span>
                    </a>

                    <div class="h-4 w-px bg-slate-800"></div>

                    <div class="flex items-center gap-2 text-xs font-mono text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="hidden sm:inline">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
                
                <!-- Notification Flash Banners -->
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 text-sm flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-400">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-950/40 border border-rose-500/30 text-rose-200 text-sm flex items-center gap-3">
                        <span class="material-symbols-outlined text-rose-400">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
