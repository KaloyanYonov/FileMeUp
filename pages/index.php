<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileMeUp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header class="header-box">
        <nav>
            <div class="nav-left">
                <a href="index.php"><button class="mainPage">Home</button></a>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            </div>
            <div class="nav-right">
                <a href="my_files.php"><button>My Files</button></a>
                <a href="../php/auth/logout.php"><button>Logout</button></a>
            </div>
        </nav>
    </header>

    <main>
        <h2 class="welcome-text">Get started. Upload your files!</h2>
        <form action="../php/file_management/file_upload.php" method="post" enctype="multipart/form-data">
            <label for="files">Choose files:</label>
            <input type="file" id="files" name="files[]" multiple required>
            <button type="submit">Upload</button>
        </form>
    </main>

    <footer>
        <div class="footer-left">
            <img src="../images/logo.png" alt="Company Logo" class="footer-logo">
        </div>
        <div class="footer-center">
            <p class="copyright">&copy; 2024 FileMeUp. All rights reserved.</p>
        </div>
        <div class="footer-right">
            <a href="https://github.com/KaloyanYonov">Kaloyan Yonov</a>
            <a href="https://github.com/Backpulver">Yoan Hristov</a>
        </div>
    </footer>
</body>

</html>
