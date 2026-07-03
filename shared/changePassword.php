<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$roleID = $_SESSION['roleID'] ?? "";

if ($roleID == 1) {
    $backLink = "../admin/profileAdmin.php";
} else if ($roleID == 2) {
    $backLink = "../doctor/profileDoctor.php";
} else if ($roleID == 3) {
    $backLink = "../pharmacist/profilePharmacist.php";
} else {
    $backLink = "../patient/editProfilePatient.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>

<div class="profilePage">

    <section class="profileTitle">
        <h2>Change Password</h2>
    </section>

    <div class="changePasswordCard">

        <form action="processChangePassword.php" method="POST">

            <label>Current Password</label>
            <input type="password" name="currentPassword" required>

            <label>New Password</label>
            <input type="password" name="newPassword" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirmPassword" required>

            <div class="buttonGroup">
                <a href="<?php echo $backLink; ?>" class="backBtn">BACK</a>
                <button type="submit" class="saveBtn">SEND VERIFICATION</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>