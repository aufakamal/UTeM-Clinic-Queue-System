<?php

include "database.php";

header("Content-Type: application/json");

$sql = "
SELECT appointmentStatus, COUNT(*) AS total
FROM appointment
GROUP BY appointmentStatus
";

$result = $conn->query($sql);

$data = [
    "Booked" => 0,
    "Completed" => 0,
    "Cancelled" => 0,
    "No Show" => 0
];

while ($row = $result->fetch_assoc()) {
    $data[$row["appointmentStatus"]] = (int)$row["total"];
}

echo json_encode($data);

?>