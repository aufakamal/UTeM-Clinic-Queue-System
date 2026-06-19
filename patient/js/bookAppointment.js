/* =========================
   BOOK APPOINTMENT PAGE
========================= */
let sameDayBtn = document.querySelector("#sameDayBtn");
let scheduledBtn = document.querySelector("#scheduledBtn");

let sameDayCard = document.querySelector("#sameDayCard");
let scheduledCard = document.querySelector("#scheduledCard");

let morningBtn = document.querySelector("#morningBtn");
let afternoonBtn = document.querySelector("#afternoonBtn");

let summaryBox = document.querySelector(".summaryBox");

let confirmBtn = document.querySelector("#confirmBtn");
let info = document.querySelector(".info");

let selectedDate = document.querySelector("#selectedDate");
let selectedSession = document.querySelector("#selectedSession");
let timeSlot = document.querySelector("#timeSlot");
let appointmentType = document.querySelector("#appointmentType");

let appointmentDateHidden = document.querySelector("#appointmentDateHidden");
let appointmentTypeHidden = document.querySelector("#appointmentTypeHidden");
let sessionHidden = document.querySelector("#sessionHidden");
let timeSlotHidden = document.querySelector("#timeSlotHidden");

if (sameDayBtn && scheduledBtn && sameDayCard && scheduledCard) {
    sameDayBtn.addEventListener("click", function() {
        summaryBox.classList.add("hidden");
        info.classList.add("hidden");
        sameDayCard.classList.remove("hidden");
        scheduledCard.classList.add("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Please choose a session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> Not selected";
    });

    scheduledBtn.addEventListener("click", function() {
        summaryBox.classList.add("hidden");
        info.classList.add("hidden");
        sameDayCard.classList.add("hidden");
        scheduledCard.classList.remove("hidden");

        chosenAppointmentDate = "";
        chosenTimeSlot = "";

        if (appointmentDateInput) {
            appointmentDateInput.value = "";
        }

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Please select a date";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Scheduled Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Please choose a session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> Not selected";
    });
}

// let currentHour = new Date().getHours();

// if (currentHour >= 0 && morningBtn) {
//     morningBtn.disabled = true;
// }

// if (currentHour >= 19 && afternoonBtn) {
//     afternoonBtn.disabled = true;
// }

if (morningBtn) {
    morningBtn.addEventListener("click", function() {
        let currentHour = new Date().getHours();

        if (currentHour >= 12) {
            alert("Morning Session booking is closed as it is past 12 PM. Please book for Afternoon Session.");
            return;
        }

        summaryBox.classList.remove("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Morning Session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> 8:00 AM - 12:00 PM";

        if (appointmentDateHidden) appointmentDateHidden.value = "Today";
        if (appointmentTypeHidden) appointmentTypeHidden.value = "Same-Day Consultation";
        if (sessionHidden) sessionHidden.value = "Morning Session";
        if (timeSlotHidden) timeSlotHidden.value = "8:00 AM - 12:00 PM";
    });
}

if (afternoonBtn) {
    afternoonBtn.addEventListener("click", function() {

        let currentHour = new Date().getHours();

        if (currentHour >= 19) {
            alert("Afternoon Session booking is closed as it is past 7 PM and the clinic is closed. Please book tomorrow.");
            return;
        }

        summaryBox.classList.remove("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Afternoon Session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> 12:00 PM - 7:00 PM";

        if (appointmentDateHidden) appointmentDateHidden.value = "Today";
        if (appointmentTypeHidden) appointmentTypeHidden.value = "Same-Day Consultation";
        if (sessionHidden) sessionHidden.value = "Afternoon Session";
        if (timeSlotHidden) timeSlotHidden.value = "12:00 PM - 7:00 PM";
    });
}

let appointmentDateInput = document.querySelector("#appointmentDate");
let slotContainer = document.querySelector("#slotContainer");

let chosenAppointmentDate = "";
let chosenTimeSlot = "";

//temporary
let allSlots = [
    "8:00 AM - 9:00 AM",
    "9:00 AM - 10:00 AM",
    "10:00 AM - 11:00 AM"
];

// object that contains array
let unavailableSlots = {
    "2026-06-15": ["9:30 AM", "2:30 PM"],
    "2026-06-16": ["8:30 AM", "11:30 AM", "4:30 PM"],
    "2026-06-17": ["10:30 AM"]
};

let tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1);

if (appointmentDateInput)
{
    appointmentDateInput.min =
        tomorrow.toISOString().split("T")[0];
}


if (appointmentDateInput && slotContainer) {
    appointmentDateInput.addEventListener("change", function() {
        chosenAppointmentDate = appointmentDateInput.value;
        chosenTimeSlot = "";

        slotContainer.innerHTML = "";

        if (!chosenAppointmentDate) {
            slotContainer.innerHTML = "<p>Please select a date first</p>";
            return;
        }

        // change to date object, not string, baru boleh compare date nanti
        let today = new Date();
        today.setHours(0, 0, 0, 0);

        // change to date object, not string
        let chosenDateObject = new Date(chosenAppointmentDate);
        chosenDateObject.setHours(0, 0, 0, 0);

        let day = chosenDateObject.getDay();

        // validation to not being able to choose weekends (no greyed out option)
        if (day === 0 || day === 6) {
            alert("Appointments are not available on weekends.");
            appointmentDateInput.value = "";
            slotContainer.innerHTML = "<p>Please select a weekday.</p>";
            return;
        }

        if (chosenDateObject <= today) {
            alert("Please select a future date.");
            appointmentDateInput.value = "";
            slotContainer.innerHTML = "<p>Please select a future date.</p>";
            return;
        }

        allSlots.forEach(function(slot) {
            // create button dynamically (according to how many slots available)
            let slotBtn = document.createElement("button");

            // to allow the button to act like a normal button, not submit button
            slotBtn.type = "button";

            // to add CSS to the button
            slotBtn.classList.add("slotBtn");

            slotBtn.textContent = slot;

            if (unavailableSlots[chosenAppointmentDate] &&
                unavailableSlots[chosenAppointmentDate].includes(slot)) {
                    slotBtn.disabled = true;
            }

            slotBtn.addEventListener("click", function() {
                document.querySelectorAll(".slotBtn").forEach(function(button) {
                    button.classList.remove("selectedSlot");
                });

                slotBtn.classList.add("selectedSlot");

                chosenTimeSlot = slot;

                if (appointmentDateHidden) appointmentDateHidden.value = chosenAppointmentDate;
                if (appointmentTypeHidden) appointmentTypeHidden.value = "Scheduled Consultation";
                if (sessionHidden) sessionHidden.value = "";
                if (timeSlotHidden) timeSlotHidden.value = chosenTimeSlot;

                summaryBox.classList.remove("hidden");

                if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> " + chosenAppointmentDate;
                if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Scheduled Consultation";
                if (selectedSession) selectedSession.classList.add("hidden");
                if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> " + chosenTimeSlot;
            });

            // add the button to the page
            slotContainer.appendChild(slotBtn);
        });
    });
}


if (confirmBtn && info) {
    confirmBtn.addEventListener("click", function() {
        info.classList.remove("hidden");
    });
}