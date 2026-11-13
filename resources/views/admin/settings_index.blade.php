@extends('layouts.app', ['title' => 'Pengaturan Aplikasi', 'header' => 'Identitas Institusi & Logo'])

@section('content')
<div class="space-y-6" x-data="{
    activeTab: '{{ $activeTab }}',
    logoPreview: null,
    faviconPreview: null,
    sigPreview: null,
    handleLogoChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.logoPreview = URL.createObjectURL(file);
        }
    },
    handleFaviconChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.faviconPreview = URL.createObjectURL(file);
        }
    },
    handleSigChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.sigPreview = URL.createObjectURL(file);
        }
    }
}">

    <!-- Page Header & Tab Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 mb-1">
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Sentra HKI & LPPM</span>
                <span>&bull;</span>
                <span>Konfigurasi Dinamis Sistem</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Aplikasi &amp; Identitas Institusi</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Atur logo, icon, identitas kampus, dan pejabat Kepala LPPM agar seluruh kop surat, sertifikat HKI, dan tampilan aplikasi selalu mutakhir secara dinamis.
            </p>
        </div>

        <!-- Quick Summary Badge -->
        <div class="flex items-center gap-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs self-start lg:self-auto">
            <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-700 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-left text-sm">
                <p class="font-bold text-slate-900 leading-tight">{{ app_setting('head_lppm_name', 'Dr. H. Ahmad Fauzi, M.T.') }}</p>
                <p class="text-[10px] text-slate-500">{{ app_setting('institution_short_name', 'UPS') }} &bull; NIDN: {{ app_setting('head_lppm_nidn', '-') }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-1.5 p-1 bg-slate-200/80 rounded-xl text-sm font-semibold overflow-x-auto">
        <button type="button"
                @click="activeTab = 'branding'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap cursor-pointer"
                :class="activeTab === 'branding' ? 'bg-white text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>1. Logo &amp; Branding</span>
        </button>

        <button type="button"
                @click="activeTab = 'institution'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap cursor-pointer"
                :class="activeTab === 'institution' ? 'bg-white text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>2. Identitas Perguruan Tinggi</span>
        </button>

        <button type="button"
                @click="activeTab = 'leader'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap cursor-pointer"
                :class="activeTab === 'leader' ? 'bg-white text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>3. Kepala LPPM &amp; Pejabat</span>
        </button>

        <button type="button"
                @click="activeTab = 'organization'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all whitespace-nowrap cursor-pointer"
                :class="activeTab === 'organization' ? 'bg-white text-blue-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>4. Lembaga &amp; Ruang Layanan</span>
        </button>
    </div>

    <!-- Main Settings Form -->
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="current_tab" :value="activeTab">

        <!-- ========================================================================= -->
        <!-- TAB 1: LOGO & BRANDING VISUAL                                            -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'branding'" class="space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- 1. Card Upload Logo Utama -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                                    <span>Logo Aplikasi &amp; Institusi</span>
                                    @if(app_setting('app_logo'))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Kustom Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Standar Sistem</span>
                                    @endif
                                </h3>
                                <p class="text-sm text-slate-500 mt-0.5">Tampil pada banner sidebar, navbar, halaman login, landing page, dan kop sertifikat resmi.</p>
                            </div>
                        </div>

                        <!-- Logo Preview Showcase (Light & Dark preview box) -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <!-- Background Terang -->
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 text-center flex flex-col items-center justify-center min-h-[140px]">
                                <span class="text-[10px] font-semibold text-slate-400 mb-2 uppercase tracking-wider">Preview Terang</span>
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" alt="Preview Baru" class="h-16 max-w-full object-contain drop-shadow-xs">
                                </template>
                                <template x-if="!logoPreview">
                                    <img src="{{ app_setting_logo('logo') }}" alt="Logo Saat Ini" class="h-16 max-w-full object-contain drop-shadow-xs">
                                </template>
                            </div>

                            <!-- Background Gelap (Khas Sidebar Sistem) -->
                            <div class="border border-[#172635] rounded-xl p-4 bg-[#203345] text-center flex flex-col items-center justify-center min-h-[140px]">
                                <span class="text-[10px] font-semibold text-slate-400 mb-2 uppercase tracking-wider">Preview di Sidebar</span>
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" alt="Preview Baru" class="h-16 max-w-full object-contain drop-shadow-sm">
                                </template>
                                <template x-if="!logoPreview">
                                    <img src="{{ app_setting_logo('logo') }}" alt="Logo Saat Ini" class="h-16 max-w-full object-contain drop-shadow-sm">
                                </template>
                            </div>
                        </div>

                        <!-- Upload Input -->
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Pilih Berkas Logo Baru</label>
                            <input type="file"
                                   name="app_logo"
                                   accept="image/png, image/jpeg, image/svg+xml, image/webp"
                                   @change="handleLogoChange"
                                   class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1 bg-white">
                            <p class="text-[11px] text-slate-400">Format: PNG transparan, SVG, JPG, WebP. Maksimal 2 MB.</p>
                        </div>
                    </div>

                    @if(app_setting('app_logo'))
                    <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                formaction="{{ route('admin.settings.reset-file', 'logo') }}"
                                onclick="return confirm('Apakah Anda yakin ingin mengembalikan logo ke standar awal sistem?')"
                                class="text-sm text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Kembalikan ke Logo Standar</span>
                        </button>
                    </div>
                    @endif
                </div>

                <!-- 2. Card Upload Favicon (Icon Browser) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                                    <span>Icon / Favicon Browser</span>
                                    @if(app_setting('app_favicon'))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Kustom Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Standar Sistem</span>
                                    @endif
                                </h3>
                                <p class="text-sm text-slate-500 mt-0.5">Icon kecil yang muncul pada tab browser dan shortcut bookmark peramban.</p>
                            </div>
                        </div>

                        <!-- Simulated Browser Tab Preview -->
                        <div class="border border-slate-200 rounded-xl p-4 bg-slate-100 mb-4">
                            <span class="text-[10px] font-semibold text-slate-400 block mb-2 uppercase tracking-wider text-center">Simulasi Tab Browser</span>
                            <div class="max-w-xs mx-auto bg-white rounded-t-lg border-t border-x border-slate-300 px-3 py-1.5 flex items-center gap-2 shadow-xs">
                                <template x-if="faviconPreview">
                                    <img :src="faviconPreview" alt="Favicon Baru" class="w-4 h-4 object-contain shrink-0">
                                </template>
                                <template x-if="!faviconPreview">
                                    <img src="{{ app_setting_logo('favicon') }}" alt="Favicon" class="w-4 h-4 object-contain shrink-0">
                                </template>
                                <span class="text-sm font-medium text-slate-700 truncate max-w-[180px]">
                                    {{ app_setting('app_name', 'SENTRA HKI') }} - Dashboard
                                </span>
                                <span class="text-slate-400 text-sm ml-auto">&times;</span>
                            </div>
                        </div>

                        <!-- Upload Input -->
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Pilih Berkas Favicon Baru</label>
                            <input type="file"
                                   name="app_favicon"
                                   accept=".ico, image/png, image/svg+xml"
                                   @change="handleFaviconChange"
                                   class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1 bg-white">
                            <p class="text-[11px] text-slate-400">Format: .ICO, PNG, SVG (rasio persegi 1:1, disarankan 32x32 atau 64x64 px). Maks 1 MB.</p>
                        </div>
                    </div>

                    @if(app_setting('app_favicon'))
                    <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                formaction="{{ route('admin.settings.reset-file', 'favicon') }}"
                                onclick="return confirm('Kembalikan favicon ke standar awal?')"
                                class="text-sm text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Kembalikan ke Favicon Standar</span>
                        </button>
                    </div>
                    @endif
                </div>

            </div>

            <!-- 3. Form Input Teks Branding -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    <span>Penamaan &amp; Slogan Aplikasi</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Singkat Aplikasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="app_name"
                               value="{{ old('app_name', app_setting('app_name', 'SENTRA HKI')) }}"
                               required
                               placeholder="Contoh: SENTRA HKI atau SIM-LPPM"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Nama singkat yang tampil di header sidebar dan pojok kiri atas.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Lengkap Sistem Informasi
                        </label>
                        <input type="text"
                               name="app_full_name"
                               value="{{ old('app_full_name', app_setting('app_full_name', 'Sistem Informasi Manajemen Kekayaan Intelektual & Publikasi Ilmiah')) }}"
                               placeholder="Nama resmi portal sistem..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Tampil di portal informasi publik dan halaman login.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Tagline / Motto Aplikasi
                        </label>
                        <input type="text"
                               name="app_tagline"
                               value="{{ old('app_tagline', app_setting('app_tagline', 'Melindungi Karya, Mewujudkan Inovasi Mandiri')) }}"
                               placeholder="Contoh: Melindungi Karya, Mewujudkan Inovasi Mandiri"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Slogan yang tampil pada footer, landing page, dan kartu promosi layanan HKI.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: IDENTITAS PERGURUAN TINGGI                                         -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'institution'" class="space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Data Institusi &amp; Legalitas Kampus</span>
                    </h3>
                    <span class="text-[11px] text-slate-500 font-medium">Kop Surat &amp; Administrasi Resmi</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Resmi Institusi (Lengkap) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="institution_legal_name"
                               value="{{ old('institution_legal_name', app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi')) }}"
                               required
                               placeholder="Contoh: Universitas Panca Sakti Bekasi"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Nama resmi perguruan tinggi pada kop surat dinas dan sertifikat verifikasi.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Singkatan Nama Kampus
                        </label>
                        <input type="text"
                               name="institution_short_name"
                               value="{{ old('institution_short_name', app_setting('institution_short_name', 'UPS Bekasi')) }}"
                               placeholder="Contoh: UPS Bekasi / UNPAS / dll"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Tampilan Kampus (Umum) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="institution_name"
                               value="{{ old('institution_name', app_setting('institution_name', 'Universitas Panca Sakti')) }}"
                               required
                               placeholder="Contoh: Universitas Panca Sakti"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Tampil di sidebar bawah nama SENTRA HKI.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1 flex items-center gap-1.5">
                            <span>Kota Domisili Kampus</span>
                            <span class="text-rose-500">*</span>
                            <span class="px-1.5 py-0.2 bg-amber-100 text-amber-800 rounded text-[9px] font-bold">Kritis</span>
                        </label>
                        <input type="text"
                               name="institution_city"
                               value="{{ old('institution_city', app_setting('institution_city', 'Bekasi')) }}"
                               required
                               placeholder="Contoh: Bekasi, Bandung, Jakarta, dll"
                               class="w-full px-3.5 py-2.5 bg-amber-50/40 border border-amber-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        <p class="text-[11px] text-amber-700 font-medium mt-1">Digunakan untuk titimangsa tanggal surat (contoh: "{{ app_setting('institution_city', 'Bekasi') }}, {{ date('d F Y') }}").</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Kode Pos
                        </label>
                        <input type="text"
                               name="institution_postal_code"
                               value="{{ old('institution_postal_code', app_setting('institution_postal_code', '17414')) }}"
                               placeholder="Contoh: 17414"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Alamat Lengkap Kampus
                    </label>
                    <textarea name="institution_address"
                              rows="2"
                              placeholder="Alamat kantor sekretariat LPPM / Rektorat..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">{{ old('institution_address', app_setting('institution_address', 'Jl. Raya Hankam No. 54, Pondok Gede, Kota Bekasi, Jawa Barat')) }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Dicetak pada bagian bawah header kop surat sertifikat dan landing page.</p>
                </div>
            </div>

            <!-- Kontak & Tautan Resmi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Saluran Komunikasi Resmi</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text"
                               name="institution_phone"
                               value="{{ old('institution_phone', app_setting('institution_phone', '(021) 84978888')) }}"
                               placeholder="Contoh: (021) 84978888"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Alamat Email Resmi LPPM
                        </label>
                        <input type="email"
                               name="institution_email"
                               value="{{ old('institution_email', app_setting('institution_email', 'lppm@panca-sakti.ac.id')) }}"
                               placeholder="Contoh: lppm@panca-sakti.ac.id"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Website Resmi Perguruan Tinggi
                        </label>
                        <input type="url"
                               name="institution_website"
                               value="{{ old('institution_website', app_setting('institution_website', 'https://panca-sakti.ac.id')) }}"
                               placeholder="Contoh: https://panca-sakti.ac.id"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: KEPALA LPPM & PEJABAT                                              -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'leader'" class="space-y-6">

            <!-- Banner Penjelasan Pimpinan Dinamis -->
            <div class="rounded-2xl bg-gradient-to-r from-blue-900 to-indigo-900 p-5 text-white shadow-md flex items-start gap-4">
                <div class="p-2.5 rounded-xl bg-white/10 shrink-0">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="space-y-1 text-sm">
                    <h3 class="font-bold text-base text-yellow-300">Pembaruan Pimpinan Otomatis (Anti-Manual Hardcoding)</h3>
                    <p class="text-blue-100 leading-relaxed">
                        Ketika terjadi <strong>pergantian Ketua/Kepala LPPM</strong> atau <strong>Ketua Sentra HKI</strong>, Admin cukup memperbarui data nama, NIDN, dan stempel/tanda tangan di halaman ini. Seluruh dokumen baru, Surat Keterangan HKI terverifikasi, dan kutipan pada halaman depan website akan langsung merefleksikan pejabat baru tanpa perlu mengubah baris kode program.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Data Form Kepala LPPM -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Pejabat Utama: Kepala / Ketua LPPM</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">
                                Nama Lengkap &amp; Gelar Akademik <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="head_lppm_name"
                                   value="{{ old('head_lppm_name', app_setting('head_lppm_name', 'Dr. H. Ahmad Fauzi, M.T.')) }}"
                                   required
                                   placeholder="Contoh: Dr. H. Ahmad Fauzi, M.T."
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            <p class="text-[11px] text-slate-400 mt-1">Dicetak di bawah tanda tangan sertifikat surat keterangan.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">
                                NIDN / NIP Kepala LPPM
                            </label>
                            <input type="text"
                                   name="head_lppm_nidn"
                                   value="{{ old('head_lppm_nidn', app_setting('head_lppm_nidn', '0412057801')) }}"
                                   placeholder="Contoh: 0412057801 / 19780512..."
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">
                                Sebutan Jabatan Resmi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="head_lppm_title"
                                   value="{{ old('head_lppm_title', app_setting('head_lppm_title', 'Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)')) }}"
                                   required
                                   placeholder="Contoh: Ketua LPPM atau Kepala LPPM"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <!-- Pimpinan Sentra HKI (Opsional Jika Berbeda) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3">
                            Pejabat Unit: Ketua Sentra Kekayaan Intelektual (HKI)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">
                                    Nama Ketua Sentra HKI
                                </label>
                                <input type="text"
                                       name="head_sentra_name"
                                       value="{{ old('head_sentra_name', app_setting('head_sentra_name', 'Dr. H. Ahmad Fauzi, M.T.')) }}"
                                       placeholder="Nama Ketua Sentra HKI"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">
                                    NIDN / NIP Ketua Sentra HKI
                                </label>
                                <input type="text"
                                       name="head_sentra_nidn"
                                       value="{{ old('head_sentra_nidn', app_setting('head_sentra_nidn', '0412057801')) }}"
                                       placeholder="NIDN/NIP..."
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">
                                    Sebutan Jabatan
                                </label>
                                <input type="text"
                                       name="head_sentra_title"
                                       value="{{ old('head_sentra_title', app_setting('head_sentra_title', 'Ketua Sentra HKI Universitas')) }}"
                                       placeholder="Contoh: Ketua Sentra HKI Universitas"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Upload Tanda Tangan & Stempel Resmi Digital -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                                <span>Tanda Tangan &amp; Stempel</span>
                                @if(app_setting_signature())
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Cap Elektronik</span>
                                @endif
                            </h3>
                        </div>
                        <p class="text-sm text-slate-500 mb-4">
                            Gambar cap stempel LPPM dan paraf/tanda tangan digital resmi pimpinan yang akan disematkan di atas nama Kepala LPPM pada Surat Keterangan / Sertifikat HKI.
                        </p>

                        <!-- Live Signature Preview Box -->
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 bg-slate-50/70 text-center flex flex-col items-center justify-center min-h-[170px] mb-4 relative overflow-hidden">
                            <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Simulasi Pratinjau Surat</span>

                            <template x-if="sigPreview">
                                <img :src="sigPreview" alt="Tanda Tangan Baru" class="h-20 max-w-full object-contain drop-shadow-xs">
                            </template>

                            <template x-if="!sigPreview">
                                @if(app_setting_signature())
                                    <img src="{{ app_setting_signature() }}" alt="Tanda Tangan Saat Ini" class="h-20 max-w-full object-contain drop-shadow-xs">
                                @else
                                    <div class="space-y-1.5 py-3">
                                        <div class="px-3 py-1.5 border border-emerald-300 bg-emerald-50 text-emerald-800 rounded text-[10px] font-bold tracking-wider uppercase font-mono shadow-2xs">
                                            [ DITANDATANGANI SECARA ELEKTRONIK ]
                                        </div>
                                        <p class="text-[10px] text-slate-400">Belum ada file stempel grafis; menggunakan cap digital otomatis.</p>
                                    </div>
                                @endif
                            </template>
                        </div>

                        <!-- Upload File Input -->
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Unggah Gambar Cap &amp; Tanda Tangan</label>
                            <input type="file"
                                   name="head_lppm_signature"
                                   accept="image/png, image/jpeg, image/webp"
                                   @change="handleSigChange"
                                   class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1 bg-white">
                            <p class="text-[11px] text-slate-400">Disarankan format PNG berlatar belakang transparan (resolusi ~400x160 px). Maksimal 2 MB.</p>
                        </div>
                    </div>

                    @if(app_setting_signature())
                    <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                formaction="{{ route('admin.settings.reset-file', 'signature') }}"
                                onclick="return confirm('Hapus file tanda tangan & stempel grafis dan gunakan cap teks standar?')"
                                class="text-sm text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus Berkas Tanda Tangan</span>
                        </button>
                    </div>
                    @endif
                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- TAB 4: LEMBAGA & RUANG LAYANAN                                            -->
        <!-- ========================================================================= -->
        <div x-show="activeTab === 'organization'" class="space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Unit Kerja &amp; Lokasi Layanan HKI</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Lembaga Induk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="lppm_name"
                               value="{{ old('lppm_name', app_setting('lppm_name', 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)')) }}"
                               required
                               placeholder="Contoh: Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Dicetak pada baris kedua kop surat dan footer sistem.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Unit Sentra HKI <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="sentra_hki_name"
                               value="{{ old('sentra_hki_name', app_setting('sentra_hki_name', 'Sentra Kekayaan Intelektual (Sentra HKI)')) }}"
                               required
                               placeholder="Contoh: Sentra Kekayaan Intelektual (Sentra HKI)"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Lokasi Gedung / Ruang Konsultasi HKI
                        </label>
                        <input type="text"
                               name="lppm_building"
                               value="{{ old('lppm_building', app_setting('lppm_building', 'Gedung LPPM Lantai 2, Kampus Universitas Panca Sakti Bekasi')) }}"
                               placeholder="Contoh: Gedung LPPM Lantai 2, Kampus Utama..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Ditampilkan pada halaman landing page sebagai panduan bagi dosen/mahasiswa yang ingin konsultasi offline.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- BOTTOM STICKY ACTION BAR                                                  -->
        <!-- ========================================================================= -->
        <div class="sticky bottom-4 z-20 bg-white/95 backdrop-blur-md rounded-2xl border border-slate-300 p-4 shadow-lg flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-slate-600">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="hidden sm:inline">Perubahan akan langsung berpengaruh ke seluruh modul aplikasi, halaman publik, dan cetak sertifikat.</span>
                <span class="sm:hidden font-semibold">Siap menyimpan</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 hover:shadow-lg transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </div>

    </form>

</div>
@endsection
