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
const safetyChecks = document.querySelectorAll(".prescriptionArea input[type='checkbox']");

const readyBtn = document.querySelector(".readyBtn");
const dispenseBtn = document.querySelector(".dispenseBtn");

const messagePopup = document.querySelector(".messagePopup");
const messageText = document.querySelector(".messageText");
const okBtn = document.querySelector(".okBtn");

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
        prescriptionInfo.innerHTML = patients[queue].prescription;

        safetyChecks.forEach((check) =>
        {
            check.checked = false;
        });

        pharmacistNote.value = "";
    });
});

if (readyBtn)
{
    readyBtn.addEventListener("click", () =>
    {
        if (selectedQueue === "")
        {
            alert("Please select a patient first.");
            return;
        }

        messageText.textContent = "Prescription status updated to Ready.";
        messagePopup.style.display = "block";
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

        messageText.textContent = "Medication dispensed successfully.";
        messagePopup.style.display = "block";
    });
}

if (okBtn)
{
    okBtn.addEventListener("click", () =>
    {
        messagePopup.style.display = "none";
    });
}

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