@extends('layouts.app', ['title' => 'Detail Karya HKI', 'header' => 'Rincian Hak Kekayaan Intelektual'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('dosen.ipr.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Daftar Karya HKI</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Detail Hak Kekayaan Intelektual (HKI)</h1>
        </div>

        <div class="flex items-center gap-2">
            @if($intellectualProperty->verification_status === 'approved')
                <a href="{{ route('dosen.incentives.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md shadow-emerald-600/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Ajukan Insentif HKI</span>
                </a>
            @endif

            @if($intellectualProperty->verification_status !== 'approved' || auth()->user()->isAdminLppm())
                <a href="{{ route('dosen.ipr.edit', $intellectualProperty) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Data</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Status Banner / Notes -->
    @php
        $statusConfig = [
            'approved' => [
                'bg' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                'badge' => 'bg-emerald-600 text-white',
                'label' => 'HKI Terverifikasi (Disetujui)',
                'desc' => 'Karya HKI dan dokumen sertifikat telah diperiksa dan disetujui oleh tim verifikator LPPM.',
            ],
            'pending' => [
                'bg' => 'bg-amber-50 border-amber-200 text-amber-800',
                'badge' => 'bg-amber-500 text-white',
                'label' => 'Menunggu Validasi LPPM',
                'desc' => 'Dokumen sertifikat dan data pendaftaran sedang dalam antrian pemeriksaan verifikator LPPM.',
            ],
            'revision' => [
                'bg' => 'bg-rose-50 border-rose-200 text-rose-800',
                'badge' => 'bg-rose-600 text-white',
                'label' => 'Perlu Perbaikan (Revisi)',
                'desc' => 'Verifikator meminta perbaikan data atau kelengkapan berkas sertifikat.',
            ],
            'rejected' => [
                'bg' => 'bg-slate-100 border-slate-200 text-slate-800',
                'badge' => 'bg-slate-700 text-white',
                'label' => 'HKI Ditolak',
                'desc' => 'Pendaftaran data HKI ini ditolak oleh verifikator LPPM.',
            ],
        ];
        $currentStatus = $statusConfig[$intellectualProperty->verification_status] ?? $statusConfig['pending'];
    @endphp

    <div class="rounded-2xl border p-5 {{ $currentStatus['bg'] }} shadow-xs">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-sm font-bold {{ $currentStatus['badge'] }}">
                        {{ $currentStatus['label'] }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-sm font-bold uppercase bg-white/80 border border-current/20">
                        Status DJKI: {{ strtoupper($intellectualProperty->djki_status) }}
                    </span>
                    @if($intellectualProperty->verified_at)
                    <span class="text-sm opacity-75">
                        pada {{ $intellectualProperty->verified_at->format('d M Y, H:i') }}
                    </span>
                    @endif
                </div>
                <p class="text-sm">{{ $currentStatus['desc'] }}</p>

                @if($intellectualProperty->verification_note)
                <div class="mt-3 p-3 rounded-xl bg-white/70 border border-current/20 text-sm font-medium">
                    <p class="font-bold mb-0.5">Catatan Verifikator:</p>
                    <p>{{ $intellectualProperty->verification_note }}</p>
                </div>
                @endif
            </div>

            @if($intellectualProperty->verifier)
            <div class="text-right shrink-0 text-sm">
                <span class="opacity-60 block">Verifikator:</span>
                <span class="font-bold">{{ $intellectualProperty->verifier->name }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Core Details Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">

        <div>
            <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200 mb-2">
                {{ str_replace('_', ' ', $intellectualProperty->ipr_type) }}
            </span>
            <h2 class="text-lg font-bold text-slate-900 leading-snug">
                {{ $intellectualProperty->title }}
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-6 text-sm border-t border-slate-100 pt-5">

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Sub-Jenis / Bidang Invensi</span>
                <span class="font-bold text-slate-800 text-sm">{{ $intellectualProperty->sub_type ?? '-' }}</span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Pemegang Hak</span>
                <span class="font-bold text-slate-800 text-sm">{{ $intellectualProperty->rights_holder }}</span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Nomor Permohonan DJKI</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $intellectualProperty->application_number ?? '-' }}
                    @if($intellectualProperty->application_date)
                        <span class="block text-[11px] text-slate-500 font-normal">Tgl: {{ $intellectualProperty->application_date->format('d M Y') }}</span>
                    @endif
                </span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Nomor Pencatatan / Pendaftaran</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $intellectualProperty->registration_number ?? '-' }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Tanggal Terbit Sertifikat</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $intellectualProperty->grant_date ? $intellectualProperty->grant_date->format('d F Y') : '-' }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Status Pendaftaran DJKI</span>
                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $intellectualProperty->djki_status === 'granted' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-50 text-blue-700' }}">
                    {{ strtoupper($intellectualProperty->djki_status) }}
                </span>
            </div>

        </div>

    </div>

    <!-- Inventors List Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Tim Inventor / Pencipta</h3>
            <span class="text-sm text-slate-500">{{ $intellectualProperty->inventors->count() }} Inventor</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">Urutan</th>
                        <th class="py-3 px-4">Nama Inventor</th>
                        <th class="py-3 px-4">Peran Tim</th>
                        <th class="py-3 px-4">Kontribusi (%)</th>
                        <th class="py-3 px-4">Kategori Afiliasi</th>
                        <th class="py-3 px-4">Institusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($intellectualProperty->inventors as $inv)
                    <tr class="hover:bg-slate-50/70">
                        <td class="py-3 px-4 text-center font-bold text-slate-400">
                            #{{ $inv->inventor_order }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-900">
                            {{ $inv->inventor_name }}
                        </td>
                        <td class="py-3 px-4">
                            @if($inv->role === 'inventor_utama')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Inventor Utama
                                </span>
                            @else
                                <span class="text-slate-500">Anggota</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $inv->contribution_percentage ? $inv->contribution_percentage . '%' : '-' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($inv->inventor_type === 'internal_lecturer')
                                <span class="text-emerald-700 font-medium">Dosen Internal</span>
                            @elseif($inv->inventor_type === 'student')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Mahasiswa Bimbingan
                                </span>
                            @else
                                <span class="text-slate-600">Mitra Eksternal</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $inv->affiliation ?? 'Universitas' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attached Documents Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Dokumen Lampiran HKI</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <!-- Sertifikat DJKI PDF -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                        PDF
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Sertifikat Resmi DJKI</p>
                        <p class="text-[11px] text-slate-500">Bukti pencatatan ciptaan / paten</p>
                    </div>
                </div>

                @if($intellectualProperty->certificate_file)
                <a href="{{ route('dosen.ipr.file', [$intellectualProperty, 'certificate']) }}" target="_blank"
                   class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm transition">
                    Unduh Sertifikat
                </a>
                @else
                <span class="text-sm text-slate-400 italic">Belum ada berkas</span>
                @endif
            </div>

            <!-- Deskripsi Invensi PDF -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                        DOC
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Deskripsi / Uraian Ciptaan</p>
                        <p class="text-[11px] text-slate-500">Spesifikasi paten atau uraian manual</p>
                    </div>
                </div>

                @if($intellectualProperty->description_file)
                <a href="{{ route('dosen.ipr.file', [$intellectualProperty, 'description']) }}" target="_blank"
                   class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition">
                    Unduh Deskripsi
                </a>
                @else
                <span class="text-sm text-slate-400 italic">Tidak ada berkas</span>
                @endif
            </div>

        </div>
    </div>

    <!-- Verification Timeline / Logs -->
    @if($intellectualProperty->verificationLogs->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Riwayat Log Verifikasi</h3>
        <div class="space-y-3">
            @foreach($intellectualProperty->verificationLogs as $log)
            <div class="flex items-start gap-3 text-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0"></span>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800">{{ $log->reviewer ? $log->reviewer->name : 'Sistem' }}</span>
                        <span class="text-slate-400">{{ $log->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <p class="text-slate-600 mt-0.5">{{ $log->notes }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Dialog & Catatan Revisi Interaktif Verifikator <-> Dosen -->
    <x-revision-thread :target="$intellectualProperty" type="ipr" />

</div>
@endsection

