<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT
    c.consultationID,
    q.queueNo,
    patient.fullName AS patientName,
    doctor.fullName AS doctorName,
    c.startTime,
    c.endTime,
    q.queueStatus
FROM consultation c
JOIN queue q ON c.queueID = q.queueID
JOIN attendance att ON q.attendanceID = att.attendanceID
JOIN appointment ap ON att.appointmentID = ap.appointmentID
JOIN user patient ON ap.userID = patient.userID
JOIN user doctor ON c.doctorUserID = doctor.userID
WHERE q.queueStatus = 'Completed'
AND c.endTime <= NOW()
ORDER BY c.startTime DESC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

?>