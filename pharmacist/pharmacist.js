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
        prescription: "Paracetamol 500mg<br>Quantity: 10<br>Current Stock: 200<br><br>Dosage: 3 times daily for 5 days<br>Doctor Note: After food"
    },

    A103:
    {
        name: "Ahmad Ali",
        queue: "A103",
        doctor: "Dr. Siti",
        allergy: "No Known Allergy",
        prescription: "Cough Syrup 10ml<br>Quantity: 1<br>Current Stock: 50<br><br>Dosage: 2 times daily for 3 days<br>Doctor Note: Drink more water"
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

okBtn.addEventListener("click", () =>
{
    messagePopup.style.display = "none";
});

const searchInput = document.querySelector(".searchInput");

if (searchInput)
{
    searchInput.addEventListener("keyup", function ()
    {
        const keyword = searchInput.value.toLowerCase();

        document.querySelectorAll(".queueBox").forEach(function (box)
        {
            const patientInfo = box.textContent.toLowerCase();

            if (patientInfo.includes(keyword))
            {
                box.style.display = "flex";
            }
            else
            {
                box.style.display = "none";
            }
        });
    });
}
