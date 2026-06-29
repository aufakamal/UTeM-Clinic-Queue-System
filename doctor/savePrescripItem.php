<?php
include '../db.php';

$medicine = $_POST['medicine'];
$dosage = $_POST['dosage'];
$frequency = $_POST['frequency'];
$duration = $_POST['duration'];
$instruction = $_POST['instruction'];

$sql = "INSERT INTO prescription_item 
(medicineID, quantity, dosage, frequency, duration, instructions)
VALUES 
('$medicine', 1, '$dosage', '$frequency', '$duration', '$instruction')";

$conn->query($sql);
?>