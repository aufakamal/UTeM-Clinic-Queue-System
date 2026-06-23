<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Report</title>
    <link rel="stylesheet" href="doctor.css">
</head>

<body>
        <?php include('inc/doctor_header.php'); ?>


    <section class="drReportPage">

        <article class="drHeaderCard">
            <div>
                <h2>Doctor's Report Dashboard</h2>
                <p>Overview of patient illness, monthly appointments and medicine usage.</p>
            </div>

            <div class="drDateBox">
                <span>📅</span>
                <p id="reportMonth">May 2025</p>
            </div>
        </article>

        <!-- SUMMARY CARDS -->
        <div class="drSummaryGrid">
            <article class="drSummaryCard">
                <div class="drIconBox blue">👥</div>
                <div>
                    <h3>Total Patients<br>This Month</h3>
                    <h1 id="totalPatients">0</h1>
                    <span class="greenText">+18% from last month</span>
                </div>
            </article>

            <article class="drSummaryCard">
                <div class="drIconBox green">🦠</div>
                <div>
                    <h3>Most Common Illness<br>This Month</h3>
                    <h1 id="commonIllness">-</h1>
                    <span id="commonIllnessDesc">-</span>
                </div>
            </article>

            <article class="drSummaryCard">
                <div class="drIconBox red">💊</div>
                <div>
                    <h3>Medicine Usage Alert<br>Low Stock</h3>
                    <h1 id="lowStockMedicine">-</h1>
                    <span id="lowStockDesc">-</span>
                </div>
            </article>
        </div>

        <!-- CHARTS -->
        <div class="drChartGrid">

            <article class="drChartBox">
                <h2>Illness Distribution</h2>
                <p>Top illnesses this month</p>

                <div class="drIllnessWrap">
                    <div id="illnessPieChart" class="drPieChart"></div>
                    <div id="illnessLegend" class="drLegend"></div>
                </div>
            </article>

            <article class="drChartBox">
                <h2>Monthly Appointment</h2>
                <p>Patient visits trend</p>

                <div id="appointmentChart" class="drLineChart"></div>
            </article>

            <article class="drChartBox">
                <h2>Medicine Usage</h2>
                <p>Most used medicines this month</p>

                <div id="medicineUsageChart" class="drBarChart"></div>
            </article>

        </div>

        <!-- TABLES -->
        <div class="drTableGrid">

            <article class="drTableBox">
                <div class="drTableHeader">
                    <div>
                        <h2>💊 Medicine Stock List</h2>
                        <p>Current medicine stock status from Pharmacy</p>
                    </div>

                    <a href="pharmacyUpdate.html">View All Medicine →</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Usage</th>
                        </tr>
                    </thead>

                    <tbody id="medicineTableBody"></tbody>
                </table>
            </article>

            <article class="drTableBox">
                <div class="drTableHeader">
                    <div>
                        <h2>👥 Patient Summary</h2>
                        <p>Summary of recent patient records</p>
                    </div>

                    <a href="patientRecord.html">View All Patients →</a>
                </div>

                <div class="drSearchArea">
                    <input type="text" id="patientSearchInput" placeholder="Search patient name or illness">
                    <select id="statusFilter">
                        <option value="All">All Status</option>
                        <option value="Completed">Completed</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Illness</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody id="patientTableBody"></tbody>
                </table>
            </article>

        </div>

    </section>

    <script src="doctorReport.js"></script>
</body>
</html>