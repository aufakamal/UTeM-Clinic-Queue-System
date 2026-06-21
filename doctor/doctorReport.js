
/* ================= DOCTOR REPORT DASHBOARD JS ================= */

const doctorPatients = [
    { name: "Amir bin Amar", illness: "Flu", status: "Completed", date: "15 May 2025" },
    { name: "Siti Aisyah", illness: "Fever", status: "Completed", date: "15 May 2025" },
    { name: "Ahmad Hakimi", illness: "Hypertension", status: "Pending", date: "15 May 2025" },
    { name: "Nurul Huda", illness: "Diabetes", status: "Completed", date: "14 May 2025" },
    { name: "Farid Iqbal", illness: "Flu", status: "Pending", date: "14 May 2025" }
];

const doctorMedicines = [
    { name: "Paracetamol", stock: 320, status: "Available", usage: 320 },
    { name: "Amoxicillin", stock: 40, status: "Low Stock", usage: 180 },
    { name: "Metformin", stock: 150, status: "Available", usage: 140 },
    { name: "Omeprazole", stock: 60, status: "Medium", usage: 110 },
    { name: "Salbutamol", stock: 25, status: "Low Stock", usage: 90 }
];

const illnessData = [
    { illness: "Flu", value: 30.5, color: "#1189d6" },
    { illness: "Fever", value: 25.2, color: "#ff5b4a" },
    { illness: "Hypertension", value: 18.7, color: "#22c55e" },
    { illness: "Diabetes", value: 12.4, color: "#7c5ce6" },
    { illness: "Others", value: 13.2, color: "#8a94a6" }
];

const monthlyAppointments = [
    { month: "Jan", total: 120 },
    { month: "Feb", total: 145 },
    { month: "Mar", total: 170 },
    { month: "Apr", total: 225 },
    { month: "May", total: 256 }
];

document.addEventListener("DOMContentLoaded", function () {
    if (!document.querySelector(".drReportPage")) return;

    renderSummaryCards();
    renderIllnessChart();
    renderAppointmentChart();
    renderMedicineUsageChart();
    renderMedicineTable();
    renderPatientTable(doctorPatients);

    const searchInput = document.getElementById("patientSearchInput");
    const statusFilter = document.getElementById("statusFilter");

    if (searchInput && statusFilter) {
        searchInput.addEventListener("input", filterPatients);
        statusFilter.addEventListener("change", filterPatients);
    }
});

function renderSummaryCards() {
    document.getElementById("totalPatients").textContent = "256";

    const topIllness = illnessData[0];
    document.getElementById("commonIllness").textContent = topIllness.illness;
    document.getElementById("commonIllnessDesc").textContent = `78 cases (${topIllness.value}%)`;

    const lowStockItems = doctorMedicines.filter(med => med.status === "Low Stock");
    const lowestStock = lowStockItems.sort((a, b) => a.stock - b.stock)[0];

    document.getElementById("lowStockMedicine").textContent = lowestStock.name;
    document.getElementById("lowStockDesc").textContent = `Only ${lowestStock.stock} left in stock`;
}

function renderIllnessChart() {
    const pieChart = document.getElementById("illnessPieChart");
    const legend = document.getElementById("illnessLegend");

    let start = 0;
    const gradientParts = illnessData.map(item => {
        const end = start + item.value;
        const part = `${item.color} ${start}% ${end}%`;
        start = end;
        return part;
    });

    pieChart.style.background = `conic-gradient(${gradientParts.join(", ")})`;

    legend.innerHTML = illnessData.map(item => `
        <div class="drLegendItem">
            <span class="drDot" style="background:${item.color}"></span>
            ${item.illness}
            <b>${item.value}%</b>
        </div>
    `).join("");
}

