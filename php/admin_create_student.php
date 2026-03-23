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

$name   = trim($input['name']          ?? '');
$roll   = strtoupper(trim($input['roll_number']   ?? ''));
$email  = trim($input['email']         ?? '');
$dept   = trim($input['department']    ?? '');
$year   = trim($input['year_of_study'] ?? '');
$phone  = trim($input['phone']         ?? '');
$pass   = trim($input['password']      ?? $roll); // default = roll number

if (!$name || !$roll || !$email || !$dept || !$year || !$phone) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Phone must be 10 digits.']);
    exit;
}

$conn     = getConnection();
$name     = $conn->real_escape_string($name);
$roll     = $conn->real_escape_string($roll);
$email    = $conn->real_escape_string($email);
$dept     = $conn->real_escape_string($dept);
$year     = $conn->real_escape_string($year);
$phone    = $conn->real_escape_string($phone);
$pass_md5 = md5($pass);

$sql = "INSERT INTO students (name, roll_number, email, password, department, year_of_study, phone)
        VALUES ('$name','$roll','$email','$pass_md5','$dept','$year','$phone')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => "Student '$name' created.", 'id' => $conn->insert_id]);
} else {
    if ($conn->errno === 1062) {
        echo json_encode(['success' => false, 'message' => 'Roll number or email already exists.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed: ' . $conn->error]);
    }
}
$conn->close();
?>
