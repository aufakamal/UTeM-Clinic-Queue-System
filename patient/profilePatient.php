<?php 
session_start();
include("../login_register/dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT 
            u.userID,
            u.fullName,
            u.email,
            u.phoneNo,
            p.bloodType,
            p.allergy,
            p.chronicCondition,
            p.currentMed,
            p.emergencyContactName,
            p.emergencyContactPhone
        FROM user u
        JOIN patient_profile p
        ON u.userID = p.userID
        WHERE u.userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    echo "<script>
            alert('Patient profile not found.');
            window.location.href='dashboard.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Patient Page</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="inc/patient.css">
</head>

<body>

<?php include('inc/patient_header.php'); ?>

<div class="profilePage">

    <section class="profileTitle">
        <h2>My Profile</h2>
    </section>

    <section class="profileCard">

        <div class="profileHeader">
            <img src="patientImages/profileIconDark.png" alt="Profile Picture" class="profileImage">

            <div class="userInfo">
                <h2><?php echo htmlspecialchars($patient['fullName']); ?></h2>
                <p><?php echo htmlspecialchars($patient['email']); ?></p>
            </div>

            <div class="profileActions">
                <span class="roleName">Patient</span>
                <a href="editProfilePatient.php" class="editBtn">Change Profile Information</a>
            </div>
        </div>

        <div class="profileContent profileTwoColumn">

            <div class="leftInfo profileSection">

                <h3>Personal Information</h3>

                <label>ID</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['userID']); ?>" readonly>

                <label>Phone Number</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['phoneNo']); ?>" readonly>

                <label>Password</label>
                <input type="password" value="********" readonly>

                <div class="sectionDivider"></div>

                <h3>Emergency Contact</h3>

                <label>Emergency Contact Name</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['emergencyContactName'] ?? 'Not set'); ?>" readonly>

                <label>Emergency Contact Phone</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['emergencyContactPhone'] ?? 'Not set'); ?>" readonly>

            </div>

            <div class="rightInfo profileSection medicalSection">

                <h3>Medical Information</h3>

                <label>Blood Type</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['bloodType'] ?? 'Not set'); ?>" readonly>

                <label>Allergies</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['allergy'] ?? 'None'); ?>" readonly>

                <label>Chronic Illness</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['chronicCondition'] ?? 'None'); ?>" readonly>

                <label>Current Medication</label>
                <input type="text" value="<?php echo htmlspecialchars($patient['currentMed'] ?? 'None'); ?>" readonly>

            </div>

        </div>

    </section>

</div>

<?php include('inc/patient_footer.php'); ?>

</body>
</html>