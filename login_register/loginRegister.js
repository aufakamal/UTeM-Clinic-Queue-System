//login validation//
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

        // jangan redirect dekat JS
        // biar login.php check ID, role, password
    });
}


//register validation//
let registerForm = document.querySelector("#registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function(event) {

        let fullName = document.querySelector("#fullName").value.trim();
        let gender = document.querySelector("#gender").value;
        let id = document.querySelector("#registerId").value.trim();
        let dateOfBirth = document.querySelector("#dateOfBirth").value;
        let email = document.querySelector("#email").value.trim();
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

        // kalau semua validation betul, form akan submit ke action="medicalCondition.php"
    });
}


//medical condition//
let medicalForm = document.querySelector("#medicalForm");

if (medicalForm) {
    medicalForm.addEventListener("submit", function(event) {
        event.preventDefault();

        alert("Medical information saved successfully.");
        window.location.href = "login.php";
    });
}


//forgot password//
let forgotForm = document.querySelector("#forgotForm");

if (forgotForm) {
    forgotForm.addEventListener("submit", function(event) {
        event.preventDefault();

        let forgotEmail = document.querySelector("#forgotEmail").value.trim();

        if (forgotEmail === "") {
            alert("Please enter your email address.");
            return;
        }

        if (!forgotEmail.includes("@")) {
            alert("Please enter a valid email address.");
            return;
        }

        alert("Password reset link has been sent.");
        window.location.href = "resetPassword.php";
    });
}


//reset pass//
let resetForm = document.querySelector("#resetForm");

if (resetForm) {
    resetForm.addEventListener("submit", function(event) {
        event.preventDefault();

        let newPassword = document.querySelector("#newPassword").value.trim();
        let confirmNewPassword = document.querySelector("#confirmNewPassword").value.trim();

        if (newPassword === "" || confirmNewPassword === "") {
            alert("Please fill in all password fields.");
            return;
        }

        if (newPassword.length < 6) {
            alert("Password must be at least 6 characters.");
            return;
        }

        if (newPassword !== confirmNewPassword) {
            alert("New password and confirm password do not match.");
            return;
        }

        alert("Password reset successful.");
        window.location.href = "resetSuccess.php";
    });
}