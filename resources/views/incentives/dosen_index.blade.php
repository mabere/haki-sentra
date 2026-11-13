@extends('layouts.app', ['title' => 'Insentif Luaran', 'header' => 'Pengajuan & Riwayat Insentif Luaran'])

@section('content')
<div class="space-y-6">

    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pengajuan Insentif Luaran Riset & HKI</h1>
            <p class="text-sm text-slate-500">Klaim insentif untuk publikasi ilmiah terverifikasi dan karya HKI bersertifikat.</p>
        </div>

        <a href="{{ route('dosen.incentives.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-md shadow-indigo-600/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Ajukan Insentif Baru</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Diajukan</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total_submitted'] }}</p>
                <p class="text-sm text-slate-400">Seluruh klaim luaran</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Sedang Diproses</p>
                <p class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $stats['total_pending'] }}</p>
                <p class="text-sm text-slate-400">Verifikasi LPPM / Keuangan</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Insentif Dicairkan</p>
                <p class="text-2xl font-extrabold text-emerald-600 mt-0.5">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</p>
                <p class="text-sm text-slate-400">Telah ditransfer ke rekening</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Incentives List Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-sm font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Luaran yang Diklaim</th>
                        <th class="py-3.5 px-4">Skema Tarif Insentif</th>
                        <th class="py-3.5 px-4">Nominal</th>
                        <th class="py-3.5 px-4">Rekening Penerima</th>
                        <th class="py-3.5 px-4 text-center">Status Pencairan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incentives as $index => $item)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-4 text-center text-slate-400 font-medium">
                            {{ $incentives->firstItem() + $index }}
                        </td>

                        <!-- Claimed Item -->
                        <td class="py-4 px-4 max-w-xs">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold uppercase mb-1 {{ $item->claimable_type === \App\Models\Publication::class ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $item->claimable_type === \App\Models\Publication::class ? 'Publikasi' : 'Karya HKI' }}
                            </span>
                            <p class="font-bold text-slate-900 leading-snug line-clamp-2">
                                {{ $item->claimable ? $item->claimable->title : '-' }}
                            </p>
                        </td>

                        <!-- Scheme -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <p class="font-semibold text-slate-800">{{ $item->scheme->name }}</p>
                            <span class="text-sm text-slate-400">Tahun: {{ $item->scheme->fiscal_year ?? date('Y') }}</span>
                        </td>

                        <!-- Amount -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <p class="font-extrabold text-slate-900">
                                Rp {{ number_format($item->approved_amount ?? $item->requested_amount, 0, ',', '.') }}
                            </p>
                            @if($item->approved_amount && $item->approved_amount !== $item->requested_amount)
                            <span class="text-[10px] sm:text-sm text-slate-400 line-through">
                                Rp {{ number_format($item->requested_amount, 0, ',', '.') }}
                            </span>
                            @endif
                        </td>

                        <!-- Bank Account -->
                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                            <p class="font-bold text-slate-800">{{ $item->bank_name }}</p>
                            <p class="text-slate-500 font-mono">{{ $item->bank_account_number }}</p>
                            <p class="text-slate-400">a.n. {{ $item->bank_account_holder }}</p>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @php
                                $statusStyles = [
                                    'submitted' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'verified' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'approved_finance' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'disbursed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $statusNames = [
                                    'submitted' => '1. Diajukan',
                                    'verified' => '2. Verifikasi LPPM',
                                    'approved_finance' => '3. Disetujui Keuangan',
                                    'disbursed' => '✓ Dana Dicairkan',
                                    'rejected' => '✕ Ditolak',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-semibold border {{ $statusStyles[$item->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusNames[$item->status] ?? $item->status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <a href="{{ route('dosen.incentives.show', $item) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                                <span>Rincian & Tracking</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-sm text-slate-400">
                            Belum ada pengajuan insentif yang dilakukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($incentives->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $incentives->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

