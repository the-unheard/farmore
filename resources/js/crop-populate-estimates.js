export function populateEstimates(cropName, plotId) {
    const plantTitle = document.getElementById('yield-predict-title');
    const plantSoilTypeSpan = document.getElementById('yield-predict-soil-type');
    const plantMonthSpan = document.getElementById('yield-predict-month');
    const plantSeedSpan = document.getElementById('yield-predict-seed');
    const plantDensitySpan = document.getElementById('yield-predict-density');
    const plantSpacingSpan = document.getElementById('yield-predict-plant-spacing');
    const plantRowSpacingSpan = document.getElementById('yield-predict-row-spacing');
    const plantFertilizerSpan = document.getElementById('yield-predict-fertilizer');
    const plantPhSpan = document.getElementById('yield-predict-ph');
    const plantMaturitySpan = document.getElementById('yield-predict-maturity');
    const plantProduceSpan = document.getElementById('yield-predict-produce');

    fetch(`/crop-yield-estimates?crop_name=${cropName}&plot_id=${plotId}`)
        .then(response => response.json())
        .then(data => {
            const {
                ideal_soil, ideal_month, seeds_min, seeds_max, seeds_unit,
                density_min, density_max, spacing_plant_min, spacing_plant_max,
                spacing_row_min, spacing_row_max, fertilizer_advice, ph_advice,
                maturity_min, maturity_max, maturity_unit, maturity_type,
                produce_min, produce_max, hectare
            } = data;

            // Title
            plantTitle.innerText = `Recommendations for ${cropName}`;

            // Soil type and month
            plantSoilTypeSpan.innerText = `${ideal_soil}`;
            plantMonthSpan.innerText = `It's ideal to plant ${ideal_month}`;

            // Seed calculation
            const seedsMin = (seeds_min * hectare).toLocaleString();
            const seedsMax = (seeds_max * hectare).toLocaleString();
            plantSeedSpan.innerText = seedsMin === seedsMax ?
                `Around ${seedsMin} ${seeds_unit}` : `${seedsMin} to ${seedsMax} ${seeds_unit}`;

            // Density calculation
            const densityMin = Math.floor((density_min * hectare)).toLocaleString();
            const densityMax = Math.floor((density_max * hectare)).toLocaleString();
            plantDensitySpan.innerText = densityMin === densityMax ?
                `Around ${densityMin} plants` : `${densityMin} to ${densityMax} plants`;

            // Spacing
            plantSpacingSpan.innerText = spacing_plant_min === spacing_plant_max ?
                `Around ${spacing_plant_min} cm` : `${spacing_plant_min}-${spacing_plant_max} cm`;

            if (spacing_row_min && spacing_row_max) {
                plantRowSpacingSpan.innerText = spacing_row_min === spacing_row_max ?
                    `Around ${spacing_row_min} cm` : `${spacing_row_min}-${spacing_row_max} cm`;
            } else {
                plantRowSpacingSpan.innerText = 'Row spacing not available';
            }

            // Fertilizer advice
            plantFertilizerSpan.innerText = fertilizer_advice;
            plantPhSpan.innerText = ph_advice;

            // Maturity
            const maturityMin = Math.floor((maturity_min * hectare)).toLocaleString();
            const maturityMax = Math.floor((maturity_max * hectare)).toLocaleString();
            plantMaturitySpan.innerText = maturityMin === maturityMax ?
                `Around ${maturityMin} ${maturity_unit} after ${maturity_type}` : `${maturityMin} to ${maturityMax} ${maturity_unit} after ${maturity_type}`;

            // Produce
            const produceMin = Math.floor((produce_min * hectare)).toLocaleString();
            const produceMax = Math.floor((produce_max * hectare)).toLocaleString();
            const produceAvg = ((produce_min * hectare) + (produce_max * hectare)) / 2;
            plantProduceSpan.innerText = produceMin === produceMax ?
                `Around ${produceMin} tons` : `${produceMin} to ${produceMax} tons`;

        })
        .catch(error => console.error('Error:', error));
}
