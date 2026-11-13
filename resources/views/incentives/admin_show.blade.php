@extends('layouts.app', ['title' => 'Proses Insentif', 'header' => 'Verifikasi & Pencairan Insentif'])

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header Navigation -->
        <div>
            <a href="{{ route('admin.incentives.index') }}"
                class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar Insentif</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Proses Verifikasi & Pencairan Insentif</h1>
        </div>

        <!-- Dosen Claimant Card -->
        <div
            class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="h-12 w-12 rounded-full bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-sm">
                    {{ $incentiveRequest->lecturer->user->initials() }}
                </div>
                <div>
                    <p class="text-sm text-slate-400 font-semibold uppercase tracking-wider">Dosen Pengusul</p>
                    <h3 class="text-sm font-bold text-slate-900">{{ $incentiveRequest->lecturer->full_name }}</h3>
                    <p class="text-sm text-slate-500">
                        NIDN: {{ $incentiveRequest->lecturer->nidn ?? '-' }} &bull;
                        {{ $incentiveRequest->lecturer->department->name ?? '-' }}
                        ({{ $incentiveRequest->lecturer->department->faculty->code ?? '' }})
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="text-sm text-slate-400 font-semibold uppercase tracking-wider block">Status Klaim Saat
                    Ini</span>
                <span
                    class="inline-block mt-0.5 px-3 py-1 rounded-full text-sm font-bold uppercase {{ $incentiveRequest->status === 'disbursed' ? 'bg-emerald-100 text-emerald-800' : ($incentiveRequest->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                    {{ str_replace('_', ' ', $incentiveRequest->status) }}
                </span>
            </div>
        </div>

        <!-- Output & Scheme Details -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider block">Skema Insentif</span>
                    <h2 class="text-base font-bold text-slate-900">{{ $incentiveRequest->scheme->name }}</h2>
                </div>
                <div class="text-right">
                    <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider block">Nominal
                        Diajukan</span>
                    <p class="text-2xl font-extrabold text-indigo-700">
                        Rp {{ number_format($incentiveRequest->requested_amount, 0, ',', '.') }}
                    </p>
                    @if ($incentiveRequest->approved_amount)
                        <p class="text-sm font-bold text-emerald-600 mt-0.5">
                            Disetujui: Rp {{ number_format($incentiveRequest->approved_amount, 0, ',', '.') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                <!-- Luaran -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Luaran yang Diklaim</span>
                    <p class="font-bold text-slate-900 leading-snug">
                        {{ $incentiveRequest->claimable ? $incentiveRequest->claimable->title : '-' }}
                    </p>
                    <div class="text-slate-500 pt-1">
                        Jenis: <span
                            class="font-bold text-slate-700 capitalize">{{ $incentiveRequest->claimable_type === \App\Models\Publication::class ? 'Publikasi Ilmiah' : 'Karya HKI' }}</span>
                    </div>
                </div>

                <!-- Rekening Bank -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Rekening Bank
                        Penerima</span>
                    <p class="font-bold text-slate-900 text-sm">{{ $incentiveRequest->bank_name }}</p>
                    <p class="font-mono text-slate-700 text-sm">No: {{ $incentiveRequest->bank_account_number }}</p>
                    <p class="text-slate-500">a.n. {{ $incentiveRequest->bank_account_holder }}</p>
                </div>

            </div>
        </div>

        <!-- Status Selesai / Processing Form Card -->
        @if (in_array($incentiveRequest->status, ['disbursed', 'rejected']))
            @if ($incentiveRequest->status === 'disbursed')
                <!-- Card Selesai Dicairkan -->
                <div class="bg-white rounded-2xl border border-emerald-200 bg-emerald-50/20 p-6 sm:p-7 shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-4 border-b border-emerald-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Klaim Telah Selesai Diproses (Dana Dicairkan)</h3>
                                <p class="text-sm text-slate-500">Dana insentif luaran telah ditransfer ke rekening dosen yang bersangkutan.</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800 shrink-0">
                            ✓ Selesai Dicairkan
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm pt-1">
                        <div class="p-3.5 rounded-xl bg-white border border-emerald-100 space-y-1">
                            <span class="text-slate-400 font-semibold text-sm block">Nominal Realisasi</span>
                            <p class="text-base font-extrabold text-emerald-700">Rp {{ number_format($incentiveRequest->approved_amount ?? $incentiveRequest->requested_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white border border-emerald-100 space-y-1">
                            <span class="text-slate-400 font-semibold text-sm block">Tanggal Transfer</span>
                            <p class="text-sm font-bold text-slate-800">{{ $incentiveRequest->disbursed_at ? \Carbon\Carbon::parse($incentiveRequest->disbursed_at)->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-white border border-emerald-100 space-y-1">
                            <span class="text-slate-400 font-semibold text-sm block">Bukti Transfer Bank</span>
                            @if ($incentiveRequest->transfer_proof_file)
                                <a href="{{ route('admin.incentives.file', [$incentiveRequest, 'proof']) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800 pt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Unduh Bukti Transfer</span>
                                </a>
                            @else
                                <span class="text-sm text-slate-400 italic pt-1 block">Tidak ada lampiran bukti</span>
                            @endif
                        </div>
                    </div>

                    @if ($incentiveRequest->notes)
                        <div class="p-3.5 rounded-xl bg-white border border-emerald-100">
                            <span class="text-sm font-semibold text-slate-400 block mb-1">Catatan Pencairan:</span>
                            <p class="text-sm text-slate-700">{{ $incentiveRequest->notes }}</p>
                        </div>
                    @endif
                </div>
            @else
                <!-- Card Selesai (Ditolak) -->
                <div class="bg-white rounded-2xl border border-rose-200 bg-rose-50/20 p-6 sm:p-7 shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-4 border-b border-rose-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Klaim Insentif Ditolak</h3>
                                <p class="text-sm text-slate-500">Pengajuan insentif tidak memenuhi kriteria dan telah ditolak.</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-rose-100 text-rose-800 shrink-0">
                            ✕ Ditolak
                        </span>
                    </div>

                    @if ($incentiveRequest->notes)
                        <div class="p-4 rounded-xl bg-white border border-rose-200 space-y-1">
                            <span class="text-sm font-bold text-rose-700 uppercase tracking-wider block">Alasan Penolakan:</span>
                            <p class="text-sm text-slate-800 font-medium">{{ $incentiveRequest->notes }}</p>
                        </div>
                    @endif
                </div>
            @endif
        @else
            <!-- Admin Processing Form Card (Hanya Tampil Jika Belum Selesai) -->
            <div class="bg-white rounded-2xl border-2 border-indigo-300 p-6 shadow-md space-y-5" x-data="{ actionType: '{{ $incentiveRequest->status === 'submitted' ? 'verify' : ($incentiveRequest->status === 'verified' ? 'approve_finance' : 'disburse') }}' }">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                    <span class="h-3 w-3 rounded-full bg-indigo-600"></span>
                    <h3 class="text-sm font-bold text-slate-900">Tindakan Alur Persetujuan & Pencairan Dana</h3>
                </div>

                <form method="POST" action="{{ route('admin.incentives.process', $incentiveRequest) }}"
                    enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Choice Action Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2.5">
                        <label class="p-3 rounded-xl border-2 cursor-pointer transition flex items-center gap-2.5"
                            :class="actionType === 'verify' ? 'border-blue-500 bg-blue-50/50 text-blue-900' :
                                'border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="action" value="verify" x-model="actionType"
                                class="text-blue-600 focus:ring-blue-500">
                            <div>
                                <p class="text-sm font-bold">1. Verifikasi</p>
                                <p class="text-[10px] text-slate-500">Administrasi valid</p>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border-2 cursor-pointer transition flex items-center gap-2.5"
                            :class="actionType === 'approve_finance' ? 'border-amber-500 bg-amber-50/50 text-amber-900' :
                                'border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="action" value="approve_finance" x-model="actionType"
                                class="text-amber-600 focus:ring-amber-500">
                            <div>
                                <p class="text-sm font-bold">2. Setujui Biaya</p>
                                <p class="text-[10px] text-slate-500">Bagian Keuangan</p>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border-2 cursor-pointer transition flex items-center gap-2.5"
                            :class="actionType === 'disburse' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900' :
                                'border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="action" value="disburse" x-model="actionType"
                                class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="text-sm font-bold">3. Cairkan Dana</p>
                                <p class="text-[10px] text-slate-500">Transfer selesai</p>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border-2 cursor-pointer transition flex items-center gap-2.5"
                            :class="actionType === 'reject' ? 'border-rose-500 bg-rose-50/50 text-rose-900' :
                                'border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="action" value="reject" x-model="actionType"
                                class="text-rose-600 focus:ring-rose-500">
                            <div>
                                <p class="text-sm font-bold">Tolak Klaim</p>
                                <p class="text-[10px] text-slate-500">Tidak sesuai syarat</p>
                            </div>
                        </label>
                    </div>

                    <!-- Conditional Input for Finance Approval: Nominal Disetujui -->
                    <div x-show="actionType === 'approve_finance'"
                        class="p-4 rounded-xl bg-amber-50/50 border border-amber-200 space-y-2">
                        <label class="block text-sm font-semibold text-amber-900">
                            Nominal Insentif yang Disetujui (Rupiah)
                        </label>
                        <input type="number" name="approved_amount"
                            value="{{ old('approved_amount', $incentiveRequest->approved_amount ?? $incentiveRequest->requested_amount) }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 font-bold">
                        <p class="text-[11px] text-amber-700">Nominal dapat disesuaikan jika terdapat pemotongan pajak atau
                            regulasi internal kampus.</p>
                    </div>

                    <!-- Conditional Input for Disbursement: Tanggal & Bukti Transfer -->
                    <div x-show="actionType === 'disburse'"
                        class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-200 space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-emerald-900 mb-1">Tanggal Transfer
                                    Pencairan</label>
                                <input type="date" name="disbursed_at" value="{{ old('disbursed_at', date('Y-m-d')) }}"
                                    class="w-full px-3.5 py-2 bg-white border border-emerald-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-emerald-900 mb-1">Unggah Bukti Transfer (PDF /
                                    Gambar)</label>
                                <input type="file" name="transfer_proof" accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:text-white cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Notes Textarea -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Catatan / Keterangan Tambahan
                            <span x-show="actionType === 'reject'" class="text-rose-500">* (Wajib diisi jika ditolak)</span>
                        </label>
                        <textarea name="notes" rows="3" placeholder="Masukkan catatan proses atau alasan penolakan..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('notes', $incentiveRequest->notes) }}</textarea>
                        @error('notes')
                            <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-1">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
                            Simpan & Perbarui Status Insentif
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Verification Timeline / Logs -->
        @if ($incentiveRequest->verificationLogs->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Riwayat Log Proses Insentif
                </h3>
                <div class="space-y-3">
                    @foreach ($incentiveRequest->verificationLogs as $log)
                        <div class="flex items-start gap-3 text-sm">
                            <span class="w-2 h-2 rounded-full bg-purple-500 mt-1.5 shrink-0"></span>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="font-bold text-slate-800">{{ $log->reviewer ? $log->reviewer->name : 'Sistem' }}</span>
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
        <x-revision-thread :target="$incentiveRequest" type="incentive" />

    </div>
@endsection
