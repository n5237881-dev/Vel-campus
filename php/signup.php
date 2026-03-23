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

$name         = trim($input['name'] ?? '');
$roll         = strtoupper(trim($input['roll_number'] ?? ''));
$email        = trim($input['email'] ?? '');
$password     = trim($input['password'] ?? '');
$department   = trim($input['department'] ?? '');
$year         = trim($input['year_of_study'] ?? '');
$phone        = trim($input['phone'] ?? '');

if (!$name || !$roll || !$email || !$password || !$department || !$year || !$phone) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
    exit;
}

$conn = getConnection();
$name       = $conn->real_escape_string($name);
$roll       = $conn->real_escape_string($roll);
$email      = $conn->real_escape_string($email);
$pass_md5   = md5($password);
$department = $conn->real_escape_string($department);
$year       = $conn->real_escape_string($year);
$phone      = $conn->real_escape_string($phone);

$sql = "INSERT INTO students (name, roll_number, email, password, department, year_of_study, phone)
        VALUES ('$name','$roll','$email','$pass_md5','$department','$year','$phone')";

if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Account created! You can now log in.']);
} else {
    if ($conn->errno === 1062) {
        echo json_encode(['success' => false, 'message' => 'Roll number or email already registered.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $conn->error]);
    }
}
$conn->close();
?>
