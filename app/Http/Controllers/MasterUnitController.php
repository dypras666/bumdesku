<?php

namespace App\Http\Controllers;

use App\Models\MasterUnit;
use App\Models\UnitChangeHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = MasterUnit::with('penanggungJawab')->orderBy('kode_unit')->paginate(10);
        return view('master-data.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('master-data.units.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_unit' => 'required|string|max:50|unique:master_units,kode_unit',
            'nama_unit' => 'required|string|max:255',
            'kategori_unit' => 'required|string|max:100',
            'nilai_aset' => 'nullable|numeric|min:0',
            'alamat' => 'nullable|string',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            
            $unit = MasterUnit::create([
                'kode_unit' => $request->kode_unit,
                'nama_unit' => $request->nama_unit,
                'kategori_unit' => $request->kategori_unit,
                'nilai_aset' => $request->nilai_aset ?? 0,
                'alamat' => $request->alamat,
                'penanggung_jawab_id' => $request->penanggung_jawab_id,
                'is_active' => $request->has('is_active')
            ]);

            // Catat riwayat pembuatan
            UnitChangeHistory::create([
                'master_unit_id' => $unit->id,
                'field_name' => 'unit_created',
                'old_value' => null,
                'new_value' => $unit->nama_unit,
                'action' => 'create',
                'changed_by' => Auth::user()->name ?? 'System',
                'description' => "Unit '{$unit->nama_unit}' berhasil dibuat dengan kode '{$unit->kode_unit}'"
            ]);

            DB::commit();
            return redirect()->route('master-units.index')
                           ->with('success', 'Unit berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan unit: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterUnit $masterUnit)
    {
        $masterUnit->load(['changeHistories', 'penanggungJawab']);
        return view('master-data.units.show', ['unit' => $masterUnit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterUnit $masterUnit)
    {
        $users = User::orderBy('name')->get();
        return view('master-data.units.edit', ['unit' => $masterUnit, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterUnit $masterUnit)
    {
        $request->validate([
            'kode_unit' => 'required|string|max:50|unique:master_units,kode_unit,' . $masterUnit->id,
            'nama_unit' => 'required|string|max:255',
            'kategori_unit' => 'required|string|max:100',
            'nilai_aset' => 'nullable|numeric|min:0',
            'alamat' => 'nullable|string',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();
            
            // Simpan data lama untuk perbandingan
            $oldData = $masterUnit->toArray();
            
            // Data baru
            $newData = [
                'kode_unit' => $request->kode_unit,
                'nama_unit' => $request->nama_unit,
                'kategori_unit' => $request->kategori_unit,
                'nilai_aset' => $request->nilai_aset ?? 0,
                'alamat' => $request->alamat,
                'penanggung_jawab_id' => $request->penanggung_jawab_id,
                'is_active' => $request->has('is_active')
            ];

            // Update unit
            $masterUnit->update($newData);

            // Catat perubahan untuk setiap field yang berubah
            $changedBy = Auth::user()->name ?? 'System';
            $fieldsToTrack = [
                'kode_unit' => 'Kode Unit',
                'nama_unit' => 'Nama Unit',
                'kategori_unit' => 'Kategori Unit',
                'nilai_aset' => 'Nilai Aset',
                'alamat' => 'Alamat',
                'penanggung_jawab_id' => 'Penanggung Jawab',
                'is_active' => 'Status Aktif'
            ];

            foreach ($fieldsToTrack as $field => $label) {
                $oldValue = $oldData[$field] ?? null;
                $newValue = $newData[$field] ?? null;
                
                // Konversi boolean untuk perbandingan
                if ($field === 'is_active') {
                    $oldValue = (bool) $oldValue;
                    $newValue = (bool) $newValue;
                }
                
                if ($oldValue != $newValue) {
                    UnitChangeHistory::create([
                        'master_unit_id' => $masterUnit->id,
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'action' => 'update',
                        'changed_by' => $changedBy,
                        'description' => "{$label} diubah dari '{$this->formatValueForDescription($field, $oldValue)}' menjadi '{$this->formatValueForDescription($field, $newValue)}'"
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('master-units.index')
                           ->with('success', 'Unit berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui unit: ' . $e->getMessage());
        }
    }

    /**
     * Format nilai untuk deskripsi perubahan
     */
    private function formatValueForDescription($field, $value)
    {
        if ($value === null || $value === '') return 'Kosong';
        
        switch ($field) {
            case 'nilai_aset':
                return 'Rp ' . number_format($value, 0, ',', '.');
            case 'is_active':
                return $value ? 'Aktif' : 'Tidak Aktif';
            case 'penanggung_jawab_id':
                if ($value) {
                    $user = User::find($value);
                    return $user ? $user->name : 'User tidak ditemukan';
                }
                return 'Kosong';
            default:
                return $value;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterUnit $masterUnit)
    {
        try {
            // Check if unit is being used in any transactions
            // Add your business logic here to check dependencies
            
            $masterUnit->delete();
            
            return redirect()->route('master-units.index')
                           ->with('success', 'Unit berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master-units.index')
                           ->with('error', 'Gagal menghapus unit: ' . $e->getMessage());
        }
    }
}
