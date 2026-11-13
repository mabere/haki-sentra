<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Verifikasi HKI - {{ $intellectualProperty->application_number ?? $intellectualProperty->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .page {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            }
        }
        .font-serif-official {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-sans antialiased min-h-screen py-8">

    <!-- Top Action Bar (No Print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 px-4 flex items-center justify-between">
        <a href="{{ route('verification.ipr.review', $intellectualProperty) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Halaman Telaah</span>
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-md cursor-pointer transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Official Sheet -->
    <div class="page max-w-4xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-xl p-10 sm:p-14 font-serif-official text-slate-900 leading-normal">

        <!-- Kop Surat Resmi -->
        <div class="border-b-4 border-double border-slate-900 pb-4 mb-6">
            <div class="flex items-center justify-between gap-4">
                <!-- Logo Kampus / LPPM -->
                <div class="h-24 w-24 flex items-center justify-center shrink-0">
                    <img src="{{ app_setting_logo('logo') }}" alt="Logo {{ app_setting('institution_name', 'Universitas Panca Sakti') }}" class="h-20 w-20 object-contain drop-shadow-xs">
                </div>

                <!-- Teks Kop Surat -->
                <div class="text-center flex-1">
                    <h2 class="text-lg sm:text-xl font-bold tracking-wide uppercase text-slate-900">
                        {{ strtoupper(app_setting('institution_legal_name', 'UNIVERSITAS PANCA SAKTI BEKASI')) }}
                    </h2>
                    <h3 class="text-base sm:text-lg font-bold uppercase text-slate-900 tracking-wider">
                        {{ strtoupper(app_setting('lppm_name', 'LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT')) }}
                    </h3>
                    <h4 class="text-sm sm:text-base font-semibold text-amber-900 tracking-wide">
                        {{ strtoupper(app_setting('sentra_hki_name', 'SENTRA KEKAYAAN INTELEKTUAL (SENTRA HKI)')) }}
                    </h4>
                    <p class="text-xs text-slate-700 mt-1 font-sans">
                        {{ app_setting('institution_address', 'Jl. Raya Hankam No.54, Jatirahayu, Kec. Pd. Melati, Kota Bekasi, Jawa Barat 17414') }}<br>
                        Laman: {{ app_setting('institution_website', 'www.pancasakti.ac.id') }} &bull; Surel: {{ app_setting('institution_email', 'lppm@pancasakti.ac.id') }}
                    </p>
                </div>

                <!-- Logo Sentra KI -->
                <div class="h-24 w-24 flex items-center justify-center shrink-0">
                    <div class="w-20 h-20 rounded-2xl border-2 border-emerald-700 bg-emerald-50 flex flex-col items-center justify-center p-1 text-center shadow-xs">
                        <svg class="w-8 h-8 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-[9px] font-bold tracking-tight text-emerald-900 uppercase font-sans">SENTRA HKI</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nomor & Judul Surat -->
        <div class="text-center my-6">
            <h1 class="text-base sm:text-lg font-bold uppercase underline tracking-wide">
                SURAT KETERANGAN VERIFIKASI HAK KEKAYAAN INTELEKTUAL
            </h1>
            <p class="text-xs font-semibold text-slate-800 font-sans mt-1">
                Nomor: {{ sprintf('%03d', $intellectualProperty->id) }}/SK-HKI/LPPM-{{ strtoupper(app_setting('institution_short_name', 'UPSB')) }}/{{ date('m/Y') }}
            </p>
        </div>

        <!-- Pembuka -->
        <p class="text-sm text-justify mb-4">
            {{ app_setting('head_lppm_title', 'Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)') }} {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}, bertindak atas nama Tim Verifikator {{ app_setting('sentra_hki_name', 'Sentra Kekayaan Intelektual') }}, dengan ini menerangkan bahwa karya cipta/invensi di bawah ini telah selesai ditelaah keabsahan administratif dan substantif dengan hasil <strong>MEMENUHI SYARAT (TERVERIFIKASI & DISETUJUI)</strong>:
        </p>

        <!-- Tabel Rincian Data HKI -->
        <div class="my-4 border border-slate-400 font-sans text-xs">
            <table class="w-full border-collapse">
                <tbody>
                    <tr class="border-b border-slate-300">
                        <td class="w-1/3 py-2 px-3 bg-slate-50 font-semibold text-slate-700">1. Judul Karya / Invensi</td>
                        <td class="w-2/3 py-2 px-3 font-bold text-slate-900">{{ $intellectualProperty->title }}</td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">2. Kategori Layanan HKI</td>
                        <td class="py-2 px-3 font-semibold text-slate-900 uppercase">{{ str_replace('_', ' ', $intellectualProperty->ipr_type) }}</td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">3. Sub-Jenis / Bidang Ciptaan</td>
                        <td class="py-2 px-3 text-slate-900">{{ $intellectualProperty->sub_type ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">4. Nomor Permohonan DJKI</td>
                        <td class="py-2 px-3 font-bold text-slate-900">{{ $intellectualProperty->application_number ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">5. Nomor Pencatatan / Sertifikat</td>
                        <td class="py-2 px-3 font-bold {{ $intellectualProperty->registration_number ? 'text-emerald-700' : 'text-slate-600' }}">
                            {{ $intellectualProperty->registration_number ?? 'Dalam Proses Penerbitan DJKI Kemenkumham' }}
                        </td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">6. Tanggal Permohonan</td>
                        <td class="py-2 px-3 text-slate-900">
                            {{ $intellectualProperty->application_date ? $intellectualProperty->application_date->format('d F Y') : '-' }}
                        </td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">7. Pemegang Hak Cipta / Paten</td>
                        <td class="py-2 px-3 font-semibold text-slate-900">{{ $intellectualProperty->rights_holder }}</td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">8. Status Verifikasi LPPM</td>
                        <td class="py-2 px-3">
                            <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                {{ $intellectualProperty->verification_status }} (Telah Disetujui)
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2 px-3 bg-slate-50 font-semibold text-slate-700">9. Status Resmi DJKI</td>
                        <td class="py-2 px-3 font-bold text-slate-900 uppercase">
                            {{ $intellectualProperty->djki_status }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tim Inventor / Pencipta -->
        <div class="my-4">
            <h4 class="text-xs font-bold uppercase tracking-wide text-slate-800 mb-1.5 font-sans">
                Susunan Tim Pencipta / Inventor:
            </h4>
            <div class="border border-slate-400 font-sans text-xs">
                <table class="w-full border-collapse">
                    <thead class="bg-slate-100 border-b border-slate-300 text-slate-700">
                        <tr>
                            <th class="py-1.5 px-3 w-10 text-center font-bold">No</th>
                            <th class="py-1.5 px-3 text-left font-bold">Nama Lengkap</th>
                            <th class="py-1.5 px-3 text-left font-bold">Peran Tim</th>
                            <th class="py-1.5 px-3 text-center font-bold">Kontribusi</th>
                            <th class="py-1.5 px-3 text-left font-bold">Institusi / Afiliasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-300">
                        @foreach($intellectualProperty->inventors as $inv)
                        <tr>
                            <td class="py-1.5 px-3 text-center font-bold">{{ $inv->inventor_order }}</td>
                            <td class="py-1.5 px-3 font-semibold">{{ $inv->inventor_name }}</td>
                            <td class="py-1.5 px-3">{{ $inv->role === 'inventor_utama' ? 'Inventor Utama' : 'Anggota' }}</td>
                            <td class="py-1.5 px-3 text-center">{{ $inv->contribution_percentage ? $inv->contribution_percentage . '%' : '-' }}</td>
                            <td class="py-1.5 px-3">{{ $inv->affiliation ?? 'Universitas Panca Sakti Bekasi' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Penutup Surat -->
        <p class="text-sm text-justify mt-4 mb-8">
            Demikian Surat Keterangan ini diterbitkan untuk dipergunakan sebagaimana mestinya, baik sebagai bukti verifikasi keabsahan institusi, kelengkapan berkas administrasi insentif publikasi/HKI, pelaporan Beban Kerja Dosen (BKD), maupun instrumen akreditasi program studi dan perguruan tinggi.
        </p>

        <!-- Tanda Tangan & QR Code -->
        <div class="flex items-end justify-between mt-10 pt-4 font-sans text-xs">
            <!-- QR Code Validasi Digital -->
            <div class="text-center p-3 border border-slate-200 rounded-xl bg-slate-50/50">
                <div class="w-20 h-20 mx-auto bg-white border border-slate-300 p-1 rounded-lg flex items-center justify-center">
                    <!-- SVG Simple QR Placeholder with System Hash -->
                    <svg class="w-full h-full text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm14 0h2v2h-2v-2zm-4 0h2v2h-2v-2zm2 2h2v2h-2v-2zm2 2h2v2h-2v-2zm-6 0h2v2h-2v-2zm4-6h2v2h-2v-2zm-2 2h2v2h-2v-2zm-2-2h2v2h-2v-2z"/>
                    </svg>
                </div>
                <p class="text-[9px] text-slate-500 mt-1 font-mono">
                    ID: {{ md5($intellectualProperty->id . $intellectualProperty->application_number) }}
                </p>
                <p class="text-[9px] text-slate-600 font-semibold">Tervalidasi Digital Sentra HKI</p>
            </div>

            <!-- Tanda Tangan Pejabat -->
            <div class="text-left w-64">
                <p class="text-slate-800">
                    {{ app_setting('institution_city', 'Bekasi') }}, {{ $intellectualProperty->verified_at ? $intellectualProperty->verified_at->format('d F Y') : date('d F Y') }}
                </p>
                <p class="font-bold text-slate-900 mt-0.5">
                    {{ app_setting('head_lppm_title', 'Ketua LPPM') }} {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }},
                </p>

                <div class="h-16 flex items-center my-1">
                    @if(app_setting_signature())
                        <img src="{{ app_setting_signature() }}" alt="Tanda Tangan & Stempel" class="h-16 max-w-full object-contain">
                    @else
                        <!-- Digital Signature Watermark -->
                        <div class="px-3 py-1 border border-emerald-300 bg-emerald-50 text-emerald-800 rounded text-[10px] font-bold tracking-wider uppercase font-mono">
                            [ DITANDATANGANI SECARA ELEKTRONIK ]
                        </div>
                    @endif
                </div>

                <p class="font-bold text-slate-900 underline text-sm">
                    {{ app_setting('head_lppm_name', 'Dr. H. Ahmad Fauzi, M.T.') }}
                </p>
                <p class="text-slate-700 text-xs">
                    NIDN / NIP: {{ app_setting('head_lppm_nidn', '0412057801') }}
                </p>
            </div>
        </div>

    </div>

</body>
</html>

