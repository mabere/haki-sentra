<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique()->index();
                $table->longText('value')->nullable();
                $table->string('group', 50)->default('general')->index();
                $table->string('type', 30)->default('text');
                $table->string('label', 150)->nullable();
                $table->string('description', 255)->nullable();
                $table->timestamps();
            });

            // Seed default settings
            $now = now();
            $defaults = [
                // Branding & Visual
                [
                    'key' => 'app_name',
                    'value' => 'SIM-HKIPUB',
                    'group' => 'branding',
                    'type' => 'text',
                    'label' => 'Nama Singkat Aplikasi',
                    'description' => 'Nama singkatan aplikasi yang tampil pada header sidebar dan footer.',
                ],
                [
                    'key' => 'app_full_name',
                    'value' => 'Sistem Informasi Manajemen Kekayaan Intelektual & Publikasi Ilmiah',
                    'group' => 'branding',
                    'type' => 'text',
                    'label' => 'Nama Lengkap Sistem',
                    'description' => 'Nama lengkap aplikasi / portal informasi.',
                ],
                [
                    'key' => 'app_tagline',
                    'value' => 'Melindungi Karya, Mewujudkan Inovasi Mandiri',
                    'group' => 'branding',
                    'type' => 'text',
                    'label' => 'Tagline / Slogan Aplikasi',
                    'description' => 'Motto atau slogan yang tampil di landing page dan portal publik.',
                ],
                [
                    'key' => 'app_logo',
                    'value' => null,
                    'group' => 'branding',
                    'type' => 'image',
                    'label' => 'Logo Aplikasi',
                    'description' => 'Logo institusi / aplikasi resmi (PNG, JPG, SVG, WebP).',
                ],
                [
                    'key' => 'app_favicon',
                    'value' => null,
                    'group' => 'branding',
                    'type' => 'image',
                    'label' => 'Icon / Favicon Browser',
                    'description' => 'Icon tab browser (ICO, PNG, SVG).',
                ],

                // Identitas Institusi
                [
                    'key' => 'institution_name',
                    'value' => 'Universitas Panca Sakti',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Nama Perguruan Tinggi',
                    'description' => 'Nama perguruan tinggi yang ditampilkan di dashboard dan sidebar.',
                ],
                [
                    'key' => 'institution_legal_name',
                    'value' => 'Universitas Panca Sakti Bekasi',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Nama Resmi Institusi (Lengkap)',
                    'description' => 'Nama legal perguruan tinggi untuk kop surat, sertifikat, dan laporan resmi.',
                ],
                [
                    'key' => 'institution_short_name',
                    'value' => 'UPS Bekasi',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Singkatan Nama Kampus',
                    'description' => 'Singkatan atau akronim institusi.',
                ],
                [
                    'key' => 'institution_city',
                    'value' => 'Bekasi',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Kota Institusi',
                    'description' => 'Nama kota yang digunakan untuk titimangsa tanggal surat dan sertifikat (misal: Bekasi, 14 Oktober 2026).',
                ],
                [
                    'key' => 'institution_address',
                    'value' => 'Jl. Raya Hankam No. 54, Pondok Gede, Kota Bekasi, Jawa Barat',
                    'group' => 'institution',
                    'type' => 'textarea',
                    'label' => 'Alamat Lengkap Kampus',
                    'description' => 'Alamat sekretariat untuk kop surat, sertifikat, dan landing page.',
                ],
                [
                    'key' => 'institution_postal_code',
                    'value' => '17414',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Kode Pos',
                    'description' => 'Kode pos domisili kampus.',
                ],
                [
                    'key' => 'institution_phone',
                    'value' => '(021) 84978888',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Nomor Telepon / WhatsApp',
                    'description' => 'Nomor kontak resmi LPPM / Sentra HKI.',
                ],
                [
                    'key' => 'institution_email',
                    'value' => 'lppm@panca-sakti.ac.id',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Email Resmi Institusi',
                    'description' => 'Alamat email korespondensi LPPM.',
                ],
                [
                    'key' => 'institution_website',
                    'value' => 'https://panca-sakti.ac.id',
                    'group' => 'institution',
                    'type' => 'text',
                    'label' => 'Website Resmi Kampus',
                    'description' => 'URL website resmi perguruan tinggi.',
                ],

                // Lembaga & Sentra HKI
                [
                    'key' => 'lppm_name',
                    'value' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)',
                    'group' => 'organization',
                    'type' => 'text',
                    'label' => 'Nama Lembaga LPPM',
                    'description' => 'Nama lengkap lembaga penelitian kampus.',
                ],
                [
                    'key' => 'sentra_hki_name',
                    'value' => 'Sentra Kekayaan Intelektual (Sentra HKI)',
                    'group' => 'organization',
                    'type' => 'text',
                    'label' => 'Nama Unit Sentra HKI',
                    'description' => 'Nama unit sentra kekayaan intelektual.',
                ],
                [
                    'key' => 'lppm_building',
                    'value' => 'Gedung LPPM Lantai 2, Kampus Universitas Panca Sakti Bekasi',
                    'group' => 'organization',
                    'type' => 'text',
                    'label' => 'Lokasi Gedung / Ruang Layanan',
                    'description' => 'Lokasi fisik ruang sentra HKI & LPPM.',
                ],

                // Pimpinan LPPM (Kepala / Ketua LPPM)
                [
                    'key' => 'head_lppm_name',
                    'value' => 'Dr. H. Ahmad Fauzi, M.T.',
                    'group' => 'leader',
                    'type' => 'text',
                    'label' => 'Nama Lengkap Kepala LPPM',
                    'description' => 'Nama lengkap pimpinan LPPM beserta gelar akademik.',
                ],
                [
                    'key' => 'head_lppm_nidn',
                    'value' => '0412057801',
                    'group' => 'leader',
                    'type' => 'text',
                    'label' => 'NIDN / NIP Kepala LPPM',
                    'description' => 'Nomor Induk Dosen Nasional atau NIP Kepala LPPM.',
                ],
                [
                    'key' => 'head_lppm_title',
                    'value' => 'Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)',
                    'group' => 'leader',
                    'type' => 'text',
                    'label' => 'Sebutan Jabatan Kepala LPPM',
                    'description' => 'Sebutan resmi jabatan pimpinan di atas tanda tangan surat.',
                ],
                [
                    'key' => 'head_lppm_signature',
                    'value' => null,
                    'group' => 'leader',
                    'type' => 'image',
                    'label' => 'Tanda Tangan & Stempel Resmi Digital',
                    'description' => 'File gambar stempel & tanda tangan transparan (PNG) untuk dicantumkan pada sertifikat/surat keterangan.',
                ],

                // Pimpinan Sentra HKI
                [
                    'key' => 'head_sentra_name',
                    'value' => 'Dr. H. Ahmad Fauzi, M.T.',
                    'group' => 'leader',
                    'type' => 'text',
                    'label' => 'Nama Ketua Sentra HKI',
                    'description' => 'Nama ketua unit sentra HKI (bisa sama atau berbeda dengan Ketua LPPM).',
                ],
                [
                    'key' => 'head_sentra_nidn',
                    'value' => '0412057801',
                    'group' => 'leader',
                    'type' => 'text',
                    'label' => 'NIDN / NIP Ketua Sentra HKI',
                    'description' => 'NIDN / NIP Ketua Sentra HKI.',
                ],
                [
                    'key' => 'head_sentra_title',
                    'value' => 'Ketua Sentra HKI Universitas',
                    'group' => 'leader',
                    'type' => 'text',
                    'label' => 'Sebutan Jabatan Ketua Sentra HKI',
                    'description' => 'Sebutan jabatan ketua sentra HKI di landing page dan surat.',
                ],
            ];

            foreach ($defaults as &$item) {
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
            }

            DB::table('app_settings')->insert($defaults);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
