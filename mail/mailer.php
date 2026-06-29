<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/config.php";

function sendVerificationEmail($receiverEmail, $receiverName, $token) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(MAIL_USERNAME, "UTeM Clinic Queue");
        $mail->addAddress($receiverEmail, $receiverName);

        $verifyLink = "http://localhost:8080/Project_Development_Workshop_Fixed/login_register/verifyEmail.php?token=" . $token;

        $mail->isHTML(false);
        $mail->Subject = "Verify Account";

        $mail->Body =
            "Hello $receiverName,

            Please verify your account.

            Verification link:
            $verifyLink

            Thank you.";

        return $mail->send();

    } catch (Exception $e) {
        return false;
    }
}
?>