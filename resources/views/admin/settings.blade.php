@extends('admin.layout', ['pageTitle' => 'PENGATURAN BISNIS & BRANDING'])

@section('content')
<div class="space-y-6">

    <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 sm:p-8">
        
        <div class="pb-6 border-b border-slate-800 mb-8">
            <h2 class="font-heading font-bold text-xl text-white">Pengaturan Workshop & Konfigurasi Sistem</h2>
            <p class="text-xs text-slate-400 font-mono mt-1">Kelola data identitas bisnis, nomor WhatsApp target booking, jam buka operasional, dan logo resmi.</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8 text-xs">
            @csrf @method('PATCH')

            <!-- SECTION 1: BRANDING & LOGO MANAGEMENT -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">palette</span>
                    <h3 class="font-heading font-bold text-sm text-white">Branding & Logo Management</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 rounded-xl bg-[#070A0F] border border-slate-800">
                    <!-- Logo Upload -->
                    <div class="space-y-3">
                        <label class="block font-medium text-slate-300">Logo Website (Navbar & Footer)</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $settings->logoUrl() }}" alt="Current Logo" class="w-14 h-14 object-contain rounded-xl p-1 bg-black/60 border border-slate-700 shrink-0">
                            <div class="space-y-1 flex-1">
                                <input type="file" name="logo" accept="image/*" class="w-full bg-[#121822] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                                <p class="text-[10px] text-slate-500 font-mono">Format PNG/WEBP transparan direkomendasikan (maks. 2MB).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Upload -->
                    <div class="space-y-3">
                        <label class="block font-medium text-slate-300">Favicon Browser</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $settings->faviconUrl() }}" alt="Current Favicon" class="w-14 h-14 object-contain rounded-xl p-1 bg-black/60 border border-slate-700 shrink-0">
                            <div class="space-y-1 flex-1">
                                <input type="file" name="favicon" accept="image/*,.ico" class="w-full bg-[#121822] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                                <p class="text-[10px] text-slate-500 font-mono">Format ICO atau PNG persegi 64x64 (maks. 1MB).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PENGATURAN QRIS & PEMBAYARAN -->
            <div class="space-y-4 pt-4 border-t border-slate-800" x-data="{ qrisZoom: false }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400">qr_code_2</span>
                        <h3 class="font-heading font-bold text-sm text-white">Konfigurasi Barcode QRIS Pembayaran</h3>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-950/80 border border-amber-800/40 text-amber-400 font-mono text-[10px]">
                        Dynamic QRIS
                    </span>
                </div>

                <div class="p-5 rounded-xl bg-[#070A0F] border border-slate-800 space-y-6">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <!-- Current QRIS Image with Zoom & Download -->
                        <div class="relative group shrink-0">
                            <div class="w-36 h-36 bg-white rounded-xl p-2 border border-amber-800/60 flex items-center justify-center overflow-hidden shadow-lg">
                                <img src="{{ $settings->qrisImageUrl() }}" alt="Current QRIS" class="w-full h-full object-contain">
                            </div>
                            <div class="mt-2 flex items-center gap-2 justify-center">
                                <button type="button" @click="qrisZoom = true" class="px-2 py-1 rounded bg-[#18212E] hover:bg-[#222F3E] text-amber-400 border border-slate-700 text-[10px] font-mono flex items-center gap-1 transition-colors" title="Perbesar QRIS">
                                    <span class="material-symbols-outlined text-[13px]">zoom_in</span>
                                    <span>Zoom</span>
                                </button>
                                <a href="{{ $settings->qrisImageUrl() }}" download="QRIS-INULIN.png" class="px-2 py-1 rounded bg-[#18212E] hover:bg-[#222F3E] text-emerald-400 border border-slate-700 text-[10px] font-mono flex items-center gap-1 transition-colors" title="Download QRIS">
                                    <span class="material-symbols-outlined text-[13px]">download</span>
                                    <span>Unduh</span>
                                </a>
                            </div>
                        </div>

                        <!-- Upload New QRIS File & Details -->
                        <div class="space-y-4 flex-1 w-full">
                            <div class="space-y-1.5">
                                <label class="block font-medium text-slate-300">Ganti Foto Barcode QRIS Baru</label>
                                <input type="file" name="qris_image" accept="image/*" class="w-full bg-[#121822] border border-slate-800 rounded-lg px-3 py-2 text-slate-300 font-mono file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                                <p class="text-[11px] text-slate-500 font-mono">Format PNG/JPG/WEBP (maks. 5MB). Foto QRIS baru otomatis langsung tampil di form booking publik tanpa hard refresh.</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block font-medium text-slate-300">Nama Merchant QRIS</label>
                                    <input type="text" name="qris_merchant_name" value="{{ old('qris_merchant_name', $settings->qris_merchant_name ?: 'Kedai Beloz Wk17620') }}" placeholder="cth. Kedai Beloz Wk17620" class="w-full bg-[#121822] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                                </div>
                                <div class="space-y-1">
                                    <label class="block font-medium text-slate-300">NMID Barcode QRIS</label>
                                    <input type="text" name="qris_nmid" value="{{ old('qris_nmid', $settings->qris_nmid ?: 'ID2025443145250') }}" placeholder="cth. ID2025443145250" class="w-full bg-[#121822] border border-slate-800 rounded-lg px-3 py-2 font-mono text-amber-300 focus:outline-none focus:border-[#F59E0B]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Zoom Lightbox Modal -->
                    <div x-show="qrisZoom" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" @keydown.escape.window="qrisZoom = false">
                        <div @click.away="qrisZoom = false" class="relative max-w-lg w-full bg-[#121822] border border-amber-500/50 rounded-2xl overflow-hidden shadow-2xl p-6 space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                                <h4 class="font-heading font-bold text-white text-sm">Pratinjau Resolusi Penuh QRIS</h4>
                                <button type="button" @click="qrisZoom = false" class="text-slate-400 hover:text-white">
                                    <span class="material-symbols-outlined text-xl">close</span>
                                </button>
                            </div>
                            <div class="bg-white p-4 rounded-xl flex items-center justify-center">
                                <img src="{{ $settings->qrisImageUrl() }}" alt="QRIS Full" class="max-h-80 w-auto object-contain">
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <span class="font-mono text-xs text-slate-400">{{ $settings->qris_merchant_name ?: 'Kedai Beloz Wk17620' }}</span>
                                <a href="{{ $settings->qrisImageUrl() }}" download="QRIS-INULIN.png" class="px-4 py-2 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-xs flex items-center gap-1.5 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                    <span>Unduh Gambar QRIS</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: IDENTITAS BISNIS -->
            <div class="space-y-4 pt-4 border-t border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">store</span>
                    <h3 class="font-heading font-bold text-sm text-white">Identitas Bisnis & Kontak</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nama Bisnis *</label>
                        <input type="text" name="business_name" value="{{ old('business_name', $settings->business_name) }}" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tagline *</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nomor WhatsApp Target Booking *</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings->whatsapp) }}" required placeholder="6285165017620" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-amber-400 font-bold focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nomor Telepon Kantor</label>
                        <input type="text" name="phone" value="{{ old('phone', $settings->phone) }}" placeholder="08xxxxxxxxxx" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email', $settings->email) }}" placeholder="halo@inulin.id" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Link Google Maps</label>
                        <input type="text" name="maps_link" value="{{ old('maps_link', $settings->maps_link) }}" placeholder="https://maps.google.com/..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Alamat Fisik Workshop Lab</label>
                    <input type="text" name="address" value="{{ old('address', $settings->address) }}" placeholder="Jl. Kaliurang KM 5, Sleman, D.I. Yogyakarta" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Singkat Usaha</label>
                    <textarea name="description" rows="2" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]">{{ old('description', $settings->description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 3: JAM OPERASIONAL & PENJADWALAN -->
            <div class="space-y-4 pt-4 border-t border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">schedule</span>
                    <h3 class="font-heading font-bold text-sm text-white">Jam Operasional & Validasi Jadwal</h3>
                </div>

                <div class="space-y-2">
                    <label class="block font-medium text-slate-300">Hari Kerja Operasional *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
                        @php
                            $days = [
                                'monday' => 'Senin',
                                'tuesday' => 'Selasa',
                                'wednesday' => 'Rabu',
                                'thursday' => 'Kamis',
                                'friday' => 'Jumat',
                                'saturday' => 'Sabtu',
                                'sunday' => 'Minggu',
                            ];
                            $activeDays = collect($settings->operating_days ?? [])->map(fn($d) => strtolower($d))->all();
                        @endphp
                        @foreach($days as $key => $label)
                            <label class="p-2.5 rounded-lg bg-[#070A0F] border border-slate-800 flex items-center gap-2 cursor-pointer hover:border-slate-600 select-none">
                                <input type="checkbox" name="operating_days[]" value="{{ $key }}" {{ in_array($key, $activeDays) ? 'checked' : '' }} class="rounded bg-[#121822] border-slate-700 text-[#F59E0B]">
                                <span class="font-mono text-xs text-white">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Jam Buka *</label>
                        <input type="text" name="opening_time" value="{{ old('opening_time', substr($settings->opening_time, 0, 5)) }}" required placeholder="09:00" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Jam Tutup *</label>
                        <input type="text" name="closing_time" value="{{ old('closing_time', substr($settings->closing_time, 0, 5)) }}" required placeholder="18:00" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Durasi Slot Default (Menit) *</label>
                        <input type="number" name="booking_duration" value="{{ old('booking_duration', $settings->booking_duration) }}" required min="15" max="240" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Maks. Kuota per Slot *</label>
                        <input type="number" name="max_bookings_per_slot" value="{{ old('max_bookings_per_slot', $settings->max_bookings_per_slot) }}" required min="1" max="10" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-800 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-sm tracking-wide transition-all shadow-[0_0_16px_rgba(245,158,11,0.25)]">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Pengaturan Bisnis</span>
                </button>
            </div>
        </form>

    </div>

    <!-- SECTION: KEAMANAN & GANTI KATA SANDI ADMINISTRATOR -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 sm:p-8" x-data="{ showCurr: false, showNew: false }">
        <div class="pb-6 border-b border-slate-800 mb-6 flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-lg text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">lock_reset</span>
                    <span>Keamanan Akun & Ganti Kata Sandi Administrator</span>
                </h2>
                <p class="text-xs text-slate-400 font-mono mt-1">Perbarui kata sandi akun login Anda secara berkala demi keamanan sistem.</p>
            </div>
            <span class="text-[11px] font-mono px-2.5 py-1 rounded bg-slate-900 border border-slate-800 text-slate-400">
                Akun: {{ auth()->user()->email }}
            </span>
        </div>

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-5 text-xs max-w-2xl">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="space-y-1.5">
                <label for="current_password" class="block font-medium text-slate-300">
                    Kata Sandi Saat Ini <span class="text-rose-400">*</span>
                </label>
                <div class="relative">
                    <input :type="showCurr ? 'text' : 'password'" id="current_password" name="current_password" required placeholder="Masukkan kata sandi saat ini" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg pl-3 pr-10 py-2.5 text-white font-mono focus:outline-none focus:border-[#F59E0B]">
                    <button type="button" @click="showCurr = !showCurr" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300" tabindex="-1">
                        <span class="material-symbols-outlined text-[16px]" x-text="showCurr ? 'visibility_off' : 'visibility'">visibility</span>
                    </button>
                </div>
            </div>

            <!-- New Password & Confirmation Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="new_password" class="block font-medium text-slate-300">
                        Kata Sandi Baru <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" id="new_password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg pl-3 pr-10 py-2.5 text-white font-mono focus:outline-none focus:border-[#F59E0B]">
                        <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300" tabindex="-1">
                            <span class="material-symbols-outlined text-[16px]" x-text="showNew ? 'visibility_off' : 'visibility'">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="new_password_confirmation" class="block font-medium text-slate-300">
                        Ulangi Kata Sandi Baru <span class="text-rose-400">*</span>
                    </label>
                    <input :type="showNew ? 'text' : 'password'" id="new_password_confirmation" name="password_confirmation" required minlength="8" placeholder="Konfirmasi kata sandi baru" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2.5 text-white font-mono focus:outline-none focus:border-[#F59E0B]">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-950/80 hover:bg-amber-900/90 text-amber-300 border border-amber-700/60 font-mono text-xs font-bold transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">key</span>
                    <span>Perbarui Kata Sandi</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
