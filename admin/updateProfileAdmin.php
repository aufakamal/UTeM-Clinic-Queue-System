<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: editProfileAdmin.php");
    exit();
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
            window.location.href='editProfileAdmin.php';
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

if ($userUpdated) {
    $_SESSION['fullName'] = $fullName;

    echo "<script>
            alert('Profile updated successfully.');
            window.location.href='profileAdmin.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to update profile.');
            window.location.href='editProfileAdmin.php';
          </script>";
}
?>