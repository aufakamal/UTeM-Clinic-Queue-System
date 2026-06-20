<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="resetPassword.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>
    <div class="main">

        <a href="forgotPassword.php" class="back">
            <img src="loginRegisterImage/backIconDark.png" alt="">
        </a>

        <div class="resetCard">

            <img src="loginRegisterImage/resetPasswordIcon.png" alt="Reset Password" class="resetIcon">

            <h2>RESET PASSWORD</h2>

            <p>
                Please enter your new password.
            </p>

            <form id="resetForm">

                <label>New Password</label>
                <input type="password" required>

                <label>Confirm Password</label>
                <input type="password" required>

                <button type="submit" class="resetBtn">RESET PASSWORD</button>

            </form>

        </div>

    </div>

</div>

<script src="loginRegister.js"></script>
</body>
</html>