<?php

include "database.php";
header("Content-Type: application/json");

$sql = "
SELECT
    WEEK(t.slotDate, 1) - WEEK(DATE_SUB(t.slotDate, INTERVAL DAYOFMONTH(t.slotDate)-1 DAY), 1) + 1 AS weekNo,
    COUNT(*) AS total
FROM appointment a
JOIN time_slot t ON a.slotID = t.slotID
WHERE MONTH(t.slotDate) = MONTH(CURDATE())
AND YEAR(t.slotDate) = YEAR(CURDATE())
GROUP BY weekNo
ORDER BY weekNo
";

$result = $conn->query($sql);

$data = [
    "Week 1" => 0,
    "Week 2" => 0,
    "Week 3" => 0,
    "Week 4" => 0,
    "Week 5" => 0
];

while($row = $result->fetch_assoc()){
    $data["Week " . $row["weekNo"]] = (int)$row["total"];
}

echo json_encode($data);
?>