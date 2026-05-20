<?php
session_start();
header('Content-Type: application/json');

if ($action === 'delete_cancelled') {
    $stmt = $conn->prepare("
        DELETE FROM bookings 
        WHERE status = 'Cancelled'
    ");
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();

    echo json_encode([
        'success' => true,
        'deleted' => $deleted,
        'message' => "$deleted cancelled booking(s) permanently removed."
    ]);
    exit;
}

include 'connect.php';

// Delete bookings older than 30 days that are Completed or Cancelled
$stmt = $conn->prepare("
    DELETE FROM bookings 
    WHERE status IN ('Completed', 'Cancelled')
    AND updated_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
");
$stmt->execute();
$deleted = $stmt->affected_rows;
$stmt->close();

echo json_encode([
    'success' => true,
    'deleted' => $deleted,
    'message' => "$deleted old booking(s) removed."
]);
?>