<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'inc/admin_header.php'; ?>

<section id="users" class="page active">
    <h2>User Management</h2>
    <p>View and filter all system users.</p>

    <div class="filter-bar">
        <input type="text" id="userSearch" placeholder="Search user ID, name, email, or phone..." onkeyup="filterUsers()">

        <select id="userRoleFilter" onchange="filterUsers()">
            <option value="All">All Roles</option>
            <option value="Admin">Admin</option>
            <option value="Doctor">Doctor</option>
            <option value="Patient">Patient</option>
            <option value="Pharmacist">Pharmacist</option>
        </select>
    </div>

    <div class="table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone No</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="usersTableBody"></tbody>
        </table>
    </div>
</section>

<div id="confirmBox" class="modal hidden">
    <div class="modal-card">
        <b id="confirmText">Are you sure?</b>
        <button class="green" onclick="confirmYes()">Confirm</button>
        <button class="red" onclick="closeConfirm()">Cancel</button>
    </div>
</div>

<script src="admin.js"></script>
</body>
</html>