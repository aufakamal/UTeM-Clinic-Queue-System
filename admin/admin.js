

function showLateArrival() {
    document.querySelectorAll(".sub").forEach(btn => btn.classList.remove("active"));
    event.target.classList.add("active");

    let html = `<div class="cards">`;

    data.late.forEach((appt, index) => {
        html += `
            <div class="card">
                <h3>Patient ${index + 1} &nbsp;&nbsp; Name: ${appt.name}</h3>
                <div class="two">
                    <p>Date: ${appt.date}</p>
                    <p>Time: ${appt.time}</p>
                </div>
                <div class="two">
                    <p>Status: <span class="dot ${getDot(appt.status)}"></span>${appt.status}</p>
                    <p>Type: ${appt.type}</p>
                </div>
                <button class="green" onclick="addLateToQueue(${index})">Add to end<br>queue</button>
                <button class="red" onclick="cancelLate(${index})">Cancel</button>
            </div>
        `;
    });

    html += `</div>`;
    document.getElementById("appointmentContent").innerHTML = html;
}

function addLateToQueue(index) {
    let patient = data.late.splice(index, 1)[0];
    patient.status = "Waiting";
    patient.queue = 10 + data.queue.length;
    patient.room = "Room 8";
    data.queue.push(patient);
    saveData();
    showLateArrival();
    renderQueue();
}

function cancelLate(index) {
    let patient = data.late.splice(index, 1)[0];
    patient.status = "Cancelled";
    data.appointments.push(patient);
    saveData();
    showLateArrival();
}

function renderQueue() {
    let html = `<div class="cards">`;

    data.queue.forEach((patient, index) => {
        html += `
            <div class="card">
                <h3>Patient ${index + 1} &nbsp;&nbsp; Name: ${patient.name}</h3>
                <div class="two">
                    <p>Date: ${patient.date}</p>
                    <p>Time: ${patient.time}</p>
                </div>
                <div class="two">
                    <p>Status: <span class="dot ${getDot(patient.status)}"></span>${patient.status}</p>
                    <p>Type: ${patient.type}</p>
                </div>
        `;

        if (patient.status !== "Confirmed") {
            html += `
                <div class="two">
                    <p>Queue Number: ${patient.queue}</p>
                    <p>Room: ${patient.room}</p>
                </div>
            `;
        }

        if (patient.status === "Confirmed") {
            html += `
                <button class="green" onclick="changeQueueStatus(${index}, 'Waiting')">Arrived</button>
                <button class="red" onclick="changeQueueStatus(${index}, 'No-Show')">No-show</button>
            `;
        }

        if (patient.status === "Waiting") {
            html += `<button class="green" onclick="changeQueueStatus(${index}, 'In Progress')">Start</button>`;
        }

        if (patient.status === "In Progress") {
            html += `<button class="green" onclick="changeQueueStatus(${index}, 'At Pharmacy')">Send Pharmacy</button>`;
        }

        if (patient.status === "At Pharmacy") {
            html += `<button class="green" onclick="changeQueueStatus(${index}, 'Completed')">Complete</button>`;
        }

        html += `</div>`;
    });

    html += `</div>`;
    document.getElementById("queueContent").innerHTML = html;
}




function showSlotForm() {
    document.getElementById("slotForm").classList.remove("hidden");
    document.getElementById("createSlotBtn").classList.add("active");
}

function hideSlotForm() {
    document.getElementById("slotForm").classList.add("hidden");
    document.getElementById("createSlotBtn").classList.remove("active");
}

async function createSlot() {
    const formData = new FormData();

    formData.append("slotDate", document.getElementById("slotDate").value);
    formData.append("startTime", document.getElementById("slotStartTime").value);
    formData.append("endTime", document.getElementById("slotEndTime").value);
    formData.append("slotType", document.getElementById("slotType").value);
    formData.append("capacity", document.getElementById("slotCapacity").value);

    const response = await fetch("../database/addSlot.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("Slot added successfully.");
        hideSlotForm();
        loadSlots();
    } else {
        alert(result.message || "Failed to add slot.");
    }
}

