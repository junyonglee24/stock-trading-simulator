document.addEventListener("DOMContentLoaded", function () {
    const inputsToValidate = [
        { id: "username", regex: /^[A-Za-z0-9_]{3,15}$/, error: "Username must be 3-15 characters, and can only include letters, numbers, and underscores." },
        { id: "firstname", regex: /^[A-Za-z\s]+$/, error: "First name should contain only letters and spaces." },
        { id: "lastname", regex: /^[A-Za-z\s]+$/, error: "Last name should contain only letters and spaces." },
        { id: "email", regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, error: "Please enter a valid email address." },
        { id: "password", regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, error: "Password must be at least 8 characters, with one uppercase letter, one lowercase letter, one number, and one special character." },
    ];

    inputsToValidate.forEach(input => {
        const field = document.getElementById(input.id);
        field.addEventListener("input", () => field.setCustomValidity("")); 
    });

    document.getElementById("confirm-password").addEventListener("input", () => {
        document.getElementById("confirm-password").setCustomValidity("");
    });
});

function validateForm() {
    let isValid = true;

    const usernameInput = document.getElementById("username");
    const usernameRegex = /^[A-Za-z0-9_]{3,15}$/;
    if (!usernameRegex.test(usernameInput.value.trim())) {
        usernameInput.setCustomValidity("Username must be 3-15 characters, and can only include letters, numbers, and underscores.");
        isValid = false;
    } else {
        usernameInput.setCustomValidity("");
    }

    const firstNameInput = document.getElementById("firstname");
    const nameRegex = /^[A-Za-z\s]+$/;
    if (!nameRegex.test(firstNameInput.value.trim())) {
        firstNameInput.setCustomValidity("First name should contain only letters.");
        isValid = false;
    } else {
        firstNameInput.setCustomValidity("");
    }

    const lastNameInput = document.getElementById("lastname");
    if (!nameRegex.test(lastNameInput.value.trim())) {
        lastNameInput.setCustomValidity("Last name should contain only letters.");
        isValid = false;
    } else {
        lastNameInput.setCustomValidity("");
    }

    const emailInput = document.getElementById("email");
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailInput.value.trim())) {
        emailInput.setCustomValidity("Please enter a valid email address.");
        isValid = false;
    } else {
        emailInput.setCustomValidity("");
    }

    const passwordInput = document.getElementById("password");
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(passwordInput.value)) {
        passwordInput.setCustomValidity("Password must be at least 8 characters, with one uppercase letter, one lowercase letter, one number, and one special character.");
        isValid = false;
    } else {
        passwordInput.setCustomValidity("");
    }

    const confirmPasswordInput = document.getElementById("confirm-password");
    if (confirmPasswordInput.value !== passwordInput.value) {
        confirmPasswordInput.setCustomValidity("Passwords do not match.");
        isValid = false;
    } else {
        confirmPasswordInput.setCustomValidity("");
    }

    if (!isValid) {
        document.querySelector("form").reportValidity(); 
        return false; 
    }

    return true; 
}
