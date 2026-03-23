<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/update_event_status.php — Change event status
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST required']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$id     = intval($input['id'] ?? 0);
$status = trim($input['status'] ?? '');

$valid = ['Upcoming','Ongoing','Completed','Cancelled'];
if (!$id || !in_array($status, $valid)) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID or status.']);
    exit;
}

$conn = getConnection();
$sql = "UPDATE events SET status='$status' WHERE id=$id";

if ($conn->query($sql) && $conn->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => "Event marked as $status."]);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed or no change.']);
}

$conn->close();
?>
