<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Updated</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="inc/patient.css">
</head>

<body>
    <?php include('inc/patient_header.php'); ?>
    <div class="profilePage">

        <div class="profileTitle">
            <!-- <a href="profilePatient.html">
                <img src="../images/backIconDark.png" class="backIcon" alt="Back">
            </a> -->

            <h2>Edit Profile</h2>
        </div>

        <div class="messageCard">

            <img src="../images/tickIcon.png" alt="Tick Icon"class="tickIcon">

            <p class="successText">Your profile has been updated successfully</p>

            <a href="profilePatient.php" class="profileBtn">BACK TO PROFILE PAGE</a>
        </div>
    </div>
    <?php include('inc/patient_footer.php'); ?>
</body>
</html>