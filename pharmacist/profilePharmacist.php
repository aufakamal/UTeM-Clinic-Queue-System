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
            p.licenseNo
        FROM user u
        JOIN pharmacist_profile p
        ON u.userID = p.userID
        WHERE u.userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$pharmacist = $result->fetch_assoc();

if (!$pharmacist) {
    echo "<script>
            alert('Pharmacist profile not found.');
            window.location.href='workspace.html';
          </script>";
    exit();
}

$dobDisplay = "";
if (!empty($pharmacist['dateOfBirth'])) {
    $dobDisplay = date("d/m/Y", strtotime($pharmacist['dateOfBirth']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Pharmacist Page</title>
    <link rel="stylesheet" href="../shared/profile.css">
</head>

<body>

<div class="profilePage">

    <section class="profileTitle">
        <h2>My Profile</h2>
    </section>

    <section class="profileCard">

        <div class="profileHeader">
            <img src="../images/profileIconDark.png" alt="Profile Picture" class="profileImage">

            <div class="userInfo">
                <h2><?php echo htmlspecialchars($pharmacist['fullName']); ?></h2>
                <p><?php echo htmlspecialchars($pharmacist['email']); ?></p>
            </div>

            <div class="profileActions">
                <span class="roleName">Pharmacist</span>
                <span class="roleName"><?php echo htmlspecialchars($pharmacist['gender']); ?></span>
                <a href="editProfilePharmacist.php" class="editBtn">Change Profile Information</a>
            </div>
        </div>

        <div class="profileContent profileTwoColumn">

            <div class="leftInfo profileSection">

                <h3>Personal Information</h3>

                <label>ID</label>
                <input type="text" value="<?php echo htmlspecialchars($pharmacist['userID']); ?>" readonly>

                <label>Date of Birth</label>
                <input type="text" value="<?php echo htmlspecialchars($dobDisplay); ?>" readonly>

                <label>Phone Number</label>
                <input type="text" value="<?php echo htmlspecialchars($pharmacist['phoneNo']); ?>" readonly>

                <label>Address</label>
                <input type="text" value="<?php echo htmlspecialchars($pharmacist['address'] ?? 'Not set'); ?>" readonly>

                <label>Password</label>
                <input type="password" value="********" readonly>

            </div>

            <div class="rightInfo profileSection medicalSection">

                <h3>Professional Information</h3>

                <label>License Number</label>
                <input type="text" value="<?php echo htmlspecialchars($pharmacist['licenseNo'] ?? 'Not set'); ?>" readonly>

            </div>

        </div>

    </section>

</div>

</body>
</html>