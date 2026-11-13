<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Sistem - {{ app_setting('app_name', 'SIM-HKIPUB') }} |
        {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ app_setting_logo('favicon') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="min-h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col justify-between py-10 px-4 sm:px-6"
    x-data="{ helpModalOpen: false, showPassword: false }">


    <!-- Centered Login Container (Slightly Wider: max-w-lg) -->
    <div class="w-full max-w-lg mx-auto my-auto">

        <!-- Header Branding -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block group mb-3">
                <img src="{{ app_setting_logo('logo') }}"
                    alt="Logo {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}"
                    class="h-16 w-16 mx-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                {{ app_setting('app_name', 'SIM-HKIPUB') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-7 sm:p-9 shadow-xl shadow-slate-200/50">

            <div class="flex items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-700 transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Flash Message: Info (e.g. Logout) -->
            @if (session('info'))
                <div
                    class="mb-4 rounded-xl bg-blue-50 border border-blue-200 p-3 text-xs sm:text-sm text-blue-800 flex items-start gap-2 shadow-2xs">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
            @endif

            <!-- Flash Message: Success -->
            @if (session('success'))
                <div
                    class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-xs sm:text-sm text-emerald-800 flex items-start gap-2 shadow-2xs">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Alert -->
            @if ($errors->has('login_id'))
                <div
                    class="mb-5 rounded-xl bg-rose-50 border border-rose-200 p-3 text-xs sm:text-sm text-rose-800 flex items-start gap-2 shadow-2xs">
                    <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ $errors->first('login_id') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <!-- Login ID Input (Email or NIDN) -->
                <div>
                    <label for="login_id" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Email atau NIDN
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="login_id" id="login_id" required autofocus
                            value="{{ old('login_id') }}" placeholder="Email atau NIDN"
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 shadow-2xs transition">
                    </div>
                </div>

                <!-- Password Input with Toggle -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-700">
                            Kata Sandi
                        </label>
                        <button type="button" @click="helpModalOpen = true"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                            Lupa kata sandi?
                        </button>
                    </div>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                            placeholder="Kata Sandi"
                            class="w-full pl-10 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 shadow-2xs transition">

                        <button type="button" @click="showPassword = !showPassword"
                            title="Tampilkan / Sembunyikan Kata Sandi"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer transition">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs sm:text-sm pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 hover:text-slate-800">
                        <input type="checkbox" name="remember"
                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 shadow-2xs">
                        <span>Remember Me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm shadow-md shadow-blue-600/25 hover:shadow-lg hover:shadow-blue-600/35 transition duration-150 flex items-center justify-center gap-2 cursor-pointer">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                </div>
            </form>

        </div>

    </div>

    <!-- Footer Copyright -->
    <footer class="w-full max-w-lg mx-auto text-center text-xs text-slate-500 pt-6">
        &copy; {{ date('Y') }} {{ app_setting('app_name', 'SIM-HKIPUB') }} &bull;
        {{ app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi') }}.
    </footer>

    <!-- Help & Password Recovery Modal -->
    <div x-show="helpModalOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        style="display: none;">

        <div @click.away="helpModalOpen = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-200 relative">

            <div class="flex items-start justify-between pb-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Bantuan Akses Akun</h3>
                        <p class="text-xs text-slate-500">Panduan Lupa Kata Sandi &amp; Aktivasi</p>
                    </div>
                </div>

                <button type="button" @click="helpModalOpen = false"
                    class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="py-4 space-y-3 text-xs sm:text-sm text-slate-600 leading-relaxed">
                <p>
                    Akun dosen dan pengelola diverifikasi oleh LPPM berdasarkan data pangkalan NIDN/NIP.
                </p>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1 text-xs">
                    <div class="font-semibold text-slate-800">{{ app_setting('lppm_name', 'LPPM') }}</div>
                    <div class="text-slate-600 font-mono">Telepon:
                        {{ app_setting('institution_phone', '(021) 84978888') }}</div>
                    <div class="text-slate-600 font-mono">Email:
                        {{ app_setting('institution_email', 'lppm@panca-sakti.ac.id') }}</div>
                </div>
            </div>

            <div class="pt-3.5 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="mailto:{{ app_setting('institution_email', 'lppm@panca-sakti.ac.id') }}?subject=Bantuan%20Akses%20Login%20SIM-HKIPUB"
                    class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition">
                    Kirim Email ke LPPM
                </a>
                <button type="button" @click="helpModalOpen = false"
                    class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>

    </div>

    @livewireScripts
</body>

</html>
