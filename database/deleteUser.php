<?php

include "database.php";

$userID = $_POST["userID"];

/* Check if user has appointments */
$check = "
SELECT COUNT(*) AS total
FROM appointment
WHERE userID = ?
";

$stmtCheck = $conn->prepare($check);
$stmtCheck->bind_param("s", $userID);
$stmtCheck->execute();
$result = $stmtCheck->get_result();
$row = $result->fetch_assoc();

if ($row["total"] > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Cannot delete this user because they already have appointment records."
    ]);
    exit;
}

/* Delete role first */
$sqlRole = "DELETE FROM user_role WHERE userID = ?";
$stmtRole = $conn->prepare($sqlRole);
$stmtRole->bind_param("s", $userID);
$stmtRole->execute();

/* Delete user */
$sqlUser = "DELETE FROM user WHERE userID = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $userID);

if ($stmtUser->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
}

?>