<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT
    a.appointmentID,
    u.fullName AS patientName,
    a.userID,
    a.appointmentType,
    a.appointmentStatus,
    t.slotDate,
    t.startTime,
    t.endTime
FROM appointment a
JOIN user u ON a.userID = u.userID
JOIN time_slot t ON a.slotID = t.slotID
WHERE a.appointmentStatus IN ('Completed', 'Cancelled', 'No Show')
ORDER BY t.slotDate DESC, t.startTime DESC
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

?>