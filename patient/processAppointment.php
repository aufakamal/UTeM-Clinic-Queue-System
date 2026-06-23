<?php
session_start();
include('../dbconnect.php');

$userID = $_SESSION['userID'];

$slotID = $_POST['slotID'] ?? null;

$appointmentDate = $_POST['appointmentDate'];
$appointmentType = $_POST['appointmentType'];
$sessionType = $_POST['session'];

$appointmentFor = $_POST['appointmentFor'];

// use left side if exist, right side if not
$dependantName = $_POST['dependantName'] ?? null;

$dependantRelationship = $_POST['dependantRelationship'] ?? null;

/* Self booking */
if ($appointmentFor == 'Self') {
    $dependantName = null;
    $dependantRelationship = null;
}

/* Dependant validation */
if (
    $appointmentFor == 'Dependant'
    && (empty($dependantName) || empty($dependantRelationship))
) {
    die("Please complete dependant information.");
}

/* Scheduled validation */
if (
    $appointmentType == 'Scheduled'
    && empty($slotID)
) {
    die("Please select a time slot.");
}

/* Same-Day slot lookup */
if ($appointmentType == 'Same-Day')
{
    if ($sessionType == 'Morning Session')
    {
        $slotType = 'Same-Day Morning';
    }
    else
    {
        $slotType = 'Same-Day Afternoon';
    }

    $sqlSlot = "
    SELECT slotID
    FROM time_slot
    WHERE slotDate = '$appointmentDate'
    AND slotType = '$slotType'
    ";

    // insert data into $resultSlot
    $resultSlot =
        mysqli_query($conn, $sqlSlot);

    if (!$resultSlot) {
        die(mysqli_error($conn));
    }

    // jadikan $rowSlot cam array untuk data yang ada dalam $resultSlot
    $rowSlot =
        mysqli_fetch_assoc($resultSlot);

    // if db returns nothing
    if (!$rowSlot) {
        die("No matching slot found.");
    }

    // to obtain slotID
    $slotID =
        $rowSlot['slotID'];
}

/* Check capacity */
$sqlCapacity = "
SELECT capacity
FROM time_slot
WHERE slotID = '$slotID'
";

$resultCapacity =
    mysqli_query($conn, $sqlCapacity);

if (!$resultCapacity) {
    die(mysqli_error($conn));
}

$rowCapacity =
    mysqli_fetch_assoc($resultCapacity);

if (!$rowCapacity) {
    die("Slot not found.");
}

if ($rowCapacity['capacity'] <= 0) {
    die("This slot is already full.");
}

/* Create appointment */
$sqlInsert = "
INSERT INTO appointment
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
)
";

$resultInsert =
    mysqli_query($conn, $sqlInsert);

if (!$resultInsert) {
    die(mysqli_error($conn));
}

/* Get appointment ID */
// obtain the latest id that mysql inserted
$appointmentID =
    mysqli_insert_id($conn);

/* Create attendance */
$sqlAttendance = "
INSERT INTO attendance
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
)
";

$resultAttendance =
    mysqli_query($conn, $sqlAttendance);

if (!$resultAttendance) {
    die(mysqli_error($conn));
}

/* Deduct capacity in time slot */
$sqlUpdateCapacity = "
UPDATE time_slot
SET capacity = capacity - 1
WHERE slotID = '$slotID'
";

$resultUpdateCapacity =
    mysqli_query($conn, $sqlUpdateCapacity);

if (!$resultUpdateCapacity) {
    die(mysqli_error($conn));
}

/* Success Message and Redirect */
echo "
<script>
alert('Appointment booked successfully!\\n\\nGo to Appointment > Appointment Record to view your appointment details and status.');
window.location.href='bookAppointment.php';
</script>
";

exit();