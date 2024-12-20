<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;

class ReturController extends Controller
{
    protected $pdo;

    public function __construct()
    {
        // Mengatur koneksi PDO dari konfigurasi .env
        $this->pdo = new PDO(
            "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
    }

    public function index()
    {
        try {
            // Query untuk data retur
            $returQuery = '
                SELECT r.*, dr.jumlah, dr.alasan
                FROM retur AS r
                JOIN detail_retur AS dr ON r.idretur = dr.idretur
                ORDER BY r.created_at DESC
            ';
            $returStmt = $this->pdo->query($returQuery);
            $retur = $returStmt->fetchAll(PDO::FETCH_ASSOC);

            // Query untuk data user
            $usersQuery = 'SELECT iduser, username FROM user';
            $usersStmt = $this->pdo->query($usersQuery);
            $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

            // Data penerimaan (jika perlu)
            $penerimaanQuery = 'SELECT * FROM penerimaan';
            $penerimaanStmt = $this->pdo->query($penerimaanQuery);
            $penerimaan = $penerimaanStmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            // Menangani error koneksi atau query
            return response()->json(['error' => 'Database error: ' . $e->getMessage()]);
        }

        // Mengembalikan data ke view
        return view('retur.index', [
            'retur' => $retur,
            'penerimaan' => $penerimaan,
            'users' => $users,
        ]);
    }
}
