let selectedQueue = "";

const patients = patientsData;
const medicineList = medicineListData;

const viewButtons = document.querySelectorAll(".viewBtn");
const emptyWorkspace = document.querySelector(".emptyWorkspace");
const prescriptionDetails = document.querySelector(".prescriptionDetails");
const patientRecordView = document.getElementById("patientRecordView");

const patientName = document.querySelector(".patientName");
const queueNo = document.querySelector(".queueNo");
const doctorName = document.querySelector(".doctorName");
const allergyInfo = document.querySelector(".allergyInfo");

const pharmacistNote = document.querySelector(".pharmacistNote");
const prescriptionIDInput = document.querySelector(".prescriptionIDInput");
const prescriptionItemIDInput = document.querySelector(".prescriptionItemIDInput");

const editMedicine = document.querySelector(".editMedicine");
const editQuantity = document.querySelector(".editQuantity");
const editDosage = document.querySelector(".editDosage");
const editFrequency = document.querySelector(".editFrequency");
const editDuration = document.querySelector(".editDuration");
const editInstructions = document.querySelector(".editInstructions");

const safetyChecks = document.querySelectorAll(".prescriptionPanel input[type='checkbox']");

const readyBtn = document.querySelector(".readyBtn");
const dispenseBtn = document.querySelector(".dispenseBtn");

const overviewTab = document.querySelector(".overviewTab");
const visitsTab = document.querySelector(".visitsTab");
const prescriptionTab = document.querySelector(".prescriptionTab");

const overviewPanel = document.querySelector(".overviewPanel");
const visitsPanel = document.querySelector(".visitsPanel");
const prescriptionPanel = document.querySelector(".prescriptionPanel");

const overviewAllergyInfo = document.querySelector(".overviewAllergyInfo");
const overviewChronicCondition = document.querySelector(".overviewChronicCondition");
const overviewCurrentMed = document.querySelector(".overviewCurrentMed");
const workspaceVisitsHistory = document.getElementById("workspaceVisitsHistory");

function showWorkspaceTab(tabName)
{
    if (overviewPanel) overviewPanel.style.display = "none";
    if (visitsPanel) visitsPanel.style.display = "none";
    if (prescriptionPanel) prescriptionPanel.style.display = "none";

    if (overviewTab) overviewTab.classList.remove("activeTab");
    if (visitsTab) visitsTab.classList.remove("activeTab");
    if (prescriptionTab) prescriptionTab.classList.remove("activeTab");

    if (tabName === "overview")
    {
        if (overviewPanel) overviewPanel.style.display = "block";
        if (overviewTab) overviewTab.classList.add("activeTab");
    }

    if (tabName === "visits")
    {
        if (visitsPanel) visitsPanel.style.display = "block";
        if (visitsTab) visitsTab.classList.add("activeTab");
    }

    if (tabName === "prescription")
    {
        if (prescriptionPanel) prescriptionPanel.style.display = "block";
        if (prescriptionTab) prescriptionTab.classList.add("activeTab");
    }
}

if (overviewTab)
{
    overviewTab.addEventListener("click", () =>
    {
        showWorkspaceTab("overview");
    });
}

if (visitsTab)
{
    visitsTab.addEventListener("click", () =>
    {
        showWorkspaceTab("visits");
    });
}

if (prescriptionTab)
{
    prescriptionTab.addEventListener("click", () =>
    {
        showWorkspaceTab("prescription");
    });
}

function buildWorkspaceVisits(queue)
{
    let rows = "";
    const userID = patients[queue].userID;
    const record = patientRecordsData[userID];

    if (record && record.history && record.history.length > 0)
    {
        record.history.forEach((item) =>
        {
            rows += `
                <tr>
                    <td>${formatValue(item.queueNo)}</td>
                    <td>${formatValue(item.dateTime)}</td>
                    <td>${formatValue(item.doctorName)}</td>
                    <td>${formatValue(item.medicineName)}</td>
                    <td>${formatValue(item.quantity)}</td>
                    <td>${formatValue(item.status)}</td>
                </tr>
            `;
        });
    }
    else
    {
        rows = `
            <tr>
                <td colspan="6">No prescription history found.</td>
            </tr>
        `;
    }

    if (workspaceVisitsHistory)
    {
        workspaceVisitsHistory.innerHTML = rows;
    }
}

