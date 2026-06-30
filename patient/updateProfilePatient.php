<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: editProfilePatient.php");
    exit();
}

function combineMedicalOptions($options, $otherText) {
    $final = [];

    if (isset($options) && is_array($options)) {
        foreach ($options as $option) {
            if ($option != "Others") {
                $final[] = $option;
            }
        }
    }

    $otherText = trim($otherText ?? "");

    if ($otherText != "") {
        $final[] = $otherText;
    }

    return count($final) > 0 ? implode(", ", $final) : "";
}

$userID = $_SESSION['userID'];

$fullName = trim($_POST['fullName']);
$gender = trim($_POST['gender']);
$email = trim($_POST['email']);
$phoneNo = trim($_POST['phoneNo']);
$address = trim($_POST['address']);

$dobInput = trim($_POST['dateOfBirth']);
$dobObject = DateTime::createFromFormat('d/m/Y', $dobInput);

if ($dobObject == false) {
    echo "<script>
            alert('Invalid date format. Please use dd/mm/yyyy.');
            window.location.href='editProfilePatient.php';
          </script>";
    exit();
}

$dateOfBirth = $dobObject->format('Y-m-d');

$bloodType = $_POST['bloodType'];

$allergy = combineMedicalOptions($_POST['allergy'] ?? [], $_POST['allergyOther'] ?? "");
$chronicCondition = combineMedicalOptions($_POST['chronicCondition'] ?? [], $_POST['chronicConditionOther'] ?? "");
$currentMed = combineMedicalOptions($_POST['currentMed'] ?? [], $_POST['currentMedOther'] ?? "");

$emergencyContactName = trim($_POST['emergencyContactName']);
$emergencyContactPhone = trim($_POST['emergencyContactPhone']);

$sqlUser = "UPDATE user
            SET fullName = ?,
<<<<<<< HEAD
=======
                gender = ?,
                dateOfBirth = ?,
>>>>>>> origin/master
                email = ?,
                phoneNo = ?,
                address = ?
            WHERE userID = ?";

$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param(
<<<<<<< HEAD
    "sssss",
    $fullName,
=======
    "sssssss",
    $fullName,
    $gender,
    $dateOfBirth,
>>>>>>> origin/master
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