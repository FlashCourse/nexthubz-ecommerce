import "./bootstrap";

// Theme loader
(function () {
    const savedTheme = localStorage.getItem("theme") || "default";
    document.documentElement.setAttribute("data-theme", savedTheme);
    document.documentElement.style.visibility = "visible";
})();
