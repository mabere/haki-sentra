<?php

namespace Tests\Feature;

use App\Models\Information;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InformationCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_view_information_index(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();

        $response = $this->actingAs($admin)->get(route('admin.informasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Informasi &amp; Template Dokumen HKI', false);
        $response->assertSee('Template Dokumen Unduhan');
    }

    public function test_admin_can_store_new_information_item_with_file(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin_lppm')->first();

        $file = UploadedFile::fake()->create('panduan_haki_2026.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->post(route('admin.informasi.store'), [
            'title' => 'Panduan Teknis Hak Cipta Perangkat Lunak 2026',
            'category' => 'template_dokumen',
            'badge_text' => 'PDF',
            'icon' => 'file-text',
            'order' => 5,
            'description' => 'Panduan lengkap penyiapan source code dan manual book.',
            'content' => 'Rincian syarat format lampiran kode sumber program...',
            'file' => $file,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.informasi.index', ['tab' => 'template_dokumen']));
        
        $this->assertDatabaseHas('information', [
            'title' => 'Panduan Teknis Hak Cipta Perangkat Lunak 2026',
            'category' => 'template_dokumen',
            'file_name' => 'panduan_haki_2026.pdf',
            'file_type' => 'PDF',
            'is_active' => true,
        ]);

        $item = Information::where('title', 'Panduan Teknis Hak Cipta Perangkat Lunak 2026')->first();
        Storage::disk('public')->assertExists($item->file_path);
    }

    public function test_admin_can_update_information_item(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $info = Information::first();

        $response = $this->actingAs($admin)->put(route('admin.informasi.update', $info->id), [
            'title' => 'Judul Terupdate LPPM',
            'category' => $info->category,
            'badge_text' => 'Updated',
            'icon' => 'check-circle',
            'order' => 10,
            'description' => 'Deskripsi yang telah diperbaiki',
            'content' => 'Konten revisi',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.informasi.index', ['tab' => $info->category]));

        $this->assertDatabaseHas('information', [
            'id' => $info->id,
            'title' => 'Judul Terupdate LPPM',
            'badge_text' => 'Updated',
            'order' => 10,
        ]);
    }

    public function test_admin_can_toggle_information_status(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $info = Information::first();
        $initialStatus = $info->is_active;

        $response = $this->actingAs($admin)->post(route('admin.informasi.toggle-status', $info->id));

        $response->assertStatus(302);
        $this->assertDatabaseHas('information', [
            'id' => $info->id,
            'is_active' => !$initialStatus,
        ]);
    }

    public function test_admin_can_delete_information_item(): void
    {
        $admin = User::where('role', 'admin_lppm')->first();
        $info = Information::create([
            'title' => 'Informasi Sementara Untuk Dihapus',
            'category' => 'faq',
            'is_active' => true,
            'order' => 99,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.informasi.destroy', $info->id));

        $response->assertRedirect(route('admin.informasi.index', ['tab' => 'faq']));
        $this->assertDatabaseMissing('information', [
            'id' => $info->id,
        ]);
    }

    public function test_non_admin_cannot_access_admin_information_crud(): void
    {
        $dosen = User::where('role', 'dosen')->first();

        // Dosen attempting GET
        $response = $this->actingAs($dosen)->get(route('admin.informasi.index'));
        $response->assertStatus(403);

        // Dosen attempting POST
        $postResponse = $this->actingAs($dosen)->post(route('admin.informasi.store'), [
            'title' => 'Hacker Info',
            'category' => 'faq',
        ]);
        $postResponse->assertStatus(403);
    }

    public function test_guest_and_public_can_access_public_information(): void
    {
        // 1. Landing page loads dynamic information
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Alur Mudah Pengajuan HKI');
        $homeResponse->assertSee('Surat Pernyataan Hak Cipta');

        // 2. Public information index
        $indexResponse = $this->get(route('informasi.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Panduan, Alur SOP &amp; Template Dokumen HKI', false);

        // 3. Public information detail show
        $info = Information::where('is_active', true)->first();
        $showResponse = $this->get(route('informasi.show', $info->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($info->title);

        // 4. Download template document
        $template = Information::where('category', 'template_dokumen')->where('is_active', true)->first();
        if ($template) {
            $downloadResponse = $this->get(route('informasi.unduh', $template->slug));
            $downloadResponse->assertStatus(200);
        }
    }
}
