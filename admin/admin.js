let data = JSON.parse(localStorage.getItem("utemPkuAdmin")) || {
    appointments: [
        { name: "abc", date: "12/4/2026", time: "8:00 am", status: "Completed", type: "Consultation" },
        { name: "bcd", date: "12/4/2026", time: "10:00 am", status: "No-Show", type: "Medical Checkup" },
        { name: "cde", date: "12/4/2026", time: "9:00 am", status: "Cancelled", type: "Medical Checkup" }
    ],
    late: [
        { name: "abc", date: "14/3/2026", time: "8:00 am", status: "Confirmed", type: "Consultation" }
    ],
    queue: [
        { name: "abc", date: "14/3/2026", time: "8:00 am", status: "Confirmed", type: "Consultation", queue: 10, room: "Room 8" }
    ],
    slots: [
        { slot: "Slot 1", doctor: "A", date: "14/3/2026", time: "8:00 am", capacity: 10 },
        { slot: "Slot 1", doctor: "A", date: "14/3/2026", time: "8:00 am", capacity: 10 },
        { slot: "Slot 1", doctor: "A", date: "14/3/2026", time: "8:00 am", capacity: 10 },
        { slot: "Slot 1", doctor: "A", date: "14/3/2026", time: "8:00 am", capacity: 10 }
    ],
    staff: [
        { name: "Ali bin Abu", id: "D001", role: "Doctor", status: "Active", password: "123" },
        { name: "Ahmad Marthes", id: "A001", role: "Admin", status: "Active", password: "123" },
        { name: "Ahmad Brokoli", id: "P001", role: "Pharmacist", status: "Active", password: "123" },
        { name: "Justin Bieber", id: "A002", role: "Admin", status: "Inactive", password: "123" }
    ]
};

let deleteSlotMode = false;
let deleteStaffMode = false;
let editStaffMode = false;
let editingStaffIndex = null;
let confirmFunction = null;

function saveData() {
    localStorage.setItem("utemPkuAdmin", JSON.stringify(data));
}

function showPage(pageId, button) {
    document.querySelectorAll(".page").forEach(page => page.classList.remove("active"));
    document.querySelectorAll(".tab").forEach(tab => tab.classList.remove("active"));

    document.getElementById(pageId).classList.add("active");
    button.classList.add("active");

    renderAll();
}

function getDot(status) {
    if (status === "No-Show" || status === "Cancelled") return "red-dot";
    if (status === "Confirmed") return "blue-dot";
    return "green-dot";
}

function renderDashboard() {
    document.getElementById("totalToday").textContent = data.appointments.length + data.late.length;
    document.getElementById("waitingPatients").textContent = data.queue.filter(q => q.status === "Waiting" || q.status === "Confirmed").length;
    document.getElementById("activeConsult").textContent = data.queue.filter(q => q.status === "In Progress").length || 6;
    document.getElementById("availableDoctors").textContent = data.staff.filter(s => s.role === "Doctor" && s.status === "Active").length || 7;
}

function showAppointmentDates() {
    document.querySelectorAll(".sub").forEach(btn => btn.classList.remove("active"));

    const firstSub = document.querySelector(".sub");
    if (firstSub) firstSub.classList.add("active");

    let html = `
        <div class="date-filter">
            <input type="date" id="appointmentDateSelect" onchange="filterAppointmentsByDate()">
        </div>

        <div id="filteredAppointments" class="cards"></div>
    `;

    document.getElementById("appointmentContent").innerHTML = html;
}



function filterAppointmentsByDate() {
    const selectedDate = document.getElementById("appointmentDateSelect").value;
    const container = document.getElementById("filteredAppointments");

    const filtered = data.appointments.filter(appt => {
        const parts = appt.date.split("/");
        const formattedDate =
            `${parts[2]}-${parts[1].padStart(2, "0")}-${parts[0].padStart(2, "0")}`;

        return formattedDate === selectedDate;
    });

    let html = "";

    filtered.forEach(appt => {
        html += `
            <div class="card">
                <h3>Patient Name: ${appt.name}</h3>
                <div class="two">
                    <p>Date: ${appt.date}</p>
                    <p>Time: ${appt.time}</p>
                </div>
                <div class="two">
                    <p>Status: <span class="dot ${getDot(appt.status)}"></span>${appt.status}</p>
                    <p>Type: ${appt.type}</p>
                </div>
            </div>
        `;
    });

    container.innerHTML = html || `<p>No appointments found for this date.</p>`;
}

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

function changeQueueStatus(index, status) {
    data.queue[index].status = status;
    saveData();
    renderQueue();
    renderDashboard();
}

