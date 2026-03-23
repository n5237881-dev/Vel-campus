<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/get_events.php — Returns all events as JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

$conn = getConnection();

$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$search   = isset($_GET['search'])   ? $conn->real_escape_string($_GET['search'])   : '';

$sql = "SELECT e.*, 
        (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) AS registered_count
        FROM events e WHERE 1=1";

if ($category && $category !== 'All') {
    $sql .= " AND e.category = '$category'";
}
if ($search) {
    $sql .= " AND (e.title LIKE '%$search%' OR e.description LIKE '%$search%' OR e.department LIKE '%$search%')";
}

$sql .= " ORDER BY e.event_date ASC";

$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    $row['spots_left'] = max(0, $row['max_participants'] - $row['registered_count']);
    $events[] = $row;
}

echo json_encode(['success' => true, 'data' => $events]);
$conn->close();
?>
