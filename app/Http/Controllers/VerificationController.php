<?php

namespace App\Http\Controllers;

use App\Models\IntellectualProperty;
use App\Models\Publication;
use App\Models\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Display publications queue for verification.
     */
    public function publicationsIndex(Request $request)
    {
        $query = Publication::with(['user.lecturer.department.faculty', 'authors', 'verifier']);

        // Filter status (default to pending if not specified, or all)
        $status = $request->input('status', 'pending');
        if ($status !== 'all') {
            $query->where('verification_status', $status);
        }

        // Search keyword
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('journal_name', 'like', "%{$search}%")
                  ->orWhere('doi', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter indexation
        if ($request->filled('indexation') && $request->indexation !== 'all') {
            $query->where('indexation', $request->indexation);
        }

        $publications = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'pending' => Publication::where('verification_status', 'pending')->count(),
            'revision' => Publication::where('verification_status', 'revision')->count(),
            'approved' => Publication::where('verification_status', 'approved')->count(),
            'rejected' => Publication::where('verification_status', 'rejected')->count(),
        ];

        return view('verification.publications_index', compact('publications', 'counts'));
    }

    /**
     * Show review page for a publication.
     */
    public function publicationReview(Publication $publication)
    {
        $publication->load(['user.lecturer.department.faculty', 'authors.lecturer', 'verifier', 'verificationLogs.reviewer']);

        return view('verification.publication_review', compact('publication'));
    }

    /**
     * Process verification decision for a publication.
     */
    public function publicationVerify(Request $request, Publication $publication)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:approve,revision,reject'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $statusBefore = $publication->verification_status;
        $statusAfter = match ($validated['action']) {
            'approve' => 'approved',
            'revision' => 'revision',
            'reject' => 'rejected',
        };

        // Note is required if revision or rejection
        if (in_array($statusAfter, ['revision', 'rejected']) && empty(trim($validated['notes'] ?? ''))) {
            return back()->withErrors(['notes' => 'Catatan verifikator wajib diisi jika meminta revisi atau menolak.']);
        }

        $publication->update([
            'verification_status' => $statusAfter,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'verification_note' => $validated['notes'] ?? null,
        ]);

        // Audit Log
        VerificationLog::create([
            'verifiable_type' => Publication::class,
            'verifiable_id' => $publication->id,
            'user_id' => Auth::id(),
            'status_before' => $statusBefore,
            'status_after' => $statusAfter,
            'notes' => $validated['notes'] ?? 'Publikasi diverifikasi dan disetujui oleh LPPM.',
        ]);

        $message = match ($statusAfter) {
            'approved' => 'Publikasi berhasil disetujui (Approved).',
            'revision' => 'Permintaan revisi berhasil dikirim ke dosen pengusul.',
            'rejected' => 'Publikasi telah ditandai ditolak.',
        };

        return redirect()->route('verification.publications.index')->with('success', $message);
    }

    /**
     * Display IPR queue for verification.
     */
    public function iprIndex(Request $request)
    {
        $query = IntellectualProperty::with(['user.lecturer.department.faculty', 'inventors', 'verifier']);

        $status = $request->input('status', 'pending');
        if ($status !== 'all') {
            $query->where('verification_status', $status);
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('ipr_type', $request->type);
        }

        $iprs = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'pending' => IntellectualProperty::where('verification_status', 'pending')->count(),
            'revision' => IntellectualProperty::where('verification_status', 'revision')->count(),
            'approved' => IntellectualProperty::where('verification_status', 'approved')->count(),
            'rejected' => IntellectualProperty::where('verification_status', 'rejected')->count(),
        ];

        return view('verification.ipr_index', compact('iprs', 'counts'));
    }

    /**
     * Show review page for an IPR.
     */
    public function iprReview(IntellectualProperty $intellectualProperty)
    {
        $intellectualProperty->load(['user.lecturer.department.faculty', 'inventors.lecturer', 'verifier', 'verificationLogs.reviewer']);

        return view('verification.ipr_review', compact('intellectualProperty'));
    }

    /**
     * Process verification decision for an IPR.
     */
    public function iprVerify(Request $request, IntellectualProperty $intellectualProperty)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:approve,revision,reject'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'djki_status' => ['nullable', 'string', 'in:draft,diajukan,pemeriksaan,granted,ditolak'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'grant_date' => ['nullable', 'date'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $statusBefore = $intellectualProperty->verification_status;
        $statusAfter = match ($validated['action']) {
            'approve' => 'approved',
            'revision' => 'revision',
            'reject' => 'rejected',
        };

        if (in_array($statusAfter, ['revision', 'rejected']) && empty(trim($validated['notes'] ?? ''))) {
            return back()->withErrors(['notes' => 'Catatan verifikator wajib diisi jika meminta revisi atau menolak.']);
        }

        $updateData = [
            'verification_status' => $statusAfter,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'verification_note' => $validated['notes'] ?? null,
        ];

        // Update status DJKI jika ditentukan
        if (!empty($validated['djki_status'])) {
            $updateData['djki_status'] = $validated['djki_status'];
        } elseif ($statusAfter === 'approved' && $intellectualProperty->djki_status === 'draft') {
            $updateData['djki_status'] = 'diajukan';
        }

        // Update nomor registrasi / pencatatan jika ada
        if ($request->has('registration_number')) {
            $updateData['registration_number'] = $validated['registration_number'] ?: null;
        }

        // Update tanggal granted jika ada
        if ($request->has('grant_date')) {
            $updateData['grant_date'] = $validated['grant_date'] ?: null;
        }

        // Upload sertifikat PDF jika dilampirkan oleh verifikator/LPPM
        if ($request->hasFile('certificate_file')) {
            $path = $request->file('certificate_file')->store('certificates', 'private');
            $updateData['certificate_file'] = $path;
        }

        $intellectualProperty->update($updateData);

        VerificationLog::create([
            'verifiable_type' => IntellectualProperty::class,
            'verifiable_id' => $intellectualProperty->id,
            'user_id' => Auth::id(),
            'status_before' => $statusBefore,
            'status_after' => $statusAfter,
            'notes' => $validated['notes'] ?? 'Karya HKI diverifikasi dan disetujui oleh Ketua LPPM / Tim Penilai.',
        ]);

        $message = match ($statusAfter) {
            'approved' => 'Karya HKI berhasil disetujui (Approved) oleh Ketua LPPM.',
            'revision' => 'Permintaan revisi berkas berhasil dikirim ke dosen/pemohon.',
            'rejected' => 'Karya HKI telah ditandai ditolak.',
        };

        return redirect()->route('verification.ipr.index')->with('success', $message);
    }

    /**
     * Display printable official LPPM verification certificate / recommendation letter.
     */
    public function iprCertificate(IntellectualProperty $intellectualProperty)
    {
        $intellectualProperty->load(['user.lecturer.department.faculty', 'inventors.lecturer', 'verifier']);

        return view('verification.ipr_certificate', compact('intellectualProperty'));
    }

    /**
     * Display all verification logs audit trail.
     */
    public function logsIndex(Request $request)
    {
        $query = VerificationLog::with(['reviewer', 'verifiable']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('notes', 'like', "%{$search}%");
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('verification.logs_index', compact('logs'));
    }

    /**
     * Download verification logs audit trail as a plain text (.txt) file.
     */
    public function logsDownload(Request $request)
    {
        $query = VerificationLog::with(['reviewer', 'verifiable'])->latest('id');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('notes', 'like', "%{$search}%");
        }

        $filename = 'log-verifikasi-' . now()->format('Y-m-d_H-i-s') . '.txt';
        $institution = app_setting('institution_legal_name', 'Universitas Panca Sakti Bekasi');
        $totalCount = (clone $query)->count();
        $filterNote = $request->filled('q') ? $request->input('q') : '-';

        return response()->streamDownload(function () use ($query, $institution, $totalCount, $filterNote) {
            echo "========================================================================\n";
            echo "LOG RIWAYAT & AUDIT TRAIL VERIFIKASI - {$institution}\n";
            echo "Diekspor pada : " . now()->format('d/m/Y H:i:s') . "\n";
            echo "Filter Catatan: {$filterNote}\n";
            echo "Total Catatan : {$totalCount}\n";
            echo "========================================================================\n\n";

            if ($totalCount === 0) {
                echo "Belum ada riwayat aktivitas verifikasi yang tercatat.\n";
                return;
            }

            $no = 1;
            foreach ($query->lazy(500) as $log) {
                $type = match ($log->verifiable_type) {
                    Publication::class => 'Publikasi',
                    IntellectualProperty::class => 'HKI',
                    default => 'Insentif',
                };
                $title = $log->verifiable ? ($log->verifiable->title ?? 'Pengajuan #' . $log->verifiable_id) : 'Objek telah dihapus';
                $actor = $log->reviewer ? $log->reviewer->name : 'Sistem';
                $time = $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-';
                $notes = $log->notes ?: '-';

                echo "[{$no}] {$time} | {$type} | {$title}\n";
                echo "    Aktor  : {$actor}\n";
                echo "    Status : {$log->status_before} -> {$log->status_after}\n";
                echo "    Catatan: {$notes}\n";
                echo "------------------------------------------------------------------------\n";
                $no++;
            }
        }, $filename, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

    /**
     * Delete verification logs (all or older than selected days).
     */
    public function logsDestroy(Request $request)
    {
        $validated = $request->validate([
            'older_than' => ['required', 'in:all,30,90,365'],
        ]);

        $query = VerificationLog::query();
        $scopeLabel = 'semua';

        if ($validated['older_than'] !== 'all') {
            $days = (int) $validated['older_than'];
            $query->where('created_at', '<', now()->subDays($days));
            $scopeLabel = "lebih dari {$days} hari";
        }

        $deletedCount = $query->delete();

        if ($deletedCount === 0) {
            return redirect()->route('verification.logs')
                ->with('info', "Tidak ada log verifikasi ({$scopeLabel}) yang perlu dihapus.");
        }

        return redirect()->route('verification.logs')
            ->with('success', "Berhasil menghapus {$deletedCount} catatan log verifikasi ({$scopeLabel}).");
    }
}

