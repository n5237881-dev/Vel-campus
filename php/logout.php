<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
session_start();
session_destroy();
echo json_encode(['success' => true]);
?>
