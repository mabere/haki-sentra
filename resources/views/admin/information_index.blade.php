@extends('layouts.app', ['title' => 'Pusat Informasi & Dokumen', 'header' => 'Kelola Pusat Informasi & Template Dokumen'])

@section('content')
<div class="space-y-6" x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editData: {
        id: null,
        title: '',
        category: 'alur_syarat',
        badge_text: '',
        icon: 'file-text',
        order: 0,
        description: '',
        content: '',
        external_link: '',
        is_active: true,
        file_name: null,
        update_url: ''
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            title: item.title,
            category: item.category,
            badge_text: item.badge_text || '',
            icon: item.icon || 'file-text',
            order: item.order || 0,
            description: item.description || '',
            content: item.content || '',
            external_link: item.external_link || '',
            is_active: Boolean(item.is_active),
            file_name: item.file_name,
            update_url: '{{ url('/admin/informasi') }}/' + item.id
        };
        this.editModalOpen = true;
    }
}">

    <!-- Notification Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="font-bold mb-1">Terjadi kesalahan pada input data:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pusat Informasi &amp; Template Dokumen HKI</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data dinamis alur SOP, template berkas unduhan, tanya jawab (FAQ), pengumuman, dan panduan publik.</p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('informasi.index') }}" target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Lihat Laman Publik</span>
            </a>

            <button type="button" @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-600/20 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Data / Dokumen</span>
            </button>
        </div>
    </div>

    <!-- Stat Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Total Data</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</span>
                <span class="px-2 py-0.5 rounded text-sm font-bold bg-slate-100 text-slate-700">Semua</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Alur &amp; Syarat</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-indigo-600">{{ $stats['alur_syarat'] }}</span>
                <span class="px-2 py-0.5 rounded text-sm font-bold bg-indigo-50 text-indigo-700">Tahapan</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Template Unduhan</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-purple-600">{{ $stats['template_dokumen'] }}</span>
                <span class="px-2 py-0.5 rounded text-sm font-bold bg-purple-50 text-purple-700">Berkas</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Tanya Jawab (FAQ)</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-emerald-600">{{ $stats['faq'] }}</span>
                <span class="px-2 py-0.5 rounded text-sm font-bold bg-emerald-50 text-emerald-700">Tanya Jawab</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs col-span-2 sm:col-span-1">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Pengumuman</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-amber-600">{{ $stats['pengumuman'] }}</span>
                <span class="px-2 py-0.5 rounded text-sm font-bold bg-amber-50 text-amber-700">Berita</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-5 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Category Tabs -->
            <div class="flex items-center gap-1 overflow-x-auto pb-1 lg:pb-0 scrollbar-none text-sm font-semibold">
                <a href="{{ route('admin.informasi.index', ['tab' => 'all', 'q' => $search]) }}"
                   class="px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ $tab === 'all' ? 'bg-slate-900 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.informasi.index', ['tab' => 'alur_syarat', 'q' => $search]) }}"
                   class="px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ $tab === 'alur_syarat' ? 'bg-indigo-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }}">
                    Alur &amp; SOP ({{ $stats['alur_syarat'] }})
                </a>
                <a href="{{ route('admin.informasi.index', ['tab' => 'template_dokumen', 'q' => $search]) }}"
                   class="px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ $tab === 'template_dokumen' ? 'bg-purple-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-purple-600 hover:bg-purple-50' }}">
                    Template Dokumen ({{ $stats['template_dokumen'] }})
                </a>
                <a href="{{ route('admin.informasi.index', ['tab' => 'faq', 'q' => $search]) }}"
                   class="px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ $tab === 'faq' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-emerald-600 hover:bg-emerald-50' }}">
                    FAQ / Tanya Jawab ({{ $stats['faq'] }})
                </a>
                <a href="{{ route('admin.informasi.index', ['tab' => 'pengumuman', 'q' => $search]) }}"
                   class="px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ $tab === 'pengumuman' ? 'bg-amber-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-amber-600 hover:bg-amber-50' }}">
                    Pengumuman ({{ $stats['pengumuman'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.informasi.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul, label atau deskripsi..."
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition cursor-pointer">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.informasi.index', ['tab' => $tab]) }}" class="px-2.5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-sm font-semibold uppercase text-slate-500 border-y border-slate-200">
                    <tr>
                        <th class="py-3 px-3 w-16 text-center">Urutan</th>
                        <th class="py-3 px-4">Judul &amp; Informasi</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Lampiran / Berkas</th>
                        <th class="py-3 px-3 text-center">Status Publik</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Urutan -->
                            <td class="py-3.5 px-3 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-mono font-bold text-sm">
                                    {{ $item->order }}
                                </span>
                            </td>

                            <!-- Judul & Deskripsi -->
                            <td class="py-3.5 px-4 max-w-md">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 line-clamp-1">{{ $item->title }}</span>
                                    @if($item->badge_text)
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 shrink-0">
                                            {{ $item->badge_text }}
                                        </span>
                                    @endif
                                </div>
                                @if($item->description)
                                    <p class="text-sm text-slate-500 line-clamp-2 mt-0.5">{{ $item->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-1">
                                    <span>Slug: <code class="font-mono text-slate-600">{{ $item->slug }}</code></span>
                                    <span>•</span>
                                    <span>Diperbarui: {{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-sm font-bold {{ $item->category_badge_color }}">
                                    {{ $item->category_label }}
                                </span>
                            </td>

                            <!-- Lampiran / Berkas -->
                            <td class="py-3.5 px-3">
                                @if($item->file_path)
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $item->file_type === 'PDF' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $item->file_type ?? 'FILE' }}
                                        </span>
                                        <a href="{{ route('informasi.unduh', $item->slug) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold text-sm truncate max-w-[160px] inline-block" title="Unduh {{ $item->file_name }}">
                                            {{ $item->file_name }}
                                        </a>
                                        @if($item->file_size)
                                            <span class="text-[11px] text-slate-400">({{ $item->file_size }})</span>
                                        @endif
                                    </div>
                                @elseif($item->external_link)
                                    <a href="{{ $item->external_link }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-sky-600 hover:underline font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        <span>Link Eksternal</span>
                                    </a>
                                @else
                                    <span class="text-sm text-slate-400 italic">Teks/Panduan Saja</span>
                                @endif
                            </td>

                            <!-- Status Publik -->
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.informasi.toggle-status', $item->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-sm font-bold transition cursor-pointer {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}"
                                            title="Klik untuk ubah status visibilitas di publik">
                                        <span class="w-2 h-2 rounded-full {{ $item->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $item->is_active ? 'Tampil di Publik' : 'Disembunyikan' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Tombol Preview Detail Publik -->
                                    <a href="{{ route('informasi.show', $item->slug) }}" target="_blank"
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Lihat Tampilan Publik">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <button type="button" @click='openEdit(@json($item))'
                                            class="px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-sm transition cursor-pointer flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form method="POST" action="{{ route('admin.informasi.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data \'{{ $item->title }}\'? File lampiran yang tersimpan juga akan ikut dihapus.');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">Belum ada data informasi pada kategori ini.</p>
                                <p class="text-sm text-slate-400 mt-0.5">Klik tombol "+ Tambah Data / Dokumen" di kanan atas untuk membuat konten baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: TAMBAH DATA INFORMASI / DOKUMEN -->
    <div x-show="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-2xl w-full p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto" @click.outside="createModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Informasi / Template Dokumen Baru</h3>
                    <p class="text-sm text-slate-500">Data yang ditambahkan akan langsung terhubung ke landing page publik dan laman panduan.</p>
                </div>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-2xl font-semibold leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.informasi.store') }}" enctype="multipart/form-data" class="space-y-4 text-sm">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Kategori Konten *</label>
                        <select name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                            <option value="alur_syarat">Alur &amp; Syarat HKI (Step SOP)</option>
                            <option value="template_dokumen" selected>Template Dokumen Unduhan</option>
                            <option value="faq">Tanya Jawab (FAQ)</option>
                            <option value="pengumuman">Pengumuman &amp; Berita Resmi</option>
                            <option value="panduan">Panduan Lengkap / Tutorial</option>
                        </select>
                    </div>

                    <!-- Urutan Tampilan -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Urutan Urutan Tampilan (#)</label>
                        <input type="number" name="order" value="1" min="0" placeholder="1, 2, 3..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Judul Informasi / Dokumen *</label>
                    <input type="text" name="title" required placeholder="Contoh: Formulir Pendaftaran Merek Dagang LPPM"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Badge Text -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Badge / Label (Opsional)</label>
                        <input type="text" name="badge_text" placeholder="Contoh: DOCX, Wajib, PDF, Baru, Langkah 1"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                    </div>

                    <!-- Icon Name -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Icon (Lucide)</label>
                        <select name="icon" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                            <option value="file-text">file-text (Dokumen Teks)</option>
                            <option value="layers">layers (Tahapan / Alur)</option>
                            <option value="help-circle">help-circle (Bantuan / FAQ)</option>
                            <option value="bell">bell (Pengumuman / Lonceng)</option>
                            <option value="check-circle">check-circle (Persetujuan / Validasi)</option>
                            <option value="book-open">book-open (Buku Panduan)</option>
                            <option value="download">download (Unduhan)</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi Singkat -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Deskripsi Singkat (Ringkasan)</label>
                    <textarea name="description" rows="2" placeholder="Ringkasan penjelasan untuk ditampilkan pada kartu atau daftar..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition"></textarea>
                </div>

                <!-- Konten Lengkap -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Konten Lengkap / Penjelasan / Jawaban FAQ</label>
                    <textarea name="content" rows="4" placeholder="Tulis rincian syarat, langkah-langkah detail, materi pengumuman, atau jawaban lengkap FAQ..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition"></textarea>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">File Lampiran Dokumen (DOCX, PDF, XLS, ZIP)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:text-sm file:font-semibold hover:file:bg-blue-700 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Format didukung: PDF, Word (DOC/DOCX), Excel (XLS/XLSX), ZIP. Maksimal 10 MB.</p>
                </div>

                <!-- Link Eksternal -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Tautan Eksternal (Opsional)</label>
                    <input type="url" name="external_link" placeholder="https://dgip.go.id atau tautan Google Drive..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                </div>

                <!-- Checkbox Publikasi -->
                <div class="flex items-center gap-2.5 pt-1.5">
                    <input type="checkbox" name="is_active" id="create_is_active" value="1" checked
                           class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                    <label for="create_is_active" class="font-semibold text-slate-700 cursor-pointer">
                        Langsung publikasikan di website publik
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md shadow-blue-600/30 transition cursor-pointer">
                        Simpan Konten Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT DATA INFORMASI / DOKUMEN -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-2xl w-full p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto" @click.outside="editModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Data Informasi / Template Dokumen</h3>
                    <p class="text-sm text-slate-500">Perbarui rincian, berkas dokumen unduhan, atau status publikasi.</p>
                </div>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-2xl font-semibold leading-none">&times;</button>
            </div>

            <form method="POST" :action="editData.update_url" enctype="multipart/form-data" class="space-y-4 text-sm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Kategori Konten *</label>
                        <select name="category" x-model="editData.category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                            <option value="alur_syarat">Alur &amp; Syarat HKI (Step SOP)</option>
                            <option value="template_dokumen">Template Dokumen Unduhan</option>
                            <option value="faq">Tanya Jawab (FAQ)</option>
                            <option value="pengumuman">Pengumuman &amp; Berita Resmi</option>
                            <option value="panduan">Panduan Lengkap / Tutorial</option>
                        </select>
                    </div>

                    <!-- Urutan Tampilan -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Urutan Urutan Tampilan (#)</label>
                        <input type="number" name="order" x-model="editData.order" min="0"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Judul Informasi / Dokumen *</label>
                    <input type="text" name="title" x-model="editData.title" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Badge Text -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Badge / Label (Opsional)</label>
                        <input type="text" name="badge_text" x-model="editData.badge_text" placeholder="Contoh: DOCX, Wajib, PDF"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                    </div>

                    <!-- Icon Name -->
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Icon (Lucide)</label>
                        <select name="icon" x-model="editData.icon" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                            <option value="file-text">file-text (Dokumen Teks)</option>
                            <option value="layers">layers (Tahapan / Alur)</option>
                            <option value="help-circle">help-circle (Bantuan / FAQ)</option>
                            <option value="bell">bell (Pengumuman / Lonceng)</option>
                            <option value="check-circle">check-circle (Persetujuan / Validasi)</option>
                            <option value="book-open">book-open (Buku Panduan)</option>
                            <option value="download">download (Unduhan)</option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi Singkat -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Deskripsi Singkat (Ringkasan)</label>
                    <textarea name="description" x-model="editData.description" rows="2"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition"></textarea>
                </div>

                <!-- Konten Lengkap -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Konten Lengkap / Penjelasan / Jawaban FAQ</label>
                    <textarea name="content" x-model="editData.content" rows="4"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition"></textarea>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Ganti File Lampiran (Opsional)</label>
                    <template x-if="editData.file_name">
                        <div class="text-sm text-slate-500 mb-2 flex items-center gap-1.5">
                            <span class="font-medium text-slate-700">File tersimpan saat ini:</span>
                            <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-blue-700" x-text="editData.file_name"></span>
                        </div>
                    </template>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-amber-600 file:text-white file:text-sm file:font-semibold hover:file:bg-amber-700 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Unggah file baru jika ingin mengganti file yang ada saat ini. Maksimal 10 MB.</p>
                </div>

                <!-- Link Eksternal -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Tautan Eksternal (Opsional)</label>
                    <input type="url" name="external_link" x-model="editData.external_link" placeholder="https://..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                </div>

                <!-- Checkbox Publikasi -->
                <div class="flex items-center gap-2.5 pt-1.5">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editData.is_active"
                           class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                    <label for="edit_is_active" class="font-semibold text-slate-700 cursor-pointer">
                        Tampilkan di website publik secara aktif
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="editModalOpen = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold shadow-md shadow-amber-600/30 transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
