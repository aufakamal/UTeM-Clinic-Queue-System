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

    <div class="filter-bar single-filter">
        <input
            type="text"
            id="historySearch"
            placeholder="Search consultation ID, queue no, patient, or doctor..."
            onkeyup="filterHistory()">
    </div>

    <div class="table-card">

        <table class="admin-table">

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