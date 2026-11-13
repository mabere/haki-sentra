<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\IncentiveScheme;
use App\Models\IntellectualProperty;
use App\Models\IprInventor;
use App\Models\Lecturer;
use App\Models\Publication;
use App\Models\PublicationAuthor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Faculties & Departments
        $fik = Faculty::create([
            'name' => 'Fakultas Ilmu Komputer',
            'code' => 'FIK',
        ]);

        $ti = Department::create([
            'faculty_id' => $fik->id,
            'name' => 'Teknik Informatika',
            'code' => 'TI',
        ]);

        $si = Department::create([
            'faculty_id' => $fik->id,
            'name' => 'Sistem Informasi',
            'code' => 'SI',
        ]);

        $ft = Faculty::create([
            'name' => 'Fakultas Teknik',
            'code' => 'FT',
        ]);

        $te = Department::create([
            'faculty_id' => $ft->id,
            'name' => 'Teknik Elektro',
            'code' => 'TE',
        ]);

        // 2. Seed Users & Roles
        $admin = User::create([
            'name' => 'Administrator LPPM & Sentra HKI',
            'email' => 'admin@kampus.ac.id',
            'password' => 'password',
            'role' => 'admin_lppm',
            'phone' => '081200000001',
            'is_active' => true,
        ]);

        Lecturer::create([
            'user_id' => $admin->id,
            'department_id' => $ti->id,
            'nidn' => '0001018001',
            'nip' => '198001012005011001',
            'front_title' => '',
            'back_title' => 'M.Kom.',
            'academic_rank' => 'Lektor',
            'sinta_id' => '6099999',
            'scopus_id' => '57999999999',
            'phone' => '081200000001',
        ]);

        $verifikator = User::create([
            'name' => 'Dr. H. Ahmad Fauzi, M.T. (Verifikator)',
            'email' => 'verifikator@kampus.ac.id',
            'password' => 'password',
            'role' => 'verifikator',
            'phone' => '081200000002',
            'is_active' => true,
        ]);

        Lecturer::create([
            'user_id' => $verifikator->id,
            'department_id' => $te->id,
            'nidn' => '0412057801',
            'nip' => '197805122003121002',
            'front_title' => 'Dr. H.',
            'back_title' => 'M.T.',
            'academic_rank' => 'Lektor Kepala',
            'sinta_id' => '6088888',
            'scopus_id' => '57888888888',
            'phone' => '081200000002',
        ]);

        $pimpinan = User::create([
            'name' => 'Prof. Dr. Ir. H. Sudirman, M.Sc. (Wakil Rektor I)',
            'email' => 'pimpinan@kampus.ac.id',
            'password' => 'password',
            'role' => 'pimpinan',
            'phone' => '081200000003',
            'is_active' => true,
        ]);

        $userDosen1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'dosen@kampus.ac.id',
            'password' => 'password',
            'role' => 'dosen',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $dosen1 = Lecturer::create([
            'user_id' => $userDosen1->id,
            'department_id' => $ti->id,
            'nidn' => '0012058501',
            'nip' => '198505122010121001',
            'front_title' => 'Dr.',
            'back_title' => 'S.Kom., M.Kom.',
            'academic_rank' => 'Lektor Kepala',
            'sinta_id' => '6012345',
            'scopus_id' => '57210987654',
            'google_scholar_id' => 'abcXYZ1234',
            'phone' => '081234567890',
        ]);

        $userDosen2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'dosen2@kampus.ac.id',
            'password' => 'password',
            'role' => 'dosen',
            'phone' => '081298765432',
            'is_active' => true,
        ]);

        $dosen2 = Lecturer::create([
            'user_id' => $userDosen2->id,
            'department_id' => $si->id,
            'nidn' => '0024089002',
            'nip' => '199008242018032002',
            'front_title' => '',
            'back_title' => 'M.Kom.',
            'academic_rank' => 'Lektor',
            'sinta_id' => '6054321',
            'scopus_id' => '57198765432',
            'google_scholar_id' => 'xyzABC9876',
            'phone' => '081298765432',
        ]);

        // 3. Seed Master Incentive Schemes
        $schemeScopusQ1 = IncentiveScheme::create([
            'name' => 'Insentif Artikel Jurnal Internasional Bereputasi Scopus Q1',
            'category' => 'publication',
            'tier' => 'Q1',
            'amount' => 15000000,
            'fiscal_year' => 2026,
            'is_active' => true,
            'description' => 'Diberikan untuk artikel jurnal terindeks Scopus kuartil Q1 dengan status published.',
        ]);

        IncentiveScheme::create([
            'name' => 'Insentif Artikel Jurnal Internasional Bereputasi Scopus Q2',
            'category' => 'publication',
            'tier' => 'Q2',
            'amount' => 10000000,
            'fiscal_year' => 2026,
            'is_active' => true,
            'description' => 'Diberikan untuk artikel jurnal terindeks Scopus kuartil Q2.',
        ]);

        IncentiveScheme::create([
            'name' => 'Insentif Artikel Jurnal Nasional Terakreditasi SINTA 1',
            'category' => 'publication',
            'tier' => 'S1',
            'amount' => 7500000,
            'fiscal_year' => 2026,
            'is_active' => true,
            'description' => 'Diberikan untuk artikel pada jurnal SINTA 1.',
        ]);

        IncentiveScheme::create([
            'name' => 'Insentif Artikel Jurnal Nasional Terakreditasi SINTA 2',
            'category' => 'publication',
            'tier' => 'S2',
            'amount' => 5000000,
            'fiscal_year' => 2026,
            'is_active' => true,
            'description' => 'Diberikan untuk artikel pada jurnal SINTA 2.',
        ]);

        $schemePaten = IncentiveScheme::create([
            'name' => 'Insentif Paten Diberi (Granted)',
            'category' => 'ipr',
            'tier' => 'paten_granted',
            'amount' => 20000000,
            'fiscal_year' => 2026,
            'is_active' => true,
            'description' => 'Diberikan atas terbitnya sertifikat paten biasa dari DJKI Kemenkumham.',
        ]);

        $schemeHakCipta = IncentiveScheme::create([
            'name' => 'Insentif Pencatatan Hak Cipta',
            'category' => 'ipr',
            'tier' => 'hak_cipta',
            'amount' => 1500000,
            'fiscal_year' => 2026,
            'is_active' => true,
            'description' => 'Diberikan atas terbitnya surat pencatatan ciptaan DJKI.',
        ]);

        // 4. Seed Sample Publications for Dosen 1
        $pub1 = Publication::create([
            'user_id' => $userDosen1->id,
            'title' => 'Deep Learning-Based Automated Detection of Agricultural Diseases in Tropical Climate',
            'publication_type' => 'jurnal_internasional',
            'journal_name' => 'IEEE Access',
            'doi' => '10.1109/ACCESS.2025.3210987',
            'issn_isbn' => '2169-3536',
            'volume' => '13',
            'issue' => '2',
            'pages' => '14520-14532',
            'publication_year' => 2025,
            'publication_date' => '2025-06-15',
            'url' => 'https://ieeexplore.ieee.org/document/3210987',
            'indexation' => 'scopus',
            'quartile' => 'Q1',
            'verification_status' => 'approved',
            'verified_by' => $verifikator->id,
            'verified_at' => now()->subDays(5),
            'verification_note' => 'Dokumen lengkap dan valid sesuai naskah IEEE.',
        ]);

        PublicationAuthor::create([
            'publication_id' => $pub1->id,
            'lecturer_id' => $dosen1->id,
            'author_name' => $dosen1->full_name,
            'author_type' => 'internal_lecturer',
            'author_role' => 'first_author',
            'author_order' => 1,
            'affiliation' => 'Universitas',
        ]);

        PublicationAuthor::create([
            'publication_id' => $pub1->id,
            'lecturer_id' => null,
            'author_name' => 'Rizky Pratama (Mahasiswa)',
            'author_type' => 'student',
            'author_role' => 'co_author',
            'author_order' => 2,
            'affiliation' => 'Universitas',
        ]);

        // 5. Seed Sample Intellectual Property for Dosen 1
        $ipr1 = IntellectualProperty::create([
            'user_id' => $userDosen1->id,
            'title' => 'Sistem Cerdas Pemantauan Kualitas Air Kolam Budidaya Berbasis Internet of Things',
            'ipr_type' => 'hak_cipta',
            'sub_type' => 'Program Komputer',
            'application_number' => 'EC00202512345',
            'application_date' => '2025-03-10',
            'registration_number' => '000789123',
            'grant_date' => '2025-04-02',
            'djki_status' => 'granted',
            'rights_holder' => 'Universitas',
            'verification_status' => 'approved',
            'verified_by' => $verifikator->id,
            'verified_at' => now()->subDays(10),
            'verification_note' => 'Sertifikat Hak Cipta DJKI valid dan telah terverifikasi.',
        ]);

        IprInventor::create([
            'intellectual_property_id' => $ipr1->id,
            'lecturer_id' => $dosen1->id,
            'inventor_name' => $dosen1->full_name,
            'inventor_type' => 'internal_lecturer',
            'role' => 'inventor_utama',
            'inventor_order' => 1,
            'contribution_percentage' => 70.00,
            'affiliation' => 'Universitas',
        ]);

        IprInventor::create([
            'intellectual_property_id' => $ipr1->id,
            'lecturer_id' => $dosen2->id,
            'inventor_name' => $dosen2->full_name,
            'inventor_type' => 'internal_lecturer',
            'role' => 'inventor_anggota',
            'inventor_order' => 2,
            'contribution_percentage' => 30.00,
            'affiliation' => 'Universitas',
        ]);

        // 6. Seed Sample Incentive Requests
        \App\Models\IncentiveRequest::create([
            'lecturer_id' => $dosen1->id,
            'incentive_scheme_id' => $schemeScopusQ1->id,
            'claimable_type' => Publication::class,
            'claimable_id' => $pub1->id,
            'requested_amount' => $schemeScopusQ1->amount,
            'approved_amount' => null,
            'bank_name' => 'Bank Mandiri',
            'bank_account_number' => '1230009876543',
            'bank_account_holder' => 'Dr. Budi Santoso, M.Kom.',
            'status' => 'submitted',
            'notes' => 'Pengajuan insentif Scopus Q1 tahun 2026.',
        ]);

        \App\Models\IncentiveRequest::create([
            'lecturer_id' => $dosen1->id,
            'incentive_scheme_id' => $schemePaten->id,
            'claimable_type' => IntellectualProperty::class,
            'claimable_id' => $ipr1->id,
            'requested_amount' => $schemePaten->amount,
            'approved_amount' => $schemePaten->amount,
            'bank_name' => 'Bank BNI',
            'bank_account_number' => '0987654321',
            'bank_account_holder' => 'Dr. Budi Santoso, M.Kom.',
            'status' => 'disbursed',
            'disbursed_at' => now()->subDays(2),
            'reviewed_by' => $admin->id,
            'notes' => 'Insentif telah disetujui LPPM dan dicairkan via transfer BNI.',
        ]);

        $this->call(InformationSeeder::class);
    }
}
