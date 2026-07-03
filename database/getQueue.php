<?php

include "database.php";

header("Content-Type: application/json");

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
    t.endTime,
    COALESCE(dp.roomNo, '-') AS roomNo
FROM queue q
LEFT JOIN attendance att ON q.attendanceID = att.attendanceID
LEFT JOIN appointment a ON att.appointmentID = a.appointmentID
LEFT JOIN user u ON a.userID = u.userID
LEFT JOIN time_slot t ON a.slotID = t.slotID
LEFT JOIN consultation c ON q.queueID = c.queueID
LEFT JOIN doctor_profile dp ON c.doctorUserID = dp.userID

WHERE t.slotDate = CURDATE()
AND q.queueStatus IN ('Waiting','Called')

ORDER BY q.queueNo ASC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

?>