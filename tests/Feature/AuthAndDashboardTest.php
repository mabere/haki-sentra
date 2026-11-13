<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthAndDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sentra HKI');
    }

    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('SIM-HKIPUB');
    }

    public function test_user_can_login_with_email(): void
    {
        $response = $this->post(route('login.post'), [
            'login_id' => 'admin@kampus.ac.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_lecturer_can_login_with_nidn(): void
    {
        $response = $this->post(route('login.post'), [
            'login_id' => '0012058501',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('dosen@kampus.ac.id', auth()->user()->email);
    }

    public function test_dashboard_displays_stats_for_authenticated_user(): void
    {
        $user = User::where('email', 'dosen@kampus.ac.id')->first();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Publikasi Saya');
        $response->assertSee('Karya HKI Saya');
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'admin@kampus.ac.id')->first();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}

