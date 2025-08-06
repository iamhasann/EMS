<?php
session_start();
require_once('../../config/database.php');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$userID = $_SESSION['id']; // ใช้จาก session เดิมที่คุณสร้างใน login

$data = json_decode(file_get_contents("php://input"), true);
$lat = $data['latitude'] ?? null;
$lon = $data['longitude'] ?? null;

if ($lat && $lon) {
    $stmt = $link->prepare("UPDATE users_tb SET latitude = ?, longitude = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ddi", $lat, $lon, $userID);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'DB update failed']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid coordinates']);
}
?>
