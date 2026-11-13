@extends('layouts.app', ['title' => 'Validasi HKI', 'header' => 'Panel Verifikasi Hak Kekayaan Intelektual'])

@section('content')
<div class="space-y-6">

    <!-- Header Title -->
    <div>
        <h1 class="text-xl font-bold text-slate-900">Antrian Verifikasi Karya HKI</h1>
        <p class="text-sm text-slate-500">Periksa keabsahan sertifikat resmi DJKI, status permohonan, dan tim inventor dosen.</p>
    </div>

    <!-- Stats Filter Tabs -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('verification.ipr.index', ['status' => 'pending']) }}"
           class="p-4 rounded-2xl border transition {{ request('status', 'pending') === 'pending' ? 'bg-amber-500 text-white border-amber-600 shadow-md shadow-amber-500/20' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <p class="text-sm font-semibold opacity-90 uppercase tracking-wider">Menunggu Validasi</p>
            <p class="text-2xl font-extrabold mt-1">{{ $counts['pending'] }}</p>
        </a>

        <a href="{{ route('verification.ipr.index', ['status' => 'revision']) }}"
           class="p-4 rounded-2xl border transition {{ request('status') === 'revision' ? 'bg-rose-600 text-white border-rose-700 shadow-md shadow-rose-600/20' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <p class="text-sm font-semibold opacity-90 uppercase tracking-wider">Perlu Revisi</p>
            <p class="text-2xl font-extrabold mt-1">{{ $counts['revision'] }}</p>
        </a>

        <a href="{{ route('verification.ipr.index', ['status' => 'approved']) }}"
           class="p-4 rounded-2xl border transition {{ request('status') === 'approved' ? 'bg-emerald-600 text-white border-emerald-700 shadow-md shadow-emerald-600/20' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <p class="text-sm font-semibold opacity-90 uppercase tracking-wider">Telah Disetujui</p>
            <p class="text-2xl font-extrabold mt-1">{{ $counts['approved'] }}</p>
        </a>

        <a href="{{ route('verification.ipr.index', ['status' => 'all']) }}"
           class="p-4 rounded-2xl border transition {{ request('status') === 'all' ? 'bg-slate-900 text-white border-slate-950 shadow-md shadow-slate-900/20' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
            <p class="text-sm font-semibold opacity-90 uppercase tracking-wider">Semua Berkas HKI</p>
            <p class="text-2xl font-extrabold mt-1">{{ array_sum($counts) }}</p>
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form method="GET" action="{{ route('verification.ipr.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="status" value="{{ request('status', 'pending') }}">

            <div class="relative flex-1">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari nama dosen, judul ciptaan, no permohonan, atau no sertifikat..."
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition">
                Cari Berkas HKI
            </button>
        </form>
    </div>

    <!-- Verification Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Dosen Pengusul</th>
                        <th class="py-3.5 px-4">Judul Ciptaan / Invensi</th>
                        <th class="py-3.5 px-4">Jenis HKI</th>
                        <th class="py-3.5 px-4 text-center">Status DJKI</th>
                        <th class="py-3.5 px-4 text-center">Sertifikat</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($iprs as $index => $ipr)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-4 text-center text-slate-400 font-medium">
                            {{ $iprs->firstItem() + $index }}
                        </td>

                        <!-- Dosen / Pemohon Info -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900">{{ $ipr->user->lecturer ? $ipr->user->lecturer->full_name : $ipr->user->name }}</p>
                            @if($ipr->user->lecturer)
                                <p class="text-[11px] text-slate-500">NIDN: {{ $ipr->user->lecturer->nidn ?? '-' }}</p>
                            @else
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-50 text-purple-700">Pemohon Publik</span>
                            @endif
                        </td>

                        <!-- Title -->
                        <td class="py-4 px-4 max-w-sm">
                            <p class="font-bold text-slate-900 leading-snug line-clamp-2">{{ $ipr->title }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                @if($ipr->application_number)
                                    <span class="font-mono text-slate-700 font-semibold">{{ $ipr->application_number }}</span> &bull;
                                @endif
                                Pemegang: {{ $ipr->rights_holder }}
                            </p>
                        </td>

                        <!-- Type -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ str_replace('_', ' ', $ipr->ipr_type) }}
                            </span>
                        </td>

                        <!-- Status DJKI -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ipr->djki_status === 'granted' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-50 text-blue-700' }}">
                                {{ strtoupper($ipr->djki_status) }}
                            </span>
                        </td>

                        <!-- Sertifikat PDF -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($ipr->certificate_file)
                            <a href="{{ route('verification.ipr.file', [$ipr, 'certificate']) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition">
                                <span>PDF</span>
                            </a>
                            @else
                            <span class="text-slate-400 italic">-</span>
                            @endif
                        </td>

                        <!-- Verification Status -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @php
                                $statusStyles = [
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'revision' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'rejected' => 'bg-slate-100 text-slate-700 border-slate-200',
                                ];
                                $statusNames = [
                                    'approved' => 'Disetujui',
                                    'pending' => 'Menunggu Review',
                                    'revision' => 'Perlu Revisi',
                                    'rejected' => 'Ditolak',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusStyles[$ipr->verification_status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusNames[$ipr->verification_status] ?? $ipr->verification_status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <a href="{{ route('verification.ipr.review', $ipr) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm shadow-xs transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span>Telaah HKI</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-sm text-slate-400">
                            Tidak ada berkas HKI dalam antrian ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($iprs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $iprs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

