<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Services\DocumentTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicInformationController extends Controller
{
    /**
     * Halaman Publik Pusat Informasi & Panduan Lengkap.
     */
    public function index(Request $request)
    {
        $kategori = $request->query('kategori', 'all');
        $search = $request->query('q');

        $query = Information::where('is_active', true)->orderBy('order')->latest();

        if ($kategori !== 'all' && in_array($kategori, ['alur_syarat', 'template_dokumen', 'faq', 'pengumuman', 'panduan'])) {
            $query->where('category', $kategori);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(12)->withQueryString();

        $alurs = Information::where('category', 'alur_syarat')->where('is_active', true)->orderBy('order')->get();
        $templates = Information::where('category', 'template_dokumen')->where('is_active', true)->orderBy('order')->get();
        $faqs = Information::where('category', 'faq')->where('is_active', true)->orderBy('order')->get();
        $announcements = Information::where('category', 'pengumuman')->where('is_active', true)->orderBy('order')->get();

        return view('information.public_index', compact('items', 'alurs', 'templates', 'faqs', 'announcements', 'kategori', 'search'));
    }

    /**
     * Detail Baca Informasi / Panduan Publik.
     */
    public function show(string $slug)
    {
        $information = Information::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Information::where('category', $information->category)
            ->where('id', '!=', $information->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('information.public_show', compact('information', 'related'));
    }

    /**
     * Download Berkas Template Dokumen (Dinamis).
     */
    public function download(string $slug): BinaryFileResponse
    {
        DocumentTemplateService::ensureTemplatesExist();

        $info = Information::where('slug', $slug)->first();

        // 1. Cek dari database
        if ($info && $info->file_path && Storage::disk('public')->exists($info->file_path)) {
            $path = Storage::disk('public')->path($info->file_path);
            $downloadName = $info->file_name ?: basename($info->file_path);

            return response()->download($path, $downloadName);
        }

        // 2. Fallback cek hardcoded template default jika file di storage belum terpetakan
        $legacyMap = [
            'surat-pernyataan-hak-cipta' => [
                'file' => 'surat-pernyataan-hak-cipta.docx',
                'name' => 'Surat_Pernyataan_Hak_Cipta_Sentra_HKI_UPS.docx',
            ],
            'panduan-pengajuan-paten' => [
                'file' => 'panduan-pengajuan-paten.pdf',
                'name' => 'Panduan_Pengajuan_Paten_Sentra_HKI_UPS.pdf',
            ],
            'formulir-pendaftaran-merek' => [
                'file' => 'formulir-pendaftaran-merek.docx',
                'name' => 'Formulir_Pendaftaran_Merek_Sentra_HKI_UPS.docx',
            ],
        ];

        if (isset($legacyMap[$slug])) {
            $fallbackPath = storage_path('app/public/templates/' . $legacyMap[$slug]['file']);
            if (file_exists($fallbackPath)) {
                return response()->download($fallbackPath, $legacyMap[$slug]['name']);
            }
        }

        abort(404, 'Berkas dokumen tidak ditemukan di server.');
    }
}
