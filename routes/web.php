<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\IntellectualPropertyController;
use App\Http\Controllers\LecturerManagementController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicInformationController;
use App\Http\Controllers\PublicSubmissionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RevisionCommentController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\VerificationController;
use App\Models\Information;
use App\Models\IntellectualProperty;
use Illuminate\Support\Facades\Route;

// Landing page Sentra HKI
Route::get('/', function () {
    $stats = [
        'copyright_count' => IntellectualProperty::where('ipr_type', 'hak_cipta')->count(),
        'patent_count' => IntellectualProperty::whereIn('ipr_type', ['paten', 'paten_sederhana'])->count(),
        'brand_count' => IntellectualProperty::whereIn('ipr_type', ['merek', 'desain_industri'])->count(),
    ];

    $alurs = Information::where('category', 'alur_syarat')->where('is_active', true)->orderBy('order')->get();
    $templates = Information::where('category', 'template_dokumen')->where('is_active', true)->orderBy('order')->get();
    $faqs = Information::where('category', 'faq')->where('is_active', true)->orderBy('order')->get();
    $announcements = Information::where('category', 'pengumuman')->where('is_active', true)->orderBy('order')->get();

    return view('landing', compact('stats', 'alurs', 'templates', 'faqs', 'announcements'));
})->name('home');

// Pusat Informasi & Panduan Publik
Route::get('/informasi', [PublicInformationController::class, 'index'])->name('informasi.index');
Route::get('/informasi/{slug}', [PublicInformationController::class, 'show'])->name('informasi.show');
Route::get('/informasi/{slug}/unduh', [PublicInformationController::class, 'download'])->name('informasi.unduh');

// Pelacakan Status HKI Real-Time Publik
Route::get('/tracking/status', [TrackingController::class, 'track'])->name('tracking.status');
Route::post('/tracking/status', [TrackingController::class, 'track'])->name('tracking.status.post');

// Pusat Unduh Dokumen & Template Resmi (Kompatibilitas Tautan Lama)
Route::get('/dokumen/unduh/{slug}', [PublicInformationController::class, 'download'])->name('dokumen.unduh');

