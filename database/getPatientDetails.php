<?php

include "database.php";

header("Content-Type: application/json");

if (!isset($_GET["userID"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing user ID."
    ]);
    exit;
}

$userID = $_GET["userID"];

$sql = "
SELECT
    u.userID,
    u.fullName,
    u.gender,
    u.dateOfBirth,
    u.address,
    u.email,
    u.phoneNo,
    p.patientType,
    p.allergy,
    p.chronicCondition,
    p.currentMed,
    p.bloodType,
    p.emergencyContactName,
    p.emergencyContactPhone
FROM user u
LEFT JOIN patient_profile p ON u.userID = p.userID
WHERE u.userID = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Patient not found."
    ]);
    exit;
}

$data = $result->fetch_assoc();
$data["success"] = true;

echo json_encode($data);

?>