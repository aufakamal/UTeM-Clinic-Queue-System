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

        <section id="scheduledCard" class="hidden">
            <h2>Scheduled Consultation</h2>
            <p>Select a preferred date and available time slot for your checkup.</p>

            <div class="topCards">
                <article>
                    <h2>Select Date</h2>
                    <h3>Choose your preferred appointment date</h3>
                    <p>Click 📅 to select a date</p>
                    <br>

                    <form id="appointmentDateForm" action="">
                        <input type="date" id="appointmentDate">
                    </form>
                </article>

                <article>
                    <h2>Select Time Slot</h2>
                    <h3>Choose an available time slot</h3>


                    <form id="appointmentSlotForm">
                        <div id="slotContainer" class="slotContainer">
                            <p>Please select a date first</p>
                        </div>
                    </form>
                </article>
            </div>
        </section>

        <section class="summaryBox hidden">
            <h2>Summary</h2>

            <div class="summaryGrid">
                <p id="selectedDate"><strong>Selected Date:</strong> Today</p>
                <p id="appointmentType"><strong>Appointment Type:</strong> Same-Day Consultation</p>
                <p id="selectedSession"><strong>Selected Session:</strong> Session</p>
                <p id="timeSlot"><strong>Time Slot:</strong> 8:00 AM - 12:00 PM</p>
            </div>

            <button id="confirmBtn" class="submitBtn">Confirm Booking</button>
            <p class="info hidden">ⓘ You have booked an appointment. Go to Appointment>Appointment Record to get more information.</p>
        </section>

    </div>
    
    <?php include('inc/patient_footer.php'); ?>

<script src="patient.js"></script>
</body>
</html>