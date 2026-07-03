<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT 
    a.appointmentID,
    a.userID,
    u.fullName,
    a.slotID,
    t.slotDate,
    t.startTime,
    t.endTime,
    t.slotType,
    a.appointmentType,
    a.appointmentStatus,
    a.appointmentFor,
    a.dependantName,
    a.dependantRelationship,
    att.attendanceStatus,
    att.checkInTime
FROM appointment a
LEFT JOIN user u ON a.userID = u.userID
LEFT JOIN time_slot t ON a.slotID = t.slotID
LEFT JOIN attendance att ON att.attendanceID = (
    SELECT MAX(att2.attendanceID)
    FROM attendance att2
    WHERE att2.appointmentID = a.appointmentID
)
WHERE a.appointmentStatus = 'Booked'
ORDER BY t.slotDate ASC, t.startTime ASC
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

?>