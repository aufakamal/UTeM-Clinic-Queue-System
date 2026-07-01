// ==============================
// LOGIN VALIDATION
// ==============================

let loginForm = document.querySelector("#loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function(event) {

        let id = document.querySelector("#loginId").value.trim();
        let password = document.querySelector("#loginPassword").value.trim();
        let role = document.querySelector("#loginRole").value;

        if (id === "" || password === "" || role === "") {
            event.preventDefault();
            alert("Please fill in all fields before login.");
            return;
        }
    });
}



// ==============================
// REGISTER VALIDATION
// ==============================

let registerForm = document.querySelector("#registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function(event) {

        let fullName = document.querySelector("#fullName").value.trim();
        let gender = document.querySelector("#gender").value;
        let id = document.querySelector("#registerId").value.trim();
        let dateOfBirth = document.querySelector("#dateOfBirth").value.trim();
        let email = document.querySelector("#email").value.trim().toLowerCase();
        let phone = document.querySelector("#phone").value.trim();
        let address = document.querySelector("#address").value.trim();
        let password = document.querySelector("#password").value.trim();
        let confirmPassword = document.querySelector("#confirmPassword").value.trim();
        let registerRole = document.querySelector("#registerRole").value;

        if (
            fullName === "" ||
            gender === "" ||
            id === "" ||
            dateOfBirth === "" ||
            email === "" ||
            phone === "" ||
            address === "" ||
            password === "" ||
            confirmPassword === "" ||
            registerRole === ""
        ) {
            event.preventDefault();
            alert("Please fill in all required fields.");
            return;
        }

        if (!email.includes("@")) {
            event.preventDefault();
            alert("Please enter a valid email address.");
            return;
        }

        if (registerRole === "student") {
            if (!email.endsWith("@student.utem.edu.my")) {
                event.preventDefault();
                alert("Students must use UTeM student email ending with @student.utem.edu.my.");
                return;
            }
        }

        if (registerRole === "staff") {
            if (!email.endsWith("@utem.edu.my") || email.endsWith("@student.utem.edu.my")) {
                event.preventDefault();
                alert("Staff must use UTeM staff email ending with @utem.edu.my.");
                return;
            }
        }

        if (phone.length < 10) {
            event.preventDefault();
            alert("Phone number must be at least 10 digits.");
            return;
        }

        if (!/^[0-9]+$/.test(phone)) {
            event.preventDefault();
            alert("Phone number must contain digits only.");
            return;
        }

        if (password.length < 6) {
            event.preventDefault();
            alert("Password must be at least 6 characters.");
            return;
        }

        if (password !== confirmPassword) {
            event.preventDefault();
            alert("Password and Confirm Password do not match.");
            return;
        }
    });
}



