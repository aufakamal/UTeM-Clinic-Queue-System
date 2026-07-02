<div id="header">
    <div id="leftSection">
    <div class="logoBox">
        <img src="../login_register/loginRegisterImage/utemLogo.png" alt="UTeM Logo">
    </div>

    <h1>PKUTeM Clinic Queue System</h1>
</div>

    <nav>
        <ul>
            <li><a href="../admin/admin-dashboard.php">Dashboard</a></li>
            <li><a href="admin-appointments.php">Appointments</a></li>
            <li><a href="admin-queue.php">Queue</a></li>
            <li><a href="admin-history.php">History</a></li>
            <li><a href="admin-slots.php">Slots</a></li>
            <li><a href="admin-staff.php">Users</a></li>
        </ul>
    </nav>

    <div id="rightSection">
        <h1>Admin</h1>

        <div class="profileContainer">
            <button class="iconBtn" id="profileBtn" type="button" onclick="toggleMenu()">
                <img
                    class="icon"
                    id="profileIcon"
                    src="../patient/patientImages/profileIconDark.png"
                    alt="Profile Icon">
            </button>

            <div id="profileDropdown">
                <a href="profileAdmin.php">View Profile</a>
                <a href="../login_register/mainPage.php">Log Out</a>
            </div>
        </div>
    </div>
</div>
