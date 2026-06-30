<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "clinic_db";
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