function buildPrescriptionTable(queue)
{
    const prescriptionListTable =
        document.getElementById("prescriptionListTable");

    if (!prescriptionListTable)
    {
        return;
    }

    let rows = "";

    const items = patients[queue].items;

    if (!items || items.length === 0)
    {
        prescriptionListTable.innerHTML = `
            <tr>
                <td colspan="7">No medicine added.</td>
            </tr>
        `;
        return;
    }

    items.forEach(item =>
    {
        rows += `
        <tr>

            <td>${item.medicineName}</td>

            <td>${item.quantity}</td>

            <td>${item.dosage}</td>

            <td>${item.frequency}</td>

            <td>${item.duration}</td>

            <td>${item.instruction}</td>

            <td>

                <button
                    type="button"
                    class="editPrescriptionBtn actionBtn"

                    data-id="${item.prescriptionItemID}"

                    data-medicine="${item.medicineID}"

                    data-quantity="${item.quantity}"

                    data-dosage="${item.dosage}"

                    data-frequency="${item.frequency}"

                    data-duration="${item.duration}"

                    data-instruction="${item.instruction}"

                    data-stock="${item.stockQuantity}">

                    Edit

                </button>

            </td>

        </tr>
        `;
    });

    prescriptionListTable.innerHTML = rows;

    initialiseEditButtons();
}

viewButtons.forEach((button) =>
{
    button.addEventListener("click", () =>
    {
        const queue = button.dataset.queue;
        selectedQueue = queue;

        if (emptyWorkspace) emptyWorkspace.style.display = "none";
        if (prescriptionDetails) prescriptionDetails.style.display = "block";

        if (patientRecordView)
        {
            patientRecordView.style.display = "none";
        }

        if (patientName) patientName.textContent = patients[queue].name;
        if (queueNo) queueNo.textContent = patients[queue].queue;
        if (doctorName) doctorName.textContent = patients[queue].doctor;

        document.getElementById("workspacePrescriptionID").value = patients[queue].prescriptionID;

        if (allergyInfo) allergyInfo.textContent = patients[queue].allergy;

        if (overviewAllergyInfo) overviewAllergyInfo.textContent = patients[queue].allergy;
        if (overviewChronicCondition) overviewChronicCondition.textContent = patients[queue].chronicCondition;
        if (overviewCurrentMed) overviewCurrentMed.textContent = patients[queue].currentMed;

        if (prescriptionIDInput) prescriptionIDInput.value = patients[queue].prescriptionID;
        if (prescriptionItemIDInput) prescriptionItemIDInput.value = patients[queue].prescriptionItemID;

        if (editMedicine) editMedicine.value = patients[queue].medicineID;
        if (editQuantity) editQuantity.value = patients[queue].quantity;
        if (editDosage) editDosage.value = patients[queue].dosage;
        if (editFrequency) editFrequency.value = patients[queue].frequency;
        if (editDuration) editDuration.value = patients[queue].duration;
        if (editInstructions) editInstructions.value = patients[queue].instructions;

        if (pharmacistNote)
        {
            pharmacistNote.value = patients[queue].pharmacistNote || "";
        }

        if (patients[queue].status === "Ready")
        {
            safetyChecks.forEach(check =>
            {
                check.checked = true;
                check.disabled = true;
            });

            readyBtn.disabled = true;
            dispenseBtn.disabled = false;
        }
        else
        {
            safetyChecks.forEach(check =>
            {
                check.checked = false;
                check.disabled = false;
            });

            readyBtn.disabled = false;
            dispenseBtn.disabled = true;
        }

        buildWorkspaceVisits(queue);
        buildPrescriptionTable(queue);
        showWorkspaceTab("prescription");
    });
});

