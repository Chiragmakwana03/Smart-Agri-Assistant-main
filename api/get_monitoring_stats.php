<?php
require_once '../includes/db_connect.php';

try {
    // Fetch latest reading
    $stmt = $pdo->query("SELECT * FROM sensor_data ORDER BY created_at DESC LIMIT 1");
    $latest = $stmt->fetch();

    // Fetch last 10 readings for table and chart
    $stmt = $pdo->query("SELECT * FROM sensor_data ORDER BY created_at DESC LIMIT 10");
    $history = $stmt->fetchAll();

    echo json_encode([
        "status" => "success",
        "latest" => $latest,
        "history" => array_reverse($history) // Reverse to show chronological order in chart
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
