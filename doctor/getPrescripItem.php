<?php
$conn = new mysqli("localhost","root","","clinic_db");

$id = $_GET['id'];

$sql = "
SELECT p.*, m.medicineName 
FROM prescription_item p
JOIN medicine m ON p.medicineID = m.medicineID
WHERE p.prescriptionID = $id
";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>