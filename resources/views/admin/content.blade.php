@extends('admin.layout', ['pageTitle' => 'PENGELOLAAN KONTEN & TAMPILAN WEB'])

@section('content')
<div class="space-y-6" x-data="{ 
    tab: 'hero',
    editFaqModal: false,
    selectedFaq: {},
    editTestiModal: false,
    selectedTesti: {},
    editGalleryModal: false,
    selectedGallery: {},
    editHeroModal: false,
    selectedHero: {},
    editWorkflowModal: false,
    selectedWorkflow: {},
    editPillarModal: false,
    selectedPillar: {},

    openEditFaq(faq) {
        this.selectedFaq = Object.assign({}, faq);
        this.editFaqModal = true;
    },
    openEditTesti(testi) {
        this.selectedTesti = Object.assign({}, testi);
        this.editTestiModal = true;
    },
    openEditGallery(gallery) {
        this.selectedGallery = Object.assign({}, gallery);
        this.editGalleryModal = true;
    },
    openEditHero(hero) {
        this.selectedHero = Object.assign({}, hero);
        this.editHeroModal = true;
    },
    openEditWorkflow(workflow) {
        this.selectedWorkflow = Object.assign({}, workflow);
        this.editWorkflowModal = true;
    },
    openEditPillar(pillar) {
        this.selectedPillar = Object.assign({}, pillar);
        this.editPillarModal = true;
    }
}">

    <!-- Tab Switcher Navigation -->
    <div class="rounded-xl bg-[#121822] border border-slate-800 p-2 flex items-center gap-2 overflow-x-auto scrollbar-thin">
        <button type="button" @click="tab = 'hero'" :class="tab === 'hero' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">view_carousel</span>
            <span>Hero & Slider ({{ count($heroSlides) }})</span>
        </button>

        <button type="button" @click="tab = 'pages'" :class="tab === 'pages' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">article</span>
            <span>Foto & Teks Halaman ({{ count($pageContents) }})</span>
        </button>

        <button type="button" @click="tab = 'workflow'" :class="tab === 'workflow' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">schema</span>
            <span>Alur Cara Kerja ({{ count($workflowSteps) }})</span>
        </button>

        <button type="button" @click="tab = 'pillars'" :class="tab === 'pillars' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">account_tree</span>
            <span>Pilar & Alat Tentang Kami ({{ count($aboutPillars) }})</span>
        </button>

        <button type="button" @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">photo_library</span>
            <span>Portofolio & Galeri ({{ count($galleries) }})</span>
        </button>

        <button type="button" @click="tab = 'testimonials'" :class="tab === 'testimonials' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">rate_review</span>
            <span>Ulasan & Rating ({{ count($testimonials) }})</span>
        </button>

        <button type="button" @click="tab = 'faqs'" :class="tab === 'faqs' ? 'bg-[#F59E0B] text-slate-950 font-bold shadow-[0_0_12px_rgba(245,158,11,0.3)]' : 'text-slate-400 hover:text-white'" class="px-3.5 py-2 rounded-lg font-mono text-xs transition-colors flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">quiz</span>
            <span>FAQ ({{ count($faqs) }})</span>
        </button>
    </div>

    <!-- =========================================================================
     * TAB 1: HERO SLIDES & AUTO-SLIDER CAROUSEL
     * ========================================================================= -->
    <div x-show="tab === 'hero'" class="space-y-6">
        <!-- Add Hero Slide Form -->
        <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">add_to_photos</span>
                    <span>Tambah Slide Hero Banner Baru (Auto-Slide Showcase)</span>
                </h3>
                <span class="text-[11px] font-mono text-slate-400">Bergeser Otomatis Setiap 4.5 Detik</span>
            </div>

            <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Badge Label *</label>
                        <input type="text" name="badge" placeholder="cth. PERFORMANCE TUNING & DIAGNOSTIC" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Judul Slide Hero *</label>
                        <input type="text" name="title" required placeholder="cth. PC Lemot Kembali Responsif & Dingin" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Urutan Tampil (Order)</label>
                        <input type="number" name="order" value="{{ count($heroSlides) + 1 }}" min="0" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Sub-judul / Penjelasan Singkat</label>
                    <textarea name="subtitle" rows="2" placeholder="Deskripsikan keunggulan pengerjaan pada banner ini..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Teks Tombol CTA</label>
                        <input type="text" name="button_text" value="Booking Jadwal Sekarang" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Link Tombol CTA</label>
                        <input type="text" name="button_link" value="{{ route('bookings.general') }}" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">File Foto Slide Hero (JPG, PNG, WEBP) *</label>
                        <input type="file" name="image" required accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <span>Aktifkan dan tampilkan di banner carousel beranda</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-[0_0_12px_rgba(245,158,11,0.25)] flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">upload</span>
                        <span>Simpan Slide Hero</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Hero Slides Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($heroSlides as $slide)
                <div class="rounded-xl overflow-hidden bg-[#121822] border border-slate-800 group relative flex flex-col justify-between">
                    <div class="relative">
                        <img src="{{ $slide->imageUrl() }}" alt="{{ $slide->title }}" class="aspect-[16/10] w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-mono bg-amber-950/90 text-amber-400 border border-amber-800/40">
                            Slide #{{ $slide->order }}
                        </span>
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded text-[10px] font-mono {{ $slide->is_active ? 'bg-emerald-950/90 text-emerald-400 border border-emerald-800/40' : 'bg-rose-950/90 text-rose-400 border border-rose-800/40' }}">
                            {{ $slide->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>

                    <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                        <div>
                            @if($slide->badge)
                                <span class="text-[10px] font-mono text-amber-400 block truncate">{{ $slide->badge }}</span>
                            @endif
                            <h4 class="font-heading font-bold text-white text-sm mt-0.5 line-clamp-1">{{ $slide->title }}</h4>
                            <p class="text-[11px] text-slate-400 line-clamp-2 mt-1">{{ $slide->subtitle }}</p>
                        </div>

                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                            <span class="text-[10px] font-mono text-slate-500">CTA: {{ $slide->button_text ?? 'Default' }}</span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="openEditHero({{ json_encode($slide) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400" title="Edit Slide">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form method="POST" action="{{ route('admin.hero-slides.delete', $slide->id) }}" onsubmit="return confirm('Hapus slide hero banner ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/50 text-slate-400 hover:text-rose-400" title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 p-8 text-center bg-[#121822] rounded-xl border border-slate-800 text-slate-500 text-xs font-mono">
                    Belum ada slide hero banner. Tambahkan slide di atas untuk mengaktifkan carousel auto-sliding.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
     * TAB 2: PAGE CONTENTS & HEADLINES (HOME, PC LEMOT, ABOUT, HOW IT WORKS)
     * ========================================================================= -->
    <div x-show="tab === 'pages'" class="space-y-6">
        <div class="p-4 rounded-xl bg-amber-950/30 border border-amber-800/40 text-xs text-amber-300 font-sans flex items-center gap-3">
            <span class="material-symbols-outlined text-lg text-amber-400 shrink-0">info</span>
            <span>Seluruh teks utama, headline beranda, deskripsi PC lemot, dan foto laboratorium di halaman web dapat diubah di sini tanpa reload server.</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($pageContents as $content)
                <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-amber-950 text-amber-400 border border-amber-800/50 uppercase">
                                {{ $content->page }} &bull; {{ $content->section_key }}
                            </span>
                            <h4 class="font-heading font-bold text-white text-base mt-1">{{ $content->title }}</h4>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.page-contents.update', $content->id) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                        @csrf
                        @method('PUT')

                        @if($content->imageUrl())
                            <div class="space-y-1">
                                <label class="block font-medium text-slate-400">Foto / Banner Saat Ini:</label>
                                <div class="relative rounded-lg overflow-hidden border border-slate-700 max-h-48 w-full group">
                                    <img src="{{ $content->imageUrl() }}" alt="{{ $content->title }}" class="w-full h-44 object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-xs text-white font-mono">
                                        Ganti file foto di formulir bawah
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block font-medium text-slate-300">Badge Tagline</label>
                                <input type="text" name="badge" value="{{ $content->badge }}" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                            </div>

                            <div class="space-y-1">
                                <label class="block font-medium text-slate-300">Judul Utama *</label>
                                <input type="text" name="title" required value="{{ $content->title }}" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block font-medium text-slate-300">Sub-judul / Ringkasan</label>
                            <textarea name="subtitle" rows="2" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]">{{ $content->subtitle }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="block font-medium text-slate-300">Konten Paragraf Lengkap</label>
                            <textarea name="content" rows="3" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]">{{ $content->content }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="block font-medium text-slate-300">Ganti Foto Bagian Ini (JPG, PNG, WEBP)</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-[0_0_12px_rgba(245,158,11,0.25)] flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">save</span>
                                <span>Simpan Perubahan Teks & Foto</span>
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="col-span-2 p-8 text-center bg-[#121822] rounded-xl border border-slate-800 text-slate-500 text-xs font-mono">
                    Belum ada data konten halaman.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
     * TAB 3: WORKFLOW STEPS (CARA KERJA 1-6)
     * ========================================================================= -->
    <div x-show="tab === 'workflow'" class="space-y-6">
        <!-- Add Workflow Step Form -->
        <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">format_list_numbered</span>
                    <span>Tambah Langkah Alur Pengerjaan (Cara Kerja)</span>
                </h3>
                <span class="text-[11px] font-mono text-slate-400">Ditampilkan di Halaman /cara-kerja</span>
            </div>

            <form method="POST" action="{{ route('admin.workflow-steps.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nomor Langkah (1-6) *</label>
                        <input type="number" name="step_number" required min="1" max="20" value="{{ count($workflowSteps) + 1 }}" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Estimasi Durasi</label>
                        <input type="text" name="duration" placeholder="cth. 10 - 20 Menit" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tag / Kategori</label>
                        <input type="text" name="tag" placeholder="cth. Pure OS Installation" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Icon Material Symbols</label>
                        <input type="text" name="icon" placeholder="cth. terminal, troubleshoot, tune" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Judul Langkah *</label>
                    <input type="text" name="title" required placeholder="cth. Isolasi Partisi & Checklist Data Aman" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Teknis SOP *</label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan prosedur pengerjaan langkah ini..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Catatan Khusus SOP</label>
                        <input type="text" name="note" placeholder="cth. Nol toleransi salah format drive data pelanggan." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Foto / Ilustrasi Pengerjaan (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <span>Tampilkan di roadmap cara kerja publik</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-[0_0_12px_rgba(245,158,11,0.25)] flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        <span>Simpan Langkah Kerja</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Workflow Steps List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($workflowSteps as $ws)
                <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 relative flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-amber-950 text-amber-400 border border-amber-800/40">
                                LANGKAH #{{ $ws->step_number }}
                            </span>
                            <span class="text-[11px] font-mono text-slate-400">{{ $ws->duration }}</span>
                        </div>

                        @if($ws->imageUrl())
                            <div class="mb-3 rounded-lg overflow-hidden border border-slate-800">
                                <img src="{{ $ws->imageUrl() }}" alt="{{ $ws->title }}" class="w-full h-28 object-cover">
                            </div>
                        @endif

                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-amber-400">
                                <span class="material-symbols-outlined text-lg">{{ $ws->icon ?: 'terminal' }}</span>
                            </span>
                            <h4 class="font-heading font-bold text-white text-sm line-clamp-1">{{ $ws->title }}</h4>
                        </div>

                        <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed mb-3">{{ $ws->description }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                        <span class="text-[10px] font-mono {{ $ws->is_active ? 'text-emerald-400' : 'text-slate-500' }}">
                            {{ $ws->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>

                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="openEditWorkflow({{ json_encode($ws) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400" title="Edit Langkah">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.workflow-steps.delete', $ws->id) }}" onsubmit="return confirm('Hapus langkah alur cara kerja ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/50 text-slate-400 hover:text-rose-400" title="Hapus">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center bg-[#121822] rounded-xl border border-slate-800 text-slate-500 text-xs font-mono">
                    Belum ada langkah alur cara kerja tersimpan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
     * TAB 4: ABOUT PILLARS & EQUIPMENT (TENTANG KAMI)
     * ========================================================================= -->
    <div x-show="tab === 'pillars'" class="space-y-6">
        <!-- Add Pillar Form -->
        <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">shield</span>
                    <span>Tambah Pilar Keamanan / Alat Lab Tentang Kami</span>
                </h3>
                <span class="text-[11px] font-mono text-slate-400">Ditampilkan di Halaman /tentang-kami</span>
            </div>

            <form method="POST" action="{{ route('admin.about-pillars.store') }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tipe Konten *</label>
                        <select name="type" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                            <option value="pillar">Pilar Standar & Keamanan</option>
                            <option value="equipment">Fasilitas & Tool Laboratorium</option>
                            <option value="metric">Metrik Statistik</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Icon Material Symbols</label>
                        <input type="text" name="icon" placeholder="cth. shield, verified, memory, speed" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Highlight / Nilai Kunci</label>
                        <input type="text" name="highlight" placeholder="cth. 100%, 14 Hari, NVMe 3.2" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Urutan Tampil (Order)</label>
                        <input type="number" name="order" value="{{ count($aboutPillars) + 1 }}" min="0" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Judul Pilar / Alat *</label>
                        <input type="text" name="title" required placeholder="cth. Protokol Keamanan Partisi Data" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Sub-judul / Penjelasan Singkat</label>
                        <input type="text" name="subtitle" placeholder="cth. Prioritas mutlak partisi dokumen terlindungi 100%" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Rinci</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan secara spesifik standar atau spesifikasi alat..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <span>Tampilkan di halaman tentang kami publik</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-[0_0_12px_rgba(245,158,11,0.25)] flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add_task</span>
                        <span>Simpan Pilar / Fasilitas</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Pillars & Equipment List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($aboutPillars as $ap)
                <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase {{ $ap->type === 'pillar' ? 'bg-amber-950 text-amber-400 border border-amber-800/40' : 'bg-emerald-950 text-emerald-400 border border-emerald-800/40' }}">
                                {{ $ap->type }}
                            </span>
                            @if($ap->highlight)
                                <span class="text-xs font-mono font-bold text-amber-400">{{ $ap->highlight }}</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-amber-400 shrink-0">
                                <span class="material-symbols-outlined text-lg">{{ $ap->icon ?: 'verified' }}</span>
                            </span>
                            <h4 class="font-heading font-bold text-white text-sm line-clamp-1">{{ $ap->title }}</h4>
                        </div>

                        @if($ap->subtitle)
                            <p class="text-[11px] text-amber-400/90 font-mono mb-2 line-clamp-1">{{ $ap->subtitle }}</p>
                        @endif

                        <p class="text-xs text-slate-400 line-clamp-3 leading-relaxed mb-3">{{ $ap->description }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                        <span class="text-[10px] font-mono {{ $ap->is_active ? 'text-emerald-400' : 'text-slate-500' }}">
                            {{ $ap->is_active ? 'AKTIF' : 'NONAKTIF' }}
                        </span>

                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="openEditPillar({{ json_encode($ap) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400" title="Edit">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.about-pillars.delete', $ap->id) }}" onsubmit="return confirm('Hapus pilar/fasilitas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/50 text-slate-400 hover:text-rose-400" title="Hapus">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center bg-[#121822] rounded-xl border border-slate-800 text-slate-500 text-xs font-mono">
                    Belum ada pilar atau fasilitas tersimpan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
     * TAB 5: PORTOFOLIO & GALERI (WITH PHOTO REPLACEMENT)
     * ========================================================================= -->
    <div x-show="tab === 'gallery'" class="space-y-6">
        <!-- Add Gallery Form -->
        <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">add_photo_alternate</span>
                    <span>Tambah Dokumentasi / Portofolio Baru</span>
                </h3>
                <span class="text-[11px] font-mono text-slate-400">Langsung Tampil di Web Publik</span>
            </div>

            <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Judul Dokumentasi Portofolio *</label>
                        <input type="text" name="title" required placeholder="cth. Instalasi Bersih Windows 11 ThinkPad T480" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">File Foto Portofolio (JPG, PNG, WEBP) *</label>
                        <input type="file" name="image" required accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Teknis Pengerjaan</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan kondisi sebelum & sesudah pengerjaan atau spesifikasi perangkat..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                        <input type="checkbox" name="active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <span>Tampilkan di halaman portofolio & beranda publik</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-[0_0_12px_rgba(245,158,11,0.25)] flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">upload</span>
                        <span>Unggah & Simpan Portofolio</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($galleries as $g)
                <div class="rounded-xl overflow-hidden bg-[#121822] border border-slate-800 group relative flex flex-col justify-between">
                    <div class="relative">
                        <img src="{{ $g->imageUrl() }}" alt="{{ $g->title }}" class="aspect-video w-full object-cover">
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded text-[10px] font-mono {{ $g->active ? 'bg-emerald-950/80 text-emerald-400 border border-emerald-800/40' : 'bg-rose-950/80 text-rose-400 border border-rose-800/40' }}">
                            {{ $g->active ? 'TAMPIL' : 'DISEMBUNYIKAN' }}
                        </span>
                    </div>

                    <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-heading font-bold text-white text-sm line-clamp-1">{{ $g->title }}</h4>
                            <p class="text-xs text-slate-400 line-clamp-2 mt-1">{{ $g->description }}</p>
                        </div>

                        <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                            <span class="text-[10px] font-mono text-slate-500">{{ $g->created_at ? $g->created_at->format('d M Y') : '' }}</span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="openEditGallery({{ json_encode($g) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400" title="Ganti Foto / Edit">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form method="POST" action="{{ route('admin.gallery.delete', $g->id) }}" onsubmit="return confirm('Hapus dokumentasi portofolio ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/50 text-slate-400 hover:text-rose-400" title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center bg-[#121822] rounded-xl border border-slate-800 text-slate-500 text-xs font-mono">
                    Belum ada dokumentasi portofolio.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
     * TAB 6: ULASAN & RATING BINTANG
     * ========================================================================= -->
    <div x-show="tab === 'testimonials'" class="space-y-6">
        <!-- Add Testimonial Form -->
        <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
            <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400">rate_review</span>
                <span>Tambah Ulasan / Testimoni Manual</span>
            </h3>

            <form method="POST" action="{{ route('admin.testimonials.store') }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nama Pelanggan *</label>
                        <input type="text" name="name" required placeholder="cth. Budi Pratama" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Peran / Perangkat</label>
                        <input type="text" name="role" placeholder="cth. Mahasiswa - Asus TUF Gaming" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Rating Bintang *</label>
                        <select name="rating" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-amber-400 font-bold focus:outline-none focus:border-[#F59E0B]">
                            <option value="5" selected>★★★★★ (5 Bintang Penuh)</option>
                            <option value="4">★★★★☆ (4 Bintang)</option>
                            <option value="3">★★★☆☆ (3 Bintang)</option>
                            <option value="2">★★☆☆☆ (2 Bintang)</option>
                            <option value="1">★☆☆☆☆ (1 Bintang)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Kutipan Ulasan / Pengalaman Servis *</label>
                    <textarea name="quote" rows="3" required placeholder="Tuliskan ulasan pelanggan secara jujur..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                        <input type="checkbox" name="active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <span>Tampilkan di halaman depan & galeri</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-sm">
                        Simpan Ulasan
                    </button>
                </div>
            </form>
        </div>

        <!-- Testimonials List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($testimonials as $t)
                <div class="rounded-xl bg-[#121822] border border-slate-800 p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-0.5 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-[16px] {{ $i <= $t->rating ? 'star-filled' : 'star-empty text-slate-700' }}">star</span>
                                @endfor
                                <span class="ml-1 text-xs font-mono font-bold text-amber-400">({{ $t->rating }})</span>
                            </div>

                            <span class="px-2 py-0.5 rounded text-[10px] font-mono {{ $t->active ? 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/40' : 'bg-slate-800 text-slate-500' }}">
                                {{ $t->active ? 'TAMPIL' : 'SEMBUNYI' }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-300 italic mb-4 font-sans leading-relaxed">"{{ $t->quote }}"</p>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                        <div>
                            <strong class="text-white block">{{ $t->name }}</strong>
                            <span class="text-[10px] font-mono text-slate-400">{{ $t->role ?? 'Pelanggan INULIN' }}</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="openEditTesti({{ json_encode($t) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400" title="Edit Ulasan">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.testimonials.delete', $t->id) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/40 text-slate-400 hover:text-rose-400" title="Hapus">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center bg-[#121822] rounded-xl border border-slate-800 text-slate-500 text-xs font-mono">
                    Belum ada ulasan / testimonial tersimpan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- =========================================================================
     * TAB 7: FAQ (PERTANYAAN UMUM)
     * ========================================================================= -->
    <div x-show="tab === 'faqs'" class="space-y-6">
        <!-- Add FAQ Form -->
        <div class="rounded-xl bg-[#121822] border border-slate-800 p-6 space-y-4">
            <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400">quiz</span>
                <span>Tambah Pertanyaan Umum (FAQ)</span>
            </h3>

            <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3 space-y-1">
                        <label class="block font-medium text-slate-300">Pertanyaan *</label>
                        <input type="text" name="question" required placeholder="cth. Apakah data di partisi D saya aman?" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Urutan Tampil</label>
                        <input type="number" name="sort_order" min="0" value="{{ count($faqs) + 1 }}" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white focus:outline-none focus:border-[#F59E0B]">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Jawaban Lengkap *</label>
                    <textarea name="answer" rows="3" required placeholder="Jawaban informatif untuk pelanggan..." class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white focus:outline-none focus:border-[#F59E0B]"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 select-none">
                        <input type="checkbox" name="active" value="1" checked class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <span>Tampilkan di halaman FAQ & beranda publik</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-mono text-xs font-bold transition-all shadow-sm">
                        Simpan FAQ
                    </button>
                </div>
            </form>
        </div>

        <!-- FAQs List -->
        <div class="rounded-xl border border-slate-800 bg-[#121822] overflow-hidden">
            <div class="p-4 border-b border-slate-800 text-xs font-mono text-slate-400">
                Daftar FAQ Aktif ({{ count($faqs) }})
            </div>
            <div class="divide-y divide-slate-800/80">
                @forelse($faqs as $f)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4 text-xs">
                        <div class="space-y-1 max-w-2xl">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-amber-400 font-semibold">#{{ $f->sort_order }}</span>
                                <h4 class="font-semibold text-white text-sm">{{ $f->question }}</h4>
                            </div>
                            <p class="text-slate-400 leading-relaxed">{{ $f->answer }}</p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono {{ $f->active ? 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/40' : 'bg-slate-800 text-slate-500' }}">
                                {{ $f->active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>

                            <button type="button" @click="openEditFaq({{ json_encode($f) }})" class="p-1.5 rounded bg-slate-800 hover:bg-slate-700 text-amber-400" title="Edit FAQ">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </button>

                            <form method="POST" action="{{ route('admin.faqs.delete', $f->id) }}" onsubmit="return confirm('Hapus FAQ ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded bg-slate-800 hover:bg-rose-950/40 text-slate-400 hover:text-rose-400" title="Hapus">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs font-mono">
                        Belum ada FAQ tersimpan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- =========================================================================
     * MODAL 1: EDIT HERO SLIDE (WITH PHOTO REPLACEMENT)
     * ========================================================================= -->
    <div x-show="editHeroModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="editHeroModal = false" class="bg-[#121822] border border-slate-700 rounded-2xl w-full max-w-xl p-6 sm:p-7 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-white text-base flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">view_carousel</span>
                    <span>Edit Slide Hero Banner</span>
                </h3>
                <button type="button" @click="editHeroModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'{{ url('admin/hero-slides') }}/' + selectedHero.id" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Badge Label</label>
                        <input type="text" name="badge" x-model="selectedHero.badge" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="sm:col-span-2 space-y-1">
                        <label class="block font-medium text-slate-300">Judul Slide *</label>
                        <input type="text" name="title" x-model="selectedHero.title" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Sub-judul</label>
                    <textarea name="subtitle" rows="2" x-model="selectedHero.subtitle" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Teks Tombol</label>
                        <input type="text" name="button_text" x-model="selectedHero.button_text" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Link Tombol</label>
                        <input type="text" name="button_link" x-model="selectedHero.button_link" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Urutan (Order)</label>
                        <input type="number" name="order" x-model="selectedHero.order" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Ganti File Foto Slide (JPG, PNG, WEBP)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" :checked="selectedHero.is_active" id="edit_hero_active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="edit_hero_active" class="text-slate-300 cursor-pointer select-none">Aktifkan di banner carousel</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editHeroModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-bold">Simpan Slide</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========================================================================
     * MODAL 2: EDIT WORKFLOW STEP
     * ========================================================================= -->
    <div x-show="editWorkflowModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="editWorkflowModal = false" class="bg-[#121822] border border-slate-700 rounded-2xl w-full max-w-xl p-6 sm:p-7 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-white text-base flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">schema</span>
                    <span>Edit Langkah Alur Pengerjaan</span>
                </h3>
                <button type="button" @click="editWorkflowModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'{{ url('admin/workflow-steps') }}/' + selectedWorkflow.id" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nomor Langkah *</label>
                        <input type="number" name="step_number" x-model="selectedWorkflow.step_number" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Durasi</label>
                        <input type="text" name="duration" x-model="selectedWorkflow.duration" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tag SOP</label>
                        <input type="text" name="tag" x-model="selectedWorkflow.tag" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2 space-y-1">
                        <label class="block font-medium text-slate-300">Judul Langkah *</label>
                        <input type="text" name="title" x-model="selectedWorkflow.title" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Icon</label>
                        <input type="text" name="icon" x-model="selectedWorkflow.icon" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi SOP *</label>
                    <textarea name="description" rows="3" x-model="selectedWorkflow.description" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Catatan Khusus SOP</label>
                    <input type="text" name="note" x-model="selectedWorkflow.note" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Ganti Foto SOP (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" :checked="selectedWorkflow.is_active" id="edit_wf_active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="edit_wf_active" class="text-slate-300 cursor-pointer select-none">Tampilkan di alur cara kerja publik</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editWorkflowModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-bold">Simpan Langkah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========================================================================
     * MODAL 3: EDIT ABOUT PILLAR & EQUIPMENT
     * ========================================================================= -->
    <div x-show="editPillarModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="editPillarModal = false" class="bg-[#121822] border border-slate-700 rounded-2xl w-full max-w-xl p-6 sm:p-7 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-white text-base flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">shield</span>
                    <span>Edit Pilar / Fasilitas Laboratorium</span>
                </h3>
                <button type="button" @click="editPillarModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'{{ url('admin/about-pillars') }}/' + selectedPillar.id" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Tipe Konten *</label>
                        <select name="type" x-model="selectedPillar.type" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                            <option value="pillar">Pilar Standar</option>
                            <option value="equipment">Alat Lab</option>
                            <option value="metric">Metrik</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Icon Material</label>
                        <input type="text" name="icon" x-model="selectedPillar.icon" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Highlight</label>
                        <input type="text" name="highlight" x-model="selectedPillar.highlight" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Judul *</label>
                    <input type="text" name="title" x-model="selectedPillar.title" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Sub-judul</label>
                    <input type="text" name="subtitle" x-model="selectedPillar.subtitle" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Rinci</label>
                    <textarea name="description" rows="3" x-model="selectedPillar.description" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 items-center">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Urutan (Order)</label>
                        <input type="number" name="order" x-model="selectedPillar.order" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white">
                    </div>

                    <div class="flex items-center gap-2 pt-5">
                        <input type="checkbox" name="is_active" value="1" :checked="selectedPillar.is_active" id="edit_pillar_active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                        <label for="edit_pillar_active" class="text-slate-300 cursor-pointer select-none">Tampilkan di web</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editPillarModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========================================================================
     * MODAL 4: EDIT GALLERY (WITH PHOTO REPLACEMENT)
     * ========================================================================= -->
    <div x-show="editGalleryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="editGalleryModal = false" class="bg-[#121822] border border-slate-700 rounded-2xl w-full max-w-xl p-6 sm:p-7 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-white text-base flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">edit_square</span>
                    <span>Edit Dokumentasi Portofolio</span>
                </h3>
                <button type="button" @click="editGalleryModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'{{ url('admin/gallery') }}/' + selectedGallery.id" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Judul Dokumentasi *</label>
                    <input type="text" name="title" x-model="selectedGallery.title" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Deskripsi Pengerjaan</label>
                    <textarea name="description" rows="3" x-model="selectedGallery.description" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Ganti File Foto Portofolio (JPG, PNG, WEBP)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-1.5 text-slate-300 font-mono file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-amber-950 file:text-amber-400 file:text-xs">
                    <span class="text-[10px] text-slate-500 font-mono">Biarkan kosong jika tidak ingin mengubah foto saat ini.</span>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" :checked="selectedGallery.active" id="edit_gallery_active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="edit_gallery_active" class="text-slate-300 cursor-pointer select-none">Tampilkan di halaman publik</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editGalleryModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-bold">Simpan Portofolio</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========================================================================
     * MODAL 5: EDIT TESTIMONIAL
     * ========================================================================= -->
    <div x-show="editTestiModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="editTestiModal = false" class="bg-[#121822] border border-slate-700 rounded-2xl w-full max-w-xl p-6 sm:p-7 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-white text-base flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">rate_review</span>
                    <span>Edit Ulasan & Rating Pelanggan</span>
                </h3>
                <button type="button" @click="editTestiModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'{{ url('admin/testimonials') }}/' + selectedTesti.id" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Nama Pelanggan *</label>
                        <input type="text" name="name" x-model="selectedTesti.name" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Peran / Perangkat</label>
                        <input type="text" name="role" x-model="selectedTesti.role" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Rating Bintang *</label>
                        <select name="rating" x-model="selectedTesti.rating" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-amber-400 font-bold">
                            <option value="5">★★★★★ (5)</option>
                            <option value="4">★★★★☆ (4)</option>
                            <option value="3">★★★☆☆ (3)</option>
                            <option value="2">★★☆☆☆ (2)</option>
                            <option value="1">★☆☆☆☆ (1)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Kutipan Ulasan *</label>
                    <textarea name="quote" rows="3" x-model="selectedTesti.quote" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" :checked="selectedTesti.active" id="edit_testi_active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="edit_testi_active" class="text-slate-300 cursor-pointer select-none">Tampilkan di halaman publik</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editTestiModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-bold">Simpan Ulasan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========================================================================
     * MODAL 6: EDIT FAQ
     * ========================================================================= -->
    <div x-show="editFaqModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.away="editFaqModal = false" class="bg-[#121822] border border-slate-700 rounded-2xl w-full max-w-xl p-6 sm:p-7 space-y-5 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-heading font-bold text-white text-base flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-400">quiz</span>
                    <span>Edit Pertanyaan Umum (FAQ)</span>
                </h3>
                <button type="button" @click="editFaqModal = false" class="text-slate-400 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'{{ url('admin/faqs') }}/' + selectedFaq.id" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-3 space-y-1">
                        <label class="block font-medium text-slate-300">Pertanyaan *</label>
                        <input type="text" name="question" x-model="selectedFaq.question" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 text-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block font-medium text-slate-300">Urutan</label>
                        <input type="number" name="sort_order" x-model="selectedFaq.sort_order" class="w-full bg-[#070A0F] border border-slate-800 rounded-lg px-3 py-2 font-mono text-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block font-medium text-slate-300">Jawaban *</label>
                    <textarea name="answer" rows="3" x-model="selectedFaq.answer" required class="w-full bg-[#070A0F] border border-slate-800 rounded-lg p-3 text-white"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" :checked="selectedFaq.active" id="edit_faq_active" class="rounded bg-[#070A0F] border-slate-700 text-[#F59E0B]">
                    <label for="edit_faq_active" class="text-slate-300 cursor-pointer select-none">Tampilkan di halaman publik</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                    <button type="button" @click="editFaqModal = false" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#F59E0B] hover:bg-amber-300 text-slate-950 font-bold">Simpan FAQ</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
