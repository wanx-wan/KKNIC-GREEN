<?php
// Railway MySQL variables are provided at runtime, not committed to GitHub.
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$name = getenv('MYSQLDATABASE') ?: 'kknicdb';
$port = (int) (getenv('MYSQLPORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $pass, $name, $port);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    http_response_code(503);
    exit('Database temporarily unavailable');
}
