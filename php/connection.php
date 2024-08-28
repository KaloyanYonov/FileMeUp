<?php
function getDbInstance() {
    $host = 'localhost';
    $dbname = 'FileMeUp_db';
    $username = 'root'; // Modify with your actual username
    $password = ''; // Modify with your actual password
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
