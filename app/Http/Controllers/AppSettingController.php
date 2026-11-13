<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class AppSettingController extends Controller
{
    /** Clear Laravel's application and optimization caches. */
    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('optimize:clear');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('admin.settings.index')
                ->withErrors(['cache' => 'Cache aplikasi gagal dibersihkan. Silakan coba lagi atau hubungi pengelola hosting.']);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cache aplikasi berhasil dibersihkan.');
    }

    /**
     * Display the application and institution settings dashboard.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'branding');
        if (!in_array($activeTab, ['branding', 'institution', 'leader', 'organization', 'features'])) {
            $activeTab = 'branding';
        }

        $allSettings = AppSetting::all()->keyBy('key');

        return view('admin.settings_index', compact('allSettings', 'activeTab'));
    }

    /**
     * Update application settings and uploaded files.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Branding
            'app_name' => ['required', 'string', 'max:100'],
            'app_full_name' => ['nullable', 'string', 'max:255'],
            'app_tagline' => ['nullable', 'string', 'max:255'],
            'app_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'app_favicon' => ['nullable', 'file', 'mimes:ico,png,svg', 'max:1024'],

            // Institution
            'institution_name' => ['required', 'string', 'max:150'],
            'institution_legal_name' => ['required', 'string', 'max:200'],
            'institution_short_name' => ['nullable', 'string', 'max:50'],
            'institution_city' => ['required', 'string', 'max:100'],
            'institution_address' => ['nullable', 'string', 'max:500'],
            'institution_postal_code' => ['nullable', 'string', 'max:20'],
            'institution_phone' => ['nullable', 'string', 'max:50'],
            'institution_email' => ['nullable', 'email', 'max:100'],
            'institution_website' => ['nullable', 'url', 'max:150'],

            // Organization
            'lppm_name' => ['required', 'string', 'max:200'],
            'sentra_hki_name' => ['required', 'string', 'max:200'],
            'lppm_building' => ['nullable', 'string', 'max:255'],

            // Leaders
            'head_lppm_name' => ['required', 'string', 'max:150'],
            'head_lppm_nidn' => ['nullable', 'string', 'max:50'],
            'head_lppm_title' => ['required', 'string', 'max:200'],
            'head_lppm_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'head_sentra_name' => ['nullable', 'string', 'max:150'],
            'head_sentra_nidn' => ['nullable', 'string', 'max:50'],
            'head_sentra_title' => ['nullable', 'string', 'max:200'],

            // Features
            'feature_incentive_enabled' => ['nullable', 'boolean'],
        ], [
            'app_name.required' => 'Nama singkat aplikasi wajib diisi.',
            'institution_name.required' => 'Nama institusi / perguruan tinggi wajib diisi.',
            'institution_legal_name.required' => 'Nama resmi institusi untuk kop surat wajib diisi.',
            'institution_city.required' => 'Kota institusi wajib diisi untuk titimangsa tanggal surat.',
            'lppm_name.required' => 'Nama lembaga LPPM wajib diisi.',
            'sentra_hki_name.required' => 'Nama unit sentra HKI wajib diisi.',
            'head_lppm_name.required' => 'Nama Kepala LPPM wajib diisi.',
            'head_lppm_title.required' => 'Sebutan jabatan Kepala LPPM wajib diisi.',
            'app_logo.image' => 'File logo harus berupa gambar PNG, JPG, atau WebP.',
            'app_logo.max' => 'Ukuran file logo maksimal 2 MB.',
            'app_favicon.max' => 'Ukuran file favicon maksimal 1 MB.',
            'head_lppm_signature.image' => 'File tanda tangan/stempel harus berupa gambar (PNG direkomendasikan).',
            'head_lppm_signature.max' => 'Ukuran file tanda tangan maksimal 2 MB.',
        ]);

        // Handle File Upload: Logo
        if ($request->hasFile('app_logo')) {
            $oldLogo = AppSetting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('app_logo')->store('settings/logos', 'public');
            AppSetting::set('app_logo', $path, 'branding', 'image', 'Logo Aplikasi');
        }

        // Handle File Upload: Favicon
        if ($request->hasFile('app_favicon')) {
            $oldFavicon = AppSetting::get('app_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $path = $request->file('app_favicon')->store('settings/favicons', 'public');
            AppSetting::set('app_favicon', $path, 'branding', 'image', 'Icon / Favicon Browser');
        }

        // Handle File Upload: Signature / Stamp
        if ($request->hasFile('head_lppm_signature')) {
            $oldSig = AppSetting::get('head_lppm_signature');
            if ($oldSig && Storage::disk('public')->exists($oldSig)) {
                Storage::disk('public')->delete($oldSig);
            }

            $path = $request->file('head_lppm_signature')->store('settings/signatures', 'public');
            AppSetting::set('head_lppm_signature', $path, 'leader', 'image', 'Tanda Tangan & Stempel Resmi Digital');
        }

        // Text & Textarea fields to persist
        $textFields = [
            'app_name' => ['group' => 'branding', 'type' => 'text', 'label' => 'Nama Singkat Aplikasi'],
            'app_full_name' => ['group' => 'branding', 'type' => 'text', 'label' => 'Nama Lengkap Sistem'],
            'app_tagline' => ['group' => 'branding', 'type' => 'text', 'label' => 'Tagline / Slogan Aplikasi'],
            'institution_name' => ['group' => 'institution', 'type' => 'text', 'label' => 'Nama Perguruan Tinggi'],
            'institution_legal_name' => ['group' => 'institution', 'type' => 'text', 'label' => 'Nama Resmi Institusi (Lengkap)'],
            'institution_short_name' => ['group' => 'institution', 'type' => 'text', 'label' => 'Singkatan Nama Kampus'],
            'institution_city' => ['group' => 'institution', 'type' => 'text', 'label' => 'Kota Institusi'],
            'institution_address' => ['group' => 'institution', 'type' => 'textarea', 'label' => 'Alamat Lengkap Kampus'],
            'institution_postal_code' => ['group' => 'institution', 'type' => 'text', 'label' => 'Kode Pos'],
            'institution_phone' => ['group' => 'institution', 'type' => 'text', 'label' => 'Nomor Telepon / WhatsApp'],
            'institution_email' => ['group' => 'institution', 'type' => 'text', 'label' => 'Email Resmi Institusi'],
            'institution_website' => ['group' => 'institution', 'type' => 'text', 'label' => 'Website Resmi Kampus'],
            'lppm_name' => ['group' => 'organization', 'type' => 'text', 'label' => 'Nama Lembaga LPPM'],
            'sentra_hki_name' => ['group' => 'organization', 'type' => 'text', 'label' => 'Nama Unit Sentra HKI'],
            'lppm_building' => ['group' => 'organization', 'type' => 'text', 'label' => 'Lokasi Gedung / Ruang Layanan'],
            'head_lppm_name' => ['group' => 'leader', 'type' => 'text', 'label' => 'Nama Lengkap Kepala LPPM'],
            'head_lppm_nidn' => ['group' => 'leader', 'type' => 'text', 'label' => 'NIDN / NIP Kepala LPPM'],
            'head_lppm_title' => ['group' => 'leader', 'type' => 'text', 'label' => 'Sebutan Jabatan Kepala LPPM'],
            'head_sentra_name' => ['group' => 'leader', 'type' => 'text', 'label' => 'Nama Ketua Sentra HKI'],
            'head_sentra_nidn' => ['group' => 'leader', 'type' => 'text', 'label' => 'NIDN / NIP Ketua Sentra HKI'],
            'head_sentra_title' => ['group' => 'leader', 'type' => 'text', 'label' => 'Sebutan Jabatan Ketua Sentra HKI'],
        ];

        foreach ($textFields as $key => $meta) {
            if ($request->has($key)) {
                AppSetting::set($key, $request->input($key), $meta['group'], $meta['type'], $meta['label']);
            }
        }

        AppSetting::set(
            'feature_incentive_enabled',
            $request->boolean('feature_incentive_enabled') ? '1' : '0',
            'features',
            'boolean',
            'Aktifkan Modul Insentif Publikasi & HKI'
        );

        AppSetting::clearCache();

        $activeTab = $request->input('current_tab', 'branding');

        return redirect()
            ->route('admin.settings.index', ['tab' => $activeTab])
            ->with('success', 'Pengaturan identitas institusi, logo, dan pimpinan LPPM berhasil diperbarui.');
    }

    /**
     * Reset uploaded logo, favicon, or signature back to default.
     */
    public function resetFile(Request $request, string $type): RedirectResponse
    {
        $map = [
            'logo' => 'app_logo',
            'favicon' => 'app_favicon',
            'signature' => 'head_lppm_signature',
        ];

        if (!array_key_exists($type, $map)) {
            return back()->withErrors(['type' => 'Jenis file tidak valid.']);
        }

        $key = $map[$type];
        $currentPath = AppSetting::get($key);

        if ($currentPath && Storage::disk('public')->exists($currentPath)) {
            Storage::disk('public')->delete($currentPath);
        }

        AppSetting::set($key, null);
        AppSetting::clearCache();

        $tab = $type === 'signature' ? 'leader' : 'branding';

        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('success', 'Berkas berhasil dikembalikan ke standar awal sistem.');
    }
}
