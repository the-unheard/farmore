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
    const plantOthersHarvested = document.getElementById('yield-others-harvested');
    const plantTotalExpected = document.getElementById('yield-total-expected');
    const plantIdealFertilizer = document.getElementById('yield-ideal-fertilizer');
    const plantIdealPh = document.getElementById('yield-ideal-ph');

    fetch(`/crop-yield-estimates?crop_name=${cropName}&plot_id=${plotId}`)
        .then(response => response.json())
        .then(data => {
            // Use Laravel-calculated values
            const {
                crop_name, ideal_soil, ideal_soonest_month, seeds_needed,
                density, spacing_plant, spacing_row, ph, npk, maturity, yield_message,
                total_actual_yield, total_expected, my_expected_max, ph_fertilizer, npk_fertilizer
            } = data;

            // Title
            plantTitle.innerText = `Recommendations for ${crop_name}`;

            // Soil type and planting time
            plantSoilTypeSpan.innerText = ideal_soil;
            plantMonthSpan.innerText = `It's ideal to plant in ${ideal_soonest_month}`;

            // Seeds & density (already calculated in Laravel)
            plantSeedSpan.innerText = seeds_needed;
            plantDensitySpan.innerText = density;

            // Spacing
            plantSpacingSpan.innerText = spacing_plant;
            plantRowSpacingSpan.innerText = spacing_row ? spacing_row : 'Row spacing not available';

            // Fertilizer advice
            plantFertilizerSpan.innerText = npk;
            plantPhSpan.innerText = ph;
            plantIdealFertilizer.innerText = npk_fertilizer;
            plantIdealPh.innerText = ph_fertilizer;

            // Maturity & Expected Yield
            plantMaturitySpan.innerText = maturity;
            plantProduceSpan.innerText = `Expected yield: ${yield_message}`;

            // Other farms' harvested total
            plantOthersHarvested.innerText = `${total_actual_yield} tons`;
            plantTotalExpected.innerText = total_expected;

        })
        .catch(error => console.error('Error:', error));


}
