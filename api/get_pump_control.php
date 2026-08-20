<?php
require_once '../includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT status FROM hardware_controls WHERE device_name = 'Water Pump'");
    $control = $stmt->fetch();
    echo $control['status']; // Returns 1 or 0
} catch (PDOException $e) {
    echo "0";
}
?>
