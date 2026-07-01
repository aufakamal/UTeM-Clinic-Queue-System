const stockPopup = document.getElementById("stockPopup");
const popupMedicineID = document.getElementById("popupMedicineID");
const popupMedicineName = document.getElementById("popupMedicineName");
const popupCurrentStock = document.getElementById("popupCurrentStock");
const stockQuantityInput = document.getElementById("stockQuantityInput");
const newStockPreview = document.getElementById("newStockPreview");
const closeStockBtn = document.querySelector(".closeStockBtn");

let currentStock = 0;

if (stockPopup) {
    document.querySelectorAll(".updateStockBtn").forEach((button) => {
        button.addEventListener("click", () => {
            popupMedicineID.value = button.dataset.id;
            popupMedicineName.textContent = button.dataset.name;

            currentStock = parseInt(button.dataset.stock);

            popupCurrentStock.textContent = currentStock + " units";
            newStockPreview.textContent = currentStock + " units";
            stockQuantityInput.value = "";

            document.querySelector("input[name='stockAction'][value='add']").checked = true;

            stockPopup.style.display = "flex";
        });
    });
}

function updateStockPreview() {
    const quantity = parseInt(stockQuantityInput.value) || 0;
    const action = document.querySelector("input[name='stockAction']:checked").value;

    let newStock = currentStock;

    if (action === "add") {
        newStock = currentStock + quantity;
    }
    else {
        newStock = currentStock - quantity;

        if (newStock < 0) {
            newStock = 0;
        }
    }

    newStockPreview.textContent = newStock + " units";
}

stockQuantityInput.addEventListener("input", updateStockPreview);

document.querySelectorAll("input[name='stockAction']").forEach((radio) => {
    radio.addEventListener("change", updateStockPreview);
});

if (closeStockBtn) {
    closeStockBtn.addEventListener("click", () => {
        stockPopup.style.display = "none";
    });
}

const addMedicineBtn = document.getElementById("addMedicineBtn");
const addMedicinePopup = document.getElementById("addMedicinePopup");
const closeAddMedicineBtn = document.querySelector(".closeAddMedicineBtn");

if (addMedicineBtn) {
    addMedicineBtn.addEventListener("click", () => {
        addMedicinePopup.style.display = "flex";
    });
}

if (closeAddMedicineBtn) {
    closeAddMedicineBtn.addEventListener("click", () => {
        addMedicinePopup.style.display = "none";
    });
}

function searchMedicineLive() {
    const input = document.getElementById("medicineSearchInput");
    const rows = document.querySelectorAll(".medicineRow");

    if (!input || rows.length === 0) {
        return;
    }

    const keyword = input.value.toLowerCase().trim();

    rows.forEach((row) => {
        const text = row.dataset.search;

        if (text.includes(keyword)) {
            row.style.display = "";
        }
        else {
            row.style.display = "none";
        }
    });
}