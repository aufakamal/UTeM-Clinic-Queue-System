<?php

include "database.php";

header("Content-Type: application/json");

if (!isset($_GET["userID"]) || !isset($_GET["roleName"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing user ID or role."
    ]);
    exit;
}

$userID = $_GET["userID"];
$roleName = $_GET["roleName"];

$sql = "
SELECT
    u.userID,
    u.fullName,
    u.gender,
    u.dateOfBirth,
    u.address,
    u.email,
    u.phoneNo,
    r.roleName
FROM user u
JOIN user_role ur ON u.userID = ur.userID
JOIN role r ON ur.roleID = r.roleID
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
        "message" => "User not found."
    ]);
    exit;
}

$data = $result->fetch_assoc();

if ($roleName === "Patient") {
    $sql = "
    SELECT patientType, allergy, chronicCondition, currentMed,
           bloodType, emergencyContactName, emergencyContactPhone
    FROM patient_profile
    WHERE userID = ?
    LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();

    if ($profile) {
        $data = array_merge($data, $profile);
    }
}

if ($roleName === "Doctor") {
    $sql = "
    SELECT docLicenseNo, specialization, roomNo
    FROM doctor_profile
    WHERE userID = ?
    LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();

    if ($profile) {
        $data = array_merge($data, $profile);
    }
}

if ($roleName === "Pharmacist") {
    $sql = "
    SELECT licenseNo
    FROM pharmacist_profile
    WHERE userID = ?
    LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();

    if ($profile) {
        $data = array_merge($data, $profile);
    }
}

$data["success"] = true;

echo json_encode($data);

?>