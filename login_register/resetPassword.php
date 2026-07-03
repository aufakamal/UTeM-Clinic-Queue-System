<?php
session_start();
include("../dbconnect.php");

if (!isset($_GET['token'])) {
    header("Location: login.php");
    exit();
}

$token = $_GET['token'];

$sql = "SELECT userID, reset_token_expiry
        FROM user
        WHERE reset_token = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>
            alert('Invalid reset link.');
            window.location.href='login.php';
          </script>";
    exit();
}

if (strtotime($user['reset_token_expiry']) < time()) {
    echo "<script>
            alert('Reset link has expired. Please request again.');
            window.location.href='forgotPassword.php';
          </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword != $confirmPassword) {
        echo "<script>alert('New password and confirm password do not match.');</script>";
    }
    else if (strlen($newPassword) < 6) {
        echo "<script>alert('Password must be at least 6 characters.');</script>";
    }
    else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = "UPDATE user
                   SET password = ?,
                       pending_password = NULL,
                       reset_token = NULL,
                       reset_token_expiry = NULL
                   WHERE reset_token = ?";

        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bind_param("ss", $hashedPassword, $token);

        if ($stmtUpdate->execute()) {
            echo "<script>
                    alert('Password reset successful. Please login.');
                    window.location.href='login.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Failed to reset password.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="resetPassword.css">
</head>
<body>

<div class="container">

    <?php include('inc/login_register_header.php'); ?>

    <div class="main">

        <form method="POST" class="reset-form">

            <h2>RESET PASSWORD</h2>

            <label>New Password</label>
            <input type="password" name="newPassword" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirmPassword" required>

            <div class="buttonRow">
                <a href="login.php" class="backBtn">BACK</a>
                <button type="submit" class="resetBtn">RESET</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>