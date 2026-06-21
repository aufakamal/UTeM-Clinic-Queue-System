<?php

include "database.php";

$userID = "A032410002";

$sql = "
SELECT 
    u.userID,
    u.fullName,
    u.gender,
    u.email,
    u.phoneNo,
    u.password,
    r.roleName
FROM user u
JOIN user_role ur ON u.userID = ur.userID
JOIN role r ON ur.roleID = r.roleID
WHERE u.userID = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

$result = $stmt->get_result();

echo json_encode($result->fetch_assoc());

?>