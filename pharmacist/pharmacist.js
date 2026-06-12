//profile button and dropdown//
let profileBtn = document.querySelector("#profileBtn");
let profileDropdown = document.querySelector("#profileDropdown");

if (profileBtn && profileDropdown) {
    profileBtn.addEventListener("click", function() {
        profileDropdown.classList.toggle("showDropdown");
    });
}

let selectedQueue = "";

const patients = 
{
    A102: 
    {
        name: "Nur Aisyah",
        queue: "A102",
        doctor: "Dr. Amir",
        allergy: "Penicillin",
        prescription: "Paracetamol 500mg<br>Dosage: 3 times daily for 5 days<br>Doctor Note: After food"
    },

    A103: 
    {
        name: "Ahmad Ali",
        queue: "A103",
        doctor: "Dr. Siti",
        allergy: "No Known Allergy",
        prescription: "Cough Syrup 10ml<br>Dosage: 2 times daily for 3 days<br>Doctor Note: Drink more water"
    }
};

const viewButtons = document.querySelectorAll(".viewBtn");
const emptyWorkspace = document.querySelector(".emptyWorkspace");
const prescriptionDetails = document.querySelector(".prescriptionDetails");

const patientName = document.querySelector(".patientName");
const queueNo = document.querySelector(".queueNo");
const doctorName = document.querySelector(".doctorName");
const allergyInfo = document.querySelector(".allergyInfo");
const prescriptionInfo = document.querySelector(".prescriptionInfo");

const pharmacistNote = document.querySelector(".pharmacistNote");
const safetyChecks = document.querySelectorAll(".prescriptionArea input[type='checkbox']");

const issueBtn = document.querySelector(".issueBtn");
const dispenseBtn = document.querySelector(".dispenseBtn");

const issuePopup = document.querySelector(".issuePopup");
const cancelBtn = document.querySelector(".cancelBtn");
const sendDoctorBtn = document.querySelector(".sendDoctorBtn");

const returnedRecord = document.querySelector("#returnedRecord");
const dispensedRecord = document.querySelector("#dispensedRecord");

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

issueBtn.addEventListener("click", () => 
{
    if (selectedQueue === "") 
    {
        alert("Please select a patient first.");
        return;
    }

    issuePopup.style.display = "flex";
});

cancelBtn.addEventListener("click", () => 
{
    issuePopup.style.display = "none";
});

sendDoctorBtn.addEventListener("click", () => 
{
    issuePopup.style.display = "none";

    returnedRecord.innerHTML = `
        <p>
            <strong>${selectedQueue}</strong><br>
            ${patients[selectedQueue].name}<br>
            Returned for doctor review
        </p>
    `;

    messageText.textContent = "Prescription sent back to doctor successfully.";
    messagePopup.style.display = "block";
});

dispenseBtn.addEventListener("click", () => 
{
    if (selectedQueue === "") 
    {
        alert("Please select a patient first.");
        return;
    }

    dispensedRecord.innerHTML = `
        <p>
            <strong>${selectedQueue}</strong><br>
            ${patients[selectedQueue].name}<br>
            Dispensed successfully
        </p>
    `;

    messageText.textContent = "Medication dispensed successfully.";
    messagePopup.style.display = "block";
});

if (okBtn) {
    okBtn.addEventListener("click", () => 
    {
        messagePopup.style.display = "none";
    });
}

