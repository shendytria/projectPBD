<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class MarginPenjualanController extends Controller
{
    // Fungsi untuk menampilkan form create
    public function create()
{
    $users = DB::table('user')->get();  // Ambil data user dari tabel 'user'
    $margin_penjualan = DB::table('margin_penjualan')
        ->join('user', 'margin_penjualan.iduser', '=', 'user.iduser')
        ->select('margin_penjualan.*', 'user.username')
        ->get(); // Ambil data margin penjualan beserta username

    // Kirimkan data users dan margin_penjualan ke view
    return view('margin_penjualan.create', compact('users', 'margin_penjualan'));
}

    // Fungsi untuk menyimpan data margin penjualan
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'persen' => 'required|numeric',
            'status' => 'required|boolean',
            'iduser' => 'required|integer',
        ]);

        // Mendapatkan nilai input dari form
        $persen = $request->input('persen');
        $status = $request->input('status');
        $iduser = $request->input('iduser');
        $created_at = Carbon::now();
        $updated_at = Carbon::now();

        // Query untuk menyimpan data margin penjualan
        try {
            DB::beginTransaction();

            DB::table('margin_penjualan')->insert([
                'persen' => $persen,
                'status' => $status,
                'iduser' => $iduser,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);

            DB::commit();
            return redirect()->route('margin_penjualan.create')->with('success', 'Margin Penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('margin_penjualan.create')->with('error', 'Gagal menyimpan Margin Penjualan. ' . $e->getMessage());
        }
    }
}
