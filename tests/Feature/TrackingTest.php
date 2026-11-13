<?php

namespace Tests\Feature;

use App\Models\IntellectualProperty;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_can_track_existing_application_number(): void
    {
        $existing = IntellectualProperty::first();
        $this->assertNotNull($existing);

        $response = $this->getJson(route('tracking.status', ['code' => $existing->application_number]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => $existing->title,
                    'application_number' => $existing->application_number,
                ],
            ]);
    }

    public function test_can_track_by_registration_number(): void
    {
        $existing = IntellectualProperty::whereNotNull('registration_number')->first();
        $this->assertNotNull($existing);

        $response = $this->getJson(route('tracking.status', ['code' => $existing->registration_number]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'registration_number' => $existing->registration_number,
                ],
            ]);
    }

    public function test_tracking_returns_422_when_code_is_empty(): void
    {
        $response = $this->getJson(route('tracking.status', ['code' => '']));

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_tracking_returns_404_when_code_not_found(): void
    {
        $response = $this->getJson(route('tracking.status', ['code' => 'KODE_ACAK_TIDAK_ADA_9999']));

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }
}

