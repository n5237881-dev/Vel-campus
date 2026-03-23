<?php
error_reporting(0);
ini_set('display_errors', 0);
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

$id = intval($input['id'] ?? 0);
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Event ID required.']);
    exit;
}

$conn = getConnection();
$sets = [];

// Fields allowed to update
$allowed = ['title', 'venue', 'event_date', 'event_time', 'registration_deadline', 'max_participants', 'status', 'description', 'organizer', 'department', 'category'];

foreach ($allowed as $field) {
    if (isset($input[$field]) && $input[$field] !== '') {
        $val = $conn->real_escape_string(trim($input[$field]));
        $sets[] = "$field = '$val'";
    }
}

if (empty($sets)) {
    echo json_encode(['success' => false, 'message' => 'Nothing to update.']);
    exit;
}

$sql = "UPDATE events SET " . implode(', ', $sets) . " WHERE id = $id";

if ($conn->query($sql) && $conn->affected_rows >= 0) {
    echo json_encode(['success' => true, 'message' => 'Event updated successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $conn->error]);
}
$conn->close();
?>
