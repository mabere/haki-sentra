@extends('layouts.app', ['title' => 'Dashboard', 'header' => 'Ringkasan Kinerja & Dashboard'])

@section('content')
<div class="space-y-8">

    <!-- SISTER Style Welcome Profile Banner (Light & Clean) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start md:items-center gap-6">
            <!-- Round Blue Avatar Circle Khas SISTER with Photo Support -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden shrink-0 shadow-md shadow-blue-600/20 ring-4 ring-blue-100 flex items-center justify-center bg-blue-600 text-white">
                @if(auth()->user()->photo_url)
                    <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>

            <!-- Welcome Text & Details -->
            <div class="space-y-1 text-center sm:text-left">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Selamat Datang
                </h2>
                <div class="text-lg sm:text-xl font-bold text-slate-800">
                    {{ auth()->user()->lecturer ? auth()->user()->lecturer->full_name : auth()->user()->name }}
                </div>
                <p class="text-sm md:text-base text-slate-600 font-medium pt-0.5 leading-relaxed">
                    {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }} &bull;
                    @if(auth()->user()->lecturer)
                        {{ auth()->user()->lecturer->department->faculty->name ?? 'Fakultas' }} &bull; Program Studi {{ auth()->user()->lecturer->department->name ?? 'Pendidikan' }} (NIDN: {{ auth()->user()->lecturer->nidn ?? '-' }})
                    @else
                        Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM) &bull; {{ auth()->user()->isAdminLppm() ? 'Sentra HKI & LPPM' : (auth()->user()->isVerifikator() ? 'Tim Verifikator' : 'Pimpinan Universitas') }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Quick Action Badges (Light Style) -->
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5 shrink-0">
            @if(auth()->user()->isDosen())
            <a href="{{ route('dosen.publications.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-2xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Publikasi</span>
            </a>
            <a href="{{ route('dosen.ipr.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm shadow-2xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Input Karya HKI</span>
            </a>
            @elseif(auth()->user()->isVerifikator())
            <a href="{{ route('verification.publications.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm shadow-2xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Mulai Validasi Berkas</span>
            </a>
            @else
            <a href="{{ route('reports.summary') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-2xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Laporan &amp; Ekspor</span>
            </a>
            @endif
        </div>
    </div>

    <!-- SISTER Style Blue Alert Banner (Gambar: Pusat Bantuan SISTER Style) -->
    <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                i
            </div>
            <div>
                <h3 class="text-sm font-bold text-sky-950 uppercase tracking-wide">
                    PUSAT BANTUAN {{ strtoupper(app_setting('lppm_name', 'LPPM')) }} &amp; {{ strtoupper(app_setting('sentra_hki_name', 'SENTRA HKI')) }} {{ strtoupper(app_setting('institution_legal_name', 'UNIVERSITAS PANCA SAKTI BEKASI')) }}
                </h3>
                <p class="text-sm md:text-base text-sky-800 mt-1 leading-relaxed max-w-4xl">
                    Layanan usulan pendaftaran Hak Cipta, Paten DJKI, validasi publikasi ilmiah dosen, serta pengajuan insentif dibuka secara terintegrasi. Pastikan seluruh berkas persyaratan dan link DOI telah diisi dengan lengkap.
                </p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ url('/#alur') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-300 hover:border-sky-400 hover:bg-sky-50/50 text-slate-800 text-sm font-bold rounded-xl shadow-2xs transition whitespace-nowrap">
                Info Selengkapnya
            </a>
        </div>
    </div>

    <!-- Section: Informasi Penting (Gambar: Hasil Pemadanan Data) -->
    <div class="space-y-4">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Informasi Penting</h2>

        <div class="bg-white rounded-2xl border-2 border-amber-300/80 shadow-xs p-6 sm:p-8 space-y-5">
            <!-- Header Kartu dengan icon amber -->
            <div class="space-y-1.5 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">
                        i
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">
                        Hasil Pemadanan Data &amp; Status Luaran
                    </h3>
                </div>
                <p class="text-sm md:text-base text-slate-600 pl-8.5 leading-relaxed">
                    Pastikan status dosen Anda telah sesuai. Segera lakukan pemadanan data publikasi, sertifikat HKI, dan usulan insentif jika dibutuhkan untuk pelaporan institusi.
                </p>
            </div>

            <!-- Sub-Box Kuning/Krem Halus SISTER (Gambar: Kenapa Anda harus melakukan ini?) -->
            <div class="bg-[#fffbf0] border border-amber-200/80 rounded-xl p-4 sm:p-5 space-y-1.5">
                <h4 class="text-sm md:text-base font-bold text-slate-900">
                    Kenapa Anda harus melakukan ini?
                </h4>
                <p class="text-sm md:text-base text-slate-600 leading-relaxed">
                    Kami membutuhkan partisipasi Anda untuk memeriksa kelengkapan dan keakuratan data, serta melakukan penyesuaian jika dibutuhkan agar capaian kinerja luaran dan pelaporan IKU universitas tercatat dengan valid.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        @if(auth()->user()->isDosen())
            <!-- Stat 1: Publikasi Saya -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Publikasi Saya</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['publications_total'] }}</p>
                    <p class="text-sm text-emerald-600 font-medium">{{ $stats['publications_approved'] }} terverifikasi</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>

            <!-- Stat 2: HKI Saya -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Karya HKI Saya</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['ipr_total'] }}</p>
                    <p class="text-sm text-emerald-600 font-medium">{{ $stats['ipr_approved'] }} bersertifikat/approved</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
                </div>
            </div>

            <!-- Stat 3: Klaim Insentif -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Pengajuan Insentif</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['incentives_total'] }}</p>
                    <p class="text-sm text-indigo-600 font-medium">Klaim luaran riset</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                </div>
            </div>

            <!-- Stat 4: Insentif Diterima -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Insentif Cair</p>
                    <p class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($stats['incentives_disbursed'], 0, ',', '.') }}</p>
                    <p class="text-sm text-slate-500 font-medium">Telah ditransfer ke rekening</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

        @elseif(auth()->user()->isVerifikator())
            <!-- Verifikator Stats -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Antrian Publikasi</p>
                    <p class="text-3xl font-extrabold text-amber-600">{{ $stats['pending_publications'] }}</p>
                    <p class="text-sm text-slate-500 font-medium">Menunggu review</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Antrian HKI</p>
                    <p class="text-3xl font-extrabold text-amber-600">{{ $stats['pending_ipr'] }}</p>
                    <p class="text-sm text-slate-500 font-medium">Menunggu review sertifikat</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Antrian</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total_pending'] }}</p>
                    <p class="text-sm text-slate-500 font-medium">Seluruh berkas masuk</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Divalidasi Saya</p>
                    <p class="text-3xl font-extrabold text-emerald-600">{{ $stats['verified_by_me'] }}</p>
                    <p class="text-sm text-emerald-600 font-medium">Klaim disetujui</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

        @else
            <!-- Admin LPPM / Pimpinan Stats -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Publikasi</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total_publications'] }}</p>
                    <p class="text-sm text-blue-600 font-medium">{{ $stats['scopus_count'] }} Scopus &bull; {{ $stats['sinta_count'] }} SINTA</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Karya HKI</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total_ipr'] }}</p>
                    <p class="text-sm text-emerald-600 font-medium">{{ $stats['granted_ipr'] }} Berstatus Granted</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Dosen Terdaftar</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total_lecturers'] }}</p>
                    <p class="text-sm text-indigo-600 font-medium">Homebase aktif</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Realisasi Insentif</p>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-900">Rp {{ number_format($stats['disbursed_incentives'], 0, ',', '.') }}</p>
                    <p class="text-sm text-amber-600 font-medium">{{ $stats['pending_incentives'] }} klaim belum diproses</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        @endif

    </div>

    <!-- Section: Visualisasi Tren & Analitik Eksekutif (Chart.js Interactive) -->
    <div class="space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <span>Visualisasi Tren &amp; Analitik Eksekutif</span>
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Monitoring dinamika luaran Tri Dharma perguruan tinggi, perolehan sertifikat HKI, dan kinerja dosen.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span> Data Terkini {{ date('Y') }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart 1: Tren Tahunan (2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Pertumbuhan Luaran Ilmiah (5 Tahun Terakhir)</h3>
                        <p class="text-sm text-slate-500">Perbandingan publikasi artikel terverifikasi dan pengajuan karya HKI</p>
                    </div>
                </div>
                <div class="h-64 sm:h-72">
                    <canvas id="trendOutputChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Portofolio Kategori HKI (1 col) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Komposisi Portofolio HKI</h3>
                    <p class="text-sm text-slate-500">Distribusi jenis perlindungan kekayaan intelektual</p>
                </div>
                <div class="h-64 sm:h-72 flex items-center justify-center">
                    <canvas id="iprCategoryChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart 3: Distribusi per Fakultas (2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Sebaran Capaian per Fakultas</h3>
                    <p class="text-sm text-slate-500">Komparasi produktivitas karya ilmiah dan HKI antar unit fakultas</p>
                </div>
                <div class="h-64 sm:h-72">
                    <canvas id="facultyDistributionChart"></canvas>
                </div>
            </div>

            <!-- Leaderboard Top Dosen (1 col) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Top 5 Dosen Kontributor</h3>
                        <p class="text-sm text-slate-500">Peringkat produktivitas HKI &amp; Publikasi</p>
                    </div>
                    <span class="text-sm font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                        Leaderboard
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($analytics['top_lecturers'] as $idx => $lec)
                    <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-sm font-extrabold {{ $idx === 0 ? 'bg-amber-100 text-amber-800 ring-2 ring-amber-400' : ($idx === 1 ? 'bg-slate-200 text-slate-700' : ($idx === 2 ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-500')) }}">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-900 line-clamp-1">{{ $lec->full_name }}</p>
                                <p class="text-[11px] text-slate-500 line-clamp-1">{{ $lec->department->name ?? 'Prodi' }} (NIDN: {{ $lec->nidn ?? '-' }})</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-sm font-extrabold text-blue-700 block">{{ $lec->total_output }} Karya</span>
                            <span class="text-[10px] text-slate-400 block">{{ $lec->publications_count }} Pub &bull; {{ $lec->intellectual_properties_count }} HKI</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 italic text-center py-6">Belum ada data capaian dosen</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Recent Publications Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Publikasi Ilmiah Terbaru</h3>
                        <p class="text-sm text-slate-500">Artikel jurnal &amp; prosiding seminar</p>
                    </div>
                </div>
                <a href="{{ auth()->user()->isVerifikator() ? route('verification.publications.index') : route('dosen.publications.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentPublications as $pub)
                <div class="p-5 hover:bg-slate-50/80 transition space-y-2">
                    <div class="flex items-start justify-between gap-4">
                        <h4 class="text-sm sm:text-base font-semibold text-slate-900 leading-snug line-clamp-2">
                            {{ $pub->title }}
                        </h4>

                        <!-- Status Badge -->
                        @php
                            $statusStyle = [
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'revision' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'rejected' => 'bg-slate-100 text-slate-700 border-slate-200',
                            ];
                            $statusLabel = [
                                'approved' => 'Disetujui',
                                'pending' => 'Menunggu Validasi',
                                'revision' => 'Perlu Revisi',
                                'rejected' => 'Ditolak',
                            ];
                        @endphp
                        <span class="inline-flex shrink-0 items-center px-2.5 py-0.5 rounded text-sm font-semibold border {{ $statusStyle[$pub->verification_status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $statusLabel[$pub->verification_status] ?? $pub->verification_status }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-3 text-sm text-slate-500">
                        <span class="font-medium text-slate-700">{{ $pub->journal_name }}</span>
                        <span>&bull;</span>
                        <span>{{ $pub->publication_year }}</span>
                        @if($pub->quartile)
                            <span>&bull;</span>
                            <span class="px-1.5 py-0.2 rounded bg-indigo-50 text-indigo-700 font-bold text-sm">{{ $pub->quartile }}</span>
                        @endif
                        @if($pub->doi)
                            <span>&bull;</span>
                            <span class="text-blue-600 truncate max-w-[150px]">DOI: {{ $pub->doi }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-sm text-slate-500">
                    Belum ada data publikasi yang terdaftar.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent IPR Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Hak Kekayaan Intelektual (HKI) Terbaru</h3>
                        <p class="text-sm text-slate-500">Hak Cipta, Paten, dan Desain Industri</p>
                    </div>
                </div>
                <a href="{{ auth()->user()->isVerifikator() ? route('verification.ipr.index') : route('dosen.ipr.index') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentIpr as $ipr)
                <div class="p-5 hover:bg-slate-50/80 transition space-y-2">
                    <div class="flex items-start justify-between gap-4">
                        <h4 class="text-sm sm:text-base font-semibold text-slate-900 leading-snug line-clamp-2">
                            {{ $ipr->title }}
                        </h4>

                        <span class="inline-flex shrink-0 items-center px-2.5 py-0.5 rounded text-sm font-semibold border {{ $ipr->djki_status === 'granted' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                            {{ strtoupper($ipr->djki_status) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-3 text-sm text-slate-500">
                        <span class="font-medium text-slate-700 capitalize">{{ str_replace('_', ' ', $ipr->ipr_type) }}</span>
                        @if($ipr->registration_number)
                            <span>&bull;</span>
                            <span>No: {{ $ipr->registration_number }}</span>
                        @endif
                        @if($ipr->grant_date)
                            <span>&bull;</span>
                            <span>Terbit: {{ $ipr->grant_date->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-sm text-slate-500">
                    Belum ada karya HKI yang terdaftar.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const years = @json($analytics['years'] ?? []);
    const trendPub = @json($analytics['trend_publications'] ?? []);
    const trendIpr = @json($analytics['trend_ipr'] ?? []);
    const iprCategories = @json($analytics['ipr_categories'] ?? []);
    const facultyLabels = @json($analytics['faculty_labels'] ?? []);
    const facultyPub = @json($analytics['faculty_publications'] ?? []);
    const facultyIpr = @json($analytics['faculty_ipr'] ?? []);

    // 1. Line/Bar Trend Output Chart
    const ctxTrend = document.getElementById('trendOutputChart');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: years,
                datasets: [
                    {
                        label: 'Publikasi Ilmiah',
                        data: trendPub,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                    },
                    {
                        label: 'Karya HKI Terdaftar',
                        data: trendIpr,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 12 } } },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // 2. Doughnut IPR Categories Chart
    const ctxIpr = document.getElementById('iprCategoryChart');
    if (ctxIpr) {
        new Chart(ctxIpr, {
            type: 'doughnut',
            data: {
                labels: Object.keys(iprCategories),
                datasets: [{
                    data: Object.values(iprCategories),
                    backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } }
                },
                cutout: '65%'
            }
        });
    }

    // 3. Horizontal/Vertical Bar Faculty Distribution
    const ctxFaculty = document.getElementById('facultyDistributionChart');
    if (ctxFaculty) {
        new Chart(ctxFaculty, {
            type: 'bar',
            data: {
                labels: facultyLabels,
                datasets: [
                    {
                        label: 'Publikasi',
                        data: facultyPub,
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                    },
                    {
                        label: 'Karya HKI',
                        data: facultyIpr,
                        backgroundColor: '#14b8a6',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 12 } } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush

