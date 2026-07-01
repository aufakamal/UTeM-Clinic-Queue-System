<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation History</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<?php include 'inc/admin_header.php'; ?>

<section class="admin-section">

    <h2>Consultation History</h2>

    <p>
        View completed consultations and previous clinic visits.
    </p>

    <div class="filter-card">

    <div class="filter-row">

        <input
            type="text"
            id="historySearch"
            class="filter-search"
            placeholder="Search consultation ID, queue no, patient, or doctor..."
            onkeyup="filterHistory()">

        <select id="historyStatusFilter" onchange="filterHistory()">
            <option value="All">All Status</option>
            <option value="Completed">Completed</option>
            <option value="Called">Called</option>
            <option value="Waiting">Waiting</option>
        </select>

        <div class="date-group">
            <label>From</label>
            <input type="date"
                   id="historyFromDate"
                   onchange="filterHistory()">
        </div>

        <div class="date-group">
            <label>To</label>
            <input type="date"
                   id="historyToDate"
                   onchange="filterHistory()">
        </div>

        <button class="reset-filter-btn"
                onclick="resetHistoryFilter()">
            Reset
        </button>

    </div>

</div>

    <div class="table-card">
    <table class="admin-table history-table">

            <thead>

                <tr>
                    <th>Consultation ID</th>
                    <th>Queue No</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody id="historyTableBody">
            </tbody>

        </table>

    </div>

</section>

<script src="admin.js"></script>

</body>
</html>