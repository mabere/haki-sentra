<?php

namespace Tests\Feature;

use App\Models\IntellectualProperty;
use App\Models\User;
use App\Models\VerificationLog;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_verifikator_can_access_ipr_verification_queue(): void
    {
        $verifikator = User::where('role', 'verifikator')->first();

        $response = $this->actingAs($verifikator)->get(route('verification.ipr.index'));

        $response->assertStatus(200);
        $response->assertSee('Antrian Verifikasi Karya HKI');
    }

    public function test_verifikator_can_review_ipr_application(): void
    {
        $verifikator = User::where('role', 'verifikator')->first();
        $ipr = IntellectualProperty::first();

        $response = $this->actingAs($verifikator)->get(route('verification.ipr.review', $ipr));

        $response->assertStatus(200);
        $response->assertSee($ipr->title);
        $response->assertSee('Keputusan Validasi Verifikator / Ketua LPPM');
    }

    public function test_verifikator_can_approve_ipr_and_update_djki_status(): void
    {
        $verifikator = User::where('role', 'verifikator')->first();
        $ipr = IntellectualProperty::first();

        $response = $this->actingAs($verifikator)->post(route('verification.ipr.verify', $ipr), [
            'action' => 'approve',
            'notes' => 'Berkas dan persyaratan telah diperiksa lengkap dan memenuhi kriteria.',
            'djki_status' => 'granted',
            'registration_number' => '000492104',
            'grant_date' => '2026-10-12',
        ]);

        $response->assertRedirect(route('verification.ipr.index'));
        $response->assertSessionHas('success');

        $ipr->refresh();
        $this->assertEquals('approved', $ipr->verification_status);
        $this->assertEquals('granted', $ipr->djki_status);
        $this->assertEquals('000492104', $ipr->registration_number);
        $this->assertEquals($verifikator->id, $ipr->verified_by);

        // Check verification log created
        $this->assertDatabaseHas('verification_logs', [
            'verifiable_id' => $ipr->id,
            'verifiable_type' => IntellectualProperty::class,
            'user_id' => $verifikator->id,
            'status_after' => 'approved',
        ]);
    }

    public function test_verifikator_can_request_revision_with_notes(): void
    {
        $verifikator = User::where('role', 'verifikator')->first();
        $ipr = IntellectualProperty::first();

        $response = $this->actingAs($verifikator)->post(route('verification.ipr.verify', $ipr), [
            'action' => 'revision',
            'notes' => 'Harap lengkapi uraian dan surat pernyataan pengalihan hak cipta.',
        ]);

        $response->assertRedirect(route('verification.ipr.index'));
        $ipr->refresh();
        $this->assertEquals('revision', $ipr->verification_status);
        $this->assertEquals('Harap lengkapi uraian dan surat pernyataan pengalihan hak cipta.', $ipr->verification_note);
    }

    public function test_verifikator_can_view_official_certificate(): void
    {
        $verifikator = User::where('role', 'verifikator')->first();
        $ipr = IntellectualProperty::first();
        $ipr->update(['verification_status' => 'approved']);

        $response = $this->actingAs($verifikator)->get(route('verification.ipr.certificate', $ipr));

        $response->assertStatus(200);
        $response->assertSee('SURAT KETERANGAN VERIFIKASI HAK KEKAYAAN INTELEKTUAL');
        $response->assertSee('UNIVERSITAS PANCA SAKTI BEKASI');
        $response->assertSee($ipr->title);
    }

    public function test_dosen_cannot_access_verification_routes(): void
    {
        $dosen = User::where('role', 'dosen')->first();
        $ipr = IntellectualProperty::first();

        $response = $this->actingAs($dosen)->get(route('verification.ipr.index'));
        $response->assertStatus(403);

        $response2 = $this->actingAs($dosen)->get(route('verification.ipr.review', $ipr));
        $response2->assertStatus(403);
    }
}

