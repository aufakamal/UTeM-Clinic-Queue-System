

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

    const slotDate = document.getElementById("slotDate").value;
    const startTime = document.getElementById("slotStartTime").value;
    const endTime = document.getElementById("slotEndTime").value;
    const slotType = document.getElementById("slotType").value;
    const capacity = document.getElementById("slotCapacity").value;

    if (!slotDate) {
        alert("Please select a slot date.");
        return;
    }

    if (!startTime) {
        alert("Please select a start time.");
        return;
    }

    if (!endTime) {
        alert("Please select an end time.");
        return;
    }

    if (!slotType) {
        alert("Please select a slot type.");
        return;
    }

    if (!capacity || capacity <= 0) {
        alert("Please enter a valid slot capacity.");
        return;
    }

    const formData = new FormData();

    formData.append("slotDate", slotDate);
    formData.append("startTime", startTime);
    formData.append("endTime", endTime);
    formData.append("slotType", slotType);
    formData.append("capacity", capacity);

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
        const response = await fetch("../database/getAppointments.php");
        appointmentList = await response.json();

        renderAppointments();

    } catch (err) {
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
    const fromDate = document.getElementById("appointmentFromDate").value;
    const toDate = document.getElementById("appointmentToDate").value;

    const filtered = appointmentList.filter(appt => {

        const patientName =
            appt.appointmentFor === "Dependant"
                ? appt.dependantName
                : appt.fullName;

        const appointmentDate = appt.slotDate;

        const matchesSearch =
            appt.appointmentID.toString().includes(search) ||
            appt.userID.toLowerCase().includes(search) ||
            patientName.toLowerCase().includes(search) ||
            appt.appointmentType.toLowerCase().includes(search);

        const matchesStatus =
            status === "All" || appt.appointmentStatus === status;

        const matchesType =
            type === "All" || appt.appointmentType === type;

        const matchesFrom =
            !fromDate || appointmentDate >= fromDate;

        const matchesTo =
            !toDate || appointmentDate <= toDate;

        return (
            matchesSearch &&
            matchesStatus &&
            matchesType &&
            matchesFrom &&
            matchesTo
        );

    });

    renderAppointments(filtered);

}

