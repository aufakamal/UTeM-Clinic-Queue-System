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

            <p class="description">
                Please select your allergies or existing medical conditions.
                You can edit or change this later in your profile.
            </p>

            <form action="login.php" method="post">

                <div class="medical-grid">

                    <div class="medical-card">
                        <h3>Allergy</h3>

                        <label><input type="checkbox" name="allergy[]" value="None"> None</label>
                        <label><input type="checkbox" name="allergy[]" value="Peanuts"> Peanuts</label>
                        <label><input type="checkbox" name="allergy[]" value="Seafood"> Seafood</label>
                        <label><input type="checkbox" name="allergy[]" value="Dust"> Dust</label>
                        <label><input type="checkbox" name="allergy[]" value="Penicillin"> Penicillin</label>
                        <label><input type="checkbox" name="allergy[]" value="Others"> Others</label>
                    </div>

                    <div class="medical-card">
                        <h3>Chronic Disease</h3>

                        <label><input type="checkbox" name="chronicDisease[]" value="None"> None</label>
                        <label><input type="checkbox" name="chronicDisease[]" value="Asthma"> Asthma</label>
                        <label><input type="checkbox" name="chronicDisease[]" value="Diabetes"> Diabetes</label>
                        <label><input type="checkbox" name="chronicDisease[]" value="Hypertension"> Hypertension</label>
                        <label><input type="checkbox" name="chronicDisease[]" value="Heart Disease"> Heart Disease</label>
                        <label><input type="checkbox" name="chronicDisease[]" value="Others"> Others</label>
                    </div>

                </div>

                <div class="medical-card medication-card">
                    <h3>Current Medication</h3>

                    <label><input type="checkbox" name="currentMed[]" value="None"> None</label>
                    <label><input type="checkbox" name="currentMed[]" value="Ventolin"> Ventolin</label>
                    <label><input type="checkbox" name="currentMed[]" value="Metformin"> Metformin</label>
                    <label><input type="checkbox" name="currentMed[]" value="Amlodipine"> Amlodipine</label>
                    <label><input type="checkbox" name="currentMed[]" value="Painkiller"> Painkiller</label>
                    <label><input type="checkbox" name="currentMed[]" value="Others"> Others</label>
                </div>

                <div class="button-group">
                    <a href="register.php" class="back-button">BACK</a>
                    <button type="submit" class="register-button">REGISTER</button>
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