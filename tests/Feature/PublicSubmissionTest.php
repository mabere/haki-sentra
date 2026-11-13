<?php

namespace Tests\Feature;

use App\Models\IntellectualProperty;
use App\Models\User;
use Tests\TestCase;

class PublicSubmissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_guest_can_submit_fast_hki_application(): void
    {
        $payload = [
            'nama' => 'Prof. Dr. Hendra Wijaya, M.T.',
            'email' => 'hendra.wijaya@kampus.ac.id',
            'phone' => '081299887766',
            'ipr_type' => 'hak_cipta',
            'title' => 'Algoritma Deteksi Dini Bencana Banjir Menggunakan Jaringan Sensor Nirkabel',
            'sub_type' => 'Program Komputer',
            'notes' => 'Telah diuji coba di wilayah Bekasi Timur.',
        ];

        $response = $this->postJson(route('permohonan.ajukan'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => $payload['title'],
                    'ipr_type' => 'hak_cipta',
                ],
            ]);

        $appNumber = $response->json('data.application_number');
        $this->assertNotEmpty($appNumber);

        // Verify saved in database
        $this->assertDatabaseHas('intellectual_properties', [
            'application_number' => $appNumber,
            'title' => $payload['title'],
            'verification_status' => 'pending',
            'djki_status' => 'draft',
        ]);

        $this->assertDatabaseHas('ipr_inventors', [
            'inventor_name' => $payload['nama'],
            'role' => 'inventor_utama',
        ]);

        // Verify this newly submitted code can be tracked immediately
        $trackResponse = $this->getJson(route('tracking.status', ['code' => $appNumber]));
        $trackResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'application_number' => $appNumber,
                    'title' => $payload['title'],
                    'verification_status' => 'pending',
                ],
            ]);
    }

    public function test_authenticated_user_can_submit_hki_application(): void
    {
        $user = User::where('role', 'dosen')->first();
        $this->assertNotNull($user);

        $payload = [
            'nama' => $user->name,
            'email' => $user->email,
            'phone' => '081234567890',
            'ipr_type' => 'paten',
            'title' => 'Alat Pengering Padi Otomatis Berbasis Energi Surya Ramah Lingkungan',
        ];

        $response = $this->actingAs($user)->postJson(route('permohonan.ajukan'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $appNumber = $response->json('data.application_number');

        $this->assertDatabaseHas('intellectual_properties', [
            'user_id' => $user->id,
            'application_number' => $appNumber,
            'ipr_type' => 'paten',
        ]);
    }

    public function test_submission_fails_with_invalid_input(): void
    {
        $response = $this->postJson(route('permohonan.ajukan'), [
            'nama' => '',
            'email' => 'bukan-email',
            'ipr_type' => 'jenis_tidak_ada',
            'title' => '',
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('nama', $response->json('errors'));
        $this->assertArrayHasKey('email', $response->json('errors'));
        $this->assertArrayHasKey('phone', $response->json('errors'));
        $this->assertArrayHasKey('ipr_type', $response->json('errors'));
        $this->assertArrayHasKey('title', $response->json('errors'));
    }
}
