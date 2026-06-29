<?php
include('../dbconnect.php');

$data = json_decode(file_get_contents("php://input"), true);

$queue_id = $data['queue_id'];

$conn->query("
    UPDATE queue 
    SET status='In Consultation'
    WHERE queue_id='$queue_id'
");
?>