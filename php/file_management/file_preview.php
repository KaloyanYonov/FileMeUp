<?php

if (!isset($_GET['filename'])) {
    exit('No file specified');
}

$filename = basename($_GET['filename']);

$uploads_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";

$file_path = $uploads_dir . DIRECTORY_SEPARATOR . $filename;

if (!file_exists($file_path)) {
    exit('File does not exist');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->file($file_path);

echo "<h1>File Preview: $filename</h1>";

if (strpos($mime_type, 'image') === 0) {
    echo "<img src='../uploads/" . htmlspecialchars($filename) . "' alt='File Preview' />";
} elseif (strpos($mime_type, 'audio') === 0) {
    echo "<audio controls><source src='../uploads/" . htmlspecialchars($filename) . "' type='$mime_type'>Your browser does not support the audio element.</audio>";
} elseif (strpos($mime_type, 'video') === 0) {
    echo "<video controls><source src='../uploads/" . htmlspecialchars($filename) . "' type='$mime_type'>Your browser does not support the video tag.</video>";
} else {
    echo "<p>File type: $mime_type</p>";
    echo "<p><a href='../uploads/" . htmlspecialchars($filename) . "' download>Download File</a></p>";
}
