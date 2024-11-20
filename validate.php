<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hash = $_POST['hash'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM sertifikat WHERE hash = :hash");
    $stmt->execute(['hash' => $hash]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode([
                'status' => 'valid', 
                'message' => 'Sertifikat valid! Terverifikasi dalam database kami.'
            ]);
        } else {
            echo json_encode([
                'status' => 'invalid', 
                'message' => 'Sertifikat tidak valid! Hash tidak ditemukan dalam database.'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
?>