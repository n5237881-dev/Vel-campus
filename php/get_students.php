<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/db.php';

$conn = getConnection();
$result = $conn->query("SELECT id, name, roll_number, email, department, year_of_study, phone, created_at FROM students ORDER BY created_at DESC");

$data = [];
while ($row = $result->fetch_assoc()) $data[] = $row;

echo json_encode(['success' => true, 'data' => $data, 'count' => count($data)]);
$conn->close();
?>
