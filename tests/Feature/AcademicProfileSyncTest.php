<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\User;
use App\Services\AcademicProfileSyncService;
use Tests\TestCase;

class AcademicProfileSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_lecturer_profile_urls_accessors(): void
    {
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => '6012345',
            'google_scholar_id' => 'ABCDE12345',
            'scopus_id' => '57211234567',
        ]);

        $this->assertEquals('https://sinta.kemdiktisaintek.go.id/authors/profile/6012345', $lecturer->sinta_url);
        $this->assertEquals('https://scholar.google.com/citations?user=ABCDE12345', $lecturer->scholar_url);
        $this->assertEquals('https://www.scopus.com/authid/detail.uri?authorId=57211234567', $lecturer->scopus_url);
    }

    public function test_sync_service_calculates_and_persists_academic_metrics(): void
    {
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => '6012345',
            'google_scholar_id' => 'ABCDE12345',
            'sinta_score_overall' => 0,
            'sinta_score_3year' => 0,
            'google_scholar_citations' => 0,
            'last_synced_at' => null,
        ]);

        $service = app(AcademicProfileSyncService::class);
        $result = $service->sync($lecturer);

        $this->assertTrue($result['sinta']);
        $this->assertTrue($result['scholar']);

        $lecturer->refresh();
        $this->assertNotNull($lecturer->last_synced_at);
        $this->assertSame(0, $lecturer->sinta_score_overall);
        $this->assertGreaterThan(0, $lecturer->google_scholar_citations);
    }

    public function test_dosen_can_trigger_academic_sync_from_profile_page(): void
    {
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => '6012345',
            'google_scholar_id' => 'ABCDE12345',
        ]);

        $dosenUser = $lecturer->user;

        $response = $this->actingAs($dosenUser)->post(route('profile.sync-academic'));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $lecturer->refresh();
        $this->assertNotNull($lecturer->last_synced_at);
    }

    public function test_admin_lppm_can_sync_individual_and_bulk_lecturers(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $lecturer = Lecturer::first();
        $lecturer->update([
            'sinta_id' => '6012345',
            'google_scholar_id' => 'ABCDE12345',
        ]);

        // Test individual sync
        $response = $this->actingAs($admin)->post(route('admin.lecturers.sync-academic', $lecturer));
        $response->assertRedirect(route('admin.lecturers.index'));
        $response->assertSessionHas('success');

        // Test bulk sync
        $bulkResponse = $this->actingAs($admin)->post(route('admin.lecturers.sync-all'));
        $bulkResponse->assertRedirect(route('admin.lecturers.index'));
        $bulkResponse->assertSessionHas('success');
    }
}
