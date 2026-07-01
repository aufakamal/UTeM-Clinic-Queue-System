<?php

include "database.php";

$data = [];

/* Total appointments */
/* Total appointments today */
$result = $conn->query("
SELECT COUNT(*) AS total
FROM appointment a
JOIN time_slot t
ON a.slotID = t.slotID
WHERE t.slotDate = CURDATE()
");

$data["totalAppointments"] = $result->fetch_assoc()["total"];

/* Waiting patients */
$result = $conn->query("
SELECT COUNT(*) AS total 
FROM queue 
WHERE queueStatus = 'Waiting'
");
$data["waitingPatients"] = $result->fetch_assoc()["total"];

/* Active consultations */
$result = $conn->query("
SELECT COUNT(*) AS total 
FROM queue 
WHERE queueStatus = 'Called'
");
$data["activeConsultations"] = $result->fetch_assoc()["total"];

/* Completed consultations today */
$result = $conn->query("
SELECT COUNT(*) AS total
FROM consultation c
JOIN queue q ON c.queueID = q.queueID
WHERE q.queueStatus = 'Completed'
AND DATE(c.endTime) = CURDATE()
");
$data["completedToday"] = $result->fetch_assoc()["total"];

echo json_encode($data);

?>