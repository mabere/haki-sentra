<?php

namespace App\Http\Controllers;

use App\Models\IncentiveRequest;
use App\Models\IntellectualProperty;
use App\Models\Publication;
use App\Models\RevisionComment;
use App\Models\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RevisionCommentController extends Controller
{
    /**
     * Store a new revision comment or response in the interactive thread.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'verifiable_type' => ['required', 'string', 'in:ipr,publication,incentive'],
            'verifiable_id' => ['required', 'integer'],
            'comment' => ['required', 'string', 'max:3000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,png,jpg,jpeg', 'max:10240'],
            'mark_as_resubmitted' => ['nullable'],
            'mark_as_revision_request' => ['nullable'],
        ]);

        $user = Auth::user();
        $target = match ($validated['verifiable_type']) {
            'ipr' => IntellectualProperty::findOrFail($validated['verifiable_id']),
            'publication' => Publication::findOrFail($validated['verifiable_id']),
            'incentive' => IncentiveRequest::findOrFail($validated['verifiable_id']),
        };

        // Authorization check
        $isOwner = false;
        if ($validated['verifiable_type'] === 'incentive') {
            $isOwner = $user->lecturer && $target->lecturer_id === $user->lecturer->id;
        } else {
            $isOwner = $target->user_id === $user->id;
        }

        $canManage = $user->isVerifikator() || $user->isAdminLppm();

        if (!$isOwner && !$canManage) {
            abort(403, 'Anda tidak memiliki hak untuk berpartisipasi pada catatan usulan ini.');
        }

        // Upload attachment if provided
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentType = null;
        $attachmentSize = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentType = strtoupper($file->getClientOriginalExtension());
            $sizeInBytes = $file->getSize();
            $attachmentSize = $sizeInBytes >= 1048576 
                ? number_format($sizeInBytes / 1048576, 1) . ' MB' 
                : number_format($sizeInBytes / 1024, 0) . ' KB';

            $attachmentPath = $file->store('revision_attachments', 'private');
        }

        // Create comment
        $comment = RevisionComment::create([
            'verifiable_type' => get_class($target),
            'verifiable_id' => $target->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_type' => $attachmentType,
            'attachment_size' => $attachmentSize,
        ]);

        // Status update logic
        if ($request->has('mark_as_resubmitted') && $isOwner) {
            $statusBefore = $target->verification_status ?? $target->status;
            
            if ($validated['verifiable_type'] === 'incentive') {
                $target->update(['status' => 'submitted']);
            } else {
                $target->update(['verification_status' => 'pending']);
            }

            VerificationLog::create([
                'verifiable_type' => get_class($target),
                'verifiable_id' => $target->id,
                'user_id' => $user->id,
                'status_before' => $statusBefore,
                'status_after' => 'pending',
                'notes' => 'Dosen telah menindaklanjuti catatan revisi dan mengajukan ulang berkas perbaikan.',
            ]);

            return back()->with('success', 'Tanggapan dan berkas revisi berhasil dikirim. Status usulan telah diajukan ulang ke Verifikator.');
        }

        if ($request->has('mark_as_revision_request') && $canManage) {
            $statusBefore = $target->verification_status ?? $target->status;

            if ($validated['verifiable_type'] === 'incentive') {
                $target->update(['status' => 'revision', 'rejection_reason' => $validated['comment']]);
            } else {
                $target->update(['verification_status' => 'revision', 'verification_note' => $validated['comment']]);
            }

            VerificationLog::create([
                'verifiable_type' => get_class($target),
                'verifiable_id' => $target->id,
                'user_id' => $user->id,
                'status_before' => $statusBefore,
                'status_after' => 'revision',
                'notes' => 'Verifikator meminta perbaikan berkas: ' . $validated['comment'],
            ]);

            return back()->with('success', 'Catatan revisi berhasil ditambahkan dan status usulan telah dialihkan ke Revisi.');
        }

        return back()->with('success', 'Catatan tanggapan berhasil dikirimkan ke dalam thread riwayat usulan.');
    }

    /**
     * Download attachment file securely.
     */
    public function downloadAttachment(RevisionComment $comment): BinaryFileResponse
    {
        $user = Auth::user();
        $target = $comment->verifiable;

        if (!$target) {
            abort(404, 'Data usulan tidak ditemukan.');
        }

        $isOwner = false;
        if ($comment->verifiable_type === IncentiveRequest::class) {
            $isOwner = $user->lecturer && $target->lecturer_id === $user->lecturer->id;
        } else {
            $isOwner = $target->user_id === $user->id;
        }

        $canManage = $user->isVerifikator() || $user->isAdminLppm();

        if (!$isOwner && !$canManage) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh lampiran revisi ini.');
        }

        if (!$comment->attachment_path || !Storage::disk('private')->exists($comment->attachment_path)) {
            abort(404, 'Berkas lampiran tidak ditemukan di server penyimpanan.');
        }

        $fileName = $comment->attachment_name ?: basename($comment->attachment_path);
        return Storage::disk('private')->download($comment->attachment_path, $fileName);
    }
}
