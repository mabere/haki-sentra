<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ app_setting('app_name', 'SIM-HKIPUB') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ app_setting_logo('favicon') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full font-sans antialiased text-slate-800 flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" style="display: none;"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-[#203345] text-slate-100 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-[#172635]">

        <!-- App Logo & Brand (SISTER Style Banner with Official Logo) -->
        <div
            class="h-16 flex items-center px-4 border-b border-[#172635] bg-gradient-to-r from-sky-100 via-sky-200 to-sky-100">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ app_setting_logo('logo') }}"
                    alt="Logo {{ app_setting('institution_name', 'Universitas Panca Sakti') }}"
                    class="h-10 w-10 object-contain shrink-0 drop-shadow-xs">
                <div class="flex flex-col">
                    <span
                        class="text-blue-900 font-black tracking-wider text-base font-sans leading-none">{{ app_setting('app_name', 'SENTRA HKI') }}</span>
                    <span
                        class="text-[10px] text-blue-700 font-bold tracking-tight mt-0.5">{{ app_setting('institution_name', 'Universitas Panca Sakti') }}</span>
                </div>
                <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-600 hover:text-slate-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mini Header Box: Tanggal & Sapaan Dosen (Gambar SISTER) -->
        <div class="px-4 py-3 border-b border-[#172635] bg-[#1a2b3b]/60 space-y-2">
            <!-- Box Tanggal Putih Khas SISTER -->
            <div class="bg-white text-slate-800 text-[11px] font-mono font-bold py-1 px-3 rounded shadow-2xs border border-slate-200 text-center"
                x-data="{ clock: '{{ date('d/m/Y') }} - {{ date('H.i.s') }}' }" x-init="setInterval(() => {
                    const d = new Date();
                    clock = String(d.getDate()).padStart(2, '0') + '/' + String(d.getMonth() + 1).padStart(2, '0') + '/' + d.getFullYear() + ' - ' + String(d.getHours()).padStart(2, '0') + '.' + String(d.getMinutes()).padStart(2, '0') + '.' + String(d.getSeconds()).padStart(2, '0');
                }, 1000)" x-text="clock">
                {{ date('d/m/Y') }} - {{ date('H.i.s') }}
            </div>
            <!-- Teks Sambutan Dosen -->
            <div class="text-[11px] text-slate-300 leading-tight">
                <div>Selamat datang, <strong
                        class="text-white">{{ auth()->user()->lecturer ? auth()->user()->lecturer->full_name : auth()->user()->name }}</strong>
                </div>
                <div class="text-slate-400 mt-0.5 truncate text-[10px]">
                    {{ auth()->user()->lecturer ? 'Dosen Program Studi ' . (auth()->user()->lecturer->department->name ?? '') : (auth()->user()->isAdminLppm() ? 'Admin Sentra HKI LPPM' : (auth()->user()->isVerifikator() ? 'Verifikator LPPM' : 'Pimpinan Universitas')) }}
                </div>
            </div>
        </div>

        <!-- Navigation Links (SISTER Accordion Group Menu) -->
        @php
            $initialGroup = null;
            if (request()->routeIs('dosen.*')) {
                $initialGroup = 'luaran';
            } elseif (request()->routeIs('verification.publications.*', 'verification.ipr.*', 'admin.incentives.*')) {
                $initialGroup = 'verifikasi';
            } elseif (request()->routeIs('admin.master.*', 'admin.lecturers.*')) {
                $initialGroup = 'master_data';
            } elseif (request()->routeIs('reports.*')) {
                $initialGroup = 'eksekutif';
            } elseif (request()->routeIs('admin.informasi.*', 'informasi.*')) {
                $initialGroup = 'panduan';
            } elseif (request()->routeIs('verification.logs', 'admin.settings.*')) {
                $initialGroup = 'pengaturan';
            }
        @endphp
        <div x-data="{
            activeGroup: @js($initialGroup),
            toggle(group) {
                this.activeGroup = this.activeGroup === group ? null : group;
            }
        }" class="flex-1 overflow-y-auto px-3 py-3 space-y-1">

            <!-- 1. Menu Tunggal: Dashboard -->
            <div>
                <a href="{{ route('dashboard') }}"
                    class="w-full flex items-center gap-3 px-3.5 py-2 rounded-lg text-[13px] font-normal transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-medium shadow-xs' : 'text-slate-300 hover:bg-[#1a2b3b] hover:text-white' }}">
                    <svg class="w-4 h-4 opacity-90 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- 2. Group: Luaran & Pengajuan -->
            @if (auth()->user()->isDosen() || auth()->user()->isAdminLppm())
                <div>
                    <button type="button" @click="toggle('luaran')"
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-[13px] font-normal transition cursor-pointer select-none"
                        :class="activeGroup === 'luaran' ? 'bg-[#1a2b3b] text-white font-medium' :
                            'text-slate-300 hover:bg-[#1a2b3b]/70 hover:text-white'">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <span>Luaran &amp; Pengajuan</span>
                        </div>
                        <span
                            class="text-slate-400 text-sm font-mono transition-transform duration-200 shrink-0 inline-block"
                            :class="activeGroup === 'luaran' ? '-rotate-90 text-white font-bold' : 'rotate-0 text-slate-400'">&lsaquo;</span>
                    </button>

                    <!-- Submenu Luaran & Pengajuan -->
                    <div x-show="activeGroup === 'luaran'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-4 pr-1 py-1 space-y-0.5 ml-5 my-0.5 border-l border-slate-700">
                        <a href="{{ route('dosen.publications.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('dosen.publications.*') ? 'bg-blue-600/30 text-white font-bold border-l-2 border-blue-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Publikasi Ilmiah</span>
                        </a>
                        <a href="{{ route('dosen.ipr.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('dosen.ipr.*') ? 'bg-blue-600/30 text-white font-bold border-l-2 border-blue-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Data HKI &amp; Paten</span>
                        </a>
                        @if (incentive_enabled())
                            <a href="{{ route('dosen.incentives.index') }}"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('dosen.incentives.*') ? 'bg-blue-600/30 text-white font-bold border-l-2 border-blue-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                                <span>Pengajuan Insentif</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 3. Group: Verifikasi LPPM -->
            @if (auth()->user()->isVerifikator() || auth()->user()->isAdminLppm())
                <div>
                    <button type="button" @click="toggle('verifikasi')"
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-[13px] font-normal transition cursor-pointer select-none"
                        :class="activeGroup === 'verifikasi' ? 'bg-[#1a2b3b] text-white font-medium' :
                            'text-slate-300 hover:bg-[#1a2b3b]/70 hover:text-white'">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Verifikasi LPPM</span>
                        </div>
                        <span
                            class="text-slate-400 text-sm font-mono transition-transform duration-200 shrink-0 inline-block"
                            :class="activeGroup === 'verifikasi' ? '-rotate-90 text-white font-bold' :
                                'rotate-0 text-slate-400'">&lsaquo;</span>
                    </button>

                    <!-- Submenu Verifikasi -->
                    <div x-show="activeGroup === 'verifikasi'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-4 pr-1 py-1 space-y-0.5 ml-5 my-0.5 border-l border-slate-700">
                        <a href="{{ route('verification.publications.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('verification.publications.*') ? 'bg-amber-500/20 text-amber-300 font-bold border-l-2 border-amber-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Validasi Publikasi</span>
                        </a>
                        <a href="{{ route('verification.ipr.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('verification.ipr.*') ? 'bg-amber-500/20 text-amber-300 font-bold border-l-2 border-amber-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Validasi HKI</span>
                        </a>
                        @if (auth()->user()->isAdminLppm() && incentive_enabled())
                            <a href="{{ route('admin.incentives.index') }}"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.incentives.*') ? 'bg-amber-500/20 text-amber-300 font-bold border-l-2 border-amber-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                                <span>Pencairan Insentif</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 4. Group: Master Data -->
            @if (auth()->user()->isAdminLppm())
                <div>
                    <button type="button" @click="toggle('master_data')"
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-[13px] font-normal transition cursor-pointer select-none"
                        :class="activeGroup === 'master_data' ? 'bg-[#1a2b3b] text-white font-medium' :
                            'text-slate-300 hover:bg-[#1a2b3b]/70 hover:text-white'">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span>Master Data</span>
                        </div>
                        <span
                            class="text-slate-400 text-sm font-mono transition-transform duration-200 shrink-0 inline-block"
                            :class="activeGroup === 'master_data' ? '-rotate-90 text-white font-bold' :
                                'rotate-0 text-slate-400'">&lsaquo;</span>
                    </button>

                    <!-- Submenu Master Data -->
                    <div x-show="activeGroup === 'master_data'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-4 pr-1 py-1 space-y-0.5 ml-5 my-0.5 border-l border-slate-700">
                        <a href="{{ route('admin.master.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.master.*') ? 'bg-indigo-600/30 text-white font-bold border-l-2 border-indigo-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>{{ incentive_enabled() ? 'Fakultas, Prodi & Tarif' : 'Fakultas & Prodi' }}</span>
                        </a>
                        <a href="{{ route('admin.lecturers.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.lecturers.*') ? 'bg-indigo-600/30 text-white font-bold border-l-2 border-indigo-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Data Dosen &amp; Akun</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- 5. Group: Eksekutif & Laporan -->
            @if (auth()->user()->isPimpinan() || auth()->user()->isAdminLppm() || auth()->user()->isVerifikator())
                <div>
                    <button type="button" @click="toggle('eksekutif')"
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-[13px] font-normal transition cursor-pointer select-none"
                        :class="activeGroup === 'eksekutif' ? 'bg-[#1a2b3b] text-white font-medium' :
                            'text-slate-300 hover:bg-[#1a2b3b]/70 hover:text-white'">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-purple-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Eksekutif &amp; Laporan</span>
                        </div>
                        <span
                            class="text-slate-400 text-sm font-mono transition-transform duration-200 shrink-0 inline-block"
                            :class="activeGroup === 'eksekutif' ? '-rotate-90 text-white font-bold' : 'rotate-0 text-slate-400'">&lsaquo;</span>
                    </button>

                    <!-- Submenu Eksekutif -->
                    <div x-show="activeGroup === 'eksekutif'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-4 pr-1 py-1 space-y-0.5 ml-5 my-0.5 border-l border-slate-700">
                        <a href="{{ route('reports.iku') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('reports.iku') ? 'bg-purple-600/30 text-white font-bold border-l-2 border-purple-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Capaian IKU 3 &amp; 5</span>
                        </a>
                        <a href="{{ route('reports.summary') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('reports.summary') ? 'bg-purple-600/30 text-white font-bold border-l-2 border-purple-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Ekspor BKD &amp; Akreditasi</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- 6. Group: Pusat Informasi (Khusus Admin LPPM - CRUD Dinamis) -->
            @if (auth()->user()->isAdminLppm())
                <div>
                    <button type="button" @click="toggle('panduan')"
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-[13px] font-normal transition cursor-pointer select-none"
                        :class="activeGroup === 'panduan' ? 'bg-[#1a2b3b] text-white font-medium' :
                            'text-slate-300 hover:bg-[#1a2b3b]/70 hover:text-white'">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Pusat Informasi</span>
                        </div>
                        <span
                            class="text-slate-400 text-sm font-mono transition-transform duration-200 shrink-0 inline-block"
                            :class="activeGroup === 'panduan' ? '-rotate-90 text-white font-bold' : 'rotate-0 text-slate-400'">&lsaquo;</span>
                    </button>

                    <!-- Submenu CRUD Pusat Informasi -->
                    <div x-show="activeGroup === 'panduan'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-4 pr-1 py-1 space-y-0.5 ml-5 my-0.5 border-l border-slate-700">
                        <a href="{{ route('admin.informasi.index') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.informasi.*') && (!request()->has('tab') || request()->query('tab') === 'all') ? 'bg-sky-500/20 text-sky-300 font-bold border-l-2 border-sky-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Kelola Semua Konten</span>
                        </a>
                        <a href="{{ route('admin.informasi.index', ['tab' => 'alur_syarat']) }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.informasi.*') && request()->query('tab') === 'alur_syarat' ? 'bg-sky-500/20 text-sky-300 font-bold border-l-2 border-sky-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Alur &amp; SOP Layanan</span>
                        </a>
                        <a href="{{ route('admin.informasi.index', ['tab' => 'template_dokumen']) }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.informasi.*') && request()->query('tab') === 'template_dokumen' ? 'bg-sky-500/20 text-sky-300 font-bold border-l-2 border-sky-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Template Dokumen</span>
                        </a>
                        <a href="{{ route('admin.informasi.index', ['tab' => 'faq']) }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.informasi.*') && request()->query('tab') === 'faq' ? 'bg-sky-500/20 text-sky-300 font-bold border-l-2 border-sky-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Tanya Jawab (FAQ)</span>
                        </a>
                        <a href="{{ route('admin.informasi.index', ['tab' => 'pengumuman']) }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.informasi.*') && request()->query('tab') === 'pengumuman' ? 'bg-sky-500/20 text-sky-300 font-bold border-l-2 border-sky-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Pengumuman Resmi</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- 7. Group: Pengaturan -->
            @if (auth()->user()->isVerifikator() || auth()->user()->isAdminLppm())
                <div>
                    <button type="button" @click="toggle('pengaturan')"
                        class="w-full flex items-center justify-between px-3.5 py-2 rounded-lg text-[13px] font-normal transition cursor-pointer select-none"
                        :class="activeGroup === 'pengaturan' ? 'bg-[#1a2b3b] text-white font-medium' :
                            'text-slate-300 hover:bg-[#1a2b3b]/70 hover:text-white'">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pengaturan</span>
                        </div>
                        <span
                            class="text-slate-400 text-sm font-mono transition-transform duration-200 shrink-0 inline-block"
                            :class="activeGroup === 'pengaturan' ? '-rotate-90 text-white font-bold' :
                                'rotate-0 text-slate-400'">&lsaquo;</span>
                    </button>

                    <!-- Submenu Pengaturan -->
                    <div x-show="activeGroup === 'pengaturan'" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="pl-4 pr-1 py-1 space-y-0.5 ml-5 my-0.5 border-l border-slate-700">
                        <a href="{{ route('verification.logs') }}"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('verification.logs') ? 'bg-blue-600/30 text-white font-bold border-l-2 border-blue-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                            <span>Riwayat Log</span>
                        </a>
                        @if (auth()->user()->isAdminLppm())
                            <a href="{{ route('admin.settings.index') }}"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600/30 text-white font-bold border-l-2 border-blue-400 -ml-[17px] pl-[25px]' : 'text-slate-400 hover:text-white hover:bg-[#1a2b3b]/50' }}">
                                <span>Pengaturan Aplikasi</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- User Profile Card in Sidebar -->
        <a href="{{ route('profile.edit') }}" title="Klik untuk edit profil &amp; foto"
            class="p-3.5 border-t border-[#172635] bg-[#1a2b3b] hover:bg-[#162534] transition flex items-center justify-between group">
            <div class="flex items-center gap-3 overflow-hidden">
                @if (auth()->user()->photo_url)
                    <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}"
                        class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 ring-blue-400">
                @else
                    <div
                        class="h-9 w-9 shrink-0 rounded-full bg-blue-600 border border-blue-400 flex items-center justify-center font-bold text-sm text-white">
                        {{ auth()->user()->initials() }}
                    </div>
                @endif
                <div class="truncate">
                    <p class="text-sm font-semibold text-white truncate group-hover:text-sky-300 transition">
                        {{ auth()->user()->lecturer ? auth()->user()->lecturer->full_name : auth()->user()->name }}</p>
                    <p class="text-[10px] sm:text-sm text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition shrink-0 ml-1" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

        <!-- Top Navbar (SISTER Clean Light Header) -->
        <header
            class="min-h-[4.75rem] py-3.5 px-6 sm:px-8 bg-white/95 backdrop-blur-md border-b border-slate-200/90 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight leading-snug">
                        {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</h1>
                    <nav class="flex items-center gap-1.5 text-sm text-slate-500 font-medium mt-1">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-1 text-slate-500 hover:text-blue-700 transition">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Beranda</span>
                        </a>
                        <svg class="w-3 h-3 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="text-blue-700 font-semibold">{{ $header ?? 'Dashboard' }}</span>
                    </nav>
                </div>
            </div>

            <!-- User Dropdown Menu -->
            @php
                $roleColors = [
                    'dosen' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                    'verifikator' => 'bg-amber-50 text-amber-700 border-amber-200/80',
                    'admin_lppm' => 'bg-blue-50 text-blue-700 border-blue-200/80',
                    'pimpinan' => 'bg-purple-50 text-purple-700 border-purple-200/80',
                ];
                $roleDots = [
                    'dosen' => 'bg-emerald-500',
                    'verifikator' => 'bg-amber-500',
                    'admin_lppm' => 'bg-blue-500',
                    'pimpinan' => 'bg-purple-500',
                ];
                $roleNames = [
                    'dosen' => 'Dosen / Peneliti',
                    'verifikator' => 'Verifikator LPPM',
                    'admin_lppm' => 'Admin Sentra HKI',
                    'pimpinan' => 'Pimpinan Kampus',
                ];
                $currentUser = auth()->user();
                $displayName = $currentUser->lecturer ? $currentUser->lecturer->full_name : $currentUser->name;
                $roleLabel = $roleNames[$currentUser->role] ?? ucfirst($currentUser->role);
            @endphp
            <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false"
                @keydown.escape.window="userMenuOpen = false">
                <button type="button" @click="userMenuOpen = !userMenuOpen" :aria-expanded="userMenuOpen.toString()"
                    class="inline-flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/90 transition shadow-2xs cursor-pointer">
                    @if ($currentUser->photo_url)
                        <img src="{{ $currentUser->photo_url }}" alt="{{ $displayName }}"
                            class="w-7 h-7 rounded-full object-cover ring-1 ring-slate-300 shrink-0">
                    @else
                        <div
                            class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden sm:flex flex-col items-start text-left leading-tight max-w-[180px]">
                        <span class="text-sm font-semibold text-slate-800 truncate w-full">{{ $displayName }}</span>
                        <span class="text-sm text-slate-500 truncate w-full">{{ $roleLabel }}</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-150 shrink-0"
                        :class="userMenuOpen ? 'rotate-180 text-slate-600' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Panel -->
                <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1" style="display: none;"
                    class="absolute right-0 mt-2 w-64 rounded-xl bg-white border border-slate-200 shadow-lg py-2 z-50">

                    <!-- User Identity Header -->
                    <div class="px-4 py-2.5 border-b border-slate-100 space-y-1.5">
                        <div>
                            <div class="text-sm font-semibold text-slate-900 truncate">{{ $displayName }}</div>
                            <div class="text-sm text-slate-500 truncate">{{ $currentUser->email }}</div>
                        </div>
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-medium border {{ $roleColors[$currentUser->role] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            <span
                                class="w-1.5 h-1.5 rounded-full {{ $roleDots[$currentUser->role] ?? 'bg-slate-400' }}"></span>
                            <span>{{ $roleLabel }}</span>
                        </div>
                    </div>

                    <!-- Menu Actions -->
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-700 transition">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pengaturan</span>
                        </a>
                    </div>

                    <div class="border-t border-slate-100 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 transition cursor-pointer text-left">
                                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if (session('success') || session('info') || $errors->any())
            <div class="px-6 sm:px-8 pt-6 max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div
                        class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('info'))
                    <div
                        class="mb-4 rounded-xl bg-blue-50 border border-blue-200 p-4 text-blue-800 flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">{{ session('info') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-4 text-rose-800 shadow-xs">
                        <div class="flex items-center gap-2 font-semibold text-sm mb-1 text-rose-700">
                            <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Terjadi kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-rose-700 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Body Content -->
        <main class="flex-1 px-6 sm:px-8 pt-6 sm:pt-8 pb-12 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-slate-200 bg-white py-4 px-6 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} {{ app_setting('app_name', 'SIM-HKIPUB') }} &bull;
            {{ app_setting('lppm_name', 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)') }}
            {{ app_setting('institution_name', 'Universitas Panca Sakti') }}.
            Dilindungi Hak Cipta.
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
