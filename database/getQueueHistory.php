<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT
    q.queueID,
    q.queueNo,
    q.queueStatus,
    att.attendanceStatus,
    att.checkInTime,
    a.userID,
    u.fullName AS patientName,
    a.appointmentType
FROM queue q
JOIN attendance att ON q.attendanceID = att.attendanceID
JOIN appointment a ON att.appointmentID = a.appointmentID
JOIN user u ON a.userID = u.userID
WHERE q.queueStatus = 'Completed'
ORDER BY att.checkInTime DESC
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

?>