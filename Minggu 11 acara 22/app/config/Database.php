<?php
// Mode aplikasi: 'development' atau 'production'.
// Pada 'development' JSON dicetak rapi (pretty print) dan detail error ditampilkan.
// Pada 'production' JSON dicetak ringkas dan detail error disembunyikan.
if (!defined('APP_ENV')) {
    define('APP_ENV', 'development');
}

class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $host    = 'localhost';
            $db      = 'si-akademik1'; // database dari acara sebelumnya
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, 'root', '', $options);
            } catch (PDOException $e) {
                $errors = APP_ENV === 'development' ? ['database' => [$e->getMessage()]] : [];
                Response::json(500, false, 'Koneksi database gagal', null, $errors);
            }
        }
        return self::$connection;
    }
}
