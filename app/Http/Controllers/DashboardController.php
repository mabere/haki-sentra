<?php

namespace App\Http\Controllers;

use App\Models\IncentiveRequest;
use App\Models\IntellectualProperty;
use App\Models\Lecturer;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display multi-role dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $stats = [];
        $recentPublications = [];
        $recentIpr = [];
        $recentIncentives = [];

        if ($role === 'dosen') {
            $lecturer = $user->lecturer;

            $myPublicationsCount = Publication::where('user_id', $user->id)->count();
            $myPublicationsApproved = Publication::where('user_id', $user->id)->where('verification_status', 'approved')->count();

            $myIprCount = IntellectualProperty::where('user_id', $user->id)->count();
            $myIprApproved = IntellectualProperty::where('user_id', $user->id)->where('verification_status', 'approved')->count();

            $myIncentives = incentive_enabled() && $lecturer
                ? IncentiveRequest::where('lecturer_id', $lecturer->id)->get()
                : collect();
            $totalIncentiveDisbursed = $myIncentives->where('status', 'disbursed')->sum('approved_amount');

            $stats = [
                'publications_total' => $myPublicationsCount,
                'publications_approved' => $myPublicationsApproved,
                'ipr_total' => $myIprCount,
                'ipr_approved' => $myIprApproved,
                'incentives_total' => $myIncentives->count(),
                'incentives_disbursed' => $totalIncentiveDisbursed,
            ];

            $recentPublications = Publication::where('user_id', $user->id)
                ->with(['authors'])
                ->latest()
                ->take(5)
                ->get();

            $recentIpr = IntellectualProperty::where('user_id', $user->id)
                ->with(['inventors'])
                ->latest()
                ->take(5)
                ->get();

            $recentIncentives = incentive_enabled() && $lecturer ? IncentiveRequest::where('lecturer_id', $lecturer->id)
                ->with(['scheme', 'claimable'])
                ->latest()
                ->take(5)
                ->get() : collect();

        } elseif ($role === 'verifikator') {
            $pendingPublications = Publication::where('verification_status', 'pending')->count();
            $pendingIpr = IntellectualProperty::where('verification_status', 'pending')->count();
            $approvedByMe = Publication::where('verified_by', $user->id)->count() + IntellectualProperty::where('verified_by', $user->id)->count();

            $stats = [
                'pending_publications' => $pendingPublications,
                'pending_ipr' => $pendingIpr,
                'total_pending' => $pendingPublications + $pendingIpr,
                'verified_by_me' => $approvedByMe,
            ];

            $recentPublications = Publication::where('verification_status', 'pending')
                ->with(['user', 'authors'])
                ->latest()
                ->take(5)
                ->get();

            $recentIpr = IntellectualProperty::where('verification_status', 'pending')
                ->with(['user', 'inventors'])
                ->latest()
                ->take(5)
                ->get();

        } else {
            // admin_lppm & pimpinan
            $totalPub = Publication::count();
            $approvedPub = Publication::where('verification_status', 'approved')->count();
            $totalIpr = IntellectualProperty::count();
            $grantedIpr = IntellectualProperty::where('djki_status', 'granted')->count();
            $totalLecturers = Lecturer::count();
            $disbursedIncentives = incentive_enabled() ? IncentiveRequest::where('status', 'disbursed')->sum('approved_amount') : 0;
            $pendingIncentives = incentive_enabled() ? IncentiveRequest::whereIn('status', ['submitted', 'verified'])->count() : 0;

            $stats = [
                'total_publications' => $totalPub,
                'approved_publications' => $approvedPub,
                'total_ipr' => $totalIpr,
                'granted_ipr' => $grantedIpr,
                'total_lecturers' => $totalLecturers,
                'disbursed_incentives' => $disbursedIncentives,
                'pending_incentives' => $pendingIncentives,
                'scopus_count' => Publication::where('indexation', 'scopus')->where('verification_status', 'approved')->count(),
                'sinta_count' => Publication::where('indexation', 'sinta')->where('verification_status', 'approved')->count(),
            ];

            $recentPublications = Publication::with(['user', 'authors'])
                ->latest()
                ->take(5)
                ->get();

            $recentIpr = IntellectualProperty::with(['user', 'inventors'])
                ->latest()
                ->take(5)
                ->get();

            $recentIncentives = incentive_enabled() ? IncentiveRequest::with(['lecturer.user', 'scheme', 'claimable'])
                ->latest()
                ->take(5)
                ->get() : collect();
        }

        // Executive Charts & Trend Visualizations (Untuk Pimpinan, Admin LPPM & Verifikator)
        $analytics = [];

        if ($role !== 'dosen') {
            $currentYear = (int) date('Y');
            $years = range($currentYear - 4, $currentYear);
            $trendPublications = [];
            $trendIpr = [];

            foreach ($years as $yr) {
                $trendPublications[] = Publication::where('publication_year', $yr)->where('verification_status', 'approved')->count();
                $trendIpr[] = IntellectualProperty::whereYear('application_date', $yr)->count();
            }

            // Proporsi Kategori HKI
            $iprCategories = [
                'Hak Cipta' => IntellectualProperty::where('ipr_type', 'copyright')->count(),
                'Paten Biasa' => IntellectualProperty::where('ipr_type', 'patent')->count(),
                'Paten Sederhana' => IntellectualProperty::where('ipr_type', 'simple_patent')->count(),
                'Desain Industri' => IntellectualProperty::where('ipr_type', 'industrial_design')->count(),
                'Merek Dagang' => IntellectualProperty::where('ipr_type', 'trademark')->count(),
            ];

            // Sebaran Capaian per Fakultas
            $faculties = \App\Models\Faculty::with(['departments.lecturers'])->get();
            $facultyLabels = [];
            $facultyPublications = [];
            $facultyIpr = [];

            foreach ($faculties as $faculty) {
                $facultyLabels[] = $faculty->code ?? $faculty->name;
                $lecturerIds = $faculty->departments->flatMap->lecturers->pluck('id');

                $pubCount = \App\Models\PublicationAuthor::whereIn('lecturer_id', $lecturerIds)
                    ->distinct('publication_id')
                    ->count('publication_id');

                $iprCount = \App\Models\IprInventor::whereIn('lecturer_id', $lecturerIds)
                    ->distinct('intellectual_property_id')
                    ->count('intellectual_property_id');

                $facultyPublications[] = $pubCount;
                $facultyIpr[] = $iprCount;
            }

            // Realisasi Anggaran Insentif
            $incentiveBudget = [
                'disbursed' => (float) IncentiveRequest::where('status', 'disbursed')->sum('approved_amount'),
                'pending' => (float) IncentiveRequest::whereIn('status', ['submitted', 'verified'])->sum('requested_amount'),
                'rejected' => (float) IncentiveRequest::where('status', 'rejected')->sum('requested_amount'),
            ];

            // Top 5 Dosen Kontributor
            $topLecturers = Lecturer::with(['user', 'department.faculty'])
                ->withCount(['publications', 'intellectualProperties'])
                ->get()
                ->map(function ($lec) {
                    $lec->total_output = $lec->publications_count + $lec->intellectual_properties_count;
                    return $lec;
                })
                ->sortByDesc('total_output')
                ->take(5)
                ->values();

            $analytics = [
                'years' => $years,
                'trend_publications' => $trendPublications,
                'trend_ipr' => $trendIpr,
                'ipr_categories' => $iprCategories,
                'faculty_labels' => $facultyLabels,
                'faculty_publications' => $facultyPublications,
                'faculty_ipr' => $facultyIpr,
                'incentive_budget' => $incentiveBudget,
                'top_lecturers' => $topLecturers,
            ];
        }

        return view('dashboard.index', compact('stats', 'recentPublications', 'recentIpr', 'recentIncentives', 'analytics'));
    }
}