async function deleteSlot(slotID) {
    if (!confirm("Delete this slot?")) {
        return;
    }

    const formData = new FormData();
    formData.append("slotID", slotID);

    const response = await fetch("../database/deleteSlot.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("Slot deleted successfully.");
        loadSlots();
    } else {
        alert(result.message || "Failed to delete slot.");
    }
}



function editSlot(slotID) {
    const slot = slotList.find(s => s.slotID == slotID);

    document.getElementById("slotDate").value = slot.slotDate;
    document.getElementById("slotStartTime").value = slot.startTime;
    document.getElementById("slotEndTime").value = slot.endTime;
    document.getElementById("slotType").value = slot.slotType;
    document.getElementById("slotCapacity").value = slot.capacity;

    document.getElementById("slotForm").classList.remove("hidden");

    const saveBtn = document.querySelector("#slotForm .green");
    saveBtn.textContent = "Update";
    saveBtn.setAttribute("onclick", `updateSlot(${slotID})`);
}

async function updateSlot(slotID) {
    const formData = new FormData();

    formData.append("slotID", slotID);
    formData.append("slotDate", document.getElementById("slotDate").value);
    formData.append("startTime", document.getElementById("slotStartTime").value);
    formData.append("endTime", document.getElementById("slotEndTime").value);
    formData.append("slotType", document.getElementById("slotType").value);
    formData.append("capacity", document.getElementById("slotCapacity").value);

    const response = await fetch("../database/updateSlot.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("Slot updated successfully.");
        hideSlotForm();

        const saveBtn = document.querySelector("#slotForm .green");
        saveBtn.textContent = "Create";
        saveBtn.setAttribute("onclick", "createSlot()");

        loadSlots();
    } else {
        alert("Failed to update slot.");
    }
}






function hideStaffForm() {
    document.getElementById("staffForm").classList.add("hidden");
    document.getElementById("addStaffBtn").classList.remove("active");
}









function showConfirm(text, action) {
    confirmFunction = action;
    document.getElementById("confirmText").textContent = text;
    document.getElementById("confirmBox").classList.remove("hidden");
}

function confirmYes() {
    if (confirmFunction) confirmFunction();
    closeConfirm();
}

function closeConfirm() {
    document.getElementById("confirmBox").classList.add("hidden");
}







/* =========================================
   ni functions yg common
========================================= */

function logout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "../login_register/login.php";
    }
}


function toggleMenu() {

    document
        .getElementById("profileDropdown")
        .classList.toggle("showDropdown");
}

/* =========================================
   page appoinments
========================================= */

let appointmentList = [];

async function loadAppointments() {
    try {
        console.log("Loading appointments...");

        const response = await fetch("../database/getAppointments.php");
        appointmentList = await response.json();

        console.log("Appointments from database:", appointmentList);

        renderAppointments();

    } catch (err) {
        console.log("Appointment error:", err);
    }
}
    function renderAppointments(list = appointmentList) {

    const tableBody = document.getElementById("appointmentTableBody");

    if (!tableBody) return;

    tableBody.innerHTML = "";

    list.forEach(appt => {

        const patientName =
            appt.appointmentFor === "Dependant"
                ? appt.dependantName
                : appt.fullName;

        const attendanceStatus = appt.attendanceStatus || "Pending";

        tableBody.innerHTML += `
            <tr>
                <td>${appt.appointmentID}</td>
                <td>${patientName}</td>
                <td>${appt.userID}</td>
                <td>${appt.appointmentType}</td>
                <td>${appt.slotDate}</td>
                <td>${appt.startTime} - ${appt.endTime}</td>

                <td>
                    <span class="status-badge status-${appt.appointmentStatus.toLowerCase().replaceAll(" ", "")}">
                        ${appt.appointmentStatus}
                    </span>
                </td>

                <td>
                    <span class="status-badge status-${attendanceStatus.toLowerCase().replaceAll(" ", "")}">
                        ${attendanceStatus}
                    </span>
                </td>

                <td>
                    ${
                        attendanceStatus === "Pending"
                        ? `
                            <button class="table-btn" onclick="markArrived('${appt.appointmentID}')">
                                Arrived
                            </button>

                            <button class="table-btn no-show-btn" onclick="markNoShow('${appt.appointmentID}')">
                                No Show
                            </button>
                          `
                        : "-"
                    }
                </td>
            </tr>
        `;
    });
}
function filterAppointments() {
    const search = document.getElementById("appointmentSearch").value.toLowerCase();
    const status = document.getElementById("appointmentStatusFilter").value;
    const type = document.getElementById("appointmentTypeFilter").value;

    const filtered = appointmentList.filter(appt => {
        const patientName =
            appt.appointmentFor === "Dependant"
                ? appt.dependantName
                : appt.fullName;

        const matchesSearch =
            appt.appointmentID.toString().toLowerCase().includes(search) ||
            appt.userID.toLowerCase().includes(search) ||
            appt.appointmentType.toLowerCase().includes(search) ||
            patientName.toLowerCase().includes(search);

        const matchesStatus =
            status === "All" || status === "" || appt.appointmentStatus === status;

        const matchesType =
            type === "All" || type === "" || appt.appointmentType === type;

        return matchesSearch && matchesStatus && matchesType;
    });

    renderAppointments(filtered);
}

