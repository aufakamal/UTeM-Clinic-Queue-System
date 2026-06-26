<?php
session_start();
include("../login_register/dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: editProfilePatient.php");
    exit();
}

$userID = $_SESSION['userID'];

$fullName = trim($_POST['fullName']);
$email = trim($_POST['email']);
$phoneNo = trim($_POST['phoneNo']);
$address = trim($_POST['address']);

$bloodType = $_POST['bloodType'];

$allergy = isset($_POST['allergy']) ? implode(", ", $_POST['allergy']) : "";
$chronicCondition = isset($_POST['chronicCondition']) ? implode(", ", $_POST['chronicCondition']) : "";
$currentMed = isset($_POST['currentMed']) ? implode(", ", $_POST['currentMed']) : "";

$emergencyContactName = trim($_POST['emergencyContactName']);
$emergencyContactPhone = trim($_POST['emergencyContactPhone']);

$sqlUser = "UPDATE user
            SET fullName = ?,
                email = ?,
                phoneNo = ?,
                address = ?
            WHERE userID = ?";

$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param(
    "sssss",
    $fullName,
    $email,
    $phoneNo,
    $address,
    $userID
);

$userUpdated = $stmtUser->execute();

$sqlPatient = "UPDATE patient_profile
               SET bloodType = ?,
                   allergy = ?,
                   chronicCondition = ?,
                   currentMed = ?,
                   emergencyContactName = ?,
                   emergencyContactPhone = ?
               WHERE userID = ?";

$stmtPatient = $conn->prepare($sqlPatient);
$stmtPatient->bind_param(
    "sssssss",
    $bloodType,
    $allergy,
    $chronicCondition,
    $currentMed,
    $emergencyContactName,
    $emergencyContactPhone,
    $userID
);

$patientUpdated = $stmtPatient->execute();

if ($userUpdated && $patientUpdated) {
    $_SESSION['fullName'] = $fullName;

    echo "<script>
            alert('Profile updated successfully.');
            window.location.href='profilePatient.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to update profile.');
            window.location.href='editProfilePatient.php';
          </script>";
}
?>