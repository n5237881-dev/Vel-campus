<?php
error_reporting(0);
ini_set('display_errors', 0);
// php/export_csv.php — Download registrations as CSV
require_once '../includes/db.php';

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
if (!$event_id) {
    http_response_code(400);
    die('Event ID required');
}

$conn = getConnection();

// Get event title for filename
$evRes = $conn->query("SELECT title FROM events WHERE id=$event_id LIMIT 1");
if ($evRes->num_rows === 0) { die('Event not found'); }
$evTitle = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $evRes->fetch_assoc()['title']);

$sql = "SELECT r.id, r.student_name, r.roll_number, r.student_email, r.department, r.year_of_study, r.phone, r.registered_at
        FROM registrations r
        WHERE r.event_id = $event_id
        ORDER BY r.registered_at ASC";

$result = $conn->query($sql);

// Force CSV download
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="registrations_' . $evTitle . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');
fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel

// Header row
fputcsv($out, ['#', 'Student Name', 'Roll Number', 'Email', 'Department', 'Year', 'Phone', 'Registered At']);

$i = 1;
while ($row = $result->fetch_assoc()) {
    fputcsv($out, [
        $i++,
        $row['student_name'],
        $row['roll_number'],
        $row['student_email'],
        $row['department'],
        $row['year_of_study'],
        $row['phone'],
        $row['registered_at']
    ]);
}

fclose($out);
$conn->close();
exit;
?>
