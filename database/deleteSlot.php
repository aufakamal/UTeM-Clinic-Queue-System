<?php

include "database.php";

$slotID = $_POST["slotID"];

/* Check if slot is already used by appointment */
$check = "
SELECT COUNT(*) AS total
FROM appointment
WHERE slotID = ?
";

$stmtCheck = $conn->prepare($check);
$stmtCheck->bind_param("i", $slotID);
$stmtCheck->execute();
$result = $stmtCheck->get_result();
$row = $result->fetch_assoc();

if ($row["total"] > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Cannot delete. This slot already has appointments."
    ]);
    exit;
}

/* Delete slot */
$sql = "
DELETE FROM time_slot
WHERE slotID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $slotID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

?>