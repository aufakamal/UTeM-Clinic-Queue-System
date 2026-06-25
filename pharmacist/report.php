<?php

$conn = new mysqli("localhost", "root", "", "clinic_db", 3306);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$dispensedSql = "SELECT COUNT(*) AS dispensedToday 
                 FROM prescription 
                 WHERE status = 'Dispensed'";

$dispensedResult = $conn->query($dispensedSql);
$dispensedRow = $dispensedResult->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pharmacist.css">

    <title>Pharmacist Report</title>
</head>

<body>
    <div id="header">
        <div id="leftSection">
            <button class="iconBtn">
                <img class="icon" src="../images/backIconDark.png">
            </button>

            <h1>UTeM Clinic Queue System</h1>
        </div>

        <nav>
            <ul>
                <li><a href="workspace.php">Workspace</a></li>
                <li><a href="report.php">Reports</a></li>
                <li><a href="medicine.php">Medicine Stock</a></li>
            </ul>
        </nav>

    <div id="rightSection">
    <h1>Welcome, Pharmacist!</h1>

    <div class="profileContainer">
        <button class="iconBtn" id="profileBtn" type="button">
            <img class="icon" src="../images/profileIconDark.png" alt="Profile Icon">
        </button>

        <div id="profileDropdown">
            <a href="profilePharmacist.html">View Profile</a>
            <a href="../login_register/login.php">Log Out</a>
        </div>
    </div>
    </div>
    </div>

    <section class="reportPage">

        <article class="reportHeader">
            <h2>Pharmacy Report</h2>
            <p>Daily summary for prescription review and medication dispensing.</p>
        </article>

        <div class="reportSummary">

            <article class="reportCard">
                <h3>Dispensed Today</h3>
                <p>21</p>
                <span>Completed prescriptions today</span>
            </article>

        </div>

        <article class="recordBox">
    <h2>Top Medicines Dispensed</h2>
    <p class="chartDesc">Most frequently dispensed medicines today.</p>

    <div class="verticalBarChart">

        <div class="yAxis">
            <p>20</p>
            <p>15</p>
            <p>10</p>
            <p>5</p>
            <p>0</p>
        </div>

        <div class="chartArea">

            <div class="chartLine lineOne"></div>
            <div class="chartLine lineTwo"></div>
            <div class="chartLine lineThree"></div>
            <div class="chartLine lineFour"></div>

            <div class="barItem">
                <b>18</b>
                <div class="verticalBar paracetamolBar"></div>
                <p>Paracetamol</p>
            </div>

            <div class="barItem">
                <b>12</b>
                <div class="verticalBar amoxicillinBar"></div>
                <p>Amoxicillin</p>
            </div>

            <div class="barItem">
                <b>10</b>
                <div class="verticalBar ibuprofenBar"></div>
                <p>Ibuprofen</p>
            </div>

            <div class="barItem">
                <b>8</b>
                <div class="verticalBar cetirizineBar"></div>
                <p>Cetirizine</p>
            </div>

            <div class="barItem">
                <b>6</b>
                <div class="verticalBar vitaminBar"></div>
                <p>Vitamin C</p>
            </div>

        </div>
    </div>
</article>
