document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="repassword"]');
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        // Regular Expression for Strong Password
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.");
            event.preventDefault(); // Stop form submission
            return;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            event.preventDefault(); // Stop form submission
        }
    });
});