function renderSlots() {
    let html = "";

    data.slots.forEach((slot, index) => {
        html += `
            <div class="card">
                ${deleteSlotMode ? `<button class="delete-x" onclick="askDeleteSlot(${index})">&times;</button>` : ""}
                <h3>${slot.slot} &nbsp;&nbsp;&nbsp;&nbsp; Doctor: ${slot.doctor}</h3>
                <div class="two">
                    <p>Date: ${slot.date}</p>
                    <p>Time: ${slot.time}</p>
                </div>
                <p>Capacity: ${slot.capacity}</p>
            </div>
        `;
    });

    document.getElementById("slotContent").innerHTML = html;
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
        alert("Failed to add slot.");
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

function toggleDeleteSlot() {
    deleteSlotMode = !deleteSlotMode;
    document.getElementById("deleteSlotBtn").classList.toggle("active", deleteSlotMode);
    renderSlots();
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

function askDeleteSlot(index) {
    showConfirm("Are you sure you want to delete this slot?", function () {
        data.slots.splice(index, 1);
        saveData();
        renderSlots();
    });
}

function renderStaff() {
    let html = "";

    data.staff.forEach((staff, index) => {
        html += `
            <div class="card">
                ${deleteStaffMode ? `<button class="delete-x" onclick="askDeleteStaff(${index})">&times;</button>` : ""}
                ${editStaffMode ? `<button class="edit-icon" onclick="editStaff(${index})">✎</button>` : ""}
                <p>Name: ${staff.name}</p>
                <p>Staff ID: ${staff.id}</p>
                <p>Role: ${staff.role}</p>
                <p>Status: ${staff.status}</p>
            </div>
        `;
    });

    document.getElementById("staffContent").innerHTML = html;
}

function showStaffForm() {
    editingStaffIndex = null;
    document.getElementById("saveStaffBtn").textContent = "Create";
    document.getElementById("staffName").value = "";
    document.getElementById("staffId").value = "";
    document.getElementById("staffRole").value = "Admin";
    document.getElementById("staffPassword").value = "";
    document.getElementById("staffForm").classList.remove("hidden");
    document.getElementById("addStaffBtn").classList.add("active");
}

function hideStaffForm() {
    document.getElementById("staffForm").classList.add("hidden");
    document.getElementById("addStaffBtn").classList.remove("active");
}

function saveStaff() {
    let staff = {
        name: document.getElementById("staffName").value || "Hadi Merican",
        id: document.getElementById("staffId").value || "A003",
        role: document.getElementById("staffRole").value,
        status: "Active",
        password: document.getElementById("staffPassword").value || "123"
    };

    if (editingStaffIndex === null) {
        data.staff.push(staff);
    } else {
        data.staff[editingStaffIndex] = staff;
    }

    saveData();
    hideStaffForm();
    renderStaff();
    renderDashboard();
}

function toggleDeleteStaff() {
    deleteStaffMode = !deleteStaffMode;
    editStaffMode = false;
    document.getElementById("deleteStaffBtn").classList.toggle("active", deleteStaffMode);
    document.getElementById("editStaffBtn").classList.remove("active");
    renderStaff();
}

function toggleEditStaff() {
    editStaffMode = !editStaffMode;
    deleteStaffMode = false;
    document.getElementById("editStaffBtn").classList.toggle("active", editStaffMode);
    document.getElementById("deleteStaffBtn").classList.remove("active");
    renderStaff();
}

function editStaff(index) {
    let staff = data.staff[index];
    editingStaffIndex = index;

    document.getElementById("staffName").value = staff.name;
    document.getElementById("staffId").value = staff.id;
    document.getElementById("staffRole").value = staff.role;
    document.getElementById("staffPassword").value = staff.password;
    document.getElementById("saveStaffBtn").textContent = "Save";
    document.getElementById("staffForm").classList.remove("hidden");
}

function askDeleteStaff(index) {
    showConfirm("Are you sure you want to delete this staff?", function () {
        data.staff.splice(index, 1);
        saveData();
        renderStaff();
        renderDashboard();
    });
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





function renderCurrentPage() {


    if (document.getElementById("weeklyChart")) {
    renderWeeklyChart();
}
    if (document.getElementById("totalToday")) {
        renderDashboard();
    }

    if (document.getElementById("appointmentContent")) {
    let html = `<button class="actions-button active">12 April 2026 📅 ▼</button><div class="cards">`;

    data.appointments.forEach(appt => {
        html += `
            <div class="card">
                <h3>Patient Name: ${appt.name}</h3>
                <div class="two">
                    <p>Date: ${appt.date}</p>
                    <p>Time: ${appt.time}</p>
                </div>
                <div class="two">
                    <p>Status: <span class="dot ${getDot(appt.status)}"></span>${appt.status}</p>
                    <p>Type: ${appt.type}</p>
                </div>
            </div>
        `;
    });

    html += `</div>`;
    document.getElementById("appointmentContent").innerHTML = html;
}

    if (document.getElementById("queueContent")) {
        renderQueue();
    }

    if (document.getElementById("slotContent")) {
        renderSlots();
    }

    if (document.getElementById("staffContent")) {
        renderStaff();
    }
}

function renderWeeklyChart() {
    const chart = document.getElementById("weeklyChart");

    if (!chart) return;

    const weeklyData = {
        Mon: 0,
        Tue: 0,
        Wed: 0,
        Thu: 0,
        Fri: 0,
        Sat: 0,
        Sun: 0
    };

    data.appointments.forEach(appt => {
        const parts = appt.date.split("/");
        const d = new Date(parts[2], parts[1] - 1, parts[0]);

        const day = d.toLocaleDateString(
            "en-US",
            { weekday: "short" }
        );

        if (weeklyData[day] !== undefined) {
            weeklyData[day]++;
        }
    });

    const max = Math.max(
        ...Object.values(weeklyData),
        1
    );

    chart.innerHTML = "";

    Object.entries(weeklyData).forEach(([day, count]) => {

        const height = (count / max) * 120;

        chart.innerHTML += `
            <div class="chart-item">
                <div class="chart-bar"
                     style="height:${height}px">
                     ${count}
                </div>
                <div class="chart-label">${day}</div>
            </div>
        `;
    });
}

renderCurrentPage();



function logout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "../login_register/login.php";
    }
}

function toggleMenu() {
    document
        .getElementById("dropdownMenu")
        .classList.toggle("hidden");
}
function toggleMenu() {

    document
        .getElementById("profileDropdown")
        .classList.toggle("showDropdown");
}

if (document.getElementById("appointmentContent")) {
    showAppointmentDates();
}

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
    const formData = new FormData();
    formData.append("appointmentID", id);

    const response = await fetch("../database/markArrived.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    if (result.success) {
        alert("Patient marked as arrived.");
        loadAppointments();
    } else {
        alert("Failed to mark arrived.");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    loadAppointments();
});

    



