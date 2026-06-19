<?php
include('inc/connect.php');

$userID = 'B032410101';

$sql = "
    SELECT
        a.appointmentID,
        a.appointmentType,
        a.appointmentStatus,
        ts.slotDate,
        ts.startTime,
        ts.endTime
    FROM appointment a
    JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE a.userID = '$userID'
";

$result = mysqli_query($conn, $sql);

$previousSQL = "
    SELECT
        a.appointmentID,
        a.appointmentType,
        a.appointmentStatus,
        ts.slotDate,
        ts.startTime,
        ts.endTime
    FROM appointment a
    JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE a.userID = '$userID'
        AND a.appointmentStatus IN ('Completed', 'Cancelled')
    ORDER BY ts.slotDate DESC
";

$previousResult = mysqli_query($conn, $previousSQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient.css">
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
        while ($row = mysqli_fetch_assoc($result))
        {
            if ($row['appointmentStatus'] == 'Booked')
            {
        ?>
                <article>
                    <h2><?= date('d/m/Y', strtotime($row['slotDate'])) ?></h2>

                    <div class="singleTable">
                        <table>
                            <tr>
                                <th>Appointment Type</th>
                                <th>Time Slot</th>
                                <th>Appointment Status</th>
                                <th>Action</th>
                            </tr>

                            <tr>
                                <td><?= $row['appointmentType'] ?></td>

                                <td>
                                    <?= date('g:i A', strtotime($row['startTime'])) ?>
                                    -
                                    <?= date('g:i A', strtotime($row['endTime'])) ?>
                                </td>

                                <td><?= $row['appointmentStatus'] ?></td>

                                <td>
                                    <button type="button">Cancel</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </article>
        <?php
            }
        }
        ?>
    </article>

    <article id="previousRecords" class="hidden">
        <?php
        while ($row = mysqli_fetch_assoc($previousResult))
        {
        ?>
            <article>
                <h2><?= date('d/m/Y', strtotime($row['slotDate'])) ?></h2>

                <div class="singleTable">
                    <table>
                        <tr>
                            <th>Appointment Type</th>
                            <th>Time Slot</th>
                            <th>Appointment Status</th>
                            <th>Prescription Status</th>
                        </tr>

                        <tr>
                            <td><?= $row['appointmentType'] ?></td>

                            <td>
                                <?= date('g:i A', strtotime($row['startTime'])) ?>
                                -
                                <?= date('g:i A', strtotime($row['endTime'])) ?>
                            </td>

                            <td><?= $row['appointmentStatus'] ?></td>

                            <td>No Prescription Issued</td>
                        </tr>
                    </table>
                </div>
            </article>
        <?php
        }
        ?>
    </article>
</section>

<script src="js/appointmentRecord.js"></script>

</body>
</html>