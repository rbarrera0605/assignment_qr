<?php
// If environment variables exist (on Render / InfinityFree), use them
// Otherwise, use defaults for local XAMPP
$host = getenv('DB_HOST') ?: 'localhost';        // localhost for XAMPP
$user = getenv('DB_USERNAME') ?: 'root';        // default XAMPP user
$pass = getenv('DB_PASSWORD') ?: '';            // default XAMPP password
$db   = getenv('DB_NAME') ?: 'assignment_qr';   // local database name in XAMPP

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
