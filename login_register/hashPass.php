<?php

$users = [
    'admin123',
    'doctor123',
    'pharmacy123',
    'patient123',
    'staff123'
];

foreach ($users as $password)
{
    echo "<h3>$password</h3>";
    echo password_hash($password, PASSWORD_DEFAULT);
    echo "<br><br>";
}

?>