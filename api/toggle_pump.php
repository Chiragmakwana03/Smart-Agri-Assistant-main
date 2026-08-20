<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if (isset($_GET['status'])) {
    $status = (int)$_GET['status'];
    try {
        $stmt = $pdo->prepare("UPDATE hardware_controls SET status = ?, mode = 'manual' WHERE device_name = 'Water Pump'");
        $stmt->execute([$status]);
        header("Location: ../monitoring.php?success=1");
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
