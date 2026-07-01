<?php

include('../dbconnect.php'); // ubah path kalau dbconnect lain folder

date_default_timezone_set('Asia/Kuala_Lumpur');

$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

$monthStart = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
$monthLabel = date('F Y', strtotime($monthStart));

/* =========================
   TOTAL PATIENTS THIS MONTH
========================= */

$totalPatientsQuery = $conn->query("
    SELECT COUNT(DISTINCT a.userID) AS total
    FROM appointment a
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE MONTH(ts.slotDate) = $selectedMonth
");

$totalPatientsThisMonth = $totalPatientsQuery->fetch_assoc()['total'] ?? 0;

/* =========================
   TOTAL CONSULTATIONS YEAR
========================= */

$totalConsultationQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM consultation c
    INNER JOIN `queue` q ON c.queueID = q.queueID
    INNER JOIN attendance atd ON q.attendanceID = atd.attendanceID
    INNER JOIN appointment a ON atd.appointmentID = a.appointmentID
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $selectedYear
");

$totalConsultations = $totalConsultationQuery->fetch_assoc()['total'] ?? 0;

/* =========================
   TOTAL APPOINTMENTS YEAR
========================= */

$totalAppointmentQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM appointment a
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $selectedYear
");

$totalAppointmentsYear = $totalAppointmentQuery->fetch_assoc()['total'] ?? 0;
$avgAppointments = round($totalAppointmentsYear / 12);

/* =========================
   MONTHLY APPOINTMENT TREND
========================= */

$monthNames = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec'
];

$monthlyData = array_fill(0, 12, 0);

