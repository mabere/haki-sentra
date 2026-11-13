<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create([
            'role' => 'dosen',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Foto Profil &amp; Data Akun', false);
        $response->assertSee($user->email);
    }

    public function test_user_can_update_profile_and_upload_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@example.com',
            'role' => 'dosen',
        ]);

        $photo = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nama Baru Dosen',
            'email' => 'baru@example.com',
            'phone' => '08123456789',
            'photo' => $photo,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil dan foto pengguna berhasil diperbarui.');

        $user->refresh();
        $this->assertEquals('Nama Baru Dosen', $user->name);
        $this->assertEquals('baru@example.com', $user->email);
        $this->assertEquals('08123456789', $user->phone);
        $this->assertNotNull($user->photo);

        Storage::disk('public')->assertExists($user->photo);
    }

    public function test_lecturer_can_update_academic_profile(): void
    {
        $faculty = Faculty::create(['name' => 'Fakultas Teknik', 'code' => 'FT']);
        $dept = Department::create(['faculty_id' => $faculty->id, 'name' => 'Informatika', 'code' => 'IF']);

        $user = User::factory()->create([
            'role' => 'dosen',
        ]);

        $lecturer = Lecturer::create([
            'user_id' => $user->id,
            'department_id' => $dept->id,
            'nidn' => '0412345678',
            'academic_rank' => 'Asisten Ahli',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'nidn' => '0499999999',
            'front_title' => 'Dr.',
            'back_title' => 'M.Kom.',
            'academic_rank' => 'Lektor',
            'sinta_id' => '654321',
            'scopus_id' => '987654321',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $lecturer->refresh();
        $this->assertEquals('0499999999', $lecturer->nidn);
        $this->assertEquals('Dr.', $lecturer->front_title);
        $this->assertEquals('M.Kom.', $lecturer->back_title);
        $this->assertEquals('Lektor', $lecturer->academic_rank);
        $this->assertEquals('654321', $lecturer->sinta_id);
        $this->assertEquals('987654321', $lecturer->scopus_id);
    }
}
