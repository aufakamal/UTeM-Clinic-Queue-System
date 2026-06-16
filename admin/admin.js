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

function createSlot() {
    data.slots.push({
        slot: "Slot " + (data.slots.length + 1),
        doctor: document.getElementById("slotDoctor").value || "A",
        date: document.getElementById("slotDate").value || "14/3/2026",
        time: document.getElementById("slotTime").value || "8:00 am",
        capacity: document.getElementById("slotCapacity").value || "10"
    });

    saveData();
    hideSlotForm();
    renderSlots();
}

function toggleDeleteSlot() {
    deleteSlotMode = !deleteSlotMode;
    document.getElementById("deleteSlotBtn").classList.toggle("active", deleteSlotMode);
    renderSlots();
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
        window.location.href = "../login_register/login.html";
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