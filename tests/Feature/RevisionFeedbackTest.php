<?php

namespace Tests\Feature;

use App\Models\IntellectualProperty;
use App\Models\Publication;
use App\Models\RevisionComment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RevisionFeedbackTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    public function test_verifikator_can_post_revision_comment_and_request_revision(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $ipr = IntellectualProperty::first();
        $ipr->update(['verification_status' => 'pending']);

        $response = $this->actingAs($admin)->post(route('revisi.komentar.store'), [
            'verifiable_type' => 'ipr',
            'verifiable_id' => $ipr->id,
            'comment' => 'Mohon lampiran surat pernyataan dialihkan ke format PDF bertanda tangan basah.',
            'mark_as_revision_request' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('revision_comments', [
            'verifiable_type' => IntellectualProperty::class,
            'verifiable_id' => $ipr->id,
            'user_id' => $admin->id,
            'comment' => 'Mohon lampiran surat pernyataan dialihkan ke format PDF bertanda tangan basah.',
        ]);

        $ipr->refresh();
        $this->assertEquals('revision', $ipr->verification_status);
    }

    public function test_dosen_can_reply_and_resubmit_work(): void
    {
        Storage::fake('public');

        $ipr = IntellectualProperty::first();
        $dosen = $ipr->user;
        $ipr->update(['verification_status' => 'revision']);

        $file = UploadedFile::fake()->create('revisi_surat_pernyataan.pdf', 300, 'application/pdf');

        $response = $this->actingAs($dosen)->post(route('revisi.komentar.store'), [
            'verifiable_type' => 'ipr',
            'verifiable_id' => $ipr->id,
            'comment' => 'Surat pernyataan bertanda tangan sudah diperbarui dan diunggah kembali.',
            'attachment' => $file,
            'mark_as_resubmitted' => '1',
        ]);

        $response->assertSessionHas('success');

        $comment = RevisionComment::where('verifiable_id', $ipr->id)->where('user_id', $dosen->id)->first();
        $this->assertNotNull($comment);
        $this->assertNotNull($comment->attachment_path);
        Storage::disk('public')->assertExists($comment->attachment_path);

        $ipr->refresh();
        $this->assertEquals('pending', $ipr->verification_status);
    }

    public function test_unauthorized_user_cannot_comment_on_other_users_work(): void
    {
        $ipr = IntellectualProperty::first();
        // Cari dosen lain yang bukan pemilik
        $otherDosen = User::where('role', 'dosen')->where('id', '!=', $ipr->user_id)->first();

        $response = $this->actingAs($otherDosen)->post(route('revisi.komentar.store'), [
            'verifiable_type' => 'ipr',
            'verifiable_id' => $ipr->id,
            'comment' => 'Komentar tidak sah.',
        ]);

        $response->assertStatus(403);
    }
}
