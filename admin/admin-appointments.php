<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'inc/admin_header.php'; ?>

<section class="admin-section">
    <h2>Appointment Management</h2>
    <p>Search, filter, and update today’s clinic appointments.</p>

    <div class="filter-bar">
        <input type="text" id="appointmentSearch" placeholder="Search by patient name, ID, or type..." onkeyup="filterAppointments()">

        <input type="date" id="appointmentDateFilter" onchange="filterAppointments()">

        <select id="appointmentStatusFilter" onchange="filterAppointments()">
            <option value="">All Status</option>
            <option value="Booked">Booked</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
            <option value="No Show">No Show</option>
        </select>

        <select id="appointmentTypeFilter" onchange="filterAppointments()">
            <option value="All">All Types</option>
            <option value="Same-Day">Same-Day</option>
            <option value="Scheduled">Scheduled</option>
        </select>
    </div>

    <div class="table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient</th>
                    <th>User ID</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Appointment</th>
                    <th>Attendance</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="appointmentTableBody">
            </tbody>
        </table>
    </div>
</section>

<script src="admin.js"></script>
</body>
</html>