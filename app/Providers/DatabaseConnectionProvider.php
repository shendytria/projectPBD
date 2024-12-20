<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PDO;
use PDOException;

class DatabaseConnectionProvider extends ServiceProvider
{
    private $pdo;

    public function __construct()
    {
        $host = '127.0.0.1';
        $database = 'bismillahpbd';
        $user = 'root';
        $password = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    public function queryMentah($query, $params = [])
    {
        if ($this->pdo === null) {
            throw new \Exception('Koneksi gagal.');
        }

        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll();
    }
}
