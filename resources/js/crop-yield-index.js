// If cropYieldData is undefined, assign an empty object as a fallback
const cropData = cropYieldData || {};
const mostPlantedObject = mostPlantedData || {};

// Most planted crops data
const cropMost = mostPlantedObject.crop || [];
const cropCountMost = mostPlantedObject.crop_count || [];

// Access the crop yield data directly
const idData = cropData.id || [];
const cropNameData = cropData.crop || [];
const performanceData = cropData.performance || []; // ✅ Use Performance Instead of Yield
const harvestDate = cropData.harvestDates || [];

// Map cropNameData and performanceData into the required format for ApexCharts
const chartData = cropNameData.map((crop, index) => ({
    x: crop,
    y: performanceData[index] || 0, // If performanceData is shorter, default to 0 for missing values
    date: harvestDate[index],
    id: idData[index]
}));

const optionsColumns = {
    plotOptions: {
        bar: {
            columnWidth: "50%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    series: [{
        name: "Performance (%)", // ✅ Updated to show performance percentage
        data: performanceData
    }],
    chart: {
        type: "bar",
        height: 250,
        events: {
            click: function(event, chartContext, config) {
                const clickedId = chartData[config.dataPointIndex].id;
                window.location.href = `/crop-yield/${clickedId}`;
            }
        },
    },
    xaxis: { categories: cropNameData, },
    grid: { strokeDashArray: 4, },
    dataLabels: { enabled: false },
    legend: { show: false },
    tooltip: {
        intersect: false,
        x: {
            formatter: function(value, {dataPointIndex}) {
                const date = chartData[dataPointIndex].date; // Get the date from the chartData
                return value + '<br/><b>' + date + '</b>'; // Format the tooltip text
            }
        },
        y: {
            formatter: function(value) {
                return value.toFixed(2) + "%"; // ✅ Show Performance as a Percentage
            },
            title: { formatter: function() { return 'Performance'; } }
        }
    },
    colors: ['#8E54E9'],
    fill: {
        type: 'gradient',
        gradient: {
            type: 'horizontal',
            gradientToColors: ['#4776E6'],
            stops: [0, 100]
        }
    },

};

// Most frequently planted
const optionsMostPlanted = {
    colors: [
        "#4776E6",
        "#8E54E9",
        "#E9724C",
        "#70877F",
        "#E05263"
    ],
    series: cropCountMost,
    chart: {
        height: 400,
        type: "donut",
    },
    labels: cropMost,
    legend: {
        position: "bottom",
        formatter: function (seriesName, opts) {
            // Custom legend formatter
            const percentage = parseFloat(opts.w.globals.seriesPercent[opts.seriesIndex]) || 0; // Convert to number safely
            const count = opts.w.globals.series[opts.seriesIndex]; // Get count (number of times planted)
            const sliceColor = opts.w.globals.colors[opts.seriesIndex]; // Get the color of the slice

            return `
            <span class="most-planted-crop text-left ml-1 min-w-[100px]">${seriesName}</span>
            <span class="most-planted-count text-center">${count}</span>
            <span class="most-planted-percentage text-right">${percentage.toFixed(1)}%</span>
            <div class="progress-bar-container relative w-20 h-3 bg-gray-200 rounded-sm overflow-hidden inline-block ml-14 top-0.5">
                <div class="progress-bar h-full rounded-sm" style="width: ${percentage}%; background-color: ${sliceColor};"></div>
            </div>
        `;
        },
        itemMargin: {
            horizontal: 10,
            vertical: 5
        },
    },
    tooltip: { fillSeriesColor: false, }
}

// Render function
const renderChart = (elementId, options) => {
    if (document.getElementById(elementId) && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById(elementId), options);
        chart.render();
    }
};

renderChart("column-chart", optionsColumns);
renderChart("most-planted-chart", optionsMostPlanted);
