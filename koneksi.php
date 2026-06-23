<?php
// koneksi.php

class Database {
    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "DB_UAS_PBO_TRPL1B_PutriZizatunAprilia";
    private static $pdo = null;

    // Metode statis murni OOP untuk mengambil koneksi PDO
    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname, 
                    self::$username, 
                    self::$password
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Koneksi database gagal (OOP Error): " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>