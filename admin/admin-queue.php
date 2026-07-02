<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTeM PKU Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'inc/admin_header.php'; ?>

<section id="queue" class="page active">
    <h2>Queue Management</h2>
    <p>View and filter today’s clinic queue.</p>

    <div id="roomDashboard" class="room-dashboard"></div>

    <div class="filter-bar">
        <input type="text" id="queueSearch"
               placeholder="Search queue no, patient, or user ID..."
               onkeyup="filterQueue()">

        <select id="queueStatusFilter" onchange="filterQueue()">
            <option value="All">All Status</option>
            <option value="Waiting">Waiting</option>
            <option value="Called">Called</option>
            <option value="Completed">Completed</option>
        </select>
    </div>

    <div class="table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Queue No</th>
                    <th>Patient</th>
                    <th>User ID</th>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody id="queueTableBody"></tbody>
        </table>
    </div>
</section>

<script src="admin.js"></script>
</body>
</html>