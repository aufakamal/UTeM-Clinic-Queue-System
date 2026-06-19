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

        $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
        $mail->addAddress($receiverEmail, $receiverName);

        $verifyLink = "http://localhost:8080/UTeM-Clinic-Queue-System/login_register/verifyEmail.php?token=" . $token;

        $mail->isHTML(true);
        $mail->Subject = "Verify Your UTeM PKU Clinic Account";

        $mail->Body = "
            <h2>UTeM PKU Clinic Email Verification</h2>
            <p>Hello <b>$receiverName</b>,</p>
            <p>Please click the button below to verify your account.</p>

            <a href='$verifyLink' 
               style='background:#0F766E;color:white;padding:12px 18px;
               text-decoration:none;border-radius:8px;display:inline-block;'>
               Verify Email
            </a>

            <p>If you did not register, you may ignore this email.</p>
        ";

        return $mail->send();

    } catch (Exception $e) {
        return false;
    }
}

?>