async function markNoShow(id) {
    if (!confirm("Mark this appointment as No Show?")) {
        return;
    }

    const formData = new FormData();
    formData.append("appointmentID", id);

    const response = await fetch("../database/markNoShow.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("Appointment marked as No Show.");
        loadAppointments();
    } else {
        alert("Failed to mark No Show.");
    }
}

async function markArrived(id) {
    if (!confirm("Mark this patient as Arrived and add to queue?")) {
        return;
    }

    const formData = new FormData();
    formData.append("appointmentID", id);

    const response = await fetch("../database/markArrived.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert(
            "Patient marked as arrived.\n" +
            "Queue No: " + result.queueNo + "\n" +
            "Assigned Room: " + result.roomNo
        );

        loadAppointments();

        if (typeof loadQueue === "function") {
            loadQueue();
        }

    } else {
        alert(result.message || "Failed to mark arrived.");
    }
}

/* =========================================
   PAGE INITIALIZER
========================================= */

document.addEventListener("DOMContentLoaded", () => {
    loadAppointments();
    setMinimumSlotDate();

    loadSlots();
});

    





function filterQueue() {
    const search = document.getElementById("queueSearch").value.toLowerCase();
    const status = document.getElementById("queueStatusFilter").value;
    const room = document.getElementById("queueRoomFilter").value;

    const filtered = queueList.filter(item => {
        const matchSearch =
            item.patient.toLowerCase().includes(search) ||
            item.userID.toLowerCase().includes(search) ||
            item.queueNo.toLowerCase().includes(search);

        const matchStatus = status === "All" || item.status === status;
        const matchRoom = room === "All" || item.room === room;

        return matchSearch && matchStatus && matchRoom;
    });

    renderQueueTable(filtered);
}



document.addEventListener("DOMContentLoaded", () => {
    renderQueueTable();
});

function generateDefaultSlots() {
    if (localStorage.getItem("defaultSlotsCreated")) return;

    const defaultSlots = [];

    for (let i = 1; i <= 10; i++) {
        defaultSlots.push({
            id: "S" + i.toString().padStart(3, "0"),
            date: new Date().toISOString().split("T")[0],
            time: `${8 + i}:00 AM`,
            type: i <= 5 ? "Same-Day" : "Scheduled",
            capacity: 10,
            booked: 0,
            status: "Available"
        });
    }

    localStorage.setItem("adminSlots", JSON.stringify(defaultSlots));
    localStorage.setItem("defaultSlotsCreated", "true");
}



document.addEventListener("DOMContentLoaded", () => {
    console.log("Admin JS loaded");

    if (document.getElementById("appointmentTableBody")) {
        console.log("Appointment table found");
        loadAppointments();
    }
});

/* =========================================
   ni page queue
========================================= */

let queueList = [];

async function loadQueue() {
    try {
        const response = await fetch("../database/getQueue.php");
        queueList = await response.json();
        renderQueueTable();
    } catch (err) {
        console.log("Queue error:", err);
    }
}

