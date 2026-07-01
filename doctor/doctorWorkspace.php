<?php

include('../dbconnect.php');

$searchPatientSql = "
    SELECT
        u.userID,
        u.fullName,
        u.gender,
        u.dateOfBirth,
        pp.patientType,
        pp.allergy,
        pp.chronicCondition,
        pp.currentMed,
        pp.bloodType
    FROM user u
    INNER JOIN patient_profile pp
    ON u.userID = pp.userID
    ORDER BY u.fullName ASC
";

$searchPatientResult = $conn->query($searchPatientSql);

$searchPatients = [];
$patientRecords = [];

if ($searchPatientResult && $searchPatientResult->num_rows > 0) {

    while ($patientRow = $searchPatientResult->fetch_assoc()) {

        $userID = $patientRow['userID'];

        $searchPatients[] = [
            "userID" => $userID,
            "fullName" => $patientRow['fullName']
        ];

        $patientRecords[$userID] = [
            "userID" => $userID,
            "fullName" => $patientRow['fullName'],
            "gender" => $patientRow['gender'],
            "dateOfBirth" => $patientRow['dateOfBirth'],
            "patientType" => $patientRow['patientType'],
            "bloodType" => $patientRow['bloodType'],
            "allergy" => $patientRow['allergy'],
            "chronicCondition" => $patientRow['chronicCondition'],
            "currentMed" => $patientRow['currentMed']
        ];

    }

}

$patient_id = $_GET['patient_id'] ?? 0;

$visitSql = "
    SELECT
        c.consultationID AS visitID,
        c.reasonForVisit AS reason,
        c.clinicalFindings AS findings,
        c.diagnosis,
        c.treatmentPlan AS treatment,
        COALESCE(
            GROUP_CONCAT(
                CONCAT(m.medicineName, ' - ', pi.dosage)
                SEPARATOR ', '
            ),
            'No medication'
        ) AS prescription_text,
        c.startTime AS visitDate,
        u.fullName AS doctor_name
    FROM appointment ap
    INNER JOIN attendance a ON ap.appointmentID = a.appointmentID
    INNER JOIN queue q ON a.attendanceID = q.attendanceID
    INNER JOIN consultation c ON q.queueID = c.queueID
    INNER JOIN user u ON c.doctorUserID = u.userID
    LEFT JOIN prescription p ON p.consultationID = c.consultationID
    LEFT JOIN prescription_item pi ON p.prescriptionID = pi.prescriptionID
    LEFT JOIN medicine m ON pi.medicineID = m.medicineID
    WHERE ap.userID = ?
    GROUP BY
        c.consultationID,
        c.reasonForVisit,
        c.clinicalFindings,
        c.diagnosis,
        c.treatmentPlan,
        c.startTime,
        u.fullName
    ORDER BY c.startTime DESC
";

$stmt = $conn->prepare($visitSql);

$visitResult = null;

