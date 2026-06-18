<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient.css">
    <title>UTeM Clinic Queue System</title>
</head>

<body>

    <?php include('inc/patient_header.php'); ?>
    
    <section>
        <h2>Appointment Record</h2>
        <p>Track your upcoming visits and review previous bookings.</p>

        <div class="appointmentTabs">
            <button type="button" id="upcomingTab" class="activeTab">Upcoming</button>
            <button type="button" id="previousTab">Previous</button>
        </div>

        <article id="upcomingRecords">
            <h2>20/6/2026</h2>
            <p>Appointment Status: Ongoing</p>

            <div class="singleTable">
                <table>
                    <tr>
                        <th>Appointment Type</th>
                        <th>Time Slot</th>
                        <th>Queue Number</th>
                        <th>Queue Status</th>
                        <th>Room Number</th>
                    </tr>
                    <tr>
                        <td>Appointment</td>
                        <td>11:00 AM - 12:00 PM</td>
                        <td>S34</td>
                        <td>Waiting</td>
                        <td>Room 3</td>
                    </tr>
                </table>
            </div>
        </article>

        <article id="previousRecords" class="hidden">
            <h2>1/1/2026</h2>
            <p>Appointment Status: Cancelled / No-Show / Completed</p>

            <div class="singleTable">
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Queue Number</th>
                        <th>Queue Status</th>
                        <th>Room Number</th>
                    </tr>
                    <tr>
                        <td>DD/MM/YY</td>
                        <td>11:00 AM - 12:00 PM</td>
                        <td>S34</td>
                        <td>Completed</td>
                        <td>Room 3</td>
                    </tr>
                </table>
            </div>
        </article>
    </section>

    <?php include('inc/patient_footer.php'); ?>
    
<script src="patient.js"></script>
</body>
</html>