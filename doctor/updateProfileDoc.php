<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: editProfileDoctor.php");
    exit();
}

$userID = $_SESSION['userID'];

$fullName = trim($_POST['fullName']);
$gender = trim($_POST['gender']);
$email = trim($_POST['email']);
$phoneNo = trim($_POST['phoneNo']);
$address = trim($_POST['address']);

$docLicenseNo = trim($_POST['docLicenseNo']);
$specialization = trim($_POST['specialization']);
$roomNo = trim($_POST['roomNo']);

$dobInput = trim($_POST['dateOfBirth']);
$dobObject = DateTime::createFromFormat('d/m/Y', $dobInput);

if ($dobObject == false) {
    echo "<script>
            alert('Invalid date format. Please use dd/mm/yyyy.');
            window.location.href='editProfileDoctor.php';
          </script>";
    exit();
}

$dateOfBirth = $dobObject->format('Y-m-d');

$sqlUser = "UPDATE user
            SET fullName = ?,
                gender = ?,
                dateOfBirth = ?,
                email = ?,
                phoneNo = ?,
                address = ?
            WHERE userID = ?";

$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param(
    "sssssss",
    $fullName,
    $gender,
    $dateOfBirth,
    $email,
    $phoneNo,
    $address,
    $userID
);

$userUpdated = $stmtUser->execute();

$sqlDoctor = "UPDATE doctor_profile
              SET docLicenseNo = ?,
                  specialization = ?,
                  roomNo = ?
              WHERE userID = ?";

$stmtDoctor = $conn->prepare($sqlDoctor);
$stmtDoctor->bind_param(
    "ssss",
    $docLicenseNo,
    $specialization,
    $roomNo,
    $userID
);

$doctorUpdated = $stmtDoctor->execute();

if ($userUpdated && $doctorUpdated) {
    $_SESSION['fullName'] = $fullName;

    echo "<script>
            alert('Profile updated successfully.');
            window.location.href='profileDoctor.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to update profile.');
            window.location.href='editProfileDoctor.php';
          </script>";
}
?>