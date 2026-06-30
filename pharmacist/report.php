<?php

$conn = new mysqli("localhost", "root", "", "clinic_db", 3306);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

function getCount($conn, $sql)
{
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

$pending = getCount($conn, "SELECT COUNT(*) AS total FROM prescription WHERE status = 'Pending'");
$ready = getCount($conn, "SELECT COUNT(*) AS total FROM prescription WHERE status = 'Ready'");
$dispensed = getCount($conn, "SELECT COUNT(*) AS total FROM prescription WHERE status = 'Dispensed'");
$totalPrescription = getCount($conn, "SELECT COUNT(*) AS total FROM prescription");
$lowStock = getCount($conn, "SELECT COUNT(*) AS total FROM medicine WHERE stockQuantity <= 50");

$totalStatus = $pending + $ready + $dispensed;

if ($totalStatus == 0)
{
    $pendingPercent = 0;
    $readyPercent = 0;
    $dispensedPercent = 0;
}
else
{
    $pendingPercent = round(($pending / $totalStatus) * 100);
    $readyPercent = round(($ready / $totalStatus) * 100);
    $dispensedPercent = round(($dispensed / $totalStatus) * 100);
}

if ($totalPrescription == 0)
{
    $dispensingRate = 0;
}
else
{
    $dispensingRate = round(($dispensed / $totalPrescription) * 100);
}

$topMedicineSql = "
SELECT 
    m.medicineName,
    SUM(pi.quantity) AS totalQuantity
    FROM prescription_item pi
    INNER JOIN medicine m ON pi.medicineID = m.medicineID
    GROUP BY m.medicineID, m.medicineName
    ORDER BY totalQuantity DESC
    LIMIT 5
    ";

$topMedicineResult = $conn->query($topMedicineSql);

$topMedicineNames = array();
$topMedicineValues = array();

if ($topMedicineResult && $topMedicineResult->num_rows > 0)
{
    while ($medicine = $topMedicineResult->fetch_assoc())
    {
        $topMedicineNames[] = $medicine['medicineName'];
        $topMedicineValues[] = $medicine['totalQuantity'];
    }
}

$dailyLabels = array();
$dailyValues = array();

$dailySql = "
SELECT 
    prescriptionDate,
    COUNT(*) AS total
    FROM prescription
    WHERE status = 'Dispensed'
    GROUP BY prescriptionDate
    ORDER BY prescriptionDate ASC
    LIMIT 7
    ";

$dailyResult = $conn->query($dailySql);

if ($dailyResult && $dailyResult->num_rows > 0)
{
    while ($row = $dailyResult->fetch_assoc())
    {
        $dailyLabels[] = date("d M", strtotime($row['prescriptionDate']));
        $dailyValues[] = $row['total'];
    }
}

if (count($dailyLabels) < 2)
{
    $dailyLabels = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
    $dailyValues = array(1, 2, 1, 3, 2, 4, 2);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacist Report</title>
    <link rel="stylesheet" href="pharmacist.css">
</head>

<body>

    <?php include('inc/pharmacist_header.php'); ?>

    <section class="reportPage">

        <article class="reportHeader reportTop">
            <div>
                <h2>Pharmacy Dashboard Overview</h2>
                <p>Overview of medication dispensing and prescription workflow.</p>
            </div>

            <div class="datePickerBox">
                <span>📅</span>
                <input type="month" value="<?php echo date('Y-m'); ?>">
            </div>
        </article>

        <div class="reportSummary">

            <article class="reportCard summaryCard">
                <h3>Pending Prescription</h3>
                <p><?php echo $pending; ?></p>
                <span>Awaiting review</span>
            </article>

            <article class="reportCard summaryCard">
                <h3>Ready to Dispense</h3>
                <p><?php echo $ready; ?></p>
                <span>Prepared medication</span>
            </article>

            <article class="reportCard summaryCard">
                <h3>Dispensed</h3>
                <p><?php echo $dispensed; ?></p>
                <span>Completed prescription</span>
            </article>

            <article class="reportCard summaryCard">
                <h3>Dispensing Rate</h3>
                <p><?php echo $dispensingRate; ?>%</p>
                <span>Completed rate</span>
            </article>

        </div>

        <article class="reminderBox">
            <div>
                <b>Reminder:</b>
                <span><?php echo $lowStock; ?> medicine item(s) are below minimum stock level. Please review the medicine inventory.</span>
            </div>

            <a href="medicine.php">View Low Stock</a>
        </article>

        <div class="dashboardGrid">

            <article class="reportBox">
                <h2>Daily Dispensing Trend</h2>
                <p class="chartDesc">Number of prescriptions dispensed per day.</p>

                <div class="chartBox">
                    <canvas id="dailyChart"></canvas>
                </div>
            </article>

            <article class="reportBox">
                <h2>Top Medicines Dispensed</h2>
                <p class="chartDesc">Most frequently prescribed medicines.</p>

                <div class="medicineBox">

                    <?php
                    if (count($topMedicineNames) > 0)
                    {
                        $maxMedicine = max($topMedicineValues);

                        for ($i = 0; $i < count($topMedicineNames); $i++)
                        {
                            if ($maxMedicine == 0)
                            {
                                $barWidth = 0;
                            }
                            else
                            {
                                $barWidth = round(($topMedicineValues[$i] / $maxMedicine) * 100);
                            }
                    ?>

                    <div class="medicineItem">
                        <div class="medicineNameRow">
                            <h3><?php echo $topMedicineNames[$i]; ?></h3>
                            <b><?php echo $topMedicineValues[$i]; ?></b>
                        </div>

                        <div class="medicineBar">
                            <div style="width: <?php echo $barWidth; ?>%;"></div>
                        </div>
                    </div>

                    <?php
                        }
                    }
                    else
                    {
                        echo "<p>No medicine data available.</p>";
                    }
                    ?>

                </div>
            </article>

        </div>

        <div class="bottomReportBox">

        <article class="bottomItem purpleItem">

            <div>
                <h3>Total Prescriptions</h3>
                <p><?php echo $totalPrescription; ?></p>
                <span>All prescription records</span>
            </div>

        </article>

        <article class="bottomItem greenItem">

            <div>
                <h3>Completed Prescriptions</h3>
                <p><?php echo $dispensed; ?></p>
                <span>Successfully dispensed</span>
            </div>

        </article>

        <article class="bottomItem orangeItem">

            <div>
                <h3>Pending Review</h3>
                <p><?php echo $pending; ?></p>
                <span>Awaiting pharmacist review</span>
            </div>

        </article>

        </div>

    </section>

    <script>
        const statusData = {
            pending: <?php echo $pending; ?>,
            ready: <?php echo $ready; ?>,
            dispensed: <?php echo $dispensed; ?>
        };

        const dailyLabels = <?php echo json_encode($dailyLabels); ?>;
        const dailyValues = <?php echo json_encode($dailyValues); ?>;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/pharmacist.js"></script>
    <script src="js/report.js"></script>

</body>
</html>

<?php
$conn->close();
?>