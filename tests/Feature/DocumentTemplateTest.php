<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTemplateTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_download_copyright_declaration_docx(): void
    {
        $response = $this->get(route('dokumen.unduh', 'surat-pernyataan-hak-cipta'));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'Surat_Pernyataan_Hak_Cipta_Sentra_HKI_UPS.docx'));
    }

    public function test_can_download_patent_guide_pdf(): void
    {
        $response = $this->get(route('dokumen.unduh', 'panduan-pengajuan-paten'));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'Panduan_Pengajuan_Paten_Sentra_HKI_UPS.pdf'));
    }

    public function test_can_download_trademark_form_docx(): void
    {
        $response = $this->get(route('dokumen.unduh', 'formulir-pendaftaran-merek'));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'Formulir_Pendaftaran_Merek_Sentra_HKI_UPS.docx'));
    }

    public function test_invalid_slug_returns_404(): void
    {
        $response = $this->get(route('dokumen.unduh', 'file-tidak-valid-999'));

        $response->assertStatus(404);
    }
}

