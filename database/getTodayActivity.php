<?php

include "database.php";

header("Content-Type: application/json");

$data = [];

/* Arrived today */
$result = $conn->query("
SELECT COUNT(*) AS total
FROM attendance
WHERE attendanceStatus = 'Arrived'
AND DATE(checkInTime) = CURDATE()
");
$data["arrivedToday"] = $result->fetch_assoc()["total"];

/* No Show today */
$result = $conn->query("
SELECT COUNT(*) AS total
FROM appointment a
JOIN time_slot t ON a.slotID = t.slotID
WHERE a.appointmentStatus = 'No Show'
AND t.slotDate = CURDATE()
");

$data["noShowToday"] = $result->fetch_assoc()["total"];

/* Booked today */
$result = $conn->query("
SELECT COUNT(*) AS total
FROM appointment a
JOIN time_slot t ON a.slotID = t.slotID
WHERE a.appointmentStatus = 'Booked'
AND t.slotDate = CURDATE()
");
$data["bookedToday"] = $result->fetch_assoc()["total"];

/* Cancelled today */
$result = $conn->query("
SELECT COUNT(*) AS total
FROM appointment a
JOIN time_slot t ON a.slotID = t.slotID
WHERE a.appointmentStatus = 'Cancelled'
AND t.slotDate = CURDATE()
");
$data["cancelledToday"] = $result->fetch_assoc()["total"];

echo json_encode($data);

?>