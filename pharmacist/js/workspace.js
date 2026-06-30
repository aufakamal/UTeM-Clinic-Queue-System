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

        safetyChecks.forEach((check) =>
        {
            check.checked = false;
        });

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

function safetyChecked()
{
    let checked = false;

    safetyChecks.forEach((check) =>
    {
        if (check.checked)
        {
            checked = true;
        }
    });

    return checked;
}

function setWorkspaceAction(action)
{
    const workspaceActionInput = document.querySelector(".workspaceActionInput");

    if (workspaceActionInput)
    {
        workspaceActionInput.value = action;
    }
}

function validateWorkspaceForm()
{
    const workspaceActionInput = document.querySelector(".workspaceActionInput");

    if (selectedQueue === "")
    {
        alert("Please select a patient first.");
        return false;
    }

    if (workspaceActionInput && 
        (workspaceActionInput.value === "ready" || workspaceActionInput.value === "dispense"))
    {
        if (!safetyChecked())
        {
            alert("Please complete the Safety Check before proceeding.");
            return false;
        }
    }

    if (editQuantity && editQuantity.value <= 0)
    {
        alert("Quantity must be more than 0.");
        return false;
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

    patientRecordView.style.display = "block";

    let historyRows = "";

    if (record.history && record.history.length > 0)
    {
        record.history.forEach((item) =>
        {
            let statusClass = "pendingStatus";

            if (item.status === "Dispensed")
            {
                statusClass = "doneStatus";
            }
            else if (item.status === "Ready")
            {
                statusClass = "doneStatus";
            }
            else if (item.status === "Pending")
            {
                statusClass = "waitingStatus";
            }

            historyRows += `
                <tr>
                    <td>${formatValue(item.queueNo)}</td>
                    <td>${formatValue(item.dateTime)}</td>
                    <td>${formatValue(item.doctorName)}</td>
                    <td>${formatValue(item.medicineName)}</td>
                    <td>${formatValue(item.quantity)}</td>
                    <td><span class="${statusClass}">${formatValue(item.status)}</span></td>
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
        <div class="viewOnlyRecord">

            <div class="recordTop">
                <h2>Patient Medical Record <span>(View Only)</span></h2>
            </div>

            <div class="viewOnlyAlert">
                You are viewing this patient's medical record. Information shown below is for reference only.
            </div>

            <div class="medicalInfoCard">
                <div>
                    <p>Full Name</p>
                    <div class="recordValue">${formatValue(record.fullName)}</div>

                    <p>Patient ID</p>
                    <div class="recordValue">${formatValue(record.userID)}</div>
                </div>

                <div>
                    <p>Gender</p>
                    <div class="recordValue">${formatValue(record.gender)}</div>

                    <p>Date of Birth</p>
                    <div class="recordValue">${formatDate(record.dateOfBirth)}</div>
                </div>

                <div>
                    <p>Blood Type</p>
                    <div class="recordValue">${formatValue(record.bloodType)}</div>

                    <p>Patient Type</p>
                    <div class="recordValue">${formatValue(record.patientType)}</div>
                </div>
            </div>

            <div class="medicalBlock">
                <h3>Allergies</h3>
                <p>${formatValue(record.allergy)}</p>
            </div>

            <div class="medicalBlock">
                <h3>Chronic Condition</h3>
                <p>${formatValue(record.chronicCondition)}</p>
            </div>

            <div class="medicalBlock">
                <h3>Current Medication</h3>
                <p>${formatValue(record.currentMed)}</p>
            </div>

            <div class="medicalBlock">
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

            <div class="medicalBlock">
                <h3>Pharmacy Note (Read Only)</h3>
                <p>No additional notes for this patient.</p>
            </div>

            <div class="viewOnlyNote">
                This is a read-only medical record. Only doctors are allowed to edit medical information.
            </div>

        </div>
    `;
}

function setWorkspaceAction(action)
{
    const workspaceActionInput = document.querySelector(".workspaceActionInput");

    if (workspaceActionInput)
    {
        workspaceActionInput.value = action;
    }
}

function validateWorkspaceForm()
{
    const workspaceActionInput = document.querySelector(".workspaceActionInput");

    if (selectedQueue === "")
    {
        alert("Please select a patient first.");
        return false;
    }

    if (workspaceActionInput.value === "ready" || workspaceActionInput.value === "dispense")
    {
        if (!safetyChecked())
        {
            alert("Please complete at least one Safety Check before proceeding.");
            return false;
        }
    }

    if (editQuantity && editQuantity.value <= 0)
    {
        alert("Quantity must be more than 0.");
        return false;
    }

    return true;
}