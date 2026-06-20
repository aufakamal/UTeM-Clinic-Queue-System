<?php

include "database.php";

$slotID = $_POST["slotID"];
$slotDate = $_POST["slotDate"];
$startTime = $_POST["startTime"];
$endTime = $_POST["endTime"];
$slotType = $_POST["slotType"];
$capacity = $_POST["capacity"];

$sql = "
UPDATE time_slot
SET slotDate = ?,
    startTime = ?,
    endTime = ?,
    slotType = ?,
    capacity = ?
WHERE slotID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $slotDate, $startTime, $endTime, $slotType, $capacity, $slotID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

?>