function renderAppointmentChart() {
    const chart = document.getElementById("appointmentChart");

    const width = 420;
    const height = 240;
    const paddingLeft = 42;
    const paddingBottom = 42;
    const topPadding = 25;
    const chartWidth = width - paddingLeft - 25;
    const chartHeight = height - paddingBottom - topPadding;

    const maxValue = Math.max(...monthlyAppointments.map(item => item.total));
    const minValue = 0;

    const points = monthlyAppointments.map((item, index) => {
        const x = paddingLeft + (index * chartWidth / (monthlyAppointments.length - 1));
        const y = topPadding + chartHeight - ((item.total - minValue) / (maxValue - minValue)) * chartHeight;
        return { x, y, ...item };
    });

    const pointString = points.map(point => `${point.x},${point.y}`).join(" ");

    chart.innerHTML = `
        <svg viewBox="0 0 ${width} ${height}">
            <line x1="${paddingLeft}" y1="${topPadding}" x2="${paddingLeft}" y2="${height - paddingBottom}" class="drAxis"/>
            <line x1="${paddingLeft}" y1="${height - paddingBottom}" x2="${width - 20}" y2="${height - paddingBottom}" class="drAxis"/>

            <line x1="${paddingLeft}" y1="55" x2="${width - 20}" y2="55" class="drGridLine"/>
            <line x1="${paddingLeft}" y1="100" x2="${width - 20}" y2="100" class="drGridLine"/>
            <line x1="${paddingLeft}" y1="145" x2="${width - 20}" y2="145" class="drGridLine"/>

            <polyline points="${pointString}" class="drLinePath"/>

            ${points.map(point => `
                <circle cx="${point.x}" cy="${point.y}" r="6" class="drLinePoint"/>
                <text x="${point.x - 12}" y="${height - 14}" class="drLineText">${point.month}</text>
            `).join("")}
        </svg>
    `;
}

function renderMedicineUsageChart() {
    const chart = document.getElementById("medicineUsageChart");
    const maxUsage = Math.max(...doctorMedicines.map(med => med.usage));

    chart.innerHTML = doctorMedicines.map(med => {
        const percentage = (med.usage / maxUsage) * 100;

        return `
            <div class="drBarRow">
                <span class="drBarLabel">${med.name}</span>
                <div class="drBarTrack">
                    <div class="drBarFill" style="width:${percentage}%"></div>
                </div>
                <span class="drBarValue">${med.usage}</span>
            </div>
        `;
    }).join("");
}

function renderMedicineTable() {
    const tbody = document.getElementById("medicineTableBody");

    tbody.innerHTML = doctorMedicines.map(med => {
        const statusClass = getMedicineStatusClass(med.status);

        return `
            <tr>
                <td>${med.name}</td>
                <td>${med.stock}</td>
                <td><span class="drBadge ${statusClass}">${med.status}</span></td>
                <td>${med.usage}</td>
            </tr>
        `;
    }).join("");
}

function renderPatientTable(patientList) {
    const tbody = document.getElementById("patientTableBody");

    if (patientList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align:center; color:#526882;">No patient record found</td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = patientList.map(patient => {
        const statusClass = patient.status.toLowerCase();

        return `
            <tr>
                <td>${patient.name}</td>
                <td>${patient.illness}</td>
                <td><span class="drBadge ${statusClass}">${patient.status}</span></td>
                <td>${patient.date}</td>
            </tr>
        `;
    }).join("");
}

function filterPatients() {
    const keyword = document.getElementById("patientSearchInput").value.toLowerCase();
    const status = document.getElementById("statusFilter").value;

    const filteredPatients = doctorPatients.filter(patient => {
        const matchKeyword =
            patient.name.toLowerCase().includes(keyword) ||
            patient.illness.toLowerCase().includes(keyword);

        const matchStatus = status === "All" || patient.status === status;

        return matchKeyword && matchStatus;
    });

    renderPatientTable(filteredPatients);
}

function getMedicineStatusClass(status) {
    if (status === "Available") return "available";
    if (status === "Low Stock") return "low";
    if (status === "Medium") return "medium";
    return "";
}