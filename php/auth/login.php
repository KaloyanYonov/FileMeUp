<?php
session_start();
include 'db_connection.php';

$pdo = getDbInstance();

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT user_id, username, password_hash FROM Users WHERE username = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['userId'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    header("Location: ../../pages/index.php");
} else {
    echo "Invalid username or password.";
}
?>
