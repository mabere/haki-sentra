@extends('layouts.app', ['title' => 'Detail Publikasi', 'header' => 'Rincian Publikasi Ilmiah'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('dosen.publications.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Daftar Publikasi</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Detail Publikasi Ilmiah</h1>
        </div>

        <div class="flex items-center gap-2">
            @if($publication->verification_status === 'approved')
                <a href="{{ route('dosen.incentives.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md shadow-emerald-600/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Ajukan Insentif Publikasi</span>
                </a>
            @endif

            @if($publication->verification_status !== 'approved' || auth()->user()->isAdminLppm())
                <a href="{{ route('dosen.publications.edit', $publication) }}"
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
                'label' => 'Publikasi Terverifikasi (Disetujui)',
                'desc' => 'Publikasi ini telah diperiksa dan disetujui oleh tim verifikator LPPM.',
            ],
            'pending' => [
                'bg' => 'bg-amber-50 border-amber-200 text-amber-800',
                'badge' => 'bg-amber-500 text-white',
                'label' => 'Menunggu Validasi LPPM',
                'desc' => 'Dokumen naskah dan isian sedang dalam antrian pemeriksaan verifikator LPPM.',
            ],
            'revision' => [
                'bg' => 'bg-rose-50 border-rose-200 text-rose-800',
                'badge' => 'bg-rose-600 text-white',
                'label' => 'Perlu Perbaikan (Revisi)',
                'desc' => 'Verifikator meminta perbaikan data atau kelengkapan berkas sebelum dapat disetujui.',
            ],
            'rejected' => [
                'bg' => 'bg-slate-100 border-slate-200 text-slate-800',
                'badge' => 'bg-slate-700 text-white',
                'label' => 'Publikasi Ditolak',
                'desc' => 'Publikasi ini ditolak oleh verifikator LPPM.',
            ],
        ];
        $currentStatus = $statusConfig[$publication->verification_status] ?? $statusConfig['pending'];
    @endphp

    <div class="rounded-2xl border p-5 {{ $currentStatus['bg'] }} shadow-xs">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-sm font-bold {{ $currentStatus['badge'] }}">
                        {{ $currentStatus['label'] }}
                    </span>
                    @if($publication->verified_at)
                    <span class="text-sm opacity-75">
                        pada {{ $publication->verified_at->format('d M Y, H:i') }}
                    </span>
                    @endif
                </div>
                <p class="text-sm">{{ $currentStatus['desc'] }}</p>

                @if($publication->verification_note)
                <div class="mt-3 p-3 rounded-xl bg-white/70 border border-current/20 text-sm font-medium">
                    <p class="font-bold mb-0.5">Catatan Verifikator:</p>
                    <p>{{ $publication->verification_note }}</p>
                </div>
                @endif
            </div>

            @if($publication->verifier)
            <div class="text-right shrink-0 text-sm">
                <span class="opacity-60 block">Verifikator:</span>
                <span class="font-bold">{{ $publication->verifier->name }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Article Core Details Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">

        <div>
            <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 mb-2">
                {{ str_replace('_', ' ', $publication->publication_type) }}
            </span>
            <h2 class="text-lg font-bold text-slate-900 leading-snug">
                {{ $publication->title }}
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-6 text-sm border-t border-slate-100 pt-5">

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Nama Jurnal / Penerbit</span>
                <span class="font-bold text-slate-800 text-sm">{{ $publication->journal_name }}</span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Tahun & Tanggal Terbit</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $publication->publication_year }}
                    {{ $publication->publication_date ? '('.$publication->publication_date->format('d M Y').')' : '' }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Volume / Nomor / Halaman</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $publication->volume ? 'Vol. '.$publication->volume : '-' }}
                    {{ $publication->issue ? 'No. '.$publication->issue : '' }}
                    {{ $publication->pages ? 'Hal. '.$publication->pages : '' }}
                </span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">ISSN / ISBN</span>
                <span class="font-bold text-slate-800">{{ $publication->issn_isbn ?? '-' }}</span>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Kategori Indeksasi & Kuartil</span>
                <div class="flex items-center gap-1.5 mt-0.5">
                    @if($publication->quartile)
                        <span class="px-2 py-0.5 rounded text-[11px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Scopus {{ $publication->quartile }}
                        </span>
                    @endif
                    @if($publication->sinta_grade)
                        <span class="px-2 py-0.5 rounded text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $publication->sinta_grade }}
                        </span>
                    @endif
                    @if(!$publication->quartile && !$publication->sinta_grade)
                        <span class="font-bold text-slate-700 capitalize">{{ $publication->indexation ?? 'Non-Indexed' }}</span>
                    @endif
                </div>
            </div>

            <div>
                <span class="text-slate-400 font-semibold block mb-0.5">Tautan DOI Resmi</span>
                @if($publication->doi)
                <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="font-semibold text-blue-600 hover:underline flex items-center gap-1">
                    <span>{{ $publication->doi }}</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                @else
                <span class="text-slate-400">-</span>
                @endif
            </div>

        </div>

    </div>

    <!-- Authors List Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Tim Penulis & Kontribusi</h3>
            <span class="text-sm text-slate-500">{{ $publication->authors->count() }} Penulis</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">Urutan</th>
                        <th class="py-3 px-4">Nama Penulis</th>
                        <th class="py-3 px-4">Peran Kepenulisan</th>
                        <th class="py-3 px-4">Kategori Afiliasi</th>
                        <th class="py-3 px-4">Institusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($publication->authors as $author)
                    <tr class="hover:bg-slate-50/70">
                        <td class="py-3 px-4 text-center font-bold text-slate-400">
                            #{{ $author->author_order }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-900">
                            {{ $author->author_name }}
                        </td>
                        <td class="py-3 px-4">
                            @if($author->author_role === 'first_author')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    Penulis Pertama
                                </span>
                            @elseif($author->author_role === 'corresponding')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    Korespondensi
                                </span>
                            @else
                                <span class="text-slate-500">Anggota</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($author->author_type === 'internal_lecturer')
                                <span class="text-emerald-700 font-medium">Dosen Internal</span>
                            @elseif($author->author_type === 'student')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Mahasiswa Bimbingan
                                </span>
                            @else
                                <span class="text-slate-600">Mitra / Eksternal</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $author->affiliation ?? 'Universitas' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attached Documents Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Dokumen Lampiran</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <!-- Naskah PDF -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm">
                        PDF
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Naskah Lengkap Artikel</p>
                        <p class="text-[11px] text-slate-500">Berkas artikel publikasi</p>
                    </div>
                </div>

                @if($publication->file_path)
                <a href="{{ route('dosen.publications.file', [$publication, 'manuscript']) }}" target="_blank"
                   class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition">
                    Unduh PDF
                </a>
                @else
                <span class="text-sm text-slate-400 italic">Tidak ada berkas</span>
                @endif
            </div>

            <!-- LoA PDF -->
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                        LoA
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Bukti Penerimaan / LoA</p>
                        <p class="text-[11px] text-slate-500">Surat keterangan terima artikel</p>
                    </div>
                </div>

                @if($publication->loa_path)
                <a href="{{ route('dosen.publications.file', [$publication, 'loa']) }}" target="_blank"
                   class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm transition">
                    Unduh LoA
                </a>
                @else
                <span class="text-sm text-slate-400 italic">Tidak ada berkas</span>
                @endif
            </div>

        </div>
    </div>

    <!-- Verification Timeline / Logs -->
    @if($publication->verificationLogs->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Riwayat Log Verifikasi</h3>
        <div class="space-y-3">
            @foreach($publication->verificationLogs as $log)
            <div class="flex items-start gap-3 text-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-500 mt-1.5 shrink-0"></span>
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
    <x-revision-thread :target="$publication" type="publication" />

</div>
@endsection

