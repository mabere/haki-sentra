<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Http\Controllers;

use App\Models\IncentiveRequest;
use App\Models\IncentiveScheme;
use App\Models\IntellectualProperty;
use App\Models\Publication;
use App\Models\VerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncentiveController extends Controller
{
    /**
     * Helper to get or safely create basic lecturer profile.
     */
    private function getLecturerForUser($user)
    {
        if ($user->lecturer) {
            return $user->lecturer;
        }

        $dept = \App\Models\Department::first();
        if ($dept) {
            return \App\Models\Lecturer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department_id' => $dept->id,
                    'nidn' => $user->isAdminLppm() ? 'ADMIN-' . $user->id : '00' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                    'nip' => '1985' . str_pad($user->id, 14, '0', STR_PAD_LEFT),
                    'academic_rank' => $user->isAdminLppm() ? 'Administrator LPPM' : 'Tenaga Pengajar',
                    'phone' => $user->phone ?? '081200000000',
                ]
            );
        }

        return null;
    }

    /**
     * Dosen: List of their submitted incentive requests.
     */
    public function dosenIndex(Request $request)
    {
        $user = Auth::user();
        $lecturer = $this->getLecturerForUser($user);

        if (!$lecturer) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Profil dosen belum terdaftar.']);
        }

        $query = IncentiveRequest::where('lecturer_id', $lecturer->id)
            ->with(['scheme', 'claimable', 'reviewer']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $incentives = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_submitted' => IncentiveRequest::where('lecturer_id', $lecturer->id)->count(),
            'total_disbursed' => IncentiveRequest::where('lecturer_id', $lecturer->id)->where('status', 'disbursed')->sum('approved_amount'),
            'total_pending' => IncentiveRequest::where('lecturer_id', $lecturer->id)->whereIn('status', ['submitted', 'verified', 'approved_finance'])->count(),
        ];

        return view('incentives.dosen_index', compact('incentives', 'stats'));
    }

    /**
     * Dosen: Show form to submit a new incentive request.
     */
    public function dosenCreate(Request $request)
    {
        $user = Auth::user();
        $lecturer = $this->getLecturerForUser($user);

        if (!$lecturer) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Profil dosen belum terdaftar.']);
        }

        // 1. Get approved publications not already claimed in non-rejected request
        $claimedPubIds = IncentiveRequest::where('claimable_type', Publication::class)
            ->where('status', '!=', 'rejected')
            ->pluck('claimable_id');

        $availablePublications = Publication::where('user_id', $user->id)
            ->where('verification_status', 'approved')
            ->whereNotIn('id', $claimedPubIds)
            ->get();

        // 2. Get approved & granted IPRs not already claimed
        $claimedIprIds = IncentiveRequest::where('claimable_type', IntellectualProperty::class)
            ->where('status', '!=', 'rejected')
            ->pluck('claimable_id');

        $availableIpr = IntellectualProperty::where('user_id', $user->id)
            ->where('verification_status', 'approved')
            ->where('djki_status', 'granted')
            ->whereNotIn('id', $claimedIprIds)
            ->get();

        // 3. Active Schemes
        $schemes = IncentiveScheme::where('is_active', true)->get();

        // 4. Last used bank details for auto-fill
        $lastIncentive = IncentiveRequest::where('lecturer_id', $lecturer->id)->latest()->first();

        return view('incentives.dosen_create', compact(
            'availablePublications',
            'availableIpr',
            'schemes',
            'lastIncentive'
        ));
    }

    /**
     * Dosen: Store new incentive request.
     */
    public function dosenStore(Request $request)
    {
        $lecturer = $this->getLecturerForUser(Auth::user());

        if (!$lecturer) {
            abort(403);
        }

        $validated = $request->validate([
            'claimable_type' => ['required', 'string', 'in:' . Publication::class . ',' . IntellectualProperty::class],
            'claimable_id' => ['required', 'integer'],
            'incentive_scheme_id' => ['required', 'exists:incentive_schemes,id'],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_holder' => ['required', 'string', 'max:100'],
            'statement_letter' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'claimable_id.required' => 'Luaran yang akan diklaim wajib dipilih.',
            'incentive_scheme_id.required' => 'Skema tarif insentif wajib dipilih.',
            'bank_name.required' => 'Nama bank wajib diisi.',
            'bank_account_number.required' => 'Nomor rekening wajib diisi.',
            'bank_account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $claimableType = $validated['claimable_type'];
        $claimable = $claimableType::query()->findOrFail($validated['claimable_id']);

        if ((int) $claimable->user_id !== (int) Auth::id()) {
            abort(403, 'Luaran yang dipilih bukan milik akun Anda.');
        }

        $isEligible = $claimable instanceof Publication
            ? $claimable->verification_status === 'approved'
            : $claimable->verification_status === 'approved' && $claimable->djki_status === 'granted';

        if (!$isEligible) {
            return back()->withErrors(['claimable_id' => 'Luaran harus sudah disetujui dan memenuhi syarat untuk diajukan.']);
        }

        // Anti-duplicate check
        $exists = IncentiveRequest::where('claimable_type', $validated['claimable_type'])
            ->where('claimable_id', $validated['claimable_id'])
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($exists) {
            return back()->withErrors(['claimable_id' => 'Luaran ini sudah pernah diajukan insentif sebelumnya dan sedang diproses.']);
        }

        $scheme = IncentiveScheme::findOrFail($validated['incentive_scheme_id']);

        $letterPath = null;
        if ($request->hasFile('statement_letter')) {
            $letterPath = $request->file('statement_letter')->store('incentives/statements', 'private');
        }

        $incentive = IncentiveRequest::create([
            'lecturer_id' => $lecturer->id,
            'incentive_scheme_id' => $scheme->id,
            'claimable_type' => $validated['claimable_type'],
            'claimable_id' => $validated['claimable_id'],
            'requested_amount' => $scheme->amount,
            'approved_amount' => null,
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_holder' => $validated['bank_account_holder'],
            'statement_letter_file' => $letterPath,
            'status' => 'submitted',
        ]);

        VerificationLog::create([
            'verifiable_type' => IncentiveRequest::class,
            'verifiable_id' => $incentive->id,
            'user_id' => Auth::id(),
            'status_before' => 'draft',
            'status_after' => 'submitted',
            'notes' => 'Pengajuan insentif baru diajukan oleh dosen pengusul.',
        ]);

        return redirect()->route('dosen.incentives.index')
            ->with('success', 'Pengajuan insentif berhasil dikirim. Menunggu verifikasi LPPM & bagian keuangan.');
    }

    /**
     * Dosen: Show details of an incentive request.
     */
    public function dosenShow(IncentiveRequest $incentiveRequest)
    {
        $lecturer = $this->getLecturerForUser(Auth::user());

        if (Auth::user()->isDosen() && $lecturer && $incentiveRequest->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $incentiveRequest->load(['scheme', 'claimable', 'reviewer', 'verificationLogs.reviewer', 'lecturer.user']);

        return view('incentives.dosen_show', compact('incentiveRequest'));
    }

    /**
     * Admin LPPM: Manage all incentive requests.
     */
    public function adminIndex(Request $request)
    {
        $query = IncentiveRequest::with(['lecturer.user', 'scheme', 'claimable']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('lecturer.user', function ($u) use ($search) {
                $u->where('name', 'like', "%{$search}%");
            })->orWhere('bank_account_number', 'like', "%{$search}%")
              ->orWhere('bank_account_holder', 'like', "%{$search}%");
        }

        $incentives = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_claims' => IncentiveRequest::count(),
            'pending' => IncentiveRequest::whereIn('status', ['submitted', 'verified'])->count(),
            'disbursed_total' => IncentiveRequest::where('status', 'disbursed')->sum('approved_amount'),
            'disbursed_count' => IncentiveRequest::where('status', 'disbursed')->count(),
        ];

        return view('incentives.admin_index', compact('incentives', 'stats'));
    }

    /**
     * Admin LPPM: Review single incentive request.
     */
    public function adminShow(IncentiveRequest $incentiveRequest)
    {
        $incentiveRequest->load(['scheme', 'claimable', 'reviewer', 'verificationLogs.reviewer', 'lecturer.user', 'lecturer.department.faculty']);

        return view('incentives.admin_show', compact('incentiveRequest'));
    }

    /**
     * Admin LPPM: Process status change (verify, approve_finance, disburse, reject).
     */
    public function adminProcess(Request $request, IncentiveRequest $incentiveRequest)
    {
        if (in_array($incentiveRequest->status, ['disbursed', 'rejected'])) {
            return redirect()->route('admin.incentives.show', $incentiveRequest)
                ->with('error', 'Pengajuan insentif ini sudah selesai diproses dan tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'action' => ['required', 'string', 'in:verify,approve_finance,disburse,reject'],
            'approved_amount' => ['nullable', 'numeric', 'min:0'],
            'disbursed_at' => ['nullable', 'date'],
            'transfer_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $statusBefore = $incentiveRequest->status;
        $statusAfter = $statusBefore;

        if ($validated['action'] === 'verify') {
            $statusAfter = 'verified';
            $incentiveRequest->update([
                'status' => 'verified',
                'reviewed_by' => Auth::id(),
                'notes' => $validated['notes'] ?? 'Berkas administrasi dinyatakan valid oleh LPPM.',
            ]);
        } elseif ($validated['action'] === 'approve_finance') {
            $statusAfter = 'approved_finance';
            $approvedAmount = !empty($validated['approved_amount']) ? $validated['approved_amount'] : $incentiveRequest->requested_amount;
            $incentiveRequest->update([
                'status' => 'approved_finance',
                'approved_amount' => $approvedAmount,
                'reviewed_by' => Auth::id(),
                'notes' => $validated['notes'] ?? 'Disetujui pencairan dana oleh bagian keuangan.',
            ]);
        } elseif ($validated['action'] === 'disburse') {
            $statusAfter = 'disbursed';
            $proofPath = $incentiveRequest->transfer_proof_file;
            if ($request->hasFile('transfer_proof')) {
                $proofPath = $request->file('transfer_proof')->store('incentives/proofs', 'private');
            }

            $approvedAmount = $incentiveRequest->approved_amount ?? $incentiveRequest->requested_amount;

            $incentiveRequest->update([
                'status' => 'disbursed',
                'approved_amount' => $approvedAmount,
                'disbursed_at' => $validated['disbursed_at'] ?? now()->toDateString(),
                'transfer_proof_file' => $proofPath,
                'reviewed_by' => Auth::id(),
                'notes' => $validated['notes'] ?? 'Dana insentif telah berhasil dicairkan dan ditransfer ke rekening dosen.',
            ]);
        } elseif ($validated['action'] === 'reject') {
            $statusAfter = 'rejected';
            if (empty(trim($validated['notes'] ?? ''))) {
                return back()->withErrors(['notes' => 'Alasan penolakan wajib diisi.']);
            }
            $incentiveRequest->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'notes' => $validated['notes'],
            ]);
        }

        VerificationLog::create([
            'verifiable_type' => IncentiveRequest::class,
            'verifiable_id' => $incentiveRequest->id,
            'user_id' => Auth::id(),
            'status_before' => $statusBefore,
            'status_after' => $statusAfter,
            'notes' => $validated['notes'] ?? 'Status insentif diubah menjadi ' . $statusAfter,
        ]);

        return redirect()->route('admin.incentives.show', $incentiveRequest)
            ->with('success', 'Status pengajuan insentif berhasil diperbarui.');
    }

    /**
     * Download attached file.
     */
    public function downloadFile(IncentiveRequest $incentiveRequest, string $type)
    {
        $user = Auth::user();

        if ($user->isDosen()) {
            $lecturer = $this->getLecturerForUser($user);

            if (!$lecturer || $incentiveRequest->lecturer_id !== $lecturer->id) {
                abort(403, 'Anda tidak memiliki hak untuk mengakses berkas pengajuan ini.');
            }
        }

        $path = match ($type) {
            'proof' => $incentiveRequest->transfer_proof_file,
            'statement' => $incentiveRequest->statement_letter_file,
            default => abort(404),
        };

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        return Storage::disk('private')->response($path);
    }
}

