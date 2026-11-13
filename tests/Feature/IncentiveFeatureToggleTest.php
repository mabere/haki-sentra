<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncentiveFeatureToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        AppSetting::clearCache();
    }

    public function test_incentive_module_is_disabled_by_default(): void
    {
        $this->assertFalse(incentive_enabled());

        $dosen = User::where('role', 'dosen')->firstOrFail();
        $admin = User::where('role', 'admin_lppm')->firstOrFail();

        $this->actingAs($dosen)->get(route('dosen.incentives.index'))->assertStatus(404);
        $this->actingAs($admin)->get(route('admin.incentives.index'))->assertStatus(404);

        // Sidebar and dashboard hide incentive entries
        $dosenDash = $this->actingAs($dosen)->get(route('dashboard'));
        $dosenDash->assertStatus(200);
        $dosenDash->assertDontSee('Pengajuan Insentif');

        $adminDash = $this->actingAs($admin)->get(route('dashboard'));
        $adminDash->assertStatus(200);
        $adminDash->assertDontSee('Pencairan Insentif');
        $adminDash->assertDontSee('Realisasi Insentif');

        // Master Data shows Tarif & Skema Insentif tab as disabled
        $master = $this->actingAs($admin)->get(route('admin.master.index'));
        $master->assertStatus(200);
        $master->assertSee('Tarif &amp; Skema Insentif', false);
        $master->assertSee('Nonaktif');
    }

    public function test_only_admin_lppm_can_toggle_incentive_feature(): void
    {
        $dosen = User::where('role', 'dosen')->firstOrFail();
        $pimpinan = User::where('role', 'pimpinan')->firstOrFail();
        $verifikator = User::where('role', 'verifikator')->firstOrFail();
        $admin = User::where('role', 'admin_lppm')->firstOrFail();

        foreach ([$dosen, $pimpinan, $verifikator] as $user) {
            $this->actingAs($user)
                ->post(route('admin.settings.update'), [
                    'feature_incentive_enabled' => '1',
                ])
                ->assertStatus(403);
        }

        $payload = [
            'app_name' => 'SENTRA HKI',
            'institution_name' => 'Universitas Panca Sakti',
            'institution_legal_name' => 'Universitas Panca Sakti Bekasi',
            'institution_city' => 'Bekasi',
            'lppm_name' => 'LPPM',
            'sentra_hki_name' => 'Sentra HKI',
            'head_lppm_name' => 'Dr. H. Ahmad Fauzi, M.T.',
            'head_lppm_title' => 'Kepala LPPM',
            'feature_incentive_enabled' => '1',
            'current_tab' => 'features',
        ];

        $this->actingAs($admin)
            ->post(route('admin.settings.update'), $payload)
            ->assertRedirect(route('admin.settings.index', ['tab' => 'features']))
            ->assertSessionHas('success');

        $this->assertTrue(incentive_enabled());

        $this->actingAs($dosen)->get(route('dosen.incentives.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.incentives.index'))->assertStatus(200);

        $dosenDash = $this->actingAs($dosen)->get(route('dashboard'));
        $dosenDash->assertStatus(200);
        $dosenDash->assertSee('Pengajuan Insentif');
    }
}
