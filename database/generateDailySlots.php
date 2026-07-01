<?php

include "database.php";

header("Content-Type: application/json");

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

$stmtInsert = $conn->prepare($insertSql);

$checkSql = "
SELECT COUNT(*) AS total
FROM time_slot
WHERE slotDate = ?
";

$stmtCheck = $conn->prepare($checkSql);

$generated = 0;

for($i = 0; $i < 30; $i++) {

    $slotDate = date("Y-m-d", strtotime("+$i day"));

    $stmtCheck->bind_param("s", $slotDate);
    $stmtCheck->execute();

    $result = $stmtCheck->get_result();
    $row = $result->fetch_assoc();

    if($row["total"] > 0){
        continue;
    }

    foreach($slots as $slot){

        $startTime = $slot[0];
        $endTime = $slot[1];
        $slotType = $slot[2];
        $capacity = $slot[3];

        $stmtInsert->bind_param(
            "ssssi",
            $slotDate,
            $startTime,
            $endTime,
            $slotType,
            $capacity
        );

        $stmtInsert->execute();
    }

    $generated++;

}

echo json_encode([
    "success" => true,
    "message" => "$generated day(s) of slots generated."
]);

?>