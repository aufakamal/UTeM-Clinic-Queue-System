<?php

$conn = new mysqli("localhost", "root", "", "clinic_db", 3306);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

/* =========================
   PENDING PRESCRIPTIONS
========================= */
$sql = "
SELECT
    p.prescriptionID,
    p.status,
    q.queueNo,
    patient.fullName AS patientName,
    doctor.fullName AS doctorName,
    GROUP_CONCAT(
        CONCAT(
            m.medicineName,
            '<br>Quantity: ', pi.quantity,
            '<br>Current Stock: ', m.stockQuantity,
            '<br><br>Dosage: ', pi.dosage,
            '<br>Frequency: ', pi.frequency,
            '<br>Duration: ', pi.duration,
            '<br>Doctor Note: ', pi.instructions
        )
        SEPARATOR '<br><br>'
    ) AS prescriptionInfo
FROM prescription p
INNER JOIN consultation c ON p.consultationID = c.consultationID
INNER JOIN queue q ON c.queueID = q.queueID
INNER JOIN attendance a ON q.attendanceID = a.attendanceID
INNER JOIN appointment ap ON a.appointmentID = ap.appointmentID
INNER JOIN user patient ON ap.userID = patient.userID
INNER JOIN user doctor ON c.doctorUserID = doctor.userID
INNER JOIN prescription_item pi ON p.prescriptionID = pi.prescriptionID
INNER JOIN medicine m ON pi.medicineID = m.medicineID
WHERE p.status = 'Pending'
GROUP BY p.prescriptionID, q.queueNo, patient.fullName, doctor.fullName, p.status
ORDER BY p.prescriptionID DESC
";

$result = $conn->query($sql);

if (!$result)
{
    die("Query error: " . $conn->error);
}

$patients = array();

/* =========================
   SEARCH PATIENT DATA
========================= */
$searchPatientSql = "
SELECT 
    u.userID,
    u.fullName,
    u.gender,
    u.dateOfBirth,
    u.email,
    u.phoneNo,
    pp.patientType,
    pp.allergy,
    pp.chronicCondition,
    pp.currentMed,
    pp.bloodType
FROM user u
INNER JOIN patient_profile pp ON u.userID = pp.userID
ORDER BY u.fullName ASC
";

$searchPatientResult = $conn->query($searchPatientSql);

$searchPatients = array();
$patientRecords = array();

if ($searchPatientResult && $searchPatientResult->num_rows > 0)
{
    while ($patientRow = $searchPatientResult->fetch_assoc())
    {
        $userID = $patientRow['userID'];

        $searchPatients[] = array(
            "userID" => $userID,
            "fullName" => $patientRow['fullName'],
            "gender" => $patientRow['gender'],
            "dateOfBirth" => $patientRow['dateOfBirth'],
            "bloodType" => $patientRow['bloodType'],
            "allergy" => $patientRow['allergy'],
            "chronicCondition" => $patientRow['chronicCondition'],
            "currentMed" => $patientRow['currentMed']
        );

        $patientRecords[$userID] = array(
            "userID" => $userID,
            "fullName" => $patientRow['fullName'],
            "gender" => $patientRow['gender'],
            "dateOfBirth" => $patientRow['dateOfBirth'],
            "patientType" => $patientRow['patientType'],
            "bloodType" => $patientRow['bloodType'],
            "allergy" => $patientRow['allergy'],
            "chronicCondition" => $patientRow['chronicCondition'],
            "currentMed" => $patientRow['currentMed'],
            "history" => array()
        );
    }
}

/* =========================
   PRESCRIPTION HISTORY
========================= */
$historySql = "
SELECT
    ap.userID,
    q.queueNo,
    c.startTime,
    doctor.fullName AS doctorName,
    p.status,
    p.prescriptionDate,
    m.medicineName,
    pi.quantity,
    pi.dosage,
    pi.frequency,
    pi.duration,
    pi.instructions
FROM prescription p
INNER JOIN consultation c ON p.consultationID = c.consultationID
INNER JOIN queue q ON c.queueID = q.queueID
INNER JOIN attendance a ON q.attendanceID = a.attendanceID
INNER JOIN appointment ap ON a.appointmentID = ap.appointmentID
INNER JOIN user doctor ON c.doctorUserID = doctor.userID
INNER JOIN prescription_item pi ON p.prescriptionID = pi.prescriptionID
INNER JOIN medicine m ON pi.medicineID = m.medicineID
ORDER BY c.startTime DESC
";

$historyResult = $conn->query($historySql);

