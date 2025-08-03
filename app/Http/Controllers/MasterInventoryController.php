<?php

namespace App\Http\Controllers;

use App\Models\MasterInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = MasterInventory::orderBy('kode_barang')->paginate(10);
        return view('master-data.inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.inventories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:master_inventories,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            MasterInventory::create([
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'kategori_barang' => $request->kategori_barang,
                'satuan' => $request->satuan,
                'harga_beli' => $request->harga_beli ?? 0,
                'harga_jual' => $request->harga_jual ?? 0,
                'stok_minimum' => $request->stok_minimum ?? 0,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('master-inventories.index')
                           ->with('success', 'Barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterInventory $masterInventory)
    {
        return view('master-data.inventories.show', ['inventory' => $masterInventory]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterInventory $masterInventory)
    {
        return view('master-data.inventories.edit', ['inventory' => $masterInventory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterInventory $masterInventory)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:master_inventories,kode_barang,' . $masterInventory->id,
            'nama_barang' => 'required|string|max:255',
            'kategori_barang' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            $masterInventory->update([
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'kategori_barang' => $request->kategori_barang,
                'satuan' => $request->satuan,
                'harga_beli' => $request->harga_beli ?? 0,
                'harga_jual' => $request->harga_jual ?? 0,
                'stok_minimum' => $request->stok_minimum ?? 0,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('master-inventories.index')
                           ->with('success', 'Barang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterInventory $masterInventory)
    {
        try {
            // Check if inventory is being used in any transactions
            // Add your business logic here to check dependencies
            
            $masterInventory->delete();
            
            return redirect()->route('master-inventories.index')
                           ->with('success', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master-inventories.index')
                           ->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}
