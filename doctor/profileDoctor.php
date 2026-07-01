<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT
            u.userID,
            u.fullName,
            u.gender,
            u.dateOfBirth,
            u.address,
            u.email,
            u.phoneNo,
            d.docLicenseNo,
            d.specialization,
            d.roomNo
        FROM user u
        JOIN doctor_profile d
        ON u.userID = d.userID
        WHERE u.userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    echo "<script>
            alert('Doctor profile not found.');
            window.location.href='doctorWorkspace.php';
          </script>";
    exit();
}

$dobDisplay = "";
if (!empty($doctor['dateOfBirth'])) {
    $dobDisplay = date("d/m/Y", strtotime($doctor['dateOfBirth']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Doctor Page</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="doctor.css">
    
</head>

<body>

<?php include('inc/doctor_header.php'); ?>

<div class="profilePage">

    <section class="profileTitle">
        <h2>My Profile</h2>
    </section>

    <section class="profileCard">

        <div class="profileHeader">
            <img src="../images/profileIconDark.png" alt="Profile Picture" class="profileImage">

            <div class="userInfo">
                <h2><?php echo htmlspecialchars($doctor['fullName']); ?></h2>
                <p><?php echo htmlspecialchars($doctor['email']); ?></p>
            </div>

            <div class="profileActions">
                <span class="roleName">Doctor</span>
                <span class="roleName"><?php echo htmlspecialchars($doctor['gender']); ?></span>
                <a href="editProfileDoctor.php" class="editBtn">Change Profile Information</a>
            </div>
        </div>

        <div class="profileContent profileTwoColumn">

            <div class="leftInfo profileSection">

                <h3>Personal Information</h3>

                <label>ID</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor['userID']); ?>" readonly>

                <label>Date of Birth</label>
                <input type="text" value="<?php echo htmlspecialchars($dobDisplay); ?>" readonly>

                <label>Phone Number</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor['phoneNo']); ?>" readonly>

                <label>Address</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor['address'] ?? 'Not set'); ?>" readonly>

                <label>Password</label>
                <input type="password" value="********" readonly>

            </div>

            <div class="rightInfo profileSection medicalSection">

                <h3>Professional Information</h3>

                <label>Doctor License Number</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor['docLicenseNo'] ?? 'Not set'); ?>" readonly>

                <label>Specialization</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor['specialization'] ?? 'Not set'); ?>" readonly>

                <label>Room Number</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor['roomNo'] ?? 'Not set'); ?>" readonly>

            </div>

        </div>

    </section>

</div>

</body>
</html>