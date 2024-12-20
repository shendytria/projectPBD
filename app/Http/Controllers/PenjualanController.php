<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use PDOException;
use Exception;
use PDO;

class PenjualanController extends Controller
{
    // Menyambungkan ke database menggunakan PDO secara langsung
    private function getConnection()
    {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=bismillahpbd', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            // Jika terjadi error dalam koneksi
            return redirect()->back()->with('error', 'Koneksi Gagal: ' . $e->getMessage());
        }
    }

    // Menampilkan data penjualan
    public function index()
    {
        try {
            $pdo = $this->getConnection();

            // Mengambil data penjualan dengan query yang sudah diberikan
            $stmt = $pdo->prepare('SELECT * FROM barang as b
                                    JOIN kartu_stok as ks ON ks.idbarang = b.idbarang
                                    AND ks.created_at = (SELECT MAX(created_at) FROM kartu_stok WHERE idbarang = b.idbarang)
                                    WHERE stock >= 1');
            $stmt->execute();
            $penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Mengambil detail penjualan
            $stmt = $pdo->prepare('
                SELECT
                    p.idpenjualan,
                    dp.jumlah,
                    dp.harga_satuan,
                    dp.subtotal,
                    p.total_nilai,
                    p.created_at,
                    b.nama AS nama_barang,
                    u.username
                FROM penjualan p
                JOIN detail_penjualan dp ON dp.idpenjualan = p.idpenjualan
                JOIN barang b ON dp.idbarang = b.idbarang
                JOIN user u ON p.iduser = u.iduser
                ORDER BY p.created_at DESC
            ');
            $stmt->execute();
            $detail_penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mengambil data users
            $stmt = $pdo->prepare('SELECT iduser, username FROM user');
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mengambil data margin penjualan
            $stmt = $pdo->prepare('SELECT idmargin_penjualan, persen FROM margin_penjualan');
            $stmt->execute();
            $margin_penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mengembalikan data ke view
            return view('penjualan.index', compact('penjualan', 'detail_penjualan', 'users', 'margin_penjualan'));
        } catch (PDOException $e) {
            // Menangani error jika terjadi saat query
            return redirect()->back()->with('error', 'Gagal mengambil data: ' . $e->getMessage());
        }
    }

    // Menyimpan data penjualan
    public function save(Request $request)
    {
        try {
            $pdo = $this->getConnection();

            $stmt = $pdo->prepare('INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan)
                                   VALUES (NOW(), ?, ?, ?, ?, ?)');
            $stmt->execute([
                $request->input('subtotal_nilai'),
                $request->input('ppn'),
                $request->input('total_nilai'),
                $request->input('iduser'),
                $request->input('idmargin_penjualan')
            ]);

            return redirect('/penjualan/index')->with('success', 'Penjualan created successfully!');
        } catch (PDOException $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
        }
    }

    // Menangani checkout penjualan
    public function checkout(Request $request)
    {
        $pdo = $this->getConnection();

        try {
            // Log isi request untuk debugging
            Log::info('Checkout request received', [
                'idbarang' => $request->idbarang,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'ppn' => $request->ppn,
                'iduser' => $request->iduser,
                'total' => $request->total,
            ]);

            // Mulai transaksi
            $pdo->beginTransaction();

            // Mengecek apakah stok mencukupi dengan mengambil kartu stok terakhir untuk idbarang
            $stmt = $pdo->prepare("SELECT stock FROM kartu_stok WHERE idbarang = :idbarang ORDER BY created_at DESC LIMIT 1");
            $stmt->execute(['idbarang' => $request->idbarang]);
            $stok_tersedia = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$stok_tersedia) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan di stok!');
            }

            if ($stok_tersedia['stock'] < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            // Menghitung total nilai berdasarkan jumlah dan harga satuan
            $total_nilai = $request->jumlah * $request->harga_satuan;

            // Menyimpan data penjualan ke tabel penjualan
            $penjualan = [
                'created_at' => now(),
                'subtotal_nilai' => $total_nilai,
                'ppn' => $request->ppn,
                'iduser' => $request->iduser,
                'total_nilai' => $request->total, // total harga setelah PPN
            ];

            $stmt = $pdo->prepare("INSERT INTO penjualan (created_at, subtotal_nilai, ppn, iduser, total_nilai)
                VALUES (:created_at, :subtotal_nilai, :ppn, :iduser, :total_nilai)");
            $stmt->execute($penjualan);

            // Mendapatkan ID penjualan yang baru saja ditambahkan
            $idpenjualan = $pdo->lastInsertId();

            // Menyimpan detail penjualan
            $detail_penjualan = [
                'harga_satuan' => $request->harga_satuan,
                'jumlah' => $request->jumlah,
                'subtotal' => $total_nilai,
                'idpenjualan' => $idpenjualan,
                'idbarang' => $request->idbarang,
            ];

            $stmt = $pdo->prepare("INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, idpenjualan, idbarang)
                VALUES (:harga_satuan, :jumlah, :subtotal, :idpenjualan, :idbarang)");
            $stmt->execute($detail_penjualan);

            // Commit transaksi jika semua berhasil
            $pdo->commit();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Transaksi Berhasil');
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error
            $pdo->rollBack();

            // Log error
            Log::error('Error during checkout transaction', [
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
