<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\User;
use App\Models\VerificationLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_and_verifikator_can_download_logs_as_txt(): void
    {
        $admin = User::where('role', 'admin_lppm')->firstOrFail();
        $verifikator = User::where('role', 'verifikator')->firstOrFail();
        $publication = Publication::first();

        VerificationLog::create([
            'verifiable_type' => Publication::class,
            'verifiable_id' => $publication?->id ?? 1,
            'user_id' => $verifikator->id,
            'status_before' => 'pending',
            'status_after' => 'verified',
            'notes' => 'Catatan audit uji coba ekspor txt',
        ]);

        foreach ([$admin, $verifikator] as $actor) {
            $response = $this->actingAs($actor)->get(route('verification.logs.download'));

            $response->assertStatus(200);
            $this->assertStringContainsString('text/plain', (string) $response->headers->get('content-type'));
            $this->assertStringContainsString('.txt', (string) $response->headers->get('content-disposition'));

            $content = $response->streamedContent();
            $this->assertStringContainsString('LOG RIWAYAT & AUDIT TRAIL VERIFIKASI', $content);
            $this->assertStringContainsString('Catatan audit uji coba ekspor txt', $content);
        }
    }

    public function test_dosen_cannot_download_or_delete_logs(): void
    {
        $dosen = User::where('role', 'dosen')->firstOrFail();

        $this->actingAs($dosen)
            ->get(route('verification.logs.download'))
            ->assertStatus(403);

        $this->actingAs($dosen)
            ->delete(route('verification.logs.destroy'), ['older_than' => 'all'])
            ->assertStatus(403);
    }

    public function test_verifikator_cannot_delete_logs(): void
    {
        $verifikator = User::where('role', 'verifikator')->firstOrFail();

        $this->actingAs($verifikator)
            ->delete(route('verification.logs.destroy'), ['older_than' => 'all'])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_old_logs_and_all_logs(): void
    {
        $admin = User::where('role', 'admin_lppm')->firstOrFail();
        $publication = Publication::first();

        $oldLog = VerificationLog::create([
            'verifiable_type' => Publication::class,
            'verifiable_id' => $publication?->id ?? 1,
            'user_id' => $admin->id,
            'status_before' => 'pending',
            'status_after' => 'verified',
            'notes' => 'Log lama 45 hari',
        ]);
        $oldLog->forceFill(['created_at' => now()->subDays(45)])->saveQuietly();

        $recentLog = VerificationLog::create([
            'verifiable_type' => Publication::class,
            'verifiable_id' => $publication?->id ?? 1,
            'user_id' => $admin->id,
            'status_before' => 'pending',
            'status_after' => 'revision',
            'notes' => 'Log baru hari ini',
        ]);

        // Delete logs older than 30 days
        $this->actingAs($admin)
            ->delete(route('verification.logs.destroy'), ['older_than' => '30'])
            ->assertRedirect(route('verification.logs'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('verification_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('verification_logs', ['id' => $recentLog->id]);

        // Delete all remaining logs
        $this->actingAs($admin)
            ->delete(route('verification.logs.destroy'), ['older_than' => 'all'])
            ->assertRedirect(route('verification.logs'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('verification_logs', 0);
    }
}
