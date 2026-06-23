<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile Page</title>
    <link rel="stylesheet" href="../shared/profile.css">
    <link rel="stylesheet" href="inc/patient.css">
</head>

<body>
    <?php include('inc/patient_header.php'); ?>
    <div class="profilePage">

        <section class="profileTitle">
            <!-- <a href="profilePatient.php">
                <img src="../images/backIconDark.png" class="backIcon" alt="Back">
            </a> -->

            <h2>Edit Profile</h2>
        </section>

        <div class="profileCard">

            <div class="editHeader">
                <img src="../images/profileIconDark.png" class="editProfileImage" alt="Profile Icon">
            </div>

            <form class="editForm">
                <div class="nameRow">
                    <div>
                        <label>First Name</label>
                        <input type="text" value="Ailene">
                    </div>

                    <div>
                        <label>Last Name</label>
                        <input type="text" value="Farhana">
                    </div>
                </div>

                <label>Email</label>
                <input type="email" value="afarhana@gmail.com">

                <label>ID</label>
                <input type="text" value="ST03295">

                <label>Phone Number</label>
                <input type="text" value="0166681881">

                <label>Password</label>
                <input type="password" value="123456789">

                <a href="changePassword.php" class="changePasswordLink">Change password</a>

                <h3>Medical Information</h3>

                <label>Blood Type</label>
                <input type="text" value="O">

                <label>Allergies</label>
                <input type="text" value="Peanuts">

                <label>Chronic Illness</label>
                <input type="text" value="Asthma">

                <label>Medication</label>
                <input type="text" value="Inhaler">

                <div class="buttonGroup">
                    <a href="profilePatient.php" class="saveBtn secondaryBtn">BACK</a>

                    <a href="updateProfileSuccess.php" class="saveBtn">SAVE</a>
                </div>
            </form>
        </div>
    </div>
    <?php include('inc/patient_footer.php'); ?>
</body>
</html>