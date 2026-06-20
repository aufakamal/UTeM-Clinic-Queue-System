<?php

include "database.php";

$sql = "
SELECT
    q.queueID,
    q.queueNo,
    q.queueStatus,
    att.attendanceStatus,
    a.appointmentID,
    a.userID,
    u.fullName,
    a.appointmentType,
    t.slotDate,
    t.startTime,
    t.endTime
FROM queue q
LEFT JOIN attendance att ON q.attendanceID = att.attendanceID
LEFT JOIN appointment a ON att.appointmentID = a.appointmentID
LEFT JOIN user u ON a.userID = u.userID
LEFT JOIN time_slot t ON a.slotID = t.slotID
ORDER BY q.queueNo ASC
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

?>