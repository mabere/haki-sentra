@extends('layouts.app', ['title' => 'Karya HKI', 'header' => 'Manajemen Hak Kekayaan Intelektual (HKI)'])

@section('content')
    <div class="space-y-6">

        <!-- Top Action & Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Daftar Hak Kekayaan Intelektual (HKI)</h1>
                <p class="text-sm text-slate-500">Kelola Hak Cipta, Paten, Merek Dagang, dan Desain Industri hasil karya
                    Anda.</p>
            </div>

            <a href="{{ route('dosen.ipr.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm shadow-md shadow-emerald-600/30 transition duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Daftarkan Karya HKI Baru</span>
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <form method="GET" action="{{ route('dosen.ipr.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">

                <!-- Search Keyword -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-500 mb-1">Cari Judul /
                        No. Permohonan / No. Sertifikat</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Contoh: Sistem Cerdas, EC002025..., 000789..."
                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-500 mb-1">Jenis
                        HKI</label>
                    <select name="type"
                        class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <option value="all">Semua Jenis</option>
                        <option value="hak_cipta" {{ request('type') === 'hak_cipta' ? 'selected' : '' }}>Hak Cipta</option>
                        <option value="paten" {{ request('type') === 'paten' ? 'selected' : '' }}>Paten Biasa</option>
                        <option value="paten_sederhana" {{ request('type') === 'paten_sederhana' ? 'selected' : '' }}>Paten
                            Sederhana</option>
                        <option value="merek" {{ request('type') === 'merek' ? 'selected' : '' }}>Merek Dagang</option>
                        <option value="desain_industri" {{ request('type') === 'desain_industri' ? 'selected' : '' }}>Desain
                            Industri</option>
                    </select>
                </div>

                <!-- Status DJKI Filter -->
                <div>
                    <label class="block text-sm font-semibold uppercase tracking-wider text-slate-500 mb-1">Status
                        DJKI</label>
                    <select name="djki_status"
                        class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <option value="all">Semua Status DJKI</option>
                        <option value="draft" {{ request('djki_status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diajukan" {{ request('djki_status') === 'diajukan' ? 'selected' : '' }}>Diajukan
                        </option>
                        <option value="pemeriksaan_formal"
                            {{ request('djki_status') === 'pemeriksaan_formal' ? 'selected' : '' }}>Pemeriksaan Formal
                        </option>
                        <option value="pemeriksaan_substantif"
                            {{ request('djki_status') === 'pemeriksaan_substantif' ? 'selected' : '' }}>Pemeriksaan
                            Substantif</option>
                        <option value="granted" {{ request('djki_status') === 'granted' ? 'selected' : '' }}>Diberi /
                            Granted</option>
                        <option value="ditolak" {{ request('djki_status') === 'ditolak' ? 'selected' : '' }}>Ditolak
                        </option>
                    </select>
                </div>

                <!-- Actions Filter -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold shadow-xs transition">
                        Filter
                    </button>
                    @if (request()->anyFilled(['q', 'type', 'djki_status', 'verification_status']))
                        <a href="{{ route('dosen.ipr.index') }}"
                            class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition"
                            title="Reset filter">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- IPR Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-sm font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Judul Ciptaan / Invensi</th>
                            <th class="py-3.5 px-4">Jenis & Pemegang Hak</th>
                            <th class="py-3.5 px-4">Nomor DJKI</th>
                            <th class="py-3.5 px-4">Tim Inventor (% Kontribusi)</th>
                            <th class="py-3.5 px-4 text-center">Sertifikat</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($iprs as $index => $ipr)
                            <tr class="hover:bg-slate-50/70 transition">

                                <!-- Number -->
                                <td class="py-4 px-4 text-center text-slate-400 font-medium">
                                    {{ $iprs->firstItem() + $index }}
                                </td>

                                <!-- Title & Subtype -->
                                <td class="py-4 px-4 max-w-sm">
                                    <a href="{{ route('dosen.ipr.show', $ipr) }}"
                                        class="font-bold text-slate-900 hover:text-emerald-600 leading-snug line-clamp-2 transition">
                                        {{ $ipr->title }}
                                    </a>
                                    @if ($ipr->sub_type)
                                        <div class="mt-1 text-sm text-slate-500">
                                            <span class="font-semibold text-slate-700">Sub-jenis:</span>
                                            {{ $ipr->sub_type }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Type & Holder -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span
                                            class="inline-block px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ str_replace('_', ' ', $ipr->ipr_type) }}
                                        </span>
                                        <p class="text-sm text-slate-500 truncate max-w-[140px]">
                                            Hak: {{ $ipr->rights_holder }}
                                        </p>
                                    </div>
                                </td>

                                <!-- DJKI Numbers -->
                                <td class="py-4 px-4 whitespace-nowrap text-sm">
                                    @if ($ipr->registration_number)
                                        <div class="font-bold text-slate-900">
                                            No: {{ $ipr->registration_number }}
                                        </div>
                                    @endif
                                    @if ($ipr->application_number)
                                        <div class="text-slate-500">
                                            Permohonan: {{ $ipr->application_number }}
                                        </div>
                                    @endif
                                    @if ($ipr->grant_date)
                                        <div class="text-slate-400 text-[10px] sm:text-sm">
                                            Terbit: {{ $ipr->grant_date->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Inventors List -->
                                <td class="py-4 px-4 max-w-xs">
                                    <ul class="space-y-1 text-sm">
                                        @foreach ($ipr->inventors->take(3) as $inv)
                                            <li class="flex items-center justify-between gap-2 text-slate-700">
                                                <div class="flex items-center gap-1 truncate">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full {{ $inv->role === 'inventor_utama' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                                    <span class="truncate">{{ $inv->inventor_name }}</span>
                                                    @if ($inv->inventor_type === 'student')
                                                        <span
                                                            class="text-[10px] sm:text-sm px-1 py-0.2 rounded bg-amber-100 text-amber-800 font-medium">Mhs</span>
                                                    @endif
                                                </div>
                                                @if ($inv->contribution_percentage)
                                                    <span class="text-sm font-bold text-slate-400 shrink-0">
                                                        {{ $inv->contribution_percentage }}%
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                        @if ($ipr->inventors->count() > 3)
                                            <li class="text-sm text-slate-400 italic">
                                                + {{ $ipr->inventors->count() - 3 }} inventor lainnya
                                            </li>
                                        @endif
                                    </ul>
                                </td>

                                <!-- Certificate PDF -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if ($ipr->certificate_file)
                                        <a href="{{ route('dosen.ipr.file', [$ipr, 'certificate']) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-sm font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor"
                                                view0 20 20">
                                                <path
                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                            </svg>
                                            <span>Sertifikat</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-sm italic">-</span>
                                    @endif
                                </td>

                                <!-- Status DJKI & Verification -->
                                <td class="py-4 px-4 text-center whitespace-nowrap space-y-1">
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] sm:text-sm font-bold uppercase {{ $ipr->djki_status === 'granted' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-50 text-blue-700' }}">
                                            {{ strtoupper($ipr->djki_status) }}
                                        </span>
                                    </div>
                                    <div>
                                        @php
                                            $vStyles = [
                                                'approved' => 'text-emerald-600 font-bold',
                                                'pending' => 'text-amber-600',
                                                'revision' => 'text-rose-600 font-bold',
                                                'rejected' => 'text-slate-500',
                                            ];
                                            $vLabels = [
                                                'approved' => '✓ Disetujui',
                                                'pending' => '⏳ Menunggu Validasi',
                                                'revision' => '⚠️ Perlu Revisi',
                                                'rejected' => '✕ Ditolak',
                                            ];
                                        @endphp
                                        <span
                                            class="text-sm {{ $vStyles[$ipr->verification_status] ?? 'text-slate-500' }}">
                                            {{ $vLabels[$ipr->verification_status] ?? $ipr->verification_status }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('dosen.ipr.show', $ipr) }}" title="Lihat Detail"
                                            class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-slate-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if ($ipr->verification_status !== 'approved' || auth()->user()->isAdminLppm())
                                            <a href="{{ route('dosen.ipr.edit', $ipr) }}" title="Edit Data"
                                                class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-slate-100 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('dosen.ipr.destroy', $ipr) }}"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data HKI ini?');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Karya HKI"
                                                    class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div
                                            class="h-12 w-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-800">Belum ada karya HKI terdaftar</p>
                                        <p class="text-sm text-slate-500">Daftarkan Hak Cipta, Paten, Merek, atau Desain
                                            Industri Anda untuk pencatatan dan klaim insentif.</p>
                                        <a href="{{ route('dosen.ipr.create') }}"
                                            class="inline-block mt-2 px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold">
                                            + Daftarkan Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($iprs->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $iprs->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
