<?php

namespace App\Http\Controllers;

use App\Models\IntellectualProperty;
use App\Models\IprInventor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicSubmissionController extends Controller
{
    /**
     * Handle fast public submission for Intellectual Property.
     */
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'ipr_type' => ['required', 'string', 'in:hak_cipta,paten,paten_sederhana,merek,desain_industri,lainnya'],
            'title' => ['required', 'string', 'max:500'],
            'sub_type' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama.required' => 'Nama lengkap pemohon wajib diisi.',
            'email.required' => 'Email pemohon wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'ipr_type.required' => 'Kategori layanan HKI wajib dipilih.',
            'title.required' => 'Judul ciptaan / inovasi wajib diisi.',
        ]);

        if (!auth()->check() && User::where('email', strtolower(trim($validated['email'])))->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar. Silakan masuk sebelum mengajukan permohonan.',
            ], 422);
        }

        return DB::transaction(function () use ($validated) {
            // Find or associate user
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                $user = User::firstOrCreate(
                    ['email' => strtolower(trim($validated['email']))],
                    [
                        'name' => trim($validated['nama']),
                        'phone' => trim($validated['phone']),
                        'role' => 'dosen',
                        'password' => Hash::make(Str::random(12)),
                        'is_active' => true,
                    ]
                );
            }

            // Generate clean application number
            $year = date('Y');
            $count = IntellectualProperty::whereYear('created_at', $year)->count() + 1;

            // Format: EC00YYYYXXXXX for copyright, P00 for patent, DID for brand, IDD for design
            $prefix = match ($validated['ipr_type']) {
                'hak_cipta' => 'EC00',
                'paten', 'paten_sederhana' => 'P00',
                'merek' => 'DID',
                'desain_industri' => 'IDD',
                default => 'HKI',
            };
            $appNumber = sprintf("%s%s%05d", $prefix, $year, $count);

            $ipr = IntellectualProperty::create([
                'user_id' => $user->id,
                'title' => trim($validated['title']),
                'ipr_type' => $validated['ipr_type'],
                'sub_type' => $validated['sub_type'] ?? match ($validated['ipr_type']) {
                    'hak_cipta' => 'Karya Tulis / Program Komputer',
                    'paten' => 'Invensi Teknologi',
                    'merek' => 'Merek Dagang & Jasa',
                    'desain_industri' => 'Desain Industri',
                    default => 'Lainnya',
                },
                'application_number' => $appNumber,
                'application_date' => now()->toDateString(),
                'registration_number' => null,
                'grant_date' => null,
                'djki_status' => 'draft',
                'rights_holder' => 'Universitas Panca Sakti Bekasi',
                'verification_status' => 'pending',
                'verification_note' => !empty($validated['notes']) ? 'Catatan awal pemohon: ' . $validated['notes'] : null,
            ]);

            // Add primary inventor
            $lecturerId = $user->lecturer ? $user->lecturer->id : null;
            IprInventor::create([
                'intellectual_property_id' => $ipr->id,
                'lecturer_id' => $lecturerId,
                'inventor_name' => trim($validated['nama']),
                'inventor_type' => $lecturerId ? 'internal_lecturer' : 'external',
                'role' => 'inventor_utama',
                'inventor_order' => 1,
                'contribution_percentage' => 100.00,
                'affiliation' => 'Universitas Panca Sakti Bekasi',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permohonan HKI berhasil diajukan ke Sentra HKI LPPM!',
                'data' => [
                    'id' => $ipr->id,
                    'application_number' => $appNumber,
                    'title' => $ipr->title,
                    'ipr_type' => $ipr->ipr_type,
                    'created_at' => $ipr->created_at->format('d/m/Y H:i'),
                ],
            ]);
        });
    }
}

