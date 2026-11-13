@extends('layouts.app', ['title' => 'Daftarkan Karya HKI', 'header' => 'Formulir Pendaftaran HKI'])

@section('content')
    <div class="max-w-4xl mx-auto space-y-6" x-data="iprForm()">

        <!-- Header Navigation -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('dosen.ipr.index') }}"
                    class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar Karya HKI</span>
                </a>
                <h1 class="text-xl font-bold text-slate-900 mt-1">Daftarkan Karya Hak Kekayaan Intelektual (HKI)</h1>
            </div>
        </div>

        <!-- Main IPR Form -->
        <form method="POST" action="{{ route('dosen.ipr.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Pokok Ciptaan / Invensi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">1. Informasi Pokok Ciptaan /
                    Invensi</h2>

                <!-- Judul Ciptaan -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Judul Ciptaan / Invensi / Merek <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="title" rows="3" required placeholder="Masukkan judul ciptaan atau invensi secara lengkap..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">{{ old('title') }}</textarea>
                    @error('title')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <!-- Jenis HKI -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Jenis HKI <span class="text-rose-500">*</span>
                        </label>
                        <select name="ipr_type" x-model="iprType" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="hak_cipta">Hak Cipta</option>
                            <option value="paten">Paten Biasa</option>
                            <option value="paten_sederhana">Paten Sederhana</option>
                            <option value="merek">Merek Dagang</option>
                            <option value="desain_industri">Desain Industri</option>
                            <option value="rahasia_dagang">Rahasia Dagang</option>
                            <option value="dtlst">Desain Tata Letak Sirkuit Terpadu (DTLST)</option>
                        </select>
                    </div>

                    <!-- Sub-jenis Ciptaan -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Sub-Jenis / Bidang
                        </label>
                        <input type="text" name="sub_type" value="{{ old('sub_type') }}"
                            placeholder="Contoh: Program Komputer, Karya Tulis, Modul..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Pemegang Hak -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Pemegang Hak Cipta/Paten <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="rights_holder" required
                            value="{{ old('rights_holder', 'Universitas') }}" placeholder="Universitas / Individu / Lembaga"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                </div>
            </div>

            <!-- Section 2: Pendaftaran & Status DJKI -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Status Pendaftaran DJKI
                    Kemenkumham</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <!-- Status DJKI -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Status Permohonan DJKI <span class="text-rose-500">*</span>
                        </label>
                        <select name="djki_status" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                            <option value="draft">Draft (Belum Diajukan)</option>
                            <option value="diajukan">Diajukan ke DJKI</option>
                            <option value="pemeriksaan_formal">Pemeriksaan Formal</option>
                            <option value="pemeriksaan_substantif">Pemeriksaan Substantif</option>
                            <option value="granted" selected>Diberi (Granted / Sertifikat Terbit)</option>
                            <option value="ditolak">Ditolak / Ditarik Kembali</option>
                        </select>
                    </div>

                    <!-- Nomor Permohonan -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nomor Permohonan DJKI
                        </label>
                        <input type="text" name="application_number" value="{{ old('application_number') }}"
                            placeholder="Contoh: EC00202512345"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Tanggal Permohonan -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Tanggal Pengajuan
                        </label>
                        <input type="date" name="application_date" value="{{ old('application_date') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Nomor Pencatatan / Pendaftaran -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nomor Pencatatan / Sertifikat
                        </label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}"
                            placeholder="Contoh: 000789123"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <!-- Tanggal Terbit Sertifikat -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Tanggal Terbit Sertifikat
                        </label>
                        <input type="date" name="grant_date" value="{{ old('grant_date') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                </div>
            </div>

            <!-- Section 3: Tim Inventor / Pencipta (Repeater Dinamis) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">3. Tim Inventor / Pencipta</h2>
                        <p class="text-sm text-slate-500">Tentukan susunan pencipta/inventor beserta persentase kontribusi.
                        </p>
                    </div>
                    <button type="button" @click="addInventor()"
                        class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-sm transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Inventor</span>
                    </button>
                </div>

                <!-- Total Percentage Warning Banner -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                    <span class="text-slate-600 font-medium">Total Persentase Kontribusi:</span>
                    <span :class="totalPercentage === 100 ? 'text-emerald-600 font-bold' : 'text-amber-600 font-bold'">
                        <span x-text="totalPercentage"></span>%
                        <span x-show="totalPercentage !== 100" class="text-[10px] text-slate-500 font-normal ml-1">(Bisa
                            disesuaikan total 100%)</span>
                    </span>
                </div>

                <div class="space-y-3">
                    <template x-for="(inv, index) in inventors" :key="index">
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 relative">

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                    <span
                                        class="h-5 w-5 rounded-full bg-emerald-600 text-white text-[10px] flex items-center justify-center font-bold"
                                        x-text="index + 1"></span>
                                    <span>Inventor #<span x-text="index + 1"></span></span>
                                </span>

                                <button type="button" x-show="inventors.length > 1" @click="removeInventor(index)"
                                    class="text-sm text-rose-500 hover:text-rose-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <!-- Nama Inventor -->
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Lengkap &
                                        Gelar</label>
                                    <input type="text" :name="`inventors[${index}][name]`" x-model="inv.name" required
                                        placeholder="Nama Lengkap Inventor"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <!-- Kategori Afiliasi -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kategori
                                        Afiliasi</label>
                                    <select :name="`inventors[${index}][type]`" x-model="inv.type"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="internal_lecturer">Dosen Internal Kampus</option>
                                        <option value="student">Mahasiswa Bimbingan</option>
                                        <option value="external">Mitra / Inventor Eksternal</option>
                                    </select>
                                </div>

                                <!-- Peran Inventor -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Peran Tim</label>
                                    <select :name="`inventors[${index}][role]`" x-model="inv.role"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="inventor_utama">Inventor / Pencipta Utama</option>
                                        <option value="inventor_anggota">Inventor Anggota</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Kontribusi % -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kontribusi
                                        (%)</label>
                                    <input type="number" :name="`inventors[${index}][contribution_percentage]`"
                                        x-model.number="inv.contribution_percentage" min="0" max="100"
                                        step="0.1" placeholder="Contoh: 60"
                                        class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>

                                <!-- Afiliasi -->
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Institusi /
                                        Afiliasi</label>
                                    <input type="text" :name="`inventors[${index}][affiliation]`"
                                        x-model="inv.affiliation" placeholder="Institusi (Default: Universitas)"
                                        class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <!-- Section 4: Berkas Sertifikat & Deskripsi Invensi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">4. Berkas Sertifikat &
                    Deskripsi HKI</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Sertifikat PDF -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Unggah Sertifikat Resmi DJKI (PDF)
                        </label>
                        <input type="file" name="certificate" accept=".pdf"
                            class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Sertifikat pencatatan ciptaan / paten resmi (Maks 10
                            MB).</p>
                    </div>

                    <!-- Deskripsi Invensi PDF -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Deskripsi Invensi / Manual Ciptaan (PDF)
                        </label>
                        <input type="file" name="description_file" accept=".pdf"
                            class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Dokumen spesifikasi paten atau uraian ciptaan (Maks 10
                            MB).</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('dosen.ipr.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md shadow-emerald-600/30 transition cursor-pointer">
                    Simpan & Daftarkan
                </button>
            </div>

        </form>

    </div>

    <script>
        function iprForm() {
            return {
                iprType: 'hak_cipta',
                inventors: [{
                    name: '{{ $lecturer ? $lecturer->full_name : auth()->user()->name }}',
                    type: 'internal_lecturer',
                    role: 'inventor_utama',
                    contribution_percentage: 100,
                    affiliation: 'Universitas'
                }],
                addInventor() {
                    this.inventors.push({
                        name: '',
                        type: 'internal_lecturer',
                        role: 'inventor_anggota',
                        contribution_percentage: 0,
                        affiliation: 'Universitas'
                    });
                },
                removeInventor(index) {
                    if (this.inventors.length > 1) {
                        this.inventors.splice(index, 1);
                    }
                },
                get totalPercentage() {
                    return this.inventors.reduce((sum, inv) => sum + (Number(inv.contribution_percentage) || 0), 0);
                }
            };
        }
    </script>
@endsection
