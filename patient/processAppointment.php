<?php

session_start();
include('../dbconnect.php');

$userID = $_SESSION['userID'] ?? null;

if (!$userID) {
    echo "<script>
            alert('Session expired. Please login again.');
            window.location.href='../login_register/login.php';
        </script>";
    exit();
}

$slotID = $_POST['slotID'] ?? null;
$appointmentDate = $_POST['appointmentDate'] ?? null;
$appointmentType = $_POST['appointmentType'] ?? null;
$sessionType = $_POST['session'] ?? null;
$appointmentFor = $_POST['appointmentFor'] ?? 'Self';

$dependantName = $_POST['dependantName'] ?? null;
$dependantRelationship = $_POST['dependantRelationship'] ?? null;

if (empty($appointmentDate)) {
    die("Please select an appointment date.");
}

if (empty($appointmentType)) {
    die("Please select an appointment type.");
}

if ($appointmentFor == 'Self') {
    $dependantName = null;
    $dependantRelationship = null;
}

if ($appointmentFor == 'Dependant' && (empty($dependantName) || empty($dependantRelationship))) {
    die("Please complete dependant information.");
}

if ($appointmentType == 'Scheduled' && empty($slotID)) {
    die("Please select a time slot.");
}

if ($appointmentType == 'Same-Day') {

    if ($sessionType == 'Morning Session') {
        $slotType = 'Same-Day Morning';
    }
    else if ($sessionType == 'Afternoon Session') {
        $slotType = 'Same-Day Afternoon';
    }
    else {
        die("Please select a session.");
    }

    $sqlSlot = "SELECT slotID
                FROM time_slot
                WHERE slotDate = '$appointmentDate'
                AND slotType = '$slotType'
                AND capacity > 0
                AND slotStatus = 'Available'
                LIMIT 1";

    $resultSlot = mysqli_query($conn, $sqlSlot);

    if (!$resultSlot) {
        die(mysqli_error($conn));
    }

    $rowSlot = mysqli_fetch_assoc($resultSlot);

    if (!$rowSlot) {
        die("No matching slot found.");
    }

    $slotID = $rowSlot['slotID'];
}

$sqlCapacity = "SELECT capacity
                FROM time_slot
                WHERE slotID = '$slotID'";

$resultCapacity = mysqli_query($conn, $sqlCapacity);

if (!$resultCapacity) {
    die(mysqli_error($conn));
}

$rowCapacity = mysqli_fetch_assoc($resultCapacity);

if (!$rowCapacity) {
    die("Slot not found.");
}

if ($rowCapacity['capacity'] <= 0) {
    die("This slot is already full.");
}

if ($dependantName !== null) {
    $dependantName = mysqli_real_escape_string($conn, $dependantName);
}

if ($dependantRelationship !== null) {
    $dependantRelationship = mysqli_real_escape_string($conn, $dependantRelationship);
}

$sqlInsert = "INSERT INTO appointment 
              (
                userID, 
                slotID, 
                appointmentType, 
                appointmentStatus, 
                appointmentFor, 
                dependantName, 
                dependantRelationship
              )
              VALUES 
              (
                '$userID', 
                '$slotID', 
                '$appointmentType', 
                'Booked', 
                '$appointmentFor', 
                " . ($dependantName ? "'$dependantName'" : "NULL") . ", 
                " . ($dependantRelationship ? "'$dependantRelationship'" : "NULL") . "
              )";

$resultInsert = mysqli_query($conn, $sqlInsert);

if (!$resultInsert) {
    die(mysqli_error($conn));
}

$appointmentID = mysqli_insert_id($conn);

$sqlAttendance = "INSERT INTO attendance 
                  (
                    appointmentID, 
                    attendanceStatus, 
                    checkInTime
                  )
                  VALUES 
                  (
                    '$appointmentID', 
                    'Pending', 
                    NULL
                  )";

$resultAttendance = mysqli_query($conn, $sqlAttendance);

if (!$resultAttendance) {
    die(mysqli_error($conn));
}

$sqlUpdateCapacity = "UPDATE time_slot
                      SET capacity = capacity - 1
                      WHERE slotID = '$slotID'
                      AND capacity > 0";

$resultUpdateCapacity = mysqli_query($conn, $sqlUpdateCapacity);

if (!$resultUpdateCapacity) {
    die(mysqli_error($conn));
}

echo "<script>
        alert('Appointment booked successfully!\\n\\nGo to Appointment > Appointment Record to view your appointment details and status.');
        window.location.href='bookAppointment.php';
    </script>";

exit();

?>