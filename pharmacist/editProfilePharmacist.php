<?php
session_start();
include("../dbconnect.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT
            u.userID,
            u.fullName,
            u.gender,
            u.dateOfBirth,
            u.address,
            u.email,
            u.phoneNo,
            p.licenseNo
        FROM user u
        JOIN pharmacist_profile p
        ON u.userID = p.userID
        WHERE u.userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$pharmacist = $result->fetch_assoc();

if (!$pharmacist) {
    echo "<script>
            alert('Pharmacist profile not found.');
            window.location.href='profilePharmacist.php';
          </script>";
    exit();
}

$dobDisplay = "";
if (!empty($pharmacist['dateOfBirth'])) {
    $dobDisplay = date("d/m/Y", strtotime($pharmacist['dateOfBirth']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pharmacist Profile</title>
    <link rel="stylesheet" href="../shared/profile.css">
</head>

<body>
<?php include('inc/pharmacist_header.php'); ?>
<div class="profilePage">

    <section class="profileTitle">
        <h2>Edit Profile</h2>
    </section>

    <div class="profileCard">

        <div class="editHeader">
            <img src="../images/profileIconDark.png" alt="Profile Picture" class="profileImage">
        </div>

        <form class="editForm editProfileWide" action="updateProfilePharmacist.php" method="POST">

            <div class="editProfileGrid">

                <div class="profileSection">

                    <h3>Personal Information</h3>

                    <label>Full Name</label>
                    <input type="text" name="fullName" value="<?php echo htmlspecialchars($pharmacist['fullName']); ?>" required>

                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select gender</option>
                        <option value="Male" <?php if ($pharmacist['gender'] == "Male") echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if ($pharmacist['gender'] == "Female") echo "selected"; ?>>Female</option>
                    </select>

                    <label>Date of Birth</label>
                    <input type="text" name="dateOfBirth" value="<?php echo htmlspecialchars($dobDisplay); ?>" placeholder="dd/mm/yyyy" required>

                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($pharmacist['email']); ?>" required>

                    <label>ID</label>
                    <input type="text" value="<?php echo htmlspecialchars($pharmacist['userID']); ?>" readonly>

                    <label>Phone Number</label>
                    <input type="text" name="phoneNo" value="<?php echo htmlspecialchars($pharmacist['phoneNo']); ?>" required>

                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($pharmacist['address'] ?? ''); ?>">

                    <label>Password</label>
                    <input type="password" value="********" readonly>

                    <div class="changePasswordWrapper">
                        <a href="../shared/changePassword.php" class="changePasswordLink">Change Password</a>
                    </div>

                </div>

                <div class="profileSection medicalSection">

                    <h3>Professional Information</h3>

                    <label>License Number</label>
                    <input type="text" name="licenseNo" value="<?php echo htmlspecialchars($pharmacist['licenseNo'] ?? ''); ?>" required>

                </div>

            </div>

            <div class="buttonGroup">
                <a href="profilePharmacist.php" class="saveBtn secondaryBtn">BACK</a>
                <button type="submit" class="saveBtn">SAVE</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>