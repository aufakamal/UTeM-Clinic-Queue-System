<?php

session_start();
include("../dbconnect.php");

function showPopupAndReturn($message) {
    $_SESSION["popupMessage"] = $message;
    header("Location: bookAppointment.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    showPopupAndReturn("Invalid request.");
}

$userID = $_SESSION["userID"] ?? null;

if (!$userID) {
    showPopupAndReturn("Session expired. Please login again.");
}

$slotID = $_POST["slotID"] ?? null;
$appointmentDate = $_POST["appointmentDate"] ?? null;
$appointmentType = $_POST["appointmentType"] ?? null;
$session = $_POST["session"] ?? null;
$timeSlot = $_POST["timeSlot"] ?? null;
$appointmentFor = $_POST["appointmentFor"] ?? "Self";

$dependantName = $_POST["dependantName"] ?? null;
$dependantRelationship = $_POST["dependantRelationship"] ?? null;

if (empty($appointmentType)) {
    showPopupAndReturn("Please select an appointment type.");
}

if (empty($appointmentDate)) {
    showPopupAndReturn("Please select an appointment date.");
}

if ($appointmentType == "Scheduled Consultation" && empty($slotID)) {
    showPopupAndReturn("Please select a time slot.");
}

if ($appointmentFor == "Dependant") {
    if (empty($dependantName) || empty($dependantRelationship)) {
        showPopupAndReturn("Please fill in dependant information.");
    }
}

if ($appointmentType == "Same-Day Consultation") {

    $sqlSlot = "
        SELECT slotID
        FROM time_slot
        WHERE slotDate = CURDATE()
        AND slotType = 'Same-Day'
        AND session = ?
        AND capacity > 0
        LIMIT 1
    ";

    $stmtSlot = $conn->prepare($sqlSlot);
    $stmtSlot->bind_param("s", $session);
    $stmtSlot->execute();

    $resultSlot = $stmtSlot->get_result();

    if ($resultSlot->num_rows == 0) {
        showPopupAndReturn("No available same-day slot for this session.");
    }

    $slot = $resultSlot->fetch_assoc();
    $slotID = $slot["slotID"];
    $appointmentDate = date("Y-m-d");
}

$sqlAppointment = "
    INSERT INTO appointment
    (
        userID,
        slotID,
        appointmentType,
        appointmentFor,
        dependantName,
        dependantRelationship,
        appointmentStatus
    )
    VALUES
    (?, ?, ?, ?, ?, ?, 'Booked')
";

$stmtAppointment = $conn->prepare($sqlAppointment);
$stmtAppointment->bind_param(
    "sissss",
    $userID,
    $slotID,
    $appointmentType,
    $appointmentFor,
    $dependantName,
    $dependantRelationship
);

if (!$stmtAppointment->execute()) {
    showPopupAndReturn("Failed to book appointment.");
}

$appointmentID = $stmtAppointment->insert_id;

$sqlUpdateSlot = "
    UPDATE time_slot
    SET capacity = capacity - 1
    WHERE slotID = ?
    AND capacity > 0
";

$stmtUpdateSlot = $conn->prepare($sqlUpdateSlot);
$stmtUpdateSlot->bind_param("i", $slotID);

if (!$stmtUpdateSlot->execute()) {
    showPopupAndReturn("Failed to update slot capacity.");
}

$_SESSION["popupMessage"] = "Appointment booked successfully.";
header("Location: bookAppointment.php");
exit;

?>