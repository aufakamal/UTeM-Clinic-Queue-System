<?php

$conn = new mysqli("localhost", "root", "", "clinic_db", 3306);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$search = "";
$filter = "all";

if (isset($_GET['search']))
{
    $search = $_GET['search'];
}

if (isset($_GET['filter']))
{
    $filter = $_GET['filter'];
}

$totalSql = "SELECT COUNT(*) AS totalMedicine FROM medicine";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();

$stockSql = "SELECT SUM(stockQuantity) AS totalStock FROM medicine";
$stockResult = $conn->query($stockSql);
$stockRow = $stockResult->fetch_assoc();

$lowStockSql = "SELECT COUNT(*) AS lowStock FROM medicine WHERE stockQuantity < 60";
$lowStockResult = $conn->query($lowStockSql);
$lowStockRow = $lowStockResult->fetch_assoc();

$lowAlertSql = "SELECT * FROM medicine WHERE stockQuantity < 60 ORDER BY stockQuantity ASC LIMIT 4";
$lowAlertResult = $conn->query($lowAlertSql);

$sql = "SELECT * FROM medicine 
        WHERE (medicineName LIKE '%$search%' OR genericName LIKE '%$search%')";

if ($filter == "available")
{
    $sql .= " AND stockQuantity >= 60";
}
else if ($filter == "low")
{
    $sql .= " AND stockQuantity < 60";
}

$sql .= " ORDER BY medicineID ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Stock</title>
    <link rel="stylesheet" href="pharmacist.css">
</head>

<body>

    <?php include('inc/pharmacist_header.php'); ?>

    <section class="reportPage">

        <article class="reportHeader medicineHero">
            <div>
                <h2>Medicine Stock</h2>
                <p>View medicine stock and availability in pharmacy.</p>
            </div>
        </article>

        <div class="medicineDashboard">

            <div class="medicineMain">

                <div class="reportSummary medicineSummary">

                    <article class="reportCard">
                        <h3>Total Medicines</h3>
                        <p><?php echo $totalRow['totalMedicine']; ?></p>
                        <span>Medicine records</span>
                    </article>

                    <article class="reportCard">
                        <h3>Total Stock Quantity</h3>
                        <p><?php echo $stockRow['totalStock']; ?></p>
                        <span>Available quantity</span>
                    </article>

                    <article class="reportCard">
                        <h3>Low Stock Items</h3>
                        <p><?php echo $lowStockRow['lowStock']; ?></p>
                        <span>Need restock</span>
                    </article>

                </div>

                <article class="reportBox">
                    <h2>Search & Filter</h2>

                    <form method="GET" action="medicine.php" class="medicineFilterForm">

                        <div class="searchMedicineBox">
                            <label>Search Medicine</label>
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search by medicine name or generic name..." 
                                value="<?php echo $search; ?>"
                            >
                        </div>

                        <div class="filterMedicineBox">
                            <label>Filter by Status</label>

                            <div class="filterButtons">
                                <button 
                                    type="submit" 
                                    name="filter" 
                                    value="all" 
                                    class="<?php echo ($filter == 'all') ? 'activeFilter' : ''; ?>"
                                >
                                    All
                                </button>

                                <button 
                                    type="submit" 
                                    name="filter" 
                                    value="available" 
                                    class="<?php echo ($filter == 'available') ? 'availableFilter activeFilter' : 'availableFilter'; ?>"
                                >
                                    Available
                                </button>

                                <button 
                                    type="submit" 
                                    name="filter" 
                                    value="low" 
                                    class="<?php echo ($filter == 'low') ? 'lowFilter activeFilter' : 'lowFilter'; ?>"
                                >
                                    Low Stock
                                </button>

                                <a href="medicine.php" class="clearFilterBtn">Clear Filter</a>
                            </div>
                        </div>

                    </form>
                </article>

            </div>

            <article class="lowStockAlert">
                <h2>Low Stock Alert</h2>
                <p>Medicines that need to be restocked soon.</p>

                <?php
                if ($lowAlertResult->num_rows > 0)
                {
                    while ($alert = $lowAlertResult->fetch_assoc())
                    {
                        echo "<div class='alertRow'>";
                        echo "<span>" . $alert['medicineName'] . "</span>";
                        echo "<b>" . $alert['stockQuantity'] . " units</b>";
                        echo "</div>";
                    }
                }
                else
                {
                    echo "<p>No low stock medicines.</p>";
                }
                ?>
            </article>

        </div>

        <article class="recordBox">
            <h2>Medicine List</h2>

            <table>
                <tr>
                    <th>Medicine ID</th>
                    <th>Medicine Name</th>
                    <th>Generic Name</th>
                    <th>Description</th>
                    <th>Stock Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php

                if ($result->num_rows > 0)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>" . $row['medicineID'] . "</td>";
                        echo "<td>" . $row['medicineName'] . "</td>";
                        echo "<td>" . $row['genericName'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['stockQuantity'] . "</td>";

                        if ($row['stockQuantity'] < 60)
                        {
                            echo "<td><span class='waitingStatus'>Low Stock</span></td>";
                        }
                        else
                        {
                            echo "<td><span class='doneStatus'>Available</span></td>";
                        }

                        echo "<td><button class='viewMedicineBtn' type='button'>Update Stock</button></td>";
                        echo "</tr>";
                    }
                }
                else
                {
                    echo "<tr>";
                    echo "<td colspan='7'>No medicine record found.</td>";
                    echo "</tr>";
                }

                ?>

            </table>
        </article>

    </section>

</body>
</html>

<?php
$conn->close();
?>