<?php

    session_start();
    include('../dbconnect.php');

    $userID = $_SESSION['userID'];

    $sql = "SELECT patientType
            FROM patient_profile
            WHERE userID = '$userID'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    $patientType = $row['patientType'];

    $availableSlots = [];

    if(isset($_POST['checkAvailability'])) {
        $selectedDate = $_POST['appointmentDate'];

        $sqlSlots = "SELECT *
                    FROM time_slot
                    WHERE slotDate = '$selectedDate' AND slotType = 'Scheduled' AND capacity > 0";

        $resultSlots = mysqli_query($conn, $sqlSlots);

        while($rowSlot = mysqli_fetch_assoc($resultSlots)) {
            $availableSlots[] = $rowSlot;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inc/patient.css">
    <title>UTeM Clinic Queue System</title>
</head>

<body>

    <?php include('inc/patient_header.php'); ?>

    <div class="bookAppointmentPage">

        <section>
            <h2>Book Appointment</h2>

            <p>Select the type of appointment.</p>

            <div class="topCards">
                <button id="sameDayBtn" class="cardBtn">
                    <h2>Same-Day Consultation</h2>
                    <h3>Same-day consultation for medicine and MC</h3>
                    <p>Click to book now</p>
                </button>

                <button id="scheduledBtn" class="cardBtn">
                    <h2>Scheduled Consultation</h2>
                    <h3>Non-urgent, routine health evaluation for early detection</h3>
                    <p>Click to book now</p>
                </button>
            </div>
        </section>

        <section id="sameDayCard" class="hidden">
            <h2>Same-Day Consultation</h2>
            <p>Choose your preferred session for today.</p>

            <div class="topCards">
                <button id="morningBtn" class="cardBtn">
                    <h2>Morning Session</h2>
                    <h3>8:00 AM - 12:00 PM</h3>
                    <p>Any time within these hours is acceptable.</p>
                </button>

                <button id="afternoonBtn" class="cardBtn">
                    <h2>Afternoon Session</h2>
                    <h3>12:00 PM - 7:00 PM</h3>
                    <p>Any time within these hours is acceptable.</p>
                </button>
            </div>
        </section>

        <section id="scheduledCard" class="<?=isset($_POST['checkAvailability']) ? '' : 'hidden' ?>">
            <h2>Scheduled Consultation</h2>
            <p>Select a preferred date and available time slot for your checkup.</p>

            <div class="topCards">
                <article>
                    <h2>Select Date</h2>
                    <h3>Choose your preferred appointment date</h3>
                    <p>Click 📅 to select a date</p>
                    <br>

                    <form method="POST">
                        <input type="date" id="appointmentDate" name="appointmentDate" value="<?= isset($selectedDate) ? $selectedDate : ''; ?>">
                        <button type="submit" name="checkAvailability" class="submitBtn">Check Availability</button>
                    </form>
                </article>

                <article>
                    <h2>Select Time Slot</h2>
                    <h3>Choose an available time slot</h3>

                    <form id="appointmentSlotForm">
                        <div id="slotContainer" class="slotContainer">
                            <?php
                                if(empty($availableSlots)) {
                                    echo "<p>No available slot</p>";
                                }
                                else {
                                    foreach($availableSlots as $slot)
                                    {
                            ?>

                            <button type="button" class="slotBtn"
                                    data-slotid="<?= $slot['slotID'] ?>"
                                    data-start="<?= $slot['startTime'] ?>"
                                    data-end="<?= $slot['endTime'] ?>">

                            <?=
                                date("g:i A", strtotime($slot['startTime']));
                            ?>
                            -
                            <?=
                                date("g:i A", strtotime($slot['endTime']));
                            ?>
                            </button>

                            <?php
                                    }
                                }
                            ?>
                        </div>
                    </form>
                </article>
            </div>
        </section>

        <section class="summaryBox hidden">
            <h2>Summary</h2>

            <form action="processAppointment.php" method="POST">
                <div class="summaryGrid">
                    <p id="selectedDate"><strong>Selected Date:</strong> - </p>

                    <p id="appointmentType"><strong>Appointment Type:</strong> - </p>

                    <p id="selectedSession"><strong>Selected Session:</strong> - </p>

                    <p id="timeSlot"><strong>Time Slot:</strong> - </p>

                    <!-- Hidden inputs sent to PHP -->
                    <input type="hidden" name="slotID" id="slotIDHidden">
                    <input type="hidden" name="appointmentDate" id="appointmentDateHidden">
                    <input type="hidden" name="appointmentType" id="appointmentTypeHidden">
                    <input type="hidden" name="session" id="sessionHidden">
                    <input type="hidden" name="timeSlot" id="timeSlotHidden">
                </div>

                <?php if ($patientType == 'Staff') : ?>

                <div class="appointmentForSection">
                    <h3>Appointment For</h3>

                    <label><input type="radio" name="appointmentFor" value="Self" checked>Self</label>

                    <label><input type="radio" name="appointmentFor" value="Dependant">Dependant</label>
                </div>

                <div id="dependantSection" class="hidden">

                    <h3>Dependant Information</h3>

                    <div>
                        <label for="dependantName">Dependant Name</label>

                        <input type="text" id="dependantName" name="dependantName">
                    </div>

                    <div>
                        <label for="dependantRelationship">Relationship</label>

                        <input type="text" id="dependantRelationship" name="dependantRelationship">
                    </div>
                </div>

                <?php else : ?>

                <input type="hidden" name="appointmentFor" value="Self">

                <?php endif; ?>

                <button type="submit" id="confirmBtn" class="submitBtn">Confirm Booking</button>
            </form>
        </section>
    </div>
    
    <?php include('inc/patient_footer.php'); ?>

<script src="js/bookAppointment.js"></script>
</body>
</html>