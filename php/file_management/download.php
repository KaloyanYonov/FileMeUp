<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.html');
    exit();
}

if (!isset($_GET['file'])) {
    die('No file specified.');
}

$file = basename($_GET['file']);

$uploader_id = $_SESSION['userId'];
$uploads_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $uploader_id;
$filePath = $uploads_dir . DIRECTORY_SEPARATOR . $file;

if (!file_exists($filePath)) {
    die('File not found.');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

ob_clean();
flush();

readfile($filePath);
exit;
