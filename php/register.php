<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/register.php — Handles event registration POST
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Read JSON body or fallback to POST fields
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$event_id      = intval($input['event_id'] ?? 0);
$student_name  = trim($input['student_name'] ?? '');
$student_email = trim($input['student_email'] ?? '');
$roll_number   = strtoupper(trim($input['roll_number'] ?? ''));
$department    = trim($input['department'] ?? '');
$year_of_study = trim($input['year_of_study'] ?? '');
$phone         = trim($input['phone'] ?? '');

// --- Validation ---
if (!$event_id || !$student_name || !$student_email || !$roll_number || !$department || !$year_of_study || !$phone) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Phone must be 10 digits.']);
    exit;
}

$conn = getConnection();

// Check event exists and is upcoming
$evRes = $conn->query("SELECT id, status, max_participants, registration_deadline, 
    (SELECT COUNT(*) FROM registrations r WHERE r.event_id = events.id) as reg_count
    FROM events WHERE id = $event_id LIMIT 1");

if ($evRes->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Event not found.']);
    exit;
}

$ev = $evRes->fetch_assoc();

if ($ev['status'] !== 'Upcoming') {
    echo json_encode(['success' => false, 'message' => 'Registrations are closed for this event.']);
    exit;
}

if (date('Y-m-d') > $ev['registration_deadline']) {
    echo json_encode(['success' => false, 'message' => 'Registration deadline has passed.']);
    exit;
}

if ($ev['reg_count'] >= $ev['max_participants']) {
    echo json_encode(['success' => false, 'message' => 'This event is fully booked.']);
    exit;
}

// Escape inputs
$student_name  = $conn->real_escape_string($student_name);
$student_email = $conn->real_escape_string($student_email);
$roll_number   = $conn->real_escape_string($roll_number);
$department    = $conn->real_escape_string($department);
$year_of_study = $conn->real_escape_string($year_of_study);
$phone         = $conn->real_escape_string($phone);

$sql = "INSERT INTO registrations (event_id, student_name, student_email, roll_number, department, year_of_study, phone)
        VALUES ($event_id, '$student_name', '$student_email', '$roll_number', '$department', '$year_of_study', '$phone')";

if ($conn->query($sql)) {
    $reg_id = $conn->insert_id;
    echo json_encode([
        'success'    => true,
        'message'    => 'Registration successful! 🎉',
        'reg_id'     => $reg_id,
        'roll_number'=> $roll_number
    ]);
} else {
    if ($conn->errno === 1062) {
        echo json_encode(['success' => false, 'message' => 'You are already registered for this event.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }
}

$conn->close();
?>
