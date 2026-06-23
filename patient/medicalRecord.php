<?php
    session_start();
    include('../dbconnect.php');

    $userID = $_SESSION['userID'];

    $sql = "SELECT c.consultationID, c.startTime, c.reasonForVisit, c.clinicalFindings, c.diagnosis, c.treatmentPlan, u.fullName AS doctorName
            FROM appointment a
            JOIN attendance att ON a.appointmentID = att.appointmentID
            JOIN queue q ON att.attendanceID = q.attendanceID
            JOIN consultation c ON q.queueID = c.queueID
            JOIN user u ON c.doctorUserID = u.userID
            WHERE a.userID = '$userID'
            ORDER BY c.startTime DESC";

    $result = mysqli_query($conn, $sql);
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
        <h2>Medical Record</h2>
        <p>View your visit history, doctor notes, and prescriptions.</p>

        <article class="medicalRecordCard">

            <?php
                if(mysqli_num_rows($result) == 0)
                {
            ?>

            <p>No medical records found.</p>

            <?php
            }
                else
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
            ?>

            <div class="medicalRecordHeader">
                <span class="medicalRecordLabel">Visit Date</span>

                <h2><?= date('d/m/Y', strtotime($row['startTime'])) ?></h2>
            </div>

            <div class="medicalRecordTables">
                <table>
                    <tr>
                        <th>Doctor</th>
                        <td><?= $row['doctorName'] ?></td>
                    </tr>

                    <tr>
                        <th>Reason for Visit</th>
                        <td><?= $row['reasonForVisit'] ?></td>
                    </tr>

                    <tr>
                        <th>Clinical Findings</th>
                        <td><?= $row['clinicalFindings'] ?></td>
                    </tr>

                    <tr>
                        <th>Diagnosis</th>
                        <td><?= $row['diagnosis'] ?></td>
                    </tr>

                    <tr>
                        <th>Treatment Plan</th>
                        <td><?= $row['treatmentPlan'] ?></td>
                    </tr>

                    <tr>
                        <th>Prescription</th>
                        <td>
                            <?php

                                $consultationID = $row['consultationID'];

                                $prescriptionSQL = "SELECT m.medicineName, pi.dosage, pi.frequency, pi.duration, pi.instructions
                                                    FROM prescription p
                                                    JOIN prescription_item pi ON p.prescriptionID = pi.prescriptionID
                                                    JOIN medicine m ON pi.medicineID = m.medicineID
                                                    WHERE p.consultationID = '$consultationID'";

                                $prescriptionResult = mysqli_query($conn, $prescriptionSQL);

                                if(mysqli_num_rows($prescriptionResult) == 0) {
                                    echo "No prescription issued.";
                                }
                                else {
                                    while($medicine = mysqli_fetch_assoc($prescriptionResult)) {
                                        echo "<strong>" . $medicine['medicineName'] . "</strong><br>";
                                        echo "Dosage: " . $medicine['dosage'] . "<br>";
                                        echo "Frequency: " . $medicine['frequency'] . "<br>";
                                        echo "Duration: " . $medicine['duration'] . "<br>";
                                        echo "Instruction: " . $medicine['instructions'] . "<br><br>";
                                    }
                                }

                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </article>
        <?php
                }
            }
        ?>
    </section>

    <?php include('inc/patient_footer.php'); ?>
    <script src="js/medicalRecord.js"></script>
</body>
</html>