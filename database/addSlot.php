<?php

include "database.php";

$slotDate = $_POST["slotDate"];
$startTime = $_POST["startTime"];
$endTime = $_POST["endTime"];
$slotType = $_POST["slotType"];
$capacity = $_POST["capacity"];

$sql = "
INSERT INTO time_slot (slotDate, startTime, endTime, slotType, capacity)
VALUES (?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $slotDate, $startTime, $endTime, $slotType, $capacity);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

?>