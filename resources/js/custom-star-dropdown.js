document.addEventListener('DOMContentLoaded', function () {
    const ratingFilter = document.getElementById('rating-filter'); // Container
    const ratingOptions = document.getElementById('rating-options'); // Dropdown
    const ratingFilterSelected = document.getElementById('rating-filter-selected'); // Container that displays selected option

    // Toggles to show/hide the dropdown
    ratingFilter.addEventListener('click', function (event) {
        ratingOptions.classList.toggle('hidden');
    });

    // Adds event listener for each <li> element
    ratingOptions.querySelectorAll('li').forEach(option => {
        option.addEventListener('click', function (event) {
            event.stopPropagation();
            const selectedRating = parseFloat(option.getAttribute('data-value'));
            ratingFilterSelected.innerHTML = option.innerHTML.trim();
            ratingOptions.classList.add('hidden');
            document.getElementById('rating-filter-selected').setAttribute('data-value', selectedRating);
        });
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!ratingFilter.contains(event.target)) {
            ratingOptions.classList.add('hidden');
        }
    });
});
