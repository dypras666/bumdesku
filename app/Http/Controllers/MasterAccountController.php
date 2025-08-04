<?php

namespace App\Http\Controllers;

use App\Models\MasterAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterAccountController extends Controller
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
        $accounts = MasterAccount::orderBy('kode_akun')->paginate(10);
        return view('master-data.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-data.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required|string|max:255|unique:master_accounts',
            'nama_akun' => 'required|string|max:255',
            'kategori_akun' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MasterAccount::create($request->all());

        return redirect()->route('master-accounts.index')
            ->with('success', 'Master Akun berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterAccount $masterAccount)
    {
        return view('master-data.accounts.show', compact('masterAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterAccount $masterAccount)
    {
        return view('master-data.accounts.edit', compact('masterAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterAccount $masterAccount)
    {
        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required|string|max:255|unique:master_accounts,kode_akun,' . $masterAccount->id,
            'nama_akun' => 'required|string|max:255',
            'kategori_akun' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $masterAccount->update($request->all());

        return redirect()->route('master-accounts.index')
            ->with('success', 'Master Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterAccount $masterAccount)
    {
        try {
            $masterAccount->delete();
            return redirect()->route('master-accounts.index')
                ->with('success', 'Master Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master-accounts.index')
                ->with('error', 'Gagal menghapus Master Akun. Data mungkin sedang digunakan.');
        }
    }
}
