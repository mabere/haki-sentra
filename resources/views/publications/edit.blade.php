@extends('layouts.app', ['title' => 'Edit Publikasi', 'header' => 'Perbarui Data Publikasi'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="publicationEditForm()">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('dosen.publications.show', $publication) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Detail Publikasi</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Perbarui Data Publikasi Ilmiah</h1>
        </div>
    </div>

    @if($publication->verification_status === 'revision' && $publication->verification_note)
    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-xs">
        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <div>
            <p class="font-bold">Permintaan Revisi dari Verifikator:</p>
            <p class="mt-0.5">{{ $publication->verification_note }}</p>
        </div>
    </div>
    @endif

    <!-- Form Edit -->
    <form method="POST" action="{{ route('dosen.publications.update', $publication) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Informasi Dasar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">1. Informasi Pokok Publikasi</h2>

            <!-- Judul Artikel -->
            <div>
                <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Judul Lengkap Artikel <span class="text-rose-500">*</span>
                </label>
                <textarea name="title"
                          rows="3"
                          required
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('title', $publication->title) }}</textarea>
                @error('title') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Jenis Publikasi -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Jenis Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select name="publication_type"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        <option value="jurnal_internasional" {{ old('publication_type', $publication->publication_type) === 'jurnal_internasional' ? 'selected' : '' }}>Jurnal Internasional Bereputasi</option>
                        <option value="jurnal_nasional_sinta" {{ old('publication_type', $publication->publication_type) === 'jurnal_nasional_sinta' ? 'selected' : '' }}>Jurnal Nasional Terakreditasi (SINTA)</option>
                        <option value="jurnal_nasional" {{ old('publication_type', $publication->publication_type) === 'jurnal_nasional' ? 'selected' : '' }}>Jurnal Nasional Tidak Terakreditasi</option>
                        <option value="prosiding_internasional" {{ old('publication_type', $publication->publication_type) === 'prosiding_internasional' ? 'selected' : '' }}>Prosiding Seminar Internasional</option>
                        <option value="prosiding_nasional" {{ old('publication_type', $publication->publication_type) === 'prosiding_nasional' ? 'selected' : '' }}>Prosiding Seminar Nasional</option>
                        <option value="buku_referensi" {{ old('publication_type', $publication->publication_type) === 'buku_referensi' ? 'selected' : '' }}>Buku Referensi / Monograf</option>
                        <option value="book_chapter" {{ old('publication_type', $publication->publication_type) === 'book_chapter' ? 'selected' : '' }}>Book Chapter</option>
                    </select>
                </div>

                <!-- Nama Jurnal -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Nama Jurnal / Penerbit <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="journal_name"
                           required
                           value="{{ old('journal_name', $publication->journal_name) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <!-- DOI -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor DOI
                    </label>
                    <input type="text"
                           name="doi"
                           value="{{ old('doi', $publication->doi) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <!-- ISSN / ISBN -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        ISSN / ISBN
                    </label>
                    <input type="text"
                           name="issn_isbn"
                           value="{{ old('issn_isbn', $publication->issn_isbn) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <!-- Volume / Issue / Pages -->
                <div class="sm:col-span-2 grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Volume</label>
                        <input type="text" name="volume" value="{{ old('volume', $publication->volume) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Nomor / Issue</label>
                        <input type="text" name="issue" value="{{ old('issue', $publication->issue) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Halaman</label>
                        <input type="text" name="pages" value="{{ old('pages', $publication->pages) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                    </div>
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Tahun Terbit <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="publication_year"
                           required
                           value="{{ old('publication_year', $publication->publication_year) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- URL -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        URL Tautan Artikel
                    </label>
                    <input type="url"
                           name="url"
                           value="{{ old('url', $publication->url) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

            </div>
        </div>

        <!-- Section 2: Indeksasi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Indeksasi & Peringkat Jurnal</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Basis Indeksasi</label>
                    <select name="indexation" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900">
                        <option value="">Pilih Indeksasi</option>
                        <option value="scopus" {{ old('indexation', $publication->indexation) === 'scopus' ? 'selected' : '' }}>Scopus</option>
                        <option value="wos" {{ old('indexation', $publication->indexation) === 'wos' ? 'selected' : '' }}>Web of Science (WoS)</option>
                        <option value="sinta" {{ old('indexation', $publication->indexation) === 'sinta' ? 'selected' : '' }}>SINTA (Nasional)</option>
                        <option value="garuda" {{ old('indexation', $publication->indexation) === 'garuda' ? 'selected' : '' }}>Garuda</option>
                        <option value="google_scholar" {{ old('indexation', $publication->indexation) === 'google_scholar' ? 'selected' : '' }}>Google Scholar</option>
                        <option value="lainnya" {{ old('indexation', $publication->indexation) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Kuartil Scopus</label>
                    <select name="quartile" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900">
                        <option value="">Non-Quartile</option>
                        <option value="Q1" {{ old('quartile', $publication->quartile) === 'Q1' ? 'selected' : '' }}>Scopus Q1</option>
                        <option value="Q2" {{ old('quartile', $publication->quartile) === 'Q2' ? 'selected' : '' }}>Scopus Q2</option>
                        <option value="Q3" {{ old('quartile', $publication->quartile) === 'Q3' ? 'selected' : '' }}>Scopus Q3</option>
                        <option value="Q4" {{ old('quartile', $publication->quartile) === 'Q4' ? 'selected' : '' }}>Scopus Q4</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Akreditasi SINTA</label>
                    <select name="sinta_grade" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900">
                        <option value="">Bukan SINTA</option>
                        <option value="S1" {{ old('sinta_grade', $publication->sinta_grade) === 'S1' ? 'selected' : '' }}>SINTA 1</option>
                        <option value="S2" {{ old('sinta_grade', $publication->sinta_grade) === 'S2' ? 'selected' : '' }}>SINTA 2</option>
                        <option value="S3" {{ old('sinta_grade', $publication->sinta_grade) === 'S3' ? 'selected' : '' }}>SINTA 3</option>
                        <option value="S4" {{ old('sinta_grade', $publication->sinta_grade) === 'S4' ? 'selected' : '' }}>SINTA 4</option>
                        <option value="S5" {{ old('sinta_grade', $publication->sinta_grade) === 'S5' ? 'selected' : '' }}>SINTA 5</option>
                        <option value="S6" {{ old('sinta_grade', $publication->sinta_grade) === 'S6' ? 'selected' : '' }}>SINTA 6</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Section 3: Tim Penulis -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-900">3. Tim Penulis & Kolaborasi</h2>
                <button type="button"
                        @click="addAuthor()"
                        class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold text-sm transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Penulis</span>
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(author, index) in authors" :key="index">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                <span class="h-5 w-5 rounded-full bg-indigo-600 text-white text-[10px] flex items-center justify-center font-bold" x-text="index + 1"></span>
                                <span>Penulis #<span x-text="index + 1"></span></span>
                            </span>
                            <button type="button"
                                    x-show="authors.length > 1"
                                    @click="removeAuthor(index)"
                                    class="text-sm text-rose-500 hover:text-rose-700">Hapus</button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Penulis</label>
                                <input type="text" :name="`authors[${index}][name]`" x-model="author.name" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kategori</label>
                                <select :name="`authors[${index}][type]`" x-model="author.type" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                                    <option value="internal_lecturer">Dosen Internal</option>
                                    <option value="student">Mahasiswa</option>
                                    <option value="external">Eksternal/Mitra</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Peran</label>
                                <select :name="`authors[${index}][role]`" x-model="author.role" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm">
                                    <option value="first_author">Penulis Pertama</option>
                                    <option value="corresponding">Korespondensi</option>
                                    <option value="co_author">Anggota</option>
                                </select>
                            </div>
                        </div>

                        <input type="text" :name="`authors[${index}][affiliation]`" x-model="author.affiliation" placeholder="Afiliasi" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                </template>
            </div>
        </div>

        <!-- Section 4: Berkas -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">4. Ganti Dokumen Lampiran (Opsional)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Ganti Berkas Naskah PDF</label>
                    <input type="file" name="file" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-700">
                    @if($publication->file_path)
                    <p class="text-[11px] text-emerald-600 mt-1 font-medium">✓ Berkas naskah saat ini sudah tersimpan.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Ganti Berkas LoA PDF</label>
                    <input type="file" name="loa" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700">
                    @if($publication->loa_path)
                    <p class="text-[11px] text-emerald-600 mt-1 font-medium">✓ Berkas LoA saat ini sudah tersimpan.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('dosen.publications.show', $publication) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
    function publicationEditForm() {
        return {
            authors: @json($publication->authors->map(fn($a) => [
                'name' => $a->author_name,
                'type' => $a->author_type,
                'role' => $a->author_role,
                'affiliation' => $a->affiliation ?? 'Universitas',
            ])),
            addAuthor() {
                this.authors.push({ name: '', type: 'internal_lecturer', role: 'co_author', affiliation: 'Universitas' });
            },
            removeAuthor(index) {
                if (this.authors.length > 1) {
                    this.authors.splice(index, 1);
                }
            }
        };
    }
</script>
@endsection

