<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;

class RoleController extends Controller
{
    private $pdo;

    public function __construct()
    {
        // Konfigurasi koneksi ke database menggunakan PDO
        $host = '127.0.0.1';
        $db = 'bismillahpbd';
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

    // Menampilkan semua role
    public function index()
    {
        $stmt = $this->pdo->query('SELECT * FROM role');
        $roles = $stmt->fetchAll();

        return view('role.index', compact('roles'));
    }

    // Menampilkan form untuk menambah role baru
    public function create()
    {
        return view('role.create');
    }

    // Menyimpan role baru ke database
    public function store(Request $request)
    {
        $namaRole = $request->input('nama_role');

        // Validasi input jika diperlukan
        if (empty($namaRole)) {
            return redirect()->route('roles.create')->with('error', 'Nama role tidak boleh kosong.');
        }

        // Persiapan dan eksekusi query
        $stmt = $this->pdo->prepare('INSERT INTO role (nama_role) VALUES (:nama_role)');
        if ($stmt->execute([':nama_role' => $namaRole])) {
            return redirect()->route('roles.index')->with('success', 'Role added successfully.');
        } else {
            return redirect()->route('roles.create')->with('error', 'Failed to add role.');
        }
    }


    // Menampilkan form untuk mengedit role
    public function edit($idrole)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM role WHERE idrole = :idrole');
        $stmt->execute([':idrole' => $idrole]);
        $role = $stmt->fetch();

        return view('role.edit', compact('role'));
    }

    // Memperbarui role yang ada
    public function update(Request $request, $idrole)
    {
        // Validasi data
        $request->validate([
            'nama_role' => 'required|string|max:255',
        ]);

        $namaRole = $request->input('nama_role');

        try {
            $stmt = $this->pdo->prepare('UPDATE role SET nama_role = :nama_role WHERE idrole = :idrole');
            $stmt->execute([':nama_role' => $namaRole, ':idrole' => $idrole]);

            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (PDOException $e) {
            return redirect()->route('roles.index')->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    // Menghapus role
    public function destroy($idrole)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM role WHERE idrole = :idrole');
            $stmt->execute([':idrole' => $idrole]);

            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (PDOException $e) {
            return redirect()->route('roles.index')->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}
