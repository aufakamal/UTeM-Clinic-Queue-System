/* =========================
   APPOINTMENT RECORD PAGE
========================= */
let upcomingTab = document.querySelector("#upcomingTab");
let previousTab = document.querySelector("#previousTab");

let upcomingRecords = document.querySelector("#upcomingRecords");
let previousRecords = document.querySelector("#previousRecords");

if (upcomingTab && previousTab && upcomingRecords && previousRecords) {
    upcomingTab.addEventListener("click", function() {
        upcomingTab.classList.add("activeTab");
        previousTab.classList.remove("activeTab");

        upcomingRecords.classList.remove("hidden");
        previousRecords.classList.add("hidden");
    });

    previousTab.addEventListener("click", function() {

        previousTab.classList.add("activeTab");
        upcomingTab.classList.remove("activeTab");

        previousRecords.classList.remove("hidden");
        upcomingRecords.classList.add("hidden");
    });
}