function searchPatientLive()
{
    const searchPatientInput = document.getElementById("searchPatientInput");
    const searchResultBox = document.getElementById("searchResultBox");

    if (!searchPatientInput || !searchResultBox)
    {
        return;
    }

    const keyword = searchPatientInput.value.toLowerCase().trim();

    if (keyword === "")
    {
        searchResultBox.innerHTML = "";
        return;
    }

    let resultHTML = "";

    searchPatientsData.forEach((patient) =>
    {
        const patientInfo = 
            patient.fullName.toLowerCase() + " " +
            patient.userID.toLowerCase();

        if (patientInfo.includes(keyword))
        {
            resultHTML += `
                <div class="patientSearchResult">
                    <div>
                        <h4>${patient.fullName}</h4>
                        <p>${patient.userID}</p>
                    </div>

                    <button type="button" onclick="viewPatientRecord('${patient.userID}')">
                        View
                    </button>
                </div>
            `;
        }
    });

    if (resultHTML === "")
    {
        resultHTML = `
            <div class="noPatientFound">
                No patient found.
            </div>
        `;
    }

    searchResultBox.innerHTML = resultHTML;
}

function formatValue(value)
{
    if (value === null || value === "" || value === undefined)
    {
        return "-";
    }

    return value;
}

function formatDate(dateValue)
{
    if (dateValue === null || dateValue === "" || dateValue === undefined)
    {
        return "-";
    }

    const date = new Date(dateValue);

    return date.toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric"
    });
}

function safetyChecked()
{
    for (const check of safetyChecks)
    {
        if (!check.checked)
        {
            return false;
        }
    }

    return true;
}

function setWorkspaceAction(action)
{
    document.getElementById("workspaceAction").value = action;
}

function validateWorkspaceForm()
{
    const workspaceActionInput =
        document.getElementById("workspaceAction");

    if (selectedQueue === "")
    {
        alert("Please select a patient first.");
        return false;
    }

    if (
        workspaceActionInput.value === "ready" ||
        workspaceActionInput.value === "dispense"
    )
    {
        if (!safetyChecked())
        {
            alert("Please complete all Safety Checks before proceeding.");
            return false;
        }
    }

    return true;
}

if (readyBtn)
{
    readyBtn.addEventListener("click", () =>
    {
        if (selectedQueue === "")
        {
            alert("Please select a patient first.");
            return;
        }
    });
}

if (dispenseBtn)
{
    dispenseBtn.addEventListener("click", () =>
    {
        if (selectedQueue === "")
        {
            alert("Please select a patient first.");
            return;
        }
    });
}

// function viewPatientRecord(userID)
// {
//     const record = patientRecordsData[userID];

//     if (!record)
//     {
//         alert("Patient record not found.");
//         return;
//     }

//     selectedQueue = "";

//     if (emptyWorkspace)
//     {
//         emptyWorkspace.style.display = "none";
//     }

//     if (prescriptionDetails)
//     {
//         prescriptionDetails.style.display = "none";
//     }

//     if (!patientRecordView)
//     {
//         alert("patientRecordView not found.");
//         return;
//     }

//     patientRecordView.style.display = "block";

//     let historyRows = "";

//     if (record.history && record.history.length > 0)
//     {
//         record.history.forEach((item) =>
//         {
//             historyRows += `
//                 <tr>
//                     <td>${formatValue(item.queueNo)}</td>
//                     <td>${formatValue(item.dateTime)}</td>
//                     <td>${formatValue(item.doctorName)}</td>
//                     <td>${formatValue(item.medicineName)}</td>
//                     <td>${formatValue(item.quantity)}</td>
//                     <td>${formatValue(item.status)}</td>
//                 </tr>
//             `;
//         });
//     }
//     else
//     {
//         historyRows = `
//             <tr>
//                 <td colspan="6">No prescription history found.</td>
//             </tr>
//         `;
//     }