// Pengajuan Permohonan Cepat HKI Publik
Route::post('/permohonan/ajukan', [PublicSubmissionController::class, 'submit'])->name('permohonan.ajukan');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna & Edit Akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/sync-academic', [ProfileController::class, 'syncAcademic'])->name('profile.sync-academic');

    // Dialog & Catatan Revisi Interaktif
    Route::post('/revisi/komentar', [RevisionCommentController::class, 'store'])->name('revisi.komentar.store');
    Route::get('/revisi/lampiran/{comment}', [RevisionCommentController::class, 'downloadAttachment'])->name('revisi.lampiran');

    // Modul Dosen
    Route::prefix('dosen')->name('dosen.')->middleware('role:dosen,admin_lppm')->group(function () {
        // Publikasi
        Route::get('/publikasi', [PublicationController::class, 'index'])->name('publications.index');
        Route::get('/publikasi/tambah', [PublicationController::class, 'create'])->name('publications.create');
        Route::post('/publikasi', [PublicationController::class, 'store'])->name('publications.store');
        Route::post('/publikasi/fetch-doi', [PublicationController::class, 'fetchDoi'])->name('publications.fetch-doi');
        Route::post('/publikasi/preview-import', [PublicationController::class, 'previewImport'])->name('publications.preview-import');
        Route::post('/publikasi/import', [PublicationController::class, 'executeImport'])->name('publications.import');
        Route::get('/publikasi/{publication}', [PublicationController::class, 'show'])->name('publications.show');
        Route::get('/publikasi/{publication}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
        Route::put('/publikasi/{publication}', [PublicationController::class, 'update'])->name('publications.update');
        Route::delete('/publikasi/{publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');
        Route::get('/publikasi/{publication}/file/{type}', [PublicationController::class, 'downloadFile'])->name('publications.file');

        // Hak Kekayaan Intelektual (HKI)
        Route::get('/haki', [IntellectualPropertyController::class, 'index'])->name('ipr.index');
        Route::get('/haki/tambah', [IntellectualPropertyController::class, 'create'])->name('ipr.create');
        Route::post('/haki', [IntellectualPropertyController::class, 'store'])->name('ipr.store');
        Route::get('/haki/{intellectualProperty}', [IntellectualPropertyController::class, 'show'])->name('ipr.show');
        Route::get('/haki/{intellectualProperty}/edit', [IntellectualPropertyController::class, 'edit'])->name('ipr.edit');
        Route::put('/haki/{intellectualProperty}', [IntellectualPropertyController::class, 'update'])->name('ipr.update');
        Route::delete('/haki/{intellectualProperty}', [IntellectualPropertyController::class, 'destroy'])->name('ipr.destroy');
        Route::get('/haki/{intellectualProperty}/file/{type}', [IntellectualPropertyController::class, 'downloadFile'])->name('ipr.file');

        // Pengajuan Insentif Dosen
        Route::get('/insentif', [IncentiveController::class, 'dosenIndex'])->name('incentives.index');
        Route::get('/insentif/tambah', [IncentiveController::class, 'dosenCreate'])->name('incentives.create');
        Route::post('/insentif', [IncentiveController::class, 'dosenStore'])->name('incentives.store');
        Route::get('/insentif/{incentiveRequest}', [IncentiveController::class, 'dosenShow'])->name('incentives.show');
        Route::get('/insentif/{incentiveRequest}/file/{type}', [IncentiveController::class, 'downloadFile'])->name('incentives.file');

        Route::get('/profil', fn () => redirect()->route('profile.edit'))->name('profile');
    });

    // Modul Verifikator
    Route::prefix('verifikasi')->name('verification.')->middleware('role:verifikator,admin_lppm')->group(function () {
        // Validasi Publikasi
        Route::get('/publikasi', [VerificationController::class, 'publicationsIndex'])->name('publications.index');
        Route::get('/publikasi/{publication}/review', [VerificationController::class, 'publicationReview'])->name('publications.review');
        Route::post('/publikasi/{publication}/verify', [VerificationController::class, 'publicationVerify'])->name('publications.verify');

        // Validasi HKI
        Route::get('/haki', [VerificationController::class, 'iprIndex'])->name('ipr.index');
        Route::get('/haki/{intellectualProperty}/review', [VerificationController::class, 'iprReview'])->name('ipr.review');
        Route::post('/haki/{intellectualProperty}/verify', [VerificationController::class, 'iprVerify'])->name('ipr.verify');
        Route::get('/haki/{intellectualProperty}/surat-keterangan', [VerificationController::class, 'iprCertificate'])->name('ipr.certificate');
        Route::get('/haki/{intellectualProperty}/file/{type}', [IntellectualPropertyController::class, 'downloadFile'])->name('ipr.file');
        Route::get('/publikasi/{publication}/file/{type}', [PublicationController::class, 'downloadFile'])->name('publications.file');

        // Riwayat & Log
        Route::get('/riwayat', [VerificationController::class, 'logsIndex'])->name('logs');
    });

    // Modul Admin Sentra HKI & LPPM
    Route::prefix('admin')->name('admin.')->middleware('role:admin_lppm,verifikator')->group(function () {
        // Financial records and bank details are restricted to Admin LPPM.
        Route::middleware('role:admin_lppm')->group(function () {
            Route::get('/insentif', [IncentiveController::class, 'adminIndex'])->name('incentives.index');
            Route::get('/insentif/{incentiveRequest}', [IncentiveController::class, 'adminShow'])->name('incentives.show');
            Route::post('/insentif/{incentiveRequest}/process', [IncentiveController::class, 'adminProcess'])->name('incentives.process');
            Route::get('/insentif/{incentiveRequest}/file/{type}', [IncentiveController::class, 'downloadFile'])->name('incentives.file');
        });

        // Master data and user accounts contain privileged records; restrict them to Admin LPPM.
        Route::middleware('role:admin_lppm')->group(function () {
            Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.index');
            Route::post('/master-data/skema', [MasterDataController::class, 'storeScheme'])->name('master.scheme.store');
            Route::put('/master-data/skema/{scheme}', [MasterDataController::class, 'updateScheme'])->name('master.scheme.update');
            Route::delete('/master-data/skema/{scheme}', [MasterDataController::class, 'destroyScheme'])->name('master.scheme.destroy');
            Route::post('/master-data/fakultas', [MasterDataController::class, 'storeFaculty'])->name('master.faculty.store');
            Route::post('/master-data/prodi', [MasterDataController::class, 'storeDepartment'])->name('master.department.store');

            Route::get('/dosen', [LecturerManagementController::class, 'index'])->name('lecturers.index');
            Route::post('/dosen', [LecturerManagementController::class, 'store'])->name('lecturers.store');
            Route::put('/dosen/{lecturer}', [LecturerManagementController::class, 'update'])->name('lecturers.update');
            Route::post('/dosen/{lecturer}/toggle-status', [LecturerManagementController::class, 'toggleStatus'])->name('lecturers.toggle-status');
            Route::post('/dosen/{lecturer}/sync-academic', [LecturerManagementController::class, 'syncAcademic'])->name('lecturers.sync-academic');
            Route::post('/dosen/sync-all', [LecturerManagementController::class, 'syncAll'])->name('lecturers.sync-all');
        });

        // Kelola Pusat Informasi & Template Dokumen (Khusus Admin LPPM)
        Route::prefix('informasi')->name('informasi.')->middleware('role:admin_lppm')->group(function () {
            Route::get('/', [InformationController::class, 'index'])->name('index');
            Route::post('/', [InformationController::class, 'store'])->name('store');
            Route::put('/{informasi}', [InformationController::class, 'update'])->name('update');
            Route::delete('/{informasi}', [InformationController::class, 'destroy'])->name('destroy');
            Route::post('/{informasi}/toggle-status', [InformationController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Pengaturan Aplikasi, Identitas Institusi & Pimpinan LPPM (Khusus Admin LPPM)
        Route::prefix('pengaturan')->name('settings.')->middleware('role:admin_lppm')->group(function () {
            Route::get('/', [AppSettingController::class, 'index'])->name('index');
            Route::post('/', [AppSettingController::class, 'update'])->name('update');
            Route::post('/reset/{type}', [AppSettingController::class, 'resetFile'])->name('reset-file');
        });
    });

    // Modul Pimpinan & Laporan
    Route::prefix('laporan')->name('reports.')->middleware('role:pimpinan,admin_lppm,verifikator')->group(function () {
        Route::get('/iku', [ReportController::class, 'iku'])->name('iku');
        Route::get('/rekap', [ReportController::class, 'summary'])->name('summary');
        Route::get('/rekap/export', [ReportController::class, 'export'])->name('export');
    });
});
