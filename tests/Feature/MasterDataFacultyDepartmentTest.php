<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataFacultyDepartmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_update_and_delete_faculty(): void
    {
        $admin = User::where('role', 'admin_lppm')->firstOrFail();

        $faculty = Faculty::create([
            'name' => 'Fakultas Uji Coba',
            'code' => 'FUC',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.master.faculty.update', $faculty), [
                'name' => 'Fakultas Uji Coba Baru',
                'code' => 'FUCB',
            ])
            ->assertRedirect(route('admin.master.index', ['tab' => 'faculties']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'name' => 'Fakultas Uji Coba Baru',
            'code' => 'FUCB',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.master.faculty.destroy', $faculty))
            ->assertRedirect(route('admin.master.index', ['tab' => 'faculties']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('faculties', ['id' => $faculty->id]);
    }

    public function test_admin_cannot_delete_faculty_with_departments(): void
    {
        $admin = User::where('role', 'admin_lppm')->firstOrFail();

        $faculty = Faculty::has('departments')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.master.faculty.destroy', $faculty))
            ->assertRedirect(route('admin.master.index', ['tab' => 'faculties']))
            ->assertSessionHasErrors('error');

        $this->assertDatabaseHas('faculties', ['id' => $faculty->id]);
    }

    public function test_admin_can_update_and_delete_department(): void
    {
        $admin = User::where('role', 'admin_lppm')->firstOrFail();
        $faculty = Faculty::firstOrFail();

        $department = Department::create([
            'faculty_id' => $faculty->id,
            'name' => 'Prodi Uji Coba',
            'code' => 'PUC',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.master.department.update', $department), [
                'faculty_id' => $faculty->id,
                'name' => 'Prodi Uji Coba Baru',
                'code' => 'PUCB',
            ])
            ->assertRedirect(route('admin.master.index', ['tab' => 'faculties']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'Prodi Uji Coba Baru',
            'code' => 'PUCB',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.master.department.destroy', $department))
            ->assertRedirect(route('admin.master.index', ['tab' => 'faculties']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }

    public function test_admin_cannot_delete_department_with_lecturers(): void
    {
        $admin = User::where('role', 'admin_lppm')->firstOrFail();
        $department = Department::has('lecturers')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.master.department.destroy', $department))
            ->assertRedirect(route('admin.master.index', ['tab' => 'faculties']))
            ->assertSessionHasErrors('error');

        $this->assertDatabaseHas('departments', ['id' => $department->id]);
    }

    public function test_non_admin_cannot_update_or_delete_faculty_or_department(): void
    {
        $dosen = User::where('role', 'dosen')->firstOrFail();
        $faculty = Faculty::firstOrFail();
        $department = Department::firstOrFail();

        $this->actingAs($dosen)
            ->put(route('admin.master.faculty.update', $faculty), ['name' => 'Hack', 'code' => 'HCK'])
            ->assertStatus(403);

        $this->actingAs($dosen)
            ->delete(route('admin.master.faculty.destroy', $faculty))
            ->assertStatus(403);

        $this->actingAs($dosen)
            ->put(route('admin.master.department.update', $department), [
                'faculty_id' => $faculty->id,
                'name' => 'Hack',
                'code' => 'HCK2',
            ])
            ->assertStatus(403);

        $this->actingAs($dosen)
            ->delete(route('admin.master.department.destroy', $department))
            ->assertStatus(403);
    }
}
