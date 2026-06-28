//profile button and dropdown//
let profileBtn = document.querySelector("#profileBtn");
let profileDropdown = document.querySelector("#profileDropdown");

if (profileBtn && profileDropdown) {
    profileBtn.addEventListener("click", function() {
        profileDropdown.classList.toggle("showDropdown");
    });
}