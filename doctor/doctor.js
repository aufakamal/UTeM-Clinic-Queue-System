// ==========================
// DATA PESAKIT
// ==========================
const patients = {
    "A103": {
        name: "Siti Sarah binti Roslan",
        gender: "Female",
        id: "D0323578456",
        bloodType: "O+",
        allergies: [
            "Seafood (Patient Reported)",
            "Penicillin (Doctor Confirmed)"
        ],
        chronicDiseases: [
            "Asthma"
        ],
        currentMedication: [
            "Ventolin Inhaler"
        ]
    },
    D514698002: {
        name: "Amir bin Amar",
        gender: "Male",
        id: "D514698002",
        blood: "B+",
        allergies: ["Dust", "Peanuts"],
        chronic: ["Asthma"],
        medication: ["Ventolin Inhaler"],
        visits: [
            {
                date: "22/06/2026, 11:35 pm",
                reason: "Headache",
                findings: "BP 120/80, HR 88",
                diagnosis: "Tension Headache",
                treatment: "Rest + hydration",
                prescription: "Ibuprofen 400mg"
            }
        ]
    }
};

// ==========================
// LOAD PAGE
// ==========================
document.addEventListener("DOMContentLoaded", () => {

    // START SESSION
    document.querySelectorAll(".startBtn").forEach(button => {
        button.addEventListener("click", () => {

            const queue = button.dataset.queue;
            const patient = patients[queue];

            if (patient) {
                showPatientSession(patient, queue);
                const savedVisits = localStorage.getItem("visitHistory_" + queue);
                if (savedVisits) {
                    visitHistory = JSON.parse(savedVisits);
                } else {
                    visitHistory = [];
                }
                renderVisitHistory();
            }
        });
    });


        // SAVE DRAFT BUTTON
    const saveDraftBtn = document.getElementById("saveDraftBtn");

    if (saveDraftBtn) {
        saveDraftBtn.addEventListener("click", () => {
            showActionMessage("Draft is saved.");
        });
    }

    // COMPLETE CONSULTATION BUTTON
    function completeConsultation() {

    if (!selectedQueue || !selectedPatient) {
        alert("Please select a patient first.");
        return;
    }

    const completedData = {
        queue: selectedQueue,
        patient: selectedPatient,
        completedAt: new Date().toISOString()
    };

    localStorage.setItem("completed_" + selectedQueue, JSON.stringify(completedData));

    showActionMessage("Consultation completed: " + selectedQueue);

    // OPTIONAL: disable button in left panel
    const btn = document.querySelector(`.startBtn[data-queue="${selectedQueue}"]`);
    if (btn) {
        btn.textContent = "Completed";
        btn.disabled = true;
        btn.style.background = "#9CA3AF";
    }
}
});

// ==========================
// START CONSULTATION
// ==========================
function showPatientSession(patient, queue) {
    selectedQueue = queue;
    selectedPatient = patient;

    const defaultWorkspace = document.getElementById("defaultWorkspace");
    const placeholderText = document.getElementById("placeholderText");
    const patientRecordDisplay = document.getElementById("patientRecordDisplay");

    if (placeholderText) placeholderText.style.display = "none";
    if (patientRecordDisplay) patientRecordDisplay.style.display = "block";

    document.getElementById("pName").textContent = patient.name;
    document.getElementById("pGender").textContent = patient.gender;
    document.getElementById("pID").textContent = patient.id;
    document.getElementById("pBlood").textContent = patient.bloodType;

    renderEditableList("pAllergyList", patient.allergies);
    renderEditableList("pChronicList", patient.chronicDiseases);
    renderEditableList("pMedList", patient.currentMedication);
}

// ==========================
// EDITABLE MEDICAL INFO
// ==========================
function renderEditableList(containerId, items) {

    const container = document.getElementById(containerId);

    if (!container) {
        return;
    }

    container.innerHTML = "";

    items.forEach(item => {

        const row = document.createElement("div");
        row.className = "editableItem";

        row.innerHTML = `
            <input type="text" value="${item}" readonly>
            <button type="button" onclick="editItem(this)">Edit</button>
        `;

        container.appendChild(row);
    });
}

function editItem(button) {

    const input = button.previousElementSibling;

    if (input.readOnly) {

        input.readOnly = false;
        input.focus();
        button.textContent = "Save";
        button.classList.add("saveEditBtn");

    } else {

        input.readOnly = true;
        button.textContent = "Edit";
        button.classList.remove("saveEditBtn");

    }
}

