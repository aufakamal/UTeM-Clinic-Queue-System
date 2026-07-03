<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT
    'Appointment' AS recordType,
    a.appointmentID AS recordID,
    u.fullName AS patientName,
    CONCAT(t.slotDate, ' ', t.startTime) AS recordDateTime,
    a.appointmentStatus AS status,
    a.appointmentType AS extraInfo
FROM appointment a
JOIN user u ON a.userID = u.userID
JOIN time_slot t ON a.slotID = t.slotID

UNION ALL

SELECT
    'Queue' AS recordType,
    q.queueNo AS recordID,
    u.fullName AS patientName,
    COALESCE(att.checkInTime, CONCAT(t.slotDate, ' ', t.startTime)) AS recordDateTime,
    q.queueStatus AS status,
    a.appointmentType AS extraInfo
FROM queue q
JOIN attendance att ON q.attendanceID = att.attendanceID
JOIN appointment a ON att.appointmentID = a.appointmentID
JOIN user u ON a.userID = u.userID
JOIN time_slot t ON a.slotID = t.slotID

UNION ALL

SELECT
    'Consultation' AS recordType,
    c.consultationID AS recordID,
    patient.fullName AS patientName,
    c.startTime AS recordDateTime,
    q.queueStatus AS status,
    doctor.fullName AS extraInfo
FROM consultation c
JOIN queue q ON c.queueID = q.queueID
JOIN attendance att ON q.attendanceID = att.attendanceID
JOIN appointment a ON att.appointmentID = a.appointmentID
JOIN user patient ON a.userID = patient.userID
JOIN user doctor ON c.doctorUserID = doctor.userID

ORDER BY recordDateTime DESC
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