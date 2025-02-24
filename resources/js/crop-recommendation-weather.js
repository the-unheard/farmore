document.addEventListener("DOMContentLoaded", function () {
    function updateWeatherText(text) {
        document.querySelectorAll(".weather-ideal").forEach((el) => {
            el.innerText = text;
            updateWeatherTag(el.closest(".tab-content"), text); // Update tag inside the correct crop tab
        });
    }

    function updateWeatherTag(tabContent, weatherText) {
        if (!tabContent) return;

        const tagContainer = tabContent.querySelector(".tags-container");
        if (!tagContainer) return;

        // Remove old weather tag if it exists
        const existingWeatherTag = tagContainer.querySelector(".weather-tag");
        if (existingWeatherTag) {
            existingWeatherTag.remove();
        }

        // If the weather is ideal, add a new "Weather" tag
        if (weatherText.startsWith("Yes")) {
            const weatherTag = document.createElement("span");
            weatherTag.classList.add("px-3", "py-1", "text-sm", "font-semibold", "text-white", "bg-grad-blue", "rounded-full", "weather-tag");
            weatherTag.innerText = "Weather";
            tagContainer.appendChild(weatherTag);
        }
    }

    if (!plotLatitude || !plotLongitude) {
        updateWeatherText("Weather data unavailable");
        return;
    }

    fetch(`/weather-data?latitude=${plotLatitude}&longitude=${plotLongitude}`)
        .then((response) => response.json())
        .then((data) => {
            if (!data.forecast || !data.forecast.list) {
                updateWeatherText("Weather data unavailable");
                return;
            }

            const now = new Date();
            const today = now.getDate();
            const tomorrow = new Date(now);
            tomorrow.setDate(today + 1);

            let rainTodayOrTomorrow = null;
            let rainAfterTomorrow = null;

            for (let entry of data.forecast.list) {
                const forecastDate = new Date(entry.dt * 1000);
                const forecastDay = forecastDate.getDate();
                const isRainy = entry.weather.some((w) =>
                    w.main.toLowerCase().includes("rain")
                );

                if (isRainy) {
                    if (forecastDay === today || forecastDay === tomorrow.getDate()) {
                        rainTodayOrTomorrow = forecastDate;
                        break;
                    } else if (!rainAfterTomorrow) {
                        rainAfterTomorrow = forecastDate;
                    }
                }
            }

            let weatherText;
            if (rainTodayOrTomorrow) {
                weatherText = `No, it's going to rain on ${rainTodayOrTomorrow.toLocaleString("en-US", {
                    weekday: "long",
                    hour: "numeric",
                    minute: "numeric",
                    hour12: true,
                })}`;
            } else if (rainAfterTomorrow) {
                weatherText = `Yes, it won't rain until ${rainAfterTomorrow.toLocaleString("en-US", {
                    weekday: "long",
                    hour: "numeric",
                    minute: "numeric",
                    hour12: true,
                })}`;
            } else {
                weatherText = "Yes, it's not going to rain for the next 3 days";
            }

            updateWeatherText(weatherText);
        })
        .catch((error) => {
            console.error("Error fetching weather forecast:", error);
            updateWeatherText("Weather data unavailable");
        });
});
