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

if (morningBtn) {
    morningBtn.addEventListener("click", function() {
        summaryBox.classList.remove("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Morning Session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> 8:00 AM - 12:00 PM";
    });
}

if (afternoonBtn) {
    afternoonBtn.addEventListener("click", function() {
        summaryBox.classList.remove("hidden");

        if (selectedDate) selectedDate.innerHTML = "<strong>Selected Date:</strong> Today";
        if (appointmentType) appointmentType.innerHTML = "<strong>Appointment Type:</strong> Same-Day Consultation";
        if (selectedSession) selectedSession.innerHTML = "<strong>Selected Session:</strong> Afternoon Session";
        if (timeSlot) timeSlot.innerHTML = "<strong>Time Slot:</strong> 12:00 PM - 7:00 PM";
    });
}

let appointmentDateInput = document.querySelector("#appointmentDate");
let slotContainer = document.querySelector("#slotContainer");

let chosenAppointmentDate = "";
let chosenTimeSlot = "";

let allSlots = [
    "8:30 AM",
    "9:30 AM",
    "10:30 AM",
    "11:30 AM",
    "2:30 PM",
    "3:30 PM",
    "4:30 PM"
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

/* =========================
   SELF-ASSESSMENT PAGE
========================= */

let symptomChecker = document.querySelector("#symptomChecker");
let mainAssessmentCard = document.querySelector("#mainAssessmentCard");

let feverFluCard = document.querySelector("#feverFluCard");
let painInjuryCard = document.querySelector("#painInjuryCard");
let medicationConcernCard = document.querySelector("#medicationConcernCard");
let mentalHealthConcernCard = document.querySelector("#mentalHealthConcernCard");
let generalHealthConcernCard = document.querySelector("#generalHealthConcernCard");

if (symptomChecker && mainAssessmentCard)
{
    symptomChecker.addEventListener("click", function() {
        mainAssessmentCard.classList.remove("hidden");
    });
}

if (mainAssessmentCard)
{
    mainAssessmentCard.addEventListener("submit", function(event) {
        event.preventDefault();

        let selectedConcern = document.querySelector("input[name='mainAssessment']:checked");

        if (!selectedConcern) {
            alert("Please select one concern before continuing.");
            return;
        }

        feverFluCard.classList.add("hidden");
        painInjuryCard.classList.add("hidden");
        medicationConcernCard.classList.add("hidden");
        mentalHealthConcernCard.classList.add("hidden");
        generalHealthConcernCard.classList.add("hidden");

        if (selectedConcern.value === "feverFlu") {
            feverFluCard.classList.remove("hidden");
        } 
        else if (selectedConcern.value === "painInjury") {
            painInjuryCard.classList.remove("hidden");
        } 
        else if (selectedConcern.value === "medicationConcern") {
            medicationConcernCard.classList.remove("hidden");
        } 
        else if (selectedConcern.value === "mentalHealthConcern") {
            mentalHealthConcernCard.classList.remove("hidden");
        } 
        else if (selectedConcern.value === "generalHealthConcern") {
            generalHealthConcernCard.classList.remove("hidden");
        }
    });
}

let feverFluForm = document.querySelector("#feverFluForm");
let painInjuryForm = document.querySelector("#painInjuryForm");
let medicationConcernForm  = document.querySelector("#medicationConcernForm");
let mentalHealthConcernForm  = document.querySelector("#mentalHealthConcernForm");
let generalHealthConcernForm  = document.querySelector("#generalHealthConcernForm");


let resultCard = document.querySelector("#assessmentResultCard");
let resultText = document.querySelector("#assessmentResultText");

if (feverFluForm) {
    feverFluForm.addEventListener("submit", function(event) {
        event.preventDefault();
    
        let fever = document.querySelector("input[name='flu_fever']:checked");
        let duration = document.querySelector("input[name='flu_duration']:checked");
        let medication = document.querySelector("input[name='flu_medication']:checked");
    
        let warningSigns = document.querySelectorAll("input[name='flu_warning_signs']:checked");
        let symptoms = document.querySelectorAll("input[name='flu_symptoms']:checked");
    
        if (!fever || !duration || !medication) {
            alert("Please answer all required questions.");
            return;
        }
    
        let hasDangerSign = false;
    
        warningSigns.forEach(function(sign) {
            if (
                sign.value === "Persistent vomiting" ||
                sign.value === "Difficulty breathing" ||
                sign.value === "Chest pain"
            ) {
                hasDangerSign = true;
            }
        });
    
        let resultMessage = "";
    
        if (hasDangerSign) {
            resultMessage = "You should visit the clinic as soon as possible. Your symptoms may need urgent attention.";
        }
        else if (fever.value === "Yes" && duration.value === "More than 5 days") {
            resultMessage = "You should visit the clinic. Fever lasting more than 5 days should be checked by healthcare staff.";
        }
        else if (fever.value === "Yes" && duration.value === "2-5 days") {
            resultMessage = "You may monitor your symptoms, but visit the clinic if your fever gets worse or does not improve.";
        }
        else if (fever.value === "Yes" && duration.value === "Less than 2 days") {
            resultMessage = "You can monitor your condition first. Rest, drink enough water, and visit the clinic if symptoms worsen.";
        }
        else if (symptoms.length >= 3) {
            resultMessage = "You have several symptoms. Consider visiting the clinic if they affect your daily activities or continue for a few days.";
        }
        else {
            resultMessage = "Your symptoms appear mild based on your answers. Monitor your condition and visit the clinic if anything worsens.";
        }
    
        resultCard.classList.remove("hidden");
        resultText.innerHTML = resultMessage;
    });
}

if (painInjuryForm) {
    painInjuryForm.addEventListener("submit", function(event) {
        event.preventDefault();
    
        let resultMessage = "";
    
        let recent = document.querySelector("input[name='pain_injury_recent']:checked");
        let move = document.querySelector("input[name='pain_move_normal']:checked");
        let painLevel = document.querySelector("input[name='pain_level']:checked");
        let painLocation = document.querySelectorAll("input[name='pain_location']:checked");
        let painSwelling = document.querySelector("input[name='pain_swelling']:checked");
    
        if (!recent || !move || !painLevel || painLocation.length === 0 || !painSwelling) {
            alert("Please answer all required questions.");
            return;
        }
    
        let hasDangerLocation = false;
    
        painLocation.forEach(function(location) {
            if (
                location.value === "Head & Neck" ||
                location.value === "Abdomen & Torso" ||
                location.value === "Whole body"
            ) {
                hasDangerLocation = true;
            }
        });
    
        if (painLevel.value === "Severe" || move.value === "No" || painSwelling.value === "Yes") {
            resultMessage = "You should visit the clinic as soon as possible. Your pain or injury may need medical attention.";
        }
        else if (hasDangerLocation) {
            resultMessage = "You should visit the clinic as soon as possible. Your pain or injury may need medical attention.";
        }
        else if (recent.value === "Yes" && painLevel.value === "Moderate") {
            resultMessage = "You should consider visiting the clinic, especially if the pain continues, worsens, or affects movement.";
        }
        else if (painLevel.value === "Moderate") {
            resultMessage = "You should consider visiting the clinic, especially if the pain continues, worsens, or affects movement.";
        }
        else if (painLevel.value === "Mild" && move.value === "Yes" && painSwelling.value === "No") {
            resultMessage = "Your pain appears mild based on your answers. You may monitor it first, but visit the clinic if it worsens or does not improve.";
        }
        else {
            resultMessage = "Your pain appears mild based on your answers. You may monitor it first, but visit the clinic if it worsens or does not improve.";
        }
    
        resultCard.classList.remove("hidden");
        resultText.innerHTML = resultMessage;
    });
}

if (medicationConcernForm) {
    medicationConcernForm.addEventListener("submit", function(event) {
        event.preventDefault();
    
        let resultMessage = "";
    
        let prescribed = document.querySelector("input[name='medication_prescribed']:checked");
        let symptoms = document.querySelector("input[name='medication_symptoms']:checked");
        let issues = document.querySelectorAll("input[name='medication_issues']:checked");
        let severity = document.querySelector("input[name='medication_severity']:checked");
    
        if (!prescribed || !symptoms || issues.length === 0 || !severity) {
            alert("Please answer all required questions.");
            return;
        }
    
        let hasSideEffects = false;
        let medicationNotWorking = false;
        let unsureHowToTake = false;
    
        issues.forEach(function(issue) {
            if (issue.value === "Side effects") {
                hasSideEffects = true;
            }
    
            if (issue.value === "Medication not working") {
                medicationNotWorking = true;
            }
    
            if (issue.value === "Unsure how to take medication") {
                unsureHowToTake = true;
            }
        });
    
        if (severity.value === "Urgent concern") {
            resultMessage = "You should visit the clinic as soon as possible. Your medication concern may need urgent attention.";
        }
        else if (symptoms.value === "Yes" && hasSideEffects) {
            resultMessage = "You should speak with clinic staff or a pharmacist. You may be experiencing side effects from your medication.";
        }
        else if (medicationNotWorking) {
            resultMessage = "You should consider visiting the clinic or speaking with a pharmacist if your medication does not seem to be working.";
        }
        else if (unsureHowToTake) {
            resultMessage = "You should ask a pharmacist or healthcare staff for proper medication instructions before continuing.";
        }
        else if (severity.value === "Moderate concern") {
            resultMessage = "You should consider getting advice from the clinic or pharmacist, especially if the concern continues.";
        }
        else {
            resultMessage = "Your medication concern appears mild based on your answers. Monitor your condition and ask clinic staff if you are unsure.";
        }
    
        resultCard.classList.remove("hidden");
        resultText.innerHTML = resultMessage;
    });
}

if (mentalHealthConcernForm) {
    mentalHealthConcernForm.addEventListener("submit", function(event) {
        event.preventDefault();
    
        let resultMessage = "";
    
        let concentration = document.querySelector("input[name='mental_concentration']:checked");
        let duration = document.querySelector("input[name='mental_duration']:checked");
        let emotion = document.querySelector("input[name='mental_emotion']:checked");
        let speakProfessional = document.querySelector("input[name='mental_speak_professional']:checked");
    
        if (!concentration || !duration || !emotion || !speakProfessional) {
            alert("Please answer all required questions.");
            return;
        }
    
        if (
            emotion.value === "Low mood" &&
            duration.value === "More than 5 days"
        ) {
            resultMessage = "You should consider speaking with a healthcare professional or counsellor. Your emotional wellbeing matters, and getting support early can help.";
        }
        else if (
            emotion.value === "Anxious" &&
            duration.value === "More than 5 days"
        ) {
            resultMessage = "You should consider speaking with a healthcare professional or counsellor, especially if the anxiety affects your studies, work, sleep, or daily routine.";
        }
        else if (
            concentration.value === "Yes" &&
            (emotion.value === "Stressed" || emotion.value === "Anxious" || emotion.value === "Low mood")
        ) {
            resultMessage = "You may benefit from speaking with a healthcare professional or counsellor, especially if this is affecting your concentration or daily activities.";
        }
        else if (
            speakProfessional.value === "Yes" ||
            speakProfessional.value === "Maybe"
        ) {
            resultMessage = "It would be a good idea to speak with a healthcare professional or counsellor for guidance and support.";
        }
        else if (duration.value === "2-5 days") {
            resultMessage = "Monitor how you feel over the next few days. If it continues, worsens, or affects your daily life, consider speaking with a healthcare professional.";
        }
        else {
            resultMessage = "Based on your answers, your concern appears mild. Continue monitoring how you feel and seek support if it gets worse or starts affecting your daily life.";
        }
    
        resultCard.classList.remove("hidden");
        resultText.innerHTML = resultMessage;
    });
}

if (generalHealthConcernForm) {
    generalHealthConcernForm.addEventListener("submit", function(event) {
        event.preventDefault();
    
        let resultMessage = "";
    
        let concern = document.querySelector("input[name='general_concern']:checked");
        let duration = document.querySelector("input[name='general_duration']:checked");
        let worsening = document.querySelector("input[name='general_worsening']:checked");
        let dailyActivities = document.querySelector("input[name='general_daily_activities']:checked");
    
        if (!concern || !duration || !worsening || !dailyActivities) {
            alert("Please answer all required questions.");
            return;
        }
    
        if (
            worsening.value === "Yes" &&
            dailyActivities.value === "Yes"
        ) {
            resultMessage = "You should visit the clinic. Your symptoms are worsening and affecting your daily activities.";
        }
        else if (
            duration.value === "More than a week"
        ) {
            resultMessage = "You should consider visiting the clinic since your concern has lasted more than a week.";
        }
        else if (
            concern.value === "Need medical advice" ||
            concern.value === "Health check inquiry"
        ) {
            resultMessage = "You may visit the clinic or contact healthcare staff for advice regarding your concern.";
        }
        else if (
            worsening.value === "Not sure"
        ) {
            resultMessage = "Monitor your symptoms carefully. If they become worse or start affecting your daily activities, visit the clinic.";
        }
        else if (
            duration.value === "Few days" &&
            dailyActivities.value === "Yes"
        ) {
            resultMessage = "You should consider visiting the clinic if this concern is already affecting your daily activities.";
        }
        else {
            resultMessage = "Your concern appears mild based on your answers. Monitor your condition and visit the clinic if it worsens or lasts longer than expected.";
        }
    
        resultCard.classList.remove("hidden");
        resultText.innerHTML = resultMessage;
    });
}

/* =========================
    PROFILE DROPDOWN
========================= */

let profileBtn = document.querySelector("#profileBtn");
let profileDropdown = document.querySelector("#profileDropdown");

if (profileBtn && profileDropdown) {
    profileBtn.addEventListener("click", function() {
        profileDropdown.classList.toggle("showDropdown");
    });
}

