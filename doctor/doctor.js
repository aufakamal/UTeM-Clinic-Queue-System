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
// ==========================
// SUBMIT RESPONSE TO PHARMACY
// ==========================
function submitResponse() {

    const reply = document.getElementById("doctorReply");

    if (!reply || reply.value.trim() === "") {

        alert("Please enter a response for the pharmacist.");

        return;
    }

    showActionMessage("Data is saved and sent to the pharmacy.");

    reply.value = "";
}

function switchMiniTab(button, tabName) {

    // active button
    document.querySelectorAll(".miniTab").forEach(tab => {
        tab.classList.remove("active");
    });

    button.classList.add("active");

    // sections
    const overviewSection = document.querySelector(".overviewSection");
    const visitsSection = document.getElementById("visitsSection");
    const diagnosisSection = document.getElementById("diagnosisSection");
    const prescriptionSection = document.getElementById("prescriptionSection");

    if (overviewSection) overviewSection.style.display = "none";
    if (visitsSection) visitsSection.style.display = "none";
    if (diagnosisSection) diagnosisSection.style.display = "none";
    if (prescriptionSection) prescriptionSection.style.display = "none";

    if (tabName === "overview" && overviewSection) {
        overviewSection.style.display = "block";
    }

    if (tabName === "visits" && visitsSection) {
        visitsSection.style.display = "block";
    }

    if (tabName === "diagnosis" && diagnosisSection) {
        diagnosisSection.style.display = "block";
    }

    if (tabName === "prescription" && prescriptionSection) {
        prescriptionSection.style.display = "block";
    }
}

// ==========================
// DIAGNOSIS TAB
// ==========================
function editDiagnosis(button) {

    const diagnosisItem = button.closest(".diagnosisItem");

    const input = diagnosisItem.querySelector("input");
    const textarea = diagnosisItem.querySelector("textarea");

    const isReadonly = input.readOnly;

    if (isReadonly) {

        input.readOnly = false;
        textarea.readOnly = false;

        input.focus();

        button.textContent = "Save";
        button.classList.add("saveMode");

    } else {

        input.readOnly = true;
        textarea.readOnly = true;

        button.textContent = "Edit";
        button.classList.remove("saveMode");

    }
}

function addDiagnosis() {

    const diagnosisList = document.getElementById("diagnosisList");

    if (!diagnosisList) {
        return;
    }

    const diagnosisItem = document.createElement("div");
    diagnosisItem.className = "diagnosisItem";

    diagnosisItem.innerHTML = `
        <label>Diagnosis Title</label>
        <input 
            type="text" 
            placeholder="Enter diagnosis title"
        >

        <label>Doctor Notes</label>
        <textarea placeholder="Enter doctor's notes"></textarea>

        <div class="diagnosisActions">
            <button 
                type="button" 
                class="editDiagnosisBtn saveMode" 
                onclick="editDiagnosis(this)"
            >
                Save
            </button>
        </div>
    `;

    diagnosisList.appendChild(diagnosisItem);

    diagnosisItem.querySelector("input").focus();
}

// ==========================
// ADD FINDINGS POPUP
// ==========================
function addFindings() {
    const popup = document.getElementById("addFindingsPopup");

    if (popup) {
        popup.style.display = "flex";
    }
}

function closeFindingsPopup() {
    const popup = document.getElementById("addFindingsPopup");

    if (popup) {
        popup.style.display = "none";
    }

    clearFindingsForm();
}

function saveFinding() {
    const checkedTypes = document.querySelectorAll("input[name='findingType']:checked");
    const valueInput = document.getElementById("findingValueInput");
    const extraFindingsArea = document.getElementById("extraFindingsArea");

    if (checkedTypes.length === 0) {
        alert("Please select at least one finding type.");
        return;
    }

    if (!valueInput || valueInput.value.trim() === "") {
        alert("Please enter finding value.");
        return;
    }

    if (!extraFindingsArea) {
        return;
    }

    checkedTypes.forEach(type => {
        const findingBox = document.createElement("div");
        findingBox.className = "findingTextBox";

        findingBox.innerHTML = `
            <strong>${type.value}</strong>
            <p>${valueInput.value.trim()}</p>
        `;

        extraFindingsArea.appendChild(findingBox);
    });

    closeFindingsPopup();
}