//     patientRecordView.innerHTML = `
//         <div class="searchPatientRecord">

//             <h2 class="searchRecordTitle">
//                 Patient Medical Record <span>(View Only)</span>
//             </h2>

//             <div class="searchNotice">
//                 You are viewing this patient's medical record. Information shown below is for reference only.
//             </div>

//             <div class="searchPatientInfo">
//                 <p><b>Full Name:</b> ${formatValue(record.fullName)}</p>
//                 <p><b>Patient ID:</b> ${formatValue(record.userID)}</p>
//                 <p><b>Gender:</b> ${formatValue(record.gender)}</p>
//                 <p><b>Date of Birth:</b> ${formatDate(record.dateOfBirth)}</p>
//                 <p><b>Blood Type:</b> ${formatValue(record.bloodType)}</p>
//                 <p><b>Patient Type:</b> ${formatValue(record.patientType)}</p>
//             </div>

//             <div class="searchMiniTabs">
//                 <button type="button" class="active" onclick="switchSearchTab('overview', this)">
//                     Overview
//                 </button>

//                 <button type="button" onclick="switchSearchTab('visits', this)">
//                     Visits
//                 </button>
//             </div>

//             <div id="searchOverviewSection">
//                 <div class="searchViewBlock">
//                     <h3>Allergies</h3>
//                     <p>${formatValue(record.allergy)}</p>
//                 </div>

//                 <div class="searchViewBlock">
//                     <h3>Chronic Diseases</h3>
//                     <p>${formatValue(record.chronicCondition)}</p>
//                 </div>

//                 <div class="searchViewBlock">
//                     <h3>Current Medication</h3>
//                     <p>${formatValue(record.currentMed)}</p>
//                 </div>
//             </div>

//             <div id="searchVisitsSection" style="display:none;">
//                 <div class="searchViewBlock">
//                     <h3>Prescription History</h3>

//                     <table class="historyTable">
//                         <tr>
//                             <th>Queue No.</th>
//                             <th>Date & Time</th>
//                             <th>Doctor</th>
//                             <th>Medicine</th>
//                             <th>Quantity</th>
//                             <th>Status</th>
//                         </tr>

//                         ${historyRows}
//                     </table>
//                 </div>
//             </div>

//         </div>
//     `;
// }

function switchSearchTab(tab, btn)
{
    document.querySelectorAll(".searchMiniTabs button").forEach((button) =>
    {
        button.classList.remove("active");
    });

    btn.classList.add("active");

    document.getElementById("searchOverviewSection").style.display = "none";
    document.getElementById("searchVisitsSection").style.display = "none";

    if (tab === "overview")
    {
        document.getElementById("searchOverviewSection").style.display = "block";
    }
    else if (tab === "visits")
    {
        document.getElementById("searchVisitsSection").style.display = "block";
    }
}

function setWorkspaceAction(action)
{
    document.getElementById("workspaceAction").value = action;
}

/* =========================
   QUEUE FILTER
========================= */

const filterButtons = document.querySelectorAll(".queueFilter button");
const queueBoxes = document.querySelectorAll(".queueBox");

filterButtons.forEach(button => {

    button.addEventListener("click", () => {

        // Active button
        filterButtons.forEach(btn => btn.classList.remove("activeFilter"));
        button.classList.add("activeFilter");

        const filter = button.dataset.filter;

        queueBoxes.forEach(box => {

            const status = box.dataset.status;

            if (filter === "All" || status === filter)
            {
                box.style.display = "flex";
            }
            else
            {
                box.style.display = "none";
            }

        });

    });

});

/* ===========================
   EDIT PRESCRIPTION POPUP
=========================== */

const prescriptionPopup = document.getElementById("prescriptionPopup");

