@extends('layouts.app', ['title' => 'Ajukan Insentif', 'header' => 'Formulir Pengajuan Insentif Luaran'])

@section('content')
    <div class="max-w-3xl mx-auto space-y-6" x-data="incentiveForm()">

        <!-- Header Navigation -->
        <div>
            <a href="{{ route('dosen.incentives.index') }}"
                class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar Insentif</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Pengajuan Insentif Luaran Riset & HKI</h1>
        </div>

        <!-- Main Form -->
        <form method="POST" action="{{ route('dosen.incentives.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Pemilihan Luaran yang Diklaim -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">1. Pilih Luaran yang Memenuhi
                    Syarat</h2>

                <!-- Kategori Luaran -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Kategori Luaran <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                            :class="category === 'publication' ? 'border-indigo-600 bg-indigo-50/50 text-indigo-900' :
                                'border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="category_radio" value="publication" x-model="category"
                                @change="onCategoryChange()" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <p class="text-sm font-bold">Publikasi Ilmiah</p>
                                <p class="text-[10px] text-slate-500">Jurnal Scopus / SINTA terverifikasi</p>
                            </div>
                        </label>

                        <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                            :class="category === 'ipr' ? 'border-emerald-600 bg-emerald-50/50 text-emerald-900' :
                                'border-slate-200 hover:bg-slate-50 text-slate-700'">
                            <input type="radio" name="category_radio" value="ipr" x-model="category"
                                @change="onCategoryChange()" class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="text-sm font-bold">Karya HKI</p>
                                <p class="text-[10px] text-slate-500">Paten / Hak Cipta Granted</p>
                            </div>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="claimable_type"
                    :value="category === 'publication' ? '{{ addslashes(\App\Models\Publication::class) }}' :
                        '{{ addslashes(\App\Models\IntellectualProperty::class) }}'">

                <!-- Pilihan Judul Luaran (Publikasi) -->
                <div x-show="category === 'publication'">
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Pilih Artikel Publikasi Terverifikasi <span class="text-rose-500">*</span>
                    </label>
                    @if ($availablePublications->isEmpty())
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-800">
                            Belum ada publikasi terverifikasi yang tersedia untuk diajukan insentif. Pastikan publikasi Anda
                            sudah berstatus <strong>Disetujui (Approved)</strong> oleh verifikator LPPM.
                        </div>
                    @else
                        <select name="claimable_id" x-model="selectedClaimableId" :required="category === 'publication'"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">-- Pilih Artikel Publikasi --</option>
                            @foreach ($availablePublications as $pub)
                                <option value="{{ $pub->id }}">
                                    [{{ $pub->quartile ? 'Scopus ' . $pub->quartile : $pub->sinta_grade ?? 'Jurnal' }}]
                                    {{ $pub->title }} ({{ $pub->publication_year }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- Pilihan Judul Luaran (HKI) -->
                <div x-show="category === 'ipr'" style="display: none;">
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Pilih Karya HKI Bersertifikat (Granted) <span class="text-rose-500">*</span>
                    </label>
                    @if ($availableIpr->isEmpty())
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-800">
                            Belum ada karya HKI berstatus <strong>Granted & Disetujui</strong> yang tersedia untuk diajukan
                            insentif.
                        </div>
                    @else
                        <select name="claimable_id" x-model="selectedClaimableId" :required="category === 'ipr'"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="">-- Pilih Karya HKI --</option>
                            @foreach ($availableIpr as $ipr)
                                <option value="{{ $ipr->id }}">
                                    [{{ strtoupper(str_replace('_', ' ', $ipr->ipr_type)) }}] {{ $ipr->title }} (No:
                                    {{ $ipr->registration_number ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                @error('claimable_id')
                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                @enderror

                <!-- Pilihan Skema Tarif Insentif -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Skema Tarif Insentif LPPM <span class="text-rose-500">*</span>
                    </label>
                    <select name="incentive_scheme_id" x-model="selectedSchemeId" @change="onSchemeChange()" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        <option value="">-- Pilih Skema Tarif Insentif --</option>
                        <template x-for="s in filteredSchemes" :key="s.id">
                            <option :value="s.id"
                                x-text="`${s.name} - Rp ${Number(s.amount).toLocaleString('id-ID')}`"></option>
                        </template>
                    </select>
                </div>

                <!-- Preview Nominal Banner -->
                <div x-show="selectedAmount > 0"
                    class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-200 flex items-center justify-between">
                    <div>
                        <span class="text-sm text-slate-500">Estimasi Nominal Insentif:</span>
                        <p class="text-lg font-extrabold text-indigo-700"
                            x-text="`Rp ${Number(selectedAmount).toLocaleString('id-ID')}`"></p>
                    </div>
                    <span class="text-[11px] text-indigo-600 font-medium">Berdasarkan Tarif LPPM TA
                        {{ date('Y') }}</span>
                </div>

            </div>

            <!-- Section 2: Data Rekening Bank Penerima -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Rekening Bank Penerima
                    Insentif</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Bank <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_name" required
                            value="{{ old('bank_name', $lastIncentive->bank_name ?? 'Bank Mandiri') }}"
                            placeholder="Contoh: Bank Mandiri, BNI, BCA..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nomor Rekening <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_account_number" required
                            value="{{ old('bank_account_number', $lastIncentive->bank_account_number ?? '') }}"
                            placeholder="Contoh: 1370012345678"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Pemilik Rekening <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bank_account_holder" required
                            value="{{ old('bank_account_holder', $lastIncentive->bank_account_holder ?? auth()->user()->name) }}"
                            placeholder="Nama sesuai buku tabungan"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Berkas Pernyataan -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">3. Dokumen Pernyataan /
                    Formulir SPJ (Opsional)</h2>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Unggah Surat Permohonan / SPJ (PDF)
                    </label>
                    <input type="file" name="statement_letter" accept=".pdf"
                        class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Format PDF, maksimal ukuran 10 MB.</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('dosen.incentives.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
                    Kirim Pengajuan Insentif
                </button>
            </div>

        </form>
    </div>

    <script>
        function incentiveForm() {
            const allSchemes = @json($schemes);

            return {
                category: 'publication',
                selectedClaimableId: '',
                selectedSchemeId: '',
                selectedAmount: 0,
                get filteredSchemes() {
                    return allSchemes.filter(s => s.category === this.category);
                },
                onCategoryChange() {
                    this.selectedClaimableId = '';
                    this.selectedSchemeId = '';
                    this.selectedAmount = 0;
                },
                onSchemeChange() {
                    const found = allSchemes.find(s => s.id == this.selectedSchemeId);
                    this.selectedAmount = found ? found.amount : 0;
                }
            };
        }
    </script>
@endsection