function clearFindingsForm() {
    document.querySelectorAll("input[name='findingType']").forEach(checkbox => {
        checkbox.checked = false;
    });

    const valueInput = document.getElementById("findingValueInput");

    if (valueInput) {
        valueInput.value = "";
    }
}

// ==========================
// ADD PRESCRIPTION
// ==========================
function addPrescription() {

    const prescriptionList = document.getElementById("prescriptionList");

    if (!prescriptionList) {
        return;
    }

    const item = document.createElement("div");
    item.className = "prescriptionItem prescriptionEditItem";

    item.innerHTML = `
        <div class="prescriptionInputGroup">
            <label>Medicine:</label>
            <input class="prescriptionInput medicineInput" type="text" placeholder="Medicine name">
        </div>

        <div class="prescriptionInputGroup">
            <label>Dose:</label>
            <input class="prescriptionInput doseInput" type="text" placeholder="Dose">
        </div>

        <div class="prescriptionInputGroup">
            <label>Frequency:</label>
            <input class="prescriptionInput frequencyInput" type="text" placeholder="Frequency">
        </div>

        <div class="prescriptionInputGroup">
            <label>Duration:</label>
            <input class="prescriptionInput durationInput" type="text" placeholder="Duration">
        </div>

        <div class="prescriptionInputGroup">
            <label>Instructions:</label>
            <input class="prescriptionInput instructionsInput" type="text" placeholder="Instructions">
        </div>

        <button type="button" class="savePrescriptionBtn" onclick="savePrescription(this)">
            Save
        </button>
    `;

    prescriptionList.appendChild(item);

    item.querySelector(".medicineInput").focus();
}

function savePrescription(button) {

    const item = button.closest(".prescriptionItem");

    const medicine = item.querySelector(".medicineInput").value.trim();
    const dose = item.querySelector(".doseInput").value.trim();
    const frequency = item.querySelector(".frequencyInput").value.trim();
    const duration = item.querySelector(".durationInput").value.trim();
    const instructions = item.querySelector(".instructionsInput").value.trim();

    if (
        medicine === "" ||
        dose === "" ||
        frequency === "" ||
        duration === "" ||
        instructions === ""
    ) {
        alert("Please complete all prescription fields.");
        return;
    }

    item.classList.remove("prescriptionEditItem");

    item.innerHTML = `
        <p>
            <strong>Medicine:</strong> ${medicine}<br>
            <strong>Dose:</strong> ${dose}<br>
            <strong>Frequency:</strong> ${frequency}<br>
            <strong>Duration:</strong> ${duration}<br>
            <strong>Instructions:</strong> ${instructions}
        </p>
    `;
}

// Mark as resolved / pending
function toggleIssueStatus(button){
    const issueSpan = document.getElementById("formPopIssue");
    if(button.dataset.status === "pending"){
        button.dataset.status = "resolved";
        button.textContent = "Resolved";
        issueSpan.style.backgroundColor="#DCFCE7";
        issueSpan.style.color="#166534";
    } else {
        button.dataset.status = "pending";
        button.textContent = "Pending";
        issueSpan.style.backgroundColor="#FDE68A";
        issueSpan.style.color="#92400E";
    }
}

function enableInlineResponse(){
    const textarea = document.getElementById("doctorReply");
    if(textarea) textarea.removeAttribute("readonly");
    textarea.focus();
}

function submitResponse(){
    const reply = document.getElementById("doctorReply");
    if(!reply.value.trim()){
        alert("Please enter a response for the pharmacist.");
        return;
    }
    alert("Response saved and sent to pharmacy!");
    reply.setAttribute("readonly", true);
}


// Pastikan anda letak ini di bahagian bawah fail atau di dalam DOMContentLoaded
const okBtn = document.querySelector(".okBtn");
const messagePopup = document.querySelector(".messagePopup");

if (okBtn) {
    okBtn.addEventListener("click", function(e) {
        e.preventDefault(); // Mencegah tindakan default (penting!)
        
        // Sembunyikan popup
        messagePopup.style.display = "none"; 
        
        console.log("Popup telah ditutup."); // Untuk debugging
    });
} else {
    console.error("Butang OK tidak dijumpai dalam HTML!");
}
