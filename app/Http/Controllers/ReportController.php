<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\IntellectualProperty;
use App\Models\Lecturer;
use App\Models\Publication;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display IKU 3 & IKU 5 Executive Achievement Dashboard.
     */
    public function iku(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // 1. Publikasi Internasional & Nasional Terakreditasi (IKU 5 & IKU 3)
        $totalPubs = Publication::where('verification_status', 'approved')->count();
        $scopusPubs = Publication::where('verification_status', 'approved')
            ->whereIn('indexation', ['scopus', 'wos'])
            ->count();
        $sintaPubs = Publication::where('verification_status', 'approved')
            ->where('indexation', 'sinta')
            ->count();

        // 2. Hak Kekayaan Intelektual Granted (IKU 5)
        $totalIpr = IntellectualProperty::where('verification_status', 'approved')->count();
        $grantedIpr = IntellectualProperty::where('verification_status', 'approved')
            ->where('djki_status', 'granted')
            ->count();
        $patentGranted = IntellectualProperty::where('verification_status', 'approved')
            ->where('djki_status', 'granted')
            ->whereIn('ipr_type', ['paten', 'paten_sederhana'])
            ->count();
        $copyrightGranted = IntellectualProperty::where('verification_status', 'approved')
            ->where('djki_status', 'granted')
            ->where('ipr_type', 'hak_cipta')
            ->count();

        // 3. Target IKU 5 (Target tahunan kampus misal 50 luaran bereputasi)
        $targetIku5 = 50;
        $realisasiIku5 = $scopusPubs + $grantedIpr;
        $persenIku5 = $targetIku5 > 0 ? min(100, round(($realisasiIku5 / $targetIku5) * 100, 1)) : 0;

        // 4. Distribusi Luaran per Fakultas
        $faculties = Faculty::with(['departments.lecturers.publications', 'departments.lecturers.iprInventors.intellectualProperty'])->get();
        $facultyStats = $faculties->map(function ($fac) {
            $pubCount = 0;
            $iprCount = 0;
            foreach ($fac->departments as $dept) {
                foreach ($dept->lecturers as $lec) {
                    $pubCount += $lec->publications->where('verification_status', 'approved')->count();
                    $iprCount += $lec->iprInventors->filter(function ($inv) {
                        return $inv->intellectualProperty && $inv->intellectualProperty->verification_status === 'approved';
                    })->count();
                }
            }
            return [
                'faculty' => $fac->name,
                'code' => $fac->code,
                'publications' => $pubCount,
                'iprs' => $iprCount,
                'total' => $pubCount + $iprCount,
            ];
        });

        // 5. Top Dosen Kontributor
        $topLecturers = Lecturer::with(['user', 'department.faculty'])
            ->withCount([
                'publications' => function ($q) {
                    $q->where('verification_status', 'approved');
                }
            ])
            ->orderByDesc('publications_count')
            ->take(5)
            ->get();

        return view('reports.iku', compact(
            'year',
            'totalPubs',
            'scopusPubs',
            'sintaPubs',
            'totalIpr',
            'grantedIpr',
            'patentGranted',
            'copyrightGranted',
            'targetIku5',
            'realisasiIku5',
            'persenIku5',
            'facultyStats',
            'topLecturers'
        ));
    }

    /**
     * Display Summary for BKD & Accreditation.
     */
    public function summary(Request $request)
    {
        $type = $request->input('type', 'all');
        $deptId = $request->input('department_id', 'all');

        $publicationsQuery = Publication::with(['authors.lecturer.department', 'user.lecturer'])
            ->where('verification_status', 'approved');

        $iprQuery = IntellectualProperty::with(['inventors.lecturer.department', 'user.lecturer'])
            ->where('verification_status', 'approved');

        if ($deptId !== 'all') {
            $publicationsQuery->whereHas('authors.lecturer', function ($q) use ($deptId) {
                $q->where('department_id', $deptId);
            });
            $iprQuery->whereHas('inventors.lecturer', function ($q) use ($deptId) {
                $q->where('department_id', $deptId);
            });
        }

        $publications = $publicationsQuery->latest()->get();
        $iprs = $iprQuery->latest()->get();
        $departments = Department::with('faculty')->orderBy('name')->get();

        return view('reports.summary', compact('publications', 'iprs', 'departments', 'type', 'deptId'));
    }

    /**
     * Export BKD & Accreditation data to CSV / Excel compatible file.
     */
    public function export(Request $request): StreamedResponse
    {
        $fileName = 'rekap_luaran_hki_publikasi_' . date('Ymd_His') . '.csv';

        $publications = Publication::with(['authors', 'user'])->where('verification_status', 'approved')->get();
        $iprs = IntellectualProperty::with(['inventors', 'user'])->where('verification_status', 'approved')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($publications, $iprs) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for MS Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header Row
            fputcsv($handle, [
                'No',
                'Jenis Luaran',
                'Judul Karya / Artikel',
                'Penulis / Inventor',
                'Kategori / Indeksasi',
                'Nomor Registrasi / DOI / ISSN',
                'Penerbit / Jurnal / Pemegang Hak',
                'Tanggal Terbit / Granted',
                'Status DJKI / Akreditasi',
            ], ';');

            $no = 1;
            foreach ($publications as $pub) {
                $authors = $pub->authors->pluck('author_name')->join(', ');
                fputcsv($handle, [
                    $no++,
                    'Publikasi Ilmiah',
                    $pub->title,
                    $authors ?: $pub->user->name,
                    strtoupper($pub->indexation ?? 'Lainnya') . ($pub->quartile ? " ({$pub->quartile})" : ''),
                    $pub->doi ?? ($pub->issn ?? '-'),
                    $pub->journal_name,
                    $pub->publication_date ? $pub->publication_date->format('d/m/Y') : '-',
                    'Terverifikasi LPPM',
                ], ';');
            }

            foreach ($iprs as $ipr) {
                $inventors = $ipr->inventors->pluck('inventor_name')->join(', ');
                fputcsv($handle, [
                    $no++,
                    'Hak Kekayaan Intelektual',
                    $ipr->title,
                    $inventors ?: $ipr->user->name,
                    strtoupper(str_replace('_', ' ', $ipr->ipr_type)),
                    $ipr->registration_number ?? ($ipr->application_number ?? '-'),
                    $ipr->rights_holder,
                    $ipr->grant_date ? $ipr->grant_date->format('d/m/Y') : '-',
                    strtoupper($ipr->djki_status),
                ], ';');
            }

            fclose($handle);
        }, 200, $headers);
    }
}
