<?php

/**
 * @copyright Copyright (c) 2026 Ramli (ramli.baharuddin@gmail.com)
 * @license   All Rights Reserved
 */

namespace App\Http\Controllers;

use App\Services\DocumentTemplateService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentTemplateController extends Controller
{
    /**
     * Download legal template document.
     */
    public function download(string $slug): BinaryFileResponse
    {
        DocumentTemplateService::ensureTemplatesExist();

        $templates = [
            'surat-pernyataan-hak-cipta' => [
                'file' => 'surat-pernyataan-hak-cipta.docx',
                'name' => 'Surat_Pernyataan_Hak_Cipta_Sentra_HKI_UPS.docx',
                'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
            'panduan-pengajuan-paten' => [
                'file' => 'panduan-pengajuan-paten.pdf',
                'name' => 'Panduan_Pengajuan_Paten_Sentra_HKI_UPS.pdf',
                'mime' => 'application/pdf',
            ],
            'formulir-pendaftaran-merek' => [
                'file' => 'formulir-pendaftaran-merek.docx',
                'name' => 'Formulir_Pendaftaran_Merek_Sentra_HKI_UPS.docx',
                'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ],
        ];

        if (!isset($templates[$slug])) {
            abort(404, 'Dokumen template tidak ditemukan.');
        }

        $info = $templates[$slug];
        $path = storage_path('app/public/templates/' . $info['file']);

        if (!file_exists($path)) {
            abort(404, 'Berkas fisik template belum tersedia di server.');
        }

        return response()->download($path, $info['name'], [
            'Content-Type' => $info['mime'],
        ]);
    }
}

