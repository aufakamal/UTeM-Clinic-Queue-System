
let currentPatient = null;
let currentQueueId = null;

function startSessionFromQueue(btn) {
    console.log("BUTTON CLICKED");

    const queueId = btn.dataset.queueid;
    console.log("QUEUE ID:", queueId);
}

function startSessionFromQueue(btn) {

    const queueId = btn.dataset.queueid;

    fetch("getNextQueue.php")
    .then(res => res.json())
    .then(data => {

        if (!data) {
            alert("No patient");
            return;
        }

        currentPatient = data;
        currentQueueId = data.queue_id;

        fetch("lockQueue.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ queue_id: currentQueueId })
        });

        loadPatient(data);
    });
}

function loadPatient(p) {

    document.getElementById("patientRecordDisplay").style.display = "block";

    document.getElementById("pName").innerText = p.fullName;
    document.getElementById("pGender").innerText = p.gender;
    document.getElementById("pID").innerText = p.userID;
    document.getElementById("pBlood").innerText = p.bloodType;

    // clear + show empty editable lists
    document.getElementById("pAllergyList").innerHTML = "";
    document.getElementById("pChronicList").innerHTML = "";
    document.getElementById("pMedList").innerHTML = "";
}

function endSession() {

    const payload = {
        queue_id: currentQueueId,
        diagnosis: document.getElementById("diagnosisInput")?.value || "",
        treatment: document.getElementById("treatmentInput")?.value || "",
        prescription: collectPrescription()
    };

    fetch("endSession.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(payload)
    });

    currentPatient = null;
    currentQueueId = null;

    document.getElementById("patientRecordDisplay").style.display = "none";

    alert("Session completed");
}

function collectPrescription() {

    let rows = document.querySelectorAll("#rxTableBody tr");
    let data = [];

    rows.forEach(row => {

        if (row.id === "emptyRow") return;

        let cells = row.children;

        data.push({
            medicine: cells[1].innerText,
            dosage: cells[2].innerText,
            frequency: cells[3].innerText,
            duration: cells[4].innerText,
            instruction: cells[5].innerText
        });
    });

    return data;
}

document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".endBtn").forEach(button => {
        button.disabled = true;
        button.addEventListener("click", endSession);
    });

});

function showPatientSession(patient, userID) {

    selectedQueue = userID;
    selectedPatient = patient;

    const placeholderText = document.getElementById("placeholderText");
    const patientRecordDisplay = document.getElementById("patientRecordDisplay");
    const viewOnlyRecordDisplay = document.getElementById("viewOnlyRecordDisplay");
    const searchPatientView = document.getElementById("searchPatientView");

    if (placeholderText) placeholderText.style.display = "none";
    if (patientRecordDisplay) patientRecordDisplay.style.display = "block";
    if (viewOnlyRecordDisplay) viewOnlyRecordDisplay.style.display = "none";
    if (searchPatientView) searchPatientView.style.display = "none";

    hideAllSections();

    const overview = document.querySelector(".overviewSection");
    if (overview) overview.style.display = "block";

    document.querySelectorAll(".miniTab").forEach(tab => {
        tab.classList.remove("active");
    });

    const firstTab = document.querySelector(".miniTab");
    if (firstTab) firstTab.classList.add("active");

    document.getElementById("pName").textContent = patient.name || "-";
    document.getElementById("pGender").textContent = patient.gender || "-";
    document.getElementById("pID").textContent = patient.id || "-";
    document.getElementById("pBlood").textContent = patient.bloodType || "-";

    renderEditableList("pAllergyList", patient.allergies || ["-"]);
    renderEditableList("pChronicList", patient.chronicDiseases || ["-"]);
    renderEditableList("pMedList", patient.currentMedication || ["-"]);
}

function hideAllSections() {

    const visits = document.getElementById("visitsSection");
    const diagnosis = document.getElementById("diagnosisSection");
    const prescription = document.getElementById("prescriptionSection");
    const overview = document.querySelector(".overviewSection");

    if (overview) overview.style.display = "none";
    if (visits) visits.style.display = "none";
    if (diagnosis) diagnosis.style.display = "none";
    if (prescription) prescription.style.display = "none";
}

