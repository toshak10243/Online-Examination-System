<?php
// dbConnection.php
date_default_timezone_set('Asia/Kolkata');

$DB_HOST = '127.0.0.1';
$DB_NAME = 'online_exam';
$DB_USER = 'root';
$DB_PASS = '1234';
$DB_CHAR = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHAR}";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // friendly error for dev
    echo "DB Connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
