<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $pdo;

    public function __construct()
    {
        // Konfigurasi koneksi ke database menggunakan PDO
        $host = env('DB_HOST', '127.0.0.1');
        $db = env('DB_DATABASE', 'bismillahpbd');
        $user = env('DB_USERNAME', 'root');
        $pass = env('DB_PASSWORD', '');
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
            // Menggunakan abort untuk menghentikan eksekusi dan memberikan pesan error
            abort(500, 'Database connection failed: ' . $e->getMessage());
        }
    }

    // Menampilkan daftar user
    public function index()
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT u.*, r.nama_role
            FROM user u
            LEFT JOIN role r ON u.idrole = r.idrole
        ");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return view('user.index', compact('users'));
        } catch (PDOException $e) {
            return abort(500, 'Failed to fetch data: ' . $e->getMessage());
        }
    }


    // Menampilkan form create user
    public function create()
    {
        // Ambil semua data role
        $stmt = $this->pdo->query('SELECT idrole, nama_role FROM role');
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kirim data roles ke view
        return view('user.create', compact('roles'));
    }


    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        // Validasi input termasuk password
        $request->validate([
            'username' => 'required|string|max:255|unique:user,username',  // pastikan username unik
            'idrole' => 'required|integer',
            'password' => 'required|string|min:6',
        ]);

        // Hash password menggunakan Hash facade Laravel
        $password = Hash::make($request->input('password'));

        // Ambil nilai username dan idrole
        $username = $request->input('username');
        $idrole = $request->input('idrole');

        try {
            $hashedPassword = password_hash($request->password, PASSWORD_BCRYPT);
            // Menyimpan user baru menggunakan PDO (jika tetap ingin pakai PDO)
            $stmt = $this->pdo->prepare("INSERT INTO user (username, password, idrole) VALUES (:username, :password, :idrole)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':idrole', $idrole);
            $stmt->execute();

            return redirect('/user')->with('success', 'User created successfully');
        } catch (\PDOException $e) {
            return back()->withErrors(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    // Menampilkan form edit user berdasarkan ID
    public function edit($id)
    {
        // Ambil data user berdasarkan ID
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE iduser = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return redirect('/user')->with('error', 'User not found');
        }

        // Ambil semua data role
        $stmtRoles = $this->pdo->query('SELECT idrole, nama_role FROM role');
        $roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

        // Kirim data user dan roles ke view
        return view('user.edit', compact('user', 'roles'));
    }


    // Memperbarui data user di database
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'idrole' => 'required|integer',
        ]);

        // Mengupdate user dengan PDO
        $stmt = $this->pdo->prepare("UPDATE user SET username = :username, idrole = :idrole WHERE iduser = :id");
        $stmt->bindParam(':username', $request->username);
        $stmt->bindParam(':idrole', $request->idrole);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return redirect('/user')->with('success', 'User updated successfully');
    }

    // Menghapus user dari database
    public function destroy($id)
    {
        // Menghapus user dengan PDO
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE iduser = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return redirect('/user')->with('success', 'User deleted successfully');
    }
}
