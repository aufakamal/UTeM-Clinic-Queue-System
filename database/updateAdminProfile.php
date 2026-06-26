<?php

include "database.php";

$userID = "A032410002";

$fullName = $_POST["fullName"];
$email = $_POST["email"];
$phoneNo = $_POST["phoneNo"];

$sql = "
UPDATE user
SET fullName = ?,
    email = ?,
    phoneNo = ?
WHERE userID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss",
    $fullName,
    $email,
    $phoneNo,
    $userID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

?>
