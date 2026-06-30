<?php
session_start();
include("../dbconnect.php");
require_once("../mail/mailer.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    if (empty($email)) {
        $message = "Please enter your email address.";
    } else {

        $sql = "SELECT userID, fullName, email
                FROM user
                WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {

            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

            $update = "UPDATE user
                       SET reset_token = ?,
                           reset_token_expiry = ?
                       WHERE email = ?";

            $stmtUpdate = $conn->prepare($update);
            $stmtUpdate->bind_param("sss", $token, $expiry, $email);
            $stmtUpdate->execute();

            $emailSent = sendForgotPasswordEmail(
                $user['email'],
                $user['fullName'],
                $token
            );

            if ($emailSent) {
                echo "<script>
                        alert('Reset password link has been sent to your email.');
                        window.location.href='login.php';
                      </script>";
                exit();
            } else {
                $message = "Failed to send reset email.";
            }

        } else {
            $message = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgotPassword.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>

    <div class="main">

        <a href="login.php" class="back">
            <img src="loginRegisterImage/backIconDark.png" alt="">
        </a>

        <div class="forgotCard">

            <img src="loginRegisterImage/forgotPasswordIcon.png" alt="Forgot Password" class="forgotIcon">

            <h2>FORGOT PASSWORD</h2>

            <p>Enter your email address and we will send a password reset link.</p>

            <?php if (!empty($message)) { ?>
                <p style="color:red; font-weight:bold;">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php } ?>

            <form method="POST" action="forgotPassword.php">

                <label>Email Address</label>

                <input 
                    type="email" 
                    name="email"
                    placeholder="example@email.com" 
                    required
                >

                <button type="submit" class="submitBtn">SUBMIT</button>

            </form>

        </div>

    </div>

</div>

</body>
</html>