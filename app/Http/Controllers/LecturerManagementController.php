<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LecturerManagementController extends Controller
{
    /**
     * Display a listing of lecturers and accounts.
     */
    public function index(Request $request)
    {
        $query = Lecturer::with(['user', 'department.faculty']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nidn', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('academic_rank', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('department_id') && $request->department_id !== 'all') {
            $query->where('department_id', $request->department_id);
        }

        $lecturers = $query->orderBy('nidn')->paginate(12)->withQueryString();
        $departments = Department::with('faculty')->orderBy('name')->get();
        $faculties = Faculty::orderBy('name')->get();

        $stats = [
            'total_lecturers' => Lecturer::count(),
            'active_accounts' => User::where('role', 'dosen')->where('is_active', true)->count(),
            'lektor_kepala' => Lecturer::whereIn('academic_rank', ['Lektor Kepala', 'Guru Besar'])->count(),
        ];

        return view('admin.lecturers_index', compact('lecturers', 'departments', 'faculties', 'stats'));
    }

    /**
     * Store new lecturer and user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nidn' => ['required', 'string', 'max:20', 'unique:lecturers,nidn'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:lecturers,nip'],
            'department_id' => ['required', 'exists:departments,id'],
            'front_title' => ['nullable', 'string', 'max:30'],
            'back_title' => ['nullable', 'string', 'max:30'],
            'academic_rank' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'sinta_id' => ['nullable', 'string', 'max:50'],
            'scopus_id' => ['nullable', 'string', 'max:50'],
            'google_scholar_id' => ['nullable', 'string', 'max:50'],
            'orcid_id' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:12', 'max:128'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'nidn.unique' => 'NIDN ini sudah terdaftar di sistem.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role' => 'dosen',
                'phone' => $validated['phone'] ?? null,
                'is_active' => true,
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'nidn' => $validated['nidn'],
                'nip' => $validated['nip'] ?? null,
                'front_title' => $validated['front_title'] ?? null,
                'back_title' => $validated['back_title'] ?? null,
                'academic_rank' => $validated['academic_rank'] ?? 'Tenaga Pengajar',
                'sinta_id' => $validated['sinta_id'] ?? null,
                'scopus_id' => $validated['scopus_id'] ?? null,
                'google_scholar_id' => $validated['google_scholar_id'] ?? null,
                'orcid_id' => $validated['orcid_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Data dosen dan akun pengguna berhasil ditambahkan.');
    }

    /**
     * Update existing lecturer.
     */
    public function update(Request $request, Lecturer $lecturer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $lecturer->user_id],
            'nidn' => ['required', 'string', 'max:20', 'unique:lecturers,nidn,' . $lecturer->id],
            'nip' => ['nullable', 'string', 'max:30', 'unique:lecturers,nip,' . $lecturer->id],
            'department_id' => ['required', 'exists:departments,id'],
            'front_title' => ['nullable', 'string', 'max:30'],
            'back_title' => ['nullable', 'string', 'max:30'],
            'academic_rank' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'sinta_id' => ['nullable', 'string', 'max:50'],
            'scopus_id' => ['nullable', 'string', 'max:50'],
            'google_scholar_id' => ['nullable', 'string', 'max:50'],
            'orcid_id' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:12', 'max:128'],
        ]);

        DB::transaction(function () use ($validated, $lecturer) {
            $userUpdate = [
                'name' => $validated['name'],
                'email' => strtolower(trim($validated['email'])),
                'phone' => $validated['phone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userUpdate['password'] = Hash::make($validated['password']);
            }

            $lecturer->user->update($userUpdate);

            $lecturer->update([
                'department_id' => $validated['department_id'],
                'nidn' => $validated['nidn'],
                'nip' => $validated['nip'] ?? null,
                'front_title' => $validated['front_title'] ?? null,
                'back_title' => $validated['back_title'] ?? null,
                'academic_rank' => $validated['academic_rank'] ?? $lecturer->academic_rank,
                'sinta_id' => $validated['sinta_id'] ?? null,
                'scopus_id' => $validated['scopus_id'] ?? null,
                'google_scholar_id' => $validated['google_scholar_id'] ?? null,
                'orcid_id' => $validated['orcid_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Toggle active status of user account.
     */
    public function toggleStatus(Lecturer $lecturer)
    {
        $user = $lecturer->user;
        $user->is_active = !$user->is_active;
        $user->save();

        $statusMsg = $user->is_active ? 'diaktifkan kembali.' : 'dinonaktifkan sementara.';

        return redirect()->route('admin.lecturers.index')
            ->with('success', "Akun dosen {$user->name} berhasil {$statusMsg}");
    }

    /**
     * Sinkronkan profil akademik dosen individual (SINTA & Google Scholar).
     */
    public function syncAcademic(Lecturer $lecturer, \App\Services\AcademicProfileSyncService $syncService)
    {
        $result = $syncService->sync($lecturer);

        return redirect()->route('admin.lecturers.index')
            ->with('success', "Sinkronisasi profil akademik untuk {$lecturer->full_name} selesai. {$result['message']}");
    }

    /**
     * Sinkronisasi massal seluruh dosen aktif.
     */
    public function syncAll(\App\Services\AcademicProfileSyncService $syncService)
    {
        $lecturers = Lecturer::whereNotNull('sinta_id')->orWhereNotNull('google_scholar_id')->get();
        $synced = 0;

        foreach ($lecturers as $lecturer) {
            $syncService->sync($lecturer);
            $synced++;
        }

        return redirect()->route('admin.lecturers.index')
            ->with('success', "Sinkronisasi massal berhasil dijalankan untuk {$synced} dosen.");
    }
}
