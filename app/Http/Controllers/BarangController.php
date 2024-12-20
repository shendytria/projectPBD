<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    private $pdo;

    public function __construct()
    {
        // Konfigurasi koneksi ke database menggunakan PDO
        $host = '127.0.0.1';
        $db = 'bismillahpbd'; // Nama database
        $user = 'root';
        $pass = ''; // Tanpa password
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    // Menampilkan daftar barang
    public function index()
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT barang.*, satuan.nama_satuan,
                CASE
                    WHEN barang.jenis = 0 THEN 'Makanan'
                    WHEN barang.jenis = 1 THEN 'Minuman'
                    ELSE 'Unknown'
                END AS jenis
            FROM barang
            LEFT JOIN satuan ON barang.idsatuan = satuan.idsatuan
        ");
            $stmt->execute();
            $barangs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return view('barang.index', compact('barangs'));
        } catch (PDOException $e) {
            return abort(500, 'Failed to fetch data: ' . $e->getMessage());
        }
    }


    // Menampilkan form create barang
    public function create()
    {
        // Ambil data satuan untuk dropdown
        $stmt = $this->pdo->prepare("SELECT * FROM satuan");
        $stmt->execute();
        $satuans = $stmt->fetchAll();

        return view('barang.create', compact('satuans'));
    }

    // Menyimpan barang baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis' => 'required|in:0,1',
            'nama' => 'required|string|max:45',
            'idsatuan' => 'required|integer',
            'harga' => 'required|integer',
            // 'status' => 'required|boolean',
        ]);

        // Prepare the statement
        $stmt = $this->pdo->prepare("INSERT INTO barang (jenis, nama, idsatuan, harga, status) VALUES (:jenis, :nama, :idsatuan, :harga, :status)");

        // Bind parameters using variables
        $jenis = $request->input('jenis');
        $nama = $request->input('nama');
        $idsatuan = $request->input('idsatuan');
        $harga = $request->input('harga');
        // $status = $request->input('status');

        // Use bindParam with variables
        $stmt->bindParam(':jenis', $jenis);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':idsatuan', $idsatuan);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':status', $status);

        // Execute the statement
        $stmt->execute();

        return redirect('/barang')->with('success', 'Barang created successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:1,0', // Tambahkan DP jika itu adalah status baru
        ]);

        // Cek apakah pengguna saat ini adalah penerimaan
        // if (auth()->user()->role !== 'penerimaan') {
        //     return redirect()->back()->with('error', 'Hanya penerimaan yang dapat mengubah status pengadaan.');
        // }

        // Update status pengadaan
        DB::table('barang')
            ->where('idbarang', $id)
            ->update(['status' => $validated['status']]);

        return redirect()->route('barang.index')->with('success', 'Status berhasil diperbarui.');
    }

    // Menampilkan form edit barang berdasarkan ID
    public function edit($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM barang WHERE idbarang = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $barang = $stmt->fetch();

        // Ambil data satuan untuk dropdown
        $stmtSatuan = $this->pdo->prepare("SELECT * FROM satuan");
        $stmtSatuan->execute();
        $satuans = $stmtSatuan->fetchAll();

        return view('barang.edit', compact('barang', 'satuans'));
    }

    // Memperbarui data barang di database
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'jenis' => 'required|in:0,1',
            'nama' => 'required|string|max:45',
            'idsatuan' => 'required|integer',
            'harga' => 'required|integer',
            // 'status' => 'required|boolean',
        ]);

        // Prepare the statement
        $stmt = $this->pdo->prepare("UPDATE barang SET jenis = :jenis, nama = :nama, idsatuan = :idsatuan, harga = :harga, status = :status WHERE idbarang = :id");

        // Bind parameters using variables
        $jenis = $request->input('jenis');
        $nama = $request->input('nama');
        $idsatuan = $request->input('idsatuan');
        $harga = $request->input('harga');
        $status = $request->input('status');

        // Use bindParam with variables
        $stmt->bindParam(':jenis', $jenis);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':idsatuan', $idsatuan);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':status', $status);

        // Bind the ID variable for the update query
        $stmt->bindParam(':id', $id);

        // Execute the statement
        $stmt->execute();

        return redirect('/barang')->with('success', 'Barang updated successfully');
    }

    // Menghapus barang dari database
    public function destroy($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM barang WHERE idbarang = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return redirect('/barang')->with('success', 'Barang deleted successfully');
    }
}
