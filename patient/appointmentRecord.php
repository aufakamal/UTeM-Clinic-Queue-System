<?php
session_start();
include('../dbconnect.php');

$userID = $_SESSION['userID'];

/* Upcoming Appointments */
$upcomingSQL = "
SELECT
    a.appointmentID,
    a.appointmentType,
    a.appointmentStatus,
    a.appointmentFor,
    a.dependantName,
    a.dependantRelationship,
    ts.slotDate,
    ts.startTime,
    ts.endTime,
    ts.slotType
FROM appointment a
JOIN time_slot ts
    ON a.slotID = ts.slotID
WHERE a.userID = '$userID'
AND a.appointmentStatus = 'Booked'
ORDER BY ts.slotDate ASC,
         ts.startTime ASC
";

$upcomingResult =
    mysqli_query($conn, $upcomingSQL);


/* Previous Appointments */
$previousSQL = "
SELECT
    a.appointmentID,
    a.appointmentType,
    a.appointmentStatus,
    a.appointmentFor,
    a.dependantName,
    a.dependantRelationship,
    ts.slotDate,
    ts.startTime,
    ts.endTime,
    ts.slotType
FROM appointment a
JOIN time_slot ts
    ON a.slotID = ts.slotID
WHERE a.userID = '$userID'
AND a.appointmentStatus IN
(
    'Completed',
    'Cancelled',
    'No Show'
)
ORDER BY ts.slotDate DESC,
         ts.startTime DESC
";

$previousResult =
    mysqli_query($conn, $previousSQL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inc/patient.css">
    <title>UTeM Clinic Queue System</title>
</head>
<body>

<?php include('inc/patient_header.php'); ?>

<section>
    <h2>Appointment Record</h2>
    <p>Track your upcoming visits and review previous bookings.</p>

    <div class="appointmentTabs">
        <button type="button" id="upcomingTab" class="activeTab">Upcoming</button>
        <button type="button" id="previousTab">Previous</button>
    </div>

    <article id="upcomingRecords">

<?php
if(mysqli_num_rows($upcomingResult) == 0)
{
?>
    <p>No upcoming appointments found.</p>
<?php
}
else
{
?>

<div class="appointmentRecordTable">

<table>

<tr>
    <th>Date</th>
    <th>Session</th>
    <th>Time Slot</th>
    <th>Appointment For</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($upcomingResult))
{
?>

<tr>

    <td>
        <?= date('d/m/Y', strtotime($row['slotDate'])) ?>
    </td>

    <td>
        <?= $row['slotType'] ?>
    </td>

    <td>
        <?= date('g:i A', strtotime($row['startTime'])) ?>
        -
        <?= date('g:i A', strtotime($row['endTime'])) ?>
    </td>

    <td>

        <?php
        if($row['appointmentFor'] == 'Self')
        {
            echo "Self";
        }
        else
        {
            echo $row['dependantName']
                . " ("
                . $row['dependantRelationship']
                . ")";
        }
        ?>

    </td>

    <td>
        <span class="status<?= str_replace(' ', '', $row['appointmentStatus']) ?>">
            <?= $row['appointmentStatus'] ?>
        </span>
    </td>

    <td>

<form
    action="cancelAppointment.php"
    method="POST"
    onsubmit="return confirm('Are you sure you want to cancel this appointment?');">

    <input
        type="hidden"
        name="appointmentID"
        value="<?= $row['appointmentID'] ?>">

    <button
        type="submit"
        class="cancelBtn">
        Cancel
    </button>

</form>

    </td>

</tr>

<?php
}
?>

</table>

</div>

<?php
}
?>

</article>

<article id="previousRecords" class="hidden">

<?php
if(mysqli_num_rows($previousResult) == 0)
{
?>
    <p>No previous appointments found.</p>
<?php
}
else
{
?>

<div class="appointmentRecordTable">

<table>

<tr>
    <th>Date</th>
    <th>Session</th>
    <th>Time Slot</th>
    <th>Appointment For</th>
    <th>Status</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($previousResult))
{
?>

<tr>

    <td>
        <?= date('d/m/Y', strtotime($row['slotDate'])) ?>
    </td>

    <td>
        <?= $row['slotType'] ?>
    </td>

    <td>
        <?= date('g:i A', strtotime($row['startTime'])) ?>
        -
        <?= date('g:i A', strtotime($row['endTime'])) ?>
    </td>

    <td>

        <?php
        if($row['appointmentFor'] == 'Self')
        {
            echo "Self";
        }
        else
        {
            echo $row['dependantName']
                . " ("
                . $row['dependantRelationship']
                . ")";
        }
        ?>

    </td>

    <td>
        <span class="status<?= str_replace(' ', '', $row['appointmentStatus']) ?>">
            <?= $row['appointmentStatus'] ?>
        </span>
    </td>

</tr>

<?php
}
?>

</table>

</div>

<?php
}
?>

</article>
</section>

<script src="js/appointmentRecord.js"></script>

</body>
</html>