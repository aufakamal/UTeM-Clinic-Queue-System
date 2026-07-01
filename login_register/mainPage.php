<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="mainPage.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>

    <main class="mainContent">

        <img class="clinicLogo" src="loginRegisterImage/clinicLogoo.png" alt="Clinic Logo">

        <h1 class="motto">
            Your Health, <span>Our Priority</span>
        </h1>


        <p class="welcomeText">
            Welcome to Pusat Kesihatan UTeM. Book appointments,<br>
            manage your records, and access healthcare services with ease.
        </p>

        <div class="buttonWrapper">

            <a href="login.php" class="actionCard">
                <div class="iconCircle">
                    <img src="loginRegisterImage/profile_icon_login-removebg-preview.png" alt="Login Icon">
                </div>

                <div class="buttonText">
                    <h2>LOGIN</h2>
                    <p>Access your account</p>
                </div>
            </a>

            <a href="register.php" class="actionCard">
                <div class="iconCircle">
                    <img src="loginRegisterImage/profile_icon_register-removebg-preview.png" alt="Register Icon">
                </div>

                <div class="buttonText">
                    <h2>REGISTER</h2>
                    <p>Create a new account</p>
                </div>
            </a>

        </div>

    </main>

</div>

<script src="loginRegister.js"></script>
</body>
</html>