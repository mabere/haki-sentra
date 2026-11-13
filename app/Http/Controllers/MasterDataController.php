<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\IncentiveScheme;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Display master data management dashboard.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'schemes');

        $schemes = IncentiveScheme::orderBy('category')->orderBy('name')->get();
        $faculties = Faculty::with('departments')->orderBy('name')->get();
        $departments = Department::with('faculty')->orderBy('name')->get();

        $stats = [
            'total_schemes' => $schemes->count(),
            'active_schemes' => $schemes->where('is_active', true)->count(),
            'total_faculties' => $faculties->count(),
            'total_departments' => $departments->count(),
        ];

        return view('admin.master_index', compact('schemes', 'faculties', 'departments', 'tab', 'stats'));
    }

    /**
     * Store new incentive scheme.
     */
    public function storeScheme(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:publication,ipr'],
            'tier' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0'],
            'fiscal_year' => ['required', 'integer', 'min:2020', 'max:2050'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        IncentiveScheme::create($validated);

        return redirect()->route('admin.master.index', ['tab' => 'schemes'])
            ->with('success', 'Skema tarif insentif baru berhasil ditambahkan.');
    }

    /**
     * Update existing incentive scheme.
     */
    public function updateScheme(Request $request, IncentiveScheme $scheme)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:publication,ipr'],
            'tier' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0'],
            'fiscal_year' => ['required', 'integer', 'min:2020', 'max:2050'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $scheme->update($validated);

        return redirect()->route('admin.master.index', ['tab' => 'schemes'])
            ->with('success', 'Skema tarif insentif berhasil diperbarui.');
    }

    /**
     * Delete an incentive scheme.
     */
    public function destroyScheme(IncentiveScheme $scheme)
    {
        if ($scheme->requests()->exists()) {
            return back()->withErrors(['error' => 'Skema tidak dapat dihapus karena sudah memiliki riwayat klaim pengajuan dosen. Anda dapat menonaktifkannya.']);
        }

        $scheme->delete();

        return redirect()->route('admin.master.index', ['tab' => 'schemes'])
            ->with('success', 'Skema tarif insentif berhasil dihapus.');
    }

    /**
     * Store new faculty.
     */
    public function storeFaculty(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:faculties,code'],
        ]);

        Faculty::create($validated);

        return redirect()->route('admin.master.index', ['tab' => 'faculties'])
            ->with('success', 'Fakultas baru berhasil ditambahkan.');
    }

    /**
     * Store new department / prodi.
     */
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => ['required', 'exists:faculties,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
        ]);

        Department::create($validated);

        return redirect()->route('admin.master.index', ['tab' => 'faculties'])
            ->with('success', 'Program studi baru berhasil ditambahkan.');
    }
}
