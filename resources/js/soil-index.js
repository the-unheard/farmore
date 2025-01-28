// If soilData is undefined, assign an empty object as a fallback
const data = soilData || {};

// Access the soil data directly
const nitrogenData = data.nitrogen || [];
const phosphorusData = data.phosphorus || [];
const potassiumData = data.potassium || [];
const temperatureData = data.temperature || [];
const humidityData = data.humidity || [];
const phData = data.ph || [];
const recordDates = data.recordDates || [];

// soil chart options
const options = {
    series: [
        { name: "Nitrogen", data: nitrogenData, color: "#4776E6" },
        { name: "Phosphorus", data: phosphorusData, color: "#8E54E9" },
        { name: "Potassium", data: potassiumData, color: "#E9724C" },
        { name: "Temperature", data: temperatureData, color: "#70877F" },
        { name: "Humidity", data: humidityData, color: "#E05263" },
        { name: "pH Level", data: phData, color: "#885053" },
    ],
    xaxis: {
        categories: recordDates,
        tooltip: { enabled: false },
        axisTicks: { show: false },
    },
    chart: { type: "area", height: 450, },
    grid: { strokeDashArray: 4, },
    legend: { position: 'top' },
    dataLabels: { enabled: false },
};

if (document.getElementById("legend-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("legend-chart"), options);
    chart.render();
}

// toggling between nutrient trend analyses
document.addEventListener('DOMContentLoaded', function() {
    const nutrients = ['nitrogen', 'phosphorus', 'potassium', 'temperature', 'humidity', 'ph'];
    let currentNutrientIndex = 0;

    const prevButton = document.getElementById('prevNutrient');
    const nextButton = document.getElementById('nextNutrient');

    // hide all nutrient sections
    function hideAllNutrients() {
        nutrients.forEach(nutrient => {
            document.getElementById(`nutrient-${nutrient}`).classList.add('hidden');
        });
    }

    // show the current nutrient section
    function showCurrentNutrient() {
        const currentNutrient = nutrients[currentNutrientIndex];
        document.getElementById(`nutrient-${currentNutrient}`).classList.remove('hidden');
    }

    // initialize by showing the first nutrient
    hideAllNutrients();
    showCurrentNutrient();

    // event listeners for the buttons
    prevButton.addEventListener('click', function() {
        currentNutrientIndex = (currentNutrientIndex === 0) ? nutrients.length - 1 : currentNutrientIndex - 1;
        hideAllNutrients();
        showCurrentNutrient();
    });

    nextButton.addEventListener('click', function() {
        currentNutrientIndex = (currentNutrientIndex === nutrients.length - 1) ? 0 : currentNutrientIndex + 1;
        hideAllNutrients();
        showCurrentNutrient();
    });
});
