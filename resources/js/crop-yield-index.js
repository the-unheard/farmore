// If cropYieldData is undefined, assign an empty object as a fallback
const cropData = cropYieldData || {};

// Access the crop yield data directly
const idData = cropData.id || [];
const cropNameData = cropData.crop || [];
const yieldData = cropData.cropYield || [];
const harvestDate = cropData.harvestDates || [];

// Map cropData and yieldData into the required format for ApexCharts
const chartData = cropNameData.map((crop, index) => ({
    x: crop,
    y: yieldData[index] || 0, // If yieldData is shorter, default to 0 for missing values
    date: harvestDate[index],
    id: idData[index]
}));

const options = {
    plotOptions: {
        bar: {
            columnWidth: "50%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    series: [{ data: yieldData }],
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
        y: { title: { formatter: function(value) { return '';  } } } // formats the tooltip text
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


if(document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("column-chart"), options);
    chart.render();
}
