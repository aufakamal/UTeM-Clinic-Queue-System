<?php
session_start();
include("../dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userID = trim($_POST['userID']);
    $password = trim($_POST['password']);
    $roleID = $_POST['roleID'];

    if (empty($userID) || empty($password) || empty($roleID)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
    else {

        $sql = "SELECT u.userID,
                       u.fullName,
                       u.password,
                       ur.roleID
                FROM user u
                JOIN user_role ur
                ON u.userID = ur.userID
                WHERE u.userID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            if ($row['roleID'] != $roleID) {

                echo "<script>
                        alert('Incorrect role selected.');
                      </script>";
            }
            else if (password_verify($password, $row['password'])) {

                $_SESSION['userID'] = $row['userID'];
                $_SESSION['fullName'] = $row['fullName'];
                $_SESSION['roleID'] = $row['roleID'];

                switch ($row['roleID']) {

                    case 1:
                        header("Location: ../admin/profileAdmin.html");
                        break;

                    case 2:
                        header("Location: ../doctor/doctor.html");
                        break;

                    case 3:
                        header("Location: ../pharmacist/workspace.html");
                        break;

                    case 4:
                        header("Location: ../patient/dashboard.php");
                        break;
                }

                exit();
            }
            else {

                echo "<script>
                        alert('Wrong password.');
                      </script>";
            }
        }
        else {

            echo "<script>
                    alert('User ID not found.');
                  </script>";
        }
    }
}
?>

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

            <h2>
                Welcome To <br>
                UTeM's PKU <br>
                Digital Clinic <br>
                Queue
            </h2>

        </div>

        <div class="right-sec">

            <h2>LOGIN</h2>

            <form method="POST" action="login.php">

                <label>ID</label>
                <input
                    type="text"
                    id="loginId"
                    name="userID"
                    required
                >

                <label>Password</label>
                <input
                    type="password"
                    id="loginPassword"
                    name="password"
                    required
                >

                <a href="forgotPassword.php" class="forgot">
                    Forgot Password?
                </a>

                <label>Login As</label>

                <select
                    id="loginRole"
                    name="roleID"
                    required
                >
                    <option value="">Select</option>
                    <option value="1">Admin</option>
                    <option value="2">Doctor</option>
                    <option value="3">Pharmacist</option>
                    <option value="4">Patient</option>
                </select>

                <div class="buttonRow">
                    <a href="mainPage.php" class="backBtn">BACK</a>

                    <button type="submit" class="loginBtn">LOGIN</button>
                </div>
                
                <p>
                    Don't have an account?
                    <a href="register.php">Register</a>
                </p>

            </form>

        </div>

    </div>

</div>

</body>
</html>