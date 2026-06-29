<?php
include('../dbconnect.php');

$result = $conn->query("
    SELECT * 
    FROM queue 
    WHERE queueStatus = 'Waiting'
");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>