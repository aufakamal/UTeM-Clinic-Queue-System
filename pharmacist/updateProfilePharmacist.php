<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: editProfilePharmacist.php");
    exit();
}

$userID = $_SESSION['userID'];

$fullName = trim($_POST['fullName']);
$gender = trim($_POST['gender']);
$email = trim($_POST['email']);
$phoneNo = trim($_POST['phoneNo']);
$address = trim($_POST['address']);
$licenseNo = trim($_POST['licenseNo']);

$dobInput = trim($_POST['dateOfBirth']);
$dobObject = DateTime::createFromFormat('d/m/Y', $dobInput);

if ($dobObject == false) {
    echo "<script>
            alert('Invalid date format. Please use dd/mm/yyyy.');
            window.location.href='editProfilePharmacist.php';
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

$sqlPharmacist = "UPDATE pharmacist_profile
                  SET licenseNo = ?
                  WHERE userID = ?";

$stmtPharmacist = $conn->prepare($sqlPharmacist);
$stmtPharmacist->bind_param("ss", $licenseNo, $userID);

$pharmacistUpdated = $stmtPharmacist->execute();

if ($userUpdated && $pharmacistUpdated) {
    $_SESSION['fullName'] = $fullName;

    echo "<script>
            alert('Profile updated successfully.');
            window.location.href='profilePharmacist.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to update profile.');
            window.location.href='editProfilePharmacist.php';
          </script>";
}
?>