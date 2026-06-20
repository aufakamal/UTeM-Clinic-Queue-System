<?php

include "database.php";

$appointmentID = $_POST["appointmentID"];

/* 1. Update attendance */
$sql = "
UPDATE attendance
SET attendanceStatus = 'Arrived',
    checkInTime = NOW()
WHERE appointmentID = ?
AND attendanceStatus = 'Pending'
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointmentID);
$stmt->execute();

/* 2. Get latest attendanceID for this appointment */
$getAttendance = "
SELECT attendanceID 
FROM attendance
WHERE appointmentID = ?
ORDER BY attendanceID DESC
LIMIT 1
";

$stmt2 = $conn->prepare($getAttendance);
$stmt2->bind_param("i", $appointmentID);
$stmt2->execute();

$result = $stmt2->get_result();
$attendance = $result->fetch_assoc();

if (!$attendance) {
    echo json_encode(["success" => false, "message" => "Attendance not found"]);
    exit;
}

$attendanceID = $attendance["attendanceID"];

/* 3. Check if queue already exists */
$checkQueue = "
SELECT queueID
FROM queue
WHERE attendanceID = ?
LIMIT 1
";

$stmt3 = $conn->prepare($checkQueue);
$stmt3->bind_param("i", $attendanceID);
$stmt3->execute();

$queueResult = $stmt3->get_result();

if ($queueResult->num_rows > 0) {
    echo json_encode(["success" => true, "message" => "Already in queue"]);
    exit;
}

/* 4. Get next queue number */
$getNextQueue = "
SELECT IFNULL(MAX(queueNo), 0) + 1 AS nextQueueNo
FROM queue
";

$nextResult = $conn->query($getNextQueue);
$nextRow = $nextResult->fetch_assoc();

$nextQueueNo = $nextRow["nextQueueNo"];

/* 5. Insert queue record */
$insertQueue = "
INSERT INTO queue (attendanceID, queueNo, queueStatus)
VALUES (?, ?, 'Waiting')
";

$stmt4 = $conn->prepare($insertQueue);
$stmt4->bind_param("ii", $attendanceID, $nextQueueNo);

if ($stmt4->execute()) {
    echo json_encode(["success" => true, "queueNo" => $nextQueueNo]);
} else {
    echo json_encode(["success" => false, "message" => "Queue insert failed"]);
}

?>