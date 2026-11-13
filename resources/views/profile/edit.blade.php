@extends('layouts.app', ['title' => 'Pengaturan Profil', 'header' => 'Pengaturan Profil Pengguna'])

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Section -->
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Profil Pengguna</h1>
        <p class="text-sm md:text-base text-slate-600 mt-1">
            Kelola data pribadi, foto profil akun, dan informasi biodata akademik Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Card 1: Foto Profil & Akun Dasar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Foto Profil &amp; Data Akun</h2>
                    <p class="text-sm text-slate-500">Unggah foto resmi untuk identitas pada sistem dan laporan.</p>
                </div>
            </div>

            <!-- Upload Foto Profil Section -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pt-2" x-data="{ photoPreview: null }">
                <!-- Preview Foto Lingkaran SISTER -->
                <div class="relative shrink-0">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" alt="Preview Foto" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover ring-4 ring-blue-100 shadow-md">
                    </template>
                    <template x-if="!photoPreview">
                        <div>
                            @if($user->photo_url)
                                <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover ring-4 ring-blue-100 shadow-md">
                            @else
                                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-blue-600 text-white flex items-center justify-center text-3xl font-extrabold ring-4 ring-blue-100 shadow-md">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                        </div>
                    </template>
                </div>

                <!-- Kontrol Pilih Foto -->
                <div class="flex-1 space-y-2 text-center sm:text-left">
                    <label class="block text-sm font-semibold text-slate-800">
                        Foto Profil Resmi
                    </label>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-xl">
                        Format yang didukung: PNG, JPG, JPEG, atau WEBP. Ukuran maksimal 2MB. Foto berlatar belakang rapi disarankan untuk keperluan akademik.
                    </p>
                    <div class="pt-2">
                        <input type="file"
                               name="photo"
                               id="photo"
                               accept="image/png, image/jpeg, image/jpg, image/webp"
                               @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }"
                               class="text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:sm:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                    @error('photo')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid Input Identitas Akun -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4">
                <!-- Nama Lengkap -->
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-800">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('name')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Pengguna -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold text-slate-800">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('email')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No Telepon / WhatsApp -->
                <div class="space-y-1.5">
                    <label for="phone" class="block text-sm font-semibold text-slate-800">
                        No. Telepon / WhatsApp
                    </label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           value="{{ old('phone', $user->phone ?? ($user->lecturer->phone ?? '')) }}"
                           placeholder="Contoh: 081234567890"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('phone')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Card 2: Biodata Akademik Dosen (Hanya Jika Role Dosen / Memiliki Relasi Lecturer) -->
        @if($user->lecturer)
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Biodata Dosen &amp; Homebase</h2>
                    <p class="text-sm text-slate-500">Informasi jabatan akademik, afiliasi homebase, dan pengenal publikasi.</p>
                </div>
            </div>

            <!-- Homebase Info Box (Readonly) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-sm">
                <div>
                    <span class="font-bold text-slate-800">Fakultas &amp; Program Studi:</span>
                    <p class="text-slate-600 mt-0.5">
                        {{ $user->lecturer->department->faculty->name ?? 'Fakultas' }} &bull; Program Studi {{ $user->lecturer->department->name ?? '-' }}
                    </p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-sm">
                    Homebase Aktif
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- NIDN -->
                <div class="space-y-1.5">
                    <label for="nidn" class="block text-sm font-semibold text-slate-800">NIDN</label>
                    <input type="text"
                           name="nidn"
                           id="nidn"
                           value="{{ old('nidn', $user->lecturer->nidn) }}"
                           placeholder="Contoh: 0012058001"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('nidn')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIP -->
                <div class="space-y-1.5">
                    <label for="nip" class="block text-sm font-semibold text-slate-800">NIP / NUPTK</label>
                    <input type="text"
                           name="nip"
                           id="nip"
                           value="{{ old('nip', $user->lecturer->nip) }}"
                           placeholder="Nomor Induk Pegawai"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('nip')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jabatan Fungsional -->
                <div class="space-y-1.5">
                    <label for="academic_rank" class="block text-sm font-semibold text-slate-800">Jabatan Akademik</label>
                    <select name="academic_rank"
                            id="academic_rank"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Pilih Jabatan Akademik</option>
                        @foreach(['Tenaga Pendidik', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'] as $rank)
                            <option value="{{ $rank }}" {{ old('academic_rank', $user->lecturer->academic_rank) === $rank ? 'selected' : '' }}>
                                {{ $rank }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gelar Depan -->
                <div class="space-y-1.5">
                    <label for="front_title" class="block text-sm font-semibold text-slate-800">Gelar Depan</label>
                    <input type="text"
                           name="front_title"
                           id="front_title"
                           value="{{ old('front_title', $user->lecturer->front_title) }}"
                           placeholder="Contoh: Dr., Prof., Ir."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Gelar Belakang -->
                <div class="space-y-1.5">
                    <label for="back_title" class="block text-sm font-semibold text-slate-800">Gelar Belakang</label>
                    <input type="text"
                           name="back_title"
                           id="back_title"
                           value="{{ old('back_title', $user->lecturer->back_title) }}"
                           placeholder="Contoh: M.Kom., Ph.D., S.T."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- SINTA ID -->
                <div class="space-y-1.5">
                    <label for="sinta_id" class="block text-sm font-semibold text-slate-800">SINTA ID</label>
                    <input type="text"
                           name="sinta_id"
                           id="sinta_id"
                           value="{{ old('sinta_id', $user->lecturer->sinta_id) }}"
                           placeholder="Contoh: 6012345"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Scopus ID -->
                <div class="space-y-1.5">
                    <label for="scopus_id" class="block text-sm font-semibold text-slate-800">Scopus Author ID</label>
                    <input type="text"
                           name="scopus_id"
                           id="scopus_id"
                           value="{{ old('scopus_id', $user->lecturer->scopus_id) }}"
                           placeholder="Contoh: 57211234567"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Google Scholar ID -->
                <div class="space-y-1.5">
                    <label for="google_scholar_id" class="block text-sm font-semibold text-slate-800">Google Scholar ID</label>
                    <input type="text"
                           name="google_scholar_id"
                           id="google_scholar_id"
                           value="{{ old('google_scholar_id', $user->lecturer->google_scholar_id) }}"
                           placeholder="Contoh: ABCDE12345"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- ORCID ID -->
                <div class="space-y-1.5">
                    <label for="orcid_id" class="block text-sm font-semibold text-slate-800">ORCID ID</label>
                    <input type="text"
                           name="orcid_id"
                           id="orcid_id"
                           value="{{ old('orcid_id', $user->lecturer->orcid_id) }}"
                           placeholder="0000-0002-1234-5678"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>

        <!-- Card 3: Integrasi & Sinkronisasi SINTA & Google Scholar (Standalone Card) -->
        <div class="bg-white rounded-2xl border-2 border-emerald-500/30 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span>Integrasi &amp; Sinkronisasi Repositori Akademik</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 tracking-wider">SINTA &bull; Scholar</span>
                        </h2>
                        <p class="text-sm text-slate-500 mt-0.5">
                            Sinkronkan skor SINTA dan metrik sitasi ilmiah Google Scholar ke profil Anda.
                        </p>
                    </div>
                </div>

                <!-- Tombol Sinkronkan Sekarang -->
                <button type="submit"
                        form="sync-academic-form"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition cursor-pointer shrink-0">
                    <svg class="w-4 h-4 animate-spin-hover" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Tarik Data &amp; Sinkronkan Sekarang</span>
                </button>
            </div>

            <!-- Status Sinkronisasi Terakhir Alert Box -->
            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-sm">
                <div class="flex items-center gap-2 text-emerald-900">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-medium">Status Pemutakhiran Terakhir:</span>
                    <span class="font-bold text-emerald-950">
                        {{ $user->lecturer->last_synced_at ? $user->lecturer->last_synced_at->format('d F Y, H:i') : 'Belum pernah disinkronkan' }}
                    </span>
                </div>
                <span class="text-sm text-slate-500 italic">
                    *Jika baru mengisi SINTA ID atau Scholar ID di atas, gunakan tombol "Simpan & Sinkronkan Sekaligus" di bawah.
                </span>
            </div>

            <!-- 4 Kartu Metrik Ringkas -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">SINTA Score Overall</p>
                    <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ number_format($user->lecturer->sinta_score_overall ?? 0) }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Akumulasi skor seluruh luaran</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">SINTA Score 3-Yr</p>
                    <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ number_format($user->lecturer->sinta_score_3year ?? 0) }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Skor luaran 3 tahun terakhir</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Scholar Citations</p>
                    <p class="text-2xl font-extrabold text-amber-700 mt-1">{{ number_format($user->lecturer->google_scholar_citations ?? 0) }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Total sitasi artikel publikasi</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">h-index / i10-index</p>
                    <p class="text-2xl font-extrabold text-purple-700 mt-1">{{ $user->lecturer->google_scholar_h_index ?? 0 }} / {{ $user->lecturer->google_scholar_i10_index ?? 0 }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Indeks produktivitas &amp; sitasi</p>
                </div>
            </div>

            <!-- Tautan Eksternal ke Profil Publik -->
            <div class="flex flex-wrap items-center gap-2 pt-1 text-sm">
                <span class="text-slate-500 font-semibold">Tautan Cepat Portal:</span>
                @if($user->lecturer->sinta_url)
                    <a href="{{ $user->lecturer->sinta_url }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold border border-emerald-200 inline-flex items-center gap-1.5 transition">
                        <span>Buka Profil SINTA Kemdikbudristek</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
                @if($user->lecturer->scholar_url)
                    <a href="{{ $user->lecturer->scholar_url }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold border border-blue-200 inline-flex items-center gap-1.5 transition">
                        <span>Buka Profil Google Scholar</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
                @if($user->lecturer->scopus_url)
                    <a href="{{ $user->lecturer->scopus_url }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 font-semibold border border-amber-200 inline-flex items-center gap-1.5 transition">
                        <span>Buka Profil Scopus</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Card 3: Keamanan & Ganti Password -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Ganti Password (Opsional)</h2>
                    <p class="text-sm text-slate-500">Kosongkan jika Anda tidak ingin mengubah password akun Anda saat ini.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Password Baru -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-semibold text-slate-800">
                        Password Baru
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           minlength="12" maxlength="128" autocomplete="new-password"
                           placeholder="Minimal 12 karakter"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('password')
                        <p class="text-sm text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-800">
                        Ulangi Password Baru
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           placeholder="Ketik ulang password baru"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2">
            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold text-sm text-center transition">
                Batal
            </a>

            @if($user->lecturer)
            <button type="submit" name="action" value="save_and_sync" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Simpan &amp; Sinkronkan Sekaligus</span>
            </button>
            @endif

            <button type="submit" name="action" value="save" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md shadow-blue-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>

    @if($user->lecturer)
        <form id="sync-academic-form" method="POST" action="{{ route('profile.sync-academic') }}" class="hidden">
            @csrf
        </form>
    @endif

</div>
@endsection