const closePrescriptionBtn =
document.querySelector(".closePrescriptionBtn");

const popupMedicine =
document.getElementById("popupMedicine");

const popupCurrentStock =
document.getElementById("popupCurrentStock");

const popupRemainingStock =
document.getElementById("popupRemainingStock");

const popupQuantity =
document.getElementById("popupQuantity");

const popupDosage =
document.getElementById("popupDosage");

const popupFrequency =
document.getElementById("popupFrequency");

const popupDuration =
document.getElementById("popupDuration");

const popupInstruction =
document.getElementById("popupInstruction");

const popupPrescriptionItemID =
document.getElementById("popupPrescriptionItemID");

const savePopupBtn =
document.getElementById("savePopupBtn");

if (closePrescriptionBtn)
{
    closePrescriptionBtn.addEventListener("click", () =>
    {
        prescriptionPopup.style.display = "none";
    });
}

function initialiseEditButtons()
{
    document.querySelectorAll(".editPrescriptionBtn").forEach(button =>
    {
        button.onclick = () =>
        {

            document.getElementById("popupQueueKey").value = selectedQueue;
            
            popupPrescriptionItemID.value =
                button.dataset.id;

            popupMedicine.value =
                button.dataset.medicine;

            updateStockInfo(
                parseInt(button.dataset.stock)
            );

            popupQuantity.value =
                button.dataset.quantity;

            popupDosage.value =
                button.dataset.dosage;

            popupFrequency.value =
                button.dataset.frequency;

            popupDuration.value =
                button.dataset.duration;

            popupInstruction.value =
                button.dataset.instruction;

            prescriptionPopup.style.display = "flex";
        };
    });
}

if (popupMedicine)
{
    popupMedicine.addEventListener("change", () =>
    {
        const selectedMedicine =
            medicineList.find(medicine =>
                medicine.medicineID == popupMedicine.value
            );

        if (selectedMedicine)
        {
            updateStockInfo(
                parseInt(selectedMedicine.stockQuantity)
            );
        }
    });
}

function updateStockInfo(stock)
{
    popupCurrentStock.textContent = stock + " units";

    if (stock < 10)
    {
        popupCurrentStock.style.color = "#dc2626";
    }
    else if (stock < 50)
    {
        popupCurrentStock.style.color = "#d97706";
    }
    else
    {
        popupCurrentStock.style.color = "#16a34a";
    }

    popupQuantity.max = stock;

    const quantity = parseInt(popupQuantity.value) || 0;

    let remaining = stock - quantity;

    if (remaining < 0)
    {
        remaining = 0;
    }

    popupRemainingStock.textContent =
        remaining + " units";
}

if (popupQuantity)
{
    popupQuantity.addEventListener("input", () =>
    {
        const selectedMedicine = medicineList.find(medicine =>
            medicine.medicineID == popupMedicine.value
        );

        if (selectedMedicine)
        {
            updateStockInfo(
                parseInt(selectedMedicine.stockQuantity)
            );
        }
    });
}

if (typeof reopenQueue !== "undefined" &&
    reopenQueue !== "")
{
    const button =
        document.querySelector(
            `.viewBtn[data-queue="${reopenQueue}"]`
        );

    if (button)
    {
        button.click();
    }
}

readyBtn.onclick = () => {

    setWorkspaceAction("ready");

    if (!validateWorkspaceForm())
        return;

    document.getElementById("workspaceForm").submit();

};

dispenseBtn.onclick = () => {

    if (selectedQueue === "")
    {
        alert("Please select a patient first.");
        return;
    }

    if (patients[selectedQueue].status !== "Ready")
    {
        alert("Prescription must be marked Ready before dispensing.");
        return;
    }

    setWorkspaceAction("dispense");

    if (!validateWorkspaceForm())
        return;

    document.getElementById("workspaceForm").submit();

};

if (workspaceError === "notready")
{
    alert("Prescription must be marked Ready before dispensing.");
}

