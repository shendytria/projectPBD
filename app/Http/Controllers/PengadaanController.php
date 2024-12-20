<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengadaanController extends Controller
{
    private $pdo;

    public function __construct()
    {
        // Database connection configuration
        $host = '127.0.0.1';
        $db = 'bismillahpbd'; // Replace with your actual database name
        $user = 'root'; // Replace with your database user
        $pass = ''; // Replace with your database password
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    // Menampilkan daftar pengadaan
    public function index()
    {
        try {
            // Mengambil data pengadaan dengan total dari function
            $pengadaanList = DB::table('pengadaan as p')
                ->join('user as u', 'p.user_iduser', '=', 'u.iduser')
                ->join('vendor as v', 'p.vendor_idvendor', '=', 'v.idvendor')
                ->select(
                    'p.idpengadaan',
                    'u.username',
                    'v.nama_vendor',
                    'p.subtotal_nilai',
                    'p.status',
                    DB::raw('fn_calculate_total_pengadaan(p.idpengadaan) as total_calculated')
                )
                ->orderBy('p.idpengadaan', 'ASC')
                ->get();

            // Modifikasi perhitungan dengan hasil dari function
            foreach ($pengadaanList as $pengadaan) {
                $pengadaan->subtotal = $pengadaan->total_calculated;
                $pengadaan->ppn = $pengadaan->total_calculated * 0.11;
                $pengadaan->total_nilai = $pengadaan->total_calculated + $pengadaan->ppn;
            }

            return view('pengadaan.index', compact('pengadaanList'));
        } catch (PDOException $e) {
            return abort(500, 'Failed to fetch data: ' . $e->getMessage());
        }
    }

    // Menampilkan form untuk menambahkan pengadaan baru
    public function create()
    {
        $stmtUsers = $this->pdo->prepare("SELECT iduser, username FROM user");
        $stmtUsers->execute();
        $users = $stmtUsers->fetchAll();

        $stmtVendors = $this->pdo->prepare("SELECT idvendor, nama_vendor FROM vendor");
        $stmtVendors->execute();
        $vendors = $stmtVendors->fetchAll();

        $stmtBarangs = $this->pdo->prepare("SELECT idbarang, nama, harga FROM barang");
        $stmtBarangs->execute();
        $barangs = $stmtBarangs->fetchAll();

        // Set initial status
        $initialStatus = 1;

        return view('pengadaan.create', compact('users', 'vendors', 'barangs', 'initialStatus'));
    }

    // Menambahkan pengadaan baru
    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'user_iduser' => 'required|integer',
                'vendor_idvendor' => 'required|integer',
                'items' => 'required|array',
                'items.*.id_barang' => 'required|integer',
                'items.*.jumlah' => 'required|integer',
            ]);

            $subtotal_nilai = 0;
            $items_detail = [];

            foreach ($request->input('items') as $item) {
                $barang = DB::table('barang')
                    ->where('idbarang', $item['id_barang'])
                    ->first();

                if (!$barang) {
                    throw new \Exception('Barang dengan ID ' . $item['id_barang'] . ' tidak ditemukan.');
                }

                $sub_total = $item['jumlah'] * $barang->harga;
                $subtotal_nilai += $sub_total;

                $items_detail[] = [
                    'idbarang' => $item['id_barang'],
                    'harga_satuan' => $barang->harga,
                    'jumlah' => $item['jumlah'],
                    'sub_total' => $sub_total
                ];
            }

            $ppn = $subtotal_nilai * 0.11;
            $total_nilai = $subtotal_nilai + $ppn;

            DB::beginTransaction();

            foreach ($items_detail as $detail) {
                DB::select('CALL sp_insert_pengadaan(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                    $request->input('user_iduser'),
                    $request->input('status', '1'),
                    $request->input('vendor_idvendor'),
                    $subtotal_nilai,
                    $ppn,
                    $total_nilai,
                    $detail['harga_satuan'],
                    $detail['jumlah'],
                    $detail['sub_total'],
                    $detail['idbarang']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pengadaan.index')
                ->with('success', 'Pengadaan berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in pengadaan store: ' . $e->getMessage());

            return redirect()
                ->route('pengadaan.index')
                ->with('error', 'Gagal menambahkan pengadaan: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $pengadaan = DB::select("
            SELECT p.*, fn_calculate_total_pengadaan(p.idpengadaan) as total_calculated
            FROM pengadaan p
            WHERE p.idpengadaan = ?
        ", [$id])[0];

            if (!$pengadaan) {
                return redirect()->route('pengadaan.index')
                    ->with('error', 'Pengadaan tidak ditemukan.');
            }

            $pengadaan->ppn = $pengadaan->total_calculated * 0.11;
            $pengadaan->total_nilai = $pengadaan->total_calculated + $pengadaan->ppn;

            $details = DB::table('detail_pengadaan as dp')
                ->join('barang as b', 'dp.idbarang', '=', 'b.idbarang')
                ->where('dp.idpengadaan', $id)
                ->select('dp.*', 'b.nama')
                ->get();

            return view('pengadaan.show', compact('pengadaan', 'details'));
        } catch (PDOException $e) {
            return redirect()->route('pengadaan.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah_terima' => 'required|array',
                'jumlah_terima.*' => 'required|integer|min:1'
            ]);

            $this->pdo->beginTransaction();

            // Ambil data pengadaan berdasarkan ID
            $stmt = $this->pdo->prepare("SELECT * FROM pengadaan WHERE idpengadaan = ?");
            $stmt->execute([$id]);
            $pengadaan = $stmt->fetch();

            if (!$pengadaan) {
                $this->pdo->rollBack();
                return redirect()->route('pengadaan.index')
                    ->with('error', 'Pengadaan tidak ditemukan.');
            }

            // Ambil detail barang terkait
            $detailsStmt = $this->pdo->prepare("SELECT dp.*, b.nama FROM detail_pengadaan dp
                                           JOIN barang b ON dp.idbarang = b.idbarang
                                           WHERE dp.idpengadaan = ?");
            $detailsStmt->execute([$id]);
            $details = $detailsStmt->fetchAll();

            // Simpan penerimaan dengan status dan iduser
            $insertStmt = $this->pdo->prepare("INSERT INTO penerimaan
                                      (idpengadaan, iduser, status, created_at)
                                      VALUES (?, ?, 2, NOW())");
            $insertStmt->execute([
                $id,
                $pengadaan['user_iduser'] // Menggunakan iduser dari pengadaan
            ]);

            // Ambil ID penerimaan yang baru
            $idPenerimaan = $this->pdo->lastInsertId();

            // Simpan detail penerimaan
            $jumlahTerima = $request->input('jumlah_terima');
            foreach ($details as $index => $detail) {
                // Hitung subtotal baru berdasarkan jumlah yang diterima
                $subtotal = $detail['harga_satuan'] * $jumlahTerima[$index];

                $insertDetailStmt = $this->pdo->prepare("INSERT INTO detail_penerimaan
                                                (idpenerimaan, idbarang, jumlah_terima,
                                                harga_satuan_terima, sub_total_terima)
                                                VALUES (?, ?, ?, ?, ?)");
                $insertDetailStmt->execute([
                    $idPenerimaan,
                    $detail['idbarang'],
                    $jumlahTerima[$index],
                    $detail['harga_satuan'],
                    $subtotal
                ]);
            }

            // Hitung total jumlah yang sudah diterima untuk barang ini
            foreach ($details as $detail) {
            $totalTerimaStmt = $this->pdo->prepare("SELECT SUM(jumlah_terima) as total_diterima
                                                   FROM detail_penerimaan
                                                   WHERE idbarang = ? AND idpenerimaan IN (
                                                       SELECT idpenerimaan FROM penerimaan WHERE idpengadaan = ?
                                                   )");
            $totalTerimaStmt->execute([$detail['idbarang'], $id]);
            $totalDiterima = $totalTerimaStmt->fetchColumn();

            // Periksa apakah jumlah yang diterima sudah sama dengan jumlah pengadaan
            if ($totalDiterima >= $detail['jumlah']) {
                $statusPengadaan = 3; // Selesai
            } else {
                $statusPengadaan = 2; // Diproses
            }

            // Update status pengadaan
            $updateStmt = $this->pdo->prepare("UPDATE pengadaan SET status = ?
                                         WHERE idpengadaan = ?");
            $updateStmt->execute([$statusPengadaan, $id]);
        }
            $this->pdo->commit();

            return redirect()->route('pengadaan.index')
                ->with('success', 'Penerimaan berhasil disimpan dan status pengadaan telah diubah.');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return redirect()->route('pengadaan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $this->pdo->beginTransaction();

            // Periksa apakah pengadaan ada
            $stmt = $this->pdo->prepare("SELECT * FROM pengadaan WHERE idpengadaan = ?");
            $stmt->execute([$id]);
            $pengadaan = $stmt->fetch();

            if (!$pengadaan) {
                $this->pdo->rollBack();
                return redirect()->route('pengadaan.index')
                    ->with('error', 'Pengadaan tidak ditemukan.');
            }

            // Perbarui status pengadaan menjadi dibatalkan (4)
            $updateStmt = $this->pdo->prepare("UPDATE pengadaan SET status = 4 WHERE idpengadaan = ?");
            $updateStmt->execute([$id]);

            $this->pdo->commit();

            return redirect()->route('pengadaan.index')
                ->with('success', 'Pengadaan berhasil dibatalkan.');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return redirect()->route('pengadaan.index')
                ->with('error', 'Terjadi kesalahan pada database: ' . $e->getMessage());
        }
    }
}
