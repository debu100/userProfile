function validateSignupForm() {
const name = document.forms[0]['user_name'].value.trim();
const email = document.forms[0]['user_email'].value.trim();
const password = document.forms[0]['user_password'].value;

// Name must only contain letters and spaces (no numbers or special characters)
const namePattern = /^[A-Za-z\s]{3,}$/;
if (!namePattern.test(name)) {
    alert("Name should only contain letters and be at least 3 characters long.");
    return false;
}

// Email validation
const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;
if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    return false;
}

// Password must have at least:
// 1 lowercase, 1 uppercase, 1 number, 1 special character, and be at least 6 characters
const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;
if (!passwordPattern.test(password)) {
    alert("Password must be at least 6 characters and include uppercase, lowercase, number, and special character.");
    return false;
}

return true;
}
