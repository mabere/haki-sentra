@extends('layouts.app', ['title' => 'Tambah Publikasi', 'header' => 'Formulir Publikasi Ilmiah'])

@section('content')
    <div class="max-w-4xl mx-auto space-y-6" x-data="publicationForm()">

        <!-- Header Navigation -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('dosen.publications.index') }}"
                    class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Daftar Publikasi</span>
                </a>
                <h1 class="text-xl font-bold text-slate-900 mt-1">Tambah Publikasi Ilmiah Baru</h1>
            </div>
        </div>

        <!-- DOI Auto-Fetch Banner Card -->
        <div
            class="rounded-2xl bg-gradient-to-r from-blue-900 to-indigo-900 p-6 text-white shadow-lg border border-indigo-800/50">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-indigo-500/20 text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-white">Tarik Metadata Otomatis via Crossref DOI</h3>
                </div>
                <p class="text-sm text-indigo-200">
                    Punya nomor DOI artikel? Masukkan nomor DOI di bawah untuk mengisi judul, nama jurnal, volume, nomor,
                    tahun, dan tim penulis secara instan.
                </p>

                <div class="flex flex-col sm:flex-row gap-2 pt-1">
                    <div class="relative flex-1">
                        <input type="text" x-model="doiInput"
                            placeholder="Contoh: 10.1016/j.jbi.2023.104445 atau https://doi.org/..."
                            @keydown.enter.prevent="fetchMetadata()"
                            class="w-full px-4 py-2.5 bg-slate-950/70 border border-indigo-400/30 rounded-xl text-sm text-white placeholder-indigo-300/50 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <button type="button" @click="fetchMetadata()" :disabled="fetching"
                        class="px-5 py-2.5 rounded-xl bg-indigo-500 hover:bg-indigo-400 text-white font-semibold text-sm transition shadow-md flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                        <template x-if="fetching">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <span x-text="fetching ? 'Mengambil Data...' : 'Tarik Metadata'"></span>
                    </button>
                </div>

                <!-- DOI Fetch Status Alerts -->
                <template x-if="fetchMessage">
                    <div :class="fetchSuccess ? 'bg-emerald-500/20 text-emerald-200 border-emerald-500/30' :
                        'bg-rose-500/20 text-rose-200 border-rose-500/30'"
                        class="p-3 rounded-xl border text-sm flex items-center gap-2 mt-2">
                        <span x-text="fetchMessage"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Publication Form -->
        <form method="POST" action="{{ route('dosen.publications.store') }}" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Dasar Publikasi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">1. Informasi Pokok Publikasi
                </h2>

                <!-- Judul Artikel -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Judul Lengkap Artikel <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="title" x-model="formData.title" rows="3" required placeholder="Masukkan judul artikel ilmiah..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('title') }}</textarea>
                    @error('title')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Jenis Publikasi -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Jenis Publikasi <span class="text-rose-500">*</span>
                        </label>
                        <select name="publication_type" x-model="formData.publication_type" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">Pilih Jenis Publikasi</option>
                            <option value="jurnal_internasional">Jurnal Internasional Bereputasi</option>
                            <option value="jurnal_nasional_sinta">Jurnal Nasional Terakreditasi (SINTA)</option>
                            <option value="jurnal_nasional">Jurnal Nasional Tidak Terakreditasi</option>
                            <option value="prosiding_internasional">Prosiding Seminar Internasional</option>
                            <option value="prosiding_nasional">Prosiding Seminar Nasional</option>
                            <option value="buku_referensi">Buku Referensi / Monograf (ISBN)</option>
                            <option value="book_chapter">Book Chapter</option>
                        </select>
                    </div>

                    <!-- Nama Jurnal / Penerbit -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Jurnal / Seminar / Penerbit <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="journal_name" x-model="formData.journal_name" required
                            value="{{ old('journal_name') }}" placeholder="Contoh: IEEE Access, Jurnal Komputer..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <!-- DOI -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nomor DOI (Digital Object Identifier)
                        </label>
                        <input type="text" name="doi" x-model="formData.doi" value="{{ old('doi') }}"
                            placeholder="10.1016/..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <!-- ISSN / ISBN -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            ISSN / ISBN
                        </label>
                        <input type="text" name="issn_isbn" x-model="formData.issn_isbn" value="{{ old('issn_isbn') }}"
                            placeholder="Contoh: 2169-3536"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <!-- Volume / Issue / Pages -->
                    <div class="sm:col-span-2 grid grid-cols-3 gap-3">
                        <div>
                            <label
                                class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Volume</label>
                            <input type="text" name="volume" x-model="formData.volume" value="{{ old('volume') }}"
                                placeholder="12"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Nomor /
                                Issue</label>
                            <input type="text" name="issue" x-model="formData.issue" value="{{ old('issue') }}"
                                placeholder="3"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Halaman</label>
                            <input type="text" name="pages" x-model="formData.pages" value="{{ old('pages') }}"
                                placeholder="105-115"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Tahun Terbit -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Tahun Terbit <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="publication_year" x-model="formData.publication_year" required
                            min="1990" max="{{ date('Y') + 1 }}" value="{{ old('publication_year', date('Y')) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    <!-- URL Resmi Artikel -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            URL Tautan Artikel Resmi
                        </label>
                        <input type="url" name="url" x-model="formData.url" value="{{ old('url') }}"
                            placeholder="https://..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                </div>
            </div>

            <!-- Section 2: Kategori Indeksasi & Akreditasi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">2. Indeksasi & Peringkat Jurnal
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <!-- Indeksasi -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Basis
                            Indeksasi</label>
                        <select name="indexation" x-model="formData.indexation"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">Pilih Indeksasi</option>
                            <option value="scopus">Scopus</option>
                            <option value="wos">Web of Science (WoS)</option>
                            <option value="sinta">SINTA (Nasional)</option>
                            <option value="garuda">Garuda</option>
                            <option value="google_scholar">Google Scholar</option>
                            <option value="lainnya">Lainnya / Tidak Terindeks</option>
                        </select>
                    </div>

                    <!-- Kuartil Scopus -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Kuartil
                            Scopus (Jika ada)</label>
                        <select name="quartile" x-model="formData.quartile"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">Bukan Scopus / Non-Quartile</option>
                            <option value="Q1">Scopus Q1</option>
                            <option value="Q2">Scopus Q2</option>
                            <option value="Q3">Scopus Q3</option>
                            <option value="Q4">Scopus Q4</option>
                        </select>
                    </div>

                    <!-- Peringkat SINTA -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">Akreditasi
                            SINTA (Jika ada)</label>
                        <select name="sinta_grade" x-model="formData.sinta_grade"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">Bukan SINTA</option>
                            <option value="S1">SINTA 1</option>
                            <option value="S2">SINTA 2</option>
                            <option value="S3">SINTA 3</option>
                            <option value="S4">SINTA 4</option>
                            <option value="S5">SINTA 5</option>
                            <option value="S6">SINTA 6</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Section 3: Tim Penulis (Repeater Dinamis) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">3. Tim Penulis & Kolaborasi</h2>
                        <p class="text-sm text-slate-500">Tandai status kepenulisan dan keterlibatan mahasiswa/mitra luar.
                        </p>
                    </div>
                    <button type="button" @click="addAuthor()"
                        class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold text-sm transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Penulis</span>
                    </button>
                </div>

                <!-- Authors Repeater Rows -->
                <div class="space-y-3">
                    <template x-for="(author, index) in authors" :key="index">
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 relative group">

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                    <span
                                        class="h-5 w-5 rounded-full bg-indigo-600 text-white text-[10px] flex items-center justify-center font-bold"
                                        x-text="index + 1"></span>
                                    <span>Penulis #<span x-text="index + 1"></span></span>
                                </span>

                                <button type="button" x-show="authors.length > 1" @click="removeAuthor(index)"
                                    class="text-sm text-rose-500 hover:text-rose-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Hapus</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <!-- Nama Penulis -->
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Lengkap &
                                        Gelar</label>
                                    <input type="text" :name="`authors[${index}][name]`" x-model="author.name" required
                                        placeholder="Nama Lengkap Penulis"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>

                                <!-- Tipe Penulis -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kategori
                                        Afiliasi</label>
                                    <select :name="`authors[${index}][type]`" x-model="author.type"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="internal_lecturer">Dosen Internal Kampus</option>
                                        <option value="student">Mahasiswa Bimbingan</option>
                                        <option value="external">Penulis Eksternal / Mitra</option>
                                    </select>
                                </div>

                                <!-- Peran Penulis -->
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Peran
                                        Kepenulisan</label>
                                    <select :name="`authors[${index}][role]`" x-model="author.role"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="first_author">Penulis Pertama (First)</option>
                                        <option value="corresponding">Korespondensi (Corr)</option>
                                        <option value="co_author">Penulis Anggota (Co-Author)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <input type="text" :name="`authors[${index}][affiliation]`"
                                    x-model="author.affiliation"
                                    placeholder="Afiliasi / Universitas (Default: Universitas)"
                                    class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            <!-- Section 4: Berkas Naskah & LoA -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">4. Dokumen Naskah & Lampiran
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Naskah Lengkap PDF -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Unggah Naskah Artikel (PDF)
                        </label>
                        <input type="file" name="file" accept=".pdf"
                            class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Format PDF, maksimal ukuran 10 MB.</p>
                    </div>

                    <!-- LoA PDF -->
                    <div>
                        <label class="block text-sm font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Bukti LoA / Korespondensi (Opsional)
                        </label>
                        <input type="file" name="loa" accept=".pdf"
                            class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Format PDF, maksimal ukuran 10 MB.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('dosen.publications.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
                    Simpan & Ajukan Verifikasi
                </button>
            </div>

        </form>

    </div>

    <script>
        function publicationForm() {
            return {
                doiInput: '',
                fetching: false,
                fetchMessage: '',
                fetchSuccess: false,
                formData: {
                    title: '{{ old('title') }}',
                    publication_type: '{{ old('publication_type') }}',
                    journal_name: '{{ old('journal_name') }}',
                    doi: '{{ old('doi') }}',
                    issn_isbn: '{{ old('issn_isbn') }}',
                    volume: '{{ old('volume') }}',
                    issue: '{{ old('issue') }}',
                    pages: '{{ old('pages') }}',
                    publication_year: '{{ old('publication_year', date('Y')) }}',
                    url: '{{ old('url') }}',
                    indexation: '{{ old('indexation') }}',
                    quartile: '{{ old('quartile') }}',
                    sinta_grade: '{{ old('sinta_grade') }}',
                },
                authors: [{
                    name: '{{ $lecturer ? $lecturer->full_name : auth()->user()->name }}',
                    type: 'internal_lecturer',
                    role: 'first_author',
                    affiliation: 'Universitas'
                }],
                addAuthor(name = '', type = 'internal_lecturer', role = 'co_author', affiliation = 'Universitas') {
                    this.authors.push({
                        name,
                        type,
                        role,
                        affiliation
                    });
                },
                removeAuthor(index) {
                    if (this.authors.length > 1) {
                        this.authors.splice(index, 1);
                    }
                },
                async fetchMetadata() {
                    if (!this.doiInput.trim()) {
                        this.fetchMessage = 'Silakan masukkan nomor DOI terlebih dahulu.';
                        this.fetchSuccess = false;
                        return;
                    }

                    this.fetching = true;
                    this.fetchMessage = '';

                    try {
                        const response = await fetch('{{ route('dosen.publications.fetch-doi') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                doi: this.doiInput.trim()
                            }),
                        });

                        const res = await response.json();

                        if (res.success) {
                            this.fetchSuccess = true;
                            this.fetchMessage = `Berhasil menarik metadata: "${res.title}"`;

                            this.formData.title = res.title || this.formData.title;
                            this.formData.journal_name = res.journal_name || this.formData.journal_name;
                            this.formData.doi = res.doi || this.doiInput;
                            this.formData.issn_isbn = res.issn_isbn || this.formData.issn_isbn;
                            this.formData.volume = res.volume || this.formData.volume;
                            this.formData.issue = res.issue || this.formData.issue;
                            this.formData.pages = res.pages || this.formData.pages;
                            this.formData.publication_year = res.publication_year || this.formData.publication_year;
                            this.formData.url = res.url || this.formData.url;

                            // Pre-select publication type as international journal
                            if (!this.formData.publication_type) {
                                this.formData.publication_type = 'jurnal_internasional';
                            }
                            if (!this.formData.indexation) {
                                this.formData.indexation = 'scopus';
                            }

                            // Fill authors from Crossref
                            if (res.authors && res.authors.length > 0) {
                                this.authors = [];
                                res.authors.forEach((a, idx) => {
                                    this.authors.push({
                                        name: a.name,
                                        type: idx === 0 ? 'internal_lecturer' : 'external',
                                        role: idx === 0 ? 'first_author' : 'co_author',
                                        affiliation: a.affiliation || 'Universitas'
                                    });
                                });
                            }
                        } else {
                            this.fetchSuccess = false;
                            this.fetchMessage = res.message || 'Metadata tidak ditemukan di Crossref.';
                        }
                    } catch (err) {
                        this.fetchSuccess = false;
                        this.fetchMessage = 'Gagal menghubungi server: ' + err.message;
                    } finally {
                        this.fetching = false;
                    }
                }
            };
        }
    </script>
@endsection