function addItem(containerId) {

    const container = document.getElementById(containerId);

    if (!container) {
        return;
    }

    const row = document.createElement("div");
    row.className = "editableItem";

    row.innerHTML = `
        <input type="text" placeholder="Enter new item">
        <button type="button" onclick="editItem(this)" class="saveEditBtn">Save</button>
    `;

    container.appendChild(row);

    const input = row.querySelector("input");
    input.focus();
}


function openTab(tabId) {

    // hide semua
    document.querySelectorAll(".tabSection").forEach(tab => {
        tab.classList.remove("active");
    });

    // show only selected
    document.getElementById(tabId).classList.add("active");
}


// ==========================
// ACTION MESSAGE POPUP
// ==========================
function showActionMessage(message) {

    const messagePopup = document.getElementById("messagePopup");
    const messageText = document.getElementById("messageText");

    if (messageText) {
        messageText.textContent = message;
    }

    if (messagePopup) {
        messagePopup.style.display = "flex";
    }
}

function saveDraft() {

    if (!selectedQueue || !selectedPatient) {
        alert("Please select a patient first.");
        return;
    }

    const draftData = {
        queue: selectedQueue,
        patient: selectedPatient,
        savedAt: new Date().toISOString()
    };

    localStorage.setItem("draft_" + selectedQueue, JSON.stringify(draftData));

    showActionMessage("Draft saved for " + selectedQueue);
}

function switchTab(tabName, button) {

    // active button
    document.querySelectorAll(".miniTab").forEach(tab => {
        tab.classList.remove("active");
    });

    if (button) {
        button.classList.add("active");
    }

    // hide semua section
    const overview = document.querySelector(".overviewSection");
    const visits = document.getElementById("visitsSection");
    const diagnosis = document.getElementById("diagnosisSection");
    const prescription = document.getElementById("prescriptionSection");

    if (overview) overview.style.display = "none";
    if (visits) visits.style.display = "none";
    if (diagnosis) diagnosis.style.display = "none";
    if (prescription) prescription.style.display = "none";

    // show selected section
    if (tabName === "overview" && overview) {
        overview.style.display = "block";
    }

    if (tabName === "visits" && visits) {
        visits.style.display = "block";
    }

    if (tabName === "diagnosis" && diagnosis) {
        diagnosis.style.display = "block";
    }

    if (tabName === "prescription" && prescription) {
        prescription.style.display = "block";
    }
}

// ==========================
// DIAGNOSIS TAB
// ==========================
function addFinding() {

    const input = document.getElementById("newFinding");
    const output = document.getElementById("findingOutput");

    if (!input || !output) {
        console.log("Input or output not found");
        return;
    }

    const value = input.value.trim();

    if (value === "") {
        return;
    }

    const div = document.createElement("div");
    div.className = "savedFinding";

    div.innerHTML = `
        <strong>Additional Finding</strong>
        <span>${value}</span>
    `;

    output.appendChild(div);

    input.value = "";
    input.focus();
}


// ==========================
//  PRESCRIPTION
// ==========================
function updateStock() {
    const select = document.getElementById("medicine");
    const stock = select.options[select.selectedIndex].dataset.stock;

    document.getElementById("stockBox").innerText = stock + " tablets";
}

function addPrescription() {

    const med = document.getElementById("medicine").value;
    const dosage = document.getElementById("dosage").value;
    const freq = document.getElementById("frequency").value;
    const duration = document.getElementById("duration").value;
    const notes = document.getElementById("notes").value;

    const tbody = document.getElementById("rxTableBody");

    if (!tbody) {
        console.log("rxTableBody not found");
        return;
    }

    const no = tbody.querySelectorAll("tr").length + 1;

    const tr = document.createElement("tr");

    tr.innerHTML = `
        <td>${no}</td>
        <td>${med}</td>
        <td>${dosage}</td>
        <td>${freq}</td>
        <td>${duration}</td>
        <td>${notes || "-"}</td>
        <td>
            <button type="button" class="deleteRxBtn" onclick="deleteRx(this)">🗑</button>
        </td>
    `;

    tbody.appendChild(tr);

    document.getElementById("notes").value = "";
}

