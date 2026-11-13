<?php

namespace App\Services;

use ZipArchive;

class DocumentTemplateService
{
    /**
     * Ensure all official templates exist on disk.
     */
    public static function ensureTemplatesExist(): void
    {
        $dir = storage_path('app/public/templates');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $files = [
            'surat-pernyataan-hak-cipta.docx' => [
                'type' => 'docx',
                'title' => 'SURAT PERNYATAAN KEPEMILIKAN KARYA CIPTAAN',
                'content' => self::getCopyrightDeclarationText(),
            ],
            'formulir-pendaftaran-merek.docx' => [
                'type' => 'docx',
                'title' => 'FORMULIR PENDAFTARAN MEREK DAGANG & JASA',
                'content' => self::getTrademarkFormText(),
            ],
            'panduan-pengajuan-paten.pdf' => [
                'type' => 'pdf',
                'title' => 'PANDUAN PENGAJUAN PATEN & PATEN SEDERHANA',
                'content' => self::getPatentGuideContent(),
            ],
        ];

        foreach ($files as $filename => $info) {
            $path = $dir . DIRECTORY_SEPARATOR . $filename;
            if (!file_exists($path)) {
                if ($info['type'] === 'docx') {
                    self::createDocx($path, $info['title'], $info['content']);
                } elseif ($info['type'] === 'pdf') {
                    self::createPdf($path, $info['title'], $info['content']);
                }
            }
        }
    }

