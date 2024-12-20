<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    private $pdo;

    public function __construct()
    {
        // Database connection configuration
        $host = '127.0.0.1';
        $db = 'bismillahpbd'; // Replace with your database name
        $user = 'root'; // Replace with your database user
        $pass = ''; // Replace with your database password
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

    // Menampilkan daftar satuan
    public function index()
    {
        // Mengambil semua data dari tabel satuan
        $stmt = $this->pdo->prepare("SELECT * FROM satuan");
        $stmt->execute();
        $satuans = $stmt->fetchAll();

        return view('satuan.index', compact('satuans'));
    }

    // Menampilkan form create satuan
    public function create()
    {
        return view('satuan.create');
    }

    // Menyimpan satuan baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
        ]);

        // Ambil data dari request
        $namaSatuan = $request->input('nama_satuan');
        $status = 1; // Misalnya status default adalah 1 (aktif)

        // Insert data ke tabel satuan
        $stmt = $this->pdo->prepare("INSERT INTO satuan (nama_satuan, status) VALUES (:nama_satuan, :status)");

        // Bind parameters
        $stmt->bindParam(':nama_satuan', $namaSatuan);
        $stmt->bindParam(':status', $status);

        // Execute the statement
        $stmt->execute();

        return redirect('/satuan')->with('success', 'Satuan created successfully');
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
        DB::table('satuan')
            ->where('idsatuan', $id)
            ->update(['status' => $validated['status']]);

        return redirect()->route('satuan.index')->with('success', 'Status berhasil diperbarui.');
    }

    // Menampilkan form edit satuan berdasarkan ID
    public function edit($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM satuan WHERE idsatuan = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $satuan = $stmt->fetch();

        if (!$satuan) {
            return redirect('/satuan')->with('error', 'Satuan not found');
        }

        return view('satuan.edit', compact('satuan'));
    }

    // Memperbarui data satuan di database
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
            // 'status' => 'required|boolean',
        ]);

        // Update data di tabel satuan
        $stmt = $this->pdo->prepare("UPDATE satuan SET nama_satuan = :nama_satuan, status = :status WHERE idsatuan = :id");

        // Retrieve values using input() method
        $nama_satuan = $request->input('nama_satuan'); // Use input() method
        $status = $request->input('status'); // Use input() method

        // Bind parameters
        $stmt->bindParam(':nama_satuan', $nama_satuan);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        // Execute the statement
        if ($stmt->execute()) {
            return redirect('/satuan')->with('success', 'Satuan updated successfully');
        }

        return redirect('/satuan')->with('error', 'Failed to update Satuan');
    }

    // Menghapus satuan dari database
    public function destroy($id)
    {
        // Delete from satuan table
        try {
            $stmt = $this->pdo->prepare("DELETE FROM satuan WHERE idsatuan = :id");
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return redirect('/satuan')->with('success', 'Satuan deleted successfully');
            }
        } catch (PDOException | Exception) {
            return redirect('/satuan')->with('error', "Failed to delete Satuan: {$e}");
        }

        return redirect('/satuan')->with('error', 'Failed to delete Satuan');
    }
}
