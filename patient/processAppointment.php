<?php

include('inc/connect.php');

$appointmentDate = $_POST['appointmentDate'];
$appointmentType = $_POST['appointmentType'];
$timeSlot = $_POST['timeSlot'];

echo "Date: " . $appointmentDate . "<br>";
echo "Type: " . $appointmentType . "<br>";
echo "Slot: " . $timeSlot . "<br><br>";

?>