function deleteRx(button) {
    button.closest("tr").remove();

    const rows = document.querySelectorAll("#rxTableBody tr");

    rows.forEach((row, index) => {
        row.children[0].textContent = index + 1;
    });
}

// ==========================
// SAVE COMPLETION
// ==========================
let visitHistory = [];

function saveCompletion() {

    if (!selectedPatient || !selectedQueue) {
        alert("Please select a patient first.");
        return;
    }

    // ambil reason
    const reason = document.getElementById("reasonVisitText")?.innerText || "-";

    // ambil clinical findings static
    let clinicalFindings = [];

    document.querySelectorAll(".findingBox").forEach(box => {
        const title = box.querySelector("strong")?.innerText || "";
        const value = box.querySelector("span")?.innerText || "";

        if (title && value) {
            clinicalFindings.push(title + ": " + value);
        }
    });

    // ambil additional findings yang doctor add
    document.querySelectorAll("#findingOutput .savedFinding").forEach(item => {
        clinicalFindings.push(item.innerText);
    });

    // ambil diagnosis
    const diagnosis = document.getElementById("diagnosisText")?.innerText || "-";

    // ambil treatment plan
    const treatment = document.getElementById("treatmentText")?.innerText || "-";

    // ambil prescription terbaru dari table
    let prescriptions = [];

    document.querySelectorAll("#rxTableBody tr").forEach(row => {
        const cells = row.children;

        if (cells.length >= 6) {
            prescriptions.push({
                medicine: cells[1].innerText,
                dosage: cells[2].innerText,
                frequency: cells[3].innerText,
                duration: cells[4].innerText,
                notes: cells[5].innerText
            });
        }
    });

    // create visit record
    const visit = {
        patientName: selectedPatient.name,
        date: new Date().toLocaleString(),
        doctor: "Dr Anis",
        appointmentType: "Same-Day Consultation",
        status: "Completed",
        reason: reason,
        clinicalFindings: clinicalFindings,
        diagnosis: diagnosis,
        treatment: treatment,
        prescriptions: prescriptions
    };

    // latest masuk atas
    visitHistory.unshift(visit);

    // save dalam browser
    localStorage.setItem("visitHistory_" + selectedQueue, JSON.stringify(visitHistory));

    // display dekat Visits
    renderVisitHistory();

    alert("Consultation saved and added to Visits.");
}

function renderVisitHistory() {

    const container = document.getElementById("visitHistoryList");

    if (!container) {
        return;
    }

    container.innerHTML = "";

    visitHistory.forEach((visit) => {

        const prescriptionText = visit.prescriptions.length > 0
            ? visit.prescriptions.map(p => {
                return `${p.medicine} | ${p.dosage} | ${p.frequency} | ${p.duration}`;
            }).join("<br>")
            : "-";

        const clinicalText = visit.clinicalFindings.length > 0
            ? visit.clinicalFindings.join("<br>")
            : "-";

        const card = document.createElement("div");
        card.className = "visitCard";

        card.innerHTML = `
            <h2 class="visitTitle">Medical Record</h2>

            <table class="visitTable">
                <thead>
                    <tr>
                        <th>Time Slot</th>
                        <th>Doctor</th>
                        <th>Appointment Type</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>${visit.date}</td>
                        <td>${visit.doctor}</td>
                        <td>${visit.appointmentType}</td>
                        <td><span class="status done">${visit.status}</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="visitDetail">
                <div><b>Reason for Visit:</b> ${visit.reason}</div>
                <div><b>Clinical Findings:</b><br>${clinicalText}</div>
                <div><b>Diagnosis:</b> ${visit.diagnosis}</div>
                <div><b>Treatment Plan:</b> ${visit.treatment}</div>
                <div><b>Prescription:</b><br>${prescriptionText}</div>
            </div>
        `;

        container.appendChild(card);
    });
}



function renderVisits(visits) {

    const box = document.getElementById("visitHistoryList");

    box.innerHTML = "";

    visits.forEach(v => {

        const div = document.createElement("div");

        div.className = "visitCard";

        div.innerHTML = `
            <h3>Medical Record</h3>

            <p><b>Reason:</b> ${v.reason}</p>
            <p><b>Diagnosis:</b> ${v.diagnosis}</p>
            <p><b>Prescription:</b> ${v.prescription}</p>
        `;

        box.appendChild(div);
    });
}