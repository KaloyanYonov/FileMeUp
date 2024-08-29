<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
    switch ($_FILES["file"]["error"]) {
        case UPLOAD_ERR_PARTIAL:
            exit('File only partially uploaded');
        case UPLOAD_ERR_NO_FILE:
            exit('No file was uploaded');
        case UPLOAD_ERR_EXTENSION:
            exit('File upload stopped by a PHP extension');
        case UPLOAD_ERR_FORM_SIZE:
            exit('File exceeds MAX_FILE_SIZE in the HTML form');
        case UPLOAD_ERR_INI_SIZE:
            exit('File exceeds upload_max_filesize in php.ini');
        case UPLOAD_ERR_NO_TMP_DIR:
            exit('Temporary folder not found');
        case UPLOAD_ERR_CANT_WRITE:
            exit('Failed to write file');
        default:
            exit('Unknown upload error');
    }
}

// Reject uploaded file larger than 50MB
if ($_FILES["file"]["size"] > 52428800) {
    exit('File too large (max 50MB)');
}

// Use fileinfo to get the MIME type of the uploaded file
$finfo = new finfo(FILEINFO_MIME_TYPE);
$current_mime_type = $finfo->file($_FILES["file"]["tmp_name"]);

// Define allowed MIME types
$mime_types = [
    "text/html", "text/plain",
    "image/jpeg", "image/png", "image/gif", "image/bmp", "image/svg+xml", "image/webp", "image/tiff", "image/heif", "image/avif",
    "audio/mp3, audio/mpeg", "audio/ogg", "audio/vnd.wav",
    "video/mp4", "video/webm",
    "application/json", "application/xml", "application/pdf", "application/javascript", "application/zip", "application/vnd.ms-excel", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
];

// Check if the file type is allowed, otherwise mark it as "unknown"
if (!in_array($current_mime_type, $mime_types)) {
    $current_mime_type = "unknown";
}

// Set the file name and base name
$pathinfo = pathinfo($_FILES["file"]["name"]);
$base = preg_replace("/[^\w-]/", "_", $pathinfo["filename"]);

// Set the filename with proper extension
$filename = $base . "." . $pathinfo["extension"];

// Modify the path to point to the main 'uploads' directory in the project root
$uploads_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";

if (!is_writable($uploads_dir)) {
    exit("Uploads directory is not writable");
}

// Destination path
$destination = $uploads_dir . DIRECTORY_SEPARATOR . $filename;

// Add suffix if file exists
$i = 1;
while (file_exists($destination)) {
    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = $uploads_dir . DIRECTORY_SEPARATOR . $filename;
    $i++;
}

// Move uploaded file to the main uploads directory
if (!move_uploaded_file($_FILES["file"]["tmp_name"], $destination)) {
    exit("Can't move uploaded file");
}

// After successful upload, redirect to the preview page with the file name
header("Location: ../php/file_management/file_preview.php?filename=" . urlencode($filename));
exit();
