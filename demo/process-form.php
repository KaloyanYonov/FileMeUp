<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

    switch ($_FILES["image"]["error"]) {
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
if ($_FILES["image"]["size"] > 52428800) {
    exit('File too large (max 1MB)');
}

// Use fileinfo to get the MIME type of the uploaded file
$finfo = new finfo(FILEINFO_MIME_TYPE);
$current_mime_type = $finfo->file($_FILES["image"]["tmp_name"]);

$mime_types = ["image/gif", "image/png", "image/jpeg"];

if (!in_array($mime_type, $mime_types)) {
    exit("Invalid file type");
}

$pathinfo = pathinfo($_FILES["image"]["name"]);

$base = preg_replace("/[^\w-]/", "_", $pathinfo["filename"]);

// Set the filename with proper extension
$filename = $base . "." . $pathinfo["extension"];

// Uploads directory path
$uploads_dir = __DIR__ . DIRECTORY_SEPARATOR . "uploads";

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

// Without this file is temp only -.- ah php
if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
    exit("Can't move uploaded file");
}

echo "File uploaded successfully.";