if ($stmt) {

    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $visitResult = $stmt->get_result();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Workspace</title>
    <link rel="stylesheet" href="doctor.css">
</head>

<body>

<?php include('inc/doctor_header.php'); ?>

<section class="doctorWorkspace">

    <!-- LEFT COLUMN -->
    <div class="leftColumn">

        <?php

        $countResult = $conn->query("
            SELECT COUNT(*) AS total
            FROM queue
            WHERE queueStatus = 'Waiting'
        ");

        $totalWaiting = 0;

        if ($countResult) {

            $row = $countResult->fetch_assoc();
            $totalWaiting = $row['total'];

        }

        ?>

        <article class="doctorCard">

            <h2>Current Queue</h2>

            <div class="queueNumber">
                <?= $totalWaiting ?>
            </div>

            <p>Patients Waiting</p>

            <div class="queueButtons">

                <button
                    class="startBtn"
                    onclick="startSession()">
                    Start Next Patient
                </button>

                <button
                    id="endSessionBtn"
                    class="endBtn"
                    onclick="endSession()"
                    disabled>
                    End Current Session
                </button>

            </div>

        </article>

        <article class="doctorCard">

            <div class="searchPatientBox">

                <h2>Search Patient</h2>

                <input
                    class="searchInput"
                    id="searchPatientInput"
                    type="text"
                    placeholder="Search by name or patient ID"
                    onkeyup="searchPatientLive()">

                <div id="searchResultBox"></div>

            </div>

        </article>

    </div>

    <!-- RIGHT WORKSPACE -->
    <article class="workspaceCard" id="workspaceCard">

        <div id="defaultWorkspace">

            <p id="placeholderText">
                Click "Start Next Patient" to begin a consultation.
            </p>

            <div id="searchPatientView" style="display:none;"></div>

            <!-- CURRENT CONSULTATION VIEW -->
            <div id="patientRecordDisplay" style="display:none;">

                <h2 class="recordTitle">CURRENT CONSULTATION</h2>
                <h3 class="recordSubTitle">PATIENT RECORD</h3>

                <div class="patientInfo">
                    <p><strong>Full name:</strong> <span id="pName"></span></p>
                    <p><strong>Gender:</strong> <span id="pGender"></span></p>
                    <p><strong>ID:</strong> <span id="pID"></span></p>
                    <p><strong>Blood type:</strong> <span id="pBlood"></span></p>
                </div>

                <div class="miniTabBar">
                    <button class="miniTab active" onclick="switchTab('overview', this)">Overview</button>
                    <button class="miniTab" onclick="switchTab('visits', this)">Visits</button>
                    <button class="miniTab" onclick="switchTab('diagnosis', this)">Diagnosis</button>
                    <button class="miniTab" onclick="switchTab('prescription', this)">Prescription</button>
                </div>

                <!-- OVERVIEW -->
                <div class="overviewSection">

                    <div class="infoBlock">

                        <div class="infoHeader">
                            <h3>Allergies</h3>
                        </div>

                        <div id="pAllergyList"></div>

                        <div id="allergyInputBox" style="display:none; margin-top:10px;">
                            <div class="inputRow">
                                <input type="text" id="allergyInput" placeholder="Enter new allergy">
                                <button type="button" onclick="saveAllergy()">Save</button>
                            </div>
                        </div>

                    </div>

                    <div class="infoBlock">

                        <div class="infoHeader">
                            <h3>Chronic Diseases</h3>
                        </div>

                        <div id="pChronicList"></div>

                        <div id="chronicInputBox" style="display:none; margin-top:10px;">
                            <div class="inputRow">
                                <input type="text" id="chronicInput" placeholder="Enter new condition">
                                <button type="button" onclick="saveChronic()">Save</button>
                            </div>
                        </div>

                    </div>

                    <div class="infoBlock">

                        <div class="infoHeader">
                            <h3>Medication</h3>
                        </div>

                        <div id="pMedList"></div>

                        <div id="medInputBox" style="display:none; margin-top:10px;">
                            <div class="inputRow">
                                <input type="text" id="medInput" placeholder="Enter new medication">
                                <button type="button" onclick="saveMedication()">Save</button>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- VISITS SECTION -->
                <div id="visitsSection" class="tabSection" style="display:none;">

                    <?php if ($visitResult && $visitResult->num_rows > 0): ?>

                        <?php while ($visit = $visitResult->fetch_assoc()): ?>

                            <div class="visitCard">

                                <h2 class="visitTitle">Medical Record</h2>

                                <div class="visitDetail">

                                    <div class="detailRow">
                                        <div class="detailLabel">Doctor</div>
                                        <div class="detailValue">
                                            <?= $visit['doctor_name'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Reason</div>
                                        <div class="detailValue">
                                            <?= $visit['reason'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Diagnosis</div>
                                        <div class="detailValue">
                                            <?= $visit['diagnosis'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Treatment</div>
                                        <div class="detailValue">
                                            <?= $visit['treatment'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Prescription</div>
                                        <div class="detailValue">
                                            <?= $visit['prescription_text'] ?? 'No medication' ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <p style="text-align:center;">
                            No visit history found.
                        </p>

                    <?php endif; ?>

                </div>

                <!-- DIAGNOSIS SECTION -->
                <div id="diagnosisSection" class="tabSection" style="display:none;">

                    <div class="infoBlock">

                        <h3>Reason for Visit</h3>

                        <textarea
                            id="reasonInput"
                            rows="4"
                            placeholder="Enter patient's reason for visit..."></textarea>

                    </div>

                    <div class="infoBlock">

                        <div class="infoHeader">

                            <h3>Clinical Findings</h3>

                            <button
                                type="button"
                                class="addSmallBtn"
                                onclick="openFindingInput()">
                                + Add Finding
                            </button>

                        </div>

                        <div id="findingList" class="editableList"></div>

                        <div
                            id="findingInputBox"
                            class="inputRow"
                            style="display:none;">

                            <input
                                type="text"
                                id="findingInput"
                                placeholder="Enter clinical finding">

                            <button
                                type="button"
                                onclick="saveFinding()">
                                Save
                            </button>

                        </div>

                    </div>

                    <div class="infoBlock">

                        <h3>Diagnosis</h3>

                        <textarea
                            id="diagnosisInput"
                            rows="4"
                            placeholder="Enter diagnosis..."></textarea>

                    </div>

                    <div class="infoBlock">

                        <h3>Treatment Plan</h3>

                        <textarea
                            id="treatmentInput"
                            rows="5"
                            placeholder="Enter treatment plan..."></textarea>

                    </div>

                </div>

                <!-- PRESCRIPTION SECTION -->
                <div id="prescriptionSection" class="tabSection" style="display:none;">

                    <div class="formCard">

                        <div class="grid">

                            <div class="field">

                                <label>Medicine</label>

                                <select id="medicine" onchange="updateStock()">

                                    <option value="">Select Medicine</option>

                                    <?php

                                    $medicineResult = $conn->query("SELECT * FROM medicine");

                                    if ($medicineResult && $medicineResult->num_rows > 0) {

                                        while ($row = $medicineResult->fetch_assoc()) {

                                    ?>

                                            <option
                                                value="<?= $row['medicineID'] ?>"
                                                data-stock="<?= $row['stockQuantity'] ?>">
                                                <?= $row['medicineName'] ?>
                                            </option>

                                    <?php

                                        }

                                    }

                                    ?>

                                </select>

                            </div>

                            <div class="stockQtyRow">

                                <div class="field">
                                    <label>Stock Available</label>
                                    <div class="stockBox" id="stockBox">-</div>
                                </div>

                                <div class="field">

                                    <label>Quantity</label>

                                    <input
                                        type="number"
                                        id="quantity"
                                        min="1"
                                        placeholder="e.g. 2">

                                </div>

                            </div>

                            <div class="field">

                                <label>Dosage</label>

                                <input
                                    type="text"
                                    id="dosage"
                                    placeholder="e.g. 500mg">

                            </div>

                            <div class="field">

                                <label>Frequency</label>

                                <input
                                    type="text"
                                    id="frequency"
                                    placeholder="e.g. 3 times daily">

                            </div>

                            <div class="field">

                                <label>Duration</label>

                                <input
                                    type="text"
                                    id="duration"
                                    placeholder="e.g. 5 days">

                            </div>

                            <div class="field">

                                <label>Instruction</label>

                                <input
                                    type="text"
                                    id="instruction"
                                    placeholder="e.g. After meal">

                            </div>

                        </div>

                        <div class="addPrescriptionWrap">

                            <button type="button" class="addBtn" onclick="addPrescription()">
                                + Add to Prescription
                            </button>

                        </div>

                    </div>

                    <div class="prescriptionListBox">

                        <h3>Prescription List</h3>
                        <p>Listed medicines will be saved for this patient.</p>

                        <table class="rxTable">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th>Qty</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="rxTableBody">
                                <tr id="emptyRow">
                                    <td colspan="8" style="text-align:center;">
                                        No prescription added yet
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>

                    <div class="importantBox">

                        <div>
                            <h4>ⓘ Important Notes</h4>
                            <p>• Please review the prescription before submitting.</p>
                            <p>• Stock will be deducted after ending the consultation.</p>
                            <p>• Cannot prescribe medicine with 0 stock.</p>
                        </div>

                        <!-- <button type="button" class="savePrescriptionBtn" onclick="savePrescription()">
                            💾 Save Prescription
                        </button> -->

                    </div>

                </div>

            </div>

            <!-- VIEW ONLY PATIENT MEDICAL RECORD -->
            <div id="viewOnlyRecordDisplay" style="display:none;">

                <h2 class="recordTitle">
                    Patient Medical Record
                    <span style="color: green; font-size: 16px;">(View Only)</span>
                </h2>

                <div class="viewOnlyNotice">

                    <div class="noticeIcon">
                        👁
                    </div>

                    <div class="noticeText">
                        You are <strong>viewing</strong> this patient's medical record.
                        Information shown below is for <strong>reference only</strong>.
                    </div>

                </div>

                <div class="patientInfoCard">

                    <div class="infoLine">
                        <span class="infoLabel">Full Name:</span>
                        <span class="infoValue" id="vName">-</span>
                    </div>

                    <div class="infoLine">
                        <span class="infoLabel">Gender:</span>
                        <span class="infoValue" id="vGender">-</span>
                    </div>

                    <div class="infoLine">
                        <span class="infoLabel">ID:</span>
                        <span class="infoValue" id="vID">-</span>
                    </div>

                    <div class="infoLine">
                        <span class="infoLabel">Blood Type:</span>
                        <span class="infoValue" id="vBlood">-</span>
                    </div>

                    <div class="infoLine">
                        <span class="infoLabel">Date of Birth:</span>
                        <span class="infoValue" id="vDOB">-</span>
                    </div>

                    <div class="infoLine">
                        <span class="infoLabel">Patient Type:</span>
                        <span class="infoValue" id="vType">-</span>
                    </div>

                </div>

                <div class="miniTabBar">

                    <button class="miniTab active" onclick="switchViewTab('overview', this)">
                        Overview
                    </button>

                    <button class="miniTab" onclick="switchViewTab('visits', this)">
                        Visits
                    </button>

                </div>

                <!-- OVERVIEW -->
                <div id="viewOverviewSection" class="viewTabSection">

                    <div class="viewInfoBlock">
                        <h3>Allergies</h3>
                        <p id="vAllergy">-</p>
                    </div>

                    <div class="viewInfoBlock">
                        <h3>Chronic Diseases</h3>
                        <p id="vChronic">-</p>
                    </div>

                    <div class="viewInfoBlock">
                        <h3>Current Medication</h3>
                        <p id="vMed">-</p>
                    </div>

                </div>

                <!-- VISITS -->
                <div id="viewVisitsSection" class="viewTabSection" style="display:none;">

                    <?php if ($visitResult && $visitResult->num_rows > 0): ?>

                        <?php while ($visit = $visitResult->fetch_assoc()): ?>

                            <div class="visitCard">

                                <h2 class="visitTitle">Medical Record</h2>

                                <div class="visitDetail">

                                    <div class="detailRow">
                                        <div class="detailLabel">Doctor</div>
                                        <div class="detailValue">
                                            <?= $visit['doctor_name'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Reason</div>
                                        <div class="detailValue">
                                            <?= $visit['reason'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Diagnosis</div>
                                        <div class="detailValue">
                                            <?= $visit['diagnosis'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Treatment</div>
                                        <div class="detailValue">
                                            <?= $visit['treatment'] ?? '-' ?>
                                        </div>
                                    </div>

                                    <div class="detailRow">
                                        <div class="detailLabel">Prescription</div>
                                        <div class="detailValue">
                                            <?= $visit['prescription_text'] ?? 'No medication' ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <p style="text-align:center;">
                            No visit history found.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </article>

</section>

<script>
window.searchPatientsData = <?php echo json_encode($searchPatients); ?>;
window.patientRecordsData = <?php echo json_encode($patientRecords); ?>;
</script>

<script src="doctor.js"></script>

</body>
</html>
