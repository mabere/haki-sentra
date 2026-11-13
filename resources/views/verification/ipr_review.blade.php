@extends('layouts.app', ['title' => 'Telaah HKI', 'header' => 'Lembar Verifikasi Hak Kekayaan Intelektual'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <a href="{{ route('verification.ipr.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Antrian Verifikasi HKI</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Telaah & Verifikasi Hak Kekayaan Intelektual</h1>
        </div>

        @if($intellectualProperty->verification_status === 'approved')
        <div class="flex items-center gap-2">
            <a href="{{ route('verification.ipr.certificate', $intellectualProperty) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-bold text-sm rounded-xl shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Surat Keterangan LPPM</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Pemohon / Dosen Pengusul Info Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="h-12 w-12 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                {{ $intellectualProperty->user->initials() }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="text-sm text-slate-400 font-semibold uppercase tracking-wider">Pemohon / Pengusul</p>
                    @if($intellectualProperty->user->lecturer)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">Dosen Internal</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700">Pemohon Publik</span>
                    @endif
                </div>
                <h3 class="text-sm font-bold text-slate-900 mt-0.5">
                    {{ $intellectualProperty->user->lecturer ? $intellectualProperty->user->lecturer->full_name : $intellectualProperty->user->name }}
                </h3>
                <p class="text-sm text-slate-500">
                    @if($intellectualProperty->user->lecturer)
                        NIDN: {{ $intellectualProperty->user->lecturer->nidn ?? '-' }} &bull; {{ $intellectualProperty->user->lecturer->department->name ?? '-' }} ({{ $intellectualProperty->user->lecturer->department->faculty->name ?? '-' }})
                    @else
                        Email: {{ $intellectualProperty->user->email }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Contact & Status Actions -->
        <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap justify-end">
            @if($intellectualProperty->user->phone)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $intellectualProperty->user->phone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    $waText = "Halo Bapak/Ibu " . ($intellectualProperty->user->name) . ", kami dari Sentra HKI LPPM terkait permohonan HKI: \"" . $intellectualProperty->title . "\" (No: " . ($intellectualProperty->application_number ?? '-') . ").";
                @endphp
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waText) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-sm border border-emerald-200 transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.879.814 2.801.814 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.586-5.766-5.773-5.766zm8.469 5.766c0 4.68-3.805 8.484-8.47 8.484-.002 0-.005 0-.007 0-1.42 0-2.812-.353-4.048-1.026l-4.575 1.2 1.222-4.46c-.742-1.289-1.134-2.756-1.134-4.202 0-4.68 3.805-8.484 8.47-8.484 4.664 0 8.472 3.804 8.472 8.488z"/></svg>
                    <span>Hubungi WA</span>
                </a>
            @endif

            <span class="inline-block px-2.5 py-1 rounded-full text-sm font-bold uppercase {{ $intellectualProperty->djki_status === 'granted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                DJKI: {{ strtoupper($intellectualProperty->djki_status) }}
            </span>
        </div>
    </div>

    <!-- IPR Core Details Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <div class="border-b border-slate-100 pb-3 flex flex-col sm:flex-row sm:items-start justify-between gap-2">
            <div>
                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200 mb-1">
                    {{ str_replace('_', ' ', $intellectualProperty->ipr_type) }}
                </span>
                <h2 class="text-base font-bold text-slate-900 leading-snug">
                    {{ $intellectualProperty->title }}
                </h2>
            </div>
            <div>
                @php
                    $vStatusBadge = match($intellectualProperty->verification_status) {
                        'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'revision' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold uppercase border {{ $vStatusBadge }}">
                    Verifikasi: {{ strtoupper($intellectualProperty->verification_status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-slate-400 font-semibold block">Sub-Jenis / Bidang Ciptaan</span>
                <span class="font-bold text-slate-800">{{ $intellectualProperty->sub_type ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Pemegang Hak Cipta / Paten</span>
                <span class="font-bold text-slate-800">{{ $intellectualProperty->rights_holder }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Nomor Permohonan DJKI</span>
                <span class="font-bold text-slate-800">{{ $intellectualProperty->application_number ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Tanggal Pengajuan</span>
                <span class="font-bold text-slate-800">
                    {{ $intellectualProperty->application_date ? $intellectualProperty->application_date->format('d F Y') : '-' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Nomor Pencatatan / Sertifikat</span>
                <span class="font-bold text-slate-800 {{ $intellectualProperty->registration_number ? 'text-emerald-700' : '' }}">
                    {{ $intellectualProperty->registration_number ?? '-' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 font-semibold block">Tanggal Terbit Sertifikat</span>
                <span class="font-bold text-slate-800">
                    {{ $intellectualProperty->grant_date ? $intellectualProperty->grant_date->format('d F Y') : '-' }}
                </span>
            </div>
        </div>

        @if($intellectualProperty->verification_note)
        <div class="mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
            <span class="font-bold text-slate-700 block mb-0.5">Catatan / Keterangan Terakhir:</span>
            <p class="text-slate-600 italic">{{ $intellectualProperty->verification_note }}</p>
        </div>
        @endif
    </div>

    <!-- Inventors Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Tim Inventor / Pencipta</h3>
            <span class="text-sm text-slate-500">{{ $intellectualProperty->inventors->count() }} Inventor Terdaftar</span>
        </div>
        <table class="w-full text-left text-sm text-slate-700">
            <thead class="bg-slate-50 text-[11px] font-semibold uppercase text-slate-500">
                <tr>
                    <th class="py-2.5 px-4 w-12 text-center">Urutan</th>
                    <th class="py-2.5 px-4">Nama Inventor</th>
                    <th class="py-2.5 px-4">Peran Tim</th>
                    <th class="py-2.5 px-4 text-center">Kontribusi (%)</th>
                    <th class="py-2.5 px-4">Kategori Afiliasi</th>
                    <th class="py-2.5 px-4">Institusi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($intellectualProperty->inventors as $inv)
                <tr>
                    <td class="py-2.5 px-4 text-center font-bold text-slate-400">#{{ $inv->inventor_order }}</td>
                    <td class="py-2.5 px-4 font-semibold text-slate-900">{{ $inv->inventor_name }}</td>
                    <td class="py-2.5 px-4">
                        @if($inv->role === 'inventor_utama')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">Inventor Utama</span>
                        @else
                            <span class="text-slate-500">Anggota</span>
                        @endif
                    </td>
                    <td class="py-2.5 px-4 text-center font-bold text-slate-800">
                        {{ $inv->contribution_percentage ? $inv->contribution_percentage . '%' : '-' }}
                    </td>
                    <td class="py-2.5 px-4">
                        @if($inv->inventor_type === 'internal_lecturer')
                            <span class="text-emerald-700 font-medium">Dosen Internal</span>
                        @elseif($inv->inventor_type === 'student')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">Mahasiswa</span>
                        @else
                            <span class="text-slate-600">Eksternal</span>
                        @endif
                    </td>
                    <td class="py-2.5 px-4 text-slate-600">{{ $inv->affiliation ?? 'Universitas' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Certificate & Document Attachments Check -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Sertifikat DJKI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                    PDF
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Sertifikat Resmi DJKI</p>
                    <p class="text-[11px] text-slate-500">Kemenkumham RI</p>
                </div>
            </div>

            @if($intellectualProperty->certificate_file)
            <a href="{{ route('verification.ipr.file', [$intellectualProperty, 'certificate']) }}" target="_blank"
               class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition">
                Unduh PDF &rarr;
            </a>
            @else
            <span class="text-[11px] text-amber-600 font-semibold italic">Belum diunggah</span>
            @endif
        </div>

        <!-- Deskripsi / Berkas Pendukung -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                    DOC
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Deskripsi / Uraian Ciptaan</p>
                    <p class="text-[11px] text-slate-500">Dokumen pendukung usulan</p>
                </div>
            </div>

            @if($intellectualProperty->description_file)
            <a href="{{ route('verification.ipr.file', [$intellectualProperty, 'description']) }}" target="_blank"
               class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition">
                Unduh PDF &rarr;
            </a>
            @else
            <span class="text-[11px] text-slate-400 font-semibold italic">Tidak ada lampiran</span>
            @endif
        </div>
    </div>

    <!-- Decision & DJKI Update Form (Ketua LPPM / Verifikator) -->
    <div class="bg-white rounded-2xl border-2 border-amber-300 p-6 shadow-md space-y-5" x-data="{ decision: 'approve' }">
        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
            <span class="h-3 w-3 rounded-full bg-amber-500"></span>
            <h3 class="text-sm font-bold text-slate-900">Keputusan Validasi Verifikator / Ketua LPPM</h3>
        </div>

        <form method="POST" action="{{ route('verification.ipr.verify', $intellectualProperty) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Choice Radio Buttons -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tentukan Status Keputusan Verifikasi:</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                           :class="decision === 'approve' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                        <input type="radio" name="action" value="approve" x-model="decision" class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <p class="text-sm font-bold">1. Setujui (Approve)</p>
                            <p class="text-[10px] text-slate-500">Data valid & siap diproses LPPM/DJKI</p>
                        </div>
                    </label>

                    <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                           :class="decision === 'revision' ? 'border-amber-500 bg-amber-50/50 text-amber-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                        <input type="radio" name="action" value="revision" x-model="decision" class="text-amber-600 focus:ring-amber-500">
                        <div>
                            <p class="text-sm font-bold">2. Minta Revisi</p>
                            <p class="text-[10px] text-slate-500">Perlu perbaikan berkas/identitas</p>
                        </div>
                    </label>

                    <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                           :class="decision === 'reject' ? 'border-rose-500 bg-rose-50/50 text-rose-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                        <input type="radio" name="action" value="reject" x-model="decision" class="text-rose-600 focus:ring-rose-500">
                        <div>
                            <p class="text-sm font-bold">3. Tolak HKI</p>
                            <p class="text-[10px] text-slate-500">Tidak memenuhi ketentuan LPPM</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- DJKI Status & Registration Panel (Terbuka untuk update status proses) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Pembaruan Progres DJKI & Sertifikat (Kemenkumham)</span>
                    </h4>
                    <span class="text-[10px] text-slate-500 italic">Dapat diperbarui sewaktu-waktu oleh LPPM</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Status Resmi DJKI -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Status Resmi DJKI</label>
                        <select name="djki_status" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500">
                            <option value="draft" {{ old('djki_status', $intellectualProperty->djki_status) === 'draft' ? 'selected' : '' }}>Draft (Konsep)</option>
                            <option value="diajukan" {{ old('djki_status', $intellectualProperty->djki_status) === 'diajukan' ? 'selected' : '' }}>Diajukan ke DJKI</option>
                            <option value="pemeriksaan" {{ old('djki_status', $intellectualProperty->djki_status) === 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan Substantif</option>
                            <option value="granted" {{ old('djki_status', $intellectualProperty->djki_status) === 'granted' ? 'selected' : '' }}>Granted (Sertifikat Terbit)</option>
                            <option value="ditolak" {{ old('djki_status', $intellectualProperty->djki_status) === 'ditolak' ? 'selected' : '' }}>Ditolak DJKI</option>
                        </select>
                        @error('djki_status') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nomor Pencatatan / Sertifikat -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">No. Pencatatan / Sertifikat</label>
                        <input type="text" name="registration_number"
                               value="{{ old('registration_number', $intellectualProperty->registration_number) }}"
                               placeholder="Contoh: 000492104"
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500">
                        @error('registration_number') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Terbit Sertifikat -->
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tanggal Terbit Sertifikat</label>
                        <input type="date" name="grant_date"
                               value="{{ old('grant_date', $intellectualProperty->grant_date ? $intellectualProperty->grant_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500">
                        @error('grant_date') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Upload Sertifikat PDF Baru -->
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                        Unggah / Ganti Berkas Sertifikat Resmi DJKI (PDF, Max 10MB)
                    </label>
                    <input type="file" name="certificate_file" accept=".pdf"
                           class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1">Jika diunggah, file sertifikat resmi ini akan menggantikan file sebelumnya dan dapat diunduh pemohon.</p>
                    @error('certificate_file') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Notes Textarea -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Catatan Verifikator / Alasan Revisi / Rekomendasi
                    <span x-show="decision !== 'approve'" class="text-rose-500">* (Wajib diisi jika revisi/tolak)</span>
                </label>
                <textarea name="notes"
                          rows="3"
                          placeholder="Tuliskan catatan perbaikan data/berkas, atau rekomendasi pendaftaran ke DJKI..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition">{{ old('notes', $intellectualProperty->verification_note) }}</textarea>
                @error('notes') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md transition cursor-pointer"
                        :class="decision === 'approve' ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30' : (decision === 'revision' ? 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/30' : 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/30')">
                    Kirim Keputusan & Perbarui Status HKI
                </button>
            </div>
        </form>
    </div>

    <!-- Verification Audit Log Timeline -->
    @if($intellectualProperty->verificationLogs && $intellectualProperty->verificationLogs->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Riwayat Telaah & Keputusan Verifikasi</span>
        </h3>

        <div class="space-y-3 divide-y divide-slate-100">
            @foreach($intellectualProperty->verificationLogs as $log)
            <div class="pt-3 first:pt-0 flex items-start justify-between gap-3 text-sm">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900">{{ $log->reviewer->name ?? 'Sistem' }}</span>
                        <span class="text-slate-400">&bull;</span>
                        <span class="text-slate-500">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mt-1 flex items-center gap-2 text-[11px]">
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium uppercase">{{ $log->status_before }}</span>
                        <span class="text-slate-400">&rarr;</span>
                        <span class="px-2 py-0.5 rounded font-bold uppercase {{ $log->status_after === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($log->status_after === 'revision' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ $log->status_after }}
                        </span>
                    </div>
                    <p class="text-slate-600 mt-1 italic">{{ $log->notes }}</p>
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
