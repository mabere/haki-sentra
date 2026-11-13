@extends('layouts.app', ['title' => 'Capaian IKU', 'header' => 'Dashboard Eksekutif Capaian IKU 3 & 5'])

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Actions Bar (SISTER Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">{{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</h1>
            <div class="flex items-center gap-2 text-sm text-slate-500 font-medium mt-1">
                <span>Beranda</span>
                <span>/</span>
                <span>Eksekutif &amp; Laporan</span>
                <span>/</span>
                <span class="text-blue-700 font-semibold">Capaian IKU 3 &amp; 5</span>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <span class="text-sm font-semibold text-slate-500">Tahun Evaluasi:</span>
            <span class="px-3.5 py-1.5 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-900 shadow-2xs">
                Tahun {{ $year }}
            </span>
            <a href="{{ route('reports.export') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-2xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor Laporan
            </a>
        </div>
    </div>

    <!-- SISTER Style Welcome Banner (Gambar 1) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
        <!-- Big Round Avatar / User Photo Support -->
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden shrink-0 shadow-md shadow-blue-600/20 ring-4 ring-blue-100 flex items-center justify-center bg-blue-600 text-white">
            @if(auth()->user()->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
            @else
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            @endif
        </div>

        <!-- Welcome Text Details -->
        <div class="space-y-1.5 text-center sm:text-left flex-1">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Selamat Datang
            </h2>
            <div class="flex items-center justify-center sm:justify-start gap-2 pt-0.5">
                <span class="text-base sm:text-lg font-bold text-blue-700">
                    Dashboard Capaian IKU 3 &amp; IKU 5
                </span>
            </div>
            <p class="text-sm md:text-base text-slate-600 font-medium pt-1 leading-relaxed">
                {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }} &bull; {{ app_setting('lppm_name', 'Lembaga Penelitian dan Pengabdian kepada Masyarakat') }} &bull; {{ app_setting('sentra_hki_name', 'Sentra Hak Kekayaan Intelektual') }} &bull; Publikasi Ilmiah Dosen
            </p>
        </div>
    </div>

    <!-- SISTER Style Blue Alert Banner (Gambar 1: Pusat Bantuan SISTER style) -->
    <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                i
            </div>
            <div>
                <h3 class="text-sm font-bold text-sky-950 uppercase tracking-wide">
                    PEMANTAUAN CAPAIAN IKU 5: KARYA RISET &amp; HKI YANG DITERAPKAN DI MASYARAKAT
                </h3>
                <p class="text-sm md:text-base text-sky-800 mt-1 leading-relaxed max-w-4xl">
                    Akumulasi publikasi dosen pada jurnal bereputasi (Scopus/WoS) serta karya Hak Kekayaan Intelektual yang telah memperoleh sertifikat resmi DJKI Kemenkumham (Granted) pada Tahun Evaluasi {{ $year }}.
                </p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ route('reports.summary') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-300 hover:border-sky-400 hover:bg-sky-50/50 text-slate-800 text-sm font-bold rounded-xl shadow-2xs transition whitespace-nowrap">
                Info Selengkapnya
            </a>
        </div>
    </div>

    <!-- Section: Informasi Penting (Gambar 1 Style) -->
    <div class="space-y-4">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Informasi Penting</h2>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8 space-y-6">
            <!-- Header Kartu dengan icon amber/kuning (Gambar 1: Hasil Pemadanan Data style) -->
            <div class="space-y-1.5 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">
                        i
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">
                        Hasil Pemadanan Data &amp; Rekapitulasi Luaran IKU
                    </h3>
                </div>
                <p class="text-sm md:text-base text-slate-600 pl-8.5 leading-relaxed">
                    Pastikan status luaran dosen Anda telah sesuai. Segera lakukan pemadanan data sertifikat granted dan indeksasi artikel jika dibutuhkan untuk pelaporan institusi.
                </p>
            </div>

            <!-- Sub-Box Kuning/Krem Halus SISTER (Gambar 1: Kenapa Anda harus melakukan ini? style) -->
            <div class="bg-amber-50/70 border border-amber-200/90 rounded-2xl p-5 sm:p-6 space-y-3.5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h4 class="text-sm md:text-base font-bold text-amber-950">
                            Capaian IKU 5: Karya Riset &amp; HKI yang Diterapkan
                        </h4>
                        <p class="text-sm text-amber-900 mt-0.5">
                            Target capaian tahunan institusi: <strong>{{ $targetIku5 }} Luaran Bereputasi</strong>
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2">
                        <span class="text-sm font-medium text-amber-900">Realisasi:</span>
                        <span class="px-3.5 py-1 bg-amber-500 text-white text-sm font-bold rounded-full shadow-2xs">
                            {{ $realisasiIku5 }} Luaran ({{ $persenIku5 }}% dari Target)
                        </span>
                    </div>
                </div>

                <!-- Progress Bar SISTER -->
                <div class="w-full h-3.5 bg-amber-200/70 rounded-full overflow-hidden p-0.5 border border-amber-300/80">
                    <div class="h-full bg-amber-600 rounded-full transition-all duration-500" style="width: {{ $persenIku5 }}%;"></div>
                </div>
            </div>

            <!-- Grid 4 Kartu Metrik (Terang, Bersih, Kontras Tinggi SISTER) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                <!-- Scopus / WoS -->
                <div class="bg-slate-50/70 hover:bg-white border border-slate-200 hover:border-blue-400 rounded-2xl p-5 transition shadow-2xs space-y-2">
                    <span class="text-sm font-bold uppercase tracking-wider text-slate-500 block">Scopus / WoS</span>
                    <span class="text-3xl font-extrabold text-slate-900 block">{{ $scopusPubs }}</span>
                    <div class="pt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Artikel Internasional
                        </span>
                    </div>
                </div>

                <!-- Paten Granted -->
                <div class="bg-slate-50/70 hover:bg-white border border-slate-200 hover:border-amber-400 rounded-2xl p-5 transition shadow-2xs space-y-2">
                    <span class="text-sm font-bold uppercase tracking-wider text-slate-500 block">Paten Granted</span>
                    <span class="text-3xl font-extrabold text-slate-900 block">{{ $patentGranted }}</span>
                    <div class="pt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-bold bg-amber-100 text-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Invensi Teknologi
                        </span>
                    </div>
                </div>

                <!-- Hak Cipta Terbit -->
                <div class="bg-slate-50/70 hover:bg-white border border-slate-200 hover:border-indigo-400 rounded-2xl p-5 transition shadow-2xs space-y-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Hak Cipta Terbit</span>
                    <span class="text-3xl font-extrabold text-slate-900 block">{{ $copyrightGranted }}</span>
                    <div class="pt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Sertifikat Resmi DJKI
                        </span>
                    </div>
                </div>

                <!-- Jurnal SINTA -->
                <div class="bg-slate-50/70 hover:bg-white border border-slate-200 hover:border-purple-400 rounded-2xl p-5 transition shadow-2xs space-y-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Jurnal SINTA</span>
                    <span class="text-3xl font-extrabold text-slate-900 block">{{ $sintaPubs }}</span>
                    <div class="pt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Akreditasi SINTA
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Column: Fakultas Distribution & Top Contributors (SISTER Clean Table & Card) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Distribusi per Fakultas -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Distribusi Luaran per Fakultas</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Perbandingan produktivitas publikasi &amp; HKI terverifikasi</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase text-slate-600">
                        <tr>
                            <th class="py-3 px-3.5">Fakultas</th>
                            <th class="py-3 px-3.5 text-center">Publikasi</th>
                            <th class="py-3 px-3.5 text-center">HKI</th>
                            <th class="py-3 px-3.5 text-right">Total Luaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($facultyStats as $fStat)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-3.5">
                                <span class="font-bold text-slate-900 block">{{ $fStat['faculty'] }}</span>
                                <span class="text-[11px] text-slate-500 font-mono">Kode: {{ $fStat['code'] }}</span>
                            </td>
                            <td class="py-3.5 px-3.5 text-center font-bold text-blue-700">
                                {{ $fStat['publications'] }}
                            </td>
                            <td class="py-3.5 px-3.5 text-center font-bold text-emerald-700">
                                {{ $fStat['iprs'] }}
                            </td>
                            <td class="py-3.5 px-3.5 text-right">
                                <span class="font-extrabold text-slate-900 px-2.5 py-1 rounded-lg bg-slate-100">
                                    {{ $fStat['total'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Dosen Kontributor (Leaderboard) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3.5">
                <h3 class="text-sm font-bold text-slate-900">Dosen Kontributor Teraktif</h3>
                <p class="text-sm text-slate-500 mt-0.5">Dosen dengan publikasi terverifikasi terbanyak</p>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($topLecturers as $rank => $tLec)
                <div class="py-3.5 flex items-center justify-between gap-3 text-sm hover:bg-slate-50/50 transition px-1 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-sm {{ $rank === 0 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-700' }}">
                            #{{ $rank + 1 }}
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 block leading-tight">{{ $tLec->full_name }}</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">{{ $tLec->department->name ?? '-' }}</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 whitespace-nowrap">
                        {{ $tLec->publications_count }} Publikasi
                    </span>
                </div>
                @empty
                <p class="py-8 text-center text-sm text-slate-400">Belum ada data publikasi terverifikasi.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
