@extends('layouts.app', ['title' => 'Kelola Insentif', 'header' => 'Panel Pengelolaan & Pencairan Insentif'])

@section('content')
    <div class="space-y-6">

        <!-- Top Action & Title Header -->
        <div>
            <h1 class="text-xl font-bold text-slate-900">Kelola Pengajuan & Pencairan Insentif</h1>
            <p class="text-sm text-slate-500">Verifikasi kelayakan klaim luaran dosen, persetujuan nominal, dan pencairan
                dana insentif.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <a href="{{ route('admin.incentives.index', ['status' => 'all']) }}"
                class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between hover:bg-slate-50 transition">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Klaim Masuk</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total_claims'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.incentives.index', ['status' => 'submitted']) }}"
                class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between hover:bg-slate-50 transition">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Perlu Verifikasi</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $stats['pending'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('admin.incentives.index', ['status' => 'disbursed']) }}"
                class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between hover:bg-slate-50 transition">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Klaim Dicairkan</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $stats['disbursed_count'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </a>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Realisasi Dana</p>
                    <p class="text-xl font-extrabold text-purple-700 mt-0.5">Rp
                        {{ number_format($stats['disbursed_total'], 0, ',', '.') }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.incentives.index') }}"
                class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2 relative">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama dosen, nomor rekening, atau nama rekening..."
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2">
                    <select name="status"
                        class="flex-1 py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Semua Status</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>1. Diajukan
                        </option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>2. Terverifikasi
                            LPPM</option>
                        <option value="approved_finance" {{ request('status') === 'approved_finance' ? 'selected' : '' }}>
                            3. Disetujui Keuangan</option>
                        <option value="disbursed" {{ request('status') === 'disbursed' ? 'selected' : '' }}>4. Dana
                            Dicairkan</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <button type="submit"
                        class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Incentives Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-sm font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Dosen Pengusul</th>
                            <th class="py-3.5 px-4">Luaran yang Diklaim</th>
                            <th class="py-3.5 px-4">Skema Tarif</th>
                            <th class="py-3.5 px-4">Nominal</th>
                            <th class="py-3.5 px-4">Rekening Tujuan</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($incentives as $index => $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-4 text-center text-slate-400 font-medium">
                                    {{ $incentives->firstItem() + $index }}
                                </td>

                                <!-- Dosen -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                     <p class="font-bold text-slate-900">{{ $item->lecturer->user->name }}</p>
                                     <p class="text-sm text-slate-500">NIDN: {{ $item->lecturer->nidn ?? '-' }}</p>
                                 </td>

                                <!-- Output Item -->
                                <td class="py-4 px-4 max-w-xs">
                                     <span
                                         class="inline-block px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold uppercase mb-1 {{ $item->claimable_type === \App\Models\Publication::class ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                         {{ $item->claimable_type === \App\Models\Publication::class ? 'Publikasi' : 'Karya HKI' }}
                                     </span>
                                     <p class="font-bold text-slate-900 leading-snug line-clamp-2">
                                         {{ $item->claimable ? $item->claimable->title : '-' }}
                                     </p>
                                 </td>

                                <!-- Scheme -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                     <p class="font-semibold text-slate-800">{{ $item->scheme->name }}</p>
                                 </td>

                                <!-- Amount -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                     <p class="font-extrabold text-slate-900">
                                         Rp
                                         {{ number_format($item->approved_amount ?? $item->requested_amount, 0, ',', '.') }}
                                     </p>
                                 </td>

                                <!-- Bank -->
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
                                     <span
                                         class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-semibold border {{ $statusStyles[$item->status] ?? 'bg-slate-100 text-slate-700' }}">
                                         {{ $statusNames[$item->status] ?? $item->status }}
                                     </span>
                                 </td>

                                <!-- Action -->
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    @if(in_array($item->status, ['disbursed', 'rejected']))
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <!-- Tombol Proses Klaim Disabled (Data Sudah Selesai) -->
                                            <button type="button" disabled
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 font-semibold text-sm border border-slate-200 cursor-not-allowed select-none"
                                                title="Pengajuan insentif ini sudah selesai diproses ({{ $item->status === 'disbursed' ? 'Dana Dicairkan' : 'Klaim Ditolak' }})">
                                                @if($item->status === 'disbursed')
                                                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Selesai</span>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span>Ditolak</span>
                                                @endif
                                            </button>

                                            <!-- Tombol Lihat Detail -->
                                            <a href="{{ route('admin.incentives.show', $item) }}"
                                                title="Lihat Detail & Bukti Klaim"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Detail</span>
                                            </a>
                                        </div>
                                    @else
                                        <!-- Tombol Proses Klaim Aktif -->
                                        <a href="{{ route('admin.incentives.show', $item) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-xs shadow-indigo-600/20 transition">
                                            <span>Proses Klaim</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-sm text-slate-400">
                                    Tidak ada pengajuan insentif yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($incentives->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $incentives->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
