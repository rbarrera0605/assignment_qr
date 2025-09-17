<?php
include "lib/phpqrcode/qrlib.php";

// Directory to save test QR codes
$qrDir = "qrcodes/";
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0777, true);
}

// QR code content
$data = "Hello, this is a test QR code!";

// File path for the QR code image
$filePath = $qrDir . "test.png";

// Generate QR code
QRcode::png($data, $filePath, QR_ECLEVEL_L, 5);

// Display success message
echo "QR Code generated!<br>";
echo "<img src='$filePath' alt='QR Code'>";
?>
