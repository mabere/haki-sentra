@extends('layouts.app', ['title' => 'Rekap BKD & Akreditasi', 'header' => 'Rekapitulasi Luaran BKD & Akreditasi'])

@section('content')
<div class="space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Rekapitulasi Luaran BKD & Akreditasi</h1>
            <p class="text-sm text-slate-500">Kompilasi karya publikasi ilmiah dan HKI untuk instrumen akreditasi program studi & pelaporan BKD dosen.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('reports.export') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-600/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Unduh Excel (CSV)</span>
            </a>

            <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Rekap</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form method="GET" action="{{ route('reports.summary') }}" class="flex flex-col sm:flex-row items-center gap-3 text-sm">
            <div class="w-full sm:w-56">
                <label class="block font-semibold text-slate-700 mb-1">Pilihan Jenis Luaran</label>
                <select name="type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Luaran (Publikasi & HKI)</option>
                    <option value="publications" {{ $type === 'publications' ? 'selected' : '' }}>Hanya Publikasi Ilmiah</option>
                    <option value="iprs" {{ $type === 'iprs' ? 'selected' : '' }}>Hanya Hak Kekayaan Intelektual</option>
                </select>
            </div>

            <div class="w-full sm:w-64">
                <label class="block font-semibold text-slate-700 mb-1">Filter Program Studi</label>
                <select name="department_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    <option value="all">Semua Program Studi</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $deptId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->faculty->code ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-5 sm:pt-4">
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-semibold transition cursor-pointer">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table 1: Publikasi Ilmiah -->
    @if($type === 'all' || $type === 'publications')
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <span>Publikasi Ilmiah Dosen Terverifikasi ({{ $publications->count() }} Data)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-[11px] font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Judul Artikel Ilmiah</th>
                        <th class="py-3 px-4">Nama Jurnal & Penerbit</th>
                        <th class="py-3 px-4">Penulis Dosen</th>
                        <th class="py-3 px-4 text-center">Indeksasi</th>
                        <th class="py-3 px-4 text-center">Tanggal Terbit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($publications as $index => $pub)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 text-center text-slate-400 font-semibold">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 max-w-md">
                            <span class="font-bold text-slate-900 block leading-snug">{{ $pub->title }}</span>
                            @if($pub->doi)
                                <span class="text-[11px] font-mono text-indigo-600">DOI: {{ $pub->doi }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="font-semibold text-slate-800 block">{{ $pub->journal_name }}</span>
                            <span class="text-[11px] text-slate-400">ISSN: {{ $pub->issn ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-slate-900 block">{{ $pub->authors->pluck('author_name')->join(', ') ?: $pub->user->name }}</span>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pub->indexation === 'scopus' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $pub->indexation }} {{ $pub->quartile ? "({$pub->quartile})" : '' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap font-medium text-slate-600">
                            {{ $pub->publication_date ? $pub->publication_date->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-sm text-slate-400">Tidak ada data publikasi terverifikasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Table 2: Hak Kekayaan Intelektual -->
    @if($type === 'all' || $type === 'iprs')
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                <span>Hak Kekayaan Intelektual Terverifikasi ({{ $iprs->count() }} Data)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-[11px] font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Judul Ciptaan / Invensi</th>
                        <th class="py-3 px-4">Jenis HKI</th>
                        <th class="py-3 px-4">Tim Pencipta / Inventor</th>
                        <th class="py-3 px-4 text-center">No. Pencatatan / DJKI</th>
                        <th class="py-3 px-4 text-center">Status DJKI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($iprs as $index => $ipr)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3 px-4 text-center text-slate-400 font-semibold">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 max-w-md">
                            <span class="font-bold text-slate-900 block leading-snug">{{ $ipr->title }}</span>
                            <span class="text-[11px] text-slate-400">Pemegang: {{ $ipr->rights_holder }}</span>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ str_replace('_', ' ', $ipr->ipr_type) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-slate-900 block">{{ $ipr->inventors->pluck('inventor_name')->join(', ') ?: $ipr->user->name }}</span>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap font-mono text-[11px]">
                            <span class="font-bold text-emerald-700 block">{{ $ipr->registration_number ?? '-' }}</span>
                            <span class="text-slate-400 block text-[10px]">{{ $ipr->application_number ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ipr->djki_status === 'granted' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-50 text-blue-700' }}">
                                {{ $ipr->djki_status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-sm text-slate-400">Tidak ada data karya HKI terverifikasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
