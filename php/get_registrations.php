<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/get_registrations.php — Lists all registrations for an event
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
if (!$event_id) {
    echo json_encode(['success' => false, 'message' => 'Event ID required']);
    exit;
}

$conn = getConnection();

$sql = "SELECT r.*, e.title as event_title 
        FROM registrations r
        JOIN events e ON e.id = r.event_id
        WHERE r.event_id = $event_id
        ORDER BY r.registered_at DESC";

$result = $conn->query($sql);
$regs = [];
while ($row = $result->fetch_assoc()) {
    $regs[] = $row;
}

echo json_encode(['success' => true, 'data' => $regs, 'count' => count($regs)]);
$conn->close();
?>
