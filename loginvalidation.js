function validateLoginForm() {
    const loginEmail = document.forms[0]['email'].value.trim();
    const loginPassword = document.forms[0]['password'].value;

    const loginEmailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;
    const strongPasswordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

    if (!loginEmailPattern.test(loginEmail)) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (!strongPasswordPattern.test(loginPassword)) {
        alert("Please enter a valid password.");
        return false;
    }

    return true;
}