function renderEditableList(containerId, items) {

    const container = document.getElementById(containerId);

    if (!container) return;

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

function switchTab(tabName, button) {

    document.querySelectorAll(".miniTab").forEach(tab => {
        tab.classList.remove("active");
    });

    if (button) {
        button.classList.add("active");
    }

    hideAllSections();

    const overview = document.querySelector(".overviewSection");
    const visits = document.getElementById("visitsSection");
    const diagnosis = document.getElementById("diagnosisSection");
    const prescription = document.getElementById("prescriptionSection");

    if (tabName === "overview" && overview) overview.style.display = "block";
    if (tabName === "visits" && visits) visits.style.display = "block";
    if (tabName === "diagnosis" && diagnosis) diagnosis.style.display = "block";
    if (tabName === "prescription" && prescription) prescription.style.display = "block";
}

/* =========================
   SEARCH PATIENT
========================= */

function searchPatientLive() {

    const input = document.getElementById("searchPatientInput");
    const box = document.getElementById("searchResultBox");

    if (!input || !box || !window.searchPatientsData) return;

    const keyword = input.value.toLowerCase().trim();

    if (keyword === "") {
        box.innerHTML = "";
        return;
    }

    let html = "";

    window.searchPatientsData.forEach(p => {

        const text = (p.fullName + " " + p.userID).toLowerCase();

        if (text.includes(keyword)) {
            html += `
                <div class="patientSearchResult">
                    <div>
                        <h4>${p.fullName}</h4>
                        <p>${p.userID}</p>
                    </div>

                    <button type="button" onclick="viewPatientRecord('${p.userID}')">
                        View
                    </button>
                </div>
            `;
        }
    });

    box.innerHTML = html || `<div class="noPatientFound">No patient found</div>`;
}

function viewPatientRecord(userID) {

    const patient = window.patientRecordsData[userID];

    if (!patient) {
        alert("Patient record not found.");
        return;
    }

    selectedQueue = null;
    selectedPatient = null;

    const placeholderText = document.getElementById("placeholderText");
    const patientRecordDisplay = document.getElementById("patientRecordDisplay");
    const viewOnlyRecordDisplay = document.getElementById("viewOnlyRecordDisplay");
    const searchPatientView = document.getElementById("searchPatientView");

    if (placeholderText) placeholderText.style.display = "none";
    if (patientRecordDisplay) patientRecordDisplay.style.display = "none";
    if (viewOnlyRecordDisplay) viewOnlyRecordDisplay.style.display = "block";
    if (searchPatientView) searchPatientView.style.display = "none";

    hideAllSections();

    document.getElementById("vName").textContent = patient.fullName || "-";
    document.getElementById("vGender").textContent = patient.gender || "-";
    document.getElementById("vBlood").textContent = patient.bloodType || "-";
    document.getElementById("vID").textContent = patient.userID || "-";
    document.getElementById("vDOB").textContent = formatDate(patient.dateOfBirth) || "-";
    document.getElementById("vType").textContent = patient.patientType || "-";

    document.getElementById("vAllergy").textContent = patient.allergy || "-";
    document.getElementById("vChronic").textContent = patient.chronicCondition || "-";
    document.getElementById("vMed").textContent = patient.currentMed || "-";
}

function formatDate(dateValue) {

    if (!dateValue) return "-";

    const date = new Date(dateValue);

    if (isNaN(date)) return dateValue;

    return date.toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "long",
        year: "numeric"
    });
}

/* =========================
   DIAGNOSIS
========================= */

let clinicalData = {
    reason: "",
    diagnosis: "",
    treatment: "",
    observation: "",
    test: ""
};

function saveClinical() {

    clinicalData.reason = document.getElementById("reasonInput")?.value || "";
    clinicalData.diagnosis = document.getElementById("diagnosisInput")?.value || "";
    clinicalData.treatment = document.getElementById("treatmentInput")?.value || "";
    clinicalData.observation = document.getElementById("observationInput")?.value || "";
    clinicalData.test = document.getElementById("testInput")?.value || "";

    if (selectedPatient) {
        selectedPatient.clinical = clinicalData;
    }
}

