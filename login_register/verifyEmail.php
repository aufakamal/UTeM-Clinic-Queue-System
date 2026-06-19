<?php
include("dbconnect.php");

if (isset($_GET['token'])) {

    $token = $_GET['token'];

    $sql = "SELECT userID FROM user WHERE verification_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $update = "UPDATE user 
                   SET email_verified = 1, verification_token = NULL 
                   WHERE verification_token = ?";

        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bind_param("s", $token);
        $stmtUpdate->execute();

        echo "<script>
                alert('Email verified successfully! You can now login.');
                window.location.href='login.php';
              </script>";

    } else {
        echo "<script>
                alert('Invalid or expired verification link.');
                window.location.href='login.php';
              </script>";
    }

} else {
    header("Location: login.php");
    exit();
}
?>