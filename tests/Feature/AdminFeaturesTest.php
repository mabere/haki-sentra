<?php

namespace Tests\Feature;

use App\Models\Faculty;
use App\Models\IncentiveScheme;
use App\Models\Lecturer;
use App\Models\User;
use Tests\TestCase;

class AdminFeaturesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_admin_can_access_master_data(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();

        $response = $this->actingAs($admin)->get(route('admin.master.index'));

        $response->assertStatus(200);
        $response->assertSee('Master Data', false);
        $response->assertSee('Daftar Skema & Besaran Insentif', false);
    }

    public function test_admin_can_store_new_incentive_scheme(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();

        $response = $this->actingAs($admin)->post(route('admin.master.scheme.store'), [
            'name' => 'Insentif Prototipe Teruji',
            'category' => 'ipr',
            'tier' => 'prototipe',
            'amount' => 5000000,
            'fiscal_year' => 2026,
            'description' => 'Untuk prototipe industri yang telah diuji.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.master.index', ['tab' => 'schemes']));
        $this->assertDatabaseHas('incentive_schemes', [
            'name' => 'Insentif Prototipe Teruji',
            'amount' => 5000000,
        ]);
    }

    public function test_admin_can_access_lecturers_directory(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();

        $response = $this->actingAs($admin)->get(route('admin.lecturers.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Dosen', false);
        $response->assertSee('Budi Santoso');
    }

    public function test_admin_can_toggle_lecturer_account_status(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $lecturer = Lecturer::where('nidn', '0012058501')->first();

        $this->assertTrue($lecturer->user->is_active);

        $response = $this->actingAs($admin)->post(route('admin.lecturers.toggle-status', $lecturer));

        $response->assertRedirect(route('admin.lecturers.index'));
        $lecturer->user->refresh();
        $this->assertFalse($lecturer->user->is_active);
    }

    public function test_admin_can_access_iku_report(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();

        $response = $this->actingAs($admin)->get(route('reports.iku'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Capaian IKU', false);
        $response->assertSee('Distribusi Luaran per Fakultas');
    }

    public function test_admin_can_access_summary_report_and_export(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();

        $response = $this->actingAs($admin)->get(route('reports.summary'));
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Luaran BKD', false);

        $exportResponse = $this->actingAs($admin)->get(route('reports.export'));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_incentives_action_button_disabled_when_completed(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $lecturer = Lecturer::first();
        $scheme = IncentiveScheme::first();

        // 1. Klaim pending (submitted) -> Tombol aktif 'Proses Klaim'
        $pendingClaim = \App\Models\IncentiveRequest::create([
            'lecturer_id' => $lecturer->id,
            'incentive_scheme_id' => $scheme->id,
            'claimable_type' => \App\Models\Publication::class,
            'claimable_id' => 1,
            'requested_amount' => 5000000,
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Dosen Test',
            'status' => 'submitted',
        ]);

        // 2. Klaim selesai (disbursed) -> Tombol disable
        $completedClaim = \App\Models\IncentiveRequest::create([
            'lecturer_id' => $lecturer->id,
            'incentive_scheme_id' => $scheme->id,
            'claimable_type' => \App\Models\Publication::class,
            'claimable_id' => 2,
            'requested_amount' => 5000000,
            'approved_amount' => 5000000,
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Dosen Test',
            'status' => 'disbursed',
            'disbursed_at' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.incentives.index'));
        $response->assertStatus(200);

        // Harus ada tombol aktif 'Proses Klaim'
        $response->assertSee('Proses Klaim', false);
        // Harus ada tombol disabled 'Selesai'
        $response->assertSee('Selesai', false);

        // Halaman show klaim yang sudah selesai tidak menampilkan tombol submit proses
        $showResponse = $this->actingAs($admin)->get(route('admin.incentives.show', $completedClaim));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Klaim Telah Selesai Diproses', false);
        $showResponse->assertDontSee('Simpan &amp; Perbarui Status Insentif', false);
    }
}
