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
        <!-- <a href="dashboard.php">
            <img src="../images/backIconDark.png" alt="Back" class="backIcon" alt="Back">
        </a> -->

        <h2>My Profile</h2>
    </section>

<section class="profileCard">
    <div class="profileHeader">
        <img src="../images/profileIconDark.png" alt="Profile Picture" class="profileImage">

        <div class="userInfo">
            <h2>Ailene Farhana</h2>
            <p>afarhana@gmail.com</p>
        </div>

        <div class="profileActions">
            <span class="roleName">Patient</span>
            
            <a href="editProfilePatient.php" class="editBtn">Change Profile Information</a>
        </div>

    </div>

    <div class="profileContent">

        <div class="leftInfo">

            <label>ID</label>
            <input type="text" value="ST03295" readonly>

            <label>Phone Number</label>
            <input type="text" value="01133752068" readonly>

            <label>Password</label>
            <input type="password" value="12345678" readonly>

        </div>

        <div class="rightInfo">

            <h3>Medical Information</h3>

            <label>Blood Type</label>
            <input type="text" value="O" readonly>

            <label>Allergies</label>
            <input type="text" value="Peanuts" readonly>

            <label>Chronic Illness</label>
            <input type="text" value="Asthma" readonly>

        </div>

    </div>

</section>
</div>
<?php include('inc/patient_footer.php'); ?>
</body>
</html>