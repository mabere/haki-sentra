<?php

namespace Database\Seeders;

use App\Models\Information;
use App\Models\User;
use App\Services\DocumentTemplateService;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    public function run(): void
    {
        DocumentTemplateService::ensureTemplatesExist();

        $admin = User::where('role', 'admin_lppm')->first();
        $adminId = $admin ? $admin->id : null;

        $items = [
            // ALUR & SYARAT HKI
            [
                'title' => 'Konsultasi & Siapkan Berkas',
                'slug' => 'alur-01-konsultasi-berkas',
                'category' => 'alur_syarat',
                'badge_text' => 'Tahap 01',
                'icon' => 'file-check-2',
                'description' => 'Konsultasikan karya cipta atau invensi Anda dengan Sentra HKI LPPM dan lengkapi dokumen persyaratan sesuai jenis karya.',
                'content' => "Langkah pertama pendaftaran HKI adalah konsultasi awal dengan staf Sentra HKI LPPM Universitas Panca Sakti Bekasi.\n\nDokumen yang perlu disiapkan antara lain:\n1. Formulir pendaftaran resmi yang telah diisi lengkap.\n2. Surat pernyataan keaslian karya bermaterai Rp 10.000.\n3. Salinan KTP seluruh pencipta/inventor.\n4. Bukti luaran fisik/digital (dokumen PDF, manuskrip, kode program, foto ciptaan).",
                'order' => 1,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Unggah ke Sistem',
                'slug' => 'alur-02-unggah-ke-sistem',
                'category' => 'alur_syarat',
                'badge_text' => 'Tahap 02',
                'icon' => 'upload-cloud',
                'description' => 'Mengisi formulir pengajuan online melalui portal Sentra HKI universitas dan melampirkan berkas scan asli.',
                'content' => "Pengusul dapat mengunggah berkas secara mandiri melalui portal Sentra HKI:\n- Dosen dapat masuk ke Dashboard Portal Dosen.\n- Mahasiswa atau masyarakat umum dapat menggunakan menu Cepat Ajukan Permohonan di beranda portal.",
                'order' => 2,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Verifikasi & Review',
                'slug' => 'alur-03-verifikasi-review',
                'category' => 'alur_syarat',
                'badge_text' => 'Tahap 03',
                'icon' => 'search-check',
                'description' => 'Tim verifikator Sentra HKI memeriksa keabsahan administrasi dan substantif sebelum diajukan resmi ke DJKI Kementerian.',
                'content' => "Verifikasi dilakukan oleh petugas yang ditugaskan di Sentra HKI dalam waktu maksimal 2-3 hari kerja. Apabila terdapat kekurangan berkas, pemohon akan menerima notifikasi status 'Perlu Perbaikan'.",
                'order' => 3,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Terbit Sertifikat Resmi',
                'slug' => 'alur-04-terbit-sertifikat',
                'category' => 'alur_syarat',
                'badge_text' => 'Tahap 04',
                'icon' => 'award',
                'description' => 'Sertifikat pencatatan HKI resmi diterbitkan oleh DJKI Kemenkumham RI dan dapat diunduh langsung secara digital.',
                'content' => "Setelah disetujui DJKI Kemenkumham, surat pencatatan ciptaan atau sertifikat paten akan terbit resmi. Pengusul dapat mengunduh dokumen sertifikat langsung melalui menu pelacakan status atau mengambil salinan cetak di kantor LPPM.",
                'order' => 4,
                'is_active' => true,
                'created_by' => $adminId,
            ],

            // TEMPLATE DOKUMEN RESMI
            [
                'title' => 'Surat Pernyataan Hak Cipta',
                'slug' => 'surat-pernyataan-hak-cipta',
                'category' => 'template_dokumen',
                'badge_text' => 'Format DOCX • 245 KB',
                'icon' => 'file-text',
                'description' => 'Format resmi surat pernyataan pengalihan hak dan keaslian karya cipta untuk pengajuan Hak Cipta DJKI.',
                'content' => 'Template resmi Surat Pernyataan Hak Cipta yang memuat persetujuan seluruh pencipta dan pengalihan hak kepada Universitas Panca Sakti Bekasi jika didanai oleh kampus.',
                'file_path' => 'templates/surat-pernyataan-hak-cipta.docx',
                'file_name' => 'Surat_Pernyataan_Hak_Cipta_Sentra_HKI_UPS.docx',
                'file_type' => 'DOCX',
                'file_size' => '245 KB',
                'order' => 1,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Panduan Pengajuan Paten',
                'slug' => 'panduan-pengajuan-paten',
                'category' => 'template_dokumen',
                'badge_text' => 'Format PDF • Resmi LPPM',
                'icon' => 'book-open',
                'description' => 'Buku panduan penyusunan deskripsi paten, klaim invensi, dan abstrak spesifikasi paten sederhana & biasa.',
                'content' => 'Pedoman lengkap bagi dosen dan peneliti dalam menyusun drafting paten berstandar DJKI Kemenkumham RI.',
                'file_path' => 'templates/panduan-pengajuan-paten.pdf',
                'file_name' => 'Panduan_Pengajuan_Paten_Sentra_HKI_UPS.pdf',
                'file_type' => 'PDF',
                'file_size' => '1.2 MB',
                'order' => 2,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Formulir Pendaftaran Merek',
                'slug' => 'formulir-pendaftaran-merek',
                'category' => 'template_dokumen',
                'badge_text' => 'Format DOCX • Resmi LPPM',
                'icon' => 'file-badge',
                'description' => 'Formulir permohonan pendaftaran merek dagang / jasa binaan inkubator bisnis universitas.',
                'content' => 'Formulir isian data merek, kelas barang/jasa, etiket merek, dan keterangan pemakai merek pertama.',
                'file_path' => 'templates/formulir-pendaftaran-merek.docx',
                'file_name' => 'Formulir_Pendaftaran_Merek_Sentra_HKI_UPS.docx',
                'file_type' => 'DOCX',
                'file_size' => '180 KB',
                'order' => 3,
                'is_active' => true,
                'created_by' => $adminId,
            ],

            // PERTANYAAN UMUM (FAQ)
            [
                'title' => 'Berapa lama proses penerbitan sertifikat Hak Cipta?',
                'slug' => 'faq-lama-proses-hak-cipta',
                'category' => 'faq',
                'badge_text' => 'Estimasi Waktu',
                'icon' => 'help-circle',
                'description' => 'Estimasi waktu pemrosesan penerbitan sertifikat Hak Cipta di DJKI.',
                'content' => 'Pengajuan Hak Cipta melalui jalur kampus dan verifikasi DJKI memakan waktu sekitar 7 -14 hari
                kerja setelah berkas dinyatakan lengkap dan valid.',
                'order' => 1,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Apakah mahasiswa bisa mendaftarkan Hak Cipta tugas akhir/skripsi?',
                'slug' => 'faq-mahasiswa-hak-cipta-skripsi',
                'category' => 'faq',
                'badge_text' => 'Mahasiswa & Kolaborasi',
                'icon' => 'help-circle',
                'description' => 'Penjelasan pendaftaran HKI karya mahasiswa dan dosen pembimbing.',
                'content' => 'Sangat bisa! Mahasiswa Universitas Panca Sakti Bekasi sangat didorong untuk mendaftarkan tugas akhir, skripsi, prototipe, atau program komputer ciptaannya dengan pendampingan dari dosen pembimbing dan Sentra HKI.',
                'order' => 2,
                'is_active' => true,
                'created_by' => $adminId,
            ],
            [
                'title' => 'Berapakah biaya pendaftaran melalui Sentra HKI Universitas?',
                'slug' => 'faq-biaya-pendaftaran-hki',
                'category' => 'faq',
                'badge_text' => 'Biaya & Tarif',
                'icon' => 'help-circle',
                'description' => 'Kebijakan tarif dan insentif pendaftaran HKI bagi sivitas akademika.',
                'content' => 'Biaya PNBP disesuaikan dengan tarif resmi DJKI Kemenkumham. Untuk sivitas akademika Universitas Panca Sakti Bekasi, tersedia subsidi pendaftaran dari LPPM serta insentif luaran bagi karya yang berhasil granted/terbit.',
                'order' => 3,
                'is_active' => true,
                'created_by' => $adminId,
            ],

            // PENGUMUMAN
            [
                'title' => 'Pembukaan Pendaftaran Insentif Luaran Riset & HKI Tahun Anggaran 2026',
                'slug' => 'pengumuman-insentif-luaran-2026',
                'category' => 'pengumuman',
                'badge_text' => 'Informasi Resmi',
                'icon' => 'bell',
                'description' => 'LPPM Universitas Panca Sakti Bekasi membuka pengajuan klaim insentif publikasi internasional dan HKI bersertifikat.',
                'content' => "Diberitahukan kepada seluruh dosen tetap di lingkungan Universitas Panca Sakti Bekasi bahwa pengajuan insentif luaran riset (Scopus/SINTA) dan sertifikat HKI tahun 2026 telah dibuka.\n\nKlaim dapat diajukan secara langsung melalui SIM-LPPM menu 'Pelaks. Penelitian -> Pengajuan Insentif'.",
                'order' => 1,
                'is_active' => true,
                'created_by' => $adminId,
            ],
        ];

        foreach ($items as $item) {
            Information::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
