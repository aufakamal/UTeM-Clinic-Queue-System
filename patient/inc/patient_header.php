<div id="header">
    <div id="leftSection">
        <!-- <button class="iconBtn">
            <img class="icon" src="../images/backIconDark.png" alt="Back Button">
        </button> -->

    <h1>UTeM Clinic Queue System</h1>
</div>

<nav>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>

        <li class="dropdown">
            Appointment
            <ul class="submenu">
                <li><a href="bookAppointment.php">Book Appointment</a></li>
                <li><a href="appointmentRecord.php">Appointment Record</a></li>
            </ul>
        </li>

        <li><a href="medicalRecord.php">Medical Record</a></li>
        <li><a href="selfAssessment.php">Self-Assessment</a></li>
    </ul>
</nav>

<div id="rightSection">
    <h1>Welcome, Patient!</h1>

    <div class="profileContainer">

        <button class="iconBtn" id="profileBtn" type="button">
            <img
                class="icon"
                id="profileIcon"
                src="patientImages/profileIconDark.png"
                alt="Profile Icon">
        </button>

        <div id="profileDropdown">
            <a href="profilePatient.php">View Profile</a>
            <a href="../login_register/mainPage.php">Log Out</a>
        </div>

    </div>
</div>

</div>

<script src="js/profile.js"></script>