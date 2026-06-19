<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>

    <div class="main">

        <div class="left-sec">
            <a href="mainPage.php" class="back">
                <img src="loginRegisterImage/backIconDark.png" alt="Back">
            </a>

            <h2>
                Welcome To <br>
                UTeM's PKU <br>
                Digital Clinic <br>
                Queue
            </h2>
        </div>

        <div class="right-sec">
            <h2>REGISTER</h2>

            <form id="registerForm">
                <label>Full Name</label>
                <input type="text" id="fullName">
                
                <label>ID</label>
                <input type="text" id="registerId">
                
                <label>Email</label>
                <input type="email" id="email">
                
                <label>Phone Number</label>
                <input type="text" id="phone">
                
                <label>Create Password</label>
                <input type="password" id="password">
                
                <label>Confirm Password</label>
                <input type="password" id="confirmPassword">
                
                <label>Register As</label>
                <select id="registerRole">
                    <option value="">Dropdown</option>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                </select>

                <button type="button" onclick="window.location.href='medicalCondition.php'" class="submit-button">NEXT</button>
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>

    </div>

</div>

<script src="loginRegister.js"></script>
</body>
</html>