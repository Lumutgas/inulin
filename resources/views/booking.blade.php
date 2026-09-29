@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14" x-data="{
    // Services map from database
    services: {
        @foreach($allServices as $srv)
            '{{ $srv->id }}': {
                id: '{{ $srv->id }}',
                name: '{{ addslashes($srv->name) }}',
                price: {{ $srv->price }},
                formattedPrice: 'Rp{{ number_format($srv->price, 0, ',', '.') }}',
                duration: {{ $srv->duration ?: 60 }}
            },
        @endforeach
    },
    serviceId: '{{ old('service_id', $service->id) }}',
    name: '{{ old('name', '') }}',
    whatsapp: '{{ old('whatsapp', '') }}',
    device: '{{ old('device', 'Laptop') }}',
    os: '{{ old('os', 'Windows') }}',
    windowsVersion: '{{ old('windows_version', 'Windows 11 Pro') }}',
    linuxDistro: '{{ old('linux_distro', 'Ubuntu LTS 24.04') }}',
    date: '{{ old('date', date('Y-m-d')) }}',
    time: '{{ old('time', '09:00') }}',
    notes: '{{ old('notes', '') }}',
    paymentMethod: '{{ old('payment_method', 'cash') }}',
    paymentTiming: '{{ old('payment_timing', 'direct') }}',
    qrisEnlargeModal: false,
    proofPreview: null,
    proofFileName: '',
    copied: false,

    handleProofUpload(event) {
        const file = event.target.files[0];
        if (file) {
            this.proofFileName = file.name;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.proofPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            this.proofPreview = null;
            this.proofFileName = '';
        }
    },

    get currentService() {
        return this.services[this.serviceId] || this.services['{{ $service->id }}'];
    },

    get osDisplayName() {
        if (this.os === 'Linux') {
            return 'Linux (' + this.linuxDistro + ')';
        }
        return 'Windows (' + this.windowsVersion + ')';
    },

    get formattedSchedule() {
        if (!this.date) return '-';
        return this.date + ' ' + (this.time || '09:00') + ' WIB';
    },

    get paymentMethodLabel() {
        if (this.paymentMethod === 'cash') {
            return 'Tunai di Workshop / Lab';
        }
        return this.paymentTiming === 'paylater'
            ? 'QRIS Paylater (Bayar Setelah Jadi / Pasca Servis)'
            : 'QRIS Langsung (Bukti Transfer Terlampir)';
    },

    get generatedWaMessage() {
        const targetName = this.name.trim() || '[Nama Pemesan]';
        const targetWa = this.whatsapp.trim() || '[WhatsApp]';
        const targetNotes = this.notes.trim() || '-';
        return 'Halo INULIN, saya mau booking instal ulang:\n\n' +
            'Nama: ' + targetName + '\n' +
            'WhatsApp: ' + targetWa + '\n' +
            'Perangkat: ' + this.device + '\n' +
            'OS: ' + this.osDisplayName + '\n' +
            'Jadwal: ' + this.formattedSchedule + '\n' +
            'Catatan: ' + targetNotes + '\n' +
            'Layanan: ' + this.currentService.name + '\n' +
            'Total: ' + this.currentService.formattedPrice + '\n' +
            'Metode Bayar: ' + this.paymentMethodLabel;
    },

    copySnippet() {
        navigator.clipboard.writeText(this.generatedWaMessage);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}">

    <!-- Breadcrumb & Header Title -->
    <div class="mb-10 max-w-3xl">
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-3 font-mono">
            <a href="{{ route('home') }}" class="hover:text-amber-400">HOME</a>
            <span>/</span>
            <a href="{{ route('home') }}#layanan" class="hover:text-amber-400">LAYANAN</a>
            <span>/</span>
            <span class="text-amber-400 uppercase font-semibold">FORMULIR BOOKING</span>
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-white mb-2 font-heading">
            Formulir Booking Instalasi OS
        </h1>
        <p class="text-sm sm:text-base text-slate-400 leading-relaxed">
            Pilih konfigurasi sistem operasi, tentukan jam kedatangan ke workshop, dan data pesanan Anda tersimpan otomatis di database sebelum dialihkan ke WhatsApp.
        </p>

        <!-- Stepper Indicator -->
        <div class="flex items-center gap-3 mt-6 text-xs font-mono font-medium">
            <div class="flex items-center gap-2 text-[#F59E0B]">
                <span class="w-5 h-5 rounded-full bg-[#F59E0B] text-slate-950 flex items-center justify-center text-[11px] font-bold">1</span>
                <span>Konfigurasi Form</span>
            </div>
            <span class="text-slate-600">—</span>
            <div class="flex items-center gap-2 text-slate-400">
                <span class="w-5 h-5 rounded-full bg-[#18212E] border border-[#222F3E] flex items-center justify-center text-[11px]">2</span>
                <span>Review Pesanan</span>
            </div>
            <span class="text-slate-600">—</span>
            <div class="flex items-center gap-2 text-slate-400">
                <span class="w-5 h-5 rounded-full bg-[#18212E] border border-[#222F3E] flex items-center justify-center text-[11px]">3</span>
                <span>Kirim WhatsApp</span>
            </div>
        </div>
    </div>

    <!-- Error Summary if any -->
    @if($errors->any())
        <div class="mb-8 p-4 rounded-xl bg-rose-950/40 border border-rose-600/40 text-rose-200 text-sm">
            <div class="flex items-center gap-2 font-semibold mb-2">
                <span class="material-symbols-outlined text-rose-400">error</span>
                <span>Harap perbaiki kesalahan berikut:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Layout: 2 Columns Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Form Controls (7 cols) -->
        <form class="lg:col-span-7 flex flex-col gap-6" method="POST" action="{{ route('bookings.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Hidden input for dynamic service selection -->
            <input type="hidden" name="service_id" :value="serviceId">

            <!-- Section 01: Data Pemesan -->
            <div class="bg-[#121822] border border-[#222F3E] rounded-xl p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#222F3E]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-[#070A0F] border border-amber-800/40">01</span>
                        <div>
                            <h2 class="text-base font-semibold text-white font-heading">Data Pemesan</h2>
                            <p class="text-xs text-slate-400">Identitas pemilik unit untuk pencatatan nota serah terima.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-medium text-slate-300">
                            Nama Lengkap <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" x-model="name" required placeholder="cth. Budi Pratama" class="w-full bg-[#070A0F] border border-[#222F3E] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-lg px-3.5 py-2.5 text-sm text-white placeholder-slate-600 transition-colors">
                        <span class="text-[11px] text-slate-500 block">Nama pemilik unit saat pengerjaan.</span>
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="space-y-1.5">
                        <label for="whatsapp" class="block text-xs font-medium text-slate-300">
                            Nomor WhatsApp <span class="text-rose-400">*</span>
                        </label>
                        <input type="tel" id="whatsapp" name="whatsapp" x-model="whatsapp" required placeholder="08xxxxxxxxxx" class="w-full bg-[#070A0F] border border-[#222F3E] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-lg px-3.5 py-2.5 text-sm font-mono text-white placeholder-slate-600 transition-colors">
                        <span class="text-[11px] text-slate-500 block">Digunakan untuk konfirmasi & update status.</span>
                    </div>
                </div>
            </div>

            <!-- Section 02: Perangkat & Sistem Operasi -->
            <div class="bg-[#121822] border border-[#222F3E] rounded-xl p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#222F3E]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-[#070A0F] border border-amber-800/40">02</span>
                        <div>
                            <h2 class="text-base font-semibold text-white font-heading">Perangkat & Sistem Operasi</h2>
                            <p class="text-xs text-slate-400">Pilih jenis perangkat dan sistem operasi yang diinginkan.</p>
                        </div>
                    </div>
                </div>

                <!-- Tipe Perangkat -->
                <div class="mb-6">
                    <label class="block text-xs font-medium text-slate-300 mb-2.5">Tipe Perangkat <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Laptop -->
                        <div @click="device = 'Laptop'" :class="device === 'Laptop' ? 'border-[#F59E0B] bg-amber-950/20' : 'border-[#222F3E] bg-[#070A0F] hover:border-slate-600'" class="cursor-pointer p-4 rounded-lg border transition-all flex items-start gap-3">
                            <span class="material-symbols-outlined text-slate-400 text-2xl mt-0.5" :class="device === 'Laptop' ? 'text-amber-400' : ''">laptop</span>
                            <div>
                                <div class="text-xs font-semibold text-white flex items-center gap-2">
                                    <span>Laptop / Notebook</span>
                                    <span x-show="device === 'Laptop'" class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1">Driver baterai, touchpad OEM, & profil pendinginan.</p>
                            </div>
                        </div>

                        <!-- PC Desktop -->
                        <div @click="device = 'PC'" :class="device === 'PC' ? 'border-[#F59E0B] bg-amber-950/20' : 'border-[#222F3E] bg-[#070A0F] hover:border-slate-600'" class="cursor-pointer p-4 rounded-lg border transition-all flex items-start gap-3">
                            <span class="material-symbols-outlined text-slate-400 text-2xl mt-0.5" :class="device === 'PC' ? 'text-amber-400' : ''">desktop_windows</span>
                            <div>
                                <div class="text-xs font-semibold text-white flex items-center gap-2">
                                    <span>PC Desktop</span>
                                    <span x-show="device === 'PC'" class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1">Driver GPU diskrit, BIOS tuning, & chipset PCIe.</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="device" :value="device">
                </div>

                <!-- OS Family Selection Tabs -->
                <div class="mb-5">
                    <label class="block text-xs font-medium text-slate-300 mb-2.5">Keluarga Sistem Operasi <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="os = 'Windows'" :class="os === 'Windows' ? 'border-[#F59E0B] bg-amber-950/20 text-white' : 'border-[#222F3E] bg-[#070A0F] text-slate-400 hover:text-white'" class="p-3.5 rounded-lg border flex items-center justify-between transition-all">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[20px]" :class="os === 'Windows' ? 'text-[#F59E0B]' : ''">window</span>
                                <div class="text-left">
                                    <p class="text-xs font-semibold">Microsoft Windows</p>
                                    <p class="text-[10px] text-slate-500 font-mono">10 / 11 Clean</p>
                                </div>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full border flex items-center justify-center" :class="os === 'Windows' ? 'border-[#F59E0B]' : 'border-slate-600'">
                                <span x-show="os === 'Windows'" class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                            </span>
                        </button>

                        <button type="button" @click="os = 'Linux'" :class="os === 'Linux' ? 'border-[#F59E0B] bg-amber-950/20 text-white' : 'border-[#222F3E] bg-[#070A0F] text-slate-400 hover:text-white'" class="p-3.5 rounded-lg border flex items-center justify-between transition-all">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[20px]" :class="os === 'Linux' ? 'text-amber-400' : ''">terminal</span>
                                <div class="text-left">
                                    <p class="text-xs font-semibold">GNU / Linux</p>
                                    <p class="text-[10px] text-slate-500 font-mono">Ubuntu / Mint / Kali</p>
                                </div>
                            </div>
                            <span class="w-3.5 h-3.5 rounded-full border flex items-center justify-center" :class="os === 'Linux' ? 'border-[#F59E0B]' : 'border-slate-600'">
                                <span x-show="os === 'Linux'" class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                            </span>
                        </button>
                    </div>
                    <input type="hidden" name="os" :value="os">
                </div>

                <!-- Conditional OS Version Dropdown -->
                <div class="p-4 rounded-lg bg-[#070A0F] border border-[#222F3E]">
                    <!-- If Windows Selected -->
                    <div x-show="os === 'Windows'" class="space-y-2">
                        <label for="windows_version" class="block text-xs font-medium text-slate-300">
                            Pilihan Versi Windows <span class="text-rose-400">*</span>
                        </label>
                        <select id="windows_version" name="windows_version" x-model="windowsVersion" class="w-full bg-[#121822] border border-[#222F3E] rounded-md px-3.5 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="Windows 11 Pro">Windows 11 Pro 64-bit (23H2 / 24H2 Ready) — Rekomendasi</option>
                            <option value="Windows 11 Home">Windows 11 Home 64-bit</option>
                            <option value="Windows 10 Pro">Windows 10 Pro 64-bit (Paling Stabil)</option>
                            <option value="Windows 10 Enterprise LTSC">Windows 10 Enterprise LTSC (Sangat Ringan, No Bloatware)</option>
                            <option value="Other">Lainnya (Sebutkan pada catatan)</option>
                        </select>
                        <p class="text-[11px] text-slate-500">Termasuk driver pabrikan resmi, redistributables C++, dan aktivasi utility dasar.</p>
                    </div>

                    <!-- If Linux Selected -->
                    <div x-show="os === 'Linux'" x-cloak class="space-y-2">
                        <label for="linux_distro" class="block text-xs font-medium text-slate-300">
                            Pilihan Distribusi Linux <span class="text-rose-400">*</span>
                        </label>
                        <select id="linux_distro" name="linux_distro" x-model="linuxDistro" class="w-full bg-[#121822] border border-[#222F3E] rounded-md px-3.5 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="Ubuntu LTS 24.04">Ubuntu LTS 24.04 (Stabil Dev & Server)</option>
                            <option value="Debian 12 Bookworm">Debian 12 'Bookworm' (Rock-solid)</option>
                            <option value="Linux Mint 22">Linux Mint 22 (Desktop Familiar)</option>
                            <option value="Fedora Workstation 40">Fedora Workstation 40 (Latest GNOME)</option>
                            <option value="Kali Linux 2024">Kali Linux (Security & Lab)</option>
                            <option value="Arch Linux">Arch Linux (Base install)</option>
                            <option value="Other">Distro Lainnya (Sebutkan pada catatan)</option>
                        </select>
                        <p class="text-[11px] text-slate-500">Tersedia opsi Single-Boot atau Dual-Boot berdampingan dengan Windows.</p>
                    </div>
                </div>
            </div>

            <!-- Section 03: Jadwal & Jam Kedatangan -->
            <div class="bg-[#121822] border border-[#222F3E] rounded-xl p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#222F3E]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-[#070A0F] border border-amber-800/40">03</span>
                        <div>
                            <h2 class="text-base font-semibold text-white font-heading">Jadwal & Jam Kedatangan</h2>
                            <p class="text-xs text-slate-400">Tentukan waktu kunjungan ke workshop atau drop-off unit.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div class="space-y-1.5">
                        <label for="date" class="block text-xs font-medium text-slate-300">
                            Tanggal Booking <span class="text-rose-400">*</span>
                        </label>
                        <input type="date" id="date" name="date" x-model="date" min="{{ date('Y-m-d') }}" required class="w-full bg-[#070A0F] border border-[#222F3E] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-lg px-3.5 py-2.5 text-xs font-mono text-white transition-colors">
                        <span class="text-[11px] text-slate-500">Operasional: Senin - Sabtu (Minggu Libur)</span>
                    </div>

                    <div class="p-3.5 rounded-lg bg-[#070A0F] border border-[#222F3E] flex flex-col justify-center">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                            <span class="material-symbols-outlined text-[16px] text-amber-400">schedule</span>
                            <span>Jam Buka Workshop</span>
                        </div>
                        <p class="text-xs font-mono text-white mt-1">{{ substr($settings->opening_time, 0, 5) }} - {{ substr($settings->closing_time, 0, 5) }} WIB</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Konfirmasi cepat via WhatsApp teknisi.</p>
                    </div>
                </div>

                <!-- Slot Jam Kedatangan -->
                <div class="space-y-2">
                    <label class="block text-xs font-medium text-slate-300">Pilih Slot Jam Kedatangan <span class="text-rose-400">*</span></label>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        @foreach(['09:00', '10:30', '13:00', '14:30', '16:00'] as $slot)
                            <button type="button" @click="time = '{{ $slot }}'" :class="time === '{{ $slot }}' ? 'border-[#F59E0B] bg-amber-950/30 text-[#F59E0B]' : 'border-[#222F3E] bg-[#070A0F] text-slate-300 hover:border-slate-600'" class="p-2.5 rounded-lg border text-center transition-all flex flex-col items-center">
                                <span class="text-xs font-mono font-semibold">{{ $slot }}</span>
                                <span class="text-[10px] text-slate-500 font-mono mt-0.5">Tersedia</span>
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="time" :value="time">
                </div>
            </div>

            <!-- Section 04: Catatan Khusus -->
            <div class="bg-[#121822] border border-[#222F3E] rounded-xl p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#222F3E]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-[#070A0F] border border-amber-800/40">04</span>
                        <div>
                            <h2 class="text-base font-semibold text-white font-heading">Catatan Khusus (Opsional)</h2>
                            <p class="text-xs text-slate-400">Keluhan kendala awal, permohonan partisi tertentu, atau software tambahan.</p>
                        </div>
                    </div>
                </div>

                <textarea id="notes" name="notes" x-model="notes" rows="3" placeholder="Contoh: Laptop sering blue screen, tolong bagi drive C 150GB dan sisanya drive D, dll." class="w-full bg-[#070A0F] border border-[#222F3E] focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-lg p-3 text-xs sm:text-sm text-white placeholder-slate-600 transition-colors"></textarea>
            </div>

            <!-- Section 05: Metode Pembayaran (Tunai atau QRIS) -->
            <div class="bg-[#121822] border border-[#222F3E] rounded-xl p-6 sm:p-7">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-[#222F3E]">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-[#070A0F] border border-amber-800/40">05</span>
                        <div>
                            <h2 class="text-base font-semibold text-white font-heading">Metode Pembayaran</h2>
                            <p class="text-xs text-slate-400">Pilih opsi pembayaran: Tunai di workshop atau QRIS instan.</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Selection Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                    <!-- Cash / Tunai -->
                    <div @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'border-[#F59E0B] bg-amber-950/20' : 'border-[#222F3E] bg-[#070A0F] hover:border-slate-600'" class="cursor-pointer p-4 rounded-xl border transition-all flex items-start gap-3">
                        <span class="material-symbols-outlined text-slate-400 text-2xl mt-0.5" :class="paymentMethod === 'cash' ? 'text-[#F59E0B]' : ''">payments</span>
                        <div class="flex-1">
                            <div class="text-xs font-semibold text-white flex items-center justify-between">
                                <span>Tunai di Workshop</span>
                                <span x-show="paymentMethod === 'cash'" class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Bayar langsung secara tunai di lab setelah seluruh pengerjaan selesai dicek bersama.</p>
                        </div>
                    </div>

                    <!-- QRIS -->
                    <div @click="paymentMethod = 'qris'" :class="paymentMethod === 'qris' ? 'border-[#F59E0B] bg-amber-950/20' : 'border-[#222F3E] bg-[#070A0F] hover:border-slate-600'" class="cursor-pointer p-4 rounded-xl border transition-all flex items-start gap-3">
                        <span class="material-symbols-outlined text-slate-400 text-2xl mt-0.5" :class="paymentMethod === 'qris' ? 'text-[#F59E0B]' : ''">qr_code_2</span>
                        <div class="flex-1">
                            <div class="text-xs font-semibold text-white flex items-center justify-between">
                                <span>QRIS (Instan & Bebas Biaya)</span>
                                <span x-show="paymentMethod === 'qris'" class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Scan kode barcode QRIS via GoPay, Dana, OVO, ShopeePay, BCA, Mandiri, BRI, dll.</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="payment_method" :value="paymentMethod">

                <!-- Conditional QRIS Display & Payment Proof Upload -->
                <div x-show="paymentMethod === 'qris'" x-cloak class="p-5 rounded-xl bg-[#070A0F] border border-amber-800/40 space-y-5 animate-fade-up">
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-xl bg-[#0C111D] border border-[#222F3E]">
                        <!-- QRIS Barcode Image with Zoom & Download Controls -->
                        <div class="p-3 bg-white rounded-xl shadow-lg shrink-0 text-center group">
                            <div class="relative overflow-hidden cursor-pointer" @click="qrisEnlargeModal = true">
                                <img src="{{ $settings->qrisImageUrl() }}" alt="QRIS {{ $settings->qris_merchant_name ?: 'Kedai Beloz Wk17620' }}" class="w-44 h-auto object-contain mx-auto rounded transition-transform group-hover:scale-105 duration-200">
                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded">
                                    <span class="p-1.5 rounded-full bg-amber-950/80 text-amber-300">
                                        <span class="material-symbols-outlined text-[18px]">zoom_in</span>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Enlarge and Download Action Buttons -->
                            <div class="mt-2.5 pt-2 border-t border-slate-200 flex items-center justify-center gap-2">
                                <button type="button" @click="qrisEnlargeModal = true" class="px-2.5 py-1 rounded bg-slate-900 hover:bg-slate-800 text-amber-400 text-[10px] font-mono font-semibold flex items-center gap-1 transition-colors" title="Perbesar Barcode">
                                    <span class="material-symbols-outlined text-[13px]">zoom_in</span>
                                    <span>Perbesar</span>
                                </button>
                                <a href="{{ $settings->qrisImageUrl() }}" download="QRIS-INULIN.png" class="px-2.5 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-mono font-semibold flex items-center gap-1 transition-colors shadow" title="Unduh Barcode QRIS">
                                    <span class="material-symbols-outlined text-[13px]">download</span>
                                    <span>Unduh</span>
                                </a>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs w-full">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-950/80 border border-amber-700/60 text-amber-300 font-mono text-[10px] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span>QRIS RESMI TERVERIFIKASI</span>
                            </div>
                            <h4 class="font-heading font-bold text-white text-base">{{ $settings->qris_merchant_name ?: 'Kedai Beloz Wk17620' }}</h4>
                            <p class="font-mono text-[11px] text-slate-400">NMID: <strong class="text-slate-200">{{ $settings->qris_nmid ?: 'ID2025443145250' }}</strong></p>
                            <div class="pt-2 border-t border-[#222F3E]">
                                <span class="text-slate-400 text-[11px] block">Jumlah Pembayaran Sesuai Layanan:</span>
                                <span class="font-mono font-bold text-xl text-[#F59E0B]" x-text="currentService.formattedPrice"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-options: Menu Bayar Langsung Sekarang vs Paylater Setelah Jadi -->
                    <div class="pt-4 border-t border-[#222F3E] space-y-2.5">
                        <label class="block text-xs font-medium text-slate-200">
                            Pilih Waktu Pembayaran QRIS <span class="text-rose-400">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Option 1: Direct Now -->
                            <div @click="paymentTiming = 'direct'" :class="paymentTiming === 'direct' ? 'border-[#F59E0B] bg-amber-950/30' : 'border-[#222F3E] bg-[#0C111D] hover:border-slate-600'" class="cursor-pointer p-3.5 rounded-xl border transition-all flex items-start gap-3">
                                <span class="material-symbols-outlined text-xl mt-0.5" :class="paymentTiming === 'direct' ? 'text-[#F59E0B]' : 'text-slate-400'">flash_on</span>
                                <div class="flex-1">
                                    <div class="text-xs font-semibold text-white flex items-center justify-between">
                                        <span>Bayar Langsung Sekarang</span>
                                        <span x-show="paymentTiming === 'direct'" class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1">Scan QRIS & unggah foto bukti transfer saat ini juga.</p>
                                </div>
                            </div>

                            <!-- Option 2: Paylater After Done -->
                            <div @click="paymentTiming = 'paylater'" :class="paymentTiming === 'paylater' ? 'border-[#F59E0B] bg-amber-950/30' : 'border-[#222F3E] bg-[#0C111D] hover:border-slate-600'" class="cursor-pointer p-3.5 rounded-xl border transition-all flex items-start gap-3">
                                <span class="material-symbols-outlined text-xl mt-0.5" :class="paymentTiming === 'paylater' ? 'text-[#F59E0B]' : 'text-slate-400'">event_available</span>
                                <div class="flex-1">
                                    <div class="text-xs font-semibold text-white flex items-center justify-between">
                                        <span>Bayar Paylater (Setelah Jadi)</span>
                                        <span x-show="paymentTiming === 'paylater'" class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1">Simpan QRIS sekarang, bayar setelah laptop selesai dicek.</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="payment_timing" :value="paymentTiming">
                    </div>

                    <!-- If Direct Payment Selected: File Upload Input -->
                    <div x-show="paymentTiming === 'direct'" class="space-y-3 pt-2">
                        <label for="payment_proof" class="block text-xs font-medium text-slate-200">
                            Unggah Bukti Transfer / Pembayaran QRIS <span class="text-rose-400">*</span>
                        </label>
                        <p class="text-[11px] text-slate-400">
                            Tangkapan layar (screenshot) struk m-banking atau e-wallet Anda. Format: JPG, PNG, WEBP (Maksimal 5MB).
                        </p>
                        
                        <div>
                            <input type="file" id="payment_proof" name="payment_proof" accept="image/jpeg,image/png,image/webp" @change="handleProofUpload" :required="paymentMethod === 'qris' && paymentTiming === 'direct'" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#18212E] file:text-amber-400 hover:file:bg-[#222F3E] file:cursor-pointer border border-[#222F3E] rounded-lg bg-[#0C111D] focus:outline-none focus:border-amber-400">
                        </div>

                        <!-- Proof Preview -->
                        <div x-show="proofPreview" class="p-3 rounded-lg bg-[#121822] border border-[#222F3E] flex items-center gap-4">
                            <img :src="proofPreview" alt="Pratinjau Bukti" class="w-16 h-16 object-cover rounded-lg border border-amber-500/30">
                            <div class="text-xs">
                                <span class="text-emerald-400 font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">check_circle</span>
                                    <span>File Bukti Siap Dikirim</span>
                                </span>
                                <span class="text-slate-400 font-mono text-[11px] block truncate max-w-xs mt-0.5" x-text="proofFileName"></span>
                            </div>
                        </div>

                        <div class="p-3 rounded-lg bg-amber-950/30 border border-amber-800/40 text-[11px] text-amber-300/90 flex items-start gap-2">
                            <span class="material-symbols-outlined text-[16px] text-amber-400 shrink-0 mt-0.5">verified_user</span>
                            <span>Bukti transfer Anda akan diverifikasi (ACC) langsung oleh teknisi sebelum status pengerjaan ditandai selesai.</span>
                        </div>
                    </div>

                    <!-- If Paylater Selected: Informative Message -->
                    <div x-show="paymentTiming === 'paylater'" x-cloak class="p-4 rounded-xl bg-emerald-950/30 border border-emerald-800/50 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-400 font-heading font-semibold text-xs">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            <span>Opsi Paylater Dipilih (Bayar Nanti Pasca Pengerjaan)</span>
                        </div>
                        <p class="text-xs text-slate-300 font-sans leading-relaxed">
                            Booking Anda dapat langsung disimpan tanpa perlu mengirim bukti sekarang. Anda dipersilakan mengunduh kode barcode QRIS di atas untuk discan nanti saat laptop selesai diinstal dan Anda puas dengan hasilnya di lab.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <button type="submit" class="w-full py-4 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-sm tracking-wide transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_25px_rgba(245,158,11,0.5)] flex items-center justify-center gap-2">
                <span>Konfirmasi & Simpan Booking</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </form>

        <!-- Right Column: Live Sticky Summary & WhatsApp Preview (5 cols) -->
        <aside class="lg:col-span-5 lg:sticky lg:top-28 space-y-6">
            
            <!-- Summary Card -->
            <div class="rounded-xl bg-[#121822] border border-[#222F3E] p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-[#222F3E]">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400 text-lg">receipt_long</span>
                        <h3 class="font-heading font-bold text-sm text-white">Ringkasan Booking</h3>
                    </div>
                    <span class="font-mono text-[10px] text-emerald-400 bg-emerald-950/50 px-2 py-0.5 rounded border border-emerald-800/40">
                        Draft Aktif
                    </span>
                </div>

                <!-- Package Selection Switcher -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-medium text-slate-400">Paket Layanan Dipilih:</label>
                    <select x-model="serviceId" class="w-full bg-[#070A0F] border border-[#222F3E] rounded-lg px-3 py-2 text-xs font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                        @foreach($allServices as $srv)
                            <option value="{{ $srv->id }}">
                                {{ $srv->name }} — Rp{{ number_format($srv->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Itemized Breakdown -->
                <div class="border-t border-b border-[#222F3E] py-3.5 space-y-2.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Layanan</span>
                        <span class="text-white font-medium text-right" x-text="currentService.name"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama Pemesan</span>
                        <span class="text-white font-medium text-right" x-text="name || '-'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">WhatsApp</span>
                        <span class="text-white font-mono text-right" x-text="whatsapp || '-'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Perangkat</span>
                        <span class="text-white text-right" x-text="device"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400 shrink-0">OS / Versi</span>
                        <span class="text-white font-mono text-right truncate" x-text="osDisplayName"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400 shrink-0">Jadwal</span>
                        <span class="text-white font-mono text-right" x-text="formattedSchedule"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400 shrink-0">Metode Bayar</span>
                        <span class="text-amber-400 font-semibold text-right" x-text="paymentMethod === 'qris' ? (paymentTiming === 'paylater' ? 'QRIS Paylater' : 'QRIS Langsung') : 'Tunai di Workshop'"></span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400 shrink-0">Catatan</span>
                        <span class="text-slate-400 italic text-right truncate max-w-[180px]" x-text="notes || '-'"></span>
                    </div>
                </div>

                <!-- Price Snapshot Display -->
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block text-xs text-slate-400 font-medium">Estimasi Biaya</span>
                        <span class="text-[11px] text-slate-500 font-mono" x-text="paymentMethod === 'qris' ? (paymentTiming === 'paylater' ? 'Bayar pasca servis via QRIS' : 'Transfer via scan QRIS sekarang') : 'Bayar di workshop pasca cek'"></span>
                    </div>
                    <div class="text-right">
                        <span class="font-mono text-2xl font-bold text-[#F59E0B]" x-text="currentService.formattedPrice"></span>
                    </div>
                </div>

                <!-- Safety Note -->
                <div class="pt-3 border-t border-[#222F3E] flex items-start gap-2 text-[11px] text-slate-400">
                    <span class="material-symbols-outlined text-emerald-400 text-[16px] shrink-0 mt-0.5">check_circle</span>
                    <span>Harga diambil langsung dari database sistem. Data pribadi di drive D/E tetap aman terisolasi.</span>
                </div>
            </div>

            <!-- Plain WhatsApp Snippet Preview -->
            <div class="rounded-xl bg-[#121822] border border-[#222F3E] p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono text-slate-400 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-emerald-400">chat</span>
                        PREVIEW PESAN WHATSAPP
                    </span>
                    <button type="button" @click="copySnippet()" class="text-[11px] font-mono text-amber-400 hover:text-amber-300 flex items-center gap-1 transition-colors">
                        <span class="material-symbols-outlined text-[14px]">content_copy</span>
                        <span x-text="copied ? 'Tersalin!' : 'Salin Teks'">Salin Teks</span>
                    </button>
                </div>

                <pre class="p-3.5 rounded-lg bg-[#070A0F] border border-[#222F3E] text-[11px] font-mono text-slate-300 whitespace-pre-wrap leading-relaxed select-all" x-text="generatedWaMessage"></pre>
                
                <p class="text-[10px] font-mono text-slate-500">
                    Setelah menekan "Konfirmasi & Simpan Booking", Anda akan diarahkan ke WhatsApp untuk mengirim pesan di atas secara langsung ke teknisi.
                </p>
            </div>

        </aside>

    </div>

    <!-- Modal Perbesar / Zoom QRIS -->
    <div x-show="qrisEnlargeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md" @keydown.escape.window="qrisEnlargeModal = false">
        <div @click.away="qrisEnlargeModal = false" class="relative max-w-md w-full bg-[#121822] border border-amber-500/50 rounded-2xl overflow-hidden shadow-2xl p-6 space-y-4 animate-fade-up">
            <div class="flex items-center justify-between pb-3 border-b border-[#222F3E]">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">qr_code_2</span>
                    <h3 class="font-heading font-bold text-white text-sm">Scan QRIS Resmi INULIN</h3>
                </div>
                <button type="button" @click="qrisEnlargeModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            
            <div class="bg-white p-4 rounded-xl flex items-center justify-center shadow-inner">
                <img src="{{ $settings->qrisImageUrl() }}" alt="QRIS Full Size" class="max-h-80 w-auto object-contain">
            </div>

            <div class="text-xs space-y-1.5 p-3 rounded-lg bg-[#070A0F] border border-[#222F3E]">
                <div class="flex justify-between text-slate-300">
                    <span class="text-slate-400">Merchant:</span>
                    <span class="font-semibold text-white">{{ $settings->qris_merchant_name ?: 'Kedai Beloz Wk17620' }}</span>
                </div>
                <div class="flex justify-between text-slate-300">
                    <span class="text-slate-400">NMID:</span>
                    <span class="font-mono text-amber-400">{{ $settings->qris_nmid ?: 'ID2025443145250' }}</span>
                </div>
                <div class="flex justify-between text-slate-300 pt-1.5 border-t border-[#222F3E]">
                    <span class="text-slate-400">Nominal Transfer:</span>
                    <span class="font-mono font-bold text-base text-[#F59E0B]" x-text="currentService.formattedPrice"></span>
                </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <a href="{{ $settings->qrisImageUrl() }}" download="QRIS-INULIN.png" class="flex-1 py-2.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-xs flex items-center justify-center gap-1.5 transition-colors shadow">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    <span>Unduh Gambar QRIS</span>
                </a>
                <button type="button" @click="qrisEnlargeModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-mono text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
