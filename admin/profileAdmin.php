<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT
            userID,
            fullName,
            gender,
            dateOfBirth,
            address,
            email,
            phoneNo
        FROM user
        WHERE userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo "<script>
            alert('Admin profile not found.');
            window.location.href='admin-dashboard.php';
          </script>";
    exit();
}

$dobDisplay = "";
if (!empty($admin['dateOfBirth'])) {
    $dobDisplay = date("d/m/Y", strtotime($admin['dateOfBirth']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Admin Page</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<?php include 'inc/admin_header.php'; ?>

<div class="profilePage">

    <section class="profileTitle">
        <h2>My Profile</h2>
    </section>

    <section class="profileCard">

        <div class="profileHeader">
            <img src="../images/profileIconDark.png" alt="Profile Picture" class="profileImage">

            <div class="userInfo">
                <h2><?php echo htmlspecialchars($admin['fullName']); ?></h2>
                <p><?php echo htmlspecialchars($admin['email']); ?></p>
            </div>

            <div class="profileActions">
                <span class="roleName">Admin</span>
                <span class="roleName"><?php echo htmlspecialchars($admin['gender']); ?></span>
                <a href="editProfileAdmin.php" class="editBtn">Change Profile Information</a>
            </div>
        </div>

        <div class="profileContent profileTwoColumn">

            <div class="leftInfo profileSection">

                <h3>Personal Information</h3>

                <label>ID</label>
                <input type="text" value="<?php echo htmlspecialchars($admin['userID']); ?>" readonly>

                <label>Date of Birth</label>
                <input type="text" value="<?php echo htmlspecialchars($dobDisplay); ?>" readonly>

                <label>Phone Number</label>
                <input type="text" value="<?php echo htmlspecialchars($admin['phoneNo']); ?>" readonly>

                <label>Address</label>
                <input type="text" value="<?php echo htmlspecialchars($admin['address'] ?? 'Not set'); ?>" readonly>

                <label>Password</label>
                <input type="password" value="********" readonly>

            </div>

            <div class="rightInfo profileSection medicalSection">

                <h3>Account Information</h3>

                <label>Role</label>
                <input type="text" value="Admin" readonly>

                <label>Email</label>
                <input type="text" value="<?php echo htmlspecialchars($admin['email']); ?>" readonly>

            </div>

        </div>

    </section>

</div>

</body>
</html>
