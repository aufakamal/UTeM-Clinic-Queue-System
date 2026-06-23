let profileBtn = document.querySelector("#profileBtn");
let profileDropdown = document.querySelector("#profileDropdown");

if (profileBtn && profileDropdown) {
    profileBtn.addEventListener("click", function (event) {
        event.stopPropagation();
        profileDropdown.classList.toggle("showDropdown");
    });

    document.addEventListener("click", function () {
        profileDropdown.classList.remove("showDropdown");
    });
}