<?php
include "db.php";

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$id = intval($_GET['id']);

// Delete QR code image
$result = $conn->query("SELECT qr_code FROM assignments WHERE id=$id");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (!empty($row['qr_code']) && file_exists($row['qr_code'])) {
        unlink($row['qr_code']);
    }
}

// Delete submissions
$conn->query("DELETE FROM submissions WHERE assignment_id=$id");

// Delete assignment
$conn->query("DELETE FROM assignments WHERE id=$id");

header("Location: index.php?msg=deleted");
exit;
