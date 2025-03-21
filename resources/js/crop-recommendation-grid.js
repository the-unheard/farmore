document.addEventListener("DOMContentLoaded", function () {
    let parsedCoordinates = typeof plotCoordinates === "string" ? JSON.parse(plotCoordinates) : plotCoordinates;
    let canvasSize = 650;
    let gridSizeMeters = 10; // Default plot grid size (10m)
    let cropGridSizeMeters = 100; // Default crop grid size in cm (1m = 100cm)
    let activeCropIndex = 0; // Default first crop

    const polygonCoordinates = parsedCoordinates.map(coord => Array.isArray(coord) ? coord : Object.values(coord));
    const polygonCanvas = document.getElementById("polygonCanvas");
    const polygonCtx = polygonCanvas.getContext("2d");
    const cropCanvas = document.getElementById("cropLayoutCanvas");
    const cropCtx = cropCanvas.getContext("2d");

    const plantSpacingSlider = document.getElementById("plantSpacingSlider");
    const plantSpacingValue = document.getElementById("plantSpacingValue");
    const rowSpacingSlider = document.getElementById("rowSpacingSlider");
    const rowSpacingValue = document.getElementById("rowSpacingValue");

    const cropTabs = document.querySelectorAll("[data-tab]"); // Select all crop tabs


    function updateSpacingControls() {
        let crop = cropData[activeCropIndex];

        let plantMin = crop.spacing_plant_min;
        let plantMax = crop.spacing_plant_max;
        let hasRowSpacing = crop.spacing_row_min && crop.spacing_row_max && crop.spacing_row_min !== "" && crop.spacing_row_max !== "";

        let rowMin = hasRowSpacing ? crop.spacing_row_min : plantMin;
        let rowMax = hasRowSpacing ? crop.spacing_row_max : plantMax;

        plantSpacingSlider.min = plantMin;
        plantSpacingSlider.max = plantMax;
        plantSpacingSlider.value = plantMin;
        plantSpacingValue.textContent = `${plantMin} cm`;

        rowSpacingSlider.min = rowMin;
        rowSpacingSlider.max = rowMax;
        rowSpacingSlider.value = hasRowSpacing ? rowMin : plantMin;
        rowSpacingValue.textContent = hasRowSpacing ? `${rowMin} cm` : "";

        document.getElementById("spacingRowSlider").style.display = hasRowSpacing ? "block" : "none";

        const cropGrid10mButton = document.getElementById("cropGrid10m");
        const cropGrid1mButton = document.getElementById("cropGrid1m");

        // Disable cropGrid10m button if plantMin and rowMin are both less than 15
        if (plantMin < 15 && rowMin < 15) {
            cropGrid10mButton.disabled = true;
            cropGrid10mButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            cropGrid10mButton.disabled = false;
            cropGrid10mButton.classList.remove("opacity-50", "cursor-not-allowed");
        }

        // Auto-switch to 1m² if 10m² is currently selected but now invalid
        if (plantMin < 15 && rowMin < 15 && cropGrid10mButton.classList.contains("bg-white")) {
            // Auto-switch to 1m²
            cropGridSizeMeters = 100; // 1m = 100cm
            setActiveButton([cropGrid10mButton, cropGrid1mButton, document.getElementById("cropGrid0_1m")], cropGrid1mButton);
            drawCropGrid();
        }

        drawCropGrid();
    }

    function drawPolygonVisualization() {
        polygonCtx.clearRect(0, 0, polygonCanvas.width, polygonCanvas.height);

        polygonCtx.fillStyle = "#f9fafb";
        polygonCtx.fillRect(0, 0, polygonCanvas.width, polygonCanvas.height);

        const minX = Math.min(...polygonCoordinates.map(p => p[0]));
        const maxX = Math.max(...polygonCoordinates.map(p => p[0]));
        const minY = Math.min(...polygonCoordinates.map(p => p[1]));
        const maxY = Math.max(...polygonCoordinates.map(p => p[1]));

        const rangeX = maxX - minX;
        const rangeY = maxY - minY;
        const scale = canvasSize / Math.max(rangeX, rangeY);

        const plotSizeMeters = Math.sqrt(plotHectare * 10_000);
        const metersPerPixel = plotSizeMeters / canvasSize;
        const gridSizePixels = gridSizeMeters / metersPerPixel;

        polygonCtx.beginPath();
        polygonCoordinates.forEach(([x, y], index) => {
            const normX = (x - minX) * scale;
            const normY = canvasSize - (y - minY) * scale;
            if (index === 0) {
                polygonCtx.moveTo(normX, normY);
            } else {
                polygonCtx.lineTo(normX, normY);
            }
        });
        polygonCtx.closePath();

        polygonCtx.save();
        polygonCtx.clip();

        polygonCtx.strokeStyle = "rgba(0,123,255,0.66)";
        polygonCtx.lineWidth = 0.5;

        for (let x = 0; x < canvasSize; x += gridSizePixels) {
            for (let y = 0; y < canvasSize; y += gridSizePixels) {
                polygonCtx.strokeRect(x, y, gridSizePixels, gridSizePixels);
            }
        }

        polygonCtx.restore();
        polygonCtx.fillStyle = "rgba(0, 123, 255, 0.3)";
        polygonCtx.fill();
        polygonCtx.strokeStyle = "#007bff";
        polygonCtx.lineWidth = 2;
        polygonCtx.stroke();
    }

    function drawCropGrid() {
        cropCtx.clearRect(0, 0, cropCanvas.width, cropCanvas.height);

        let padding = 20;
        let adjustedCanvasSize = canvasSize - padding * 2;

        const spacingPlant = parseInt(plantSpacingSlider.value);
        let spacingRow = parseInt(rowSpacingSlider.value);

        // **Set Background Fill & Outline to Match Left Polygon**
        cropCtx.fillStyle = "rgba(0, 123, 255, 0.3)"; // Same light blue as polygon
        cropCtx.fillRect(0, 0, cropCanvas.width, cropCanvas.height);

        cropCtx.strokeStyle = "#007bff"; // Same blue border as polygon
        cropCtx.lineWidth = 2;
        cropCtx.strokeRect(0, 0, cropCanvas.width, cropCanvas.height); // Draw border

        if (rowSpacingSlider.disabled) {
            spacingRow = spacingPlant;
        }

        const plantSpacingPixels = (spacingPlant / cropGridSizeMeters) * adjustedCanvasSize;
        const rowSpacingPixels = (spacingRow / cropGridSizeMeters) * adjustedCanvasSize;

        cropCtx.strokeStyle = "transparent";
        cropCtx.lineWidth = 0.5;

        for (let x = padding; x <= adjustedCanvasSize + padding; x += plantSpacingPixels) {
            for (let y = padding; y <= adjustedCanvasSize + padding; y += rowSpacingPixels) {
                cropCtx.strokeRect(x, y, plantSpacingPixels, rowSpacingPixels);
            }
        }

        cropCtx.fillStyle = "rgba(0, 123, 255, 0.7)";
        for (let x = padding; x <= adjustedCanvasSize + padding; x += plantSpacingPixels) {
            for (let y = padding; y <= adjustedCanvasSize + padding; y += rowSpacingPixels) {
                cropCtx.beginPath();
                cropCtx.arc(x, y, 5, 0, Math.PI * 2);
                cropCtx.fill();
            }
        }
    }

    function setActiveButton(buttons, activeButton) {
        buttons.forEach(btn => btn.classList.remove("rounded", "bg-white", "shadow"));
        activeButton.classList.add("rounded", "bg-white", "shadow");
    }

    // **Plot Grid Toggle**
    const plotGridButtons = [document.getElementById("plotGrid10m"), document.getElementById("plotGrid1m")];
    plotGridButtons.forEach(button => {
        button.addEventListener("click", function () {
            gridSizeMeters = this.id === "plotGrid10m" ? 10 : 1;
            setActiveButton(plotGridButtons, this);
            drawPolygonVisualization();
        });
    });

    // **Crop Grid Toggle**
    const cropGridButtons = [document.getElementById("cropGrid10m"), document.getElementById("cropGrid1m"), document.getElementById("cropGrid0_1m")];
    cropGridButtons.forEach(button => {
        button.addEventListener("click", function () {
            if (this.id === "cropGrid10m") cropGridSizeMeters = 1000;
            if (this.id === "cropGrid1m") cropGridSizeMeters = 100;
            if (this.id === "cropGrid0_1m") cropGridSizeMeters = 10;
            setActiveButton(cropGridButtons, this);
            drawCropGrid();
        });
    });

    // **Update Crop Grid on Slider Change**
    plantSpacingSlider.addEventListener("input", () => {
        plantSpacingValue.textContent = `${plantSpacingSlider.value} cm`;
        if (rowSpacingSlider.disabled) {
            rowSpacingSlider.value = plantSpacingSlider.value;
            rowSpacingValue.textContent = `${rowSpacingSlider.value} cm`;
        }
        drawCropGrid();
    });

    rowSpacingSlider.addEventListener("input", () => {
        rowSpacingValue.textContent = `${rowSpacingSlider.value} cm`;
        drawCropGrid();
    });

    cropTabs.forEach((tab, index) => {
        tab.addEventListener("click", function () {
            activeCropIndex = index; // Update active crop
            updateSpacingControls(); // Refresh controls and grid
        });
    });

    // **Initial Draw**
    drawPolygonVisualization();
    updateSpacingControls();
    drawCropGrid();
});
