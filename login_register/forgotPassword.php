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

            <form id="forgotForm">

                <label>Email Address</label>

                <input type="email" placeholder="example@email.com" required>

                <button type="submit" class="submitBtn">SUBMIT</button>

            </form>

        </div>

    </div>

</div>

<script src="loginRegister.js"></script>
</body>
</html>