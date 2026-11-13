<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil pengguna.
     */
    public function edit()
    {
        $user = auth()->user()->load('lecturer.department.faculty');

        return view('profile.edit', compact('user'));
    }

    /**
     * Simpan pembaruan profil dan foto pengguna.
     */
    public function update(Request $request, \App\Services\AcademicProfileSyncService $syncService)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:12', 'max:128', 'confirmed'],
        ];

        if ($user->lecturer) {
            $rules['nidn'] = ['nullable', 'string', 'max:30', Rule::unique('lecturers', 'nidn')->ignore($user->lecturer->id)];
            $rules['nip'] = ['nullable', 'string', 'max:30', Rule::unique('lecturers', 'nip')->ignore($user->lecturer->id)];
            $rules['front_title'] = ['nullable', 'string', 'max:30'];
            $rules['back_title'] = ['nullable', 'string', 'max:30'];
            $rules['academic_rank'] = ['nullable', 'string', 'max:50'];
            $rules['sinta_id'] = ['nullable', 'string', 'max:50'];
            $rules['scopus_id'] = ['nullable', 'string', 'max:50'];
            $rules['google_scholar_id'] = ['nullable', 'string', 'max:50'];
            $rules['orcid_id'] = ['nullable', 'string', 'max:50'];
        }

        $validated = $request->validate($rules);

        // Upload & simpan foto baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            if ($user->avatar && Storage::disk('public')->exists($user->avatar) && $user->avatar !== $user->photo) {
                Storage::disk('public')->delete($user->avatar);
            }

            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
            $user->avatar = $photoPath;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update data dosen jika ada
        if ($user->lecturer) {
            $user->lecturer->update([
                'nidn' => $validated['nidn'] ?? null,
                'nip' => $validated['nip'] ?? null,
                'front_title' => $validated['front_title'] ?? null,
                'back_title' => $validated['back_title'] ?? null,
                'academic_rank' => $validated['academic_rank'] ?? null,
                'sinta_id' => $validated['sinta_id'] ?? null,
                'scopus_id' => $validated['scopus_id'] ?? null,
                'google_scholar_id' => $validated['google_scholar_id'] ?? null,
                'orcid_id' => $validated['orcid_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);
        }

        $syncMsg = '';
        if ($user->lecturer && $request->input('action') === 'save_and_sync') {
            $syncRes = $syncService->sync($user->lecturer);
            $syncMsg = ' ' . $syncRes['message'];
        }

        return redirect()->route('profile.edit')->with('success', 'Profil dan foto pengguna berhasil diperbarui.' . $syncMsg);
    }

    /**
     * Sinkronkan profil akademik dosen dengan SINTA & Google Scholar.
     */
    public function syncAcademic(\App\Services\AcademicProfileSyncService $syncService)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->lecturer) {
            return redirect()->route('profile.edit')->with('error', 'Profil dosen tidak ditemukan.');
        }

        $res = $syncService->sync($user->lecturer);

        return redirect()->route('profile.edit')->with('success', $res['message']);
    }
}