$monthlyQuery = $conn->query("
    SELECT MONTH(ts.slotDate) AS monthNo, COUNT(*) AS total
    FROM appointment a
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $selectedYear
    GROUP BY MONTH(ts.slotDate)
    ORDER BY MONTH(ts.slotDate)
");

while ($row = $monthlyQuery->fetch_assoc()) {

    $index = (int)$row['monthNo'] - 1;
    $monthlyData[$index] = (int)$row['total'];

}

/* =========================
   ILLNESS DISTRIBUTION
========================= */

$illnessQuery = $conn->query("
    SELECT
        COALESCE(NULLIF(TRIM(c.diagnosis), ''), 'Unknown') AS illness,
        COUNT(*) AS total
    FROM consultation c
    INNER JOIN `queue` q ON c.queueID = q.queueID
    INNER JOIN attendance atd ON q.attendanceID = atd.attendanceID
    INNER JOIN appointment a ON atd.appointmentID = a.appointmentID
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $selectedYear
    AND MONTH(ts.slotDate) = $selectedMonth
    GROUP BY illness
    ORDER BY total DESC
");

$allIllness = [];

while ($row = $illnessQuery->fetch_assoc()) {

    $allIllness[] = [
        'illness' => $row['illness'],
        'total' => (int)$row['total']
    ];

}

$totalIllnessCases = array_sum(array_column($allIllness, 'total'));

$topIllness = $allIllness[0]['illness'] ?? 'No Data';
$topIllnessCases = $allIllness[0]['total'] ?? 0;

$topIllnessPercent = $totalIllnessCases > 0
    ? number_format(($topIllnessCases / $totalIllnessCases) * 100, 1)
    : 0;

/* =========================
   DETECT TIE
========================= */

$highestCount = $topIllnessCases;

$tiedIllnesses = array_filter(
    $allIllness,
    function($item) use ($highestCount) {
        return $item['total'] == $highestCount;
    }
);

$illnessLabels = [];
$illnessData = [];

foreach ($allIllness as $illness) {

    $illnessLabels[] = $illness['illness'];
    $illnessData[] = $illness['total'];

}

if (empty($illnessLabels)) {

    $illnessLabels = ['No Data'];
    $illnessData = [0];

}

/* =========================
   MOST ACTIVE MONTH
========================= */

$activeMonthQuery = $conn->query("
    SELECT
        DATE_FORMAT(ts.slotDate, '%b') AS monthName,
        COUNT(*) AS total
    FROM appointment a
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $selectedYear
    GROUP BY MONTH(ts.slotDate)
    ORDER BY total DESC
    LIMIT 1
");

$activeMonthRow = $activeMonthQuery->fetch_assoc();

$mostActiveMonth = $activeMonthRow['monthName'] ?? '-';
$mostActiveCount = $activeMonthRow['total'] ?? 0;

/* =========================
   GROWTH
========================= */

$currentMonthQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM appointment a
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $selectedYear
    AND MONTH(ts.slotDate) = $selectedMonth
");

$currentMonthAppointments = $currentMonthQuery->fetch_assoc()['total'] ?? 0;

$previousMonthDate = date('Y-m-01', strtotime($monthStart . ' -1 month'));
$previousYear = (int)date('Y', strtotime($previousMonthDate));
$previousMonth = (int)date('m', strtotime($previousMonthDate));

$previousMonthQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM appointment a
    INNER JOIN time_slot ts ON a.slotID = ts.slotID
    WHERE YEAR(ts.slotDate) = $previousYear
    AND MONTH(ts.slotDate) = $previousMonth
");

$previousMonthAppointments = $previousMonthQuery->fetch_assoc()['total'] ?? 0;

if ($previousMonthAppointments > 0) {

    $growth = (($currentMonthAppointments - $previousMonthAppointments) / $previousMonthAppointments) * 100;
    $growthText = ($growth >= 0 ? '+' : '') . number_format($growth, 0) . '%';

} else {

    $growthText = 'N/A';

}

/* =========================
   INSIGHT
========================= */

if ($topIllness !== 'No Data') {

    $insightText = "$topIllness is the most common illness in $monthLabel with $topIllnessCases case(s).";

} else {

    $insightText = "No illness data recorded for $monthLabel.";

}

?>

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

    <div class="report-wrapper">

        <!-- TITLE SECTION -->
        <div class="report-header">

            <div>
                <h2>Doctor Dashboard Overview</h2>
                <p>Overview of patient visits and illness trends.</p>
            </div>

            <div class="date-box">

                <form method="GET" style="display:flex; gap:10px; align-items:center; margin:0;">

                    📅

                    <select name="month" onchange="this.form.submit()">

                        <?php

                        $months = [
                            1 => 'Jan',
                            2 => 'Feb',
                            3 => 'Mar',
                            4 => 'Apr',
                            5 => 'May',
                            6 => 'Jun',
                            7 => 'Jul',
                            8 => 'Aug',
                            9 => 'Sep',
                            10 => 'Oct',
                            11 => 'Nov',
                            12 => 'Dec'
                        ];

                        for ($m = 1; $m <= 12; $m++) {

                            $selected = ($m == $selectedMonth) ? 'selected' : '';
                            echo "<option value='$m' $selected>{$months[$m]}</option>";

                        }

                        ?>

                    </select>

                    <input type="hidden" name="year" value="<?= $selectedYear ?>">

                </form>

            </div>

        </div>

        <!-- KPI CARDS -->
        <div class="kpi-row">

            <div class="kpi-card green">
                <h4>Most Common Illness</h4>
                <h2><?= htmlspecialchars($topIllness) ?></h2>
                <p><?= $topIllnessCases ?> cases (<?= $topIllnessPercent ?>%)</p>
            </div>

            <div class="kpi-card blue">
                <h4>Total Patients</h4>
                <h2><?= number_format($totalPatientsThisMonth) ?></h2>
                <p>This Month</p>
            </div>

            <div class="kpi-card purple">
                <h4>Avg Appointments</h4>
                <h2><?= number_format($avgAppointments) ?></h2>
                <p>Per Month</p>
            </div>

            <div class="kpi-card orange">
                <h4>Total Consultations</h4>
                <h2><?= number_format($totalConsultations) ?></h2>
                <p>This Year</p>
            </div>

            <div class="kpi-card purple">
                <h4>Most Active Month</h4>
                <h2><?= $mostActiveMonth ?></h2>
                <p><?= $mostActiveCount ?> appointments</p>
            </div>

        </div>

        <div class="insight-box">
            💡 <b>Insight:</b> <?= htmlspecialchars($insightText) ?>
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

    </div>

</section>

<script>
const reportData = {
    illnessLabels: <?= json_encode($illnessLabels) ?>,
    illnessData: <?= json_encode($illnessData, JSON_NUMERIC_CHECK) ?>,
    monthlyLabels: <?= json_encode($monthNames) ?>,
    monthlyData: <?= json_encode($monthlyData, JSON_NUMERIC_CHECK) ?>
};
</script>

<script src="doctorReport.js"></script>

</body>
</html>
