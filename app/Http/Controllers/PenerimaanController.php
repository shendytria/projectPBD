<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
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

    // Menampilkan daftar penerimaan
    public function index()
    {
        // Mengambil data penerimaan, user, pengadaan, detail_pengadaan, dan detail_penerimaan
        $stmt = $this->pdo->prepare("
        SELECT
            p.idpenerimaan,
            p.idpengadaan,
            dp.idbarang,  -- Menambahkan idbarang di SELECT
            SUM(dp.jumlah_terima) AS total_terima,
            p.created_at,
            p.status,
            u.username,
            b.nama AS nama_barang,
            dpj.jumlah AS total_jumlah,  -- Jumlah dari detail_pengadaan
            ks.total_keluar,
            ks.total_masuk,
            ks.total_stock
        FROM penerimaan p
        JOIN user u ON p.iduser = u.iduser
        LEFT JOIN detail_penerimaan dp ON p.idpenerimaan = dp.idpenerimaan
        LEFT JOIN barang b ON dp.idbarang = b.idbarang
        LEFT JOIN (
            SELECT
                ks.idbarang,
                SUM(ks.keluar) AS total_keluar,
                SUM(ks.masuk) AS total_masuk,
                SUM(ks.stock) AS total_stock
            FROM kartu_stok ks
            GROUP BY ks.idbarang
            ) ks ON b.idbarang = ks.idbarang
        LEFT JOIN detail_pengadaan dpj ON p.idpengadaan = dpj.idpengadaan AND dp.idbarang = dpj.idbarang  -- Menambahkan pengadaan dan barang dalam pengadaan
        JOIN pengadaan pg ON p.idpengadaan = pg.idpengadaan
        WHERE pg.status IN ('2', '3')
        GROUP BY
            p.idpenerimaan,
            p.idpengadaan,
            dp.idbarang,
            p.created_at,
            p.status,
            u.username,
            b.nama,
            dpj.jumlah,
            ks.total_keluar,
            ks.total_masuk,
            ks.total_stock
        ORDER BY p.idpenerimaan ASC, dp.idbarang ASC;
    ");

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $penerimaanList = [];

        foreach ($rows as $row) {
            $idpenerimaan = $row['idpenerimaan'];

            // Menambahkan penerimaan ke dalam list, jika belum ada
            $penerimaanList[] = [
                'idpenerimaan' => $row['idpenerimaan'],
                'username' => $row['username'],
                'idpengadaan' => $row['idpengadaan'],
                'idbarang' => $row['idbarang'],  // Menyertakan idbarang
                'nama_barang' => $row['nama_barang'],
                'jumlah_pengadaan' => $row['total_jumlah'],  // Jumlah pengadaan dari detail_pengadaan
                'jumlah_terima' => $row['total_terima'],  // Jumlah penerimaan
                'created_at' => $row['created_at'],
                'keluar' => $row['total_keluar'],  // Jumlah keluar dari kartu_stok
                'masuk' => $row['total_masuk'],  // Jumlah masuk dari kartu_stok
                'stock' => $row['total_stock'],  // Stok total barang
                'status' => $row['status'],
            ];
        }

        return view('penerimaan.index', ['penerimaanList' => $penerimaanList]);
    }

    // Menampilkan data penerimaan berdasarkan ID
    public function show($id)
    {
        try {
            // Mengambil data penerimaan berdasarkan idpenerimaan
            $stmt = $this->pdo->prepare("SELECT * FROM penerimaan WHERE idpenerimaan = :idpenerimaan");
            $stmt->bindParam(':idpenerimaan', $id);
            $stmt->execute();

            // Mendapatkan data penerimaan
            $penerimaan = $stmt->fetch();

            if (!$penerimaan) {
                return redirect()->route('penerimaan.index')->with('error', 'Penerimaan tidak ditemukan.');
            }

            // Mengambil detail barang yang terkait dengan penerimaan
            $detailsStmt = $this->pdo->prepare("
            SELECT dp.*, b.nama
            FROM detail_penerimaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            WHERE dp.idpenerimaan = :idpenerimaan
        ");
            $detailsStmt->bindParam(':idpenerimaan', $id);
            $detailsStmt->execute();

            // Mendapatkan detail barang
            $details = $detailsStmt->fetchAll();

            return view('penerimaan.show', compact('penerimaan', 'details'));
        } catch (PDOException $e) {
            return redirect()->route('penerimaan.index')->with('error', 'Terjadi kesalahan saat mengambil data.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->pdo->beginTransaction();

            // Ambil informasi user yang terkait dengan penerimaan
            $getUserStmt = $this->pdo->prepare("
            SELECT iduser
            FROM penerimaan
            WHERE idpenerimaan = :idpenerimaan
        ");
            $getUserStmt->execute([':idpenerimaan' => $id]);
            $penerimaan = $getUserStmt->fetch();

            if (!$penerimaan) {
                throw new \Exception('Data penerimaan tidak ditemukan.');
            }

            // Cek apakah sudah ada retur untuk penerimaan ini
            $checkReturStmt = $this->pdo->prepare("
            SELECT idretur
            FROM retur
            WHERE idpenerimaan = :idpenerimaan
        ");
            $checkReturStmt->execute([':idpenerimaan' => $id]);
            $existingRetur = $checkReturStmt->fetch();

            if ($existingRetur) {
                throw new \Exception('Penerimaan ini sudah memiliki retur sebelumnya. Pengembalian hanya dapat dilakukan sekali.');
            }

            $returData = $request->input('retur');

            // Insert data retur
            $insertReturStmt = $this->pdo->prepare("
            INSERT INTO retur (idpenerimaan, iduser, created_at)
            VALUES (:idpenerimaan, :iduser, NOW())
        ");
            $insertReturStmt->execute([
                ':idpenerimaan' => $id,
                ':iduser' => $penerimaan['iduser'],
            ]);

            $idretur = $this->pdo->lastInsertId();

            foreach ($returData as $item) {
                $idBarang = $item['idbarang'];
                $jumlahRetur = $item['jumlah'];
                $alasanRetur = $item['alasan'];

                if ($jumlahRetur <= 0) {
                    throw new \Exception('Jumlah retur tidak boleh nol atau negatif.');
                }

                // Cek data detail penerimaan
                $checkJumlahStmt = $this->pdo->prepare("
                SELECT iddetail_penerimaan, jumlah_terima
                FROM detail_penerimaan
                WHERE idpenerimaan = :idpenerimaan AND idbarang = :idbarang
            ");
                $checkJumlahStmt->execute([
                    ':idpenerimaan' => $id,
                    ':idbarang' => $idBarang,
                ]);
                $detailPenerimaan = $checkJumlahStmt->fetch();

                if (!$detailPenerimaan) {
                    throw new \Exception("Detail penerimaan untuk barang dengan ID '$idBarang' tidak ditemukan.");
                }

                if ($detailPenerimaan['jumlah_terima'] < $jumlahRetur) {
                    throw new \Exception("Jumlah retur melebihi jumlah yang diterima untuk barang dengan ID '$idBarang'.");
                }

                // Cek stok barang terakhir
                $checkStockStmt = $this->pdo->prepare("
                SELECT stock
                FROM kartu_stok
                WHERE idbarang = :idbarang
                ORDER BY created_at DESC
                LIMIT 1
            ");
                $checkStockStmt->execute([':idbarang' => $idBarang]);
                $stokBarang = $checkStockStmt->fetch();

                if (!$stokBarang) {
                    throw new \Exception("Stok barang dengan ID '$idBarang' tidak ditemukan.");
                }

                // Validasi jumlah retur dan stok yang ada
                if ($stokBarang['stock'] < $jumlahRetur) {
                    throw new \Exception("Jumlah retur melebihi stok yang ada untuk barang dengan ID '$idBarang'.");
                }

                // Ambil iddetail_penerimaan
                $iddetail_penerimaan = $detailPenerimaan['iddetail_penerimaan'];

                // Update jumlah terima pada detail penerimaan
                $updateDetailStmt = $this->pdo->prepare("
                UPDATE detail_penerimaan
                SET jumlah_terima = jumlah_terima - :jumlahRetur
                WHERE idpenerimaan = :idpenerimaan AND idbarang = :idbarang
            ");
                $updateDetailStmt->execute([
                    ':jumlahRetur' => $jumlahRetur,
                    ':idpenerimaan' => $id,
                    ':idbarang' => $idBarang,
                ]);

                // Insert data detail retur
                $insertDetailReturStmt = $this->pdo->prepare("
                INSERT INTO detail_retur (idretur, jumlah, alasan, iddetail_penerimaan)
                VALUES (:idretur, :jumlah, :alasan, :iddetail_penerimaan)
            ");
                $insertDetailReturStmt->execute([
                    ':idretur' => $idretur,
                    ':jumlah' => $jumlahRetur,
                    ':alasan' => $alasanRetur,
                    ':iddetail_penerimaan' => $iddetail_penerimaan,
                ]);
            }

            $this->pdo->commit();

            return redirect()->route('penerimaan.index')->with('success', 'Data retur berhasil diperbarui.');
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return redirect()->route('penerimaan.index')->with('error', 'Terjadi kesalahan pada database: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return redirect()->route('penerimaan.index')->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }
}
