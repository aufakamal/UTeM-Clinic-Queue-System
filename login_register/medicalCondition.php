<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['registerData'] = $_POST;
}
?>

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

    <main class="medicalMain">

        <div class="medicalIntro">
            <h1>REGISTER</h1>
            <p>
                Please select your allergies or existing medical conditions.
                You can edit or change this later in your profile.
            </p>
        </div>

        <div class="medicalFormCard">

            <form action="processRegister.php" method="post" id="medicalForm">

                <div class="medical-card bloodtype-card">
                    <h3>Blood Type</h3>

                    <select name="bloodType" required>
                        <option value="">Select blood type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="medical-grid">

                    <div class="medical-card">
                        <h3>Allergy</h3>

                        <div class="checkbox-grid">
                            <label><input type="checkbox" name="allergy[]" value="None"> None</label>
                            <label><input type="checkbox" name="allergy[]" value="Dust"> Dust</label>
                            <label><input type="checkbox" name="allergy[]" value="Peanuts"> Peanuts</label>
                            <label><input type="checkbox" name="allergy[]" value="Penicillin"> Penicillin</label>
                            <label><input type="checkbox" name="allergy[]" value="Seafood"> Seafood</label>
                            <label><input type="checkbox" name="allergy[]" value="Others" class="other-checkbox" data-target="allergyOtherBox"> Others</label>
                        </div>

                        <div id="allergyOtherBox" class="other-box">
                            <input type="text" name="allergyOther" placeholder="Please specify allergy">
                        </div>
                    </div>

                    <div class="medical-card">
                        <h3>Chronic Disease</h3>

                        <div class="checkbox-grid">
                            <label><input type="checkbox" name="chronicCondition[]" value="None"> None</label>
                            <label><input type="checkbox" name="chronicCondition[]" value="Hypertension"> Hypertension</label>
                            <label><input type="checkbox" name="chronicCondition[]" value="Asthma"> Asthma</label>
                            <label><input type="checkbox" name="chronicCondition[]" value="Heart Disease"> Heart Disease</label>
                            <label><input type="checkbox" name="chronicCondition[]" value="Diabetes"> Diabetes</label>
                            <label><input type="checkbox" name="chronicCondition[]" value="Others" class="other-checkbox" data-target="chronicOtherBox"> Others</label>
                        </div>

                        <div id="chronicOtherBox" class="other-box">
                            <input type="text" name="chronicConditionOther" placeholder="Please specify chronic disease">
                        </div>
                    </div>

                    <div class="medical-card">
                        <h3>Current Medication</h3>

                        <div class="checkbox-grid">
                            <label><input type="checkbox" name="currentMed[]" value="None"> None</label>
                            <label><input type="checkbox" name="currentMed[]" value="Amlodipine"> Amlodipine</label>
                            <label><input type="checkbox" name="currentMed[]" value="Ventolin"> Ventolin</label>
                            <label><input type="checkbox" name="currentMed[]" value="Painkiller"> Painkiller</label>
                            <label><input type="checkbox" name="currentMed[]" value="Metformin"> Metformin</label>
                            <label><input type="checkbox" name="currentMed[]" value="Others" class="other-checkbox" data-target="medOtherBox"> Others</label>
                        </div>

                        <div id="medOtherBox" class="other-box">
                            <input type="text" name="currentMedOther" placeholder="Please specify medication">
                        </div>
                    </div>

                    <div class="medical-card">
                        <h3>Emergency Contact</h3>

                        <label class="field-label">Name</label>
                        <input type="text" name="emergencyContactName" placeholder="Enter emergency contact name" required>

                        <label class="field-label">Phone Number</label>
                        <input type="text" name="emergencyContactPhone" placeholder="Enter emergency contact phone number" required>
                    </div>

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

    </main>

</div>

<script>
document.querySelectorAll(".other-checkbox").forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
        const targetBox = document.getElementById(this.dataset.target);
        const input = targetBox.querySelector("input");

        if (this.checked) {
            targetBox.style.display = "block";
            input.required = true;
        } else {
            targetBox.style.display = "none";
            input.required = false;
            input.value = "";
        }
    });
});
</script>

</body>
</html>