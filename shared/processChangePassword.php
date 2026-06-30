<?php
session_start();
include("../dbconnect.php");
require_once("../mail/mailer.php");

if (!isset($_SESSION['userID'])) {
    header("Location: ../login_register/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: changePassword.php");
    exit();
}

$userID = $_SESSION['userID'];

$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

if ($newPassword != $confirmPassword) {
    echo "<script>
            alert('New password and confirm password do not match.');
            window.location.href='changePassword.php';
          </script>";
    exit();
}

if (strlen($newPassword) < 6) {
    echo "<script>
            alert('New password must be at least 6 characters.');
            window.location.href='changePassword.php';
          </script>";
    exit();
}

$sql = "SELECT fullName, email, password FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($currentPassword, $user['password'])) {
    echo "<script>
            alert('Current password is incorrect.');
            window.location.href='changePassword.php';
          </script>";
    exit();
}

$pendingPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

$update = "UPDATE user
           SET pending_password = ?,
               reset_token = ?,
               reset_token_expiry = ?
           WHERE userID = ?";

$stmtUpdate = $conn->prepare($update);
$stmtUpdate->bind_param("ssss", $pendingPassword, $token, $expiry, $userID);

if ($stmtUpdate->execute()) {

    $emailSent = sendChangePasswordEmail($user['email'], $user['fullName'], $token);

    if ($emailSent) {
        echo "<script>
                alert('Verification email sent. Please check your email to confirm password change.');
                window.location.href='../login_register/login.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to send verification email.');
                window.location.href='changePassword.php';
              </script>";
    }

} else {
    echo "<script>
            alert('Failed to process password change.');
            window.location.href='changePassword.php';
          </script>";
}
?>