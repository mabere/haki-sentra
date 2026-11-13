@extends('layouts.app', ['title' => 'Log Verifikasi', 'header' => 'Audit Trail Riwayat Verifikasi'])

@section('content')
<div class="space-y-6">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Riwayat Log &amp; Audit Trail Verifikasi</h1>
            <p class="text-sm text-slate-500">Catatan riwayat persetujuan, permintaan revisi, dan penolakan oleh tim verifikator LPPM.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Download TXT -->
            <a href="{{ route('verification.logs.download', request()->only('q')) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-sm font-semibold shadow-2xs transition">
                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Download (.txt)</span>
            </a>

            <!-- Hapus Log (Khusus Admin LPPM) -->
            @if(auth()->user()->isAdminLppm() && $logs->total() > 0)
            <form method="POST" action="{{ route('verification.logs.destroy') }}"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan log verifikasi sesuai rentang yang dipilih? Tindakan ini tidak dapat dibatalkan.');"
                class="inline-flex items-center gap-2 bg-rose-50/70 border border-rose-200 rounded-xl p-1">
                @csrf
                @method('DELETE')
                <select name="older_than" aria-label="Pilih rentang log yang dihapus"
                    class="bg-white border border-rose-200 rounded-lg px-2.5 py-1.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <option value="30">&gt; 30 Hari</option>
                    <option value="90">&gt; 90 Hari</option>
                    <option value="365">&gt; 1 Tahun</option>
                    <option value="all">Semua Log</option>
                </select>
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold transition cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus Log</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Filter Pencarian Catatan -->
    <form method="GET" action="{{ route('verification.logs') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
        <div class="relative flex-1 max-w-md">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari catatan log verifikasi..."
                class="w-full pl-9 pr-3.5 py-2 bg-white border border-slate-300 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button type="submit"
            class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold transition cursor-pointer">
            Cari
        </button>
        @if(request()->filled('q'))
            <a href="{{ route('verification.logs') }}"
                class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold text-center transition">
                Reset
            </a>
        @endif
    </form>

    <!-- Log Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Verifikator / Aktor</th>
                        <th class="py-3.5 px-4">Jenis Luaran</th>
                        <th class="py-3.5 px-4">Judul Luaran Terkait</th>
                        <th class="py-3.5 px-4 text-center">Perubahan Status</th>
                        <th class="py-3.5 px-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $index => $log)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-4 px-4 text-center text-slate-400 font-medium">
                            {{ $logs->firstItem() + $index }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-slate-500">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap font-bold text-slate-900">
                            {{ $log->reviewer ? $log->reviewer->name : 'Sistem' }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($log->verifiable_type === \App\Models\Publication::class)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">Publikasi</span>
                            @elseif($log->verifiable_type === \App\Models\IntellectualProperty::class)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">HKI</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700">Insentif</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 max-w-xs font-medium text-slate-800 line-clamp-2">
                            {{ $log->verifiable ? ($log->verifiable->title ?? 'Pengajuan #' . $log->verifiable_id) : 'Objek telah dihapus' }}
                        </td>
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <span class="text-slate-400 font-mono text-[11px]">{{ $log->status_before }}</span>
                            <span class="text-slate-400 mx-1">&rarr;</span>
                            <span class="font-bold text-slate-900 font-mono text-[11px]">{{ $log->status_after }}</span>
                        </td>
                        <td class="py-4 px-4 max-w-xs text-slate-600 text-[11px]">
                            {{ $log->notes }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-sm text-slate-400">
                            Belum ada riwayat aktivitas verifikasi yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