    /**
     * Create a standard OpenXML DOCX document.
     */
    public static function createDocx(string $destinationPath, string $title, array $paragraphs): void
    {
        $zip = new ZipArchive();
        if ($zip->open($destinationPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return;
        }

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' .
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' .
            '<Default Extension="xml" ContentType="application/xml"/>' .
            '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>' .
            '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>' .
            '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // word/document.xml
        $docXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">' .
            '<w:body>' .
            '<w:p><w:pPr><w:jc w:val="center"/></w:pPr>' .
            '<w:r><w:rPr><w:b/><w:sz w:val="28"/><w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/></w:rPr>' .
            '<w:t>' . htmlspecialchars($title) . '</w:t></w:r></w:p>' .
            '<w:p><w:pPr><w:jc w:val="center"/></w:pPr>' .
            '<w:r><w:rPr><w:i/><w:sz w:val="22"/><w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/></w:rPr>' .
            '<w:t>SENTRA HKI - LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT (LPPM)</w:t></w:r></w:p>' .
            '<w:p><w:pPr><w:jc w:val="center"/></w:pPr>' .
            '<w:r><w:rPr><w:b/><w:sz w:val="22"/><w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/></w:rPr>' .
            '<w:t>UNIVERSITAS PANCA SAKTI BEKASI</w:t></w:r></w:p>' .
            '<w:p><w:r><w:t></w:t></w:r></w:p>';

        foreach ($paragraphs as $p) {
            $isBold = !empty($p['bold']);
            $align = $p['align'] ?? 'both';
            $text = $p['text'] ?? '';

            $docXml .= '<w:p><w:pPr><w:jc w:val="' . $align . '"/><w:spacing w:line="276" w:lineRule="auto"/></w:pPr>';
            $docXml .= '<w:r><w:rPr>';
            if ($isBold) {
                $docXml .= '<w:b/>';
            }
            $docXml .= '<w:sz w:val="24"/><w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/></w:rPr>';
            $docXml .= '<w:t xml:space="preserve">' . htmlspecialchars($text) . '</w:t></w:r></w:p>';
        }

        $docXml .= '</w:body></w:document>';
        $zip->addFromString('word/document.xml', $docXml);

        $zip->close();
    }

    /**
     * Create a simple, clean, valid PDF document.
     */
    public static function createPdf(string $destinationPath, string $title, array $lines): void
    {
        // Simple PDF generator compliant with PDF-1.4 specification
        $stream = "BT /F1 16 Tf 50 780 Td (" . self::escapePdf($title) . ") Tj ET\n";
        $stream .= "BT /F1 10 Tf 50 762 Td (SENTRA HKI - UNIVERSITAS PANCA SAKTI BEKASI) Tj ET\n";
        $stream .= "BT /F1 10 Tf 50 748 Td (Portal Panduan Resmi Pengajuan Paten Civitas Akademika) Tj ET\n";
        $stream .= "0.5 w 50 738 m 550 738 l S\n";

        $y = 715;
        foreach ($lines as $line) {
            if ($y < 60) {
                break;
            }
            $isHeader = !empty($line['header']);
            $text = $line['text'] ?? '';

            if ($isHeader) {
                $y -= 10;
                $stream .= "BT /F2 12 Tf 50 {$y} Td (" . self::escapePdf($text) . ") Tj ET\n";
                $y -= 18;
            } else {
                $stream .= "BT /F1 10 Tf 50 {$y} Td (" . self::escapePdf($text) . ") Tj ET\n";
                $y -= 14;
            }
        }

        $streamLen = strlen($stream);

        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n";
        $pdf .= "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n";
        $pdf .= "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >> endobj\n";
        $pdf .= "4 0 obj << /Length {$streamLen} >>\nstream\n{$stream}\nendstream\nendobj\n";
        $pdf .= "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n";
        $pdf .= "6 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >> endobj\n";
        $pdf .= "xref\n0 7\n0000000000 65535 f \n";
        $pdf .= "trailer << /Size 7 /Root 1 0 R >>\nstartxref\n550\n%%EOF";

        file_put_contents($destinationPath, $pdf);
    }

    private static function escapePdf(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private static function getCopyrightDeclarationText(): array
    {
        return [
            ['text' => 'Yang bertanda tangan di bawah ini:', 'align' => 'left'],
            ['text' => '1. Nama Lengkap & Gelar : [Nama Pemohon / Dosen / Mahasiswa]', 'align' => 'left'],
            ['text' => '2. NIDN / NIP / NIM      : [........................................]', 'align' => 'left'],
            ['text' => '3. Fakultas / Program Studi: [........................................]', 'align' => 'left'],
            ['text' => '4. Alamat Rumah / Kantor: [........................................]', 'align' => 'left'],
            ['text' => '5. Nomor HP / WhatsApp  : [........................................]', 'align' => 'left'],
            ['text' => '6. Email Resmi          : [........................................]', 'align' => 'left'],
            ['text' => 'Dengan ini menyatakan dengan sebenarnya bahwa ciptaan yang diajukan berupa:', 'align' => 'both'],
            ['text' => 'Judul Ciptaan  : [Tuliskan Judul Lengkap Karya / Ciptaan]', 'bold' => true, 'align' => 'left'],
            ['text' => 'Jenis Ciptaan  : [Buku / Modul / Program Komputer / Karya Seni / Jurnal]', 'align' => 'left'],
            ['text' => 'Tanggal Dibuat : [Tanggal, Bulan, Tahun]', 'align' => 'left'],
            ['text' => 'Kota Pertama Diumumkan : Bekasi, Jawa Barat', 'align' => 'left'],
            ['text' => 'Menyatakan bahwa:', 'bold' => true, 'align' => 'left'],
            ['text' => '1. Karya ciptaan tersebut di atas adalah ASLI / ORISINAL hasil karya cipta saya sendiri dan bukan merupakan tiruan, jiplakan, atau plagiasi dari ciptaan orang lain.', 'align' => 'both'],
            ['text' => '2. Karya ciptaan tidak bertentangan dengan norma agama, kesusilaan, ketertiban umum, atau peraturan perundang-undangan.', 'align' => 'both'],
            ['text' => '3. Apabila di kemudian hari terbukti ada tuntutan hukum dari pihak lain, saya bersedia bertanggung jawab sepenuhnya secara perdata maupun pidana tanpa membebani Universitas Panca Sakti Bekasi.', 'align' => 'both'],
            ['text' => 'Demikian surat pernyataan ini dibuat dengan sadar, tanpa paksaan, dan dibubuhi materai cukup.', 'align' => 'both'],
            ['text' => 'Bekasi, ........................ 2026', 'align' => 'right'],
            ['text' => 'Materai Rp 10.000', 'align' => 'right'],
            ['text' => '( ............................................ )', 'align' => 'right'],
            ['text' => 'Tanda Tangan & Nama Terang Pemohon', 'align' => 'right'],
        ];
    }

    private static function getTrademarkFormText(): array
    {
        return [
            ['text' => 'DATA PEMOHON PENDAFTARAN MEREK', 'bold' => true, 'align' => 'left'],
            ['text' => '1. Nama Pemilik Merek : [Nama Civitas / Unit Bisnis / Inkubator]', 'align' => 'left'],
            ['text' => '2. NIK / KTP Pemohon  : [........................................]', 'align' => 'left'],
            ['text' => '3. Alamat Domisili    : [........................................]', 'align' => 'left'],
            ['text' => '4. Badan Usaha / Afiliasi : Universitas Panca Sakti Bekasi', 'align' => 'left'],
            ['text' => 'DATA MEREK YANG DIAJUKAN', 'bold' => true, 'align' => 'left'],
            ['text' => '1. Nama Merek (Kata/Brand) : [Tuliskan Nama Merek]', 'bold' => true, 'align' => 'left'],
            ['text' => '2. Jenis Merek             : [ ] Merek Dagang  [ ] Merek Jasa', 'align' => 'left'],
            ['text' => '3. Unsur Warna Etiket      : [Cth: Biru Navy, Emas, Putih]', 'align' => 'left'],
            ['text' => '4. Kelas Barang / Jasa     : [Kelas Nice Classification, Cth: Kelas 42]', 'align' => 'left'],
            ['text' => '5. Jenis Barang / Jasa     : [Rincian produk/layanan yang dipasarkan]', 'align' => 'left'],
            ['text' => '6. Arti / Terjemahan Merek : [Uraian makna filosofis logo dan teks]', 'align' => 'left'],
            ['text' => 'LAMPIRAN WAJIB:', 'bold' => true, 'align' => 'left'],
            ['text' => '- 3 (tiga) lembar contoh etiket merek ukuran 5 x 5 cm resolusi tinggi.', 'align' => 'left'],
            ['text' => '- Fotokopi / Scan KTP Pemohon.', 'align' => 'left'],
            ['text' => '- Surat Pernyataan Kepemilikan Merek bermaterai Rp 10.000.', 'align' => 'left'],
            ['text' => '- Surat Rekomendasi Sentra HKI LPPM Universitas Panca Sakti Bekasi.', 'align' => 'left'],
        ];
    }

    private static function getPatentGuideContent(): array
    {
        return [
            ['header' => true, 'text' => 'I. PENDAHULUAN & SYARAT UTAMA PATEN'],
            ['header' => false, 'text' => 'Paten adalah hak eksklusif yang diberikan negara kepada inventor atas invensinya di bidang teknologi.'],
            ['header' => false, 'text' => 'Invensi harus memenuhi 3 syarat mutlak: 1) Kebaruan (Novelty), 2) Langkah Inventif, 3) Dapat Diterapkan Industri.'],
            ['header' => true, 'text' => 'II. SISTEMATIKA DOKUMEN SPESIFIKASI PATEN'],
            ['header' => false, 'text' => '1. Judul Invensi: Singkat, jelas, dan menggambarkan teknologi yang ditemukan.'],
            ['header' => false, 'text' => '2. Bidang Teknik Invensi: Menjelaskan klasifikasi bidang teknik industri terkait.'],
            ['header' => false, 'text' => '3. Latar Belakang Invensi: Uraikan kelemahan teknologi lama yang disempurnakan oleh temuan ini.'],
            ['header' => false, 'text' => '4. Ringkasan Invensi: Intisari teknis dari inovasi yang dihasilkan.'],
            ['header' => false, 'text' => '5. Uraian Singkat Gambar: Keterangan mengenai gambar teknik/bagan yang dilampirkan.'],
            ['header' => false, 'text' => '6. Uraian Lengkap Invensi: Penjelasan operasional menyeluruh sehingga ahli di bidangnya dapat mereplikasi.'],
            ['header' => false, 'text' => '7. Klaim: Bagian terpenting yang membatasi lingkup pelindungan hukum invensi.'],
            ['header' => false, 'text' => '8. Abstrak: Maksimal 200 kata mengenai solusi teknis invensi.'],
            ['header' => true, 'text' => 'III. PROSEDUR VERIFIKASI SENTRA HKI LPPM'],
            ['header' => false, 'text' => 'Draf spesifikasi paten diperiksa terlebih dahulu oleh Tim Ahli Sentra HKI sebelum pendaftaran DJKI.'],
            ['header' => false, 'text' => 'Konsultasi & pemeriksaan draf dapat diajukan melalui portal online Sentra HKI UPS Bekasi.'],
        ];
    }
}

