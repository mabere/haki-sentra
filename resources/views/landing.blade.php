<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app_setting('app_name', 'Sentra HKI') }} -
        {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ app_setting_logo('favicon') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Poppins & Inter -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            800: '#1e3a8a',
                            900: '#0f172a',
                            950: '#090d16',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-navy-900 selection:text-white">

    <!-- Top Bar Informasi Singkat -->
    <div class="bg-navy-950 text-slate-300 text-sm py-2 px-4 border-b border-navy-900">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Lembaga Penelitian & Pengabdian kepada Masyarakat (LPPM) Universitas Panca Sakti Bekasi</span>
            </div>
            <div class="flex items-center gap-5 text-slate-400">
                <span class="hidden md:inline">🕒 Jam Layanan: Sen - Jum (08.00 - 16.00 WIB)</span>
                <a href="mailto:sentrahki@pancasakti.ac.id" class="hover:text-gold-400 transition">✉️
                    sentrahki@pancasakti.ac.id</a>
            </div>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header
        class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-2xs transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Brand -->
                <a href="#beranda" class="flex items-center gap-3 group shrink-0">
                    <img src="{{ app_setting_logo('logo') }}"
                        alt="Logo {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}"
                        class="h-11 w-11 object-contain drop-shadow-sm group-hover:scale-105 transition-transform shrink-0">
                    <div>
                        <span
                            class="block font-heading font-bold text-base sm:text-lg text-navy-900 tracking-tight leading-none">{{ app_setting('app_name', 'Sentra HKI') }}</span>
                        <span
                            class="block text-[11px] font-medium text-slate-500 mt-1">{{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu (Rapi & Terstruktur dengan Grup Dropdown) -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                    <!-- 1. Beranda -->
                    <a href="#beranda"
                        class="px-3 py-2 rounded-xl text-sm font-semibold text-navy-900 hover:bg-slate-100 transition-colors">
                        Beranda
                    </a>

                    <!-- 2. Grup Profil & Layanan (Dropdown) -->
                    <div class="relative group">
                        <button type="button"
                            class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 flex items-center gap-1.5 transition-colors focus:outline-none">
                            <span>Profil & Layanan</span>
                            <i data-lucide="chevron-down"
                                class="w-4 h-4 text-slate-400 group-hover:text-navy-900 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            class="absolute left-0 top-full pt-2 w-72 hidden group-hover:block transition-all duration-200 z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                                <a href="#tentang"
                                    class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group/item">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                                        <i data-lucide="info" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold text-slate-900 group-hover/item:text-blue-600 transition-colors">
                                            Tentang Sentra HKI</div>
                                        <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Visi, peran & profil
                                            LPPM Universitas</p>
                                    </div>
                                </a>

                                <a href="#layanan"
                                    class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group/item">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-amber-600 group-hover/item:text-white transition-colors">
                                        <i data-lucide="layers" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold text-slate-900 group-hover/item:text-amber-600 transition-colors">
                                            Katalog Layanan HKI</div>
                                        <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Hak Cipta, Paten,
                                            Merek & Desain</p>
                                    </div>
                                </a>

                                <a href="#layanan"
                                    class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group/item">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                                        <i data-lucide="badge-percent" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold text-slate-900 group-hover/item:text-emerald-600 transition-colors">
                                            Biaya & Tarif Layanan</div>
                                        <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Tarif resmi
                                            pendaftaran DJKI Kemenkumham</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Grup Panduan & Dokumen (Dropdown) -->
                    <div class="relative group">
                        <button type="button"
                            class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 flex items-center gap-1.5 transition-colors focus:outline-none">
                            <span>Panduan & Berkas</span>
                            <i data-lucide="chevron-down"
                                class="w-4 h-4 text-slate-400 group-hover:text-navy-900 transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            class="absolute left-0 top-full pt-2 w-72 hidden group-hover:block transition-all duration-200 z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                                <a href="#alur"
                                    class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group/item">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-indigo-600 group-hover/item:text-white transition-colors">
                                        <i data-lucide="git-merge" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold text-slate-900 group-hover/item:text-indigo-600 transition-colors">
                                            Alur &amp; SOP Layanan</div>
                                        <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Tahapan usulan hingga
                                            terbit sertifikat</p>
                                    </div>
                                </a>

                                <a href="#dokumen"
                                    class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group/item">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-purple-600 group-hover/item:text-white transition-colors">
                                        <i data-lucide="file-down" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold text-slate-900 group-hover/item:text-purple-600 transition-colors">
                                            Unduh Template Berkas</div>
                                        <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Surat pengalihan,
                                            formulir &amp; format resmi</p>
                                    </div>
                                </a>

                                <a href="#faq"
                                    class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group/item">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-rose-600 group-hover/item:text-white transition-colors">
                                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold text-slate-900 group-hover/item:text-rose-600 transition-colors">
                                            Tanya Jawab (FAQ)</div>
                                        <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Pertanyaan seputar
                                            pendaftaran HKI</p>
                                    </div>
                                </a>

                                <div class="pt-1 border-t border-slate-100">
                                    <a href="{{ route('informasi.index') }}"
                                        class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50 hover:bg-navy-900 hover:text-white transition-colors group/item">
                                        <div
                                            class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-white/20 group-hover/item:text-white transition-colors">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-bold text-slate-900 group-hover/item:text-white transition-colors">
                                                Pusat Informasi Lengkap</div>
                                            <p
                                                class="text-[11px] text-slate-500 group-hover/item:text-slate-300 leading-tight mt-0.5">
                                                Semua panduan, unduhan &amp; pengumuman</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Lacak Permohonan -->
                    <a href="#lacak"
                        class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 transition-colors flex items-center gap-1.5">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                        <span>Lacak Status</span>
                    </a>

                    <!-- 5. Kontak -->
                    <a href="#kontak"
                        class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 transition-colors">
                        Kontak
                    </a>
                </nav>

                <!-- Auth & Action Buttons (Proporsional & Ringkas) -->
                <div class="hidden sm:flex items-center gap-2.5 shrink-0">
                    @auth
                        <!-- Jika User Sudah Login -->
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-300/80 bg-white hover:bg-slate-50 text-slate-800 font-medium text-sm transition shadow-2xs">
                            <div
                                class="w-6 h-6 rounded-lg bg-navy-900 text-gold-400 flex items-center justify-center font-bold text-[10px]">
                                {{ auth()->user()->initials() }}
                            </div>
                            <span class="max-w-[100px] xl:max-w-[140px] truncate font-semibold text-slate-800">
                                {{ auth()->user()->name }}
                            </span>
                            <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-slate-400"></i>
                        </a>

                        <a href="{{ route('dosen.ipr.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-navy-900 text-white font-semibold text-sm shadow-md shadow-navy-900/20 hover:bg-navy-800 transition transform hover:-translate-y-0.5">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-gold-400"></i>
                            <span>Ajukan HKI</span>
                        </a>
                    @else
                        <!-- Jika Belum Login (Guest) -->
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-300 text-slate-700 hover:text-navy-900 hover:bg-slate-50 font-semibold text-sm transition">
                            <i data-lucide="log-in" class="w-4 h-4 text-slate-500"></i>
                            <span>Masuk</span>
                        </a>

                        <button onclick="openModal('pengajuan')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-navy-900 text-white font-semibold text-sm shadow-md shadow-navy-900/20 hover:bg-navy-800 transition transform hover:-translate-y-0.5">
                            <i data-lucide="file-plus" class="w-4 h-4 text-gold-400"></i>
                            <span>Ajukan Usulan</span>
                        </button>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex lg:hidden items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="p-2 rounded-lg text-navy-900 hover:bg-slate-100"
                            title="Dashboard">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="p-2 rounded-lg text-slate-700 hover:bg-slate-100"
                            title="Masuk">
                            <i data-lucide="log-in" class="w-5 h-5"></i>
                        </a>
                    @endauth
                    <button id="mobile-menu-btn"
                        class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu"
            class="hidden lg:hidden bg-white border-b border-slate-200 px-4 pt-3 pb-5 space-y-3 shadow-lg">
            <div class="space-y-1">
                <a href="#beranda"
                    class="block px-3 py-2 rounded-xl text-sm font-semibold text-navy-900 hover:bg-slate-50">
                    Beranda
                </a>

                <!-- Grup 1: Profil & Layanan -->
                <div class="pt-2">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Profil & Layanan</p>
                    <div class="mt-1 space-y-0.5 pl-2">
                        <a href="#tentang"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                            <i data-lucide="info" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Tentang Sentra HKI</span>
                        </a>
                        <a href="#layanan"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                            <i data-lucide="layers" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span>Katalog Layanan HKI</span>
                        </a>
                        <a href="#layanan"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                            <i data-lucide="badge-percent" class="w-3.5 h-3.5 text-emerald-500"></i>
                            <span>Biaya & Tarif Layanan</span>
                        </a>
                    </div>
                </div>

                <!-- Grup 2: Panduan & Dokumen -->
                <div class="pt-2">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Panduan & Dokumen</p>
                    <div class="mt-1 space-y-0.5 pl-2">
                        <a href="#alur"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                            <i data-lucide="git-merge" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span>Alur & SOP Layanan</span>
                        </a>
                        <a href="#dokumen"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                            <i data-lucide="file-down" class="w-3.5 h-3.5 text-purple-500"></i>
                            <span>Unduh Template Berkas</span>
                        </a>
                        <a href="#faq"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">
                            <i data-lucide="help-circle" class="w-3.5 h-3.5 text-rose-500"></i>
                            <span>Tanya Jawab (FAQ)</span>
                        </a>
                    </div>
                </div>

                <div class="pt-2 space-y-1">
                    <a href="#lacak"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                        <span>Lacak Permohonan</span>
                    </a>
                    <a href="#kontak"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                        <span>Kontak LPPM</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Action Buttons -->
            <div class="pt-3 border-t border-slate-100 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 font-semibold text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-navy-800"></i>
                        <span>Dashboard ({{ auth()->user()->name }})</span>
                    </a>
                    <a href="{{ route('dosen.ipr.create') }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm shadow-md">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-gold-400"></i>
                        <span>Ajukan HKI Sekarang</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 font-semibold text-sm">
                        <i data-lucide="log-in" class="w-4 h-4 text-slate-500"></i>
                        <span>Masuk Portal Dosen / Verifikator</span>
                    </a>
                    <button onclick="openModal('pengajuan')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm shadow-md">
                        <i data-lucide="file-plus" class="w-4 h-4 text-gold-400"></i>
                        <span>Ajukan Usulan Cepat</span>
                    </button>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda"
        class="relative overflow-hidden bg-gradient-to-b from-navy-950 via-navy-900 to-navy-950 text-white py-20 lg:py-28">
        <!-- Background Glow Elements -->
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-gold-500 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -right-40 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/10 text-gold-400 text-sm font-medium backdrop-blur">
                        <i data-lucide="award" class="w-4 h-4"></i>
                        Pusat Layanan Resmi Hak Kekayaan Intelektual Universitas
                    </div>
                    <h1 class="font-heading font-bold text-3xl sm:text-5xl lg:text-6xl tracking-tight leading-tight">
                        Lindungi Inovasi, <br>Karya, & Riset <span
                            class="text-gold-500 underline decoration-gold-500/50 decoration-wavy">Intelektual</span>
                    </h1>
                    <p
                        class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto lg:mx-0 font-light leading-relaxed">
                        Sentra HKI Universitas Panca Sakti Bekasi siap mendampingi sivitas akademika dan masyarakat umum
                        dalam pengurusan Hak Cipta, Paten, Merek, dan Desain Industri secara cepat, legal, dan
                        profesional.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                        @auth
                            <a href="{{ route('dosen.ipr.create') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-base shadow-xl shadow-gold-500/20 transition-all transform hover:-translate-y-0.5">
                                <i data-lucide="arrow-right-circle" class="w-5 h-5"></i>
                                Mulai Pengajuan HKI
                            </a>
                        @else
                            <button onclick="openModal('pengajuan')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-base shadow-xl shadow-gold-500/20 transition-all transform hover:-translate-y-0.5">
                                <i data-lucide="arrow-right-circle" class="w-5 h-5"></i>
                                Mulai Pengajuan HKI
                            </button>
                        @endauth
                        <a href="#alur"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-medium text-base border border-white/20 backdrop-blur transition-all">
                            <i data-lucide="book-open" class="w-5 h-5 text-gold-400"></i>
                            Unduh Panduan SOP
                        </a>
                    </div>

                    <!-- Quick Search / Status Tracker -->
                    <div id="lacak" class="pt-6 max-w-xl mx-auto lg:mx-0 scroll-mt-24">
                        <div
                            class="bg-white/10 p-3 rounded-2xl border border-white/10 backdrop-blur flex items-center gap-2">
                            <div class="flex-1 flex items-center gap-2 px-3 bg-white/5 rounded-xl">
                                <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                                <input type="text" id="search-code"
                                    onkeydown="if(event.key==='Enter') checkStatus()"
                                    placeholder="Cek No. Permohonan (Cth: EC00202512345)..."
                                    class="w-full bg-transparent py-2.5 text-sm text-white placeholder-slate-400 focus:outline-none">
                            </div>
                            <button id="btn-search-status" onclick="checkStatus()"
                                class="px-5 py-2.5 bg-navy-800 hover:bg-navy-700 text-white font-medium text-sm rounded-xl transition-colors shrink-0 flex items-center gap-2">
                                <span>Cek Status</span>
                            </button>
                        </div>
                        <div id="search-result"
                            class="text-sm text-white mt-3 hidden bg-slate-900/95 p-4 rounded-2xl border border-slate-700/80 backdrop-blur-md shadow-2xl transition-all">
                        </div>
                    </div>
                </div>

                <!-- Right Illustration / Card -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <!-- Decorative box glow -->
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-gold-500 to-blue-600 rounded-3xl blur-2xl opacity-20 transform rotate-3">
                        </div>

                        <!-- Main Card -->
                        <div
                            class="relative bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-md space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gold-500/20 flex items-center justify-center text-gold-500">
                                        <i data-lucide="award" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white text-base">Layanan Prioritas</h3>
                                        <p class="text-sm text-slate-400">Sertifikasi Resmi Ditjen HKI</p>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-sm font-semibold border border-emerald-500/20">Aktif
                                    2026</span>
                            </div>

                            <div class="space-y-4">
                                <div
                                    class="flex items-start gap-4 p-3.5 rounded-2xl bg-slate-800/50 border border-slate-700/50">
                                    <div class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400 shrink-0">
                                        <i data-lucide="book" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-white">Hak Cipta (Copyright)</h4>
                                        <p class="text-sm text-slate-400 mt-0.5">Buku, Penelitian, Program Komputer,
                                            Karya Seni & Musik.</p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-start gap-4 p-3.5 rounded-2xl bg-slate-800/50 border border-slate-700/50">
                                    <div class="p-2.5 rounded-xl bg-amber-500/10 text-amber-400 shrink-0">
                                        <i data-lucide="cpu" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-white">Paten & Paten Sederhana</h4>
                                        <p class="text-sm text-slate-400 mt-0.5">Inovasi teknologi, alat peraga, dan
                                            proses industri.</p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-start gap-4 p-3.5 rounded-2xl bg-slate-800/50 border border-slate-700/50">
                                    <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400 shrink-0">
                                        <i data-lucide="tag" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-white">Merek Dagang & Jasa</h4>
                                        <p class="text-sm text-slate-400 mt-0.5">Logo produk, brand usaha mahasiswa &
                                            startup kampus.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <a href="#layanan"
                                    class="block w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-center text-sm font-medium text-white transition-colors">
                                    Lihat Semua Kategori Layanan &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Singkat (Stat Bar) -->
    <section class="relative z-20 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-6 sm:p-8 grid grid-cols-2 md:grid-cols-4 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            <div class="text-center px-4 py-2">
                <div class="inline-flex p-3 rounded-2xl bg-blue-50 text-blue-600 mb-3">
                    <i data-lucide="book-open-check" class="w-6 h-6"></i>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-heading text-navy-900">
                    {{ isset($stats['copyright_count']) && $stats['copyright_count'] > 0 ? $stats['copyright_count'] . '+' : '1,250+' }}
                </div>
                <div class="text-sm text-slate-500 font-medium mt-1">Hak Cipta Terdaftar</div>
            </div>
            <div class="text-center px-4 py-2 pt-4 sm:pt-2">
                <div class="inline-flex p-3 rounded-2xl bg-amber-50 text-amber-600 mb-3">
                    <i data-lucide="lightbulb" class="w-6 h-6"></i>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-heading text-navy-900">
                    {{ isset($stats['patent_count']) && $stats['patent_count'] > 0 ? $stats['patent_count'] . '+' : '145+' }}
                </div>
                <div class="text-sm text-slate-500 font-medium mt-1">Paten & Sederhana</div>
            </div>
            <div class="text-center px-4 py-2 pt-4 sm:pt-2">
                <div class="inline-flex p-3 rounded-2xl bg-emerald-50 text-emerald-600 mb-3">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-heading text-navy-900">
                    {{ isset($stats['brand_count']) && $stats['brand_count'] > 0 ? $stats['brand_count'] . '+' : '320+' }}
                </div>
                <div class="text-sm text-slate-500 font-medium mt-1">Merek & Desain Industri</div>
            </div>
            <div class="text-center px-4 py-2 pt-4 sm:pt-2">
                <div class="inline-flex p-3 rounded-2xl bg-purple-50 text-purple-600 mb-3">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="text-2xl sm:text-3xl font-bold font-heading text-navy-900">99.8%</div>
                <div class="text-sm text-slate-500 font-medium mt-1">Tingkat Keberhasilan</div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6">
                <div
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-navy-900/5 text-navy-900 text-sm font-semibold">
                    <i data-lucide="info" class="w-4 h-4 text-gold-600"></i>
                    Tentang Sentra HKI
                </div>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-navy-900 tracking-tight">
                    Mendorong Budaya Inovasi & Pelindungan Hukum Kampus
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Sentra Kekayaan Intelektual (HKI) Universitas Panca Sakti Bekasi adalah unit layanan resmi di bawah
                    Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) yang bertugas memberikan edukasi,
                    pendampingan, serta fasilitasi pendaftaran Hak Kekayaan Intelektual bagi seluruh sivitas akademika
                    dan masyarakat luas.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                        <div class="p-2 rounded-xl bg-navy-900 text-white shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-4 h-4 text-gold-500"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-navy-900">Konsultasi Ahli</h4>
                            <p class="text-sm text-slate-500 mt-0.5">Didampingi oleh konsultan HKI berpengalaman.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                        <div class="p-2 rounded-xl bg-navy-900 text-white shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-4 h-4 text-gold-500"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm text-navy-900">Proses Transparan</h4>
                            <p class="text-sm text-slate-500 mt-0.5">Tracking usulan secara online dan realtime.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-gold-500/10 rounded-3xl transform rotate-3"></div>
                    <div class="relative bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 rounded-2xl bg-navy-900 text-gold-500 flex items-center justify-center text-xl font-bold font-heading">
                                20
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-navy-900">Tahun Dedikasi Inovasi</h3>
                                <p class="text-sm text-slate-500">Melayani ratusan riset dan hak cipta dosen &
                                    mahasiswa.</p>
                            </div>
                        </div>
                        <blockquote class="text-slate-600 text-sm italic border-l-4 border-gold-500 pl-4 py-1">
                            "Perlindungan kekayaan intelektual bukan sekadar legalitas hukum, tetapi bentuk penghargaan
                            tertinggi terhadap kreativitas dan karya anak bangsa."
                        </blockquote>
                        <div
                            class="flex items-center justify-between pt-4 border-t border-slate-100 text-sm text-slate-500 font-medium">
                            <span>{{ app_setting('head_sentra_title', 'Ketua Sentra HKI Universitas') }}</span>
                            <span
                                class="text-navy-900 font-bold">{{ app_setting('head_sentra_name', 'Dr. H. Ahmad Fauzi, M.T.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Utama Section (Grid Cards) -->
    <section id="layanan" class="py-20 bg-slate-100/70 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-16">
                <span class="px-3.5 py-1.5 rounded-full bg-navy-900/5 text-navy-900 text-sm font-semibold">
                    Kategori Layanan
                </span>
                <h2 class="font-heading font-bold text-3xl sm:text-4xl text-navy-900 tracking-tight">
                    Pilih Layanan HKI yang Anda Butuhkan
                </h2>
                <p class="text-slate-600 text-sm sm:text-base">
                    Kami melayani berbagai jenis pendaftaran Kekayaan Intelektual dengan panduan persyaratan yang jelas
                    dan mudah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1: Hak Cipta -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-gold-500/50 transition-all duration-300 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-navy-900 group-hover:text-gold-500 transition-colors">
                            <i data-lucide="book-open" class="w-7 h-7"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Hak Cipta</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Perlindungan untuk karya tulis, buku, modul ajar, program komputer, seni rupa, musik, video,
                            dan riset ilmiah.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <button onclick="openModal('hak-cipta')"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-50 hover:bg-navy-900 hover:text-white text-navy-900 font-semibold text-sm transition-colors">
                            <span>Panduan & Syarat</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 2: Paten -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-gold-500/50 transition-all duration-300 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-navy-900 group-hover:text-gold-500 transition-colors">
                            <i data-lucide="cpu" class="w-7 h-7"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Paten & Sederhana</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Perlindungan invensi di bidang teknologi, mesin, alat tepat guna, maupun pembaharuan proses
                            produksi industri.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <button onclick="openModal('paten')"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-50 hover:bg-navy-900 hover:text-white text-navy-900 font-semibold text-sm transition-colors">
                            <span>Panduan & Syarat</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Merek -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-gold-500/50 transition-all duration-300 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-navy-900 group-hover:text-gold-500 transition-colors">
                            <i data-lucide="tag" class="w-7 h-7"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Merek Dagang</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Pendaftaran logo, nama produk, brand usaha kuliner, fashion, maupun jasa binaan inkubator
                            bisnis universitas.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <button onclick="openModal('merek')"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-50 hover:bg-navy-900 hover:text-white text-navy-900 font-semibold text-sm transition-colors">
                            <span>Panduan & Syarat</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 4: Desain Industri -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:border-gold-500/50 transition-all duration-300 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div
                            class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-navy-900 group-hover:text-gold-500 transition-colors">
                            <i data-lucide="layout" class="w-7 h-7"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Desain Industri</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Kreasi bentuk, konfigurasi, atau komposisi garis dan warna pada suatu produk kerajinan atau
                            manufaktur.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <button onclick="openModal('desain')"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-slate-50 hover:bg-navy-900 hover:text-white text-navy-900 font-semibold text-sm transition-colors">
                            <span>Panduan & Syarat</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Layanan / Prosedur (Step-by-Step Workflow Dinamis) -->
    <section id="alur" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto space-y-3 mb-16">
            <span class="px-3.5 py-1.5 rounded-full bg-navy-900/5 text-navy-900 text-sm font-semibold">
                SOP Pengajuan
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-navy-900 tracking-tight">
                Alur Mudah Pengajuan HKI
            </h2>
            <p class="text-slate-600 text-sm sm:text-base">
                Proses pendaftaran dirancang cepat dan transparan dalam tahapan utama yang terstandarisasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            @forelse($alurs as $index => $step)
                <div
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative space-y-4 hover:border-gold-500/50 transition flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div
                            class="w-12 h-12 rounded-2xl {{ $index === 0 ? 'bg-gold-500 text-navy-950 shadow-gold-500/30' : ($index === 3 ? 'bg-emerald-600 text-white shadow-emerald-600/30' : 'bg-navy-900 text-white') }} font-bold font-heading text-lg flex items-center justify-center shadow-md">
                            {{ sprintf('%02d', $step->order ?: $index + 1) }}
                        </div>
                        <h3 class="font-heading font-bold text-lg text-navy-900">{{ $step->title }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            {{ $step->description }}
                        </p>
                    </div>
                    @if ($step->content)
                        <div class="pt-2">
                            <a href="{{ route('informasi.show', $step->slug) }}"
                                class="text-sm font-semibold text-blue-600 hover:underline inline-flex items-center gap-1">
                                <span>Rincian Syarat</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <!-- Step 1 -->
                <div
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative space-y-4 hover:border-gold-500/50 transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gold-500 text-navy-950 font-bold font-heading text-lg flex items-center justify-center shadow-md shadow-gold-500/30">
                        01
                    </div>
                    <h3 class="font-heading font-bold text-lg text-navy-900">Konsultasi & Dokumen</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Pemohon menyiapkan draf karya/ciptaan dan berkas administratif pendukung (KTP, Surat Pernyataan
                        Keaslian).
                    </p>
                </div>

                <!-- Step 2 -->
                <div
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative space-y-4 hover:border-gold-500/50 transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-navy-900 text-white font-bold font-heading text-lg flex items-center justify-center shadow-md">
                        02
                    </div>
                    <h3 class="font-heading font-bold text-lg text-navy-900">Unggah ke Sistem</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Mengisi formulir pengajuan online melalui portal Sentra HKI universitas dan melampirkan berkas
                        scan asli.
                    </p>
                </div>

                <!-- Step 3 -->
                <div
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative space-y-4 hover:border-gold-500/50 transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-navy-900 text-white font-bold font-heading text-lg flex items-center justify-center shadow-md">
                        03
                    </div>
                    <h3 class="font-heading font-bold text-lg text-navy-900">Verifikasi & Review</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Tim ahli Sentra HKI melakukan pemeriksaan kelayakan dokumen sebelum diajukan resmi ke DJKI
                        Kementerian.
                    </p>
                </div>

                <!-- Step 4 -->
                <div
                    class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative space-y-4 hover:border-gold-500/50 transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-bold font-heading text-lg flex items-center justify-center shadow-md shadow-emerald-600/30">
                        04
                    </div>
                    <h3 class="font-heading font-bold text-lg text-navy-900">Terbit Sertifikat</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Sertifikat HKI resmi diterbitkan oleh DJKI dan dapat diunduh atau diambil langsung di kantor
                        Sentra HKI.
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Dokumen & Template Unduhan Section (Dinamis dari Database) -->
    <section id="dokumen" class="py-20 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <span class="px-3.5 py-1.5 rounded-full bg-white/10 text-gold-400 text-sm font-semibold">
                        Pusat Unduhan
                    </span>
                    <h2 class="font-heading font-bold text-3xl sm:text-4xl mt-3 text-white tracking-tight">
                        Template &amp; Dokumen Penting
                    </h2>
                    <p class="text-slate-400 text-sm mt-2">Unduh format surat pernyataan dan panduan resmi pendaftaran
                        HKI.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('informasi.index', ['kategori' => 'template_dokumen']) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-sm border border-slate-700 transition-all">
                        <i data-lucide="folder" class="w-4 h-4 text-gold-400"></i>
                        <span>Semua Dokumen</span>
                    </a>
                    @auth
                        <a href="{{ route('dosen.ipr.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-sm transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            Ajukan Permohonan Baru
                        </a>
                    @else
                        <button onclick="openModal('pengajuan')"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-sm transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            Ajukan Permohonan Baru
                        </button>
                    @endauth
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($templates as $doc)
                    <div
                        class="bg-slate-800/80 border border-slate-700 p-6 rounded-3xl flex items-center justify-between hover:border-slate-500 transition">
                        <div class="flex items-center gap-4">
                            <div class="p-3.5 rounded-2xl bg-blue-500/10 text-gold-400">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-white">{{ $doc->title }}</h4>
                                <p class="text-sm text-slate-400 mt-0.5">Format {{ $doc->file_type ?? 'DOCX' }} •
                                    {{ $doc->file_size ?? 'Resmi LPPM' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('informasi.unduh', $doc->slug) }}"
                            onclick="downloadFile('{{ addslashes($doc->title) }}')"
                            class="p-3 rounded-xl bg-slate-700 hover:bg-gold-500 hover:text-navy-950 text-white transition-colors flex items-center justify-center shrink-0 ml-2"
                            title="Unduh Berkas {{ $doc->file_type ?? 'Dokumen' }}">
                            <i data-lucide="download" class="w-5 h-5"></i>
                        </a>
                    </div>
                @empty
                    <div
                        class="bg-slate-800/80 border border-slate-700 p-6 rounded-3xl flex items-center justify-between hover:border-slate-500 transition">
                        <div class="flex items-center gap-4">
                            <div class="p-3.5 rounded-2xl bg-blue-500/10 text-blue-400">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-white">Surat Pernyataan Hak Cipta</h4>
                                <p class="text-sm text-slate-400 mt-0.5">Format DOCX • 245 KB</p>
                            </div>
                        </div>
                        <a href="{{ route('dokumen.unduh', 'surat-pernyataan-hak-cipta') }}"
                            onclick="downloadFile('Surat Pernyataan Hak Cipta')"
                            class="p-3 rounded-xl bg-slate-700 hover:bg-gold-500 hover:text-navy-950 text-white transition-colors flex items-center justify-center"
                            title="Unduh Berkas DOCX">
                            <i data-lucide="download" class="w-5 h-5"></i>
                        </a>
                    </div>

                    <div
                        class="bg-slate-800/80 border border-slate-700 p-6 rounded-3xl flex items-center justify-between hover:border-slate-500 transition">
                        <div class="flex items-center gap-4">
                            <div class="p-3.5 rounded-2xl bg-amber-500/10 text-amber-400">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-white">Panduan Pengajuan Paten</h4>
                                <p class="text-sm text-slate-400 mt-0.5">Format PDF • Resmi LPPM</p>
                            </div>
                        </div>
                        <a href="{{ route('dokumen.unduh', 'panduan-pengajuan-paten') }}"
                            onclick="downloadFile('Panduan Pengajuan Paten')"
                            class="p-3 rounded-xl bg-slate-700 hover:bg-gold-500 hover:text-navy-950 text-white transition-colors flex items-center justify-center"
                            title="Unduh Berkas PDF">
                            <i data-lucide="download" class="w-5 h-5"></i>
                        </a>
                    </div>

                    <div
                        class="bg-slate-800/80 border border-slate-700 p-6 rounded-3xl flex items-center justify-between hover:border-slate-500 transition">
                        <div class="flex items-center gap-4">
                            <div class="p-3.5 rounded-2xl bg-emerald-500/10 text-emerald-400">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-white">Formulir Pendaftaran Merek</h4>
                                <p class="text-sm text-slate-400 mt-0.5">Format DOCX • Resmi LPPM</p>
                            </div>
                        </div>
                        <a href="{{ route('dokumen.unduh', 'formulir-pendaftaran-merek') }}"
                            onclick="downloadFile('Formulir Pendaftaran Merek')"
                            class="p-3 rounded-xl bg-slate-700 hover:bg-gold-500 hover:text-navy-950 text-white transition-colors flex items-center justify-center"
                            title="Unduh Berkas DOCX">
                            <i data-lucide="download" class="w-5 h-5"></i>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ Section (Dinamis dari Database) -->
    <section id="faq" class="py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-3 mb-16">
            <span class="px-3.5 py-1.5 rounded-full bg-navy-900/5 text-navy-900 text-sm font-semibold">
                Pertanyaan Umum
            </span>
            <h2 class="font-heading font-bold text-3xl sm:text-4xl text-navy-900 tracking-tight">
                Frequently Asked Questions
            </h2>
            <p class="text-slate-600 text-sm sm:text-base">
                Temukan jawaban atas hal-hal yang sering ditanyakan seputar layanan HKI.
            </p>
        </div>

        <div class="space-y-4">
            @forelse($faqs as $faq)
                <details class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer">
                    <summary
                        class="flex justify-between items-center font-heading font-semibold text-navy-900 text-base list-none">
                        <span>{{ $faq->title }}</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <div class="text-slate-600 text-sm mt-3 pt-3 border-t border-slate-100 leading-relaxed space-y-2">
                        <p>{{ $faq->description ?: $faq->content }}</p>
                        @if ($faq->content && $faq->description && $faq->content !== $faq->description)
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-slate-700 text-sm">
                                {!! nl2br(e($faq->content)) !!}
                            </div>
                        @endif
                    </div>
                </details>
            @empty
                <details class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer">
                    <summary
                        class="flex justify-between items-center font-heading font-semibold text-navy-900 text-base list-none">
                        <span>Berapa lama proses penerbitan sertifikat Hak Cipta?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <p class="text-slate-600 text-sm mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                        Pengajuan Hak Cipta melalui jalur kampus dan verifikasi DJKI memakan waktu sekitar 7 -14 hari
                        kerja setelah berkas dinyatakan lengkap dan valid.
                    </p>
                </details>

                <details class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer">
                    <summary
                        class="flex justify-between items-center font-heading font-semibold text-navy-900 text-base list-none">
                        <span>Apakah mahasiswa bisa mendaftarkan Hak Cipta tugas akhir/skripsi?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <p class="text-slate-600 text-sm mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                        Sangat bisa! Mahasiswa Universitas Panca Sakti Bekasi sangat didorong untuk mendaftarkan tugas
                        akhir, skripsi, atau program komputer ciptaannya dengan pendampingan dari Sentra HKI.
                    </p>
                </details>

                <details class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm cursor-pointer">
                    <summary
                        class="flex justify-between items-center font-heading font-semibold text-navy-900 text-base list-none">
                        <span>Berapakah biaya pendaftaran melalui Sentra HKI Universitas?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <p class="text-slate-600 text-sm mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                        Biaya PNBP (Penerimaan Negara Bukan Pajak) disesuaikan dengan tarif resmi DJKI. Untuk sivitas
                        akademika universitas, tersedia tarif khusus dan fasilitasi bantuan subsidi dari LPPM.
                    </p>
                </details>
            @endforelse
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('informasi.index', ['kategori' => 'faq']) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-navy-900 hover:text-blue-700 transition">
                <span>Punya Pertanyaan Lain? Lihat Pusat Informasi &amp; FAQ Lengkap &rarr;</span>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-navy-950 text-slate-400 pt-16 pb-12 border-t border-slate-800">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
            <div class="space-y-4 md:col-span-1">
                <div class="flex items-center gap-3">
                    <img src="{{ app_setting_logo('logo') }}"
                        alt="Logo {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}"
                        class="h-11 w-11 object-contain drop-shadow-sm shrink-0">
                    <div>
                        <span
                            class="block font-heading font-bold text-lg text-white leading-tight">{{ app_setting('app_name', 'Sentra HKI') }}</span>
                        <span
                            class="block text-sm text-slate-400">{{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</span>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    Pusat Layanan {{ app_setting('sentra_hki_name', 'Hak Kekayaan Intelektual') }}
                    {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}.
                    {{ app_setting('app_tagline', 'Melindungi karya, mewujudkan inovasi mandiri.') }}
                </p>
                <div class="pt-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', app_setting('institution_phone', '6281234567890')) }}?text=Halo%20{{ urlencode(app_setting('app_name', 'Sentra HKI')) }},%20saya%20ingin%20konsultasi"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-sm font-semibold hover:bg-emerald-600 hover:text-white transition">
                        <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                        Chat WhatsApp Helpdesk
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#beranda" class="hover:text-gold-400 transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-gold-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="#layanan" class="hover:text-gold-400 transition-colors">Layanan HKI</a></li>
                    <li><a href="#alur" class="hover:text-gold-400 transition-colors">Panduan & SOP</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-gold-400 transition-colors">Login Portal
                            Dosen</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Layanan Utama</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#layanan" onclick="openModal('hak-cipta')"
                            class="hover:text-gold-400 transition-colors">Pendaftaran Hak Cipta</a></li>
                    <li><a href="#layanan" onclick="openModal('paten')"
                            class="hover:text-gold-400 transition-colors">Paten & Paten Sederhana</a></li>
                    <li><a href="#layanan" onclick="openModal('merek')"
                            class="hover:text-gold-400 transition-colors">Merek Dagang & Jasa</a></li>
                    <li><a href="#layanan" onclick="openModal('desain')"
                            class="hover:text-gold-400 transition-colors">Desain Industri</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Kontak & Sekretariat</h4>
                <p class="text-sm leading-relaxed text-slate-400">
                    {{ app_setting('lppm_building', 'Gedung LPPM Lantai 2, Kampus Universitas Panca Sakti Bekasi') }}<br>
                    {{ app_setting('institution_address', 'Jl. Raya Hankam, Pondok Melati, Kota Bekasi, Jawa Barat.') }}<br>
                    Email: {{ app_setting('institution_email', 'sentrahki@pancasakti.ac.id') }}<br>
                    WhatsApp: {{ app_setting('institution_phone', '+62 812-3456-7890') }}
                </p>
            </div>
        </div>

        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex flex-col sm:flex-row items-center justify-between text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} {{ app_setting('app_name', 'Sentra HKI') }}
                {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}. All rights reserved.</p>
            <p class="mt-2 sm:mt-0">
                {{ app_setting('app_full_name', 'Sistem Informasi Manajemen Publikasi & Sentra Kekayaan Intelektual') }}
            </p>
        </div>
    </footer>

    <!-- Modal Dialog Interaktif -->
    <div id="app-modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 backdrop-blur-sm hidden p-4"
        role="dialog" aria-modal="true" aria-label="Dialog Sentra HAKI" tabindex="-1">
        <div
            class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative transform transition-all animate-in fade-in zoom-in duration-200 max-h-[90vh] overflow-y-auto">
            <button onclick="closeModal()"
                class="absolute top-5 right-5 p-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div id="modal-content" class="space-y-4">
                <!-- Konten modal dinamis -->
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast"
        class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 bg-navy-900 text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-700">
        <i data-lucide="check-circle-2" class="w-5 h-5 text-gold-500"></i>
        <span id="toast-message" class="text-sm font-medium">Notifikasi berhasil</span>
    </div>

    <!-- JavaScript Interactivity -->
    <script>
        // Inisialisasi Lucide Icons
        lucide.createIcons();

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Helper escape HTML
        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Variabel global data permohonan terkini
        window.currentTrackedData = null;

        // Cek Status Usulan (Terhubung ke endpoint database nyata)
        async function checkStatus() {
            const codeInput = document.getElementById('search-code');
            const resultEl = document.getElementById('search-result');
            const code = codeInput.value.trim();

            if (!code) {
                showToast('Masukkan nomor permohonan atau kode usulan!');
                codeInput.focus();
                return;
            }

            resultEl.classList.remove('hidden');
            resultEl.innerHTML = `
                <div class="flex items-center gap-3 py-2 text-slate-300">
                    <svg class="animate-spin h-5 w-5 text-gold-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Melacak permohonan <strong>#${escapeHtml(code)}</strong> di database Sentra HKI...</span>
                </div>
            `;

            try {
                const response = await fetch(`{{ route('tracking.status') }}?code=${encodeURIComponent(code)}`);
                const json = await response.json();

                if (!response.ok || !json.success) {
                    resultEl.innerHTML = `
                        <div class="space-y-2 text-rose-300">
                            <div class="flex items-center gap-2 font-semibold text-rose-400">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                <span>Permohonan Tidak Ditemukan</span>
                            </div>
                            <p class="text-sm text-slate-300">${escapeHtml(json.message || 'Data permohonan tidak ditemukan.')}</p>
                            <p class="text-[11px] text-slate-400">Tip: Gunakan Nomor Permohonan (misal: <strong>EC00202512345</strong>), Nomor Pencatatan (misal: <strong>000789123</strong>), atau hubungi LPPM.</p>
                        </div>
                    `;
                    lucide.createIcons();
                    showToast('Data permohonan tidak ditemukan.');
                    return;
                }

                const d = json.data;
                window.currentTrackedData = d;

                // Step Progress Indicator
                const steps = [{
                        num: 1,
                        title: 'Draf Dokumen'
                    },
                    {
                        num: 2,
                        title: 'Verifikasi LPPM'
                    },
                    {
                        num: 3,
                        title: 'Proses DJKI'
                    },
                    {
                        num: 4,
                        title: 'Sertifikat Terbit'
                    }
                ];

                let stepHtml = '<div class="grid grid-cols-4 gap-2 pt-3 pb-1 border-t border-slate-800">';
                steps.forEach(st => {
                    const isCompleted = d.current_step >= st.num;
                    const isCurrent = d.current_step === st.num;
                    const stepBg = isCompleted ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/50' :
                        'bg-slate-800 text-slate-400 border border-slate-700';
                    const titleColor = isCompleted ? 'text-emerald-400 font-semibold' : 'text-slate-400';
                    stepHtml += `
                        <div class="text-center">
                            <div class="w-6 h-6 rounded-full mx-auto flex items-center justify-center text-[10px] font-bold ${stepBg} mb-1">
                                ${isCompleted && st.num < d.current_step ? '✓' : st.num}
                            </div>
                            <div class="text-[10px] leading-tight ${titleColor}">${st.title}</div>
                        </div>
                    `;
                });
                stepHtml += '</div>';

                resultEl.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold border ${d.badge_class}">
                                    ${escapeHtml(d.status_summary)}
                                </span>
                                <h4 class="font-bold text-white text-sm mt-1.5 line-clamp-2">${escapeHtml(d.title)}</h4>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] bg-slate-800/60 p-3 rounded-xl border border-slate-700/60">
                            <div>
                                <span class="text-slate-400 block">Kategori / Sub Jenis:</span>
                                <strong class="text-slate-200">${escapeHtml(d.ipr_type_label)} (${escapeHtml(d.sub_type)})</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Nomor Permohonan:</span>
                                <strong class="text-gold-400 font-mono text-sm">${escapeHtml(d.application_number)}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Pencipta / Inventor:</span>
                                <strong class="text-slate-200">${escapeHtml(d.inventors.join(', '))}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Nomor Pencatatan / Registrasi:</span>
                                <strong class="text-emerald-400 font-mono text-sm">${escapeHtml(d.registration_number)}</strong>
                            </div>
                        </div>

                        ${d.verification_note ? `
                                <div class="text-[11px] bg-blue-950/60 p-2.5 rounded-lg border border-blue-900 text-blue-200">
                                    <strong>Catatan Verifikator LPPM:</strong> ${escapeHtml(d.verification_note)}
                                </div>
                            ` : ''}

                        ${stepHtml}

                        <div class="pt-1 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Pemegang Hak: <strong class="text-slate-300">${escapeHtml(d.rights_holder)}</strong></span>
                            <button onclick="openModal('detail-tracking')" class="text-gold-400 hover:text-gold-300 font-semibold underline flex items-center gap-1">
                                Rincian Pelacakan &rarr;
                            </button>
                        </div>
                    </div>
                `;

                lucide.createIcons();
                showToast('Status permohonan #' + d.tracking_code + ' berhasil dimuat!');
            } catch (err) {
                console.error(err);
                resultEl.innerHTML = `
                    <div class="text-rose-400 text-sm py-1">
                        Gagal menghubungi server pelacakan. Silakan coba kembali sesaat lagi.
                    </div>
                `;
                showToast('Terjadi kendala saat melacak status.');
            }
        }

        // Simulasi Unduh Dokumen
        function downloadFile(fileName) {
            showToast(`Mengunduh berkas: ${fileName}...`);
        }

        // Modal Control
        function openModal(type) {
            const modal = document.getElementById('app-modal');
            const content = document.getElementById('modal-content');

            if (type === 'pengajuan') {
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-xl bg-navy-900 text-gold-500">
                                <i data-lucide="file-plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-xl text-navy-900">Formulir Pengajuan HKI</h3>
                                <p class="text-sm text-slate-500">Silakan lengkapi data usulan awal Anda.</p>
                            </div>
                        </div>

                        @auth
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-900 flex items-center justify-between">
                            <span>Anda telah masuk sebagai <strong>{{ auth()->user()->name }}</strong>.</span>
                            <a href="{{ route('dosen.ipr.create') }}" class="font-bold underline text-blue-800">Buka Formulir Penuh &rarr;</a>
                        </div>
                        @else
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-900 flex items-center justify-between">
                            <span>Dosen / Civitas UPS Bekasi?</span>
                            <a href="{{ route('login') }}" class="font-bold underline text-amber-900">Masuk untuk riwayat otomatis &rarr;</a>
                        </div>
                        @endauth

                        <form id="form-pengajuan-hki" onsubmit="handleFormSubmit(event)" class="space-y-3 pt-1">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                                <input type="text" name="nama" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy-900" placeholder="Cth: Dr. Budi Santoso, M.Kom" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email / Kontak</label>
                                    <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy-900" placeholder="email@kampus.ac.id" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp</label>
                                    <input type="tel" name="phone" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy-900" placeholder="081234567890" value="{{ auth()->check() ? auth()->user()->phone ?? '' : '' }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Layanan</label>
                                <select name="ipr_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy-900 bg-white">
                                    <option value="hak_cipta">Hak Cipta (Buku, Penelitian, Program Komputer)</option>
                                    <option value="paten">Paten Biasa</option>
                                    <option value="paten_sederhana">Paten Sederhana</option>
                                    <option value="merek">Merek Dagang & Jasa</option>
                                    <option value="desain_industri">Desain Industri</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Ciptaan / Inovasi</label>
                                <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy-900" placeholder="Tuliskan judul lengkap karya...">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy-900" placeholder="Deskripsi ringkas karya atau catatan untuk tim ahli LPPM..."></textarea>
                            </div>
                            <div id="modal-submit-error" class="text-sm text-rose-600 hidden bg-rose-50 p-2.5 rounded-xl border border-rose-200"></div>
                            <button type="submit" id="btn-submit-pengajuan" class="w-full py-3 rounded-xl bg-navy-900 text-white font-bold text-sm shadow-lg hover:bg-navy-800 transition-colors mt-2 flex items-center justify-center gap-2">
                                <span>Kirim Permohonan Usulan</span>
                            </button>
                        </form>
                    </div>
                `;
            } else if (type === 'hak-cipta') {
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-600 w-fit">
                            <i data-lucide="book-open" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Panduan & Syarat Hak Cipta</h3>
                        <p class="text-sm text-slate-500">Berlaku untuk buku, karya ilmiah, modul, karya seni, lagu, film, dan program komputer.</p>
                        <ul class="text-sm text-slate-600 space-y-2 list-disc pl-5">
                            <li>Scan KTP Asli Pencipta / Pemegang Hak Cipta.</li>
                            <li>Surat Pernyataan Keaslian Karya (bermaterai 10.000).</li>
                            <li>File Karya / Ciptaan (PDF, DOCX, MP3, MP4, atau Source Code).</li>
                            <li>Abstrak atau deskripsi singkat karya ciptaan.</li>
                        </ul>
                        <div class="pt-3 flex gap-2">
                            <button onclick="closeModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Tutup</button>
                            <button onclick="openModal('pengajuan')" class="w-1/2 py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm">Ajukan Sekarang</button>
                        </div>
                    </div>
                `;
            } else if (type === 'paten') {
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600 w-fit">
                            <i data-lucide="cpu" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Panduan & Syarat Paten</h3>
                        <p class="text-sm text-slate-500">Berlaku untuk invensi teknologi, alat tepat guna, formula, dan pembaharuan proses.</p>
                        <ul class="text-sm text-slate-600 space-y-2 list-disc pl-5">
                            <li>Deskripsi Invensi / Spesifikasi Teknis Paten (Latar belakang, klaim, uraian ringkas).</li>
                            <li>Gambar teknik atau skema alat/proses (format jelas).</li>
                            <li>Surat Pernyataan Kepemilikan Invensi oleh Inventor.</li>
                            <li>Surat Pengalihan Hak (jika pemegang paten adalah institusi/kampus).</li>
                        </ul>
                        <div class="pt-3 flex gap-2">
                            <button onclick="closeModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Tutup</button>
                            <button onclick="openModal('pengajuan')" class="w-1/2 py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm">Ajukan Sekarang</button>
                        </div>
                    </div>
                `;
            } else if (type === 'merek') {
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 w-fit">
                            <i data-lucide="tag" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Panduan & Syarat Merek Dagang</h3>
                        <p class="text-sm text-slate-500">Melindungi nama usaha, logo brand produk kuliner, jasa, atau karya startup.</p>
                        <ul class="text-sm text-slate-600 space-y-2 list-disc pl-5">
                            <li>Label / Etiket Logo Merek dengan resolusi tinggi (format JPG/PNG).</li>
                            <li>Kelas Merek / Klasifikasi jenis barang atau jasa yang diperdagangkan.</li>
                            <li>Identitas pemohon (KTP / Surat Keputusan Pendirian Unit Usaha).</li>
                            <li>Surat Pernyataan Pemilik Merek tidak meniru merek lain.</li>
                        </ul>
                        <div class="pt-3 flex gap-2">
                            <button onclick="closeModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Tutup</button>
                            <button onclick="openModal('pengajuan')" class="w-1/2 py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm">Ajukan Sekarang</button>
                        </div>
                    </div>
                `;
            } else if (type === 'desain') {
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="p-3 rounded-xl bg-purple-50 text-purple-600 w-fit">
                            <i data-lucide="layout" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-navy-900">Panduan & Syarat Desain Industri</h3>
                        <p class="text-sm text-slate-500">Melindungi bentuk 3 dimensi, konfigurasi, estetika produk fisik.</p>
                        <ul class="text-sm text-slate-600 space-y-2 list-disc pl-5">
                            <li>Gambar atau foto produk dari berbagai sudut pandang (perspektif, tampak atas, bawah, depan, samping).</li>
                            <li>Uraian dan klaim kebaruan desain industri.</li>
                            <li>Surat Pernyataan kepemilikan desain industri.</li>
                        </ul>
                        <div class="pt-3 flex gap-2">
                            <button onclick="closeModal()" class="w-1/2 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Tutup</button>
                            <button onclick="openModal('pengajuan')" class="w-1/2 py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm">Ajukan Sekarang</button>
                        </div>
                    </div>
                `;
            } else if (type === 'detail-tracking' && window.currentTrackedData) {
                const d = window.currentTrackedData;
                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-xl bg-navy-900 text-gold-500">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-xl text-navy-900">Rincian Pelacakan Permohonan</h3>
                                <p class="text-sm text-slate-500">Status resmi pendaftaran HKI di Sentra HKI LPPM</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3 text-sm">
                            <div>
                                <span class="text-slate-500 block">Judul Ciptaan / Inovasi:</span>
                                <h4 class="font-bold text-sm text-navy-900 mt-0.5">${escapeHtml(d.title)}</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200">
                                <div>
                                    <span class="text-slate-500 block">Kategori HKI:</span>
                                    <strong class="text-slate-800">${escapeHtml(d.ipr_type_label)}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Sub Jenis:</span>
                                    <strong class="text-slate-800">${escapeHtml(d.sub_type)}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Nomor Permohonan:</span>
                                    <strong class="font-mono text-navy-900 text-sm">${escapeHtml(d.application_number)}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Tanggal Permohonan:</span>
                                    <strong class="text-slate-800">${escapeHtml(d.application_date)}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Nomor Pencatatan / Registrasi:</span>
                                    <strong class="font-mono text-emerald-700 text-sm">${escapeHtml(d.registration_number)}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Tanggal Terbit (Granted):</span>
                                    <strong class="text-slate-800">${escapeHtml(d.grant_date)}</strong>
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-200">
                                <span class="text-slate-500 block">Pencipta / Inventor:</span>
                                <strong class="text-slate-800">${escapeHtml(d.inventors.join(', '))}</strong>
                            </div>
                            <div class="pt-2 border-t border-slate-200">
                                <span class="text-slate-500 block">Pemegang Hak:</span>
                                <strong class="text-slate-800">${escapeHtml(d.rights_holder)}</strong>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200">
                                <div>
                                    <span class="text-slate-500 block">Status Verifikasi Internal:</span>
                                    <span class="font-semibold text-blue-700">${escapeHtml(d.verification_status_label)}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Status Resmi DJKI:</span>
                                    <span class="font-semibold text-emerald-700">${escapeHtml(d.djki_status_label)}</span>
                                </div>
                            </div>
                            ${d.verification_note ? `
                                    <div class="pt-2 border-t border-slate-200">
                                        <span class="text-slate-500 block">Catatan Verifikator LPPM:</span>
                                        <p class="text-slate-700 italic mt-0.5 bg-white p-2 rounded-lg border border-slate-200">${escapeHtml(d.verification_note)}</p>
                                    </div>
                                ` : ''}
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button onclick="closeModal()" class="w-full py-2.5 rounded-xl bg-navy-900 text-white font-semibold text-sm hover:bg-navy-800 transition">Tutup Detail</button>
                        </div>
                    </div>
                `;
            }

            modal.classList.remove('hidden');
            lucide.createIcons();
        }

        function closeModal() {
            document.getElementById('app-modal').classList.add('hidden');
        }

        // Close on clicking outside modal
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('app-modal');
            if (e.target === modal) {
                closeModal();
            }
        });

        async function handleFormSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btnSubmit = document.getElementById('btn-submit-pengajuan');
            const errEl = document.getElementById('modal-submit-error');
            const content = document.getElementById('modal-content');

            if (errEl) errEl.classList.add('hidden');

            // Disable button & show spinner
            const originalBtnText = btnSubmit ? btnSubmit.innerHTML : 'Kirim Permohonan Usulan';
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `
                    <svg class="animate-spin h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Mengirim Permohonan...</span>
                `;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('permohonan.ajukan') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const json = await response.json();

                if (!response.ok || !json.success) {
                    if (btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalBtnText;
                    }
                    if (errEl) {
                        errEl.classList.remove('hidden');
                        let errorMsg = json.message || 'Terjadi kesalahan saat memproses permohonan.';
                        if (json.errors) {
                            const firstErrKey = Object.keys(json.errors)[0];
                            errorMsg = json.errors[firstErrKey][0];
                        }
                        errEl.innerText = errorMsg;
                    }
                    showToast('Gagal mengajukan permohonan. Periksa kembali isian form.');
                    return;
                }

                // Tampilkan Tampilan Sukses Lengkap di Modal
                const appNumber = json.data.application_number;
                const title = json.data.title;

                content.innerHTML = `
                    <div class="space-y-5 text-center py-2">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
                            <i data-lucide="check-circle" class="w-10 h-10"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-2xl text-navy-900">Permohonan Berhasil Dikirim!</h3>
                            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Usulan Anda telah tercatat dan masuk ke antrean verifikasi tim LPPM Universitas Panca Sakti Bekasi.</p>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-left space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Nomor Permohonan / Tiket:</span>
                                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Tercatat</span>
                            </div>
                            <div class="flex items-center justify-between gap-2 bg-white p-3 rounded-xl border border-slate-200">
                                <strong class="font-mono text-base text-navy-900 font-bold" id="copy-target-code">${escapeHtml(appNumber)}</strong>
                                <button onclick="copyTrackingCode('${escapeHtml(appNumber)}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-navy-900 text-gold-400 hover:text-white text-sm font-semibold transition">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                            <div class="pt-1 text-slate-600">
                                <strong>Judul Karya:</strong> ${escapeHtml(title)}
                            </div>
                        </div>

                        <div class="space-y-2 pt-2">
                            <button onclick="trackNewlySubmittedCode('${escapeHtml(appNumber)}')" class="w-full py-3 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                                <i data-lucide="search" class="w-4 h-4"></i>
                                <span>Lacak Permohonan Ini Sekarang</span>
                            </button>
                            <button onclick="closeModal()" class="w-full py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-100 transition">
                                Selesai & Tutup
                            </button>
                        </div>
                    </div>
                `;

                lucide.createIcons();
                showToast('Permohonan HKI #' + appNumber + ' berhasil terdaftar!');
            } catch (err) {
                console.error(err);
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalBtnText;
                }
                if (errEl) {
                    errEl.classList.remove('hidden');
                    errEl.innerText = 'Terjadi gangguan jaringan saat mengirim usulan. Silakan coba lagi.';
                }
                showToast('Gagal terhubung ke server.');
            }
        }

        // Helper untuk menyalin kode tracking ke clipboard
        function copyTrackingCode(code) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(code).then(() => {
                    showToast('Nomor permohonan #' + code + ' disalin ke clipboard!');
                }).catch(() => {
                    showToast('Nomor: ' + code);
                });
            } else {
                showToast('Nomor: ' + code);
            }
        }

        // Helper untuk langsung melacak kode yang baru saja diajukan
        function trackNewlySubmittedCode(code) {
            closeModal();
            const codeInput = document.getElementById('search-code');
            if (codeInput) {
                codeInput.value = code;
                window.location.href = '#beranda';
                setTimeout(() => {
                    checkStatus();
                }, 300);
            }
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const msgEl = document.getElementById('toast-message');
            msgEl.innerText = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3500);
        }
    </script>
</body>

</html>
