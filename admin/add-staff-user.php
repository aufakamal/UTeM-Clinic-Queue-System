<?php
session_start();

include("../dbconnect.php");
require_once("../mail/mailer.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = trim($_POST["fullName"]);
    $gender = trim($_POST["gender"]);
    $userID = trim($_POST["userID"]);
    $dobInput = trim($_POST["dateOfBirth"]);
    $email = strtolower(trim($_POST["email"]));
    $phoneNo = trim($_POST["phoneNo"]);
    $address = trim($_POST["address"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $roleID = $_POST["roleID"];

    if (
        $userID == "" ||
        $roleID == ""
    ) {
        $message = "Please fill in User ID and Role.";
    }
    else if (!in_array($roleID, ["1", "2", "3"])) {
        $message = "Invalid role selected.";
    }
    else {

        $checkUser = $conn->prepare("SELECT * FROM user WHERE userID = ?");
        $checkUser->bind_param("s", $userID);
        $checkUser->execute();
        $userResult = $checkUser->get_result();

        $conn->begin_transaction();

        try {

            if ($userResult->num_rows == 0) {

                if (
                    $fullName == "" ||
                    $gender == "" ||
                    $dobInput == "" ||
                    $email == "" ||
                    $phoneNo == "" ||
                    $address == "" ||
                    $password == "" ||
                    $confirmPassword == ""
                ) {
                    throw new Exception("Please fill in all fields for new user.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Please enter a valid email address.");
                }

                if (!preg_match("/^[0-9]+$/", $phoneNo)) {
                    throw new Exception("Phone number must contain numbers only.");
                }

                if (strlen($password) < 8) {
                    throw new Exception("Password must be at least 8 characters.");
                }

                if ($password !== $confirmPassword) {
                    throw new Exception("Password and confirm password do not match.");
                }

                $dobObject = DateTime::createFromFormat("d/m/Y", $dobInput);

                if ($dobObject == false) {
                    throw new Exception("Invalid date format. Please use dd/mm/yyyy.");
                }

                $dateOfBirth = $dobObject->format("Y-m-d");

                $checkEmail = $conn->prepare("SELECT userID FROM user WHERE email = ?");
                $checkEmail->bind_param("s", $email);
                $checkEmail->execute();
                $emailResult = $checkEmail->get_result();

                if ($emailResult->num_rows > 0) {
                    throw new Exception("Email already exists.");
                }

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $emailVerified = 0;
                $verificationToken = bin2hex(random_bytes(32));

                $sqlUser = "INSERT INTO user 
                (userID, fullName, gender, dateOfBirth, address, email, phoneNo, password, email_verified, verification_token)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmtUser = $conn->prepare($sqlUser);
                $stmtUser->bind_param(
                    "ssssssssis",
                    $userID,
                    $fullName,
                    $gender,
                    $dateOfBirth,
                    $address,
                    $email,
                    $phoneNo,
                    $hashedPassword,
                    $emailVerified,
                    $verificationToken
                );
                $stmtUser->execute();

                $emailSent = sendVerificationEmail($email, $fullName, $verificationToken);

                if (!$emailSent) {
                    throw new Exception("Verification email failed to send. User was not added.");
                }
            }

            $checkRole = $conn->prepare("SELECT * FROM user_role WHERE userID = ? AND roleID = ?");
            $checkRole->bind_param("si", $userID, $roleID);
            $checkRole->execute();
            $roleResult = $checkRole->get_result();

            if ($roleResult->num_rows > 0) {
                throw new Exception("This user already has this role.");
            }

            $sqlRole = "INSERT INTO user_role (userID, roleID) VALUES (?, ?)";
            $stmtRole = $conn->prepare($sqlRole);
            $stmtRole->bind_param("si", $userID, $roleID);
            $stmtRole->execute();

            $conn->commit();

            echo "<script>
                alert('Clinic staff role added successfully.');
                window.location.href='admin-staff.php';
            </script>";
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $message = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Clinic Staff User</title>
    <link rel="stylesheet" href="add-staff-user.css">
</head>
<body>

<?php include 'inc/admin_header.php'; ?>

<main class="page-container">

    <section class="page-title">
        <h1>Add Clinic Staff User</h1>
        <p>Register a new clinic staff account or add another role to existing user.</p>
    </section>

    <section class="form-card">

        <h3>Account Information</h3>

        <?php if ($message != "") { ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php } ?>

        <form method="post">

            <div class="form-grid">

                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullName" placeholder="Enter full name">
                </div>

                <div class="input-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>User ID</label>
                    <input type="text" name="userID" placeholder="Enter user ID" required>
                </div>

                <div class="input-group">
                    <label>Date of Birth</label>
                    <input type="text" name="dateOfBirth" placeholder="dd/mm/yyyy">
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email">
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phoneNo" placeholder="Enter phone number">
                </div>

                <div class="input-group full">
                    <label>Address</label>
                    <textarea name="address" placeholder="Enter full address"></textarea>
                </div>

                <div class="input-group">
                    <label>Create Password</label>
                    <input type="password" name="password" placeholder="Enter password">
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmPassword" placeholder="Confirm password">
                </div>

                <div class="input-group full">
                    <label>Role</label>
                    <select name="roleID" required>
                        <option value="">Select role</option>
                        <option value="1">Admin</option>
                        <option value="2">Doctor</option>
                        <option value="3">Pharmacist</option>
                    </select>
                </div>

            </div>

            <div class="button-group">
                <a href="admin-staff.php" class="back-btn">Back</a>
                <button type="submit" class="add-btn">Add User / Role</button>
            </div>

        </form>

    </section>

</main>

<script src="admin.js"></script>
</body>
</html>