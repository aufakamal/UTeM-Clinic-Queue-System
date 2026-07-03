<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic History</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<?php include 'inc/admin_header.php'; ?>

<section class="admin-section">

    <h2>Clinic History</h2>
    <p>View appointment, queue and consultation records.</p>

    <div class="filter-card">
        <div class="filter-row">

            <input
                type="text"
                id="historySearch"
                class="filter-search"
                placeholder="Search ID, patient, details, or status..."
                onkeyup="filterHistory()">

            <select id="historyTypeFilter" onchange="filterHistory()">
                <option value="All">All Records</option>
                <option value="Appointment">Appointments</option>
                <option value="Queue">Queue</option>
                <option value="Consultation">Consultations</option>
            </select>

            <select id="historyStatusFilter" onchange="filterHistory()">
                <option value="All">All Status</option>
                <option value="Booked">Booked</option>
                <option value="Completed">Completed</option>
                <option value="Called">Called</option>
                <option value="Waiting">Waiting</option>
                <option value="Cancelled">Cancelled</option>
                <option value="No Show">No Show</option>
            </select>

            <div class="date-group">
                <label>From</label>
                <input
                    type="date"
                    id="historyFromDate"
                    onchange="filterHistory()">
            </div>

            <div class="date-group">
                <label>To</label>
                <input
                    type="date"
                    id="historyToDate"
                    onchange="filterHistory()">
            </div>

            <button class="reset-filter-btn" onclick="resetHistoryFilter()">
                Reset
            </button>

        </div>
    </div>

    <div class="table-card">
        <table class="admin-table history-table">
            <thead>
                <tr>
                    <th>Record Type</th>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Date / Time</th>
                    <th>Details</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody id="historyTableBody"></tbody>
        </table>
    </div>

</section>

<script src="admin.js"></script>

</body>
</html>