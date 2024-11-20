<?php
include 'configmin.php'; // Pastikan file config berisi koneksi database

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil hash dari permintaan POST
    $hash = $_POST['hash'] ?? '';

    if (empty($hash)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Hash tidak valid'
        ]);
        exit;
    }

    try {
        // Cek apakah hash sudah ada di database
        $stmt = $pdo->prepare("SELECT * FROM sertifikat WHERE hash = :hash");
        $stmt->execute(['hash' => $hash]);
        $existingCertificate = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCertificate) {
            // Jika sertifikat sudah ada, validasi berhasil
            echo json_encode([
                'status' => 'valid', 
                'message' => 'Sertifikat sudah terdaftar',
                'action' => 'existing'
            ]);
        } else {
            // Jika sertifikat belum ada, simpan ke database
            $insertStmt = $pdo->prepare("INSERT INTO sertifikat (hash) VALUES (:hash)");
            $insertStmt->execute(['hash' => $hash]);

            echo json_encode([
                'status' => 'valid', 
                'message' => 'Sertifikat berhasil divalidasi dan disimpan',
                'action' => 'new'
            ]);
        }
    } catch (PDOException $e) {
        // Tangani error database
        echo json_encode([
            'status' => 'error', 
            'message' => 'Kesalahan database: ' . $e->getMessage()
        ]);
    }
} else {
    // Metode request tidak valid
    echo json_encode([
        'status' => 'error', 
        'message' => 'Metode request tidak valid'
    ]);
}
?>