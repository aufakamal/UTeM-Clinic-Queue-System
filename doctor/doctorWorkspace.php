<?php
include('inc/doctor_header.php'); 
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


<section class="doctorWorkspace">

    <!-- LEFT COLUMN -->
    <div class="leftColumn">

        <!-- <article class="doctorCard">
            <h2>Current Consultations</h2>

            <div class="currentconstBox">
                <div>
                    <h3 style="color: #0369A1;">A103</h3>
                    <p style="font-weight: 600; font-size: 16px;">
                        Siti Sarah bt Roslan
                    </p>
                    <p class="appointmentLabel">
                        Type of Appointment: Medical Checkup
                    </p>
                </div>

                <button class="startBtn" data-queue="A103">
                    Start Session
                </button>

                <button class="endBtn" data-queue="A103">
                    End Session
                </button>
            </div>
        </article> -->

        <?php
        $queueResult = $conn->query("
            SELECT 
                q.queue_id,
                q.queueStatus,
                u.userID,
                u.fullName,
                u.gender,
                u.bloodType
            FROM queue q
            INNER JOIN user u ON q.userID = u.userID
            WHERE q.queueStatus = 'Waiting'
            ORDER BY q.queue_id ASC
        ");
        ?>

        <article class="doctorCard">
            <h2>Current Queue</h2>

            <?php if ($queueResult && $queueResult->num_rows > 0) { ?>

                <?php while ($p = $queueResult->fetch_assoc()) { ?>

                    <div class="currentconstBox">

                        <div>
                            <h3><?= $p['userID'] ?></h3>
                            <p><?= $p['fullName'] ?></p>
                            <small><?= $p['gender'] ?> | <?= $p['bloodType'] ?></small>
                        </div>

                        <button 
                            class="startBtn" 
                            data-queueid="<?= $p['queue_id'] ?>" 
                            onclick="startSessionFromQueue(this)">
                            Start Session
                        </button>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p style="padding:10px;">No patients in queue</p>

            <?php } ?>
        </article>

        <article class="doctorCard">
            <div class="searchPatientBox">
                <h2>Search Patient</h2>

                <input 
                    class="searchInput" 
                    id="searchPatientInput"
                    type="text" 
                    placeholder="Search by name or patient ID"
                    onkeyup="searchPatientLive()"
                >

                <div id="searchResultBox"></div>
            </div>
        </article>

    </div>

    <!-- RIGHT WORKSPACE -->
    <article class="workspaceCard" id="workspaceCard">

        <div id="defaultWorkspace">

            <p id="placeholderText">
                Please select a patient to start consultation.
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
                            <button type="button" class="addSmallBtn" onclick="openAllergyInput()">+ Add Allergy</button>
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
                            <button type="button" class="addSmallBtn" onclick="openChronicInput()">+ Add Condition</button>
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
                            <button type="button" class="addSmallBtn" onclick="openMedicationInput()">+ Add Medication</button>
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
            </div>

            <!-- VIEW ONLY PATIENT MEDICAL RECORD -->
            <div id="viewOnlyRecordDisplay" style="display:none;">

                <h2 class="recordTitle">
                    Patient Medical Record
                    <span style="color: green; font-size: 16px;">(View Only)</span>
                </h2>

                <div class="viewOnlyNotice">
                    You are viewing this patient's medical record. Information shown below is for reference only.
                </div>

                <div class="viewOnlyGrid">

                    <div class="viewField">
                        <label>Full Name</label>
                        <p id="vName">-</p>
                    </div>

                    <div class="viewField">
                        <label>Gender</label>
                        <p id="vGender">-</p>
                    </div>

                    <div class="viewField">
                        <label>Blood Type</label>
                        <p id="vBlood">-</p>
                    </div>

                    <div class="viewField">
                        <label>Patient ID</label>
                        <p id="vID">-</p>
                    </div>

                    <div class="viewField">
                        <label>Date of Birth</label>
                        <p id="vDOB">-</p>
                    </div>

                    <div class="viewField">
                        <label>Patient Type</label>
                        <p id="vType">-</p>
                    </div>

                </div>

                <div class="viewInfoBlock">
                    <h3>Allergies</h3>
                    <p id="vAllergy">-</p>
                </div>

                <div class="viewInfoBlock">
                    <h3>Chronic Condition</h3>
                    <p id="vChronic">-</p>
                </div>

                <div class="viewInfoBlock">
                    <h3>Current Medication</h3>
                    <p id="vMed">-</p>
                </div>

            </div>

            <!-- VISITS SECTION -->
            <div id="visitsSection" class="tabSection" style="display:none;">
                <div id="visitHistoryList"></div>

                <div class="visitCard">
                    <h2 class="visitTitle">Medical Record</h2>

                    <table class="visitTable">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                <th>Doctor</th>
                                <th>Appointment Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>11:00 AM - 12:00 PM</td>
                                <td>Dr Anis</td>
                                <td>Same-Day Consultation</td>
                                <td><span class="status done">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="visitDetail">
                        <div><b>Reason for Visit:</b> Fever</div>
                        <div><b>Clinical Findings:</b> Viral infection</div>
                        <div><b>Diagnosis:</b> Influenza</div>
                        <div><b>Treatment Plan:</b> Rest + fluids</div>
                        <div><b>Prescription:</b> Paracetamol</div>
                    </div>
                </div>
            </div>

            <!-- DIAGNOSIS SECTION -->
            <div id="diagnosisSection" class="tabSection" style="display:none;">

                <div class="diagnosisCleanBox">

                    <div class="findingBox">
                        <strong>Reason for Visit</strong>
                        <input type="text" id="reasonInput" oninput="saveClinical()">
                    </div>

                    <div class="cardBox">
                        <div class="cardHeader">
                            <h4>Clinical Findings</h4>
                        </div>

                        <div class="findingsGrid">

                            <div class="findingBox">
                                <strong>Temperature</strong>
                                <input type="text" id="tempInput">
                            </div>

                            <div class="findingBox">
                                <strong>Blood Pressure</strong>
                                <input type="text" id="bpInput">
                            </div>

                            <div class="findingBox">
                                <strong>Heart Rate</strong>
                                <input type="text" id="hrInput">
                            </div>

                            <div class="findingBox">
                                <strong>Physical Observation</strong>
                                <input type="text" id="observationInput" placeholder="Enter observation">
                            </div>

                            <div class="findingBox">
                                <strong>Test Results</strong>
                                <input type="text" id="testInput" placeholder="Enter test result">
                            </div>

                        </div>

                        <div id="findingOutput"></div>

                        <div class="noteRow">
                            <input id="newFinding" placeholder="Add Clinical finding...">
                            <button type="button" onclick="addFinding()">Add</button>
                        </div>
                    </div>

                    <div class="findingBox">
                        <strong>Diagnosis</strong>
                        <input type="text" id="diagnosisInput" oninput="saveClinical()">
                    </div>

                    <div class="findingBox">
                        <strong>Treatment Plan</strong>
                        <input type="text" id="treatmentInput" oninput="saveClinical()">
                    </div>

                </div>

            </div>

            <!-- PRESCRIPTION SECTION -->
            <div id="prescriptionSection" class="tabSection" style="display:none;">

                <h2 class="prescriptionTitle">PRESCRIPTION</h2>

                <div class="formCard">

                    <div class="rowTop">
                        <h3>Add Medicine <span class="infoIcon">ⓘ</span></h3>
                        <button type="button" class="adminBtn">+ Add New Medicine (Admin Only)</button>
                    </div>

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

                        <div class="field">
                            <label>Stock Available</label>
                            <div class="stockBox" id="stockBox">-</div>
                        </div>

                        <div class="field">
                            <label>Dosage</label>
                            <select id="dosage">
                                <option value="">Select Dosage</option>
                                <?php
                                $result = $conn->query("SELECT * FROM dosage");
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="field">
                            <label>Frequency</label>
                            <select id="frequency">
                                <option value="">Select Frequency</option>
                                <?php
                                $result = $conn->query("SELECT * FROM frequency");
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="field">
                            <label>Duration</label>
                            <select id="duration">
                                <option value="">Select Duration</option>
                                <?php
                                $result = $conn->query("SELECT * FROM duration");
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="field">
                            <label>Instruction</label>
                            <select id="instruction">
                                <option value="">Select Instruction</option>
                                <?php
                                $result = $conn->query("SELECT * FROM instruction");
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
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
                                <th>Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="rxTableBody">
                            <tr id="emptyRow">
                                <td colspan="7" style="text-align:center;">
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
                        <p>• Stock will be deducted after saving the prescription.</p>
                        <p>• Cannot prescribe medicine with 0 stock.</p>
                    </div>

                    <button type="button" class="savePrescriptionBtn" onclick="savePrescription()">
                        💾 Save Prescription
                    </button>
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