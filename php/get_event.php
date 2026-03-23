<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/get_event.php — Returns single event details
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid event ID']);
    exit;
}

$conn = getConnection();

$sql = "SELECT e.*, 
        (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS registered_count
        FROM events e WHERE e.id = $id LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Event not found']);
    exit;
}

$event = $result->fetch_assoc();
$event['spots_left'] = max(0, $event['max_participants'] - $event['registered_count']);

echo json_encode(['success' => true, 'data' => $event]);
$conn->close();
?>
