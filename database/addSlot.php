<?php

include "database.php";

header("Content-Type: application/json");

$slotDate = $_POST["slotDate"];
$startTime = $_POST["startTime"];
$endTime = $_POST["endTime"];
$slotType = $_POST["slotType"];
$capacity = $_POST["capacity"];

if ($slotDate < date("Y-m-d")) {
    echo json_encode([
        "success" => false,
        "message" => "Cannot create slots for past dates."
    ]);
    exit;
}

$checkSql = "
SELECT slotID 
FROM time_slot 
WHERE slotDate = ? 
AND startTime = ? 
AND endTime = ?
LIMIT 1
";

$stmt = $conn->prepare($checkSql);
$stmt->bind_param("sss", $slotDate, $startTime, $endTime);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "This slot already exists."
    ]);
    exit;
}

$sql = "
INSERT INTO time_slot 
(slotDate, startTime, endTime, slotType, capacity, slotStatus)
VALUES (?, ?, ?, ?, ?, 'Available')
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $slotDate, $startTime, $endTime, $slotType, $capacity);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
}

?>