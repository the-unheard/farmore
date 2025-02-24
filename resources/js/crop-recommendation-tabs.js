document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll("#tab-buttons button");
    const contents = document.querySelectorAll(".tab-content");

    if (tabs.length > 0) {
        // Set the first tab as active on load
        tabs[0].classList.add("text-grad-blue");

        // Show the first tab content by default
        contents[0].classList.remove("hidden");
    }

    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
        const target = this.getAttribute("data-tab");

        // Hide all content
        contents.forEach(content => {
            content.classList.add("hidden");
        });

            // Show the selected tab content
            document.querySelector(`[data-tab-content="${target}"]`).classList.remove("hidden");

            // Remove active state from all tabs
            tabs.forEach(t => t.classList.remove("text-grad-blue"));

            // Add active state to clicked tab
            this.classList.add("text-grad-blue");
        });
    });
});
