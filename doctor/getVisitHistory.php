<?php

include('../dbconnect.php');

header("Content-Type: application/json");

$patient_id = $_GET['patient_id'] ?? "";

if ($patient_id === "") {
    echo json_encode([
        "success" => false,
        "message" => "Patient ID is required."
    ]);
    exit;
}

$sql = "
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
    INNER JOIN attendance a 
        ON ap.appointmentID = a.appointmentID
    INNER JOIN queue q 
        ON a.attendanceID = q.attendanceID
    INNER JOIN consultation c 
        ON q.queueID = c.queueID
    INNER JOIN user u 
        ON c.doctorUserID = u.userID
    LEFT JOIN prescription p 
        ON p.consultationID = c.consultationID
    LEFT JOIN prescription_item pi 
        ON p.prescriptionID = pi.prescriptionID
    LEFT JOIN medicine m 
        ON pi.medicineID = m.medicineID
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

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $patient_id);
$stmt->execute();

$result = $stmt->get_result();

$visits = [];

while ($row = $result->fetch_assoc()) {
    $visits[] = [
        "visitID" => $row["visitID"],
        "doctor_name" => $row["doctor_name"],
        "reason" => $row["reason"],
        "findings" => $row["findings"],
        "diagnosis" => $row["diagnosis"],
        "treatment" => $row["treatment"],
        "prescription_text" => $row["prescription_text"],
        "visitDate" => $row["visitDate"]
    ];
}

echo json_encode([
    "success" => true,
    "visits" => $visits
]);

?>