<?php
session_start();
include("../dbconnect.php");
require_once("../mail/mailer.php");

if (!isset($_SESSION['registerData'])) {
    header("Location: register.php");
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

$data = $_SESSION['registerData'];

$userID = trim($data['userID']);
$fullName = trim($data['fullName']);
$gender = trim($data['gender']);
$email = strtolower(trim($data['email']));
$phoneNo = trim($data['phoneNo']);
$address = trim($data['address']);
$password = password_hash($data['password'], PASSWORD_DEFAULT);
$registerRole = $data['registerRole'];

if ($registerRole == "student") {
    if (!str_ends_with($email, "@student.utem.edu.my")) {
        echo "<script>
                alert('Students must use UTeM student email ending with @student.utem.edu.my.');
                window.location.href='register.php';
              </script>";
        exit();
    }
}

if ($registerRole == "staff") {
    if (!str_ends_with($email, "@utem.edu.my") || str_ends_with($email, "@student.utem.edu.my")) {
        echo "<script>
                alert('Staff must use UTeM staff email ending with @utem.edu.my.');
                window.location.href='register.php';
              </script>";
        exit();
    }
}

$emailVerified = 0;
$verificationToken = bin2hex(random_bytes(32));

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

$bloodType = $_POST['bloodType'];

$allergy = combineMedicalOptions($_POST['allergy'] ?? [], $_POST['allergyOther'] ?? "");
$chronicCondition = combineMedicalOptions($_POST['chronicCondition'] ?? [], $_POST['chronicConditionOther'] ?? "");
$currentMed = combineMedicalOptions($_POST['currentMed'] ?? [], $_POST['currentMedOther'] ?? "");

$emergencyContactName = $_POST['emergencyContactName'];
$emergencyContactPhone = $_POST['emergencyContactPhone'];

$roleID = 4;
$patientType = ($registerRole == "student") ? "Student" : "Staff";

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

    $sqlRole = "INSERT INTO user_role (userID, roleID) VALUES (?, ?)";
    $stmtRole = $conn->prepare($sqlRole);
    $stmtRole->bind_param("si", $userID, $roleID);
    $stmtRole->execute();

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