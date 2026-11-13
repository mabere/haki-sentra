<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\VerificationLog;
use App\Services\AcademicPublicationImportService;
use App\Services\CrossrefService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    /**
     * Display a listing of publications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Publication::with(['authors', 'user']);

        // Dosen only sees their own publications unless Admin LPPM
        if ($user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        // Search Filter
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('journal_name', 'like', "%{$search}%")
                  ->orWhere('doi', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('verification_status', $request->status);
        }

        // Indexation Filter
        if ($request->filled('indexation') && $request->indexation !== 'all') {
            $query->where('indexation', $request->indexation);
        }

        // Year Filter
        if ($request->filled('year') && $request->year !== 'all') {
            $query->where('publication_year', $request->year);
        }

        $publications = $query->latest()->paginate(10)->withQueryString();

        // Get unique years for filter
        $availableYears = Publication::select('publication_year')
            ->distinct()
            ->orderByDesc('publication_year')
            ->pluck('publication_year');

        return view('publications.index', compact('publications', 'availableYears'));
    }

    /**
     * Show the form for creating a new publication.
     */
    public function create()
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        return view('publications.create', compact('lecturer'));
    }

    /**
     * Auto-fetch metadata via Crossref API (AJAX endpoint).
     */
    public function fetchDoi(Request $request, CrossrefService $crossrefService)
    {
        $request->validate([
            'doi' => ['required', 'string'],
        ]);

        $result = $crossrefService->fetchByDoi($request->input('doi'));

        return response()->json($result);
    }

    /**
     * Preview publications from SINTA and Google Scholar before importing.
     */
    public function previewImport(AcademicPublicationImportService $importService)
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        if (!$lecturer) {
            return response()->json([
                'success' => false,
                'message' => 'Profil dosen tidak ditemukan.',
                'articles' => [],
            ], 422);
        }

        $result = $importService->fetchAndDeduplicate($lecturer);

        return response()->json($result);
    }

    /**
     * Execute import for selected publications.
     */
    public function executeImport(Request $request, AcademicPublicationImportService $importService)
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        if (!$lecturer) {
            return response()->json([
                'success' => false,
                'message' => 'Profil dosen tidak ditemukan.',
            ], 422);
        }

        $request->validate([
            'articles' => ['required', 'array', 'min:1'],
            'articles.*.title' => ['required', 'string'],
            'articles.*.journal_name' => ['required', 'string'],
        ], [
            'articles.required' => 'Pilih minimal 1 artikel untuk diimpor.',
            'articles.min' => 'Pilih minimal 1 artikel untuk diimpor.',
        ]);

        $count = $importService->importSelected($lecturer, $request->input('articles', []));

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "Berhasil mengimpor {$count} artikel publikasi ilmiah ke SIM-LPPM!",
        ]);
    }

    /**
     * Store a newly created publication.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:1000'],
            'publication_type' => ['required', 'string', 'max:100'],
            'journal_name' => ['required', 'string', 'max:255'],
            'doi' => ['nullable', 'string', 'max:255'],
            'issn_isbn' => ['nullable', 'string', 'max:100'],
            'volume' => ['nullable', 'string', 'max:50'],
            'issue' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'string', 'max:50'],
            'publication_year' => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'publication_date' => ['nullable', 'date'],
            'url' => ['nullable', 'url', 'max:500'],
            'indexation' => ['nullable', 'string', 'max:50'],
            'quartile' => ['nullable', 'string', 'max:10'],
            'sinta_grade' => ['nullable', 'string', 'max:10'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // Max 10MB PDF
            'loa' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*.name' => ['required', 'string', 'max:255'],
            'authors.*.type' => ['required', 'string', 'in:internal_lecturer,student,external'],
            'authors.*.role' => ['required', 'string', 'in:first_author,corresponding,co_author'],
            'authors.*.affiliation' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Judul publikasi wajib diisi.',
            'publication_type.required' => 'Jenis publikasi wajib dipilih.',
            'journal_name.required' => 'Nama jurnal/penerbit wajib diisi.',
            'publication_year.required' => 'Tahun terbit wajib diisi.',
            'authors.required' => 'Minimal harus ada 1 penulis.',
            'file.mimes' => 'Berkas naskah harus berformat PDF.',
            'file.max' => 'Ukuran berkas naskah maksimal 10 MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('publications/manuscripts', 'private');
        }

        $loaPath = null;
        if ($request->hasFile('loa')) {
            $loaPath = $request->file('loa')->store('publications/loa', 'private');
        }

        $publication = Publication::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'publication_type' => $validated['publication_type'],
            'journal_name' => $validated['journal_name'],
            'doi' => $validated['doi'] ?? null,
            'issn_isbn' => $validated['issn_isbn'] ?? null,
            'volume' => $validated['volume'] ?? null,
            'issue' => $validated['issue'] ?? null,
            'pages' => $validated['pages'] ?? null,
            'publication_year' => $validated['publication_year'],
            'publication_date' => $validated['publication_date'] ?? null,
            'url' => $validated['url'] ?? null,
            'indexation' => $validated['indexation'] ?? null,
            'quartile' => $validated['quartile'] ?? null,
            'sinta_grade' => $validated['sinta_grade'] ?? null,
            'file_path' => $filePath,
            'loa_path' => $loaPath,
            'verification_status' => 'pending',
        ]);

        // Save authors
        $lecturer = Auth::user()->lecturer;
        foreach ($request->input('authors', []) as $index => $authorData) {
            $lecturerId = null;
            if ($authorData['type'] === 'internal_lecturer' && $lecturer && (
                stripos($authorData['name'], Auth::user()->name) !== false || $index === 0
            )) {
                $lecturerId = $lecturer->id;
            }

            PublicationAuthor::create([
                'publication_id' => $publication->id,
                'lecturer_id' => $lecturerId,
                'author_name' => $authorData['name'],
                'author_type' => $authorData['type'],
                'author_role' => $authorData['role'],
                'author_order' => $index + 1,
                'affiliation' => $authorData['affiliation'] ?? 'Universitas',
            ]);
        }

        // Log creation
        VerificationLog::create([
            'verifiable_type' => Publication::class,
            'verifiable_id' => $publication->id,
            'user_id' => Auth::id(),
            'status_before' => 'draft',
            'status_after' => 'pending',
            'notes' => 'Publikasi baru didaftarkan oleh dosen pengusul.',
        ]);

        return redirect()->route('dosen.publications.index')
            ->with('success', 'Data publikasi ilmiah berhasil disimpan dan diajukan untuk verifikasi.');
    }

    /**
     * Display the specified publication.
     */
    public function show(Publication $publication)
    {
        $user = Auth::user();

        // Check ownership if Dosen
        if ($user->isDosen() && $publication->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk melihat publikasi ini.');
        }

        $publication->load(['authors.lecturer.department', 'verifier', 'verificationLogs.reviewer', 'incentiveRequests.scheme']);

        return view('publications.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified publication.
     */
    public function edit(Publication $publication)
    {
        $user = Auth::user();

        if ($user->isDosen() && $publication->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit publikasi ini.');
        }

        if ($publication->verification_status === 'approved' && !$user->isAdminLppm()) {
            return redirect()->route('dosen.publications.show', $publication)
                ->with('info', 'Publikasi yang sudah disetujui tidak dapat diubah kembali.');
        }

        $publication->load('authors');

        return view('publications.edit', compact('publication'));
    }

    /**
     * Update the specified publication.
     */
    public function update(Request $request, Publication $publication)
    {
        $user = Auth::user();

        if ($publication->verification_status === 'approved' && !$user->isAdminLppm()) {
            abort(403, 'Publikasi yang sudah disetujui tidak dapat diubah.');
        }

        if ($user->isDosen() && $publication->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:1000'],
            'publication_type' => ['required', 'string', 'max:100'],
            'journal_name' => ['required', 'string', 'max:255'],
            'doi' => ['nullable', 'string', 'max:255'],
            'issn_isbn' => ['nullable', 'string', 'max:100'],
            'volume' => ['nullable', 'string', 'max:50'],
            'issue' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'string', 'max:50'],
            'publication_year' => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'publication_date' => ['nullable', 'date'],
            'url' => ['nullable', 'url', 'max:500'],
            'indexation' => ['nullable', 'string', 'max:50'],
            'quartile' => ['nullable', 'string', 'max:10'],
            'sinta_grade' => ['nullable', 'string', 'max:10'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'loa' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'authors' => ['required', 'array', 'min:1'],
            'authors.*.name' => ['required', 'string', 'max:255'],
            'authors.*.type' => ['required', 'string', 'in:internal_lecturer,student,external'],
            'authors.*.role' => ['required', 'string', 'in:first_author,corresponding,co_author'],
            'authors.*.affiliation' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('file')) {
            if ($publication->file_path && Storage::disk('private')->exists($publication->file_path)) {
                Storage::disk('private')->delete($publication->file_path);
            }
            $publication->file_path = $request->file('file')->store('publications/manuscripts', 'private');
        }

        if ($request->hasFile('loa')) {
            if ($publication->loa_path && Storage::disk('private')->exists($publication->loa_path)) {
                Storage::disk('private')->delete($publication->loa_path);
            }
            $publication->loa_path = $request->file('loa')->store('publications/loa', 'private');
        }

        // If previously under revision, resubmit to pending
        $statusBefore = $publication->verification_status;
        $statusAfter = $statusBefore === 'revision' ? 'pending' : $statusBefore;

        $publication->update([
            'title' => $validated['title'],
            'publication_type' => $validated['publication_type'],
            'journal_name' => $validated['journal_name'],
            'doi' => $validated['doi'] ?? null,
            'issn_isbn' => $validated['issn_isbn'] ?? null,
            'volume' => $validated['volume'] ?? null,
            'issue' => $validated['issue'] ?? null,
            'pages' => $validated['pages'] ?? null,
            'publication_year' => $validated['publication_year'],
            'publication_date' => $validated['publication_date'] ?? null,
            'url' => $validated['url'] ?? null,
            'indexation' => $validated['indexation'] ?? null,
            'quartile' => $validated['quartile'] ?? null,
            'sinta_grade' => $validated['sinta_grade'] ?? null,
            'verification_status' => $statusAfter,
        ]);

        // Re-sync authors
        $publication->authors()->delete();
        $lecturer = Auth::user()->lecturer;

        foreach ($request->input('authors', []) as $index => $authorData) {
            $lecturerId = null;
            if ($authorData['type'] === 'internal_lecturer' && $lecturer && (
                stripos($authorData['name'], Auth::user()->name) !== false || $index === 0
            )) {
                $lecturerId = $lecturer->id;
            }

            PublicationAuthor::create([
                'publication_id' => $publication->id,
                'lecturer_id' => $lecturerId,
                'author_name' => $authorData['name'],
                'author_type' => $authorData['type'],
                'author_role' => $authorData['role'],
                'author_order' => $index + 1,
                'affiliation' => $authorData['affiliation'] ?? 'Universitas',
            ]);
        }

        if ($statusBefore === 'revision') {
            VerificationLog::create([
                'verifiable_type' => Publication::class,
                'verifiable_id' => $publication->id,
                'user_id' => Auth::id(),
                'status_before' => 'revision',
                'status_after' => 'pending',
                'notes' => 'Perbaikan data publikasi telah dikirimkan kembali oleh dosen.',
            ]);
        }

        return redirect()->route('dosen.publications.show', $publication)
            ->with('success', 'Data publikasi ilmiah berhasil diperbarui.');
    }

    /**
     * Remove the specified publication.
     */
    public function destroy(Publication $publication)
    {
        $user = Auth::user();

        if ($user->isDosen() && $publication->user_id !== $user->id) {
            abort(403);
        }

        if ($publication->verification_status === 'approved' && !$user->isAdminLppm()) {
            return redirect()->back()->withErrors(['error' => 'Publikasi yang sudah disetujui tidak dapat dihapus.']);
        }

        if ($publication->file_path && Storage::disk('private')->exists($publication->file_path)) {
            Storage::disk('private')->delete($publication->file_path);
        }

        if ($publication->loa_path && Storage::disk('private')->exists($publication->loa_path)) {
            Storage::disk('private')->delete($publication->loa_path);
        }

        $publication->authors()->delete();
        $publication->delete();

        return redirect()->route('dosen.publications.index')
            ->with('success', 'Data publikasi ilmiah berhasil dihapus.');
    }

    /**
     * Download or view attached file.
     */
    public function downloadFile(Publication $publication, string $type)
    {
        $user = Auth::user();

        if ($user->isDosen() && $publication->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk mengakses berkas publikasi ini.');
        }

        $path = match ($type) {
            'loa' => $publication->loa_path,
            'manuscript' => $publication->file_path,
            default => abort(404),
        };

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        return Storage::disk('private')->response($path);
    }
}