function renderQueueTable(list = queueList) {
    const table = document.getElementById("queueTableBody");
    if (!table) return;

    table.innerHTML = "";
    renderRoomDashboard(list);

    list.forEach(item => {
        const statusClass = item.queueStatus
            .toLowerCase()
            .replaceAll(" ", "");

        table.innerHTML += `
            <tr>
                <td>Q${item.queueNo.toString().padStart(3, "0")}</td>
                <td>${item.fullName}</td>
                <td>${item.userID}</td>
                <td class="room-cell">-</td>
                <td>${item.appointmentType}</td>
                <td>${item.slotDate}</td>
                <td>${item.startTime} - ${item.endTime}</td>
                <td>
                    <span class="status-badge status-${statusClass}">
                        ${item.queueStatus}
                    </span>
                </td>
            </tr>
        `;
    });
}

function renderRoomDashboard(list = queueList) {
    const dashboard = document.getElementById("roomDashboard");

    if (!dashboard) return;

    const calledQueue = list.filter(item =>
        item.queueStatus === "Called" &&
        item.roomNo &&
        item.roomNo !== "-"
    );

    const rooms = {};

    calledQueue.forEach(item => {
        const room = item.roomNo;

        if (!rooms[room]) {
            rooms[room] = [];
        }

        rooms[room].push(item);
    });

    dashboard.innerHTML = "";

    if (Object.keys(rooms).length === 0) {
        dashboard.innerHTML = `
            <div class="room-card">
                <h3>-</h3>
                <p>No patient called yet</p>
            </div>
        `;
        return;
    }

    Object.keys(rooms).forEach(room => {
        const firstPatient = rooms[room][0];

        dashboard.innerHTML += `
            <div class="room-card">
                <h3>${room}</h3>
                <p>Next Patient</p>
                <strong>Q${String(firstPatient.queueNo).padStart(3, "0")}</strong>
                <span>${firstPatient.fullName}</span>
            </div>
        `;
    });
}

function filterQueue() {
    const search = document.getElementById("queueSearch").value.toLowerCase();
    const status = document.getElementById("queueStatusFilter").value;

    const filtered = queueList.filter(item => {
        const matchesSearch =
            item.queueNo.toString().includes(search) ||
            item.fullName.toLowerCase().includes(search) ||
            item.userID.toLowerCase().includes(search);

        const matchesStatus =
            status === "All" || item.queueStatus === status;

        return matchesSearch && matchesStatus;
    });

    renderQueueTable(filtered);
}

// ==========================
// ni page slot management
// ==========================

let slotList = [];

async function generateDailySlots() {
    try {
        await fetch("../database/generateDailySlots.php");
    } catch (err) {
        console.log("Generate slots error:", err);
    }
}

async function loadSlots() {
    await generateDailySlots();
    await fetch("../database/updateSlotStatus.php");

    try {
        const response = await fetch("../database/getSlots.php");
        slotList = await response.json();
        renderSlotTable();
    } catch (err) {
        console.error("Slot error:", err);
    }
}

function renderSlotTable(list = slotList) {

    const table = document.getElementById("slotTableBody");

    if (!table) return;

    table.innerHTML = "";

    list.forEach(slot => {

        const hasAppointment = Number(slot.appointmentCount) > 0;

        table.innerHTML += `
        <tr>
            <td>${slot.slotID}</td>
            <td>${slot.slotDate}</td>
            <td>${slot.startTime}</td>
            <td>${slot.endTime}</td>
            <td>${slot.slotType}</td>
            <td>${slot.capacity}</td>
            <td>${slot.appointmentCount} / ${slot.capacity}</td>
            <td>
                <span class="status-badge status-${slot.slotStatus.toLowerCase()}">
                    ${slot.slotStatus}
                </span>
            </td>
            <td>
                ${
                    hasAppointment
                    ? `<span class="locked-slot">🔒 Booked</span>`
                    : `
                        <button class="btn-edit" onclick="editSlot(${slot.slotID})">Edit</button>
                        <button class="btn-delete" onclick="deleteSlot(${slot.slotID})">Delete</button>
                      `
                }
            </td>
        </tr>
        `;
    });
}

