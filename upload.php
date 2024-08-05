<?php
$supportedFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'wav', 'mp4', 'webm', 'pdf'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (in_array($fileType, $supportedFileTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            header("Location: preview.php?file=" . urlencode($target_file) . "&type=" . urlencode($fileType));
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File type not supported.";
    }
} else {
    echo "Invalid request.";
}
?>
