<?php

include "database.php";

$sql = "
SELECT
slotID,
slotDate,
startTime,
endTime,
slotType,
capacity
FROM time_slot
ORDER BY slotDate,startTime
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

?>