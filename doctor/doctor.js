const profileBtn = document.querySelector("#profileBtn");
const profileDropdown = document.querySelector("#profileDropdown");

profileBtn.addEventListener("click", function (e) {
    e.stopPropagation();
    profileDropdown.classList.toggle("show");
});

document.addEventListener("click", function () {
    profileDropdown.classList.remove("show");
});

/* =========================
   CONSULTATION
========================= */

let currentQueue = null;
let currentPatient = null;
let prescriptionList = [];

/* =========================
   START SESSION
========================= */
function startSession() {

    fetch("getNextQueue.php")

    .then(res => res.json())

    .then(data => {

        if (!data) {
            alert("No patients waiting.");
            return;
        }

        // Store current consultation
        currentQueue = data;
        currentPatient = data;

        // Lock the queue (Waiting -> Called)
        fetch("lockQueue.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                queueID: data.queueID
            })
        })

        .then(res => res.json())

        .then(result => {

            if (!result.success) {
                alert("Failed to lock queue.");
                return;
            }

            // Create consultation record
            fetch("createConsultation.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    queueID: data.queueID
                })
            })

            .then(res => res.json())

            .then(consultation => {

                if (!consultation.success) {
                    alert(consultation.message);
                    return;
                }

                // Load patient into workspace
                loadCurrentPatient(data);

                // Enable End Session button
                document.querySelector("#endSessionBtn").disabled = false;
                document.querySelector(".startBtn").disabled = true;

            });

        });

    })

    .catch(error => {
        console.error(error);
        alert("Failed to retrieve next patient.");
    });

}

function loadCurrentPatient(patient){
    document.querySelector("#placeholderText").style.display="none";
    document.querySelector("#patientRecordDisplay").style.display="block";
    document.querySelector("#viewOnlyRecordDisplay").style.display="none";

    hideAllSections();

    document.querySelector(".overviewSection").style.display="block";
    document.querySelectorAll(".miniTab").forEach(tab=>{
        tab.classList.remove("active");
    });

    document.querySelector(".miniTab").classList.add("active");

    document.querySelector("#pName").innerText=patient.fullName;
    document.querySelector("#pGender").innerText=patient.gender;
    document.querySelector("#pID").innerText=patient.userID;
    document.querySelector("#pBlood").innerText=patient.bloodType;

    renderEditableList(
        "pAllergyList",
        patient.allergy ? patient.allergy.split(",") : []
    );

    renderEditableList(
        "pChronicList",
        patient.chronicCondition ? patient.chronicCondition.split(",") : []
    );

    renderEditableList(
        "pMedList",
        patient.currentMed ? patient.currentMed.split(",") : []
    );

}

/* =========================
   SEARCH PATIENT
========================= */

