<?php
require_once '../includes/db_connect.php';

// Accept GET or POST (Supporting both long and short names)
$soil_moisture = isset($_REQUEST['soil_moisture']) ? $_REQUEST['soil_moisture'] : (isset($_REQUEST['soil']) ? $_REQUEST['soil'] : null);
$temperature = isset($_REQUEST['temperature']) ? $_REQUEST['temperature'] : (isset($_REQUEST['temp']) ? $_REQUEST['temp'] : null);
$pump_status = isset($_REQUEST['pump_status']) ? $_REQUEST['pump_status'] : (isset($_REQUEST['pump']) ? $_REQUEST['pump'] : null);

if ($soil_moisture !== null && $temperature !== null && $pump_status !== null) {
    try {
        $stmt = $pdo->prepare("INSERT INTO sensor_data (soil_moisture, temperature, pump_status) VALUES (?, ?, ?)");
        $stmt->execute([$soil_moisture, $temperature, $pump_status]);
        
        // Also update hardware_controls table if it exists to keep in sync
        $pump_numeric = (strtoupper($pump_status) == 'ON') ? 1 : 0;
        $pdo->prepare("UPDATE hardware_controls SET status = ? WHERE device_name = 'Water Pump'")->execute([$pump_numeric]);

        echo json_encode(["status" => "success", "message" => "Data saved successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
}
?>
