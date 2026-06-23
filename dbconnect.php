<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "clinic_db";
// $port = 3307;

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>