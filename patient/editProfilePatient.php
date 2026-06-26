<?php
session_start();
include("../login_register/dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT
            u.userID,
            u.fullName,
            u.email,
            u.phoneNo,
            u.address,
            p.bloodType,
            p.allergy,
            p.chronicCondition,
            p.currentMed,
            p.emergencyContactName,
            p.emergencyContactPhone
        FROM user u
        JOIN patient_profile p
        ON u.userID = p.userID
        WHERE u.userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    echo "<script>
            alert('Patient profile not found.');
            window.location.href='profilePatient.php';
          </script>";
    exit();
}

$allergyArray = explode(", ", $patient['allergy'] ?? "");
$chronicArray = explode(", ", $patient['chronicCondition'] ?? "");
$medArray = explode(", ", $patient['currentMed'] ?? "");
?>

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
        <h2>Edit Profile</h2>
    </section>

    <div class="profileCard">

        <div class="editHeader">
            <img src="patientImages/profileIconDark.png" alt="Profile Picture" class="profileImage">
        </div>

        <form class="editForm editProfileWide" action="updateProfilePatient.php" method="POST">

            <div class="editProfileGrid">

                <div class="profileSection">

                    <h3>Personal Information</h3>

                    <label>Full Name</label>
                    <input type="text" name="fullName" value="<?php echo htmlspecialchars($patient['fullName']); ?>" required>

                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>

                    <label>ID</label>
                    <input type="text" value="<?php echo htmlspecialchars($patient['userID']); ?>" readonly>

                    <label>Phone Number</label>
                    <input type="text" name="phoneNo" value="<?php echo htmlspecialchars($patient['phoneNo']); ?>" required>

                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($patient['address'] ?? ''); ?>">

                    <label>Password</label>
                    <input type="password" value="********" readonly>

                    <div class="changePasswordWrapper">
                        <a href="changePassword.php" class="changePasswordLink">Change Password</a>
                    </div>

                    <div class="sectionDivider"></div>

                    <h3>Emergency Contact</h3>

                    <label>Emergency Contact Name</label>
                    <input type="text" name="emergencyContactName" value="<?php echo htmlspecialchars($patient['emergencyContactName'] ?? ''); ?>">

                    <label>Emergency Contact Phone</label>
                    <input type="text" name="emergencyContactPhone" value="<?php echo htmlspecialchars($patient['emergencyContactPhone'] ?? ''); ?>">

                </div>

                <div class="profileSection medicalSection">

                    <h3>Medical Information</h3>

                    <label>Blood Type</label>
                    <select name="bloodType" required>
                        <option value="">Select blood type</option>
                        <option value="A+" <?php if ($patient['bloodType'] == "A+") echo "selected"; ?>>A+</option>
                        <option value="A-" <?php if ($patient['bloodType'] == "A-") echo "selected"; ?>>A-</option>
                        <option value="B+" <?php if ($patient['bloodType'] == "B+") echo "selected"; ?>>B+</option>
                        <option value="B-" <?php if ($patient['bloodType'] == "B-") echo "selected"; ?>>B-</option>
                        <option value="AB+" <?php if ($patient['bloodType'] == "AB+") echo "selected"; ?>>AB+</option>
                        <option value="AB-" <?php if ($patient['bloodType'] == "AB-") echo "selected"; ?>>AB-</option>
                        <option value="O+" <?php if ($patient['bloodType'] == "O+") echo "selected"; ?>>O+</option>
                        <option value="O-" <?php if ($patient['bloodType'] == "O-") echo "selected"; ?>>O-</option>
                    </select>

                    <label>Allergies</label>
                    <div class="checkboxGroup profileCheckboxGrid">
                        <label><input type="checkbox" name="allergy[]" value="None" <?php if (in_array("None", $allergyArray)) echo "checked"; ?>> None</label>
                        <label><input type="checkbox" name="allergy[]" value="Dust" <?php if (in_array("Dust", $allergyArray)) echo "checked"; ?>> Dust</label>
                        <label><input type="checkbox" name="allergy[]" value="Peanuts" <?php if (in_array("Peanuts", $allergyArray)) echo "checked"; ?>> Peanuts</label>
                        <label><input type="checkbox" name="allergy[]" value="Penicillin" <?php if (in_array("Penicillin", $allergyArray)) echo "checked"; ?>> Penicillin</label>
                        <label><input type="checkbox" name="allergy[]" value="Seafood" <?php if (in_array("Seafood", $allergyArray)) echo "checked"; ?>> Seafood</label>
                        <label><input type="checkbox" name="allergy[]" value="Others" <?php if (in_array("Others", $allergyArray)) echo "checked"; ?>> Others</label>
                    </div>

                    <div class="miniDivider"></div>

                    <label>Chronic Illness</label>
                    <div class="checkboxGroup profileCheckboxGrid">
                        <label><input type="checkbox" name="chronicCondition[]" value="None" <?php if (in_array("None", $chronicArray)) echo "checked"; ?>> None</label>
                        <label><input type="checkbox" name="chronicCondition[]" value="Hypertension" <?php if (in_array("Hypertension", $chronicArray)) echo "checked"; ?>> Hypertension</label>
                        <label><input type="checkbox" name="chronicCondition[]" value="Asthma" <?php if (in_array("Asthma", $chronicArray)) echo "checked"; ?>> Asthma</label>
                        <label><input type="checkbox" name="chronicCondition[]" value="Heart Disease" <?php if (in_array("Heart Disease", $chronicArray)) echo "checked"; ?>> Heart Disease</label>
                        <label><input type="checkbox" name="chronicCondition[]" value="Diabetes" <?php if (in_array("Diabetes", $chronicArray)) echo "checked"; ?>> Diabetes</label>
                        <label><input type="checkbox" name="chronicCondition[]" value="Others" <?php if (in_array("Others", $chronicArray)) echo "checked"; ?>> Others</label>
                    </div>

                    <div class="miniDivider"></div>

                    <label>Current Medication</label>
                    <div class="checkboxGroup profileCheckboxGrid">
                        <label><input type="checkbox" name="currentMed[]" value="None" <?php if (in_array("None", $medArray)) echo "checked"; ?>> None</label>
                        <label><input type="checkbox" name="currentMed[]" value="Amlodipine" <?php if (in_array("Amlodipine", $medArray)) echo "checked"; ?>> Amlodipine</label>
                        <label><input type="checkbox" name="currentMed[]" value="Ventolin" <?php if (in_array("Ventolin", $medArray)) echo "checked"; ?>> Ventolin</label>
                        <label><input type="checkbox" name="currentMed[]" value="Painkiller" <?php if (in_array("Painkiller", $medArray)) echo "checked"; ?>> Painkiller</label>
                        <label><input type="checkbox" name="currentMed[]" value="Metformin" <?php if (in_array("Metformin", $medArray)) echo "checked"; ?>> Metformin</label>
                        <label><input type="checkbox" name="currentMed[]" value="Others" <?php if (in_array("Others", $medArray)) echo "checked"; ?>> Others</label>
                    </div>

                </div>

            </div>

            <div class="buttonGroup">
                <a href="profilePatient.php" class="saveBtn secondaryBtn">BACK</a>
                <button type="submit" class="saveBtn">SAVE</button>
            </div>

        </form>

    </div>

</div>

<?php include('inc/patient_footer.php'); ?>

</body>
</html>