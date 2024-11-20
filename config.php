<?php
$host = '185.199.109.153';
$dbname = 'validasi_sertifikat_admin';
$username = 'root';
$password = ''; // Sesuaikan dengan password MySQL Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