/* =========================
   FORCE FIX SEARCH PATIENT VIEW
========================= */

function viewPatientRecord(userID)
{
    const record = patientRecordsData[userID];

    if (!record)
    {
        alert("Patient record not found.");
        return;
    }

    selectedQueue = "";

    if (emptyWorkspace)
    {
        emptyWorkspace.style.display = "none";
    }

    if (prescriptionDetails)
    {
        prescriptionDetails.style.display = "none";
    }

    if (!patientRecordView)
    {
        alert("patientRecordView not found.");
        return;
    }

    let historyRows = "";

    const historyList = Array.isArray(record.history) ? record.history : [];

    if (historyList.length > 0)
    {
        historyList.forEach((item) =>
        {
            historyRows += `
                <tr>
                    <td>${formatValue(item.queueNo)}</td>
                    <td>${formatValue(item.dateTime)}</td>
                    <td>${formatValue(item.doctorName)}</td>
                    <td>${formatValue(item.medicineName)}</td>
                    <td>${formatValue(item.quantity)}</td>
                    <td>${formatValue(item.status)}</td>
                </tr>
            `;
        });
    }
    else
    {
        historyRows = `
            <tr>
                <td colspan="6">No prescription history found.</td>
            </tr>
        `;
    }

    patientRecordView.innerHTML = `
        <div class="searchPatientRecord">

            <h2 class="searchRecordTitle">
                Patient Medical Record <span>(View Only)</span>
            </h2>

            <div class="searchNotice">
                You are viewing this patient's medical record. Information shown below is for reference only.
            </div>

            <div class="searchPatientInfo">
                <p><b>Full Name:</b> ${formatValue(record.fullName)}</p>
                <p><b>Patient ID:</b> ${formatValue(record.userID)}</p>
                <p><b>Gender:</b> ${formatValue(record.gender)}</p>
                <p><b>Date of Birth:</b> ${formatDate(record.dateOfBirth)}</p>
                <p><b>Blood Type:</b> ${formatValue(record.bloodType)}</p>
                <p><b>Patient Type:</b> ${formatValue(record.patientType)}</p>
            </div>

            <div class="searchMiniTabs">
                <button type="button" class="active" onclick="switchSearchTab('overview', this)">
                    Overview
                </button>

                <button type="button" onclick="switchSearchTab('visits', this)">
                    Visits
                </button>
            </div>

            <div id="searchOverviewSection">
                <div class="searchViewBlock">
                    <h3>Allergies</h3>
                    <p>${formatValue(record.allergy)}</p>
                </div>

                <div class="searchViewBlock">
                    <h3>Chronic Diseases</h3>
                    <p>${formatValue(record.chronicCondition)}</p>
                </div>

                <div class="searchViewBlock">
                    <h3>Current Medication</h3>
                    <p>${formatValue(record.currentMed)}</p>
                </div>
            </div>

            <div id="searchVisitsSection" style="display:none;">
                <div class="searchViewBlock">
                    <h3>Prescription History</h3>

                    <table class="historyTable">
                        <tr>
                            <th>Queue No.</th>
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>

                        ${historyRows}
                    </table>
                </div>
            </div>

        </div>
    `;

    patientRecordView.style.display = "block";
}


function switchSearchTab(tab, btn)
{
    document.querySelectorAll(".searchMiniTabs button").forEach((button) =>
    {
        button.classList.remove("active");
    });

    btn.classList.add("active");

    const overviewSection = document.getElementById("searchOverviewSection");
    const visitsSection = document.getElementById("searchVisitsSection");

    if (overviewSection)
    {
        overviewSection.style.display = "none";
    }

    if (visitsSection)
    {
        visitsSection.style.display = "none";
    }

    if (tab === "overview" && overviewSection)
    {
        overviewSection.style.display = "block";
    }
    else if (tab === "visits" && visitsSection)
    {
        visitsSection.style.display = "block";
    }
}