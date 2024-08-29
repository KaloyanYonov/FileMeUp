<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if (!isset($_SESSION['userId'])) {
    exit('User not logged in');
}

require_once '../php/auth/db_connection.php';

$file_id = $_POST['file_id'];
$shared_with = $_POST['shared_with'];
$permissions = $_POST['permissions'];

$stmt = $conn->prepare("SELECT file_id FROM Files WHERE file_id = ? AND uploader_id = ?");
$stmt->bind_param("ii", $file_id, $_SESSION['userId']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    exit("You do not have permission to share this file");
}

$stmt->close();

$stmt = $conn->prepare("INSERT INTO File_Shares (file_id, shared_with, permissions) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $file_id, $shared_with, $permissions);

if ($stmt->execute()) {
    echo "File shared successfully!";
} else {
    exit("Failed to share the file");
}

$stmt->close();
$conn->close();
