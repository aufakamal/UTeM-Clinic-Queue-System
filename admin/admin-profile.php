<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="admin-profile.css">
</head>

<body>

<?php include 'inc/admin_header.php'; ?>

<div class="profilePage">

    <section class="profileCard">

        <div class="profileHeader">

            <div class="profileImage">
                👤
            </div>

            <div class="userInfo">
                <h2 id="profileName">Loading...</h2>
                <p id="profileEmail">Loading...</p>
            </div>

            <div class="profileActions">

                <span class="roleName" id="profileRole">
                    Loading...
                </span>

                <a href="admin-edit-profile.php" class="editBtn">
                    Change Profile Information
                </a>

            </div>

        </div>

        <div class="profileContent">

            <div class="leftInfo">

                <label>Staff ID</label>
                <input id="profileUserID" type="text" readonly>

                <label>Phone Number</label>
                <input id="profilePhone" type="text" readonly>

            </div>

            <div class="rightInfo">

                <h3>Staff Information</h3>

                <label>Role</label>
                <input id="profileRoleInput" type="text" readonly>

                <label>Status</label>
                <input type="text" value="Active" readonly>

            </div>

        </div>

    </section>

</div>

<script src="admin.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    loadAdminProfile();
});
</script>

</body>
</html>