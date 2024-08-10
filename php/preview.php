<?php
$file = isset($_GET['file']) ? $_GET['file'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

$supportedFileTypes = [
    'image' => ['jpg', 'jpeg', 'png', 'gif'],
    'audio' => ['mp3', 'wav'],
    'video' => ['mp4', 'webm'],
    'document' => ['pdf']
];

function getFileTypeCategory($fileType, $supportedFileTypes)
{
    foreach ($supportedFileTypes as $category => $types) {
        if (in_array($fileType, $types)) {
            return $category;
        }
    }
    return null;
}

$fileCategory = $type ? getFileTypeCategory($type, $supportedFileTypes) : null;

if (!$file || !$fileCategory) {
    echo "Invalid file.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filemeup - Preview File</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header class="header-box">
        <nav>
            <a href="login.html"><button>Log In</button></a>
            <a href="signup.html"><button>Sign Up</button></a>
        </nav>
    </header>
    <h1>File Preview</h1>
    <?php if ($fileCategory == 'image'): ?>
        <img src="<?= htmlspecialchars($file) ?>" alt="Image" style="max-width: 100%;">
    <?php elseif ($fileCategory == 'audio'): ?>
        <audio controls>
            <source src="<?= htmlspecialchars($file) ?>" type="audio/<?= htmlspecialchars($type) ?>">
            Your browser does not support the audio element.
        </audio>
    <?php elseif ($fileCategory == 'video'): ?>
        <video controls style="max-width: 100%;">
            <source src="<?= htmlspecialchars($file) ?>" type="video/<?= htmlspecialchars($type) ?>">
            Your browser does not support the video element.
        </video>
    <?php elseif ($fileCategory == 'document'): ?>
        <embed src="<?= htmlspecialchars($file) ?>" type="application/pdf" width="100%" height="600px" />
    <?php else: ?>
        <p>Unsupported file type.</p>
    <?php endif; ?>
    <footer>
        <div class="footer-left">
            <img src="../images/logo.png" alt="Company Logo" class="footer-logo">
        </div>
        <div class="footer-center">
            <p>&copy; 2024 FileMeUp. All rights reserved.</p>
        </div>
        <div class="footer-right">
            <div>
                <a href="https://github.com/KaloyanYonov">Kaloyan Yonov</a>
                <br>
                <a href="https://github.com/Backpulver">Yoan Hristov</a>
            </div>
        </div>
    </footer>
</body>

</html>