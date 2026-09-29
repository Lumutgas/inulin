@extends('layouts.app', ['title' => 'Portofolio & Galeri Pengerjaan — INULIN'])

@section('content')
<div class="relative overflow-hidden pt-12 pb-24">
    <!-- Ambient Gradients -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-amber-500/10 via-transparent to-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute top-48 left-10 w-72 h-72 bg-purple-500/5 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-8 animate-fade-up">
            <a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">INULIN</a>
            <span class="text-slate-600">/</span>
            <span class="text-amber-400">Portofolio & Galeri</span>
        </nav>

        <!-- Page Header -->
        <div class="max-w-3xl mb-16 animate-fade-up delay-100">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-950/60 border border-amber-800/40 text-amber-400 text-xs font-mono font-medium mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>DOKUMENTASI MEJA KERJA TEKNISI</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-bold text-white tracking-tight leading-tight mb-4">
                Portofolio & <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-300">Hasil Pengerjaan Lab</span>
            </h1>
            <p class="text-base text-slate-300 font-sans leading-relaxed">
                Bukti nyata hasil instalasi, konfigurasi sistem operasi, dan perawatan hardware komputer pelanggan di laboratorium INULIN.
            </p>
        </div>

        <div x-data="{
            previewModal: false,
            previewImg: '',
            previewTitle: '',
            previewDesc: '',
            reviewModal: false,
            ratingVal: 5,
            ratingHover: 5,
            ratingLabel() {
                const labels = {
                    1: '⭐ (1.0) Perlu Perbaikan',
                    2: '⭐⭐ (2.0) Kurang Memuaskan',
                    3: '⭐⭐⭐ (3.0) Cukup Baik & Normal',
                    4: '⭐⭐⭐⭐ (4.0) Sangat Bagus & Cepat',
                    5: '⭐⭐⭐⭐⭐ (5.0) Sempurna & Sangat Direkomendasikan!'
                };
                return labels[this.ratingHover || this.ratingVal] || '';
            },
            openPreview(img, title, desc) {
                this.previewImg = img;
                this.previewTitle = title;
                this.previewDesc = desc;
                this.previewModal = true;
            }
        }">
            <!-- Success Notification Alert -->
            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-950/60 border border-emerald-500/50 flex items-center justify-between text-xs text-emerald-300 animate-fade-up">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-emerald-400 text-lg">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Gallery Showcase Grid -->
            <div class="mb-24">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-xl sm:text-2xl font-heading font-bold text-white">Studi Kasus & Pengerjaan Terverifikasi</h2>
                    <p class="text-xs text-slate-400 font-mono mt-1">Dokumentasi langsung dari workstation teknisi kami (Terkoneksi Database Real-Time)</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-amber-950/60 border border-amber-800/40 text-amber-400 text-xs font-mono">
                        Total Dokumentasi: {{ $galleries->count() }}
                    </span>
                </div>
            </div>

            @if($galleries->isEmpty())
                <div class="p-12 rounded-2xl bg-[#121822] border border-[#222F3E] text-center text-slate-400">
                    <span class="material-symbols-outlined text-5xl text-slate-600 mb-3">photo_library</span>
                    <h3 class="text-lg font-heading font-bold text-white mb-1">Belum Ada Dokumentasi Galeri</h3>
                    <p class="text-xs text-slate-400 font-sans max-w-md mx-auto">
                        Foto pengerjaan dan studi kasus laboratorium akan ditampilkan di sini setelah ditambahkan oleh teknisi melalui dashboard admin.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($galleries as $g)
                        <div class="rounded-2xl bg-[#121822] border border-[#222F3E] overflow-hidden tech-card group hover:border-amber-500/50 transition-all flex flex-col justify-between">
                            <div>
                                <!-- Image Container with Lightbox Trigger -->
                                <div class="relative aspect-video bg-[#070A0F] overflow-hidden cursor-pointer" @click="openPreview('{{ $g->imageUrl() }}', '{{ addslashes($g->title) }}', '{{ addslashes($g->description ?? '') }}')">
                                    <img src="{{ $g->imageUrl() }}" alt="{{ $g->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#121822] via-transparent to-transparent opacity-80"></div>
                                    <div class="absolute top-3 left-3">
                                        <span class="font-mono text-[10px] px-2 py-0.5 rounded bg-amber-950/80 border border-amber-700/60 text-amber-300 font-semibold backdrop-blur-sm">
                                            DOKUMENTASI #{{ $g->id }}
                                        </span>
                                    </div>
                                    <div class="absolute top-3 right-3">
                                        <span class="font-mono text-[10px] px-2 py-0.5 rounded bg-emerald-950/80 border border-emerald-700/60 text-emerald-400 font-semibold backdrop-blur-sm">
                                            QC Passed ✓
                                        </span>
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                                        <span class="px-3 py-1.5 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-400/40 text-xs font-mono flex items-center gap-1.5 backdrop-blur-md">
                                            <span class="material-symbols-outlined text-[16px]">zoom_in</span>
                                            <span>Perbesar Foto</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Text Content -->
                                <div class="p-6 space-y-3">
                                    <h3 class="text-lg font-heading font-bold text-white group-hover:text-amber-300 transition-colors">
                                        {{ $g->title }}
                                    </h3>
                                    <p class="text-xs text-slate-300 leading-relaxed font-sans">
                                        {{ $g->description ?: 'Dokumentasi pengerjaan teknisi pada workstation laboratorium INULIN.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <div class="pt-3 border-t border-[#222F3E]/60 flex items-center justify-between text-[11px] font-mono text-slate-400">
                                    <span>Tercatat: {{ $g->created_at ? $g->created_at->translatedFormat('d M Y') : 'Terverifikasi' }}</span>
                                    <button type="button" @click="openPreview('{{ $g->imageUrl() }}', '{{ addslashes($g->title) }}', '{{ addslashes($g->description ?? '') }}')" class="text-amber-400 hover:text-amber-300 flex items-center gap-1">
                                        <span>Lihat Rinci</span>
                                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Image Lightbox Modal -->
            <div x-show="previewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" @keydown.escape.window="previewModal = false">
                <div @click.away="previewModal = false" class="relative max-w-4xl w-full bg-[#121822] border border-amber-500/40 rounded-2xl overflow-hidden shadow-2xl animate-fade-up">
                    <div class="p-4 border-b border-[#222F3E] flex items-center justify-between bg-[#070A0F]">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <h3 class="font-heading font-bold text-white text-sm sm:text-base truncate max-w-md" x-text="previewTitle"></h3>
                        </div>
                        <button type="button" @click="previewModal = false" class="p-1 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                    <div class="max-h-[70vh] bg-black flex items-center justify-center p-2 overflow-hidden">
                        <img :src="previewImg" :alt="previewTitle" class="max-h-[65vh] w-auto max-w-full object-contain rounded-lg">
                    </div>
                    <div class="p-4 bg-[#0F141D] border-t border-[#222F3E]">
                        <p class="text-xs text-slate-300 font-sans" x-text="previewDesc || 'Dokumentasi terverifikasi dari laboratorium INULIN.'"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Testimonials Section -->
        <div class="mb-24 reveal-on-scroll">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div class="max-w-2xl">
                    <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-semibold">FEEDBACK REALISTIS</span>
                    <h2 class="text-2xl sm:text-3xl font-heading font-bold text-white mt-1">Ulasan Pengguna Terverifikasi</h2>
                    <p class="text-sm text-slate-400 mt-2">Testimoni teknis dari mahasiswa, developer, dan pekerja profesional yang mempercayakan laptopnya kepada kami.</p>
                </div>
                <button type="button" @click="reviewModal = true" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-heading font-bold text-xs tracking-wide shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_25px_rgba(245,158,11,0.5)] transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px] star-filled" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span>Tulis Ulasan & Rating Anda</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($testimonials as $t)
                    <div class="p-6 rounded-2xl bg-[#121822] border border-[#222F3E] relative tech-card flex flex-col justify-between hover:border-amber-500/40 transition-all">
                        <div>
                            <!-- Dynamic Stars from DB with Full Solid Amber Fill -->
                            <div class="flex items-center gap-1 text-amber-400 mb-3">
                                @php $rating = (int) ($t->rating ?? 5); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <span class="material-symbols-outlined text-[18px] text-amber-400 star-filled" style="font-variation-settings: 'FILL' 1;">star</span>
                                    @else
                                        <span class="material-symbols-outlined text-[18px] text-slate-600 star-empty" style="font-variation-settings: 'FILL' 0;">star</span>
                                    @endif
                                @endfor
                                <span class="font-mono text-xs text-amber-400 font-bold ml-1.5">({{ $rating }}.0)</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-sans italic mb-6">
                                &ldquo;{{ $t->quote }}&rdquo;
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#222F3E]/60 flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-heading font-bold text-white">{{ $t->name }}</h3>
                                <span class="text-[10px] font-mono text-amber-400">{{ $t->role ?: 'Pelanggan Terverifikasi' }}</span>
                            </div>
                            <span class="material-symbols-outlined text-amber-400 text-xl">verified</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 rounded-2xl bg-[#121822] border border-[#222F3E] text-slate-400 text-xs font-mono col-span-3 text-center">
                        Belum ada testimoni tambahan yang dimasukkan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Review Submission Modal -->
        <div x-show="reviewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fade-in" @keydown.escape.window="reviewModal = false">
            <div class="relative w-full max-w-lg rounded-2xl bg-[#0F141D] border border-amber-800/40 shadow-2xl overflow-hidden animate-zoom-in" @click.outside="reviewModal = false">
                <div class="p-6 border-b border-[#222F3E] flex items-center justify-between">
                    <div>
                        <span class="text-xs font-mono text-amber-400 font-semibold uppercase tracking-wider block">Beri Penilaian Layanan</span>
                        <h3 class="text-lg font-heading font-bold text-white mt-0.5">Tulis Ulasan & Rating Bintang</h3>
                    </div>
                    <button type="button" @click="reviewModal = false" class="w-8 h-8 rounded-lg bg-[#18212E] hover:bg-[#222F3E] text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('reviews.store') }}" class="p-6 space-y-4 text-xs font-sans">
                    @csrf
                    <!-- Interactive Star Picker -->
                    <div>
                        <label class="block font-mono text-slate-300 font-semibold mb-2">Berapa bintang untuk kepuasan layanan INULIN? *</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3.5 rounded-xl bg-[#121822] border border-[#222F3E]">
                            <div class="flex items-center gap-1.5 cursor-pointer" @mouseleave="ratingHover = ratingVal">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @mouseenter="ratingHover = star" @click="ratingVal = star; ratingHover = star" class="p-0.5 text-2xl transition-transform hover:scale-125 focus:outline-none">
                                        <span class="material-symbols-outlined text-[26px]" :class="(ratingHover || ratingVal) >= star ? 'text-amber-400 star-filled' : 'text-slate-600 star-empty'" :style="(ratingHover || ratingVal) >= star ? 'font-variation-settings: \'FILL\' 1;' : 'font-variation-settings: \'FILL\' 0;'">star</span>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="rating" :value="ratingVal">
                            <span class="font-mono text-xs font-bold text-amber-400" x-text="ratingLabel()"></span>
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block font-mono text-slate-300 font-semibold mb-1.5">Nama Lengkap *</label>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="w-full px-3.5 py-2.5 rounded-lg bg-[#121822] border border-[#222F3E] text-white focus:border-[#F59E0B] focus:outline-none">
                    </div>

                    <!-- Role / Device -->
                    <div>
                        <label class="block font-mono text-slate-300 font-semibold mb-1.5">Peran / Model Laptop atau PC</label>
                        <input type="text" name="role" placeholder="Contoh: Mahasiswa / ASUS TUF Gaming F15" class="w-full px-3.5 py-2.5 rounded-lg bg-[#121822] border border-[#222F3E] text-white focus:border-[#F59E0B] focus:outline-none">
                    </div>

                    <!-- Review Quote -->
                    <div>
                        <label class="block font-mono text-slate-300 font-semibold mb-1.5">Ulasan & Pengalaman Servis *</label>
                        <textarea name="quote" rows="4" required minlength="10" placeholder="Ceritakan bagaimana performa laptop Anda setelah diinstal ulang di lab INULIN..." class="w-full px-3.5 py-2.5 rounded-lg bg-[#121822] border border-[#222F3E] text-white focus:border-[#F59E0B] focus:outline-none resize-none"></textarea>
                        <span class="text-[10px] text-slate-500 font-mono mt-1 block">Minimal 10 karakter. Ulasan Anda akan langsung tampil di halaman website.</span>
                    </div>

                    <div class="pt-3 border-t border-[#222F3E] flex items-center justify-end gap-3">
                        <button type="button" @click="reviewModal = false" class="px-4 py-2.5 rounded-lg bg-[#18212E] hover:bg-[#222F3E] text-slate-300 text-xs font-medium transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-heading font-bold text-xs tracking-wide shadow transition-all">
                            Kirim Ulasan Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-amber-950/60 via-[#121822] to-amber-950/60 border border-amber-500/30 text-center relative overflow-hidden reveal-on-scroll">
            <h3 class="text-2xl sm:text-3xl font-heading font-bold text-white mb-4">
                Ingin Laptop Anda Kembali Segar Seperti Baru?
            </h3>
            <p class="text-slate-300 text-sm max-w-xl mx-auto mb-8 font-sans">
                Percayakan perawatan dan instalasi pada ahlinya. Booking jadwal Anda secara online sekarang juga.
            </p>
            <a href="{{ route('bookings.general') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-heading font-bold text-sm transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)]">
                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                <span>Booking Jadwal Sekarang</span>
            </a>
        </div>
        </div>

    </div>
</div>
@endsection
