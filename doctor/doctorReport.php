<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Report</title>
    <link rel="stylesheet" href="doctor.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<?php include('inc/doctor_header.php'); ?>

<section class="drReportPage">

    <!-- HEADER -->

    <div class="report-wrapper">

    <!-- TITLE SECTION -->
    <div class="report-header">
        <div>
            <h2>Doctor Dashboard Overview</h2>
            <p>Overview of patient visits and illness trends.</p>
        </div>

        <div class="date-box">
            📅 <span>May 2025</span>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="kpi-row">

        <div class="kpi-card blue">
            <h4>Total Patients</h4>
            <h2>256</h2>
            <p>This Year</p>
        </div>

        <div class="kpi-card green">
            <h4>Most Common Illness</h4>
            <h2>Flu</h2>
            <p>78 cases (30.5%)</p>
        </div>

        <div class="kpi-card purple">
            <h4>Avg Appointments</h4>
            <h2>213</h2>
            <p>Per Month</p>
        </div>

        <div class="insight-box">
    💡      <b>Insight:</b> Flu cases increased this month due to seasonal weather changes.
        </div>

    </div>

    <!-- CHART SECTION -->
    <div class="chart-row">

        <div class="chart-card">
            <h3>Illness Distribution</h3>
            <p>Top illnesses this month</p>
            <canvas id="pieChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Monthly Appointment Trend</h3>
            <p>Patient visit trend</p>
            <canvas id="lineChart"></canvas>
        </div>

    </div>

    <!-- FOOT KPI -->
    <div class="bottom-kpi">

            <div class="small-card">
                <p>Total Appointments</p>
                <h2>2,564</h2>
            </div>

            <div class="small-card">
                <p>Avg / Month</p>
                <h2>213</h2>
            </div>

            <div class="small-card">
                <p>Most Active Month</p>
                <h2>Dec</h2>
            </div>

            <div class="small-card growth">
                <p>Growth</p>
                <h2>+18%</h2>
            </div>

    </div>

</div>

</section>

<script src="doctorReport.js"></script>

</body>
</html>