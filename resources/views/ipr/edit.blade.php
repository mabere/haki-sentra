@extends('layouts.app', ['title' => 'Edit Karya HKI', 'header' => 'Perbarui Data HKI'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="iprEditForm()">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('dosen.ipr.show', $intellectualProperty) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Detail Karya HKI</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Perbarui Data Hak Kekayaan Intelektual (HKI)</h1>
        </div>
    </div>

    @if($intellectualProperty->verification_status === 'revision' && $intellectualProperty->verification_note)
    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div>
            <p class="font-bold">Permintaan Revisi dari Verifikator:</p>
            <p class="mt-0.5">{{ $intellectualProperty->verification_note }}</p>
        </div>
    </div>
    @endif

    <!-- Form Edit -->
    <form method="POST" action="{{ route('dosen.ipr.update', $intellectualProperty) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Informasi Pokok Ciptaan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">1. Informasi Pokok Ciptaan / Invensi</h2>

            <div>
                <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Judul Ciptaan / Invensi / Merek <span class="text-rose-500">*</span>
                </label>
                <textarea name="title"
                          rows="3"
                          required
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">{{ old('title', $intellectualProperty->title) }}</textarea>
                @error('title') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Jenis HKI <span class="text-rose-500">*</span>
                    </label>
                    <select name="ipr_type"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <option value="hak_cipta" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'hak_cipta' ? 'selected' : '' }}>Hak Cipta</option>
                        <option value="paten" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'paten' ? 'selected' : '' }}>Paten Biasa</option>
                        <option value="paten_sederhana" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'paten_sederhana' ? 'selected' : '' }}>Paten Sederhana</option>
                        <option value="merek" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'merek' ? 'selected' : '' }}>Merek Dagang</option>
                        <option value="desain_industri" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'desain_industri' ? 'selected' : '' }}>Desain Industri</option>
                        <option value="rahasia_dagang" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'rahasia_dagang' ? 'selected' : '' }}>Rahasia Dagang</option>
                        <option value="dtlst" {{ old('ipr_type', $intellectualProperty->ipr_type) === 'dtlst' ? 'selected' : '' }}>DTLST</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Sub-Jenis / Bidang
                    </label>
                    <input type="text"
                           name="sub_type"
                           value="{{ old('sub_type', $intellectualProperty->sub_type) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Pemegang Hak <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="rights_holder"
                           required
                           value="{{ old('rights_holder', $intellectualProperty->rights_holder) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </div>

        <!-- Section 2: Pendaftaran DJKI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Status Pendaftaran DJKI Kemenkumham</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Status Permohonan DJKI <span class="text-rose-500">*</span>
                    </label>
                    <select name="djki_status"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="draft" {{ old('djki_status', $intellectualProperty->djki_status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diajukan" {{ old('djki_status', $intellectualProperty->djki_status) === 'diajukan' ? 'selected' : '' }}>Diajukan ke DJKI</option>
                        <option value="pemeriksaan_formal" {{ old('djki_status', $intellectualProperty->djki_status) === 'pemeriksaan_formal' ? 'selected' : '' }}>Pemeriksaan Formal</option>
                        <option value="pemeriksaan_substantif" {{ old('djki_status', $intellectualProperty->djki_status) === 'pemeriksaan_substantif' ? 'selected' : '' }}>Pemeriksaan Substantif</option>
                        <option value="granted" {{ old('djki_status', $intellectualProperty->djki_status) === 'granted' ? 'selected' : '' }}>Diberi / Granted</option>
                        <option value="ditolak" {{ old('djki_status', $intellectualProperty->djki_status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor Permohonan DJKI
                    </label>
                    <input type="text"
                           name="application_number"
                           value="{{ old('application_number', $intellectualProperty->application_number) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Tanggal Pengajuan
                    </label>
                    <input type="date"
                           name="application_date"
                           value="{{ old('application_date', $intellectualProperty->application_date ? $intellectualProperty->application_date->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor Pencatatan / Sertifikat
                    </label>
                    <input type="text"
                           name="registration_number"
                           value="{{ old('registration_number', $intellectualProperty->registration_number) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Tanggal Terbit Sertifikat
                    </label>
                    <input type="date"
                           name="grant_date"
                           value="{{ old('grant_date', $intellectualProperty->grant_date ? $intellectualProperty->grant_date->format('Y-m-d') : '') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </div>

        <!-- Section 3: Tim Inventor -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-900">3. Tim Inventor / Pencipta</h2>
                <button type="button"
                        @click="addInventor()"
                        class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-sm transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Inventor</span>
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(inv, index) in inventors" :key="index">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                <span class="h-5 w-5 rounded-full bg-emerald-600 text-white text-[10px] flex items-center justify-center font-bold" x-text="index + 1"></span>
                                <span>Inventor #<span x-text="index + 1"></span></span>
                            </span>
                            <button type="button"
                                    x-show="inventors.length > 1"
                                    @click="removeInventor(index)"
                                    class="text-sm text-rose-500 hover:text-rose-700">Hapus</button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Inventor</label>
                                <input type="text" :name="`inventors[${index}][name]`" x-model="inv.name" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kategori</label>
                                <select :name="`inventors[${index}][type]`" x-model="inv.type" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                                    <option value="internal_lecturer">Dosen Internal</option>
                                    <option value="student">Mahasiswa</option>
                                    <option value="external">Eksternal</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Peran</label>
                                <select :name="`inventors[${index}][role]`" x-model="inv.role" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                                    <option value="inventor_utama">Inventor Utama</option>
                                    <option value="inventor_anggota">Anggota</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kontribusi (%)</label>
                                <input type="number" :name="`inventors[${index}][contribution_percentage]`" x-model.number="inv.contribution_percentage" min="0" max="100" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Afiliasi</label>
                                <input type="text" :name="`inventors[${index}][affiliation]`" x-model="inv.affiliation" placeholder="Afiliasi" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Section 4: Berkas -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">4. Ganti Dokumen Lampiran (Opsional)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Ganti Berkas Sertifikat PDF</label>
                    <input type="file" name="certificate" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-emerald-50 file:text-emerald-700">
                    @if($intellectualProperty->certificate_file)
                    <p class="text-[11px] text-emerald-600 mt-1 font-medium">✓ Berkas sertifikat saat ini sudah tersimpan.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Ganti Berkas Deskripsi PDF</label>
                    <input type="file" name="description_file" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700">
                    @if($intellectualProperty->description_file)
                    <p class="text-[11px] text-emerald-600 mt-1 font-medium">✓ Berkas deskripsi saat ini sudah tersimpan.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dosen.ipr.show', $intellectualProperty) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md shadow-emerald-600/30 transition cursor-pointer">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
    function iprEditForm() {
        return {
            inventors: @json($intellectualProperty->inventors->map(fn($i) => [
                'name' => $i->inventor_name,
                'type' => $i->inventor_type,
                'role' => $i->role,
                'contribution_percentage' => $i->contribution_percentage,
                'affiliation' => $i->affiliation ?? 'Universitas',
            ])),
            addInventor() {
                this.inventors.push({ name: '', type: 'internal_lecturer', role: 'inventor_anggota', contribution_percentage: 0, affiliation: 'Universitas' });
            },
            removeInventor(index) {
                if (this.inventors.length > 1) {
                    this.inventors.splice(index, 1);
                }
            }
        };
    }
</script>
@endsection

