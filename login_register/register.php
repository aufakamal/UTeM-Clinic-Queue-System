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

        <div class="register-wrapper">

            <h2>REGISTER</h2>
            <p class="subtitle">Create your account to continue using PKU Digital Clinic Queue.</p>

            <form id="registerForm" action="medicalCondition.php" method="post">

                <div class="form-grid">

                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name">
                    </div>

                    <div class="input-group">
                        <label>Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>ID</label>
                        <input type="text" id="registerId" name="userID" placeholder="Enter your ID">
                    </div>

                    <div class="input-group">
                        <label>Date of Birth</label>
                        <input type="text" id="dateOfBirth" name="dateOfBirth" placeholder="dd/mm/yyyy" required>
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email">
                    </div>

                    <div class="input-group">
                        <label>Phone Number</label>
                        <input type="text" id="phone" name="phoneNo" placeholder="Enter your phone number">
                    </div>

                    <div class="input-group full">
                        <label>Address</label>
                        <textarea id="address" name="address" placeholder="Enter your full address"></textarea>
                    </div>

                    <div class="input-group">
                        <label>Create Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password">
                    </div>

                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password">
                    </div>

                    <div class="input-group full">
                        <label>Register As</label>
                        <select id="registerRole" name="registerRole">
                            <option value="">Select role</option>
                            <option value="student">Student</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                </div>

                <div class="button-group">
                    <a href="mainPage.php" class="back-button">BACK</a>
                    <button type="submit" class="submit-button">NEXT</button>
                </div>

                <p class="login-link">
                    Already have an account? <a href="login.php">Log In</a>
                </p>

            </form>

        </div>

    </div>

</div>

<script src="loginRegister.js"></script>
</body>
</html>