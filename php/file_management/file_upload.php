<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if (!isset($_SESSION['userId'])) {
    exit('User not logged in');
}

$uploader_id = $_SESSION['userId'];
$user_uploads_dir = __DIR__ . '/../../uploads/' . $uploader_id;

$uploads_root_dir = __DIR__ . '/../../uploads/';
if (!is_writable($uploads_root_dir)) {
    exit("Uploads root directory is not writable");
}

if (!is_dir($user_uploads_dir)) {
    if (!mkdir($user_uploads_dir, 0777, true)) {
        exit("Failed to create user's upload directory");
    }
}

require_once __DIR__ . '/../auth/db_connection.php';
$conn = getDbInstance();

if (!$conn) {
    die("Database connection not established.");
}

foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
    if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) {
        switch ($_FILES['files']['error'][$key]) {
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
    if ($_FILES["files"]["size"][$key] > 52428800) {
        exit('File too large (max 50MB)');
    }

    // Use fileinfo to get the MIME type of the uploaded file
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $current_mime_type = $finfo->file($tmp_name);

    $mime_types = [
        "text/html", "text/plain",
        "image/jpeg", "image/png", "image/gif", "image/bmp", "image/svg+xml", "image/webp", "image/tiff", "image/heif", "image/avif",
        "audio/mp3", "audio/mpeg", "audio/ogg", "audio/vnd.wav",
        "video/mp4", "video/webm",
        "application/json", "application/xml", "application/pdf", "application/javascript", "application/zip", "application/vnd.ms-excel", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
    ];

    if (!in_array($current_mime_type, $mime_types)) {
        exit('File type not allowed');
    }

    // Set the file name and base name
    $pathinfo = pathinfo($_FILES["files"]["name"][$key]);
    $base = preg_replace("/[^\\w-]/", "_", $pathinfo["filename"]);

    $filename = $base . "." . $pathinfo["extension"];

    if (!is_writable($user_uploads_dir)) {
        exit("User's upload directory is not writable");
    }

    $destination = $user_uploads_dir . DIRECTORY_SEPARATOR . $filename;

    $i = 1;
    while (file_exists($destination)) {
        $filename = $base . "($i)." . $pathinfo["extension"];
        $destination = $user_uploads_dir . DIRECTORY_SEPARATOR . $filename;
        $i++;
    }

    if (!move_uploaded_file($tmp_name, $destination)) {
        exit("Can't move uploaded file");
    }

    $checksum = sha1_file($destination);

    $file_size = $_FILES["files"]["size"][$key];

    $thumbnail_dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "thumbnails";
    $mime_type_parts = explode('/', $current_mime_type);
    $thumbnail_path = $thumbnail_dir . DIRECTORY_SEPARATOR . $mime_type_parts[1] . ".png";

    if (!file_exists($thumbnail_path)) {
        $thumbnail_path = NULL;
    } else {
        $thumbnail_path = str_replace(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR, '', $thumbnail_path);
    }

    $is_public = 0;

    $stmt = $conn->prepare("INSERT INTO Files (uploader_id, file_name, file_path, mime_type, file_size, checksum, thumbnail, is_public) VALUES (:uploader_id, :file_name, :file_path, :mime_type, :file_size, :checksum, :thumbnail, :is_public)");
    $stmt->bindParam(':uploader_id', $uploader_id);
    $stmt->bindParam(':file_name', $filename);
    $stmt->bindParam(':file_path', $destination);
    $stmt->bindParam(':mime_type', $current_mime_type);
    $stmt->bindParam(':file_size', $file_size);
    $stmt->bindParam(':checksum', $checksum);
    $stmt->bindParam(':thumbnail', $thumbnail_path);
    $stmt->bindParam(':is_public', $is_public);

    if (!$stmt->execute()) {
        exit('Failed to save file information to the database');
    }
}

header('Location: ../../pages/my_files.php');
exit;
?>
