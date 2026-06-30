<?php

include "database.php";

header("Content-Type: application/json");

if (!isset($_POST["appointmentID"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing appointment ID."
    ]);
    exit;
}

$appointmentID = $_POST["appointmentID"];

/* 1. Check if attendance already exists */
$checkSql = "
SELECT attendanceID, attendanceStatus
FROM attendance
WHERE appointmentID = ?
LIMIT 1
";

$stmt = $conn->prepare($checkSql);
$stmt->bind_param("i", $appointmentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $attendance = $result->fetch_assoc();
    $attendanceID = $attendance["attendanceID"];

    /* Update attendance to Arrived */
    $updateSql = "
    UPDATE attendance
    SET attendanceStatus = 'Arrived',
        checkInTime = NOW()
    WHERE attendanceID = ?
    ";

    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $attendanceID);
    $stmt->execute();

} else {

    /* Create attendance if it does not exist */
    $insertAttendanceSql = "
    INSERT INTO attendance
    (appointmentID, attendanceStatus, checkInTime)
    VALUES (?, 'Arrived', NOW())
    ";

    $stmt = $conn->prepare($insertAttendanceSql);
    $stmt->bind_param("i", $appointmentID);
    $stmt->execute();

    $attendanceID = $conn->insert_id;
}

/* 2. Check if queue already exists for this attendance */
$queueCheckSql = "
SELECT queueID
FROM queue
WHERE attendanceID = ?
LIMIT 1
";

$stmt = $conn->prepare($queueCheckSql);
$stmt->bind_param("i", $attendanceID);
$stmt->execute();
$queueResult = $stmt->get_result();

if ($queueResult->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "This patient is already in the queue."
    ]);
    exit;
}

/* 3. Generate next queue number */
$queueNoSql = "
SELECT COALESCE(MAX(queueNo), 0) + 1 AS nextQueueNo
FROM queue
";

$result = $conn->query($queueNoSql);
$row = $result->fetch_assoc();

$queueNo = (int)$row["nextQueueNo"];

/* 4. Assign room automatically */
$roomNo = "Room " . (($queueNo - 1) % 3 + 1);

/* 5. Insert queue record */
/* 4. Insert queue record without room assignment */
$insertQueueSql = "
INSERT INTO queue
(attendanceID, queueNo, queueStatus)
VALUES (?, ?, 'Waiting')
";

$stmt = $conn->prepare($insertQueueSql);
$stmt->bind_param("ii", $attendanceID, $queueNo);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "queueNo" => $queueNo,
        "roomNo" => "-"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
}

?>