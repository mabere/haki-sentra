<?php

namespace App\Http\Controllers;

use App\Models\IntellectualProperty;
use App\Models\IprInventor;
use App\Models\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IntellectualPropertyController extends Controller
{
    /**
     * Display a listing of intellectual properties.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = IntellectualProperty::with(['inventors', 'user']);

        // Dosen only sees their own IPRs unless Admin LPPM
        if ($user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        // Search Filter
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Type Filter
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('ipr_type', $request->type);
        }

        // DJKI Status Filter
        if ($request->filled('djki_status') && $request->djki_status !== 'all') {
            $query->where('djki_status', $request->djki_status);
        }

        // Verification Status Filter
        if ($request->filled('verification_status') && $request->verification_status !== 'all') {
            $query->where('verification_status', $request->verification_status);
        }

        $iprs = $query->latest()->paginate(10)->withQueryString();

        return view('ipr.index', compact('iprs'));
    }

    /**
     * Show the form for creating a new intellectual property.
     */
    public function create()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        return view('ipr.create', compact('lecturer'));
    }

    /**
     * Store a newly created intellectual property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:1000'],
            'ipr_type' => ['required', 'string', 'max:50'],
            'sub_type' => ['nullable', 'string', 'max:100'],
            'application_number' => ['nullable', 'string', 'max:100'],
            'application_date' => ['nullable', 'date'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'grant_date' => ['nullable', 'date'],
            'djki_status' => ['required', 'string', 'max:50'],
            'rights_holder' => ['required', 'string', 'max:255'],
            'certificate' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'description_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'inventors' => ['required', 'array', 'min:1'],
            'inventors.*.name' => ['required', 'string', 'max:255'],
            'inventors.*.type' => ['required', 'string', 'in:internal_lecturer,student,external'],
            'inventors.*.role' => ['required', 'string', 'in:inventor_utama,inventor_anggota'],
            'inventors.*.contribution_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'inventors.*.affiliation' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Judul ciptaan / invensi wajib diisi.',
            'ipr_type.required' => 'Jenis HKI wajib dipilih.',
            'djki_status.required' => 'Status permohonan DJKI wajib dipilih.',
            'rights_holder.required' => 'Pemegang hak wajib diisi.',
            'inventors.required' => 'Minimal harus ada 1 inventor.',
            'certificate.mimes' => 'Berkas sertifikat harus berformat PDF.',
            'certificate.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('ipr/certificates', 'private');
        }

        $descriptionPath = null;
        if ($request->hasFile('description_file')) {
            $descriptionPath = $request->file('description_file')->store('ipr/descriptions', 'private');
        }

        $ipr = IntellectualProperty::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'ipr_type' => $validated['ipr_type'],
            'sub_type' => $validated['sub_type'] ?? null,
            'application_number' => $validated['application_number'] ?? null,
            'application_date' => $validated['application_date'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'grant_date' => $validated['grant_date'] ?? null,
            'djki_status' => $validated['djki_status'],
            'rights_holder' => $validated['rights_holder'],
            'certificate_file' => $certificatePath,
            'description_file' => $descriptionPath,
            'verification_status' => 'pending',
        ]);

        // Save inventors
        $lecturer = Auth::user()->lecturer;
        foreach ($request->input('inventors', []) as $index => $inventorData) {
            $lecturerId = null;
            if ($inventorData['type'] === 'internal_lecturer' && $lecturer && (
                stripos($inventorData['name'], Auth::user()->name) !== false || $index === 0
            )) {
                $lecturerId = $lecturer->id;
            }

            IprInventor::create([
                'intellectual_property_id' => $ipr->id,
                'lecturer_id' => $lecturerId,
                'inventor_name' => $inventorData['name'],
                'inventor_type' => $inventorData['type'],
                'role' => $inventorData['role'],
                'inventor_order' => $index + 1,
                'contribution_percentage' => !empty($inventorData['contribution_percentage']) ? $inventorData['contribution_percentage'] : null,
                'affiliation' => $inventorData['affiliation'] ?? 'Universitas',
            ]);
        }

        // Verification log
        VerificationLog::create([
            'verifiable_type' => IntellectualProperty::class,
            'verifiable_id' => $ipr->id,
            'user_id' => Auth::id(),
            'status_before' => 'draft',
            'status_after' => 'pending',
            'notes' => 'Karya HKI baru didaftarkan oleh dosen pengusul.',
        ]);

        return redirect()->route('dosen.ipr.index')
            ->with('success', 'Data karya HKI berhasil didaftarkan dan diajukan untuk verifikasi.');
    }

    /**
     * Display the specified intellectual property.
     */
    public function show(IntellectualProperty $intellectualProperty)
    {
        $user = Auth::user();

        if ($user->isDosen() && $intellectualProperty->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk melihat data HKI ini.');
        }

        $intellectualProperty->load(['inventors.lecturer.department', 'verifier', 'verificationLogs.reviewer', 'incentiveRequests.scheme']);

        return view('ipr.show', compact('intellectualProperty'));
    }

    /**
     * Show the form for editing the specified intellectual property.
     */
    public function edit(IntellectualProperty $intellectualProperty)
    {
        $user = Auth::user();

        if ($user->isDosen() && $intellectualProperty->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit data HKI ini.');
        }

        if ($intellectualProperty->verification_status === 'approved' && !$user->isAdminLppm()) {
            return redirect()->route('dosen.ipr.show', $intellectualProperty)
                ->with('info', 'Data HKI yang sudah disetujui tidak dapat diubah kembali.');
        }

        $intellectualProperty->load('inventors');

        return view('ipr.edit', compact('intellectualProperty'));
    }

    /**
     * Update the specified intellectual property.
     */
    public function update(Request $request, IntellectualProperty $intellectualProperty)
    {
        $user = Auth::user();

        if ($user->isDosen() && $intellectualProperty->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:1000'],
            'ipr_type' => ['required', 'string', 'max:50'],
            'sub_type' => ['nullable', 'string', 'max:100'],
            'application_number' => ['nullable', 'string', 'max:100'],
            'application_date' => ['nullable', 'date'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'grant_date' => ['nullable', 'date'],
            'djki_status' => ['required', 'string', 'max:50'],
            'rights_holder' => ['required', 'string', 'max:255'],
            'certificate' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'description_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'inventors' => ['required', 'array', 'min:1'],
            'inventors.*.name' => ['required', 'string', 'max:255'],
            'inventors.*.type' => ['required', 'string', 'in:internal_lecturer,student,external'],
            'inventors.*.role' => ['required', 'string', 'in:inventor_utama,inventor_anggota'],
            'inventors.*.contribution_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'inventors.*.affiliation' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('certificate')) {
            if ($intellectualProperty->certificate_file && Storage::disk('private')->exists($intellectualProperty->certificate_file)) {
                Storage::disk('private')->delete($intellectualProperty->certificate_file);
            }
            $intellectualProperty->certificate_file = $request->file('certificate')->store('ipr/certificates', 'private');
        }

        if ($request->hasFile('description_file')) {
            if ($intellectualProperty->description_file && Storage::disk('private')->exists($intellectualProperty->description_file)) {
                Storage::disk('private')->delete($intellectualProperty->description_file);
            }
            $intellectualProperty->description_file = $request->file('description_file')->store('ipr/descriptions', 'private');
        }

        $statusBefore = $intellectualProperty->verification_status;
        $statusAfter = $statusBefore === 'revision' ? 'pending' : $statusBefore;

        $intellectualProperty->update([
            'title' => $validated['title'],
            'ipr_type' => $validated['ipr_type'],
            'sub_type' => $validated['sub_type'] ?? null,
            'application_number' => $validated['application_number'] ?? null,
            'application_date' => $validated['application_date'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
            'grant_date' => $validated['grant_date'] ?? null,
            'djki_status' => $validated['djki_status'],
            'rights_holder' => $validated['rights_holder'],
            'verification_status' => $statusAfter,
        ]);

        // Re-sync inventors
        $intellectualProperty->inventors()->delete();
        $lecturer = Auth::user()->lecturer;

        foreach ($request->input('inventors', []) as $index => $inventorData) {
            $lecturerId = null;
            if ($inventorData['type'] === 'internal_lecturer' && $lecturer && (
                stripos($inventorData['name'], Auth::user()->name) !== false || $index === 0
            )) {
                $lecturerId = $lecturer->id;
            }

            IprInventor::create([
                'intellectual_property_id' => $intellectualProperty->id,
                'lecturer_id' => $lecturerId,
                'inventor_name' => $inventorData['name'],
                'inventor_type' => $inventorData['type'],
                'role' => $inventorData['role'],
                'inventor_order' => $index + 1,
                'contribution_percentage' => !empty($inventorData['contribution_percentage']) ? $inventorData['contribution_percentage'] : null,
                'affiliation' => $inventorData['affiliation'] ?? 'Universitas',
            ]);
        }

        if ($statusBefore === 'revision') {
            VerificationLog::create([
                'verifiable_type' => IntellectualProperty::class,
                'verifiable_id' => $intellectualProperty->id,
                'user_id' => Auth::id(),
                'status_before' => 'revision',
                'status_after' => 'pending',
                'notes' => 'Perbaikan data karya HKI telah dikirimkan kembali oleh dosen.',
            ]);
        }

        return redirect()->route('dosen.ipr.show', $intellectualProperty)
            ->with('success', 'Data karya HKI berhasil diperbarui.');
    }

    /**
     * Remove the specified intellectual property.
     */
    public function destroy(IntellectualProperty $intellectualProperty)
    {
        $user = Auth::user();

        if ($user->isDosen() && $intellectualProperty->user_id !== $user->id) {
            abort(403);
        }

        if ($intellectualProperty->verification_status === 'approved' && !$user->isAdminLppm()) {
            return redirect()->back()->withErrors(['error' => 'Data HKI yang sudah disetujui tidak dapat dihapus.']);
        }

        if ($intellectualProperty->certificate_file && Storage::disk('private')->exists($intellectualProperty->certificate_file)) {
            Storage::disk('private')->delete($intellectualProperty->certificate_file);
        }

        if ($intellectualProperty->description_file && Storage::disk('private')->exists($intellectualProperty->description_file)) {
            Storage::disk('private')->delete($intellectualProperty->description_file);
        }

        $intellectualProperty->inventors()->delete();
        $intellectualProperty->delete();

        return redirect()->route('dosen.ipr.index')
            ->with('success', 'Data karya HKI berhasil dihapus.');
    }

    /**
     * Download or view attached file.
     */
    public function downloadFile(IntellectualProperty $intellectualProperty, string $type)
    {
        $user = Auth::user();

        if ($user->isDosen() && $intellectualProperty->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengakses berkas HKI ini.');
        }

        $path = match ($type) {
            'description' => $intellectualProperty->description_file,
            'certificate' => $intellectualProperty->certificate_file,
            default => abort(404),
        };

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        return Storage::disk('private')->response($path);
    }
}

