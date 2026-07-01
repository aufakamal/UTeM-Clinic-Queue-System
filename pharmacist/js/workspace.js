let selectedQueue = "";

const patients = patientsData;

const viewButtons = document.querySelectorAll(".viewBtn");
const emptyWorkspace = document.querySelector(".emptyWorkspace");
const prescriptionDetails = document.querySelector(".prescriptionDetails");
const patientRecordView = document.getElementById("patientRecordView");

const patientName = document.querySelector(".patientName");
const queueNo = document.querySelector(".queueNo");
const doctorName = document.querySelector(".doctorName");
const allergyInfo = document.querySelector(".allergyInfo");
const prescriptionInfo = document.querySelector(".prescriptionInfo");

const pharmacistNote = document.querySelector(".pharmacistNote");
const prescriptionIDInput = document.querySelector(".prescriptionIDInput");
const prescriptionItemIDInput = document.querySelector(".prescriptionItemIDInput");

const editMedicine = document.querySelector(".editMedicine");
const editQuantity = document.querySelector(".editQuantity");
const editDosage = document.querySelector(".editDosage");
const editFrequency = document.querySelector(".editFrequency");
const editDuration = document.querySelector(".editDuration");
const editInstructions = document.querySelector(".editInstructions");
const safetyChecks = document.querySelectorAll(".prescriptionArea input[type='checkbox']");

const readyBtn = document.querySelector(".readyBtn");
const dispenseBtn = document.querySelector(".dispenseBtn");

const messagePopup = document.querySelector(".messagePopup");
const messageText = document.querySelector(".messageText");
const okBtn = document.querySelector(".okBtn");

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
    overviewPanel.style.display = "none";
    visitsPanel.style.display = "none";
    prescriptionPanel.style.display = "none";

    overviewTab.classList.remove("activeTab");
    visitsTab.classList.remove("activeTab");
    prescriptionTab.classList.remove("activeTab");

    if (tabName === "overview")
    {
        overviewPanel.style.display = "block";
        overviewTab.classList.add("activeTab");
    }

    if (tabName === "visits")
    {
        visitsPanel.style.display = "block";
        visitsTab.classList.add("activeTab");
    }

    if (tabName === "prescription")
    {
        prescriptionPanel.style.display = "block";
        prescriptionTab.classList.add("activeTab");
    }
}

overviewTab.addEventListener("click", () =>
{
    showWorkspaceTab("overview");
});

visitsTab.addEventListener("click", () =>
{
    showWorkspaceTab("visits");
});

prescriptionTab.addEventListener("click", () =>
{
    showWorkspaceTab("prescription");
});

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

    document.getElementById("workspaceVisitsHistory").innerHTML = rows;
}

viewButtons.forEach((button) =>
{
    button.addEventListener("click", () =>
    {
        const queue = button.dataset.queue;
        selectedQueue = queue;

        emptyWorkspace.style.display = "none";
        prescriptionDetails.style.display = "block";

        if (patientRecordView)
        {
            patientRecordView.style.display = "none";
        }

        patientName.textContent = patients[queue].name;
        queueNo.textContent = patients[queue].queue;
        doctorName.textContent = patients[queue].doctor;
        allergyInfo.textContent = patients[queue].allergy;

        overviewAllergyInfo.textContent = patients[queue].allergy;
        overviewChronicCondition.textContent = patients[queue].chronicCondition;
        overviewCurrentMed.textContent = patients[queue].currentMed;
        prescriptionIDInput.value = patients[queue].prescriptionID;
        prescriptionItemIDInput.value = patients[queue].prescriptionItemID;

        editMedicine.value = patients[queue].medicineID;
        editQuantity.value = patients[queue].quantity;
        editDosage.value = patients[queue].dosage;
        editFrequency.value = patients[queue].frequency;
        editDuration.value = patients[queue].duration;
        editInstructions.value = patients[queue].instructions;

        pharmacistNote.value = patients[queue].pharmacistNote;
        buildWorkspaceVisits(queue);
        showWorkspaceTab("overview");
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

function viewPatientRecord(userID)
{
    const record = patientRecordsData[userID];

    if (!record)
    {
        alert("Patient record not found.");
        return;
    }

    selectedQueue = "";

    emptyWorkspace.style.display = "none";
    prescriptionDetails.style.display = "none";
    patientRecordView.style.display = "block";

    let historyRows = "";

    if (record.history && record.history.length > 0)
    {
        record.history.forEach((item) =>
        {
            historyRows += `
                <tr>
                    <td>${formatValue(item.dateTime)}</td>
                    <td>${formatValue(item.doctorName)}</td>
                    <td>${formatValue(item.medicineName)}</td>
                    <td>${formatValue(item.quantity)}</td>
                </tr>
            `;
        });
    }
    else
    {
        historyRows = `
            <tr>
                <td colspan="6">No visit history found.</td>
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
                <p><b>Gender:</b> ${formatValue(record.gender)}</p>
                <p><b>ID:</b> ${formatValue(record.userID)}</p>
                <p><b>Blood Type:</b> ${formatValue(record.bloodType)}</p>
                <p><b>Date of Birth:</b> ${formatDate(record.dateOfBirth)}</p>
                <p><b>Patient Type:</b> ${formatValue(record.patientType)}</p>
            </div>

            <div class="searchMiniTabs">
                <button class="active" onclick="switchSearchTab('overview', this)">Overview</button>
                <button onclick="switchSearchTab('visits', this)">Visits</button>
                <button onclick="switchSearchTab('prescription', this)">Prescription</button>
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
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                        </tr>
                        ${historyRows}
                    </table>
                </div>
            </div>

            <div id="searchPrescriptionSection" style="display:none;">
                <div class="searchViewBlock">
                    <h3>Prescription</h3>
                    <table class="historyTable">
                        <tr>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Doctor Note</th>
                        </tr>
                        ${buildSearchPrescriptionRows(record)}
                    </table>
                </div>
            </div>

        </div>
    `;
}

function switchSearchTab(tab, btn)
{
    document.querySelectorAll(".searchMiniTabs button").forEach((button) =>
    {
        button.classList.remove("active");
    });

    btn.classList.add("active");

    document.getElementById("searchOverviewSection").style.display = "none";
    document.getElementById("searchVisitsSection").style.display = "none";
    document.getElementById("searchPrescriptionSection").style.display = "none";

    if (tab === "overview")
    {
        document.getElementById("searchOverviewSection").style.display = "block";
    }
    else if (tab === "visits")
    {
        document.getElementById("searchVisitsSection").style.display = "block";
    }
    else
    {
        document.getElementById("searchPrescriptionSection").style.display = "block";
    }
}

function buildSearchPrescriptionRows(record)
{
    if (!record.history || record.history.length === 0)
    {
        return `
            <tr>
                <td colspan="6">No prescription found.</td>
            </tr>
        `;
    }

    let rows = "";

    record.history.forEach((item) =>
    {
        rows += `
            <tr>
                <td>${formatValue(item.medicineName)}</td>
                <td>${formatValue(item.quantity)}</td>
                <td>${formatValue(item.dosage)}</td>
                <td>${formatValue(item.frequency)}</td>
                <td>${formatValue(item.duration)}</td>
                <td>${formatValue(item.instructions)}</td
            </tr>
        `;
    });

    return rows;
}