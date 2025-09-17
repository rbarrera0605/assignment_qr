<?php
$host = "localhost";
$user = "root";   // change if needed
$pass = "";       // change if needed
$db   = "assignment_qr";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
