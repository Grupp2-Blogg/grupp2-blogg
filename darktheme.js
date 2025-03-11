document.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme");

    if (savedTheme) {
        document.querySelector(`input[name="scheme"][value="${savedTheme}"]`).checked = true;
        document.documentElement.setAttribute("data-theme", savedTheme);
    }

    document.querySelectorAll('input[name="scheme"]').forEach((input) => {
        input.addEventListener("change", (event) => {
            const selectedTheme = event.target.value;
            localStorage.setItem("theme", selectedTheme);
            document.documentElement.setAttribute("data-theme", selectedTheme);
        });
    });
});