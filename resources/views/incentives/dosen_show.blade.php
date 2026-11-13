@extends('layouts.app', ['title' => 'Detail Insentif', 'header' => 'Rincian Pengajuan Insentif Luaran'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div>
        <a href="{{ route('dosen.incentives.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Insentif</span>
        </a>
        <h1 class="text-xl font-bold text-slate-900 mt-1">Status & Rincian Pengajuan Insentif</h1>
    </div>

    <!-- Stepper Tracker Alur Pencairan Dana -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Proses Verifikasi & Pencairan Dana</h3>

        @php
            $steps = [
                'submitted' => ['num' => 1, 'label' => 'Pengajuan Masuk', 'desc' => 'Dosen mengirim berkas'],
                'verified' => ['num' => 2, 'label' => 'Verifikasi LPPM', 'desc' => 'Administrasi valid'],
                'approved_finance' => ['num' => 3, 'label' => 'Persetujuan Keuangan', 'desc' => 'Nominal disetujui'],
                'disbursed' => ['num' => 4, 'label' => 'Pencairan Selesai', 'desc' => 'Dana ditransfer'],
            ];

            $orderMap = ['submitted' => 1, 'verified' => 2, 'approved_finance' => 3, 'disbursed' => 4, 'rejected' => 0];
            $currentStepNum = $orderMap[$incentiveRequest->status] ?? 1;
        @endphp

        @if($incentiveRequest->status === 'rejected')
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <div>
                <p class="font-bold">Pengajuan Insentif Ditolak</p>
                <p class="mt-0.5">{{ $incentiveRequest->notes ?? 'Tidak memenuhi syarat pencairan insentif.' }}</p>
            </div>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 relative">
            @foreach($steps as $key => $s)
            @php
                $isDone = $currentStepNum >= $s['num'];
                $isCurrent = $currentStepNum === $s['num'];
            @endphp
            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                <div class="h-10 w-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 transition {{ $isDone ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                    @if($isDone && !$isCurrent)
                        ✓
                    @else
                        {{ $s['num'] }}
                    @endif
                </div>
                <div>
                    <p class="text-sm font-bold {{ $isDone ? 'text-slate-900' : 'text-slate-400' }}">{{ $s['label'] }}</p>
                    <p class="text-[11px] text-slate-400">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Claim & Financial Info Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider block">Skema Insentif</span>
                <h2 class="text-lg font-bold text-slate-900">{{ $incentiveRequest->scheme->name }}</h2>
            </div>
            <div class="text-right">
                <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider block">Nominal Insentif</span>
                <p class="text-2xl font-extrabold text-indigo-700">
                    Rp {{ number_format($incentiveRequest->approved_amount ?? $incentiveRequest->requested_amount, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">

            <!-- Luaran yang Diklaim -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Luaran yang Diklaim</span>
                <p class="font-bold text-slate-900 text-sm leading-snug">
                    {{ $incentiveRequest->claimable ? $incentiveRequest->claimable->title : '-' }}
                </p>
                <div class="text-slate-500">
                    Kategori:
                    <span class="font-bold text-slate-700 capitalize">
                        {{ $incentiveRequest->claimable_type === \App\Models\Publication::class ? 'Publikasi Ilmiah' : 'Karya HKI' }}
                    </span>
                </div>
            </div>

            <!-- Rekening Bank Tujuan -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Rekening Bank Tujuan</span>
                <p class="font-bold text-slate-900 text-sm leading-snug">
                    {{ $incentiveRequest->bank_name }}
                </p>
                <p class="font-mono text-slate-700 text-sm">
                    No: {{ $incentiveRequest->bank_account_number }}
                </p>
                <p class="text-slate-500 text-[11px]">
                    a.n. {{ $incentiveRequest->bank_account_holder }}
                </p>
            </div>

        </div>

        @if($incentiveRequest->status === 'disbursed' && $incentiveRequest->transfer_proof_file)
        <!-- Bukti Transfer Cair -->
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                    ✓
                </div>
                <div>
                    <p class="text-sm font-bold text-emerald-900">Bukti Transfer Pencairan Tersedia</p>
                    <p class="text-[11px] text-emerald-700">Dicairkan pada: {{ $incentiveRequest->disbursed_at ? \Carbon\Carbon::parse($incentiveRequest->disbursed_at)->format('d F Y') : '-' }}</p>
                </div>
            </div>

            <a href="{{ route('dosen.incentives.file', [$incentiveRequest, 'proof']) }}" target="_blank"
               class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm transition">
                Unduh Bukti Transfer &rarr;
            </a>
        </div>
        @endif

        <!-- Dialog & Catatan Revisi Interaktif Verifikator <-> Dosen -->
        <x-revision-thread :target="$incentiveRequest" type="incentive" />

    </div>

</div>
@endsection

