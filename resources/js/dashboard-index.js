// default soilData and cropYieldData to an empty object if they are undefined
const soilDataObject = soilData || {};
const cropYieldDataObject = cropYieldData || {};
const topContributorsObject = topContributors || {};
const mostPlantedObject = mostPlantedData || {};

// top contributors data
const usernameTop = topContributorsObject.username || [];
const pinCountTop = topContributorsObject.pinCount || [];

// soil data
const nitrogenSoil = soilDataObject.nitrogen || [];
const phosphorusSoil = soilDataObject.phosphorus || [];
const potassiumSoil = soilDataObject.potassium || [];
const temperatureSoil = soilDataObject.temperature || [];
const humiditySoil = soilDataObject.humidity || [];
const phSoil = soilDataObject.ph || [];
const recordSoil = soilDataObject.recordDates || [];

// crop yield data
const idYieldChart = cropYieldDataObject.id || [];
const cropYieldChart = cropYieldDataObject.crop || [];
const yieldYieldChart = cropYieldDataObject.cropYield || [];
const recordYieldChart = cropYieldDataObject.recordDates || [];

// most planted crops data
const cropMost = mostPlantedObject.crop || [];
const cropCountMost = mostPlantedObject.crop_count || [];

// map cropYieldChart and yieldYieldChart into the required format for ApexCharts
const chartData = cropYieldChart.map((crop, index) => ({
    x: crop,
    y: yieldYieldChart[index] || 0,
    date: recordYieldChart[index] || 'N/A',
    id: idYieldChart[index] || null,
}));

// top contributors options
const optionsTopContributors = {
    plotOptions: {
        bar: {
            horizontal: true, // Make the bar horizontal
            columnWidth: "50%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    series: [{
        name: 'Pins',
        data: pinCountTop
    }],
    chart: {
        height: 210,
        type: "bar",
        toolbar: { show: false },
    },
    xaxis: {
        categories: usernameTop, // Categories remain but will not be shown
        labels: { show: true }  // Hide the x-axis (horizontal bar) labels
    },
    yaxis: {
        labels: { show: true }  // Hide the y-axis labels (usernames)
    },
    dataLabels: { enabled: false },
    legend: {
        show: true,
        position: 'top', // Place the legend above the chart
        horizontalAlign: 'center', // Align the legend in the center
        markers: {
            width: 12, // Control the size of the color boxes
            height: 12,
            radius: 4,
        },
        itemMargin: {
            horizontal: 10,
            vertical: 5,
        }
    },
    grid: { strokeDashArray: 4 },
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


// soil chart options
const optionsSoil = {
    series: [
        { name: "Nitrogen", data: nitrogenSoil, color: "#4776E6" },
        { name: "Phosphorus", data: phosphorusSoil, color: "#8E54E9" },
        { name: "Potassium", data: potassiumSoil, color: "#E9724C" },
        { name: "Temperature", data: temperatureSoil, color: "#70877F" },
        { name: "Humidity", data: humiditySoil, color: "#E05263" },
        { name: "pH Level", data: phSoil, color: "#885053" },
    ],
    xaxis: {
        categories: recordSoil,
        tooltip: { enabled: false },
        axisTicks: { show: false },
    },
    chart: { type: "area", height: 250, },
    grid: { strokeDashArray: 4, },
    legend: { position: 'top' },
    dataLabels: { enabled: false },
};

// yield chart options
const optionsYield = {
    plotOptions: {
        bar: {
            columnWidth: "50%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    series: [{ data: yieldYieldChart }],
    chart: {
        type: "bar",
        height: 250,
        events: {
            click: (event, chartContext, config) => {
                const clickedId = chartData[config.dataPointIndex].id;
                window.location.href = `/crop-yield/${clickedId}`;
            },
        },
    },
    xaxis: { categories: cropYieldChart, },
    grid: { strokeDashArray: 4, },
    dataLabels: { enabled: false },
    legend: { show: false },
    tooltip: {
        intersect: false,
        x: {
            formatter: (value, {dataPointIndex}) => {
                const date = chartData[dataPointIndex].date;
                return value + '<br/><b>' + date + '</b>';
            },
        },
        y: { title: { formatter: function(value) { return 'Performance';  } } } // formats the tooltip text
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

// most frequently planted
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
        height: 610,
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

// render function
const renderChart = (elementId, options) => {
    if (document.getElementById(elementId) && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById(elementId), options);
        chart.render();
    }
};

// render the charts
renderChart("top-contributors-chart", optionsTopContributors);
renderChart("dashboard-soil-chart", optionsSoil);
renderChart("dashboard-yield-chart", optionsYield);
renderChart("most-planted-chart", optionsMostPlanted);
