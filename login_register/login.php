<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container">
    <?php include('inc/login_register_header.php'); ?>

    <div class="main">

        <div class="left-sec">
            <a href="mainPage.php" class="back">
                <img src="loginRegisterImage/backIconDark.png" alt="Back">
            </a>

            <h2>Welcome To <br>
            UTeM's PKU <br>
            Digital Clinic <br>
            Queue
            </h2>
        </div>

        <div class="right-sec">
            <h2>LOGIN</h2>

            <form id="loginForm">
                <label>ID</label>
                <input type="text" id="loginId">

                <label>Password</label>
                <input type="password" id="loginPassword">

                <a href="forgotPassword.php" class="forgot">Forgot Password?</a>

                <label>Login As</label>
                <select id="loginRole">
                    <option value="">Dropdown</option>
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                    <option value="pharmacist">Pharmacist</option>
                    <option value="admin">Administrator</option>
                </select>

                <button type="submit" class="loginBtn">LOGIN</button>

                <p>Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</div>

<script src="loginRegister.js"></script>
</body>
</html>