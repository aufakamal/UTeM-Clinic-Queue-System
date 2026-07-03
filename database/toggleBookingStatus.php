<?php
header("Content-Type: application/json");

$statusFile = "bookingStatus.php";

include $statusFile;

$newStatus = !$bookingActive;

$content = "<?php\n\$bookingActive = " . ($newStatus ? "true" : "false") . ";\n?>";

if (file_put_contents($statusFile, $content)) {
    echo json_encode([
        "success" => true,
        "bookingActive" => $newStatus
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update booking status."
    ]);
}
?>