function renderQueueTable(list = queueList) {
    const table = document.getElementById("queueTableBody");
    if (!table) return;

    table.innerHTML = "";

    list.forEach(item => {
        const statusClass = item.status.toLowerCase().replaceAll(" ", "");

        table.innerHTML += `
            <tr>
                <td>${item.queueNo}</td>
                <td>${item.patient}</td>
                <td>${item.userID}</td>
                <td>${item.type}</td>
                <td>${item.room}</td>
                <td>
                    <span class="status-badge status-${statusClass}">
                        ${item.status}
                    </span>
                </td>
                <td>
                    <button class="table-btn" onclick="nextQueueStatus('${item.queueNo}')">
                        Next
                    </button>
                </td>
            </tr>
        `;
    });
}

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

function nextQueueStatus(queueNo) {
    const item = queueList.find(q => q.queueNo === queueNo);
    if (!item) return;

    if (item.status === "Waiting") {
        item.status = "In Consultation";
        item.room = "Room 1";
    } else if (item.status === "In Consultation") {
        item.status = "At Pharmacy";
        item.room = "Pharmacy";
    } else if (item.status === "At Pharmacy") {
        item.status = "Completed";
    }

    filterQueue();
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

    list.forEach(item => {
        const statusClass = item.queueStatus
            .toLowerCase()
            .replaceAll(" ", "");

        table.innerHTML += `
            <tr>
                <td>Q${item.queueNo.toString().padStart(3, "0")}</td>
                <td>${item.fullName}</td>
                <td>${item.userID}</td>
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
// SLOT MANAGEMENT
// ==========================

let slotList = [];

async function loadSlots() {
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

        table.innerHTML += `
        <tr>

            <td>${slot.slotID}</td>
            <td>${slot.slotDate}</td>
            <td>${slot.startTime}</td>
            <td>${slot.endTime}</td>
            <td>${slot.slotType}</td>
            <td>${slot.capacity}</td>

            <td>
                <button class="btn-edit" onclick="editSlot(${slot.slotID})">Edit</button>
                <button class="btn-delete" onclick="deleteSlot(${slot.slotID})">Delete</button>
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



});

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

// ================= USERS =================

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

                <button class="btn-delete" onclick="deleteUser('${user.userID}')">
    Delete
</button>

            </td>

        </tr>
        `;

    });

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