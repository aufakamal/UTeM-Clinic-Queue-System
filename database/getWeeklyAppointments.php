<?php

include "database.php";

$sql = "
SELECT
DAYOFWEEK(t.slotDate) AS dayNo,
COUNT(*) AS total
FROM appointment a
JOIN time_slot t
ON a.slotID = t.slotID
WHERE YEARWEEK(t.slotDate,1)=YEARWEEK(CURDATE(),1)
GROUP BY DAYOFWEEK(t.slotDate)
";

$result = $conn->query($sql);

if (!$result) {
    die($conn->error);
}

$data = [
    1=>0,
    2=>0,
    3=>0,
    4=>0,
    5=>0,
    6=>0,
    7=>0
];

while($row = $result->fetch_assoc()){
    $data[$row["dayNo"]] = (int)$row["total"];
}

echo json_encode($data);

?>