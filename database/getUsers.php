<?php

include "database.php";

$sql = "
SELECT DISTINCT
    u.userID,
    u.fullName,
    u.gender,
    u.email,
    u.phoneNo,
    r.roleName
FROM user u
JOIN user_role ur
    ON u.userID = ur.userID
JOIN role r
    ON ur.roleID = r.roleID
WHERE u.email_verified = 1
ORDER BY
    r.roleName,
    u.fullName;
";

$result = mysqli_query($conn, $sql);

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode($users);

?>