<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InformationController extends Controller
{
    /**
     * Admin: Tampilkan daftar informasi, panduan, dan template dokumen.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'all');
        $search = $request->query('q');

        $query = Information::with('creator')->orderBy('order')->latest();

        if ($tab !== 'all' && in_array($tab, ['alur_syarat', 'template_dokumen', 'faq', 'pengumuman', 'panduan'])) {
            $query->where('category', $tab);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('badge_text', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Information::count(),
            'alur_syarat' => Information::where('category', 'alur_syarat')->count(),
            'template_dokumen' => Information::where('category', 'template_dokumen')->count(),
            'faq' => Information::where('category', 'faq')->count(),
            'pengumuman' => Information::where('category', 'pengumuman')->count(),
        ];

        return view('admin.information_index', compact('items', 'stats', 'tab', 'search'));
    }

    /**
     * Admin: Simpan informasi atau template dokumen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:alur_syarat,template_dokumen,faq,pengumuman,panduan'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:50'],
            'external_link' => ['nullable', 'url', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip', 'max:10240'],
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;
        $fileSize = null;

        if ($request->hasFile('file')) {
            $uploaded = $request->file('file');
            $fileName = $uploaded->getClientOriginalName();
            $fileType = strtoupper($uploaded->getClientOriginalExtension());
            $sizeInBytes = $uploaded->getSize();
            $fileSize = $sizeInBytes >= 1048576 
                ? number_format($sizeInBytes / 1048576, 1) . ' MB' 
                : number_format($sizeInBytes / 1024, 0) . ' KB';

            $filePath = $uploaded->store('information/files', 'public');
        }

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Information::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . $count++;
        }

        Information::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'icon' => $validated['icon'] ?? 'file-text',
            'external_link' => $validated['external_link'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.informasi.index', ['tab' => $validated['category']])
            ->with('success', 'Informasi / template dokumen berhasil ditambahkan.');
    }

    /**
     * Admin: Update informasi atau template dokumen.
     */
    public function update(Request $request, Information $informasi)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:alur_syarat,template_dokumen,faq,pengumuman,panduan'],
            'description' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:50'],
            'external_link' => ['nullable', 'url', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip', 'max:10240'],
        ]);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'content' => $validated['content'] ?? null,
            'badge_text' => $validated['badge_text'] ?? null,
            'icon' => $validated['icon'] ?? $informasi->icon,
            'external_link' => $validated['external_link'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($informasi->file_path && Storage::disk('public')->exists($informasi->file_path)) {
                Storage::disk('public')->delete($informasi->file_path);
            }

            $uploaded = $request->file('file');
            $data['file_name'] = $uploaded->getClientOriginalName();
            $data['file_type'] = strtoupper($uploaded->getClientOriginalExtension());
            $sizeInBytes = $uploaded->getSize();
            $data['file_size'] = $sizeInBytes >= 1048576 
                ? number_format($sizeInBytes / 1048576, 1) . ' MB' 
                : number_format($sizeInBytes / 1024, 0) . ' KB';
            $data['file_path'] = $uploaded->store('information/files', 'public');
        }

        $informasi->update($data);

        return redirect()->route('admin.informasi.index', ['tab' => $validated['category']])
            ->with('success', 'Data informasi / panduan berhasil diperbarui.');
    }

    /**
     * Admin: Hapus informasi.
     */
    public function destroy(Information $informasi)
    {
        if ($informasi->file_path && Storage::disk('public')->exists($informasi->file_path)) {
            Storage::disk('public')->delete($informasi->file_path);
        }

        $category = $informasi->category;
        $informasi->delete();

        return redirect()->route('admin.informasi.index', ['tab' => $category])
            ->with('success', 'Informasi / dokumen berhasil dihapus.');
    }

    /**
     * Admin: Toggle status aktif/publikasi.
     */
    public function toggleStatus(Information $informasi)
    {
        $informasi->update([
            'is_active' => !$informasi->is_active,
        ]);

        $statusStr = $informasi->is_active ? 'ditampilkan di publik' : 'disembunyikan dari publik';

        return back()->with('success', "Status informasi '{$informasi->title}' berhasil diubah menjadi {$statusStr}.");
    }
}