function addFinding() {

    const input = document.getElementById("newFinding");
    const output = document.getElementById("findingOutput");

    if (!input || !output) return;

    const value = input.value.trim();

    if (value === "") return;

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

/* =========================
   ADD MEDICAL INFO
========================= */

function openAllergyInput() {
    document.getElementById("allergyInputBox").style.display = "block";
}

function saveAllergy() {
    saveNewEditableItem("allergyInput", "pAllergyList", "allergyInputBox");
}

function openChronicInput() {
    document.getElementById("chronicInputBox").style.display = "block";
}

function saveChronic() {
    saveNewEditableItem("chronicInput", "pChronicList", "chronicInputBox");
}

function openMedicationInput() {
    document.getElementById("medInputBox").style.display = "block";
}

function saveMedication() {
    saveNewEditableItem("medInput", "pMedList", "medInputBox");
}

function saveNewEditableItem(inputId, containerId, boxId) {

    const input = document.getElementById(inputId);
    const value = input.value.trim();

    if (!value) return;

    const container = document.getElementById(containerId);

    const div = document.createElement("div");
    div.className = "editableItem";

    div.innerHTML = `
        <input type="text" value="${value}" readonly>
        <button type="button" onclick="editItem(this)">Edit</button>
    `;

    container.appendChild(div);

    input.value = "";
    document.getElementById(boxId).style.display = "none";
}

/* =========================
   PRESCRIPTION
========================= */

function updateStock() {

    const select = document.getElementById("medicine");

    if (!select || select.selectedIndex < 0) return;

    const stock = select.options[select.selectedIndex].dataset.stock || "-";
    const stockBox = document.getElementById("stockBox");

    if (stockBox) {
        stockBox.innerText = stock === "-" ? "-" : stock + " tablets";
    }
}

function addPrescription() {

    const medSelect = document.getElementById("medicine");
    const dosage = document.getElementById("dosage").value;
    const freq = document.getElementById("frequency").value;
    const duration = document.getElementById("duration").value;
    const instruction = document.getElementById("instruction").value;
    const tbody = document.getElementById("rxTableBody");

    if (!medSelect.value || !dosage || !freq || !duration || !instruction) {
        alert("⚠️ Please complete all fields before adding prescription");
        return;
    }

    const emptyRow = document.getElementById("emptyRow");

    if (emptyRow) {
        emptyRow.remove();
    }

    const no = tbody.querySelectorAll("tr").length + 1;
    const med = medSelect.options[medSelect.selectedIndex].text;

    const tr = document.createElement("tr");

    tr.innerHTML = `
        <td>${no}</td>
        <td>${med}</td>
        <td>${dosage}</td>
        <td>${freq}</td>
        <td>${duration}</td>
        <td>${instruction}</td>
        <td>
            <button type="button" onclick="deleteRx(this)">🗑</button>
        </td>
    `;

    tbody.appendChild(tr);
}

function deleteRx(button) {

    button.closest("tr").remove();

    const rows = document.querySelectorAll("#rxTableBody tr");

    rows.forEach((row, index) => {
        row.children[0].textContent = index + 1;
    });

    if (rows.length === 0) {
        document.getElementById("rxTableBody").innerHTML = `
            <tr id="emptyRow">
                <td colspan="7" style="text-align:center;">
                    No prescription added yet
                </td>
            </tr>
        `;
    }
}

function savePrescription() {

    const rows = document.querySelectorAll("#rxTableBody tr");

    if (rows.length === 0 || document.getElementById("emptyRow")) {
        alert("⚠️ No prescription to save!");
        return;
    }

    alert("✅ Prescription saved successfully!");

    document.getElementById("rxTableBody").innerHTML = `
        <tr id="emptyRow">
            <td colspan="7" style="text-align:center;">
                No prescription added yet
            </td>
        </tr>
    `;
}

/* =========================
   END SESSION
========================= */

function endSession() {

    if (!selectedQueue || !selectedPatient) {
        alert("No active session!");
        return;
    }

    const confirmEnd = confirm("Are you sure you want to end this consultation?");

    if (!confirmEnd) return;

    selectedQueue = null;
    selectedPatient = null;

    document.getElementById("patientRecordDisplay").style.display = "none";
    document.getElementById("viewOnlyRecordDisplay").style.display = "none";

    hideAllSections();

    const placeholderText = document.getElementById("placeholderText");
    placeholderText.textContent = "Session completed.";
    placeholderText.style.display = "block";

    document.querySelectorAll(".endBtn").forEach(btn => {
        btn.disabled = true;
    });

    alert("Session completed successfully!");
}