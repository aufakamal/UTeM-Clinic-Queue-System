<?php

    session_start();
    include('../dbconnect.php');

    $userID = $_SESSION['userID'];

    $upcomingSQL = "SELECT a.appointmentType, ts.slotDate, ts.startTime, ts.endTime
                    FROM appointment a

                    JOIN time_slot ts ON a.slotID = ts.slotID

                    WHERE a.userID = '$userID' AND a.appointmentStatus = 'Booked'

                    ORDER BY ts.slotDate ASC, ts.startTime ASC

                    LIMIT 1";

    $upcomingResult = mysqli_query($conn, $upcomingSQL);

    $upcomingAppointment = mysqli_fetch_assoc($upcomingResult);

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
        <div class="topCards">
            <article>
                <h2>Upcoming Appointment</h2>

                <?php
                    if($upcomingAppointment)
                    {
                ?>

                <div class="upcomingAppointmentCard">
                    <h3>
                        <?= date('d/m/Y',
                            strtotime($upcomingAppointment['slotDate'])) ?>
                    </h3>

                    <p class="appointmentTime">
                        <?= date('g:i A',
                            strtotime($upcomingAppointment['startTime'])) ?>
                        -
                        <?= date('g:i A',
                            strtotime($upcomingAppointment['endTime'])) ?>
                    </p>

                    <span class="appointmentTypeBadge">
                        <?= $upcomingAppointment['appointmentType'] ?>
                    </span>

                    <p>
                        <a href="appointmentRecord.php">
                            View Appointment Record 
                        </a>
                    </p>
                </div>

                <?php
                    }
                    else
                    {
                ?>

                <p>No appointment booked yet.<a href="bookAppointment.php"> Click here </a>to make an appointment.</p>

                <?php
                    }
                ?>

            </article>

            <article>
                <h2>Clinic Information</h2>

                <h3>Before Your Appointment</h3>
                <p>Please arrive at least 15 minutes before your scheduled appointment and bring your student identification card for verification.</p>

                <br>

                <h3>Medication Collection</h3>
                <p>Patients who receive prescriptions may collect their medication at the pharmacy counter after consultation and verification.</p>
            </article>
        </div>

        <article>
            <h2>Pusat Kesihatan UTeM Operation Hours (MAIN CAMPUS)</h2>

            <div class="tableGrid">
                <table>
                    <tr>
                        <th colspan="2">Academic Week</th>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>8 AM - 7 PM</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>8 AM - 7 PM</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>8 AM - 7 PM</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>8 AM - 7 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>8 AM - 7 PM</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>Closed</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Closed</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th colspan="2">Semester Break</th>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>Closed</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Closed</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th colspan="2">Lunch Break</th>
                    </tr>
                    <tr>
                        <td>Monday - Thursday</td>
                        <td>1 PM - 2 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>12.15 PM - 2.45 PM</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th>Public Holiday</th>
                    </tr>
                    <tr>
                        <td>Closed</td>
                    </tr>
                </table>
            </div>
        </article>

        <article>
            <h2>Pusat Kesihatan UTeM Operation Hours (CAMPUS TECHNOLOGY)</h2>

            <div class="tableGrid">
                <table>
                    <tr>
                        <th colspan="2">Academic Week</th>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>Closed</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Closed</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th colspan="2">Semester Break</th>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>8 AM - 5 PM</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>Closed</td>
                    </tr>
                    <tr>
                        <td>Sunday</td>
                        <td>Closed</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th colspan="2">Lunch Break</th>
                    </tr>
                    <tr>
                        <td>Monday - Thursday</td>
                        <td>1 PM - 2 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>12.15 PM - 2.45 PM</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th>Public Holiday</th>
                    </tr>
                    <tr>
                        <td>Closed</td>
                    </tr>
                </table>
            </div>
        </article>
    </section>

    <?php include('inc/patient_footer.php'); ?>
<!-- <script src="patient.js"></script> -->
</body>
</html>