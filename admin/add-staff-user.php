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
        $fullName == "" ||
        $gender == "" ||
        $userID == "" ||
        $dobInput == "" ||
        $email == "" ||
        $phoneNo == "" ||
        $address == "" ||
        $password == "" ||
        $confirmPassword == "" ||
        $roleID == ""
    ) {
        $message = "Please fill in all fields.";
    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    }
    else if (!preg_match("/^[0-9]+$/", $phoneNo)) {
        $message = "Phone number must contain numbers only.";
    } 
    else if (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } 
    else if ($password !== $confirmPassword) {
        $message = "Password and confirm password do not match.";
    } 
    else if (!in_array($roleID, ["1", "2", "3"])) {
        $message = "Invalid role selected.";
    } 

    else {

        $dobObject = DateTime::createFromFormat("d/m/Y", $dobInput);

        if ($dobObject == false) {
            $message = "Invalid date format. Please use dd/mm/yyyy.";
        } 
        else {
            $dateOfBirth = $dobObject->format("Y-m-d");

            $check = $conn->prepare("SELECT userID FROM user WHERE userID = ? OR email = ?");
            $check->bind_param("ss", $userID, $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "User ID or email already exists.";
            } 
            else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $emailVerified = 0;
                $verificationToken = bin2hex(random_bytes(32));

                $conn->begin_transaction();

                try {
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

                    $sqlRole = "INSERT INTO user_role (userID, roleID) VALUES (?, ?)";
                    $stmtRole = $conn->prepare($sqlRole);
                    $stmtRole->bind_param("si", $userID, $roleID);
                    $stmtRole->execute();

                    $emailSent = sendVerificationEmail($email, $fullName, $verificationToken);

                if ($emailSent) {
                    $conn->commit();

                    echo "<script>
                        alert('Clinic staff user added successfully! Verification email has been sent.');
                        window.location.href='admin-staff.php';
                    </script>";
                    exit();
                } else {
                    $conn->rollback();

                    echo "<script>
                        alert('Verification email failed to send. User was not added.');
                        window.location.href='add-staff-user.php';
                    </script>";
                    exit();
                }

                } catch (Exception $e) {
                    $conn->rollback();
                    $message = "Failed to add clinic staff user.";
                }
            }
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
        <p>Register a new clinic staff account.</p>
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
                    <input type="text" name="fullName" placeholder="Enter full name" required>
                </div>

                <div class="input-group">
                    <label>Gender</label>
                    <select name="gender" required>
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
                    <input type="text" name="dateOfBirth" placeholder="dd/mm/yyyy" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email" required>
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phoneNo" placeholder="Enter phone number" required>
                </div>

                <div class="input-group full">
                    <label>Address</label>
                    <textarea name="address" placeholder="Enter full address" required></textarea>
                </div>

                <div class="input-group">
                    <label>Create Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmPassword" placeholder="Confirm password" required>
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
                <button type="submit" class="add-btn">Add User</button>
            </div>

        </form>

    </section>

</main>

</body>
</html>