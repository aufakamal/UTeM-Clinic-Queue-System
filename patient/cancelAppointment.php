<?php

    session_start();
    include('../dbconnect.php');

    $appointmentID = $_POST['appointmentID'];

    $sql = "SELECT slotID
            FROM appointment
            WHERE appointmentID = '$appointmentID'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    $slotID = $row['slotID'];

    $sqlCancel = "UPDATE appointment
                SET appointmentStatus = 'Cancelled'
                WHERE appointmentID = '$appointmentID'";

    mysqli_query($conn, $sqlCancel);

    $sqlCapacity = "UPDATE time_slot
                    SET capacity = capacity + 1
                    WHERE slotID = '$slotID'";

    mysqli_query($conn, $sqlCapacity);

    header("Location: appointmentRecord.php");
    exit();
    
?>