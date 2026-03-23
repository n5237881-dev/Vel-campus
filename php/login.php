<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST required']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$roll     = strtolower(trim($input['roll_number'] ?? ''));
$password = trim($input['password'] ?? '');

if (!$roll || !$password) {
    echo json_encode(['success' => false, 'message' => 'Roll number and password are required.']);
    exit;
}

// --- Admin check ---
if ($roll === strtolower(ADMIN_ROLL) && $password === ADMIN_PASS) {
    $_SESSION['admin'] = true;
    echo json_encode([
        'success'  => true,
        'is_admin' => true,
        'message'  => 'Admin login successful!',
        'redirect' => 'admin.php'
    ]);
    exit;
}

// --- Student check ---
$conn     = getConnection();
$roll_esc = $conn->real_escape_string(strtoupper($roll));
$pass_md5 = md5($password);

$result = $conn->query("SELECT id, name, roll_number, email, department, year_of_study, phone FROM students WHERE roll_number = '$roll_esc' AND password = '$pass_md5' LIMIT 1");

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid roll number or password.']);
    $conn->close();
    exit;
}

$student = $result->fetch_assoc();
$_SESSION['student'] = $student;

echo json_encode([
    'success'  => true,
    'is_admin' => false,
    'message'  => 'Login successful!',
    'student'  => $student,
    'redirect' => 'index.php'
]);
$conn->close();
?>
