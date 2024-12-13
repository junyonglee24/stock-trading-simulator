//form validation
function validateForm() {
    const firstNameInput = document.getElementById("firstname");
    const lastNameInput = document.getElementById("lastname");
    const emailInput = document.getElementById("email");

    const firstName = firstNameInput.value.trim();
    const lastName = lastNameInput.value.trim();
    const email = emailInput.value.trim();

    const namePattern = /^[A-Za-z]+$/;

    firstNameInput.setCustomValidity("");
    lastNameInput.setCustomValidity("");
    emailInput.setCustomValidity("");

    if (firstName === "" || !namePattern.test(firstName)) {
        firstNameInput.setCustomValidity("First name cannot be empty and must contain only letters.");
        firstNameInput.reportValidity();
        setTimeout(() => firstNameInput.setCustomValidity(""), 1000);
        return false;
    }

    if (lastName === "" || !namePattern.test(lastName)) {
        lastNameInput.setCustomValidity("Last name cannot be empty and must contain only letters.");
        lastNameInput.reportValidity();
        setTimeout(() => lastNameInput.setCustomValidity(""), 1000);
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        emailInput.setCustomValidity("Please enter a valid email address.");
        emailInput.reportValidity();
        setTimeout(() => emailInput.setCustomValidity(""), 1000);
        return false;
    }

    return true;
}


//enable edit
function enableEdit() {
    const inputs = document.querySelectorAll('.settings-section input');
    inputs.forEach(input => input.disabled = false); 
    document.querySelector('.submit-button').style.display = 'block';
    document.querySelector('.edit-button').style.display = 'none';
}
