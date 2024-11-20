<?php
include 'configmin.php'; // Pastikan ini berisi informasi koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil hash dari permintaan POST
    $hash = $_POST['hash'];

    // Cek apakah hash sudah ada di database
    $stmt = $pdo->prepare("SELECT * FROM sertifikat WHERE hash = :hash");
    $stmt->execute(['hash' => $hash]);
    $existingCertificate = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCertificate) {
        echo json_encode(['status' => 'error', 'message' => 'Sertifikat sudah ada.']);
    } else {
        // Simpan hash ke database
        $stmt = $pdo->prepare("INSERT INTO sertifikat (hash) VALUES (:hash)");
        $stmt->execute(['hash' => $hash]);

        echo json_encode(['status' => 'success', 'message' => 'Sertifikat berhasil disimpan.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid.']);
}
?>