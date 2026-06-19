<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Success</title>
    <link rel="stylesheet" href="resetSuccess.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>
    <div class="main">

        <a href="resetPassword.php" class="back">
            <img src="loginRegisterImage/backIconDark.png" alt="Back">
        </a>

        <div class="successCard">

            <img src="image/resetPasswordIcon.png" alt="Reset Password" class="successIcon">

            <h2>RESET PASSWORD</h2>

            <p class="smallText">
                Your password has been successfully reset.
            </p>

            <p class="successMessage">
                Please <a href="login.php">log in</a> again using your new password.
            </p>

        </div>

    </div>

</div>

<script src="loginRegister.js"></script>
</body>
</html>