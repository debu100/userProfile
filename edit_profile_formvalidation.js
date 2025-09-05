document.getElementById("editProfileForm").addEventListener("submit", function(e) {
    const errors = [];

    const name = document.getElementById("editusername").value.trim();
    const email = document.getElementById("editemail").value.trim();
    const password = document.getElementById("editpassword").value;

    const nameRegex = /^[a-zA-Z\s]{3,}$/;
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$&*]).{8,}$/;

    if (!nameRegex.test(name)) {
        errors.push("Username must contain only letters and spaces, and be at least 3 characters.");
    }

    if (!emailRegex.test(email)) {
        errors.push("Invalid email format.");
    }

    if (password.length > 0 && !passwordRegex.test(password)) {
        errors.push("Password must be at least 8 characters, include uppercase, lowercase, digit, and special character (!@#$&*).");
    }

    if (errors.length > 0) {
        e.preventDefault();

        const errorBox = document.createElement("div");
        errorBox.style.color = "red";
        errorBox.style.margin = "1rem 0";
        errorBox.style.padding = "10px";
        errorBox.style.border = "1px solid red";
        errorBox.style.background = "#ffecec";

        const list = document.createElement("ul");
        errors.forEach(error => {
            const li = document.createElement("li");
            li.textContent = error;
            list.appendChild(li);
        });

        errorBox.appendChild(list);

        const existing = document.querySelector("#editProfileForm + div");
        if (existing) existing.remove();

        document.getElementById("editProfileForm").insertAdjacentElement("afterend", errorBox);
    }
});