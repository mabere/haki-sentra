<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $information->title }} - {{ app_setting('app_name', 'Sentra HKI') }} {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ app_setting_logo('favicon') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Poppins & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
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
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-navy-900 selection:text-white min-h-screen flex flex-col justify-between">

    <!-- Top Bar Informasi Singkat -->
    <div class="bg-navy-950 text-slate-300 text-sm py-2 px-4 border-b border-navy-900">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Lembaga Penelitian &amp; Pengabdian kepada Masyarakat (LPPM) Universitas Panca Sakti Bekasi</span>
            </div>
            <div class="flex items-center gap-5 text-slate-400">
                <span class="hidden md:inline">🕒 Jam Layanan: Sen - Jum (08.00 - 16.00 WIB)</span>
                <a href="mailto:sentrahki@pancasakti.ac.id" class="hover:text-gold-400 transition">✉️ sentrahki@pancasakti.ac.id</a>
            </div>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                    <img src="{{ app_setting_logo('logo') }}" alt="Logo {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}" class="h-11 w-11 object-contain drop-shadow-sm group-hover:scale-105 transition-transform shrink-0">
                    <div>
                        <span class="block font-heading font-bold text-base sm:text-lg text-navy-900 tracking-tight leading-none">{{ app_setting('app_name', 'Sentra HKI') }}</span>
                        <span class="block text-[11px] font-medium text-slate-500 mt-1">{{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('informasi.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-navy-900 hover:bg-slate-100 transition-colors">
                        &larr; Semua Informasi &amp; Dokumen
                    </a>
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 transition-colors">
                        Beranda
                    </a>
                </nav>

                <!-- Auth & Action Buttons -->
                <div class="flex items-center gap-2.5 shrink-0">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-white font-semibold text-sm transition-all shadow-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-gold-400"></i>
                            <span>Dashboard Akun</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-white font-semibold text-sm transition-all shadow-sm">
                            <i data-lucide="log-in" class="w-4 h-4 text-gold-400"></i>
                            <span>Masuk Portal Dosen</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Detail Section -->
    <main class="flex-1 py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 w-full">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-navy-900 transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('informasi.index') }}" class="hover:text-navy-900 transition">Pusat Informasi</a>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate max-w-xs sm:max-w-md">{{ $information->title }}</span>
        </nav>

        <!-- Article Card Header -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6 sm:p-10 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-lg text-sm font-bold {{ $information->category_badge_color }}">
                        {{ $information->category_label }}
                    </span>
                    @if($information->badge_text)
                        <span class="px-2.5 py-1 rounded-lg text-sm font-bold bg-slate-100 text-slate-700">
                            {{ $information->badge_text }}
                        </span>
                    @endif
                </div>
                <div class="text-sm text-slate-400">
                    Dipublikasikan: <span class="font-medium text-slate-600">{{ $information->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-navy-900 tracking-tight leading-tight">
                {{ $information->title }}
            </h1>

            @if($information->description)
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border-l-4 border-gold-500 text-sm text-slate-700 leading-relaxed font-medium">
                    {{ $information->description }}
                </div>
            @endif

            <!-- File Attachment Download Box -->
            @if($information->file_path)
                <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50/60 border border-blue-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold uppercase tracking-wider text-blue-700">Dokumen Lampiran Resmi</div>
                            <h4 class="font-heading font-bold text-sm sm:text-base text-slate-900 mt-0.5">{{ $information->file_name }}</h4>
                            <p class="text-sm text-slate-500 mt-0.5">Format: {{ $information->file_type ?? 'Dokumen' }} • Ukuran: {{ $information->file_size ?? 'Tersedia' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('informasi.unduh', $information->slug) }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/20 transition shrink-0">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Unduh File Sekarang</span>
                    </a>
                </div>
            @endif

            <!-- Article Body Content -->
            @if($information->content)
                <div class="pt-4 border-t border-slate-100 text-sm sm:text-base text-slate-700 leading-relaxed space-y-4">
                    {!! nl2br(e($information->content)) !!}
                </div>
            @endif

            <!-- External Link -->
            @if($information->external_link)
                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ $information->external_link }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition">
                        <span>Kunjungi Tautan Eksternal</span>
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif

            <!-- Footer Meta -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-sm text-slate-400">
                <div>
                    Penyusun: <span class="text-slate-600 font-semibold">{{ $information->creator->name ?? 'Admin Sentra HKI LPPM' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('informasi.index') }}" class="text-blue-600 hover:underline font-semibold flex items-center gap-1">
                        &larr; Kembali ke Pusat Informasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Items -->
        @if($related->isNotEmpty())
            <div class="space-y-4 pt-4">
                <h3 class="font-heading font-bold text-lg sm:text-xl text-navy-900">Informasi &amp; Panduan Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($related as $rel)
                        <a href="{{ route('informasi.show', $rel->slug) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-gold-500/50 hover:shadow-md transition space-y-2 block group">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $rel->category_badge_color }}">
                                    {{ $rel->category_label }}
                                </span>
                                <span class="text-sm text-slate-400">{{ $rel->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                            <h4 class="font-heading font-bold text-sm sm:text-base text-navy-900 group-hover:text-blue-600 transition">
                                {{ $rel->title }}
                            </h4>
                            @if($rel->description)
                                <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $rel->description }}
                                </p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-navy-950 text-slate-400 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
            <div class="space-y-4 md:col-span-1">
                <div class="flex items-center gap-3">
                    <img src="{{ app_setting_logo('logo') }}" alt="Logo {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}" class="h-11 w-11 object-contain drop-shadow-sm shrink-0">
                    <div>
                        <span class="block font-heading font-bold text-lg text-white leading-tight">{{ app_setting('app_name', 'Sentra HKI') }}</span>
                        <span class="block text-sm text-slate-400">{{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</span>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">
                    Pusat Layanan {{ app_setting('sentra_hki_name', 'Hak Kekayaan Intelektual') }} {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}. {{ app_setting('app_tagline', 'Melindungi karya, mewujudkan inovasi mandiri.') }}
                </p>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Pusat Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition-colors">Beranda Utama</a></li>
                    <li><a href="{{ route('informasi.index') }}" class="hover:text-gold-400 transition-colors">Pusat Informasi &amp; Dokumen</a></li>
                    <li><a href="{{ route('home') }}#alur" class="hover:text-gold-400 transition-colors">Alur &amp; SOP Pengajuan</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-gold-400 transition-colors">Login Portal Dosen</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Layanan DJKI</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Hak Cipta &amp; Software</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Paten Invensi</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Merek &amp; Desain Industri</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Kontak LPPM</h4>
                <div class="text-sm space-y-2 text-slate-400 leading-relaxed">
                    <p class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4 text-gold-500 shrink-0"></i>
                        <span>{{ app_setting('institution_email', 'sentrahki@pancasakti.ac.id') }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-gold-500 shrink-0"></i>
                        <span>{{ app_setting('institution_phone', '(021) 8497-8183') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} {{ app_setting('app_name', 'Sentra HKI') }} {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
