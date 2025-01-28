import { populateEstimates } from './crop-populate-estimates.js';

document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('crop');
    const dropdown = document.getElementById('crop-dropdown');
    const listItems = document.getElementById('crop-list').getElementsByTagName('li');
    const plotDropdown = document.getElementById('plot_id');

    // Populate on page load
    populateEstimates(selectedCrop, plotDropdown.value);

    // Toggle dropdown visibility when clicking the input box
    input.addEventListener('click', function () {
        dropdown.classList.toggle('hidden');
    });

    // Filter crops
    input.addEventListener('keyup', function () {
        const filter = input.value.toLowerCase();

        for (let i = 0; i < listItems.length; i++) {
            let item = listItems[i];
            const cropData = item.getAttribute('data-crop');

            if (cropData.includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!input.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Attach click event to list items to select the crop and update estimates
    for (let i = 0; i < listItems.length; i++) {
        listItems[i].addEventListener('click', function () {
            const cropName = listItems[i].querySelector('.font-semibold').innerText;
            const plotId = document.getElementById('plot_id').value;
            input.value = cropName;

            populateEstimates(cropName, plotId);
        });
    }

    // Close dropdown when clicking outside
    plotDropdown.addEventListener('change', function (event) {
        const cropName = input.value;
        const plotId = event.target.value;

        if (cropName) {
            populateEstimates(cropName, plotId);
        }
    });
});
