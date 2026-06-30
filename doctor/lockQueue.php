<?php

include("../dbconnect.php");

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["queueID"])) {

    echo json_encode([
        "success" => false,
        "message" => "Queue ID not received."
    ]);

    exit();

}

$queueID = $data["queueID"];

$sql = "
UPDATE queue
SET queueStatus = 'Called'
WHERE queueID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $queueID);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to update queue status."
    ]);

}

$stmt->close();
$conn->close();

?>