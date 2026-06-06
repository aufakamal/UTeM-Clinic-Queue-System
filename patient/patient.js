/* =========================
   BOOK APPOINTMENT PAGE
========================= */
let sameDayBtn = document.querySelector("#sameDayBtn");
let scheduledBtn = document.querySelector("#scheduledBtn");

let sameDayCard = document.querySelector("#sameDayCard");
let scheduledCard = document.querySelector("#scheduledCard");

let morningBtn = document.querySelector("#morningBtn");
let afternoonBtn = document.querySelector("#afternoonBtn");

let confirmBtn = document.querySelector("#confirmBtn");
let info = document.querySelector(".info");

let selectedDate = document.querySelector("#selectedDate");
let selectedSession = document.querySelector("#selectedSession");
let timeSlot = document.querySelector("#timeSlot");
let appointmentType = document.querySelector("#appointmentType");

if (sameDayBtn && scheduledBtn && sameDayCard && scheduledCard) {
    sameDayBtn.addEventListener("click", function() {
        sameDayCard.classList.remove("hidden");
        scheduledCard.classList.add("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Please choose a session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> Not selected";
    });

    scheduledBtn.addEventListener("click", function() {
        sameDayCard.classList.add("hidden");
        scheduledCard.classList.remove("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Please select a date";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Scheduled Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Please choose a session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> Not selected";
    });
}

if (morningBtn) {
    morningBtn.addEventListener("click", function() {
        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Morning Session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> 8:00 AM - 12:00 PM";
    });
}

if (afternoonBtn) {
    afternoonBtn.addEventListener("click", function() {
        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Afternoon Session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> 12:00 PM - 7:00 PM";
    });
}

if (confirmBtn && info) {
    confirmBtn.addEventListener("click", function() {
        info.classList.remove("hidden");
    });
}


/* =========================
   APPOINTMENT RECORD PAGE
========================= */
let upcomingTab = document.querySelector("#upcomingTab");
let previousTab = document.querySelector("#previousTab");

let upcomingRecords = document.querySelector("#upcomingRecords");
let previousRecords = document.querySelector("#previousRecords");

if (upcomingTab && previousTab && upcomingRecords && previousRecords) {
    upcomingTab.addEventListener("click", function() {
        upcomingTab.classList.add("activeTab");
        previousTab.classList.remove("activeTab");

        upcomingRecords.classList.remove("hidden");
        previousRecords.classList.add("hidden");
    });

    previousTab.addEventListener("click", function() {
        previousTab.classList.add("activeTab");
        upcomingTab.classList.remove("activeTab");

        previousRecords.classList.remove("hidden");
        upcomingRecords.classList.add("hidden");
    });
}