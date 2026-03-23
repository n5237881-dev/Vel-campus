<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/my_registrations.php — Lookup by roll number
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

$roll = isset($_GET['roll']) ? strtoupper(trim($_GET['roll'])) : '';
if (!$roll) {
    echo json_encode(['success' => false, 'message' => 'Roll number required']);
    exit;
}

$conn = getConnection();
$roll = $conn->real_escape_string($roll);

$sql = "SELECT r.*, e.title as event_title, e.event_date, e.venue, e.category
        FROM registrations r
        JOIN events e ON e.id = r.event_id
        WHERE r.roll_number = '$roll'
        ORDER BY r.registered_at DESC";

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) $data[] = $row;

echo json_encode(['success' => true, 'data' => $data]);
$conn->close();
?>
