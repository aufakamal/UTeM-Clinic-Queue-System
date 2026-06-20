<?php
session_start();
include("dbconnect.php");
require_once("../mail/mailer.php");

if (!isset($_SESSION['registerData'])) {
    header("Location: register.php");
    exit();
}

$data = $_SESSION['registerData'];

$userID = $data['userID'];
$fullName = $data['fullName'];
$gender = $data['gender'];
$email = $data['email'];
$phoneNo = $data['phoneNo'];
$address = $data['address'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$registerRole = $data['registerRole'];

$emailVerified = 0;
$verificationToken = bin2hex(random_bytes(32));

// Convert DOB from dd/mm/yyyy to yyyy-mm-dd
$dobInput = $data['dateOfBirth'];
$dobObject = DateTime::createFromFormat('d/m/Y', $dobInput);

if ($dobObject == false) {
    echo "<script>
            alert('Invalid date format. Please use dd/mm/yyyy.');
            window.location.href='register.php';
          </script>";
    exit();
}

$dateOfBirth = $dobObject->format('Y-m-d');

// Medical condition data
$bloodType = $_POST['bloodType'];

$allergy = isset($_POST['allergy'])
    ? implode(", ", $_POST['allergy'])
    : NULL;

$chronicCondition = isset($_POST['chronicCondition'])
    ? implode(", ", $_POST['chronicCondition'])
    : NULL;

$currentMed = isset($_POST['currentMed'])
    ? implode(", ", $_POST['currentMed'])
    : NULL;

$emergencyContactName = $_POST['emergencyContactName'];
$emergencyContactPhone = $_POST['emergencyContactPhone'];

// Student/Staff are both Patient role
$roleID = 4;
$patientType = ($registerRole == "student") ? "Student" : "Staff";

// Insert into user table
$sqlUser = "INSERT INTO user 
(userID, fullName, gender, dateOfBirth, address, email, phoneNo, password, email_verified, verification_token)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sqlUser);
$stmt->bind_param(
    "ssssssssis",
    $userID,
    $fullName,
    $gender,
    $dateOfBirth,
    $address,
    $email,
    $phoneNo,
    $password,
    $emailVerified,
    $verificationToken
);

if ($stmt->execute()) {

    // Insert into user_role table
    $sqlRole = "INSERT INTO user_role (userID, roleID) VALUES (?, ?)";
    $stmtRole = $conn->prepare($sqlRole);
    $stmtRole->bind_param("si", $userID, $roleID);
    $stmtRole->execute();

    // Insert into patient_profile table
    $sqlPatient = "INSERT INTO patient_profile
    (userID, patientType, allergy, chronicCondition, currentMed, bloodType, emergencyContactName, emergencyContactPhone)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtPatient = $conn->prepare($sqlPatient);
    $stmtPatient->bind_param(
        "ssssssss",
        $userID,
        $patientType,
        $allergy,
        $chronicCondition,
        $currentMed,
        $bloodType,
        $emergencyContactName,
        $emergencyContactPhone
    );

    if ($stmtPatient->execute()) {

        unset($_SESSION['registerData']);

        $emailSent = sendVerificationEmail($email, $fullName, $verificationToken);

        if ($emailSent) {
            echo "<script>
                    alert('Registration successful! Please check your email to verify your account.');
                    window.location.href='login.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Registration saved, but verification email failed to send.');
                    window.location.href='login.php';
                  </script>";
        }

    } else {
        echo "<script>
                alert('Patient profile registration failed.');
                window.location.href='register.php';
              </script>";
    }

} else {
    echo "<script>
            alert('Registration failed. User ID may already exist.');
            window.location.href='register.php';
          </script>";
}
?>