function filterSlots(){

    const search =
    document.getElementById("slotSearch").value.toLowerCase();

    const type =
    document.getElementById("slotTypeFilter").value;

    const filtered = slotList.filter(slot=>{

        const matchesSearch =

            slot.slotDate.toLowerCase().includes(search) ||

            slot.slotType.toLowerCase().includes(search) ||

            slot.startTime.includes(search) ||

            slot.endTime.includes(search);

        const matchesType =

            type==="All" ||

            slot.slotType===type;

        return matchesSearch && matchesType;

    });

    renderSlotTable(filtered);

}



/* =========================================
   ni page dashboard
========================================= */

async function loadDashboard() {
    
    try {
        const response = await fetch("../database/getDashboard.php");
        const data = await response.json();

        document.getElementById("totalToday").textContent = data.totalAppointments;
        document.getElementById("waitingPatients").textContent = data.waitingPatients;
        document.getElementById("activeConsult").textContent = data.activeConsultations;
        document.getElementById("availableDoctors").textContent = data.availableDoctors;

    } catch (err) {
        console.error("Dashboard error:", err);
    }
}

async function loadWeeklyAppointments() {

    const response = await fetch("../database/getWeeklyAppointments.php");
    const data = await response.json();
    console.log("Weekly Data:", data);

    const values = [
        data[2],
        data[3],
        data[4],
        data[5],
        data[6],
        data[7],
        data[1]
    ];

    const days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];

    const chart = document.getElementById("weeklyChart");

    chart.innerHTML = "";
    const maxValue = Math.max(...values, 1);


    values.forEach((value, index) => {
    const barHeight = value === 0 ? 25 : (value / maxValue) * 120;

    chart.innerHTML += `
        <div class="chart-item">
            <div class="chart-bar" style="height:${barHeight}px">
                ${value}
            </div>
            <div class="chart-label">${days[index]}</div>
        </div>
    `;
});

}

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("appointmentTableBody")) {
        loadAppointments();
    }

    if (document.getElementById("queueTableBody")) {
        loadQueue();
    }

    if (document.getElementById("historyTableBody")) {
        loadHistory();
    }

    if(document.getElementById("slotTableBody")){
    loadSlots();
}

if (document.getElementById("usersTableBody")) {
    loadUsers();
}

if (document.getElementById("totalToday")) {
    loadDashboard();
}

if (document.getElementById("weeklyChart")) {
    loadWeeklyAppointments();
}

if (document.getElementById("profileName")) {
    loadAdminProfile();
}

});

/* =========================================
   ni page history
========================================= */

let historyList = [];

async function loadHistory() {
    try {
        const response = await fetch("../database/getHistory.php");
        historyList = await response.json();
        renderHistoryTable();
    } catch (err) {
        console.log("History error:", err);
    }
}

function renderHistoryTable(list = historyList) {
    const table = document.getElementById("historyTableBody");
    if (!table) return;

    table.innerHTML = "";

    list.forEach(item => {
        const statusClass = item.queueStatus
            .toLowerCase()
            .replaceAll(" ", "");

        table.innerHTML += `
            <tr>
                <td>${item.consultationID}</td>
                <td>Q${item.queueNo.toString().padStart(3, "0")}</td>
                <td>${item.patientName}</td>
                <td>${item.doctorName}</td>
                <td>${item.startTime}</td>
                <td>${item.endTime}</td>
                <td>
                    <span class="status-badge status-${statusClass}">
                        ${item.queueStatus}
                    </span>
                </td>
            </tr>
        `;
    });
}

function filterHistory() {
    const search = document.getElementById("historySearch").value.toLowerCase();

    const filtered = historyList.filter(item => {
        return (
            item.consultationID.toString().includes(search) ||
            item.patientName.toLowerCase().includes(search) ||
            item.doctorName.toLowerCase().includes(search) ||
            item.queueNo.toString().includes(search)
        );
    });

    renderHistoryTable(filtered);
}

// ================= ni page users =================

let users = [];

async function loadUsers() {
    try {
        const response = await fetch("../database/getUsers.php");
        users = await response.json();
        renderUsers(users);
    } catch (err) {
        console.error(err);
    }
}

