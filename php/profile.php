<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - FileMeUp</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header-box">
        <nav>
            <a href="index.html"><button class="mainPage">Main</button></a>
            <a href="logout.php"><button>Log Out</button></a>
        </nav>
    </header>
    <main>
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Your email: <?php echo htmlspecialchars($email); ?></p>
    </main>
</body>
</html>
