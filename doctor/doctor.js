// This is relatable to your bookAppointment.js pattern
const pendingTab = document.querySelector("#pendingTab");
const processedTab = document.querySelector("#processedTab");
const pendingRecords = document.querySelector("#pendingRecords");
const processedRecords = document.querySelector("#processedRecords");

if (pendingTab && processedTab) {
    pendingTab.addEventListener("click", () => {
        pendingTab.classList.add("activeTab");
        processedTab.classList.remove("activeTab");
        
        pendingRecords.classList.remove("hidden");
        processedRecords.classList.add("hidden");
    });

    processedTab.addEventListener("click", () => {
        processedTab.classList.add("activeTab");
        pendingTab.classList.remove("activeTab");
        
        processedRecords.classList.remove("hidden");
        pendingRecords.classList.add("hidden");
    });
}