function resetAppointmentFilter(){

    document.getElementById("appointmentSearch").value = "";
    document.getElementById("appointmentStatusFilter").value = "All";
    document.getElementById("appointmentTypeFilter").value = "All";
    document.getElementById("appointmentFromDate").value = "";
    document.getElementById("appointmentToDate").value = "";

    renderAppointments(appointmentList);

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
    if (document.getElementById("appointmentTableBody")) {
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
                <td class="room-cell">${item.roomNo || "-"}</td>
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
                    ? `<span class="locked-slot">Booked</span>`
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

function filterSlots() {

    const search =
        document.getElementById("slotSearch").value.toLowerCase();

    const type =
        document.getElementById("slotTypeFilter").value;

    const fromDate =
        document.getElementById("slotFromDate").value;

    const toDate =
        document.getElementById("slotToDate").value;

    const filtered = slotList.filter(slot => {

        const matchesSearch =

            slot.slotDate.toLowerCase().includes(search) ||

            slot.slotType.toLowerCase().includes(search) ||

            slot.startTime.includes(search) ||

            slot.endTime.includes(search);

        const matchesType =

            type === "All" ||

            slot.slotType === type;

        const matchesFrom =

            !fromDate ||

            slot.slotDate >= fromDate;

        const matchesTo =

            !toDate ||

            slot.slotDate <= toDate;

        return (
            matchesSearch &&
            matchesType &&
            matchesFrom &&
            matchesTo
        );

    });

    renderSlotTable(filtered);

}

function resetSlotFilter() {

    document.getElementById("slotSearch").value = "";
    document.getElementById("slotTypeFilter").value = "All";
    document.getElementById("slotFromDate").value = "";
    document.getElementById("slotToDate").value = "";

    renderSlotTable(slotList);

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
        document.getElementById("completedToday").textContent = data.completedToday;

    } catch (err) {
        console.error("Dashboard error:", err);
    }
}

async function loadMonthlyAppointments() {
    const response = await fetch("../database/getMonthlyAppointments.php");
    const data = await response.json();

    const chart = document.getElementById("monthlyChart");
    if (!chart) return;

    chart.innerHTML = "";

    const values = Object.values(data);
    const maxValue = Math.max(...values, 1);

    Object.keys(data).forEach(label => {
        const value = data[label];
        const height = value === 0 ? 12 : (value / maxValue) * 170;

        chart.innerHTML += `
            <div class="dashboard-chart-item">
                <div class="dashboard-chart-value">${value}</div>
                <div class="dashboard-chart-bar" style="height:${height}px"></div>
                <div class="dashboard-chart-label">${label}</div>
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

if (document.getElementById("monthlyChart")) {
    loadMonthlyAppointments();
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
    const status = document.getElementById("historyStatusFilter").value;
    const fromDate = document.getElementById("historyFromDate").value;
    const toDate = document.getElementById("historyToDate").value;

    const filtered = historyList.filter(item => {
        const startDate = item.startTime ? item.startTime.substring(0, 10) : "";

        const matchesSearch =
            item.consultationID.toString().toLowerCase().includes(search) ||
            item.queueNo.toString().toLowerCase().includes(search) ||
            item.patientName.toLowerCase().includes(search) ||
            item.doctorName.toLowerCase().includes(search);

        const matchesStatus =
            status === "All" || item.queueStatus === status;

        const matchesFromDate =
            !fromDate || startDate >= fromDate;

        const matchesToDate =
            !toDate || startDate <= toDate;

        return matchesSearch && matchesStatus && matchesFromDate && matchesToDate;
    });

    renderHistoryTable(filtered);
}

function resetHistoryFilter() {
    document.getElementById("historySearch").value = "";
    document.getElementById("historyStatusFilter").value = "All";
    document.getElementById("historyFromDate").value = "";
    document.getElementById("historyToDate").value = "";

    renderHistoryTable(historyList);
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
                <td>
                    <button class="btn-view" onclick="viewUserDetails('${user.userID}', '${user.roleName}')">
                        View Details
                    </button>
                </td>
            </tr>
        `;
    });
}

async function viewUserDetails(userID, roleName) {
    try {
        const response = await fetch("../database/getUserDetails.php?userID=" + userID + "&roleName=" + roleName);
        const user = await response.json();

        if (!user.success) {
            alert(user.message || "User details not found.");
            return;
        }

        let extraDetails = "";

        if (user.roleName === "Patient") {
            extraDetails = `
                <h3>Patient Information</h3>
                <div class="details-grid">
                    <p><b>Patient Type:</b> ${user.patientType || "-"}</p>
                    <p><b>Blood Type:</b> ${user.bloodType || "-"}</p>
                    <p><b>Allergy:</b> ${user.allergy || "-"}</p>
                    <p><b>Chronic Condition:</b> ${user.chronicCondition || "-"}</p>
                    <p><b>Current Medication:</b> ${user.currentMed || "-"}</p>
                    <p><b>Emergency Contact:</b> ${user.emergencyContactName || "-"}</p>
                    <p><b>Emergency Phone:</b> ${user.emergencyContactPhone || "-"}</p>
                </div>
            `;
        }

        if (user.roleName === "Doctor") {
            extraDetails = `
                <h3>Doctor Information</h3>
                <div class="details-grid">
                    <p><b>License No:</b> ${user.docLicenseNo || "-"}</p>
                    <p><b>Specialization:</b> ${user.specialization || "-"}</p>
                    <p><b>Room No:</b> ${user.roomNo || "-"}</p>
                </div>
            `;
        }

        if (user.roleName === "Pharmacist") {
            extraDetails = `
                <h3>Pharmacist Information</h3>
                <div class="details-grid">
                    <p><b>License No:</b> ${user.licenseNo || "-"}</p>
                </div>
            `;
        }

        document.getElementById("userDetailsContent").innerHTML = `
            <h3>Basic Information</h3>
            <div class="details-grid">
                <p><b>User ID:</b> ${user.userID || "-"}</p>
                <p><b>Full Name:</b> ${user.fullName || "-"}</p>
                <p><b>Role:</b> ${user.roleName || "-"}</p>
                <p><b>Gender:</b> ${user.gender || "-"}</p>
                <p><b>Date of Birth:</b> ${user.dateOfBirth || "-"}</p>
                <p><b>Email:</b> ${user.email || "-"}</p>
                <p><b>Phone No:</b> ${user.phoneNo || "-"}</p>
                <p><b>Address:</b> ${user.address || "-"}</p>
            </div>

            ${extraDetails}
        `;

        document.getElementById("userDetailsModal").classList.remove("hidden");

    } catch (err) {
        console.error(err);
        alert("Unable to load user details.");
    }
}

function closeUserDetails() {
    document.getElementById("userDetailsModal").classList.add("hidden");
}

async function viewUserDetails(userID, roleName) {
    const response = await fetch("../database/getUserDetails.php?userID=" + userID + "&roleName=" + roleName);
    const user = await response.json();

    if (!user.success) {
        alert("User details not found.");
        return;
    }

    let icon = "👤";
    let subtitle = user.roleName;

    if (user.roleName === "Patient") {
        icon = "🩺";
        subtitle = `Patient • ${user.patientType || "-"}`;
    } else if (user.roleName === "Doctor") {
        icon = "👨‍⚕️";
        subtitle = `Doctor • ${user.specialization || "-"}`;
    } else if (user.roleName === "Pharmacist") {
        icon = "💊";
        subtitle = "Pharmacist";
    } else if (user.roleName === "Admin") {
        icon = "🛡️";
        subtitle = "Administrator";
    }

    let extra = "";

    if (user.roleName === "Patient") {
        extra = `
            <div class="detail-section">
                <h3>Medical Information</h3>
                ${detailRow("Patient Type", user.patientType)}
                ${detailRow("Blood Type", user.bloodType)}
                ${detailRow("Allergy", user.allergy)}
                ${detailRow("Chronic Condition", user.chronicCondition)}
                ${detailRow("Current Medication", user.currentMed)}
            </div>

            <div class="detail-section">
                <h3>Emergency Contact</h3>
                ${detailRow("Contact Name", user.emergencyContactName)}
                ${detailRow("Contact Phone", user.emergencyContactPhone)}
            </div>
        `;
    }

    if (user.roleName === "Doctor") {
        extra = `
            <div class="detail-section">
                <h3>Professional Information</h3>
                ${detailRow("Doctor License No", user.docLicenseNo)}
                ${detailRow("Specialization", user.specialization)}
                ${detailRow("Room No", user.roomNo)}
            </div>
        `;
    }

    if (user.roleName === "Pharmacist") {
        extra = `
            <div class="detail-section">
                <h3>Professional Information</h3>
                ${detailRow("License No", user.licenseNo)}
            </div>
        `;
    }

    document.getElementById("userDetailsContent").innerHTML = `
        <div class="profile-head">
            <div class="profile-icon">${icon}</div>
            <h2>${user.fullName || "-"}</h2>
            <p>${subtitle}</p>
        </div>

        <div class="detail-section">
            <h3>Basic Information</h3>
            ${detailRow("User ID", user.userID)}
            ${detailRow("Full Name", user.fullName)}
            ${detailRow("Role", user.roleName)}
            ${detailRow("Gender", user.gender)}
            ${detailRow("Date of Birth", user.dateOfBirth)}
            ${detailRow("Email", user.email)}
            ${detailRow("Phone No", user.phoneNo)}
            ${detailRow("Address", user.address)}
        </div>

        ${extra}
    `;

    document.getElementById("userDetailsModal").classList.remove("hidden");
}

function detailRow(label, value) {
    return `
        <div class="detail-row">
            <span>${label}</span>
            <strong>${value || "-"}</strong>
        </div>
    `;
}

function closeUserDetails() {
    document.getElementById("userDetailsModal").classList.add("hidden");
}

function closeUserDetailsOutside(event) {
    if (event.target.id === "userDetailsModal") {
        closeUserDetails();
    }
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
