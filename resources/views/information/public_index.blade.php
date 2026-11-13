<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Informasi, Panduan &amp; Template Dokumen - {{ app_setting('app_name', 'Sentra HKI') }} {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</title>
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
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:text-navy-900 hover:bg-slate-100 transition-colors">
                        &larr; Kembali ke Beranda
                    </a>
                    <a href="{{ route('home') }}#alur" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 transition-colors">
                        Alur &amp; SOP
                    </a>
                    <a href="{{ route('home') }}#dokumen" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 transition-colors">
                        Template Unduhan
                    </a>
                    <a href="{{ route('home') }}#faq" class="px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-navy-900 hover:bg-slate-50 transition-colors">
                        FAQ
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

    <!-- Main Content Container -->
    <main class="flex-1 pb-20">
        
        <!-- Hero Section -->
        <section class="bg-gradient-to-b from-navy-950 via-navy-900 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
            <div class="max-w-5xl mx-auto text-center space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-gold-400 text-sm font-semibold backdrop-blur-xs">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>Pusat Informasi &amp; Dokumen Resmi</span>
                </div>
                <h1 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl tracking-tight leading-tight">
                    Panduan, Alur SOP &amp; Template Dokumen HKI
                </h1>
                <p class="text-slate-300 text-sm sm:text-base max-w-3xl mx-auto leading-relaxed">
                    Akses seluruh berkas formulir permohonan, alur tahapan pengajuan hak cipta, paten, merek dagang, tanya jawab (FAQ), serta berita pengumuman resmi LPPM Universitas Panca Sakti Bekasi.
                </p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto pt-4">
                    <form method="GET" action="{{ route('informasi.index') }}" class="flex items-center gap-2 bg-white/10 p-2 rounded-2xl backdrop-blur-md border border-white/20 shadow-2xl">
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                        <div class="relative flex-1">
                            <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3.5 top-3"></i>
                            <input type="text" name="q" value="{{ $search }}" placeholder="Cari dokumen, syarat panduan, kata kunci FAQ..."
                                   class="w-full pl-11 pr-4 py-2.5 bg-white text-slate-900 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-gold-500 transition">
                        </div>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-sm transition-all shrink-0">
                            Cari Informasi
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Category Filter Chips -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
            <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-md flex items-center gap-2 overflow-x-auto scrollbar-none text-sm font-semibold">
                <a href="{{ route('informasi.index', ['kategori' => 'all', 'q' => $search]) }}"
                   class="px-4 py-2.5 rounded-xl transition whitespace-nowrap {{ $kategori === 'all' ? 'bg-navy-900 text-white shadow-xs font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-navy-900' }}">
                    Semua Konten
                </a>
                <a href="{{ route('informasi.index', ['kategori' => 'alur_syarat', 'q' => $search]) }}"
                   class="px-4 py-2.5 rounded-xl transition whitespace-nowrap {{ $kategori === 'alur_syarat' ? 'bg-indigo-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    Alur &amp; SOP ({{ $alurs->count() }})
                </a>
                <a href="{{ route('informasi.index', ['kategori' => 'template_dokumen', 'q' => $search]) }}"
                   class="px-4 py-2.5 rounded-xl transition whitespace-nowrap {{ $kategori === 'template_dokumen' ? 'bg-purple-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:bg-purple-50 hover:text-purple-600' }}">
                    Template Dokumen ({{ $templates->count() }})
                </a>
                <a href="{{ route('informasi.index', ['kategori' => 'faq', 'q' => $search]) }}"
                   class="px-4 py-2.5 rounded-xl transition whitespace-nowrap {{ $kategori === 'faq' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    Tanya Jawab FAQ ({{ $faqs->count() }})
                </a>
                <a href="{{ route('informasi.index', ['kategori' => 'pengumuman', 'q' => $search]) }}"
                   class="px-4 py-2.5 rounded-xl transition whitespace-nowrap {{ $kategori === 'pengumuman' ? 'bg-amber-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:bg-amber-50 hover:text-amber-600' }}">
                    Pengumuman Resmi ({{ $announcements->count() }})
                </a>
            </div>
        </section>

        <!-- Information Items Grid / List -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 space-y-12">
            
            <!-- Pengumuman Highlight (Jika ada) -->
            @if(($kategori === 'all' || $kategori === 'pengumuman') && $announcements->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-lg sm:text-xl text-navy-900">Pengumuman &amp; Berita Resmi</h2>
                            <p class="text-sm text-slate-500">Informasi terbaru seputar penerimaan usulan dan kebijakan Sentra HKI LPPM.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($announcements as $announce)
                            <div class="bg-white rounded-2xl p-5 border border-amber-200/80 shadow-xs hover:shadow-md transition space-y-3 relative overflow-hidden group">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full pointer-events-none"></div>
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-1 rounded-lg text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $announce->badge_text ?? 'Pengumuman' }}
                                    </span>
                                    <span class="text-sm text-slate-400">
                                        {{ $announce->created_at->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                <h3 class="font-heading font-bold text-base sm:text-lg text-navy-900 group-hover:text-amber-700 transition">
                                    <a href="{{ route('informasi.show', $announce->slug) }}">{{ $announce->title }}</a>
                                </h3>
                                @if($announce->description)
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $announce->description }}
                                    </p>
                                @endif
                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <a href="{{ route('informasi.show', $announce->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-amber-700 hover:text-amber-800">
                                        <span>Baca Selengkapnya</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </a>
                                    @if($announce->file_path)
                                        <a href="{{ route('informasi.unduh', $announce->slug) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-600 font-medium">
                                            <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
                                            <span>Unduh Lampiran</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Template Dokumen Unduhan (Kategori atau Grid Utama) -->
            @if(($kategori === 'all' || $kategori === 'template_dokumen') && $templates->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i data-lucide="file-down" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-lg sm:text-xl text-navy-900">Format &amp; Template Dokumen Resmi</h2>
                            <p class="text-sm text-slate-500">Unduh berkas kelengkapan administratif (Surat Pengalihan Hak Cipta, Formulir Merek, Template Deskripsi Paten).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($templates as $doc)
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:shadow-lg hover:border-purple-300 transition-all flex flex-col justify-between group">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                            <i data-lucide="file-text" class="w-5 h-5"></i>
                                        </div>
                                        @if($doc->badge_text)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-700">
                                                {{ $doc->badge_text }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="font-heading font-bold text-base text-navy-900 group-hover:text-purple-700 transition">
                                        {{ $doc->title }}
                                    </h3>
                                    @if($doc->description)
                                        <p class="text-sm text-slate-500 leading-relaxed line-clamp-3">
                                            {{ $doc->description }}
                                        </p>
                                    @endif
                                    <div class="flex items-center gap-2 text-sm text-slate-400">
                                        <span class="font-bold text-slate-600 uppercase">{{ $doc->file_type ?? 'DOCX' }}</span>
                                        <span>•</span>
                                        <span>{{ $doc->file_size ?? 'Resmi LPPM' }}</span>
                                    </div>
                                </div>

                                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center gap-2">
                                    <a href="{{ route('informasi.unduh', $doc->slug) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-semibold text-sm shadow-sm transition">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                        <span>Unduh Berkas</span>
                                    </a>
                                    <a href="{{ route('informasi.show', $doc->slug) }}"
                                       class="p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition" title="Lihat Panduan">
                                        <i data-lucide="info" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Alur & SOP Layanan -->
            @if(($kategori === 'all' || $kategori === 'alur_syarat') && $alurs->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i data-lucide="git-merge" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-lg sm:text-xl text-navy-900">Alur &amp; SOP Pengajuan HKI</h2>
                            <p class="text-sm text-slate-500">Tahapan usulan mulai dari konsultasi berkas, penyerahan formulir, verifikasi ahli hingga terbit sertifikat resmi.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @foreach($alurs as $index => $alur)
                            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:border-indigo-400 hover:shadow-md transition space-y-3 relative group">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 font-heading font-extrabold text-base flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    {{ sprintf('%02d', $alur->order ?: $index + 1) }}
                                </div>
                                <h3 class="font-heading font-bold text-base text-navy-900">
                                    {{ $alur->title }}
                                </h3>
                                @if($alur->description)
                                    <p class="text-sm text-slate-600 leading-relaxed">
                                        {{ $alur->description }}
                                    </p>
                                @endif
                                @if($alur->content)
                                    <div class="pt-2 text-[11px] text-slate-400">
                                        <a href="{{ route('informasi.show', $alur->slug) }}" class="text-indigo-600 hover:underline font-semibold flex items-center gap-1">
                                            <span>Rincian Persyaratan &rarr;</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tanya Jawab (FAQ) -->
            @if(($kategori === 'all' || $kategori === 'faq') && $faqs->isNotEmpty())
                <div class="space-y-4 max-w-4xl mx-auto">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-lg sm:text-xl text-navy-900">Pertanyaan yang Sering Diajukan (FAQ)</h2>
                            <p class="text-sm text-slate-500">Jawaban cepat seputar persyaratan, durasi proses, dan ketentuan biaya pendaftaran HKI.</p>
                        </div>
                    </div>

                    <div class="space-y-3.5">
                        @foreach($faqs as $faq)
                            <details class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-xs cursor-pointer">
                                <summary class="flex justify-between items-center font-heading font-bold text-navy-900 text-sm sm:text-base list-none">
                                    <span>{{ $faq->title }}</span>
                                    <span class="transition group-open:rotate-180 text-slate-400">
                                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                    </span>
                                </summary>
                                <div class="text-sm text-slate-600 mt-3 pt-3 border-t border-slate-100 leading-relaxed space-y-2">
                                    <p>{{ $faq->description ?: $faq->content }}</p>
                                    @if($faq->content && $faq->description && $faq->content !== $faq->description)
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-slate-700">
                                            {!! nl2br(e($faq->content)) !!}
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Fallback Empty State jika hasil pencarian / kategori kosong -->
            @if($items->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-xl mx-auto space-y-3">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                        <i data-lucide="search-x" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-navy-900">Informasi Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500">
                        Tidak ditemukan informasi atau dokumen yang cocok dengan kata kunci pencarian Anda. Silakan coba kata kunci lain atau pilih kategori Semua.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('informasi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-navy-900 text-white font-semibold text-sm">
                            Reset Pencarian
                        </a>
                    </div>
                </div>
            @endif

        </section>

        <!-- CTA Box Bantuan & Permohonan -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
            <div class="bg-gradient-to-r from-navy-950 to-navy-900 rounded-3xl p-8 sm:p-12 text-white border border-slate-800 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-8 relative overflow-hidden">
                <div class="space-y-3 max-w-2xl relative z-10">
                    <span class="px-3 py-1 rounded-full bg-gold-500/20 text-gold-400 text-sm font-bold uppercase tracking-wider">
                        Konsultasi &amp; Pendaftaran Cepat
                    </span>
                    <h3 class="font-heading font-bold text-2xl sm:text-3xl tracking-tight">
                        Punya Karya Inovasi atau Riset yang Ingin Dilindungi?
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Sentra HKI LPPM Universitas Panca Sakti Bekasi siap mendampingi proses usulan Anda hingga terbit sertifikat resmi Kementerian Hukum &amp; HAM RI.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 relative z-10">
                    <a href="https://wa.me/6281234567890?text=Halo%20Sentra%20HKI%20UPS%20Bekasi,%20saya%20ingin%20konsultasi" target="_blank"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-md transition">
                        <i data-lucide="phone-call" class="w-4 h-4"></i>
                        <span>WhatsApp Helpdesk</span>
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold text-sm shadow-md transition">
                        <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                        <span>Masuk Portal Dosen</span>
                    </a>
                </div>
            </div>
        </section>

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
                <div class="pt-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', app_setting('institution_phone', '6281234567890')) }}?text=Halo%20{{ urlencode(app_setting('app_name', 'Sentra HKI')) }},%20saya%20ingin%20konsultasi" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 text-sm font-semibold hover:bg-emerald-600 hover:text-white transition">
                        <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                        Chat WhatsApp Helpdesk
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Pusat Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition-colors">Beranda Utama</a></li>
                    <li><a href="{{ route('home') }}#tentang" class="hover:text-gold-400 transition-colors">Tentang Sentra HKI</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Katalog Layanan</a></li>
                    <li><a href="{{ route('informasi.index', ['kategori' => 'alur_syarat']) }}" class="hover:text-gold-400 transition-colors">Alur &amp; SOP Pengajuan</a></li>
                    <li><a href="{{ route('informasi.index', ['kategori' => 'template_dokumen']) }}" class="hover:text-gold-400 transition-colors">Template Berkas</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Layanan DJKI</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Hak Cipta Karya Tulis &amp; Software</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Paten Sederhana &amp; Invensi</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Merek Produk &amp; Inkubator Bisnis</a></li>
                    <li><a href="{{ route('home') }}#layanan" class="hover:text-gold-400 transition-colors">Desain Industri &amp; Kerajinan</a></li>
                    <li><a href="{{ route('home') }}#lacak" class="hover:text-gold-400 transition-colors">Pelacakan Status Usulan</a></li>
                </ul>
            </div>

            <div class="space-y-3">
                <h4 class="font-heading font-semibold text-white text-sm">Kontak LPPM</h4>
                <div class="text-sm space-y-2 text-slate-400 leading-relaxed">
                    <p class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-gold-500 shrink-0 mt-0.5"></i>
                        <span>{{ app_setting('institution_address', 'Jl. Raya Hankam No.54, Jatirahayu, Kec. Pondok Melati, Kota Bekasi, Jawa Barat 17414') }}</span>
                    </p>
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
            <div class="flex items-center gap-6">
                <span>Didukung oleh {{ app_setting('lppm_name', 'LPPM') }} &amp; DJKI Kemenkumham RI</span>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
