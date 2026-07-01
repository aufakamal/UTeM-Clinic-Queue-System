<?php

session_start();
include('../dbconnect.php');

/* ============================
   UPDATE PRESCRIPTION ITEM
=============================== */

if (isset($_POST["savePrescription"])) {
    $prescriptionItemID = (int)$_POST["prescriptionItemID"];
    $medicineID         = (int)$_POST["medicineID"];
    $quantity           = (int)$_POST["quantity"];
    $dosage             = trim($_POST["dosage"]);
    $frequency          = trim($_POST["frequency"]);
    $duration           = trim($_POST["duration"]);
    $instruction        = trim($_POST["instruction"]);

    /* ============================
       CHECK STOCK
    =============================== */

    $stockStmt = $conn->prepare("
        SELECT stockQuantity
        FROM medicine
        WHERE medicineID = ?
    ");

    $stockStmt->bind_param("i", $medicineID);
    $stockStmt->execute();

    $stockResult = $stockStmt->get_result();

    if ($stockResult->num_rows == 0) {
        die("Medicine not found.");
    }

    $medicine = $stockResult->fetch_assoc();

    if ($quantity > $medicine["stockQuantity"]) {
        echo "<script>
                alert('Quantity exceeds available stock.');
                window.history.back();
              </script>";
        exit;
    }

    $stockStmt->close();

    /* ============================
       UPDATE PRESCRIPTION ITEM
    =============================== */

    $updateStmt = $conn->prepare("
        UPDATE prescription_item

        SET
            medicineID = ?,
            quantity = ?,
            dosage = ?,
            frequency = ?,
            duration = ?,
            instructions = ?

        WHERE prescriptionItemID = ?
    ");

    $updateStmt->bind_param(
        "iissssi",
        $medicineID,
        $quantity,
        $dosage,
        $frequency,
        $duration,
        $instruction,
        $prescriptionItemID
    );

    if (!$updateStmt->execute()) {
        die("Update failed: " . $updateStmt->error);
    }

    $updateStmt->close();

    /* ============================
    UPDATE PHARMACIST NOTE
    =============================== */

    $getNoteStmt = $conn->prepare("
        SELECT
            p.prescriptionID,
            p.note
        FROM prescription p
        INNER JOIN prescription_item pi
            ON p.prescriptionID = pi.prescriptionID
        WHERE pi.prescriptionItemID = ?
    ");

    $getNoteStmt->bind_param("i", $prescriptionItemID);
    $getNoteStmt->execute();

    $noteResult = $getNoteStmt->get_result()->fetch_assoc();

    $getNoteStmt->close();

    $pharmacistName = $_SESSION["fullName"];

    $editNote = "Edited by " . $pharmacistName;

    $currentNote = $noteResult["note"] ?? "";

    /* Remove previous "Edited by ..." line */
    $currentNote = preg_replace(
        '/\n?Edited by .*/',
        '',
        $currentNote
    );

    $currentNote = trim($currentNote);

    if (!empty($currentNote)) {
        $newNote = $currentNote . "\n" . $editNote;
    }
    else {
        $newNote = $editNote;
    }

    $updateNoteStmt = $conn->prepare("
        UPDATE prescription
        SET note = ?
        WHERE prescriptionID = ?
    ");

    $updateNoteStmt->bind_param(
        "si",
        $newNote,
        $noteResult["prescriptionID"]
    );

    $updateNoteStmt->execute();

    $updateNoteStmt->close();

    $queue = $_POST["queueKey"];

    header("Location: workspace.php?queue=" . urlencode($queue) . "&tab=prescription");

    exit;
}

/* ============================
   PENDING & READY PRESCRIPTIONS
=============================== */
$sql = "
SELECT
    p.prescriptionID,
    p.status,
    p.note,
    q.queueNo,
    patient.fullName AS patientName,
    patient.userID AS patientID,
    doctor.fullName AS doctorName,
    pp.allergy,
    pp.chronicCondition,
    pp.currentMed,

    MIN(m.medicineID) AS medicineID,
    MIN(pi.quantity) AS quantity,
    MIN(pi.dosage) AS dosage,
    MIN(pi.frequency) AS frequency,
    MIN(pi.duration) AS duration,
    MIN(pi.instructions) AS instructions,

    GROUP_CONCAT(
        CONCAT(
            m.medicineName,
            '<br>Quantity: ',pi.quantity,
            '<br>Current Stock: ',m.stockQuantity,
            '<br><br>Dosage: ',pi.dosage,
            '<br>Frequency: ',pi.frequency,
            '<br>Duration: ',pi.duration,
            '<br>Doctor Note: ',pi.instructions
        )
        SEPARATOR '<br><br>'
    ) AS prescriptionInfo

FROM prescription p
INNER JOIN consultation c ON p.consultationID=c.consultationID
INNER JOIN queue q ON c.queueID=q.queueID
INNER JOIN attendance a ON q.attendanceID=a.attendanceID
INNER JOIN appointment ap ON a.appointmentID=ap.appointmentID
INNER JOIN user patient ON ap.userID=patient.userID
INNER JOIN patient_profile pp ON patient.userID=pp.userID
INNER JOIN user doctor ON c.doctorUserID=doctor.userID
INNER JOIN prescription_item pi ON p.prescriptionID=pi.prescriptionID
INNER JOIN medicine m ON pi.medicineID=m.medicineID

WHERE p.status IN ('Pending','Ready')

GROUP BY
p.prescriptionID,
q.queueNo,
patient.fullName,
doctor.fullName,
pp.allergy,
pp.chronicCondition,
pp.currentMed,
p.status

ORDER BY
CASE
WHEN p.status='Pending' THEN 1
ELSE 2
END,
p.prescriptionID DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}

$patients = array();

/* =========================
   PRESCRIPTION ITEMS
========================= */

$itemSql = "
SELECT

    pi.prescriptionItemID,
    pi.prescriptionID,

    m.medicineID,
    m.medicineName,
    m.stockQuantity,

    pi.quantity,
    pi.dosage,
    pi.frequency,
    pi.duration,
    pi.instructions

FROM prescription_item pi

INNER JOIN medicine m
ON pi.medicineID = m.medicineID

ORDER BY pi.prescriptionID,
         pi.prescriptionItemID
";

$itemResult = $conn->query($itemSql);

if (!$itemResult) {
    die("Prescription Item Query Error: ".$conn->error);
}

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

if ($searchPatientResult && $searchPatientResult->num_rows > 0) {
    while ($patientRow = $searchPatientResult->fetch_assoc()) {
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

if ($historyResult && $historyResult->num_rows > 0) {
    while ($historyRow = $historyResult->fetch_assoc()) {
        $userID = $historyRow['userID'];

        if (isset($patientRecords[$userID])) {
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

/* =========================
   MEDICINE LIST
========================= */

$medicineSql = "
SELECT
    medicineID,
    medicineName,
    stockQuantity
FROM medicine
ORDER BY medicineName ASC
";

$medicineResult = $conn->query($medicineSql);

$medicineList = array();

if ($medicineResult && $medicineResult->num_rows > 0) {
    while ($medicineRow = $medicineResult->fetch_assoc()) {
        $medicineList[] = $medicineRow;
    }
}

if (isset($_POST["action"])) {
    $prescriptionID = (int)$_POST["prescriptionID"];
    $action = $_POST["action"];

    if ($action == "ready") {
        $stmt = $conn->prepare("
            UPDATE prescription
            SET status='Ready'
            WHERE prescriptionID=?
        ");

        $stmt->bind_param("i", $prescriptionID);
        $stmt->execute();
        $stmt->close();
    }

    else if ($action == "dispense") {
        $statusStmt = $conn->prepare("
            SELECT status
            FROM prescription
            WHERE prescriptionID = ?
        ");

        $statusStmt->bind_param("i", $prescriptionID);
        $statusStmt->execute();

        $currentStatus = $statusStmt->get_result()->fetch_assoc()["status"];

        $statusStmt->close();

        if ($currentStatus != "Ready") {
            header("Location: workspace.php?error=notready");
            exit;
        }

        /* Get all medicines in this prescription */

        $stmt = $conn->prepare("
            SELECT medicineID, quantity
            FROM prescription_item
            WHERE prescriptionID=?
        ");

        $stmt->bind_param("i", $prescriptionID);
        $stmt->execute();

        $result = $stmt->get_result();

        /* Deduct stock */

        while ($row = $result->fetch_assoc()) {
            $updateStock = $conn->prepare("
                UPDATE medicine
                SET stockQuantity = stockQuantity - ?
                WHERE medicineID = ?
            ");

            $updateStock->bind_param(
                "ii",
                $row["quantity"],
                $row["medicineID"]
            );

            $updateStock->execute();
            $updateStock->close();
        }

        $stmt->close();

        /* Update prescription status */

        $updateStatus = $conn->prepare("
            UPDATE prescription
            SET status='Dispensed'
            WHERE prescriptionID=?
        ");

        $updateStatus->bind_param("i", $prescriptionID);
        $updateStatus->execute();
        $updateStatus->close();
    }

    header("Location: workspace.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Workspace</title>
    <link rel="stylesheet" href="pharmacist.css">
    
    <link rel="icon" href="data:,">
</head>

<body>

    <?php include('inc/pharmacist_header.php'); ?>

    <section class="pharmacistWorkspace">

        <div class="leftColumn">

            <article class="pharmacyCard pendingCard">
                <h2>Prescription Queue</h2>
                <p class="pendingText">Pending and Ready prescriptions</p>
                <div class="queueFilter">

                    <button class="activeFilter" data-filter="All">
                        All
                    </button>

                    <button data-filter="Pending">
                        Pending
                    </button>

                    <button data-filter="Ready">
                        Ready
                    </button>

                </div>

                <div class="pendingList">

                <?php

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $key = "P".$row['prescriptionID'];

                        $patients[$key] = array(

                            "pharmacistNote" => $row["note"],

                            "status" => $row['status'],

                            "prescriptionID" => $row["prescriptionID"],

                            "name" => $row['patientName'],

                            "userID" => $row['patientID'],

                            "queue" => "Q".$row['queueNo'],

                            "doctor" => $row['doctorName'],

                            "allergy" => empty($row['allergy']) ? "No Known Allergy" : $row['allergy'],

                            "chronicCondition" => empty($row['chronicCondition']) ? "-" : $row['chronicCondition'],

                            "currentMed" => empty($row['currentMed']) ? "-" : $row['currentMed'],

                            "items" => array()

                        );

                ?>

                <div class="queueBox"
                    data-status="<?php echo $row['status']; ?>">

                    <div>

                        <h3><?php echo "Q".$row['queueNo']; ?></h3>

                        <p><?php echo $row['patientName']; ?></p>

                        <span class="<?php echo strtolower($row['status']); ?>Status">
                            <?php echo $row['status']; ?>
                        </span>

                    </div>

                    <button
                        class="viewBtn"
                        data-queue="<?php echo $key; ?>">
                        View
                    </button>

                </div>

                <?php

                    }
                    while ($item = $itemResult->fetch_assoc()) {
                            $key = "P".$item["prescriptionID"];

                            if (isset($patients[$key])) {
                                $patients[$key]["items"][] = array(

                                    "prescriptionItemID" => $item["prescriptionItemID"],

                                    "medicineID" => $item["medicineID"],

                                    "medicineName" => $item["medicineName"],

                                    "stockQuantity" => $item["stockQuantity"],

                                    "quantity" => $item["quantity"],

                                    "dosage" => $item["dosage"],

                                    "frequency" => $item["frequency"],

                                    "duration" => $item["duration"],

                                    "instruction" => $item["instructions"]

                                );
                            }
                        }
                }
                else {
                    echo "<p class='recentText'>No prescription found.</p>";
                }

                ?>

                </div>
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

                    <div class="workspaceTabs">
                        <button type="button" class="overviewTab activeTab">Overview</button>
                        <button type="button" class="visitsTab">Visits</button>
                        <button type="button" class="prescriptionTab">Prescription</button>
                    </div>

                    <div class="overviewPanel">

                        <div class="medicalBlock">
                            <h3>Allergies</h3>
                            <p class="overviewAllergyInfo">-</p>
                        </div>

                        <div class="medicalBlock">
                            <h3>Chronic Disease</h3>
                            <p class="overviewChronicCondition">-</p>
                        </div>

                        <div class="medicalBlock">
                            <h3>Medicine</h3>
                            <p class="overviewCurrentMed">-</p>
                        </div>

                    </div>

                    <div class="visitsPanel" style="display:none;">

                        <div class="medicalBlock">
                            <h3>Prescription History</h3>

                            <table class="historyTable">
                                <tr>
                                    <th>Queue No.</th>
                                    <th>Date & Time</th>
                                    <th>Doctor</th>
                                    <th>Medicine</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                </tr>

                                <tbody id="workspaceVisitsHistory">
                                    <tr>
                                        <td colspan="6">Please select a prescription to view history.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                        <div class="prescriptionPanel" style="display:none;">

                            <!-- SAFETY CHECK -->
                            <div class="medicalBlock">
                                <h3>Safety Check</h3>

                                <label>
                                    <input type="checkbox">
                                    Allergy Checked
                                </label>

                                <label>
                                    <input type="checkbox">
                                    Prescription Checked
                                </label>

                                <label>
                                    <input type="checkbox">
                                    Allergy Checked
                                </label>
                            </div>

                            

                        <!-- PRESCRIPTION TABLE -->
                        <div class="medicalBlock">

                            <h3>Prescription List</h3>

                            <table class="historyTable">

                                <thead>

                                    <tr>

                                        <th>Medicine</th>
                                        <th>Quantity</th>
                                        <th>Dosage</th>
                                        <th>Frequency</th>
                                        <th>Duration</th>
                                        <th>Instruction</th>
                                        <th>Action</th>

                                    </tr>

                                </thead>

                                <tbody id="prescriptionListTable">

                                </tbody>

                            </table>

                            <div class="prescriptionNoteSection">

                                <label for="pharmacistNote">

                                    Pharmacist Note

                                </label>

                                <textarea
                                    id="pharmacistNote"
                                    class="pharmacistNote"
                                    name="pharmacistNote"
                                    placeholder="Optional note..."></textarea>

                            </div>

                        </div>

                            <!-- ACTION BUTTONS -->

                            <div class="buttonArea">

                                <form method="POST" id="workspaceForm">

                                    <input type="hidden" name="prescriptionID" id="workspacePrescriptionID">

                                    <input type="hidden" name="action" id="workspaceAction">

                                    <button
                                        type="button"
                                        class="readyBtn">
                                        Mark As Ready
                                    </button>

                                    <button
                                        type="button"
                                        class="dispenseBtn">
                                        Dispense
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                <div id="patientRecordView" style="display:none;"></div>

            </article>
        </div>

    </section>

    <div class="messagePopup">
        <p class="messageText"></p>
        <button class="okBtn">OK</button>
    </div>

    <div class="stockPopup" id="prescriptionPopup">

        <div class="stockPopupContent prescriptionPopupContent">

            <button type="button" class="closePrescriptionBtn closePrescriptionBtn">
                &times;
            </button>

            <h2>Edit Prescription</h2>

            <form
                id="editPrescriptionForm"
                method="POST">

                <input
                type="hidden"
                name="queueKey"
                id="popupQueueKey">

                <input type="hidden" id="popupPrescriptionItemID" name="prescriptionItemID">

                <label>Medicine</label>

                <select
                        id="popupMedicine"
                        name="medicineID">

                    <?php foreach ($medicineList as $medicine) { ?>

                        <option value="<?php echo $medicine['medicineID']; ?>">

                            <?php echo $medicine['medicineName']; ?>

                        </option>

                    <?php } ?>

                </select>

                <div class="popupStockBox">
                    <span>Current Stock</span>

                    <strong id="popupCurrentStock"></strong>
                </div>

                <label>Quantity</label>
                <input
                type="number"
                id="popupQuantity"
                name="quantity">

                <p class="popupRemaining">
                    Remaining Stock:
                    <span id="popupRemainingStock">-</span>
                </p>

                <input
                type="text"
                id="popupDosage"
                name="dosage">

                <input
                type="text"
                id="popupFrequency"
                name="frequency">

                <input
                type="text"
                id="popupDuration"
                name="duration">

                <textarea
                id="popupInstruction"
                name="instruction"></textarea>

                <button
                        type="submit"
                        name="savePrescription"
                        id="savePopupBtn" class="saveStockBtn">

                    Save Changes

                </button>

            </form>

        </div>

    </div>

    <script>
        const patientsData = <?php echo json_encode($patients); ?>;
        const searchPatientsData = <?php echo json_encode($searchPatients); ?>;
        const patientRecordsData = <?php echo json_encode($patientRecords); ?>;
        const medicineListData = <?php echo json_encode($medicineList); ?>;
    </script>

    <script>

    const reopenQueue =
    "<?php echo $_GET['queue'] ?? ''; ?>";

    const workspaceError =
    "<?php echo $_GET['error'] ?? ''; ?>";

    </script>

    <script src="js/pharmacist.js"></script>
    <script src="js/workspace.js"></script>

</body>
</html>

<?php
$conn->close();
?>