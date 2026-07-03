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

<section id="dashboard" class="page active">
    <h2>Admin Dashboard</h2>
    <p>Overview of clinic appointments, queue, consultations, and doctors.</p>

    <div class="dashboard-overview">

    <div class="overview-card">
        <span>📅</span>
        <div>
            <h3>Appointments Today</h3>
            <p id="totalToday">0</p>
        </div>
    </div>

    <div class="overview-card">
        <span>⏳</span>
        <div>
            <h3>Waiting Patients</h3>
            <p id="waitingPatients">0</p>
        </div>
    </div>

    <div class="overview-card">
        <span>👨‍⚕️</span>
        <div>
            <h3>Active Consultations</h3>
            <p id="activeConsult">0</p>
        </div>
    </div>

    <div class="overview-card booking-overview">
        <span id="bookingIcon">🟢</span>
        <div>
            <h3>Booking System</h3>
            <p id="bookingStatusText">Checking...</p>
        </div>

        <button id="toggleBookingBtn" onclick="toggleBookingStatus()">
            Loading...
        </button>
    </div>

</div>



    <article class="chart">

    <h2>Today's Clinic Activity</h2>

    <p class="chart-subtitle">
        Real-time overview of today's clinic operations.
    </p>

    <div id="activityContainer" class="activity-container">
        Loading...
    </div>

</article>
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