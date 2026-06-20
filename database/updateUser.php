<?php

include "database.php";

$userID = $_POST["userID"];
$fullName = $_POST["fullName"];
$gender = $_POST["gender"];
$email = $_POST["email"];
$phoneNo = $_POST["phoneNo"];

$sql = "
UPDATE user
SET fullName = ?,
    gender = ?,
    email = ?,
    phoneNo = ?
WHERE userID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $fullName, $gender, $email, $phoneNo, $userID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

?>