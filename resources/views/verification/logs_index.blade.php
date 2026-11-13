@extends('layouts.app', ['title' => 'Log Verifikasi', 'header' => 'Audit Trail Riwayat Verifikasi'])

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-slate-900">Riwayat Log & Audit Trail Verifikasi</h1>
        <p class="text-sm text-slate-500">Catatan riwayat persetujuan, permintaan revisi, dan penolakan oleh tim verifikator LPPM.</p>
    </div>

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

