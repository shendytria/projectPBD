<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Menampilkan data Kartu Stok
    public function dashboard()
    {
        try {
            // Membuat koneksi menggunakan PDO
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=bismillahpbd', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ambil data kartu stok dengan raw query
            $kartu_stok = DB::select("SELECT kartu_stok.*, barang.nama
        FROM kartu_stok
        JOIN barang ON kartu_stok.idbarang = barang.idbarang");

            // Ambil data jumlah barang yang tersedia
            $barang_available = DB::select("SELECT b.idbarang, b.nama, SUM(k.stock) as total_stock
              FROM barang b
              LEFT JOIN kartu_stok k ON b.idbarang = k.idbarang
              GROUP BY b.idbarang, b.nama");

            // Mengambil stok terakhir setiap barang menggunakan query builder DB
            $barang = DB::table('barang as b')
                ->select(
                    'b.nama as nama_barang',
                    DB::raw('IFNULL(MAX(ks.stock), 0) as stock_terakhir'),
                    DB::raw('IFNULL(SUM(ks.masuk), 0) - IFNULL(SUM(ks.keluar), 0) as stock_awal'),
                    DB::raw('IFNULL(SUM(ks.keluar), 0) as stock_terjual')
                )
                ->leftJoin('kartu_stok as ks', 'b.idbarang', '=', 'ks.idbarang')
                ->groupBy('b.idbarang', 'b.nama')
                ->get();

            return view('admin.dashboard', [
                'kartu_stok' => $kartu_stok,
                'barang_available' => $barang_available,
                'barang' => $barang
            ]);
        } catch (PDOException $e) {
            // Jika ada error dalam query
            return redirect()->back()->with('error', 'Gagal mengambil data: ' . $e->getMessage());
        }
    }
}
