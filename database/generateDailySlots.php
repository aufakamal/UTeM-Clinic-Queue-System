<?php

include "database.php";

header("Content-Type: application/json");

$today = date("Y-m-d");

$checkSql = "SELECT COUNT(*) AS total FROM time_slot WHERE slotDate = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ((int)$row["total"] > 0) {
    echo json_encode([
        "success" => true,
        "message" => "Today's slots already exist."
    ]);
    exit;
}

$slots = [
    ["08:00:00", "09:00:00", "Scheduled", 5],
    ["09:00:00", "10:00:00", "Scheduled", 5],
    ["10:00:00", "11:00:00", "Scheduled", 5],
    ["11:00:00", "12:00:00", "Scheduled", 5],
    ["14:00:00", "15:00:00", "Scheduled", 5],
    ["15:00:00", "16:00:00", "Scheduled", 5],
    ["16:00:00", "17:00:00", "Scheduled", 5],
    ["17:00:00", "18:00:00", "Scheduled", 5]
];

$insertSql = "
INSERT INTO time_slot
(slotDate, startTime, endTime, slotType, capacity, slotStatus)
VALUES (?, ?, ?, ?, ?, 'Available')
";

$stmt = $conn->prepare($insertSql);

foreach ($slots as $slot) {
    $startTime = $slot[0];
    $endTime = $slot[1];
    $slotType = $slot[2];
    $capacity = $slot[3];

    $stmt->bind_param("ssssi", $today, $startTime, $endTime, $slotType, $capacity);
    $stmt->execute();
}

echo json_encode([
    "success" => true,
    "message" => "8 slots generated for today."
]);

?>