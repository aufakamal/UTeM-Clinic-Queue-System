/* =========================
   DOCTOR DASHBOARD & CONSULTATION
========================= */

// Elemen untuk Tab Perundingan
const overviewTab = document.querySelector("#overviewTab");
const visitsTab = document.querySelector("#visitsTab");
const diagnosisTab = document.querySelector("#diagnosisTab");
const prescriptionTab = document.querySelector("#prescriptionTab");

// Elemen untuk Kandungan (Content Sections)
const overviewContent = document.querySelector("#overviewContent");
const visitsContent = document.querySelector("#visitsContent");
const diagnosisContent = document.querySelector("#diagnosisContent");
const prescriptionContent = document.querySelector("#prescriptionContent");

// Fungsi untuk menukar Tab
function switchTab(selectedTab, selectedContent) {
    // 1. Sembunyikan semua content
    [overviewContent, visitsContent, diagnosisContent, prescriptionContent].forEach(content => {
        if(content) content.classList.add("hidden");
    });

    // 2. Buang class 'active' dari semua tab
    [overviewTab, visitsTab, diagnosisTab, prescriptionTab].forEach(tab => {
        if(tab) tab.classList.remove("activeTab");
    });

    // 3. Paparkan content terpilih & aktifkan tab
    if(selectedContent) selectedContent.classList.remove("hidden");
    if(selectedTab) selectedTab.classList.add("activeTab");
}

// Event Listeners untuk Tab
if (overviewTab) overviewTab.addEventListener("click", () => switchTab(overviewTab, overviewContent));
if (visitsTab) visitsTab.addEventListener("click", () => switchTab(visitsTab, visitsContent));
if (diagnosisTab) diagnosisTab.addEventListener("click", () => switchTab(diagnosisTab, diagnosisContent));
if (prescriptionTab) prescriptionTab.addEventListener("click", () => switchTab(prescriptionTab, prescriptionContent));


/* =========================
   ACTION BUTTONS (SAVE/COMPLETE)
========================= */
const saveDraftBtn = document.querySelector("#saveDraftBtn");
const completeBtn = document.querySelector("#completeBtn");

if (saveDraftBtn) {
    saveDraftBtn.addEventListener("click", () => {
        // Logik untuk menghantar data ke database (Draft mode)
        alert("Consultation saved as draft!");
    });
}

if (completeBtn) {
    completeBtn.addEventListener("click", () => {
        // Logik untuk menghantar data ke database (Finalized)
        // Disyorkan panggil fungsi fetch di sini
        alert("Consultation completed and updated in system.");
    });
}