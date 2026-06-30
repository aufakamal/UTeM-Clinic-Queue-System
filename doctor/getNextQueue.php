<?php
include("../dbconnect.php");

$sql = "
SELECT
    q.queueID,
    q.queueNo,

    u.userID,
    u.fullName,
    u.gender,

    pp.bloodType,
    pp.allergy,
    pp.chronicCondition,
    pp.currentMed

FROM queue q

INNER JOIN attendance a
ON q.attendanceID = a.attendanceID

INNER JOIN appointment ap
ON a.appointmentID = ap.appointmentID

INNER JOIN user u
ON ap.userID = u.userID

LEFT JOIN patient_profile pp
ON u.userID = pp.userID

WHERE q.queueStatus='Waiting'

ORDER BY q.queueNo ASC

LIMIT 1
";

$result = $conn->query($sql);

if($result->num_rows==0){

    echo json_encode(null);

    exit();

}

$patient=$result->fetch_assoc();

echo json_encode($patient);

?>