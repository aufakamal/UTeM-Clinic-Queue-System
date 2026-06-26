<?php

include "database.php";

$appointmentID = $_POST["appointmentID"];

$sql1 = "
UPDATE appointment
SET appointmentStatus = 'No Show'
WHERE appointmentID = ?
";

$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $appointmentID);
$appointmentUpdated = $stmt1->execute();

$sql2 = "
UPDATE attendance
SET attendanceStatus = 'No Show',
    checkInTime = NULL
WHERE appointmentID = ?
AND attendanceStatus = 'Pending'
LIMIT 1
";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $appointmentID);
$attendanceUpdated = $stmt2->execute();

if ($appointmentUpdated && $attendanceUpdated) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

?>