<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "DB_UAS_PBO_TRPL1B_PutriZizatunAprilia";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>