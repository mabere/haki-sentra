@extends('layouts.app', ['title' => 'Master Data', 'header' => 'Master Data & Konfigurasi Tarif'])

@section('content')
<div class="space-y-6" x-data="{
    activeTab: '{{ $tab }}',
    editModalOpen: false,
    schemeModalOpen: false,
    facultyModalOpen: false,
    deptModalOpen: false,
    editScheme: {
        id: null,
        name: '',
        category: 'publication',
        tier: '',
        amount: 0,
        fiscal_year: {{ date('Y') }},
        description: '',
        is_active: true
    },
    openEdit(scheme) {
        this.editScheme = {
            id: scheme.id,
            name: scheme.name,
            category: scheme.category,
            tier: scheme.tier || '',
            amount: scheme.amount,
            fiscal_year: scheme.fiscal_year || {{ date('Y') }},
            description: scheme.description || '',
            is_active: Boolean(scheme.is_active)
        };
        this.editModalOpen = true;
    }
}">

    <!-- Header Title & Tab Switcher -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Master Data & Pengaturan Tarif LPPM</h1>
            <p class="text-sm text-slate-500">Kelola skema insentif publikasi & HKI, plafon anggaran, serta struktur fakultas dan program studi.</p>
        </div>

        <!-- Tab Buttons -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-200/80 rounded-xl text-sm font-semibold">
            <button type="button"
                    @click="activeTab = 'schemes'"
                    class="px-3.5 py-2 rounded-lg transition-all"
                    :class="activeTab === 'schemes' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'">
                Tarif &amp; Skema Insentif ({{ $stats['total_schemes'] }})
            </button>
            <button type="button"
                    @click="activeTab = 'faculties'"
                    class="px-3.5 py-2 rounded-lg transition-all"
                    :class="activeTab === 'faculties' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'">
                Fakultas &amp; Prodi ({{ $stats['total_departments'] }})
            </button>
        </div>
    </div>

    <!-- Metric Overview Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Total Skema</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total_schemes'] }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold bg-indigo-50 text-indigo-700">Skema</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Skema Aktif</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-emerald-600">{{ $stats['active_schemes'] }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold bg-emerald-50 text-emerald-700">Tahun {{ date('Y') }}</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Total Fakultas</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total_faculties'] }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold bg-blue-50 text-blue-700">Fakultas</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 block">Program Studi</span>
            <div class="flex items-center justify-between mt-1">
                <span class="text-2xl font-extrabold text-slate-900">{{ $stats['total_departments'] }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold bg-purple-50 text-purple-700">Jurusan</span>
            </div>
        </div>
    </div>

    <!-- TAB 1: Skema & Tarif Insentif -->
    <div x-show="activeTab === 'schemes'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Daftar Skema & Besaran Insentif Luaran Dosen</h3>
                    <p class="text-sm text-slate-500">Standar tarif pencairan dana insentif publikasi internasional, nasional, paten, dan hak cipta.</p>
                </div>

                <button type="button"
                        @click="schemeModalOpen = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-600/20 transition shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Tarif Baru</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-sm font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Nama Skema Insentif</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Tier / Tingkat</th>
                            <th class="py-3 px-4 text-right">Nominal Tarif</th>
                            <th class="py-3 px-4 text-center">Tahun Anggaran</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($schemes as $index => $scheme)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center text-slate-400 font-semibold">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block leading-snug">{{ $scheme->name }}</span>
                                @if($scheme->description)
                                    <span class="text-sm text-slate-500 mt-0.5 line-clamp-1 italic">{{ $scheme->description }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($scheme->category === 'publication')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] sm:text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Publikasi Ilmiah
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] sm:text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Karya HKI
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-mono font-semibold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-sm">
                                    {{ $scheme->tier ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <span class="font-extrabold text-sm sm:text-base text-indigo-700">
                                    Rp {{ number_format($scheme->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap font-semibold text-slate-600">
                                {{ $scheme->fiscal_year ?? date('Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($scheme->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-bold bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button"
                                            @click='openEdit(@json($scheme))'
                                            class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-sm transition cursor-pointer">
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('admin.master.scheme.destroy', $scheme) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus skema insentif ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-sm transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-sm text-slate-400">
                                Belum ada skema tarif insentif terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: Fakultas & Program Studi -->
    <div x-show="activeTab === 'faculties'" class="grid grid-cols-1 md:grid-cols-12 gap-5" style="display: none;">
        <!-- Fakultas Section -->
        <div class="md:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Daftar Fakultas</h3>
                    <p class="text-sm text-slate-500">{{ $faculties->count() }} Fakultas terdaftar</p>
                </div>
                <button type="button"
                        @click="facultyModalOpen = true"
                        class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition cursor-pointer">
                    + Fakultas
                </button>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($faculties as $fac)
                <div class="py-3 flex items-center justify-between gap-3 text-sm">
                    <div>
                        <span class="font-bold text-slate-900 block">{{ $fac->name }}</span>
                        <span class="text-slate-400 font-mono text-sm">Kode: {{ $fac->code }} &bull; {{ $fac->departments->count() }} Prodi</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold bg-blue-50 text-blue-700 uppercase font-mono">
                        {{ $fac->code }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Program Studi Section -->
        <div class="md:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Daftar Program Studi / Jurusan</h3>
                    <p class="text-sm text-slate-500">{{ $departments->count() }} Program studi aktif</p>
                </div>
                <button type="button"
                        @click="deptModalOpen = true"
                        class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-semibold text-sm transition cursor-pointer">
                    + Program Studi
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-sm font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="py-2.5 px-3">Kode</th>
                            <th class="py-2.5 px-3">Program Studi</th>
                            <th class="py-2.5 px-3">Fakultas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($departments as $dept)
                        <tr>
                            <td class="py-2.5 px-3 font-mono font-bold text-purple-700">{{ $dept->code }}</td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $dept->name }}</td>
                            <td class="py-2.5 px-3 text-slate-500">{{ $dept->faculty->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH SKEMA BARU -->
    <div x-show="schemeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5" @click.outside="schemeModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <h3 class="text-sm font-bold text-slate-900">Tambah Skema Tarif Insentif Baru</h3>
                <button @click="schemeModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.master.scheme.store') }}" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Nama Skema Insentif *</label>
                    <input type="text" name="name" required placeholder="Contoh: Insentif Jurnal Bereputasi Scopus Q1"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Kategori Luaran *</label>
                        <select name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                            <option value="publication">Publikasi Ilmiah</option>
                            <option value="ipr">Hak Kekayaan Intelektual (HKI)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Tier / Tingkat</label>
                        <input type="text" name="tier" placeholder="Contoh: Q1, Q2, S1, paten_granted"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Nominal Insentif (Rp) *</label>
                        <input type="number" name="amount" min="0" step="50000" required placeholder="Contoh: 15000000"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-indigo-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Tahun Anggaran *</label>
                        <input type="number" name="fiscal_year" value="{{ date('Y') }}" min="2020" max="2050" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Keterangan / Kriteria Tambahan</label>
                    <textarea name="description" rows="2" placeholder="Ketentuan khusus penerimaan insentif..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition"></textarea>
                </div>

                <div class="flex items-center gap-2.5 pt-1.5">
                    <input type="checkbox" name="is_active" id="scheme_active" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                    <label for="scheme_active" class="font-semibold text-slate-700 cursor-pointer">Aktifkan skema ini untuk klaim dosen</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="schemeModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/30 transition cursor-pointer">Simpan Tarif Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT SKEMA -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5" @click.outside="editModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                <h3 class="text-sm font-bold text-slate-900">Edit Skema Tarif Insentif</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>

            <form method="POST" :action="`{{ url('admin/master-data/skema') }}/${editScheme.id}`" class="space-y-4 text-sm">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Nama Skema Insentif *</label>
                    <input type="text" name="name" x-model="editScheme.name" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Kategori Luaran *</label>
                        <select name="category" x-model="editScheme.category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                            <option value="publication">Publikasi Ilmiah</option>
                            <option value="ipr">Hak Kekayaan Intelektual (HKI)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Tier / Tingkat</label>
                        <input type="text" name="tier" x-model="editScheme.tier"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Nominal Insentif (Rp) *</label>
                        <input type="number" name="amount" min="0" step="50000" x-model="editScheme.amount" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-indigo-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Tahun Anggaran *</label>
                        <input type="number" name="fiscal_year" x-model="editScheme.fiscal_year" min="2020" max="2050" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Keterangan / Kriteria Tambahan</label>
                    <textarea name="description" rows="2" x-model="editScheme.description"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition"></textarea>
                </div>

                <div class="flex items-center gap-2.5 pt-1.5">
                    <input type="checkbox" name="is_active" id="edit_scheme_active" value="1" x-model="editScheme.is_active" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                    <label for="edit_scheme_active" class="font-semibold text-slate-700 cursor-pointer">Aktifkan skema ini untuk klaim dosen</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/30 transition cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH FAKULTAS -->
    <div x-show="facultyModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-4" @click.outside="facultyModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Tambah Fakultas Baru</h3>
                <button @click="facultyModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.faculty.store') }}" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Nama Fakultas *</label>
                    <input type="text" name="name" required placeholder="Contoh: Fakultas Ilmu Pendidikan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Kode Singkatan *</label>
                    <input type="text" name="code" required placeholder="Contoh: FIP" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition">
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="facultyModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-xs transition cursor-pointer">Simpan Fakultas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH PROGRAM STUDI -->
    <div x-show="deptModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4" style="display: none;">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-4" @click.outside="deptModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Tambah Program Studi Baru</h3>
                <button @click="deptModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.master.department.store') }}" class="space-y-4 text-sm">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Pilih Fakultas *</label>
                    <select name="faculty_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 outline-hidden transition">
                        @foreach($faculties as $f)
                            <option value="{{ $f->id }}">{{ $f->name }} ({{ $f->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Nama Program Studi *</label>
                    <input type="text" name="name" required placeholder="Contoh: Pendidikan Bahasa Inggris" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 outline-hidden transition">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5">Kode Prodi *</label>
                    <input type="text" name="code" required placeholder="Contoh: PBI" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 outline-hidden transition">
                </div>
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="deptModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-xs transition cursor-pointer">Simpan Prodi</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
