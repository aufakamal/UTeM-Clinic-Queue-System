<?php
session_start();
include("../dbconnect.php");

if (!isset($_GET['token'])) {
    header("Location: ../login_register/login.php");
    exit();
}

$token = $_GET['token'];

$sql = "SELECT userID, pending_password, reset_token_expiry
        FROM user
        WHERE reset_token = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>
            alert('Invalid verification link.');
            window.location.href='../login_register/login.php';
          </script>";
    exit();
}

if (strtotime($user['reset_token_expiry']) < time()) {
    echo "<script>
            alert('Verification link has expired. Please request again.');
            window.location.href='changePassword.php';
          </script>";
    exit();
}

if (empty($user['pending_password'])) {
    echo "<script>
            alert('No pending password change found.');
            window.location.href='../login_register/login.php';
          </script>";
    exit();
}

$update = "UPDATE user
           SET password = pending_password,
               pending_password = NULL,
               reset_token = NULL,
               reset_token_expiry = NULL
           WHERE reset_token = ?";

$stmtUpdate = $conn->prepare($update);
$stmtUpdate->bind_param("s", $token);

if ($stmtUpdate->execute()) {
    session_destroy();

    echo "<script>
            alert('Password changed successfully. Please login again.');
            window.location.href='../login_register/login.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to change password.');
            window.location.href='../login_register/login.php';
          </script>";
}
?>