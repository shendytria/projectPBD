<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    private $pdo;

    public function __construct()
    {
        // Konfigurasi koneksi ke database menggunakan PDO
        $host = '127.0.0.1';
        $db = 'bismillahpbd';
        $user = 'root';
        $pass = ''; // tanpa password
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

    // Menampilkan daftar vendor
    public function index()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vendor");
        $stmt->execute();
        $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return view('vendor.index', compact('vendors'));
    }

    // Menampilkan form create vendor
    public function create()
    {
        return view('vendor.create');
    }

    // Menyimpan vendor baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            // 'badan_hukum' => 'required|string|size:1',
            // 'status' => 'required|string|size:1',
        ]);

        // Prepare the statement
        $stmt = $this->pdo->prepare("INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (:nama_vendor, :badan_hukum, :status)");

        // Use input() method or array-like access
        $nama_vendor = $request->input('nama_vendor');
        // $badan_hukum = $request->input('badan_hukum');
        // $status = $request->input('status');

        // Bind parameters using variables
        $stmt->bindParam(':nama_vendor', $nama_vendor);
        $stmt->bindParam(':badan_hukum', $badan_hukum);
        $stmt->bindParam(':status', $status);

        // Execute the statement
        $stmt->execute();

        return redirect('/vendor')->with('success', 'Vendor created successfully');
    }

    public function updateStatusBH(Request $request, $id)
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
        DB::table('vendor')
            ->where('idvendor', $id)
            ->update(['status' => $validated['status']]);

        return redirect()->route('vendor.index')->with('success', 'Status berhasil diperbarui.');
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
        DB::table('vendor')
            ->where('idvendor', $id)
            ->update(['status' => $validated['status']]);

        return redirect()->route('vendor.index')->with('success', 'Status berhasil diperbarui.');
    }

    // Menampilkan form edit vendor berdasarkan ID
    public function edit($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vendor WHERE idvendor = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vendor) {
            return redirect('/vendor')->with('error', 'Vendor not found');
        }

        return view('vendor.edit', compact('vendor'));
    }

    // Memperbarui data vendor di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|string|size:1',
            'status' => 'required|string|size:1',
        ]);

        // Prepare the statement
        $stmt = $this->pdo->prepare("UPDATE vendor SET nama_vendor = :nama_vendor, badan_hukum = :badan_hukum, status = :status WHERE idvendor = :id");

        // Use input() method or array-like access
        $nama_vendor = $request->input('nama_vendor');
        $badan_hukum = $request->input('badan_hukum');
        $status = $request->input('status');

        // Bind parameters using variables
        $stmt->bindParam(':nama_vendor', $nama_vendor);
        $stmt->bindParam(':badan_hukum', $badan_hukum);
        $stmt->bindParam(':status', $status);

        // Bind the ID variable for the update query
        $stmt->bindParam(':id', $id);

        // Execute the statement
        $stmt->execute();

        return redirect('/vendor')->with('success', 'Vendor updated successfully');
    }

    // Menghapus vendor dari database
    public function destroy($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM vendor WHERE idvendor = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return redirect('/vendor')->with('success', 'Vendor deleted successfully');
    }
}
