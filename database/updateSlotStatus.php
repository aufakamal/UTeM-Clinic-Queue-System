<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
UPDATE time_slot ts
SET slotStatus = CASE
    WHEN (
        SELECT COUNT(*)
        FROM appointment a
        WHERE a.slotID = ts.slotID
        AND a.appointmentStatus != 'Cancelled'
    ) >= ts.capacity
    THEN 'Full'
    ELSE 'Available'
END
";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
}

?>