function searchPatientLive() {

    const input = document.querySelector("#searchPatientInput");
    const box = document.querySelector("#searchResultBox");

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

    currentQueue = null;
    currentPatient = null;

    const placeholderText = document.querySelector("#placeholderText");
    const patientRecordDisplay = document.querySelector("#patientRecordDisplay");
    const viewOnlyRecordDisplay = document.querySelector("#viewOnlyRecordDisplay");
    const searchPatientView = document.querySelector("#searchPatientView");

    if (placeholderText) placeholderText.style.display = "none";
    if (patientRecordDisplay) patientRecordDisplay.style.display = "none";
    if (viewOnlyRecordDisplay) viewOnlyRecordDisplay.style.display = "block";
    if (searchPatientView) searchPatientView.style.display = "none";

    hideAllSections();

    document.querySelector("#vName").textContent = patient.fullName || "-";
    document.querySelector("#vGender").textContent = patient.gender || "-";
    document.querySelector("#vBlood").textContent = patient.bloodType || "-";
    document.querySelector("#vID").textContent = patient.userID || "-";
    document.querySelector("#vDOB").textContent = formatDate(patient.dateOfBirth) || "-";
    document.querySelector("#vType").textContent = patient.patientType || "-";

    document.querySelector("#vAllergy").textContent = patient.allergy || "-";
    document.querySelector("#vChronic").textContent = patient.chronicCondition || "-";
    document.querySelector("#vMed").textContent = patient.currentMed || "-";
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

function switchViewTab(tab, btn){

    document
        .querySelectorAll("#viewOnlyRecordDisplay .miniTab")
        .forEach(b=>b.classList.remove("active"));

    btn.classList.add("active");

    document.querySelector("#viewOverviewSection").style.display="none";
    document.querySelector("#viewVisitsSection").style.display="none";

    if(tab==="overview"){
        document.querySelector("#viewOverviewSection").style.display="block";
    }else{
        document.querySelector("#viewVisitsSection").style.display="block";
    }

}

/* =========================
   DIAGNOSIS
========================= */

function openFindingInput() {
    document.querySelector("#findingInputBox").style.display = "flex";
    document.querySelector("#findingInput").focus();
}

function saveFinding() {
    const input = document.querySelector("#findingInput");
    const value = input.value.trim();

    if (value === "") {
        return;
    }

    const list = document.querySelector("#findingList");
    const item = document.createElement("div");

    item.className = "editableItem";
    item.innerHTML = `
        <input type="text" value="${value}" readonly>

        <button type="button" onclick="toggleEditable(this)">Edit</button>

        <button type="button" onclick="deleteEditable(this)">Delete</button>
    `;

    list.appendChild(item);

    input.value = "";

    document.querySelector("#findingInputBox").style.display = "none";
}


function toggleEditable(button) {

    const input = button.parentElement.querySelector("input");

    if (input.hasAttribute("readonly")) {
        input.removeAttribute("readonly");
        input.focus();
        button.textContent = "Save";
        button.classList.add("saveEditBtn");
    } else {
        input.setAttribute("readonly", true);
        button.textContent = "Edit";
        button.classList.remove("saveEditBtn");
    }

}

function deleteEditable(button) {
    if (!confirm("Delete this item?")) {
        return;
    }
    button.parentElement.remove();
}

/* =========================
   PRESCRIPTION
========================= */

function updateStock() {
    const select = document.querySelector("#medicine");
    const stockBox = document.querySelector("#stockBox");
    const quantity = document.querySelector("#quantity");

    const selectedOption = select.options[select.selectedIndex];
    const stock = selectedOption.getAttribute("data-stock");

    stockBox.innerText = stock ? stock : "-";

    // reset quantity
    quantity.value = "";

    // set max quantity ikut stock
    quantity.max = stock;
}

function addPrescription() {

    const medSelect = document.querySelector("#medicine");
    const dosage = document.querySelector("#dosage").value.trim();
    const freq = document.querySelector("#frequency").value.trim();
    const duration = document.querySelector("#duration").value.trim();
    const quantity = document.querySelector("#quantity").value.trim();
    const instruction = document.querySelector("#instruction").value.trim();
    const tbody = document.querySelector("#rxTableBody");

    const emptyRow = document.querySelector("#emptyRow");

    if (emptyRow) {
        emptyRow.remove();
    }

    const no = tbody.querySelectorAll("tr").length + 1;

    const medicineID = medSelect.value;
    const medicineName = medSelect.options[medSelect.selectedIndex].text;

    const stock = parseInt(medSelect.options[medSelect.selectedIndex].dataset.stock);

    if (stock <= 0) {
        alert("Selected medicine is out of stock.");
        return;
    }

    if (prescriptionList.some(item => item.medicineID === medicineID)) {
        alert("This medicine has already been added.");
        return;
    }

    const tr = document.createElement("tr");    

    prescriptionList.push({
        medicineID: medicineID,
        medicineName: medicineName,
        dosage: dosage,
        frequency: freq,
        duration: duration,
        quantity: quantity,
        instruction: instruction,
    });

    tr.dataset.id = medicineID;

    tr.innerHTML = `
        <td>${no}</td>
        <td>${medicineName}</td>
        <td>${dosage}</td>
        <td>${freq}</td>
        <td>${duration}</td>
        <td>${quantity}</td>
        <td>${instruction}</td>
        <td>
            <button type="button" onclick="deleteRx(this)">Delete</button>
        </td>
    `;

    tbody.appendChild(tr);
}

function deleteRx(button){

    if (!confirm("Remove this medicine from the prescription?")) {
        return;
    }

    const row = button.closest("tr");
    const medicineID = row.dataset.id;

    prescriptionList = prescriptionList.filter(
        item => item.medicineID !== medicineID
    );

    row.remove();
    const rows=document.querySelectorAll("#rxTableBody tr");

    rows.forEach((row,index)=>{
        row.children[0].textContent=index+1;
    });

    if(rows.length===0){
        document.querySelector("#rxTableBody").innerHTML=`
            <tr id="emptyRow">
                <td colspan="8" style="text-align:center;">
                    No prescription added yet
                </td>
            </tr>
        `;
    }
}

function collectPrescription(){
    return prescriptionList;
}

function getEditableValues(listId) {
    const values = [];
    document.querySelectorAll(`#${listId} input`).forEach(input => {
        const value = input.value.trim();

        if (value !== "") {
            values.push(value);
        }
    });
    return values.join("\n");
}

function submitConsultation() {

    if (prescriptionList.length === 0) {
        const confirmNoRx = confirm("No prescription added. Continue without medicine?");
        if (!confirmNoRx) return;
    }

    // proceed save
}

/* =========================
   END SESSION
========================= */
function endSession() {
    if (!currentQueue || !currentPatient) {
        alert("No active consultation.");
        return;
    }

    document.querySelector("#endSessionBtn").disabled = true;

    if (!document.querySelector("#reasonInput").value.trim()) {
        alert("Please enter the patient's reason for visit.");
        document.querySelector("#endSessionBtn").disabled = false;
        return;
    }

    if (!document.querySelector("#diagnosisInput").value.trim()) {
        alert("Please enter the diagnosis.");
        document.querySelector("#endSessionBtn").disabled = false;
        return;
    }

    if (prescriptionList.length === 0) {

    const confirmNoRx = confirm(
        "No prescription has been added.\n\n" +
        "Do you want to complete this consultation without prescribing medication?"
    );

    if (!confirmNoRx) {
        document.querySelector("#endSessionBtn").disabled = false;
        return;
    }
    }

        const findings = getEditableValues("findingList");

        const payload = {
            queueID: currentQueue.queueID,
            reason: document.querySelector("#reasonInput").value.trim(),
            findings: findings,
            diagnosis: document.querySelector("#diagnosisInput").value.trim(),
            treatment: document.querySelector("#treatmentInput").value.trim(),
            prescription: prescriptionList.length > 0 ? prescriptionList : []
        };

    fetch("endSession.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })

    .then(async response => {

        const data = await response.json();

        if(!response.ok){
            throw new Error(data.message);
        }

        return data;
    })

    .then(data => {
        if (data.success) {
            alert(
                "Consultation completed.\nThe queue has been updated successfully."
            );
            resetConsultation();
        }
        else {
            alert(JSON.stringify(data, null, 2));
            document.querySelector("#endSessionBtn").disabled = false;
        }

    })

        .catch(error => {
            console.error(error);
            document.querySelector("#endSessionBtn").disabled = false;
        });

}

function resetConsultation() {

    currentQueue = null;
    currentPatient = null;
    prescriptionList = [];

    // Clear consultation inputs
    document.querySelector("#reasonInput").value = "";
    document.querySelector("#diagnosisInput").value = "";
    document.querySelector("#treatmentInput").value = "";

    // Clear findings
    document.querySelector("#findingList").innerHTML = "";
    document.querySelector("#findingInput").value = "";
    document.querySelector("#findingInputBox").style.display = "none";

    // Clear patient details
    document.querySelector("#pName").textContent = "";
    document.querySelector("#pGender").textContent = "";
    document.querySelector("#pID").textContent = "";
    document.querySelector("#pBlood").textContent = "";

    document.querySelector("#pAllergyList").innerHTML = "";
    document.querySelector("#pChronicList").innerHTML = "";
    document.querySelector("#pMedList").innerHTML = "";

    // Reset prescription table
    document.querySelector("#rxTableBody").innerHTML = `
        <tr id="emptyRow">
            <td colspan="8" style="text-align:center;">
                No prescription added yet
            </td>
        </tr>
    `;

    // Reset prescription form
    document.querySelector("#medicine").selectedIndex = 0;
    updateStock();

    document.querySelector("#stockBox").textContent = "-";
    document.querySelector("#dosage").value = "";
    document.querySelector("#frequency").value = "";
    document.querySelector("#duration").value = "";
    document.querySelector("#quantity").value = "";
    document.querySelector("#instruction").value = "";

    // Hide consultation panel
    document.querySelector("#patientRecordDisplay").style.display = "none";
    document.querySelector("#viewOnlyRecordDisplay").style.display = "none";

    // Show placeholder
    document.querySelector("#placeholderText").style.display = "block";

    hideAllSections();

    // Remove active tab
    document.querySelectorAll(".miniTab").forEach(tab => {
        tab.classList.remove("active");
    });

    // Hide consultation panel
    document.querySelector("#patientRecordDisplay").style.display = "none";
    document.querySelector("#viewOnlyRecordDisplay").style.display = "none";

    // Show placeholder
    document.querySelector("#placeholderText").style.display = "block";

    // Disable End Session button
    document.querySelector("#endSessionBtn").disabled = true;
    document.querySelector(".startBtn").disabled = false;

    // Refresh queue count
    //loadQueue();
    const queueDisplay = document.querySelector(".queueNumber"); // tukar ikut id sebenar

    if(queueDisplay){
        const current = parseInt(queueDisplay.textContent);
        if(current > 0){
            queueDisplay.textContent = current - 1;
        }
}
}

function hideAllSections() {
    const sections = document.querySelectorAll(".tabSection, .overviewSection");

    sections.forEach(section => {
        section.style.display = "none";
    });
}

function switchTab(tabName, button) {
    hideAllSections();

    document.querySelectorAll(".miniTab").forEach(tab => {
        tab.classList.remove("active");
    });

    button.classList.add("active");

    switch(tabName){

        case "overview":
            document.querySelector(".overviewSection").style.display = "block";
            break;

        case "visits":
            document.querySelector("#visitsSection").style.display = "block";
            break;

        case "diagnosis":
            document.querySelector("#diagnosisSection").style.display = "block";
            break;

        case "prescription":
            document.querySelector("#prescriptionSection").style.display = "block";
            break;
    }
}


function renderEditableList(containerId, items){

    const container = document.getElementById(containerId);

    container.innerHTML = "";

    items.forEach(item=>{
        if (!item || item.trim()==="") return;

        const div=document.createElement("div");

        div.className="readOnlyItem";

        div.innerHTML=`
            <span>${item.trim()}</span>
        `;

        container.appendChild(div);
    });

}