function renderUsers(data) {

    window.filterUsers = function () {
        const search = document.getElementById("userSearch").value.toLowerCase();
        const role = document.getElementById("userRoleFilter").value;

        const filtered = users.filter(user => {
            const matchesSearch =
                user.userID.toLowerCase().includes(search) ||
                user.fullName.toLowerCase().includes(search) ||
                user.email.toLowerCase().includes(search) ||
                user.phoneNo.toLowerCase().includes(search);

            const matchesRole =
                role === "All" || user.roleName === role;

            return matchesSearch && matchesRole;
        });

        renderUsers(filtered);
    };

    const tbody = document.getElementById("usersTableBody");

    if (!tbody) return;

    tbody.innerHTML = "";

    data.forEach(user => {

        tbody.innerHTML += `
        <tr>

            <td>${user.userID}</td>
            <td>${user.fullName}</td>
            <td>${user.roleName}</td>
            <td>${user.gender}</td>
            <td>${user.email}</td>
            <td>${user.phoneNo}</td>

            <td class="action-cell">

                <div class="action-left">

                    <button class="btn-edit"
                        onclick="editUser(
                            '${user.userID}',
                            '${user.fullName}',
                            '${user.gender}',
                            '${user.email}',
                            '${user.phoneNo}'
                        )">
                        Edit
                    </button>

                    <button class="btn-delete"
                        onclick="deleteUser('${user.userID}')">
                        Delete
                    </button>

                </div>

                ${
                    user.roleName === "Patient"
                    ? `
                        <button class="btn-view"
                            onclick="viewPatientDetails('${user.userID}')">
                            View Details
                        </button>
                    `
                    : ""
                }

            </td>

        </tr>
        `;

    });

}
async function viewPatientDetails(userID) {
    const response = await fetch("../database/getPatientDetails.php?userID=" + userID);
    const patient = await response.json();

    if (!patient.success) {
        alert("Patient details not found.");
        return;
    }

    alert(
        "Patient Details\n\n" +
        "Name: " + (patient.fullName || "-") + "\n" +
        "Patient Type: " + (patient.patientType || "-") + "\n" +
        "Blood Type: " + (patient.bloodType || "-") + "\n" +
        "Allergy: " + (patient.allergy || "-") + "\n" +
        "Chronic Condition: " + (patient.chronicCondition || "-") + "\n" +
        "Current Medication: " + (patient.currentMed || "-") + "\n" +
        "Emergency Contact: " + (patient.emergencyContactName || "-") + "\n" +
        "Emergency Phone: " + (patient.emergencyContactPhone || "-")
    );
}

async function deleteUser(userID) {
    if (!confirm("Delete this user?")) {
        return;
    }

    const formData = new FormData();
    formData.append("userID", userID);

    const response = await fetch("../database/deleteUser.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("User deleted successfully.");
        loadUsers();
    } else {
        alert(result.message || "Failed to delete user.");
    }
}

async function editUser(userID, fullName, gender, email, phoneNo) {

    const newName = prompt("Full Name:", fullName);
    if (newName === null) return;

    const newGender = prompt("Gender (Male/Female):", gender);
    if (newGender === null) return;

    const newEmail = prompt("Email:", email);
    if (newEmail === null) return;

    const newPhone = prompt("Phone Number:", phoneNo);
    if (newPhone === null) return;

    const formData = new FormData();
    formData.append("userID", userID);
    formData.append("fullName", newName);
    formData.append("gender", newGender);
    formData.append("email", newEmail);
    formData.append("phoneNo", newPhone);

    const response = await fetch("../database/updateUser.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("User updated successfully.");
        loadUsers();
    } else {
        alert(result.message || "Failed to update user.");
    }
}



async function loadAdminProfile() {

    const response = await fetch("../database/getAdminProfile.php");
    const admin = await response.json();

    document.getElementById("profileName").textContent = admin.fullName;
    document.getElementById("profileEmail").textContent = admin.email;
    document.getElementById("profileRole").textContent = admin.roleName;

    document.getElementById("profileUserID").value = admin.userID;
    document.getElementById("profilePhone").value = admin.phoneNo;
    
    document.getElementById("profileRoleInput").value = admin.roleName;

}

if (document.getElementById("profileName")) {
    loadAdminProfile();
}

function setMinimumSlotDate() {
    const slotDate = document.getElementById("slotDate");

    if (!slotDate) return;

    const today = new Date().toISOString().split("T")[0];
    slotDate.min = today;
}