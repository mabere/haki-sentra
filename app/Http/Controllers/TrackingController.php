<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Http\Controllers;

use App\Models\IntellectualProperty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Track status of an Intellectual Property application.
     */
    public function track(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));

        if (strlen($code) < 6 || strlen($code) > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor permohonan atau kode usulan HKI.',
            ], 422);
        }

        $ipr = IntellectualProperty::query()->where(function ($q) use ($code) {
            $q->where('application_number', $code)->orWhere('registration_number', $code);
        })->first();

        if (!$ipr) {
            return response()->json([
                'success' => false,
                'message' => "Permohonan dengan nomor/kode '{$code}' tidak ditemukan dalam sistem Sentra HKI. Pastikan nomor permohonan Anda sudah benar.",
            ], 404);
        }

        // Mapping labels
        $typeLabels = [
            'hak_cipta' => 'Hak Cipta',
            'paten' => 'Paten Biasa',
            'paten_sederhana' => 'Paten Sederhana',
            'merek' => 'Merek Dagang & Jasa',
            'desain_industri' => 'Desain Industri',
            'lainnya' => 'Kekayaan Intelektual Lainnya',
        ];

        $djkiLabels = [
            'draft' => 'Pemberkasan Usulan',
            'diajukan' => 'Diajukan ke DJKI Kemenkumham',
            'pemeriksaan' => 'Pemeriksaan Substantif DJKI',
            'granted' => 'Sertifikat Terbit (Granted)',
            'ditolak' => 'Ditolak DJKI',
        ];

        $verificationLabels = [
            'pending' => 'Menunggu Verifikasi LPPM',
            'revision' => 'Perlu Revisi Dokumen',
            'approved' => 'Terverifikasi & Disetujui LPPM',
            'rejected' => 'Ditolak Tim LPPM',
        ];

        // Determine current progress step (1 to 4)
        // 1: Pengajuan / Draf Dokumen
        // 2: Verifikasi & Review LPPM
        // 3: Proses Permohonan Resmi DJKI
        // 4: Sertifikat Terbit (Granted)
        $currentStep = 1;
        $statusSummary = '';
        $badgeClass = 'bg-blue-500/20 text-blue-400 border-blue-500/30';

        if ($ipr->djki_status === 'granted') {
            $currentStep = 4;
            $statusSummary = 'Sertifikat HKI Resmi Telah Terbit (Granted)';
            $badgeClass = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
        } elseif (in_array($ipr->djki_status, ['diajukan', 'pemeriksaan'])) {
            $currentStep = 3;
            $statusSummary = 'Sedang Dalam Proses Pemeriksaan Resmi di DJKI Kemenkumham';
            $badgeClass = 'bg-purple-500/20 text-purple-400 border-purple-500/30';
        } elseif ($ipr->verification_status === 'approved') {
            $currentStep = 3;
            $statusSummary = 'Disetujui LPPM, Menunggu Proses Pendaftaran DJKI';
            $badgeClass = 'bg-blue-500/20 text-blue-400 border-blue-500/30';
        } elseif ($ipr->verification_status === 'revision') {
            $currentStep = 2;
            $statusSummary = 'Memerlukan Revisi / Perbaikan Dokumen Pemohon';
            $badgeClass = 'bg-amber-500/20 text-amber-400 border-amber-500/30';
        } elseif ($ipr->verification_status === 'rejected') {
            $currentStep = 2;
            $statusSummary = 'Berkas Usulan Ditolak oleh Tim LPPM';
            $badgeClass = 'bg-rose-500/20 text-rose-400 border-rose-500/30';
        } else {
            $currentStep = 2;
            $statusSummary = 'Sedang Dalam Antrean Verifikasi Tim Ahli LPPM';
            $badgeClass = 'bg-sky-500/20 text-sky-400 border-sky-500/30';
        }

        // Format inventors
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ipr->id,
                'tracking_code' => $ipr->application_number ?: ($ipr->registration_number ?: 'HKI-' . str_pad($ipr->id, 4, '0', STR_PAD_LEFT)),
                'title' => $ipr->title,
                'ipr_type' => $ipr->ipr_type,
                'ipr_type_label' => $typeLabels[$ipr->ipr_type] ?? ucfirst($ipr->ipr_type),
                'sub_type' => $ipr->sub_type ?: '-',
                'application_number' => $ipr->application_number ?: '-',
                'application_date' => $ipr->application_date ? $ipr->application_date->format('d/m/Y') : '-',
                'registration_number' => $ipr->registration_number ?: '-',
                'grant_date' => $ipr->grant_date ? $ipr->grant_date->format('d/m/Y') : '-',
                'rights_holder' => $ipr->rights_holder,
                'verification_status' => $ipr->verification_status,
                'verification_status_label' => $verificationLabels[$ipr->verification_status] ?? ucfirst($ipr->verification_status),
                'djki_status' => $ipr->djki_status,
                'djki_status_label' => $djkiLabels[$ipr->djki_status] ?? ucfirst($ipr->djki_status),
                'status_summary' => $statusSummary,
                'badge_class' => $badgeClass,
                'current_step' => $currentStep,
                'has_certificate' => !empty($ipr->certificate_file),
                'created_at' => $ipr->created_at ? $ipr->created_at->format('d/m/Y') : '-',
            ],
        ]);
    }
}

