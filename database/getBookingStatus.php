<?php
header("Content-Type: application/json");

include "bookingStatus.php";

echo json_encode([
    "success" => true,
    "bookingActive" => $bookingActive
]);
?>