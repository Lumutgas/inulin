<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use App\Models\Service;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Business Setting
        BusinessSetting::updateOrCreate(['id' => 1], [
            'business_name' => 'INULIN',
            'tagline' => 'Instal Ulang Tanpa Ribet.',
            'description' => 'Laboratorium instalasi sistem operasi Windows & Linux, konfigurasi driver hardware resmi, dan optimasi performa laptop atau PC.',
            'whatsapp' => '6285165017620',
            'phone' => '085165017620',
            'email' => 'halo@inulin.id',
            'address' => 'Jl. Kaliurang KM 5, Depok, Sleman, D.I. Yogyakarta 55281',
            'maps_link' => 'https://maps.google.com',
            'social_links' => [
                'instagram' => 'https://instagram.com/inulin.id',
                'github' => 'https://github.com/inulin-tech'
            ],
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            'opening_time' => '09:00',
            'closing_time' => '18:00',
            'booking_duration' => 60,
            'max_bookings_per_slot' => 2,
        ]);

        // Services
        $services = [
            [
                'name' => 'Instal Ulang Windows',
                'slug' => 'instal-ulang-windows',
                'description' => 'Restorasi performa menyeluruh melalui instalasi murni master resmi Microsoft. Bebas bloatware OEM, isolasi proteksi partisi data pribadi, kalibrasi driver arsitektur presisi, serta garansi stabilitas 14 hari.',
                'price' => 50000,
                'duration' => 60,
                'included_items' => [
                    'Sistem Operasi Resmi (64-Bit)',
                    'Driver Lengkap & Teruji (Chipset, GPU, Wi-Fi, Audio)',
                    'Paket Utilitas Kerja Esensial (Office, Browser, PDF, Media)',
                    'Sanitasi & Optimasi Startup',
                    'Pemeriksaan Kesehatan Storage (SMART SSD/HDD)',
                    'Garansi Layanan 14 Hari Penuh'
                ],
                'supported_os' => [
                    'Windows 11 Pro (23H2 / 24H2)',
                    'Windows 11 Home',
                    'Windows 10 Pro 64-bit',
                    'Windows 10 Enterprise LTSC'
                ],
                'active' => true,
            ],
            [
                'name' => 'Instal Linux & Dual Boot',
                'slug' => 'instal-linux-dual-boot',
                'description' => 'Ubuntu, Debian, Linux Mint, Fedora, Arch Linux, hingga Kali Linux. Konfigurasi GRUB dan partisi aman berdampingan dengan Windows tanpa risiko data corrupt.',
                'price' => 65000,
                'duration' => 75,
                'included_items' => [
                    'Partisi manual swap, root, & home terisolasi',
                    'Driver Wi-Fi & proprietary GPU (NVIDIA / AMD)',
                    'Konfigurasi aman GRUB Bootloader Dual-Boot',
                    'Setup dasar developer tools (Git, Curl, Build-essential)',
                    'Uji booting ulang kedua sistem operasi',
                    'Garansi Konfigurasi 14 Hari'
                ],
                'supported_os' => [
                    'Ubuntu LTS 24.04',
                    'Debian 12 Bookworm',
                    'Linux Mint 22',
                    'Fedora Workstation 40',
                    'Kali Linux 2024',
                    'Arch Linux'
                ],
                'active' => true,
            ],
            [
                'name' => 'Software & Driver Pack',
                'slug' => 'software-driver-pack',
                'description' => 'Pemasangan aplikasi spesifik untuk kebutuhan desain, programming, editing video, atau office lengkap. Termasuk pembaruan driver hardware versi paling stabil.',
                'price' => 35000,
                'duration' => 45,
                'included_items' => [
                    'Setup toolchain dev (VS Code, Git, Docker, Node.js, Python)',
                    'Paket aplikasi produktivitas & utility lengkap',
                    'Fix error driver audio, touchpad, & bluetooth',
                    'Redistributables C++, DirectX, & .NET Runtime',
                    'Pembersihan cache & temporary registry'
                ],
                'supported_os' => [
                    'Windows 10',
                    'Windows 11',
                    'Linux Distributions'
                ],
                'active' => true,
            ],
            [
                'name' => 'Deep Cleaning & Thermal Paste',
                'slug' => 'deep-cleaning-thermal-paste',
                'description' => 'Bebaskan laptop dari overheat dan suara kipas berisik. Pembersihan menyeluruh heatsink dan penggantian pasta pendingin dengan pasta Arctic MX-4 non-conductive premium.',
                'price' => 75000,
                'duration' => 60,
                'included_items' => [
                    'Turunkan suhu CPU/GPU hingga 10-18°C',
                    'Pembersihan debu jalur exhaust dan bilah kipas',
                    'Aplikasi pasta pendingin Arctic MX-4 original',
                    'Pemeriksaan thermal pad komponen VRAM / VRM',
                    'Stress test suhu sebelum & sesudah repasting'
                ],
                'supported_os' => [
                    'Semua Laptop (Gaming, Ultrabook, Office)',
                    'PC Desktop'
                ],
                'active' => true,
            ],
            [
                'name' => 'Upgrade SSD & RAM',
                'slug' => 'upgrade-ssd-ram',
                'description' => 'Tingkatkan performa laptop jadul jadi 10x lebih ngebut dengan SSD NVMe/SATA dan RAM ekstra. Tersedia opsi cloning tanpa perlu instal ulang dari awal.',
                'price' => 45000,
                'duration' => 60,
                'included_items' => [
                    'Opsi cloning bit-by-bit (OS & data tetap utuh)',
                    'Pemasangan caddy HDD sekunder / slot M.2 NVMe',
                    'Verifikasi aktivasi dual-channel RAM',
                    'Benchmark kecepatan transfer storage baru',
                    'Garansi pemasangan hardware 14 hari'
                ],
                'supported_os' => [
                    'Windows 10',
                    'Windows 11',
                    'Linux'
                ],
                'active' => true,
            ],
            [
                'name' => 'Troubleshooting & Recovery',
                'slug' => 'troubleshooting-recovery',
                'description' => 'Penanganan Blue Screen (BSOD), black screen, stuck bootloop, infeksi malware, hingga data hilang akibat partisi corrupt yang tidak terbaca.',
                'price' => 50000,
                'duration' => 90,
                'included_items' => [
                    'Diagnosa mendalam error code Windows / Linux kernel',
                    'Penyelamatan dokumen data penting sebelum tindakan',
                    'Reparasi bootloader EFI & registry rusak',
                    'Sanitasi malware laten tanpa merusak file kerja',
                    'Laporan hasil inspeksi hardware teknis'
                ],
                'supported_os' => [
                    'Windows 10',
                    'Windows 11',
                    'Linux Systems'
                ],
                'active' => true,
            ],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(['slug' => $srv['slug']], $srv);
        }

        // FAQs
        $faqs = [
            [
                'question' => 'Apakah data di partisi D atau E saya akan hilang saat instal ulang?',
                'answer' => 'Tidak. Proses clean install di INULIN hanya memformat partisi sistem operasi (Drive C). Seluruh partisi data sekunder (Drive D, E, dan folder pribadi lainnya) 100% aman dan terisolasi. Teknisi kami selalu memverifikasi mapping drive sebelum eksekusi.',
                'sort_order' => 1,
                'active' => true,
            ],
            [
                'question' => 'Berapa lama proses pengerjaan instalasi di lab?',
                'answer' => 'Estimasi durasi rata-rata pengerjaan instal ulang adalah 60 hingga 90 menit. Hal ini mencakup proses instalasi OS murni, instalasi driver resmi seluruh komponen, setup software esensial, hingga tahapan pengujian stabilitas akhir.',
                'sort_order' => 2,
                'active' => true,
            ],
            [
                'question' => 'Apakah driver laptop saya sudah langsung lengkap terpasang?',
                'answer' => 'Ya, seluruh driver resmi manufaktur (chipset motherboard, kartu grafis diskrit/integrated, audio Realtek, Wi-Fi 6, Bluetooth, baterai, dan touchpad presisi) akan dipasang dan dikalibrasi hingga bebas tanda seru kuning di Device Manager.',
                'sort_order' => 3,
                'active' => true,
            ],
            [
                'question' => 'Apakah ada garansi pasca instalasi?',
                'answer' => 'INULIN memberikan garansi layanan resmi selama 14 hari penuh. Apabila muncul kendala seperti crash driver, BSOD, atau ketidakstabilan pasca pengerjaan, Anda dapat membawanya kembali ke lab untuk ditangani tanpa biaya tambahan.',
                'sort_order' => 4,
                'active' => true,
            ],
            [
                'question' => 'Bagaimana sistem pembayaran di INULIN?',
                'answer' => 'Kami menggunakan sistem pembayaran manual setelah pengerjaan selesai diperiksa bersama teknisi di workshop (Cash atau QRIS). Tidak ada pembayaran di muka saat mengisi formulir booking.',
                'sort_order' => 5,
                'active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }

        // Testimonials
        $testimonials = [
            [
                'name' => 'Rian Ardiansyah',
                'role' => 'Mahasiswa / ASUS ROG Strix',
                'rating' => 5,
                'quote' => 'Laptop ROG sebelumnya lambat parah karena bloatware bawaan pabrik. Setelah clean install di INULIN, boot cuma 8 detik dan driver NVIDIA langsung sinkron. Sangat direkomendasikan!',
                'active' => true,
            ],
            [
                'name' => 'Dimas Wahyudi',
                'role' => 'Pekerja Remote / ThinkPad X1',
                'rating' => 5,
                'quote' => 'ThinkPad tua buat kerja kantor jadi sangat enteng dipasang Win 10 LTSC. Tarif Rp50rb sangat transparan dibanding buang waktu berjam-jam sendiri dan pusing cari driver audio.',
                'active' => true,
            ],
            [
                'name' => 'Nadia Savitri',
                'role' => 'Desainer Grafis / HP Pavilion',
                'rating' => 5,
                'quote' => 'Laptop kena virus di folder sistem C. Penjelasan teknisinya jelas dan partisi D saya benar-benar selamat 100%. Pengerjaan 1 jam 15 menit langsung selesai dan bergaransi.',
                'active' => true,
            ],
            [
                'name' => 'Fajar Bagaskara',
                'role' => 'Software Engineer / Acer Swift',
                'rating' => 5,
                'quote' => 'Instalasi bersih tanpa aplikasi aneh atau iklan yang sering nempel. Driver audio Dolby Atmos tetap aktif normal di laptop Acer. Kerjanya sangat rapi dan teliti.',
                'active' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['name' => $t['name']], $t);
        }

        // Hero Slides (Auto-slide showcase)
        $slides = [
            [
                'title' => 'Solusi Laptop Lemot & Beban Bloatware',
                'subtitle' => 'Restorasi performa menyeluruh. Laptop kembali responsif, booting kilat 8 detik, dan suhu lebih adem.',
                'badge' => 'OPTIMASI PERFORMA',
                'image' => 'hero/slide-pclemot.jpg',
                'button_text' => 'Booking Sekarang',
                'button_link' => '/book',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Instalasi Murni Master Resmi Microsoft',
                'subtitle' => 'Bebas virus, bebas Windows modifikasi/ghosting. Driver hardware komplit langsung dari manufaktur resmi.',
                'badge' => 'MASTER ISO ORIGINAL',
                'image' => 'hero/slide-clean-install.jpg',
                'button_text' => 'Katalog Windows',
                'button_link' => '/services',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Dual Boot Linux & Lingkungan Dev Siap Pakai',
                'subtitle' => 'Ubuntu, Debian, Mint, Arch, hingga Kali Linux. Partisi terisolasi aman berdampingan dengan Windows.',
                'badge' => 'DUAL BOOT & DEV READY',
                'image' => 'hero/slide-linux.jpg',
                'button_text' => 'Pilih Linux',
                'button_link' => '/book',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Workshop Laboratorium & Thermal Care',
                'subtitle' => 'Pengerjaan transparan, matras anti-statis ESD-safe, thermal paste grade premium Arctic & Noctua.',
                'badge' => 'STANDAR TEKNISI RESMI',
                'image' => 'hero/slide-workbench.jpg',
                'button_text' => 'Pelajari Cara Kerja',
                'button_link' => '/cara-kerja',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            \App\Models\HeroSlide::updateOrCreate(['title' => $slide['title']], $slide);
        }

        // Page Contents (Homepage PC Lemot Showcase, Intro, etc.)
        $contents = [
            [
                'page' => 'home',
                'section_key' => 'pclemot_showcase',
                'badge' => 'DIAGNOSTIK MASALAH UTAMA',
                'title' => 'Mengapa PC & Laptop Anda Menjadi Sangat Lemot?',
                'subtitle' => 'Penumpukan bloatware bawaan pabrik, fragmented registry, malware berkedok crack, dan sistem operasi usang membebani kerja prosesor serta RAM.',
                'content' => 'Clean install master murni di INULIN menghapus tuntas seluruh beban tak terlihat tersebut tanpa mengorbankan partisi dokumen pribadi Anda (D:\ / E:\). Laptop kembali segar dan ringan seperti baru keluar dari kardus.',
                'image' => 'images/pclemot-showcase.jpg',
                'order' => 1,
            ],
            [
                'page' => 'home',
                'section_key' => 'hero_headline',
                'badge' => 'Solusi Cepat & Terpercaya Laptop / PC',
                'title' => 'PC lemot? Instal Ulang Tanpa Ribet.',
                'subtitle' => 'Jasa instal ulang sistem operasi Windows dan Linux untuk laptop dan PC. Proses jelas, partisi data aman terisolasi, driver resmi pabrikan, dan dilindungi garansi 14 hari penuh.',
                'content' => 'Kombinasi teknisi berpengalaman, master ISO resmi, dan alat diagnostik lengkap siap memulihkan kinerja komputasi Anda.',
                'image' => null,
                'order' => 0,
            ],
            [
                'page' => 'about',
                'section_key' => 'about_story',
                'badge' => 'FILOSOFI & PROFIL LABORATORIUM',
                'title' => 'Menghadirkan Standar Baru untuk Instalasi Sistem Operasi.',
                'subtitle' => 'INULIN berawal dari keresahan terhadap maraknya jasa instal ulang komputer abal-abal yang memasang Windows modifikasi penuh bloatware, memakai crack activator berbahaya, serta sembrono memformat seluruh harddisk pelanggan.',
                'content' => 'Kami hadir sebagai laboratorium spesialis instalasi OS modern yang mengedepankan ketelitian teknis, isolasi keselamatan data partisi, dan kejujuran harga.',
                'image' => 'images/hero/slide-workbench.jpg',
                'order' => 0,
            ],
            [
                'page' => 'how_it_works',
                'section_key' => 'how_it_works_intro',
                'badge' => 'PROTOKOL TEKNIS SISTEMATIS',
                'title' => 'Alur Pengerjaan Laboratorium INULIN',
                'subtitle' => 'Dari meja pemesanan online hingga serah terima perangkat, kami menerapkan SOP yang presisi untuk menjamin integritas data dan kestabilan sistem operasi laptop Anda.',
                'content' => 'Transparan, terdokumentasi, dan diuji bersama sebelum Anda meninggalkan workshop.',
                'image' => 'images/hero/slide-clean-install.jpg',
                'order' => 0,
            ],
        ];

        foreach ($contents as $c) {
            \App\Models\PageContent::updateOrCreate(
                ['page' => $c['page'], 'section_key' => $c['section_key']],
                $c
            );
        }

        // Workflow Steps for Cara Kerja
        $steps = [
            [
                'step_number' => 1,
                'title' => 'Reservasi Jadwal & Spesifikasi',
                'duration' => '1 - 2 Menit',
                'tag' => 'Online Booking',
                'icon' => 'calendar_month',
                'description' => 'Pelanggan mengisi formulir online di website INULIN. Pilih paket layanan yang dibutuhkan, model perangkat laptop/PC, versi OS (Windows 11/10 atau distro Linux), serta jam kedatangan ke lab.',
                'note' => 'Harga otomatis terkunci di database (price snapshot) tanpa biaya siluman.',
                'order' => 1,
            ],
            [
                'step_number' => 2,
                'title' => 'Pemeriksaan Awal (Pre-Check Diagnostic)',
                'duration' => '10 Menit',
                'tag' => 'Workshop Intake',
                'icon' => 'troubleshoot',
                'description' => 'Saat unit diserahkan, teknisi kami melakukan uji fisik, verifikasi keyboard/layar, dan membaca status kesehatan storage (SMART SSD/HDD Health) via CrystalDiskInfo untuk memastikan storage tidak dalam kondisi bad sector parah.',
                'note' => 'Jika storage bermasalah, kami komunikasikan terlebih dahulu opsi ganti SSD sebelum instalasi dimulai.',
                'order' => 2,
            ],
            [
                'step_number' => 3,
                'title' => 'Isolasi Partisi & Checklist Data',
                'duration' => '5 Menit',
                'tag' => 'Data Safety',
                'icon' => 'folder_zip',
                'description' => 'Melalui terminal Command Prompt (diskpart), partisi sistem C:\ ditandai untuk dihapus bersih. Partisi data sekunder (D:\ / E:\ / dokumen kerja) dikunci agar tidak disentuh selama instalasi.',
                'note' => 'Protokol ketat: Nol toleransi salah format drive data pelanggan.',
                'order' => 3,
            ],
            [
                'step_number' => 4,
                'title' => 'Instalasi Master Murni UEFI GPT',
                'duration' => '15 - 25 Menit',
                'tag' => 'Pure OS Installation',
                'icon' => 'terminal',
                'description' => 'Instalasi master ISO resmi original menggunakan media USB 3.2 NVMe berkecepatan tinggi. Format tabel partisi GPT modern dan mode boot UEFI aktif untuk keamanan Secure Boot.',
                'note' => 'Bebas Windows Ghost abal-abal, tanpa aktivator KMS berbahaya, dan tanpa malware terselubung.',
                'order' => 4,
            ],
            [
                'step_number' => 5,
                'title' => 'Kalibrasi Driver OEM & Paket Utilitas Esensial',
                'duration' => '20 Menit',
                'tag' => 'Hardware Calibration',
                'icon' => 'tune',
                'description' => 'Pemasangan paket driver resmi sesuai model manufaktur (Chipset, GPU NVIDIA/AMD, Wi-Fi, Audio, Touchpad). Dilanjutkan instalasi software kerja dasar: Browser bebas iklan, LibreOffice / MS Office, WinRAR, PDF Viewer, dan video player.',
                'note' => 'Optimasi startup system agar laptop tidak lemot saat baru pertama kali dinyalakan.',
                'order' => 5,
            ],
            [
                'step_number' => 6,
                'title' => 'Stress Test, QC Checklist & Aktivasi Garansi',
                'duration' => '10 Menit',
                'tag' => 'Quality Control',
                'icon' => 'verified_user',
                'description' => 'Uji beban singkat (temperature & hardware stress test), verifikasi audio, mikrofon, kamera, dan port USB. Unit diserahkan kembali ke pelanggan beserta aktivasi garansi servis 14 hari penuh.',
                'note' => 'Dukungan WhatsApp teknisi siap membantu jika ada pertanyaan teknis pasca-instalasi.',
                'order' => 6,
            ],
        ];

        foreach ($steps as $s) {
            \App\Models\WorkflowStep::updateOrCreate(
                ['step_number' => $s['step_number']],
                $s
            );
        }

        // About Pillars for Tentang Kami
        $pillars = [
            [
                'type' => 'pillar',
                'title' => 'Protokol Keamanan Partisi Data',
                'icon' => 'shield',
                'description' => 'Kami sangat menghargai data dokumen, foto, skripsi, dan pekerjaan Anda. Sebelum proses instalasi, teknisi kami selalu melakukan mapping disk melalui command line (diskpart) untuk memastikan partisi data pribadi (D:\ atau E:\) dalam status terisolasi dan tidak terhapus.',
                'subtitle' => 'Prioritas mutlak partisi dokumen pelanggan terlindungi 100%.',
                'order' => 1,
            ],
            [
                'type' => 'pillar',
                'title' => 'Master Image Resmi Tanpa Bloatware',
                'icon' => 'verified',
                'description' => 'Kami tidak pernah menggunakan "Windows Ghost" atau ISO bajakan hasil modifikasi forum yang penuh iklan tersembunyi. Semua berkas instalasi bersumber langsung dari server resmi Microsoft (Windows 11 / 10) atau repository upstream resmi Linux (Ubuntu, Debian, Fedora, Arch).',
                'subtitle' => 'Bebas trialware yang membebani RAM dan baterai laptop.',
                'order' => 2,
            ],
            [
                'type' => 'pillar',
                'title' => 'Driver OEM Presisi & Akurat',
                'icon' => 'memory',
                'description' => 'Bukan sekadar asal nyala, seluruh subsistem perangkat keras—mulai dari chipset motherboard, GPU diskrit (NVIDIA/AMD), audio codec, hingga controller Wi-Fi/Bluetooth—dikalibrasi dengan driver resmi yang sesuai nomor seri model perangkat.',
                'subtitle' => 'Mencegah Blue Screen of Death (BSOD) dan menjaga efisiensi daya.',
                'order' => 3,
            ],
            [
                'type' => 'pillar',
                'title' => 'Transparansi Database & Tanpa Biaya Tersembunyi',
                'icon' => 'receipt_long',
                'description' => 'Biaya layanan kami tercantum secara terbuka di database sistem dan terkunci saat Anda melakukan booking (price snapshot). Tidak ada biaya dadakan di akhir pengerjaan. Seluruh pengerjaan tercatat rapi di sistem administrasi kami.',
                'subtitle' => 'Tarif pasti sesuai website, bayar setelah laptop selesai dicek.',
                'order' => 4,
            ],
            [
                'type' => 'equipment',
                'title' => 'MEDIA INSTALASI CEPAT',
                'icon' => 'usb',
                'description' => 'Menggunakan flash drive USB 3.2 NVMe enclosure dengan kecepatan baca hingga 1050 MB/s. Proses penulisan master OS ke SSD laptop Anda tuntas dalam hitungan menit.',
                'subtitle' => 'Ventoy Multi-ISO • Rufus UEFI GPT',
                'order' => 1,
            ],
            [
                'type' => 'equipment',
                'title' => 'WORKSTATION ESD-SAFE',
                'icon' => 'build',
                'description' => 'Meja kerja servis dilapisi matras anti-statis dengan kabel grounding untuk mencegah Electrostatic Discharge (ESD) yang berpotensi merusak chip IC motherboard laptop.',
                'subtitle' => 'Grounding Strap • iFixit Precision Toolset',
                'order' => 2,
            ],
            [
                'type' => 'equipment',
                'title' => 'MATERIAL THERMAL PREMIUM',
                'icon' => 'mode_fan',
                'description' => 'Untuk layanan repaste thermal paste, kami hanya menggunakan pasta kelas atas (Arctic MX-4, Noctua NT-H1, Thermal Grizzly Kryonaut) dengan konduktivitas tinggi non-konduktif.',
                'subtitle' => 'Non-conductive • Long durability',
                'order' => 3,
            ],
        ];

        foreach ($pillars as $p) {
            \App\Models\AboutPillar::updateOrCreate(
                ['type' => $p['type'], 'title' => $p['title']],
                $p
            );
        }

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@inulin.test'],
            [
                'name' => 'Admin INULIN',
                'password' => 'password',
                'is_admin' => true,
            ]
        );
    }
}
