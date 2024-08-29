<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.html');
    exit();
}

// Check if a file is specified
if (!isset($_GET['file'])) {
    die('No file specified.');
}

// Sanitize the file name to prevent directory traversal attacks
$file = basename($_GET['file']);

// Define the uploader ID and the uploads directory
$uploader_id = $_SESSION['userId'];
$uploads_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $uploader_id;
$filePath = $uploads_dir . DIRECTORY_SEPARATOR . $file;

// Ensure the file exists
if (!file_exists($filePath)) {
    die('File not found.');
}

// Set the correct headers for file download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// Clear output buffer to avoid corrupting the download
ob_clean();
flush();

// Read the file and output it to the browser
readfile($filePath);
exit;
