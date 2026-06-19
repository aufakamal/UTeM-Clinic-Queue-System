<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Information</title>
    <link rel="stylesheet" href="medicalCondition.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>

    <div class="main">

        <div class="left-sec">
            <!-- <a href="mainPage.php" class="back">
                <img src="loginRegisterImage/backIconDark.png" alt="Back">
            </a> -->

            <h2>
                Welcome To <br>
                UTeM's PKU <br>
                Digital Clinic <br>
                Queue
            </h2>
        </div>

        <div class="right-sec">

            <h2>REGISTER</h2>

            <p class="description">Do you have any allergies or existing medical conditions? If yes, please specify. You can edit or change this later in your profile.</p>

            <form>

                <label>Allergy</label>
                <textarea rows="4"></textarea>

                <label>Chronic Disease</label>
                <textarea rows="4"></textarea>

                <label>Current Medication</label>
                <textarea rows="4"></textarea>

                <div class="button-group">
                    <a href="register.php" class="back-button">BACK</a>
                    <a href="login.php" class="register-button">REGISTER</a>
                </div>

                <p class="login-link">
                    Already have an account?
                    <a href="login.php">Log In</a>
                </p>

            </form>

        </div>

    </div>

</div>
<script src="loginRegister.js"></script> 
</body>
</html>