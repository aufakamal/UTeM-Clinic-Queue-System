<?php

session_start();
include("../dbconnect.php");

header("Content-Type: application/json");

try {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid JSON payload");
    }

    $doctorUserID = $_SESSION["userID"] ?? null;

    if (!$doctorUserID) {
        throw new Exception("Session expired. Please login again.");
    }

    $queueID = $data["queueID"];

    $sql = "
        INSERT INTO consultation
        (
            queueID,
            doctorUserID,
            startTime
        )
        VALUES
        (
            ?,
            ?,
            NOW()
        )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "is",
        $queueID,
        $doctorUserID
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    echo json_encode([
        "success" => true
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}