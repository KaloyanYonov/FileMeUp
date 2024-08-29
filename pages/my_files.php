<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.html');
    exit();
}

$uploader_id = $_SESSION['userId'];
$uploads_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $uploader_id;

if (!is_dir($uploads_dir)) {
    $files = [];
} else {
    $files = scandir($uploads_dir);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Files</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header class="header-box">
        <nav>
            <div class="nav-left">
                <a href="index.php"><button class="mainPage">Home</button></a>
            </div>
            <div class="nav-right">
                <a href="my_files.php"><button>My Files</button></a>
                <a href="../php/auth/logout.php"><button>Logout</button></a>
            </div>
        </nav>
    </header>
    
    <main>
        <h1>Your Uploaded Files</h1>
        <?php if (count($files) > 2): ?>
            <table>
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>File Name</th>
                        <th>Uploader ID</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Checksum</th>
                        <th>Public</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($files as $file) {
                        if ($file === "." || $file === "..")
                            continue;

                        $filePath = $uploads_dir . DIRECTORY_SEPARATOR . $file;
                        $fileUrl = "../uploads/" . urlencode($uploader_id . DIRECTORY_SEPARATOR . $file);
                        $fileSize = filesize($filePath);
                        $fileDate = date("F d Y H:i:s.", filemtime($filePath));

                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime_type = $finfo->file($filePath);
                        $subtype = explode('/', $mime_type)[1];
                        
                        $thumbnailPath = "../images/thumbnails/" . $subtype . ".png";
                        if (!file_exists($thumbnailPath)) {
                            $thumbnailPath = "../images/thumbnails/default.png";
                        }

                        $checksum = sha1_file($filePath);

                        $is_public = true;
                        ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($thumbnailPath) ?>" alt="Thumbnail" style="width: 50px; height: 50px;"></td>
                            <td><?= htmlspecialchars($file) ?></td>
                            <td><?= htmlspecialchars($uploader_id) ?></td>
                            <td><?= htmlspecialchars($subtype) ?></td>
                            <td><?= number_format($fileSize / 1024, 2) ?> KB</td>
                            <td><?= htmlspecialchars($checksum) ?></td>
                            <td><?= $is_public ? 'Yes' : 'No' ?></td>
                            <td><?= $fileDate ?></td>
                            <td><a href="../php/file_management/file_preview.php?filename=<?= urlencode($file) ?>">Preview</a></td>
                            <td><a href="../file_management/download.php?file=<?= urlencode($file) ?>"><button>Download</button></a></td>
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
