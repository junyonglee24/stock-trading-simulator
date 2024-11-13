function validateForm() {
    let isValid = true;

    const email = document.getElementById("login-email");
    const emailRegex = /^[\w.-]+@[A-Za-z\d.-]+\.[A-Za-z]{2,6}$/;
    if (!emailRegex.test(email.value)) {
        email.setCustomValidity("Please enter a valid email. (e.g. example@gmail.com)");
        email.reportValidity();
        isValid = false;
    }


    return isValid;
}