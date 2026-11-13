@extends('layouts.app', ['title' => 'Publikasi Ilmiah', 'header' => 'Manajemen Publikasi Ilmiah Dosen'])

@section('content')
<div class="space-y-6" x-data="publicationImportManager()">

    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Daftar Publikasi Ilmiah</h1>
            <p class="text-sm text-slate-500">Kelola artikel jurnal internasional, nasional, dan prosiding seminar Anda.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button"
                    @click="openImportModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-300 shadow-xs transition duration-150">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Tarik dari SINTA / Scholar</span>
            </button>

            <a href="{{ route('dosen.publications.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm shadow-md shadow-indigo-600/30 transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Publikasi (+ Tarik DOI)</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
        <form method="GET" action="{{ route('dosen.publications.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">

            <!-- Search Keyword -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold uppercase tracking-wider text-slate-500 mb-1">Cari Judul / Jurnal / DOI</label>
                <div class="relative">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Contoh: Machine Learning, IEEE, 10.1016..."
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-semibold uppercase tracking-wider text-slate-500 mb-1">Status Verifikasi</label>
                <select name="status" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="all">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Perlu Revisi</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Indexation Filter -->
            <div>
                <label class="block text-sm font-semibold uppercase tracking-wider text-slate-500 mb-1">Indeksasi</label>
                <select name="indexation" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="all">Semua Indeksasi</option>
                    <option value="scopus" {{ request('indexation') === 'scopus' ? 'selected' : '' }}>Scopus</option>
                    <option value="wos" {{ request('indexation') === 'wos' ? 'selected' : '' }}>Web of Science (WoS)</option>
                    <option value="sinta" {{ request('indexation') === 'sinta' ? 'selected' : '' }}>SINTA (Nasional)</option>
                    <option value="garuda" {{ request('indexation') === 'garuda' ? 'selected' : '' }}>Garuda / Lainnya</option>
                </select>
            </div>

            <!-- Actions Filter -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold shadow-xs transition">
                    Filter
                </button>
                @if(request()->anyFilled(['q', 'status', 'indexation', 'year']))
                <a href="{{ route('dosen.publications.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition" title="Reset filter">
                    Reset
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Publications List Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-sm font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Judul Artikel & Sumber</th>
                        <th class="py-3.5 px-4">Kategori & Indeksasi</th>
                        <th class="py-3.5 px-4">Tim Penulis</th>
                        <th class="py-3.5 px-4 text-center">Berkas</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($publications as $index => $pub)
                    <tr class="hover:bg-slate-50/70 transition">

                        <!-- Number -->
                        <td class="py-4 px-4 text-center text-slate-400 font-medium">
                            {{ $publications->firstItem() + $index }}
                        </td>

                        <!-- Title & Source -->
                        <td class="py-4 px-4 max-w-sm">
                            <a href="{{ route('dosen.publications.show', $pub) }}" class="font-bold text-slate-900 hover:text-indigo-600 leading-snug line-clamp-2 transition">
                                {{ $pub->title }}
                            </a>
                            <div class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-slate-500">
                                <span class="font-semibold text-slate-700">{{ $pub->journal_name }}</span>
                                <span>&bull;</span>
                                <span>{{ $pub->publication_year }}</span>
                                @if($pub->volume)
                                    <span>&bull; Vol. {{ $pub->volume }}{{ $pub->issue ? '('.$pub->issue.')' : '' }}</span>
                                @endif
                            </div>
                            @if($pub->doi)
                            <div class="mt-1 flex items-center gap-1.5 text-sm text-blue-600">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <a href="https://doi.org/{{ $pub->doi }}" target="_blank" class="hover:underline truncate max-w-[200px]">
                                    {{ $pub->doi }}
                                </a>
                            </div>
                            @endif
                        </td>

                        <!-- Category & Quartile -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ str_replace('_', ' ', $pub->publication_type) }}
                                </span>
                                <div class="flex items-center gap-1.5">
                                    @if($pub->quartile)
                                        <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            Scopus {{ $pub->quartile }}
                                        </span>
                                    @endif
                                    @if($pub->sinta_grade)
                                        <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ $pub->sinta_grade }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Authors List -->
                        <td class="py-4 px-4 max-w-xs">
                            <ul class="space-y-1 text-sm">
                                @foreach($pub->authors->take(3) as $author)
                                <li class="flex items-center gap-1 text-slate-700 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $author->author_role === 'first_author' ? 'bg-indigo-500' : 'bg-slate-300' }}"></span>
                                    <span class="truncate">{{ $author->author_name }}</span>
                                    @if($author->author_type === 'student')
                                        <span class="text-[10px] sm:text-sm px-1 py-0.2 rounded bg-amber-100 text-amber-800 font-medium">Mhs</span>
                                    @endif
                                </li>
                                @endforeach
                                @if($pub->authors->count() > 3)
                                <li class="text-sm text-slate-400 italic">
                                    + {{ $pub->authors->count() - 3 }} penulis lainnya
                                </li>
                                @endif
                            </ul>
                        </td>

                        <!-- Attachment PDF -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if($pub->file_path)
                            <a href="{{ route('dosen.publications.file', [$pub, 'manuscript']) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-sm font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                <span>PDF</span>
                            </a>
                            @else
                            <span class="text-slate-400 text-sm italic">-</span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @php
                                $statusStyles = [
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'revision' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'rejected' => 'bg-slate-100 text-slate-700 border-slate-200',
                                ];
                                $statusNames = [
                                    'approved' => 'Disetujui',
                                    'pending' => 'Menunggu Validasi',
                                    'revision' => 'Perlu Perbaikan',
                                    'rejected' => 'Ditolak',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-semibold border {{ $statusStyles[$pub->verification_status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusNames[$pub->verification_status] ?? $pub->verification_status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('dosen.publications.show', $pub) }}"
                                   title="Lihat Detail"
                                   class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @if($pub->verification_status !== 'approved' || auth()->user()->isAdminLppm())
                                <a href="{{ route('dosen.publications.edit', $pub) }}"
                                   title="Edit Data"
                                   class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-slate-100 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>

                                <form method="POST" action="{{ route('dosen.publications.destroy', $pub) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data publikasi ini?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            title="Hapus Publikasi"
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="max-w-xs mx-auto space-y-2">
                                <div class="h-12 w-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-800">Belum ada publikasi ditemukan</p>
                                <p class="text-sm text-slate-500">Mulai input publikasi ilmiah Anda atau tarik otomatis metadata dari nomor DOI.</p>
                                <a href="{{ route('dosen.publications.create') }}" class="inline-block mt-2 px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-sm font-semibold">
                                    + Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publications->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $publications->links() }}
        </div>
        @endif

    </div>

    <!-- Modal Pratinjau & Impor Publikasi SINTA & Google Scholar -->
    <div x-show="showModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;">

        <!-- Backdrop -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="if(!importing) showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-slate-200 flex flex-col max-h-[92vh]">

                <!-- Modal Header -->
                <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-indigo-600 text-white flex items-center justify-center shadow-xs shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900" id="modal-title">
                                Impor Publikasi SINTA & Google Scholar
                            </h3>
                            <p class="text-sm text-slate-500">
                                Pratinjau terintegrasi dengan deduplikasi cerdas tanpa rekaman ganda.
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            @click="if(!importing) showModal = false"
                            class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 sm:p-6 overflow-y-auto space-y-5 flex-1">

                    <!-- State 1: ID SINTA / Scholar belum diisi di profil -->
                    <template x-if="!hasId">
                        <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-center space-y-3">
                            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 mx-auto flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h4 class="text-sm sm:text-base font-bold text-amber-900">ID SINTA atau Google Scholar Belum Terisi</h4>
                            <p class="text-sm text-amber-700 max-w-md mx-auto">
                                Agar sistem dapat menarik data artikel secara otomatis, silakan lengkapi nomor ID SINTA atau ID Google Scholar Anda pada menu Pengaturan Profil Dosen.
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold transition">
                                    <span>Buka Pengaturan Profil</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- State 2: Sedang Loading Fetching Data -->
                    <template x-if="hasId && loading">
                        <div class="py-12 text-center space-y-4">
                            <div class="relative w-14 h-14 mx-auto">
                                <div class="w-14 h-14 rounded-full border-4 border-indigo-200 border-t-indigo-600 animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm sm:text-base font-bold text-slate-900">Menghubungkan ke Portal Akademik...</h4>
                                <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                                    Mengambil publikasi dari SINTA & Google Scholar, menganalisis nomor DOI, dan mencocokkan data duplikasi dengan sistem SIM-LPPM.
                                </p>
                            </div>
                        </div>
                    </template>

                    <!-- State 3: Terjadi Error Saat Fetching -->
                    <template x-if="hasId && !loading && errorMessage">
                        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-center space-y-3">
                            <div class="text-rose-600 font-bold text-sm" x-text="errorMessage"></div>
                            <button type="button"
                                    @click="loadPreview()"
                                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold transition">
                                Coba Ambil Ulang Data
                            </button>
                        </div>
                    </template>

                    <!-- State 4: Sukses Memuat Data & Menampilkan Pratinjau -->
                    <div x-show="hasId && !loading && !errorMessage && articles.length > 0" class="space-y-4">

                        <!-- Profil Akademik Header Pill -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-wrap items-center justify-between gap-2 text-sm">
                            <div class="flex items-center gap-2 text-slate-700">
                                <span class="font-bold text-slate-900">{{ auth()->user()->lecturer?->full_name ?? auth()->user()->name }}</span>
                                <span class="text-slate-400">&bull;</span>
                                <span class="text-slate-500">NIDN: {{ auth()->user()->lecturer?->nidn ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200 text-[11px] sm:text-sm">
                                    SINTA ID: <span x-text="sintaId || '-'"></span>
                                </span>
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200 text-[11px] sm:text-sm">
                                    Scholar ID: <span x-text="scholarId || '-'"></span>
                                </span>
                            </div>
                        </div>

                        <!-- 4 Stat Metric Cards -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <!-- Card 1: Total Ditemukan -->
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                                <span class="text-[11px] sm:text-sm font-semibold uppercase tracking-wider text-slate-500">Total Ditemukan</span>
                                <p class="text-lg sm:text-xl font-black text-slate-900 mt-0.5" x-text="summary.total_found || 0"></p>
                            </div>

                            <!-- Card 2: Siap Diimpor (Baru) -->
                            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-center">
                                <span class="text-[11px] sm:text-sm font-semibold uppercase tracking-wider text-emerald-700">Siap Diimpor (Baru)</span>
                                <p class="text-lg sm:text-xl font-black text-emerald-700 mt-0.5" x-text="summary.total_new || 0"></p>
                            </div>

                            <!-- Card 3: Sudah Ada di SIM-LPPM -->
                            <div class="p-3.5 rounded-xl bg-slate-100 border border-slate-200 text-center">
                                <span class="text-[11px] sm:text-sm font-semibold uppercase tracking-wider text-slate-600">Sudah di SIM-LPPM</span>
                                <p class="text-lg sm:text-xl font-black text-slate-700 mt-0.5" x-text="summary.total_existing || 0"></p>
                            </div>

                            <!-- Card 4: Deduplikasi / Tergabung -->
                            <div class="p-3.5 rounded-xl bg-indigo-50 border border-indigo-200 text-center">
                                <span class="text-[11px] sm:text-sm font-semibold uppercase tracking-wider text-indigo-700">Dua Portal (Tergabung)</span>
                                <p class="text-lg sm:text-xl font-black text-indigo-700 mt-0.5" x-text="summary.total_merged || 0"></p>
                            </div>
                        </div>

                        <!-- Info Box Deduplikasi Cerdas -->
                        <div class="p-3.5 rounded-xl bg-indigo-50/80 border border-indigo-200 text-sm text-indigo-900 flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="space-y-0.5">
                                <span class="font-bold">Deduplikasi Otomatis Aktif:</span>
                                <p class="text-indigo-800 text-sm leading-relaxed">
                                    Artikel yang ditemukan di <strong>SINTA dan Google Scholar</strong> telah disatukan secara otomatis sehingga tidak ada data ganda. Artikel yang sudah pernah terdaftar di SIM-LPPM dinonaktifkan dari pemilihan.
                                </p>
                            </div>
                        </div>

                        <!-- Toolbar: Search Filter & Selection Controls -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-1">
                            <!-- Search Filter -->
                            <div class="relative flex-1">
                                <input type="text"
                                       x-model="searchFilter"
                                       placeholder="Cari judul artikel, jurnal, atau penulis..."
                                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- Buttons: Select All / Deselect -->
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button"
                                        @click="selectAllNew()"
                                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                                    Pilih Semua Baru
                                </button>
                                <button type="button"
                                        @click="deselectAll()"
                                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
                                    Batal Pilih
                                </button>
                                <span class="text-sm text-slate-500 pl-1">
                                    <span class="font-bold text-indigo-600" x-text="selectedCount"></span> / <span x-text="newArticlesCount"></span> dipilih
                                </span>
                            </div>
                        </div>

                        <!-- Articles Table in Scrollable Box -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                            <div class="overflow-x-auto max-h-[340px]">
                                <table class="w-full text-left text-sm text-slate-700 divide-y divide-slate-100">
                                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider text-[11px] sm:text-sm sticky top-0 z-10 border-b border-slate-200">
                                        <tr>
                                            <th class="py-3 px-3 w-10 text-center bg-slate-50">
                                                <input type="checkbox"
                                                       :checked="selectedCount > 0 && selectedCount === newArticlesCount"
                                                       @change="if($event.target.checked) { selectAllNew() } else { deselectAll() }"
                                                       class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                            </th>
                                            <th class="py-3 px-3 bg-slate-50">Judul Artikel & Publikasi</th>
                                            <th class="py-3 px-3 bg-slate-50">Sumber Portal</th>
                                            <th class="py-3 px-3 bg-slate-50">Penulis</th>
                                            <th class="py-3 px-3 text-center bg-slate-50">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <template x-for="article in filteredArticles" :key="article.import_key">
                                            <tr :class="article.is_already_imported ? 'bg-slate-50/70 opacity-60' : 'hover:bg-slate-50/80 transition'">
                                                <!-- Checkbox -->
                                                <td class="py-3 px-3 text-center align-top">
                                                    <template x-if="article.is_already_imported">
                                                        <span class="text-slate-400 font-bold" title="Artikel ini sudah ada di SIM-LPPM">&check;</span>
                                                    </template>
                                                    <template x-if="!article.is_already_imported">
                                                        <input type="checkbox"
                                                               :value="article.import_key"
                                                               x-model="selectedKeys"
                                                               class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                                    </template>
                                                </td>

                                                <!-- Title & Publication Details -->
                                                <td class="py-3 px-3 max-w-sm align-top">
                                                    <span class="font-bold text-slate-900 leading-snug block" x-text="article.title"></span>
                                                    <div class="mt-1 flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
                                                        <span class="font-semibold text-slate-700" x-text="article.journal_name"></span>
                                                        <span>&bull;</span>
                                                        <span x-text="article.publication_year"></span>
                                                        <template x-if="article.volume">
                                                            <span x-text="'Vol. ' + article.volume + (article.issue ? '(' + article.issue + ')' : '')"></span>
                                                        </template>
                                                    </div>
                                                    <template x-if="article.doi">
                                                        <div class="mt-0.5 text-sm text-blue-600 font-mono flex items-center gap-1">
                                                            <span>DOI:</span>
                                                            <span x-text="article.doi"></span>
                                                        </div>
                                                    </template>
                                                </td>

                                                <!-- Source Badges -->
                                                <td class="py-3 px-3 whitespace-nowrap align-top space-y-1">
                                                    <!-- Both SINTA & Scholar -->
                                                    <template x-if="article.sources && article.sources.includes('sinta') && article.sources.includes('google_scholar')">
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] sm:text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                            <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                            <span>SINTA & Scholar</span>
                                                        </span>
                                                    </template>

                                                    <!-- Only SINTA -->
                                                    <template x-if="article.sources && article.sources.includes('sinta') && !article.sources.includes('google_scholar')">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] sm:text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            SINTA
                                                        </span>
                                                    </template>

                                                    <!-- Only Scholar -->
                                                    <template x-if="article.sources && !article.sources.includes('sinta') && article.sources.includes('google_scholar')">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] sm:text-sm font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                            Google Scholar
                                                        </span>
                                                    </template>

                                                    <!-- Quartile / Grade if present -->
                                                    <div class="flex items-center gap-1">
                                                        <template x-if="article.quartile">
                                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200" x-text="article.quartile"></span>
                                                        </template>
                                                        <template x-if="article.sinta_grade">
                                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200" x-text="article.sinta_grade"></span>
                                                        </template>
                                                    </div>
                                                </td>

                                                <!-- Authors -->
                                                <td class="py-3 px-3 max-w-[200px] text-sm text-slate-600 align-top">
                                                    <span class="line-clamp-2" x-text="article.authors || '-'"></span>
                                                </td>

                                                <!-- Status Badge -->
                                                <td class="py-3 px-3 text-center whitespace-nowrap align-top">
                                                    <template x-if="article.is_already_imported">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] sm:text-sm font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                            Sudah di SIM-LPPM
                                                        </span>
                                                    </template>
                                                    <template x-if="!article.is_already_imported">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] sm:text-sm font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            Siap Diimpor
                                                        </span>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>

                                        <template x-if="filteredArticles.length === 0">
                                            <tr>
                                                <td colspan="5" class="py-8 text-center text-slate-500 text-sm">
                                                    Tidak ada artikel yang cocok dengan filter pencarian.
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- State 5: Notifikasi Sukses Setelah Impor -->
                    <template x-if="successMessage">
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-center space-y-2">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h4 class="text-sm sm:text-base font-bold text-emerald-900" x-text="successMessage"></h4>
                            <p class="text-sm text-emerald-700">Halaman akan diperbarui otomatis dalam sesaat...</p>
                        </div>
                    </template>

                </div>

                <!-- Modal Footer -->
                <div class="px-5 sm:px-6 py-4 border-t border-slate-200 bg-slate-50/70 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-slate-600">
                        <template x-if="hasId && !loading && articles.length > 0">
                            <span><strong class="text-indigo-600 font-bold" x-text="selectedCount"></strong> artikel terpilih untuk dimasukkan ke data publikasi.</span>
                        </template>
                    </div>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                        <button type="button"
                                @click="if(!importing) showModal = false"
                                :disabled="importing"
                                class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-semibold transition disabled:opacity-50">
                            Tutup
                        </button>

                        <template x-if="hasId && !loading && articles.length > 0">
                            <button type="button"
                                    @click="executeImport()"
                                    :disabled="selectedCount === 0 || importing"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-600/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="importing">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </template>
                                <template x-if="!importing">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </template>
                                <span x-text="importing ? 'Sedang Mengimpor...' : 'Impor (' + selectedCount + ') Artikel Terpilih'"></span>
                            </button>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function publicationImportManager() {
    return {
        showModal: false,
        loading: false,
        importing: false,
        hasId: {{ (!empty(auth()->user()->lecturer?->sinta_id) || !empty(auth()->user()->lecturer?->google_scholar_id)) ? 'true' : 'false' }},
        sintaId: '{{ auth()->user()->lecturer?->sinta_id ?? '' }}',
        scholarId: '{{ auth()->user()->lecturer?->google_scholar_id ?? '' }}',
        articles: [],
        summary: {
            total_found: 0,
            total_new: 0,
            total_existing: 0,
            total_merged: 0,
        },
        selectedKeys: [],
        searchFilter: '',
        errorMessage: '',
        successMessage: '',

        openImportModal() {
            this.showModal = true;
            this.errorMessage = '';
            this.successMessage = '';
            if (this.hasId && this.articles.length === 0) {
                this.loadPreview();
            }
        },

        async loadPreview() {
            this.loading = true;
            this.errorMessage = '';
            this.articles = [];
            this.selectedKeys = [];

            try {
                const response = await fetch('{{ route('dosen.publications.preview-import') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    this.errorMessage = data.message || 'Gagal memuat pratinjau publikasi.';
                    return;
                }

                this.articles = data.articles || [];
                this.summary = data.summary || {
                    total_found: 0,
                    total_new: 0,
                    total_existing: 0,
                    total_merged: 0,
                };

                // By default, select all new (not yet imported) articles
                this.selectedKeys = this.articles
                    .filter(a => !a.is_already_imported)
                    .map(a => a.import_key);

            } catch (err) {
                this.errorMessage = 'Terjadi kesalahan jaringan saat memuat data.';
            } finally {
                this.loading = false;
            }
        },

        get filteredArticles() {
            if (!this.searchFilter.trim()) {
                return this.articles;
            }
            const q = this.searchFilter.toLowerCase();
            return this.articles.filter(a =>
                (a.title && a.title.toLowerCase().includes(q)) ||
                (a.journal_name && a.journal_name.toLowerCase().includes(q)) ||
                (a.authors && a.authors.toLowerCase().includes(q))
            );
        },

        get newArticlesCount() {
            return this.articles.filter(a => !a.is_already_imported).length;
        },

        get selectedCount() {
            return this.selectedKeys.length;
        },

        selectAllNew() {
            this.selectedKeys = this.articles
                .filter(a => !a.is_already_imported)
                .map(a => a.import_key);
        },

        deselectAll() {
            this.selectedKeys = [];
        },

        async executeImport() {
            if (this.selectedKeys.length === 0) return;

            const selectedArticles = this.articles.filter(a =>
                !a.is_already_imported && this.selectedKeys.includes(a.import_key)
            );

            if (selectedArticles.length === 0) return;

            this.importing = true;
            this.errorMessage = '';

            try {
                const response = await fetch('{{ route('dosen.publications.import') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        articles: selectedArticles
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    this.errorMessage = data.message || 'Gagal menyimpan artikel terpilih.';
                    this.importing = false;
                    return;
                }

                this.successMessage = data.message || 'Artikel berhasil diimpor!';
                setTimeout(() => {
                    window.location.reload();
                }, 1000);

            } catch (err) {
                this.errorMessage = 'Terjadi kendala saat menyimpan artikel.';
                this.importing = false;
            }
        }
    };
}
</script>
@endpush

