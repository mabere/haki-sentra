<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\IntellectualProperty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppSettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminLppm;
    protected User $dosen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminLppm = User::factory()->create([
            'role' => 'admin_lppm',
            'is_active' => true,
        ]);

        $this->dosen = User::factory()->create([
            'role' => 'dosen',
            'is_active' => true,
        ]);
    }

    public function test_non_admin_cannot_access_settings_page(): void
    {
        $response = $this->actingAs($this->dosen)->get(route('admin.settings.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_settings_page(): void
    {
        $response = $this->actingAs($this->adminLppm)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Aplikasi &amp; Identitas Institusi', false);
        $response->assertSee('Logo &amp; Branding', false);
        $response->assertSee('Kepala LPPM &amp; Pejabat', false);
    }

    public function test_admin_can_update_text_settings(): void
    {
        $response = $this->actingAs($this->adminLppm)->post(route('admin.settings.update'), [
            'app_name' => 'SIM-KAMPUS',
            'app_full_name' => 'Sistem Informasi Kampus Merdeka Inovasi',
            'app_tagline' => 'Inovasi Tanpa Batas',
            'institution_name' => 'Institut Teknologi Nusantara',
            'institution_legal_name' => 'Institut Teknologi Nusantara Indonesia',
            'institution_short_name' => 'ITNI',
            'institution_city' => 'Bandung',
            'institution_address' => 'Jl. Merdeka No. 100, Kota Bandung',
            'institution_postal_code' => '40115',
            'institution_phone' => '081299998888',
            'institution_email' => 'lppm@itni.ac.id',
            'institution_website' => 'https://itni.ac.id',
            'lppm_name' => 'Lembaga Riset dan Pengabdian Masyarakat (LRPM)',
            'sentra_hki_name' => 'Sentra Inovasi & HKI',
            'lppm_building' => 'Gedung Rektorat Lt 3',
            'head_lppm_name' => 'Prof. Dr. Ir. Budi Santoso, M.Sc.',
            'head_lppm_nidn' => '0011223344',
            'head_lppm_title' => 'Direktur Riset dan Pengabdian Masyarakat',
            'head_sentra_name' => 'Dr. Rina Wijaya, S.T., M.T.',
            'head_sentra_nidn' => '0055667788',
            'head_sentra_title' => 'Ketua Unit Sentra HKI',
            'current_tab' => 'institution',
        ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'institution']));
        $response->assertSessionHas('success');

        $this->assertEquals('SIM-KAMPUS', app_setting('app_name'));
        $this->assertEquals('Institut Teknologi Nusantara', app_setting('institution_name'));
        $this->assertEquals('Bandung', app_setting('institution_city'));
        $this->assertEquals('Prof. Dr. Ir. Budi Santoso, M.Sc.', app_setting('head_lppm_name'));
        $this->assertEquals('0011223344', app_setting('head_lppm_nidn'));
    }

    public function test_admin_can_upload_and_reset_logo_and_favicon(): void
    {
        Storage::fake('public');

        $logoFile = UploadedFile::fake()->image('custom_logo.png', 400, 400);
        $favFile = UploadedFile::fake()->image('custom_fav.png', 64, 64);

        $response = $this->actingAs($this->adminLppm)->post(route('admin.settings.update'), [
            'app_name' => 'SENTRA HKI',
            'institution_name' => 'Universitas Panca Sakti',
            'institution_legal_name' => 'Universitas Panca Sakti Bekasi',
            'institution_city' => 'Bekasi',
            'lppm_name' => 'LPPM',
            'sentra_hki_name' => 'Sentra HKI',
            'head_lppm_name' => 'Dr. H. Ahmad Fauzi, M.T.',
            'head_lppm_title' => 'Ketua LPPM',
            'app_logo' => $logoFile,
            'app_favicon' => $favFile,
            'current_tab' => 'branding',
        ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'branding']));

        $logoPath = AppSetting::get('app_logo');
        $favPath = AppSetting::get('app_favicon');

        $this->assertNotNull($logoPath);
        $this->assertNotNull($favPath);
        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($favPath);

        $this->assertStringContainsString('storage/settings/logos', app_setting_logo('logo'));
        $this->assertStringContainsString('storage/settings/favicons', app_setting_logo('favicon'));

        // Test reset logo
        $resetLogoResp = $this->actingAs($this->adminLppm)->post(route('admin.settings.reset-file', 'logo'));
        $resetLogoResp->assertRedirect(route('admin.settings.index', ['tab' => 'branding']));

        $this->assertNull(AppSetting::get('app_logo'));
        $this->assertStringContainsString('logo.png', app_setting_logo('logo'));
    }

    public function test_admin_can_upload_and_reset_signature(): void
    {
        Storage::fake('public');

        $sigFile = UploadedFile::fake()->image('signature.png', 300, 150);

        $response = $this->actingAs($this->adminLppm)->post(route('admin.settings.update'), [
            'app_name' => 'SENTRA HKI',
            'institution_name' => 'Universitas Panca Sakti',
            'institution_legal_name' => 'Universitas Panca Sakti Bekasi',
            'institution_city' => 'Bekasi',
            'lppm_name' => 'LPPM',
            'sentra_hki_name' => 'Sentra HKI',
            'head_lppm_name' => 'Dr. H. Ahmad Fauzi, M.T.',
            'head_lppm_title' => 'Ketua LPPM',
            'head_lppm_signature' => $sigFile,
            'current_tab' => 'leader',
        ]);

        $response->assertRedirect(route('admin.settings.index', ['tab' => 'leader']));

        $sigPath = AppSetting::get('head_lppm_signature');
        $this->assertNotNull($sigPath);
        Storage::disk('public')->assertExists($sigPath);
        $this->assertNotNull(app_setting_signature());

        // Test reset signature
        $resetResp = $this->actingAs($this->adminLppm)->post(route('admin.settings.reset-file', 'signature'));
        $resetResp->assertRedirect(route('admin.settings.index', ['tab' => 'leader']));
        $this->assertNull(AppSetting::get('head_lppm_signature'));
        $this->assertNull(app_setting_signature());
    }

    public function test_certificate_reflects_updated_leader_and_city_dynamically(): void
    {
        // 1. Update Head of LPPM and City
        AppSetting::set('head_lppm_name', 'Dr. Siti Nurhaliza, M.Pd.');
        AppSetting::set('head_lppm_nidn', '9988776655');
        AppSetting::set('head_lppm_title', 'Kepala LPPM');
        AppSetting::set('institution_city', 'Yogyakarta');
        AppSetting::set('institution_legal_name', 'Universitas Inovasi Yogyakarta');

        // 2. Create verified IPR
        $ipr = IntellectualProperty::create([
            'user_id' => $this->dosen->id,
            'title' => 'Sistem Rekomendasi Pintar',
            'ipr_type' => 'hak_cipta',
            'application_number' => 'EC00202612345',
            'rights_holder' => 'Universitas Inovasi Yogyakarta',
            'status' => 'approved',
            'verification_status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $this->adminLppm->id,
        ]);

        // 3. View certificate
        $response = $this->actingAs($this->adminLppm)->get(route('verification.ipr.certificate', $ipr));
        $response->assertStatus(200);

        // Assert dynamic values appear on the certificate
        $response->assertSee('UNIVERSITAS INOVASI YOGYAKARTA');
        $response->assertSee('Yogyakarta,');
        $response->assertSee('Dr. Siti Nurhaliza, M.Pd.');
        $response->assertSee('9988776655');
    }
}
