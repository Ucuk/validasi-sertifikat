<?php
$host = 'localhost';
$dbname = 'validasi_sertifikat_admin';
$username = 'root'; // Ganti dengan username MySQL Anda
$password = ''; // Ganti dengan password MySQL Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi berhasil"; // Hapus atau komentari ini
} catch (PDOException $e) {
    // echo "Koneksi gagal: " . $e->getMessage(); // Hapus atau komentari ini
    error_log("Koneksi gagal: " . $e->getMessage()); // Gunakan logging untuk debugging
}
?>