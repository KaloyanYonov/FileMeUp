<?php
include 'db_connection.php';

$pdo = getDbInstance();

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (username, email, password_hash) VALUES (?, ?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $passwordHash]);
    header("Location: ../pages/login.html");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
