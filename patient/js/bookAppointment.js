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

let selectedDate = document.querySelector("#selectedDate");
let selectedSession = document.querySelector("#selectedSession");
let timeSlot = document.querySelector("#timeSlot");
let appointmentType = document.querySelector("#appointmentType");

let appointmentDateHidden = document.querySelector("#appointmentDateHidden");
let appointmentTypeHidden = document.querySelector("#appointmentTypeHidden");
let sessionHidden = document.querySelector("#sessionHidden");
let timeSlotHidden = document.querySelector("#timeSlotHidden");

let slotIDHidden = document.querySelector("#slotIDHidden");

// dont use this as this use UTC time
// let today = new Date().toISOString().split("T")[0]; 

// follow Malaysia time
let today = new Date();

let year = today.getFullYear();
let month = String(today.getMonth() + 1).padStart(2, "0");
let day = String(today.getDate()).padStart(2, "0");

today = `${year}-${month}-${day}`;

if (sameDayBtn && scheduledBtn && sameDayCard && scheduledCard) {
    sameDayBtn.addEventListener("click", function() {
        summaryBox.classList.add("hidden");
        sameDayCard.classList.remove("hidden");
        scheduledCard.classList.add("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Please choose a session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> Not selected";
    });

    scheduledBtn.addEventListener("click", function() {
        summaryBox.classList.add("hidden");
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

        if (appointmentDateHidden) appointmentDateHidden.value = today;
        if (appointmentTypeHidden) appointmentTypeHidden.value = "Same-Day";
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

        if (appointmentDateHidden) appointmentDateHidden.value = today;
        if (appointmentTypeHidden) appointmentTypeHidden.value = "Same-Day";
        if (sessionHidden) sessionHidden.value = "Afternoon Session";
        if (timeSlotHidden) timeSlotHidden.value = "12:00 PM - 7:00 PM";
    });
}

let appointmentDateInput = document.querySelector("#appointmentDate");

let tomorrow = new Date();

tomorrow.setDate(tomorrow.getDate() + 1);

if (appointmentDateInput) {
    let year = tomorrow.getFullYear();

    let month = String(tomorrow.getMonth() + 1).padStart(2, "0");

    let day = String(tomorrow.getDate()).padStart(2, "0");

    appointmentDateInput.min =`${year}-${month}-${day}`;
}


if (confirmBtn) {
    confirmBtn.addEventListener("click", function() {
    });
}

let appointmentForRadios = document.querySelectorAll('input[name="appointmentFor"]');

let dependantSection = document.querySelector("#dependantSection");

if (appointmentForRadios.length > 0 && dependantSection) {
    appointmentForRadios.forEach(function(radio) {
        radio.addEventListener("change", function() {
            if (this.value === "Dependant") {
                dependantSection.classList.remove("hidden");
            }
            else {
                dependantSection.classList.add("hidden");
            }
        });
    });
}

let slotButtons = document.querySelectorAll(".slotBtn");

slotButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        slotButtons.forEach(function(btn) {
            btn.classList.remove("selectedSlot");
        });

        this.classList.add("selectedSlot");

        let slotID =  this.dataset.slotid;

        let startTime = this.dataset.start;

        let endTime = this.dataset.end;

        if(slotIDHidden) {
            slotIDHidden.value = slotID;
        }

        if(appointmentDateHidden) {
            appointmentDateHidden.value = appointmentDateInput.value;
        }

        if(appointmentTypeHidden) {
            appointmentTypeHidden.value = "Scheduled";
        }

        if(timeSlotHidden) {
            timeSlotHidden.value = startTime + " - " + endTime;
        }

        summaryBox.classList.remove("hidden");

        if(selectedDate) {
            selectedDate.innerHTML = "<strong>Selected Date:</strong> " + appointmentDateInput.value;
        }

        if(appointmentType) { 
            appointmentType.innerHTML = "<strong>Appointment Type:</strong> Scheduled Consultation";
        }

        if(timeSlot)
        {
            timeSlot.innerHTML = "<strong>Time Slot:</strong> " + startTime + " - " + endTime;
        }
    });
});