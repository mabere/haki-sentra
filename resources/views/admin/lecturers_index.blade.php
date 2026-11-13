@extends('layouts.app', ['title' => 'Data Dosen', 'header' => 'Data Dosen & Manajemen Akun'])

@section('content')
    <div class="space-y-6" x-data="{
        createModalOpen: false,
        editModalOpen: false,
        editData: {
            id: null,
            name: '',
            email: '',
            nidn: '',
            nip: '',
            department_id: '',
            front_title: '',
            back_title: '',
            academic_rank: 'Tenaga Pengajar',
            phone: '',
            sinta_id: '',
            scopus_id: '',
            google_scholar_id: '',
            orcid_id: ''
        },
        openEdit(lecturer) {
            this.editData = {
                id: lecturer.id,
                name: lecturer.user ? lecturer.user.name : '',
                email: lecturer.user ? lecturer.user.email : '',
                nidn: lecturer.nidn || '',
                nip: lecturer.nip || '',
                department_id: lecturer.department_id || '',
                front_title: lecturer.front_title || '',
                back_title: lecturer.back_title || '',
                academic_rank: lecturer.academic_rank || 'Tenaga Pengajar',
                phone: lecturer.phone || '',
                sinta_id: lecturer.sinta_id || '',
                scopus_id: lecturer.scopus_id || '',
                google_scholar_id: lecturer.google_scholar_id || '',
                orcid_id: lecturer.orcid_id || ''
            };
            this.editModalOpen = true;
        }
    }">

        <!-- Top Action & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Data Dosen & Manajemen Akun SIM-LPPM</h1>
                <p class="text-sm text-slate-500">Kelola master data pengajar, nomor induk (NIDN/NIP), prodi, ID
                    SINTA/Scopus, serta status akun login.</p>
            </div>

            <div class="flex items-center gap-2.5">
                <form method="POST" action="{{ route('admin.lecturers.sync-all') }}" onsubmit="return confirm('Jalankan proses pemutakhiran dan sinkronisasi skor SINTA & Google Scholar untuk seluruh dosen?')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-xs transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Sinkronkan Semua Data Dosen</span>
                    </button>
                </form>

                <button type="button" @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-600/30 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Tambah Dosen Baru</span>
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Dosen Terdata</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total_lecturers'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Akun Dosen Aktif</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-0.5">{{ $stats['active_accounts'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Lektor Kepala & Guru Besar</p>
                    <p class="text-2xl font-extrabold text-purple-600 mt-0.5">{{ $stats['lektor_kepala'] }}</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.lecturers.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama dosen, NIDN, NIP, atau email..."
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="w-full sm:w-60">
                    <select name="department_id"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Semua Program Studi</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->faculty->code ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition shrink-0 cursor-pointer">
                    Filter Data
                </button>
                @if (request()->hasAny(['q', 'department_id']))
                    <a href="{{ route('admin.lecturers.index') }}"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Lecturers Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-sm font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama Lengkap & Kontak</th>
                            <th class="py-3.5 px-4">NIDN / NIP</th>
                            <th class="py-3.5 px-4">Program Studi</th>
                            <th class="py-3.5 px-4">Jabatan Akademik</th>
                            <th class="py-3.5 px-4 text-center">Sitasi Ilmiah</th>
                            <th class="py-3.5 px-4 text-center">Status Akun</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($lecturers as $index => $lec)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4 text-center text-slate-400 font-medium">
                                    {{ $lecturers->firstItem() + $index }}
                                </td>

                                <!-- Nama & Kontak -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-sm shrink-0 overflow-hidden border border-slate-200">
                                            @if ($lec->user && $lec->user->photo_url)
                                                <img src="{{ $lec->user->photo_url }}" alt="{{ $lec->full_name }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ $lec->user ? $lec->user->initials() : 'DS' }}
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $lec->full_name }}</span>
                                            <span
                                                class="text-sm text-slate-500 block">{{ $lec->user->email ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- NIDN / NIP -->
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-sm">
                                    <span class="font-bold text-slate-800 block">NIDN: {{ $lec->nidn ?? '-' }}</span>
                                    <span class="text-slate-400 block">NIP: {{ $lec->nip ?? '-' }}</span>
                                </td>

                                <!-- Prodi -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span
                                        class="font-semibold text-slate-900 block">{{ $lec->department->name ?? '-' }}</span>
                                    <span
                                        class="text-sm text-slate-400 block">{{ $lec->department->faculty->name ?? '-' }}</span>
                                </td>

                                <!-- Jabatan Akademik -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] sm:text-sm font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $lec->academic_rank ?? 'Tenaga Pengajar' }}
                                    </span>
                                </td>

                                <!-- Profil Sitasi SINTA & Scopus & Scholar -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex flex-col items-center gap-1 font-mono text-[10px] sm:text-sm">
                                        <div class="flex items-center gap-1.5">
                                            @if ($lec->sinta_id)
                                                <a href="{{ $lec->sinta_url }}" target="_blank"
                                                    class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold hover:bg-emerald-100 transition"
                                                    title="SINTA ID: {{ $lec->sinta_id }} (Overall: {{ $lec->sinta_score_overall }}, 3Yr: {{ $lec->sinta_score_3year }})">
                                                    SINTA: {{ $lec->sinta_score_overall ? number_format($lec->sinta_score_overall) : $lec->sinta_id }}
                                                </a>
                                            @endif
                                            @if ($lec->google_scholar_id)
                                                <a href="{{ $lec->scholar_url }}" target="_blank"
                                                    class="px-2 py-0.5 rounded bg-blue-50 text-blue-800 border border-blue-200 font-semibold hover:bg-blue-100 transition"
                                                    title="Google Scholar: {{ $lec->google_scholar_citations }} sitasi, h-index {{ $lec->google_scholar_h_index }}">
                                                    Scholar: {{ $lec->google_scholar_citations ? number_format($lec->google_scholar_citations) : 'ID' }}
                                                </a>
                                            @endif
                                            @if ($lec->scopus_id)
                                                <a href="{{ $lec->scopus_url }}" target="_blank"
                                                    class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 font-semibold hover:bg-amber-100 transition"
                                                    title="Scopus Author ID: {{ $lec->scopus_id }}">
                                                    Scopus
                                                </a>
                                            @endif
                                        </div>
                                        @if (!$lec->sinta_id && !$lec->scopus_id && !$lec->google_scholar_id)
                                            <span class="text-slate-400 italic text-sm">-</span>
                                        @endif
                                        @if ($lec->last_synced_at)
                                            <span class="text-[9px] text-slate-400 font-sans">Sync: {{ $lec->last_synced_at->format('d/m/y') }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Akun -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if ($lec->user && $lec->user->is_active)
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] sm:text-sm font-bold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] sm:text-sm font-bold bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <form method="POST" action="{{ route('admin.lecturers.sync-academic', $lec) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-sm transition cursor-pointer flex items-center gap-1"
                                                title="Sinkronkan SINTA & Scholar dosen ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span>Sync</span>
                                            </button>
                                        </form>

                                        <button type="button" @click='openEdit(@json($lec))'
                                            class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-sm transition cursor-pointer">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.lecturers.toggle-status', $lec) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-2.5 py-1 rounded-lg {{ $lec->user && $lec->user->is_active ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} font-semibold text-sm transition cursor-pointer"
                                                title="{{ $lec->user && $lec->user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                {{ $lec->user && $lec->user->is_active ? 'Suspend' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-sm text-slate-400">
                                    Tidak ada data dosen yang sesuai pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($lecturers->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $lecturers->links() }}
                </div>
            @endif
        </div>

        <!-- MODAL TAMBAH DOSEN BARU -->
        <div x-show="createModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
            style="display: none;">
            <div class="bg-white rounded-2xl border border-slate-200 max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto"
                @click.outside="createModalOpen = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Dosen &amp; Akun Pengguna Baru</h3>
                    <button @click="createModalOpen = false"
                        class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.lecturers.store') }}" class="space-y-4 text-sm">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Nama Lengkap (Tanpa Gelar) *</label>
                        <input type="text" name="name" required placeholder="Contoh: Muhammad Ilham"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Gelar Depan</label>
                            <input type="text" name="front_title" placeholder="Contoh: Dr., Ir., Prof."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Gelar Belakang</label>
                            <input type="text" name="back_title" placeholder="Contoh: M.Kom., Ph.D."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">NIDN *</label>
                            <input type="text" name="nidn" required placeholder="Contoh: 0015088501"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">NIP</label>
                            <input type="text" name="nip" placeholder="Contoh: 198508152010121001"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Program Studi *</label>
                            <select name="department_id" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}
                                        ({{ $dept->faculty->code ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Jabatan Akademik *</label>
                            <select name="academic_rank"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                                <option value="Tenaga Pengajar">Tenaga Pengajar</option>
                                <option value="Asisten Ahli">Asisten Ahli</option>
                                <option value="Lektor">Lektor</option>
                                <option value="Lektor Kepala">Lektor Kepala</option>
                                <option value="Guru Besar">Guru Besar</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Email *</label>
                            <input type="email" name="email" required placeholder="dosen@kampus.ac.id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Password awal (minimal 12 karakter)</label>
                            <input type="password" name="password" required minlength="12" maxlength="128" autocomplete="new-password" placeholder="Buat password sementara yang kuat"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">SINTA ID</label>
                            <input type="text" name="sinta_id" placeholder="Contoh: 6012345"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Scopus Author ID</label>
                            <input type="text" name="scopus_id" placeholder="Contoh: 57210987654"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">Google Scholar ID</label>
                            <input type="text" name="google_scholar_id" placeholder="Contoh: ABCDE12345"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="text-sm block font-semibold text-slate-700 mb-1.5">ORCID ID</label>
                            <input type="text" name="orcid_id" placeholder="0000-0002-1234-5678"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm block font-semibold text-slate-700 mb-1.5">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" placeholder="Contoh: 081234567890"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="createModalOpen = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/30 transition cursor-pointer">Simpan
                            Dosen Baru</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT DOSEN -->
        <div x-show="editModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
            style="display: none;">
            <div class="bg-white rounded-2xl border border-slate-200 max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto"
                @click.outside="editModalOpen = false">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                    <h3 class="text-sm font-bold text-slate-900">Perbarui Data Dosen</h3>
                    <button @click="editModalOpen = false"
                        class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                </div>

                <form method="POST" :action="`{{ url('admin/dosen') }}/${editData.id}`" class="space-y-4 text-sm">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Nama Lengkap (Tanpa Gelar) *</label>
                        <input type="text" name="name" x-model="editData.name" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Gelar Depan</label>
                            <input type="text" name="front_title" x-model="editData.front_title"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Gelar Belakang</label>
                            <input type="text" name="back_title" x-model="editData.back_title"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">NIDN *</label>
                            <input type="text" name="nidn" x-model="editData.nidn" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">NIP</label>
                            <input type="text" name="nip" x-model="editData.nip"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Program Studi *</label>
                            <select name="department_id" x-model="editData.department_id" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}
                                        ({{ $dept->faculty->code ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Jabatan Akademik *</label>
                            <select name="academic_rank" x-model="editData.academic_rank"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                                <option value="Tenaga Pengajar">Tenaga Pengajar</option>
                                <option value="Asisten Ahli">Asisten Ahli</option>
                                <option value="Lektor">Lektor</option>
                                <option value="Lektor Kepala">Lektor Kepala</option>
                                <option value="Guru Besar">Guru Besar</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Email Akun *</label>
                            <input type="email" name="email" x-model="editData.email" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Reset Password (Kosongkan jika tidak
                                ganti)</label>
                            <input type="password" name="password" minlength="12" maxlength="128" autocomplete="new-password" placeholder="Minimal 12 karakter untuk mengganti"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">SINTA ID</label>
                            <input type="text" name="sinta_id" x-model="editData.sinta_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Scopus Author ID</label>
                            <input type="text" name="scopus_id" x-model="editData.scopus_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">Google Scholar ID</label>
                            <input type="text" name="google_scholar_id" x-model="editData.google_scholar_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5">ORCID ID</label>
                            <input type="text" name="orcid_id" x-model="editData.orcid_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" x-model="editData.phone"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-hidden transition">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="editModalOpen = false"
                            class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition cursor-pointer">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md shadow-indigo-600/30 transition cursor-pointer">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
