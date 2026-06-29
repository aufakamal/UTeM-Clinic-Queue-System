<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT
    ts.slotID,
    ts.slotDate,
    ts.startTime,
    ts.endTime,
    ts.slotType,
    ts.capacity,
    ts.slotStatus,
    COUNT(a.appointmentID) AS appointmentCount
FROM time_slot ts
LEFT JOIN appointment a ON ts.slotID = a.slotID
GROUP BY
    ts.slotID,
    ts.slotDate,
    ts.startTime,
    ts.endTime,
    ts.slotType,
    ts.capacity,
    ts.slotStatus
ORDER BY ts.slotDate, ts.startTime
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $row["appointmentCount"] = (int)$row["appointmentCount"];
    $data[] = $row;
}

echo json_encode($data);

?>