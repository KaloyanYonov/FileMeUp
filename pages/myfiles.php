<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.html'); // Redirect if not logged in
    exit();
}

$uploads_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
$files = scandir($uploads_dir);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Files</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header class="header-box">
        <nav>
            <div class="nav-left">
                <a href="index.php"><button class="mainPage">Main</button></a>
            </div>
            <div class="nav-right">
                <a href="myfiles.php"><button>My Files</button></a>
                <a href="logout.php"><button>Logout</button></a>
            </div>
        </nav>
    </header>
    <main>
        <h1>Your Uploaded Files</h1>
        <?php if (count($files) > 2): // More than '.' and '..' ?>
            <table>
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Size</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($files as $file) {
                        if ($file === "." || $file === "..") continue;
                        $filePath = $uploads_dir . DIRECTORY_SEPARATOR . $file;
                        $fileSize = filesize($filePath);
                        $fileDate = date("F d Y H:i:s.", filemtime($filePath));
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($file) ?></td>
                            <td><?= number_format($fileSize / 1024, 2) ?> KB</td>
                            <td><?= $fileDate ?></td>
                            <td><a href="../php/file_preview.php?filename=<?= urlencode($file) ?>">Preview</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No files uploaded yet.</p>
        <?php endif; ?>
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