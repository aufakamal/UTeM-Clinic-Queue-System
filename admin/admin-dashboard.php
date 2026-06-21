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

    <div class="stats">
        <div class="stat">
            <b>Total<br>appointments<br>today</b>
            <span id="totalToday">6</span>
        </div>

        <div class="stat">
            <b>Number of<br>waiting<br>patients</b>
            <span id="waitingPatients">7</span>
        </div>

        <div class="stat">
            <b>Number of<br>active<br>consultations</b>
            <span id="activeConsult">6</span>
        </div>

        <div class="stat">
            <b>Available<br>doctors</b>
            <span id="availableDoctors">7</span>
        </div>
    </div>

    <article class="chart">
        <h2>Weekly Appointments</h2>
        <div id="weeklyChart" class="weekly-chart"></div>
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