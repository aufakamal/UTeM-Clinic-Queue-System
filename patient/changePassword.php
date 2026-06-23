<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="inc/patient.css">
</head>

<body>

    <?php include('inc/patient_header.php'); ?>

    <div class="profilePage">

        <div class="profileTitle">
            <!-- <a href="editProfilePatient.html">
                <img src="../images/backIconDark.png" class="backIcon" alt="Back">
            </a> -->

            <h2>Change Password</h2>
        </div>

        <div class="changePasswordCard">

            <img src="../images/changePasswordIcon.png" class="changePasswordIcon" alt="Change Password">

            <p>Please enter your new password</p>

            <form>
                <label>Old Password</label>
                <input type="password">

                <label>New Password</label>
                <input type="password">

                <label>Confirm Password</label>
                <input type="password">

                <div class="buttonGroup">
                    <a href="profilePatient.php" class="saveBtn secondaryBtn">BACK</a>

                    <a href="changePasswordSuccess.php" class="saveBtn">
                        CHANGE PASSWORD
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php include('inc/patient_footer.php'); ?>
</body>
</html>