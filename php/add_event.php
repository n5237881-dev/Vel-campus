<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/add_event.php — Add new event (POST)
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

$required = ['title','description','category','venue','event_date','event_time','max_participants','registration_deadline','organizer','department'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => "Field '$field' is required."]);
        exit;
    }
}

$valid_cats = ['Technical','Cultural','Sports','Workshop','Seminar','Hackathon'];
if (!in_array($input['category'], $valid_cats)) {
    echo json_encode(['success' => false, 'message' => 'Invalid category.']);
    exit;
}

$max = intval($input['max_participants']);
if ($max < 1 || $max > 10000) {
    echo json_encode(['success' => false, 'message' => 'Max participants must be between 1 and 10000.']);
    exit;
}

if ($input['registration_deadline'] > $input['event_date']) {
    echo json_encode(['success' => false, 'message' => 'Registration deadline cannot be after event date.']);
    exit;
}

$conn = getConnection();

$title       = $conn->real_escape_string(trim($input['title']));
$description = $conn->real_escape_string(trim($input['description']));
$category    = $conn->real_escape_string($input['category']);
$venue       = $conn->real_escape_string(trim($input['venue']));
$event_date  = $conn->real_escape_string($input['event_date']);
$event_time  = $conn->real_escape_string($input['event_time']);
$deadline    = $conn->real_escape_string($input['registration_deadline']);
$organizer   = $conn->real_escape_string(trim($input['organizer']));
$department  = $conn->real_escape_string(trim($input['department']));

$sql = "INSERT INTO events (title, description, category, venue, event_date, event_time, max_participants, registration_deadline, organizer, department, status)
        VALUES ('$title','$description','$category','$venue','$event_date','$event_time',$max,'$deadline','$organizer','$department','Upcoming')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Event created successfully!', 'id' => $conn->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create event: ' . $conn->error]);
}

$conn->close();
?>
