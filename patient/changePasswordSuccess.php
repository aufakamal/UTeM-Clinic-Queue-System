<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="inc/patient.css">
</head>

<body>
    <?php include('inc/patient_header.php'); ?>
    <div class="profilePage">

        <div class="profileTitle">
            <!-- <a href="changePassword.php">
                <img src="../images/backIconDark.png" alt="Back"class="backIcon">
            </a> -->

            <h2>Change Password</h2>
        </div>

        <div class="messageCard">

            <img src="../images/tickIcon.png" alt="Success" class="tickIcon">

            <p class="successText">Your password has been successfully reset. Please log in using your new password.</p>

            <a href="profilePatient.php" class="profileBtn">BACK TO PROFILE PAGE</a>

        </div>

    </div>
    <?php include('inc/patient_footer.php'); ?>
</body>
</html>