if ($historyResult && $historyResult->num_rows > 0)
{
    while ($historyRow = $historyResult->fetch_assoc())
    {
        $userID = $historyRow['userID'];

        if (isset($patientRecords[$userID]))
        {
            $patientRecords[$userID]["history"][] = array(
                "queueNo" => "Q" . $historyRow['queueNo'],
                "dateTime" => $historyRow['startTime'],
                "doctorName" => $historyRow['doctorName'],
                "medicineName" => $historyRow['medicineName'],
                "quantity" => $historyRow['quantity'],
                "dosage" => $historyRow['dosage'],
                "frequency" => $historyRow['frequency'],
                "duration" => $historyRow['duration'],
                "instructions" => $historyRow['instructions'],
                "status" => $historyRow['status']
            );
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Workspace</title>
    <link rel="stylesheet" href="pharmacist.css">
</head>

<body>

    <?php include('inc/pharmacist_header.php'); ?>

    <section class="pharmacistWorkspace">

        <div class="leftColumn">

            <article class="pharmacyCard pendingCard">
                <h2>Pending Prescriptions</h2>
                <p class="pendingText">Showing latest pending prescriptions</p>

                <?php
                if ($result->num_rows > 0)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        $key = "P" . $row['prescriptionID'];

                        $patients[$key] = array(
                            "name" => $row['patientName'],
                            "queue" => "Q" . $row['queueNo'],
                            "doctor" => $row['doctorName'],
                            "allergy" => "No Known Allergy",
                            "prescription" => $row['prescriptionInfo']
                        );
                ?>

                <div class="queueBox">
                    <div>
                        <h3><?php echo "Q" . $row['queueNo']; ?></h3>
                        <p><?php echo $row['patientName']; ?></p>
                        <span class="queueStatus">Pending</span>
                    </div>

                    <button class="viewBtn" data-queue="<?php echo $key; ?>">View</button>
                </div>

                <?php
                    }
                }
                else
                {
                    echo "<p class='recentText'>No pending prescription.</p>";
                }
                ?>
            </article>

            <article class="pharmacyCard">
                <h2>Search Patient</h2>

                <input 
                    class="searchInput" 
                    id="searchPatientInput"
                    type="text" 
                    placeholder="Search by name or patient ID"
                    onkeyup="searchPatientLive()"
                >

                <div id="searchResultBox"></div>
            </article>

        </div>

        <div class="rightWorkspaceColumn">

            <article class="workspaceCard">

                <div class="emptyWorkspace">
                    <h2>Prescription Workspace</h2>
                    <p>Please select a prescription or search a patient to view details.</p>
                </div>

                <div class="prescriptionDetails">
                    <h2>Prescription Workspace</h2>
                    
                    <div class="patientDetails">
                        <p><b>Patient Name:</b> <span class="patientName"></span></p>
                        <p><b>Queue No:</b> <span class="queueNo"></span></p>
                        <p><b>Doctor:</b> <span class="doctorName"></span></p>
                    </div>

                    <div class="prescriptionArea">
                        <div>
                            <h3>Allergies</h3>
                            <p class="allergyInfo"></p>

                            <h3>Prescription</h3>
                            <p class="prescriptionInfo"></p>
                        </div>

                        <div>
                            <h3>Safety Check</h3>
                            <label><input type="checkbox"> Allergy checked</label>
                            <label><input type="checkbox"> Dosage checked</label>
                            <label><input type="checkbox"> Instruction clear</label>

                            <h3>Remarks</h3>
                            <textarea class="pharmacistNote" placeholder="Write remarks here..."></textarea>
                        </div>
                    </div>
                    
                    <div class="nextActionBox">
                        <div>
                            <b>What happens next?</b>
                            <p><b>Mark as Ready:</b> After preparing the medication.</p>
                            <p><b>Dispense:</b> When the patient collects the medication.</p>
                        </div>

                        <div class="actionButtons">
                            <button class="readyBtn">Mark as Ready</button>
                            <button class="dispenseBtn">Dispense</button>
                        </div>
                    </div>
                </div>

                <div id="patientRecordView" style="display:none;"></div>

            </article>

            <article class="statusFlowBox">
                <h2>Prescription Status Flow</h2>

                <div class="statusFlow">
                    <div class="flowCard pendingFlow">
                        <h3>Pending</h3>
                        <p>Prescription received</p>
                    </div>

                    <span>→</span>

                    <div class="flowCard readyFlow">
                        <h3>Ready</h3>
                        <p>Medication prepared</p>
                    </div>

                    <span>→</span>

                    <div class="flowCard dispensedFlow">
                        <h3>Dispensed</h3>
                        <p>Patient collected</p>
                    </div>
                </div>
            </article>

        </div>

    </section>

    <div class="messagePopup">
        <p class="messageText"></p>
        <button class="okBtn">OK</button>
    </div>

    <script>
        const patientsData = <?php echo json_encode($patients); ?>;
        const searchPatientsData = <?php echo json_encode($searchPatients); ?>;
        const patientRecordsData = <?php echo json_encode($patientRecords); ?>;
    </script>

    <script src="js/pharmacist.js"></script>
    <script src="js/workspace.js"></script>

</body>
</html>

<?php
$conn->close();
?>