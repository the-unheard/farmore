document.addEventListener('DOMContentLoaded', function() {

    const loadingScreen = document.getElementById('loading-screen');
    const weatherContent = document.getElementById('weather-content');

    const data = plotData || {};
    const longitude = data.longitude || null;
    const latitude = data.latitude || null;

    // Fetch weather data
    fetch(`/weather-data?latitude=${latitude}&longitude=${longitude}`)
        .then(response => response.json()) // Directly get the response as JSON
        .then(data => {
            // General weather info
            document.getElementById('today-weather-desc').innerText = data.weather.weather[0].description;
            document.getElementById('today-weather-icon').src = `http://openweathermap.org/img/wn/${data.weather.weather[0].icon}@4x.png`;
            document.getElementById('today-weather-icon-small').src = `http://openweathermap.org/img/wn/${data.weather.weather[0].icon}@2x.png`;

            // Temperatures
            document.getElementById('today-weather-temp').innerText = `${(data.weather.main.temp - 273.15).toFixed(2)} °C`;
            document.getElementById('today-weather-feel').innerText = `Feels like ${(data.weather.main.feels_like - 273.15).toFixed(2)} °C`;

            // Rainfall
            document.getElementById('today-weather-rain').innerText = data.weather.rain && data.weather.rain["1h"]
                ? `${data.weather.rain["1h"]} mm (last hour)`
                : `0 mm (last hour)`;

            // Pressure, humidity, visibility, cloudiness
            document.getElementById('today-weather-pressure').innerText = `${data.weather.main.pressure} hPa`;
            document.getElementById('today-weather-humidity').innerText = `${data.weather.main.humidity}%`;
            document.getElementById('today-weather-visibility').innerText = `${(data.weather.visibility / 1000).toFixed(2)} km`;
            document.getElementById('today-weather-cloudiness').innerText = `${data.weather.clouds.all}%`;

            // Wind details
            document.getElementById('today-weather-wind-speed').innerText = `${data.weather.wind.speed} m/s`;
            document.getElementById('today-weather-wind-direction').innerText = `${data.weather.wind.deg}°`;

            // Sunrise and sunset
            const options = { hour: '2-digit', minute: '2-digit' }; // Options to show only hours and minutes
            const sunriseTime = new Date(data.weather.sys.sunrise * 1000).toLocaleTimeString([], options);
            const sunsetTime = new Date(data.weather.sys.sunset * 1000).toLocaleTimeString([], options);
            document.getElementById('today-weather-sunrise').innerText = sunriseTime;
            document.getElementById('today-weather-sunset').innerText = sunsetTime;

            // Min and max temperature
            document.getElementById('today-weather-min-temp').innerText = `${(data.weather.main.temp_min - 273.15).toFixed(2)} °C`;
            document.getElementById('today-weather-max-temp').innerText = `${(data.weather.main.temp_max - 273.15).toFixed(2)} °C`;

            // Atmospheric pressure
            document.getElementById('today-weather-sea').innerText = `${data.weather.main.sea_level} hPa`;
            document.getElementById('today-weather-ground').innerText = `${data.weather.main.grnd_level} hPa`;

            // Air pollution data
            const aqiDescriptions = {
                1: 'Good',
                2: 'Fair',
                3: 'Moderate',
                4: 'Poor',
                5: 'Very Poor'
            };
            const aqiValue = data.air_pollution.list[0].main.aqi;
            document.getElementById('today-weather-pollution').innerText = `${aqiValue} - ${aqiDescriptions[aqiValue]}`;

            // Forecast data
            const weatherForecastContainer = document.getElementById('weather-forecast');
            const filteredForecasts = data.forecast.list.filter((_, index) => index % 4 === 0).slice(0, 6);

            // Now loop through the filtered forecasts
            filteredForecasts.forEach((forecast, index) => {
                // Format the date
                const forecastDate = new Date(forecast.dt * 1000).toLocaleDateString(undefined, {
                    weekday: 'short',
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });

                // Get the weather icon and description
                const weatherIcon = `http://openweathermap.org/img/wn/${forecast.weather[0].icon}.png`;
                const weatherDesc = forecast.weather[0].description;
                const temperature = `${Math.round(forecast.main.temp - 273.15)} °C`;

                // Create a new card element
                const card = document.createElement('div');
                card.classList.add('weather-forecast-card', 'flex-grow', 'bg-gray-50', 'p-6', 'rounded-md', 'text-center', 'shadow-md');

                // Add the card's HTML content
                card.innerHTML = `
                        <div class="weather-forecast-dt text-sm mb-2 text-left text-gray-500 font-semibold">${forecastDate}</div>
                        <div class="flex items-center justify-between">
                            <div class="weather-forecast-temp mr-2 text-gray-900 text-3xl">${temperature}</div>
                            <div class="bg-grad-blue rounded-full p-2 w-13 h-13">
                                <img src="${weatherIcon}" alt="${weatherDesc}" class="weather-forecast-icon w- h-9">
                            </div>
                        </div>
                        <div class="weather-forecast-info text-sm mt-2 capitalize text-gray-900 text-left">${weatherDesc}</div>
                `;

                // Append the card to the forecast container
                weatherForecastContainer.appendChild(card);
            });

            // Hide loading screen and show weather content
            loadingScreen.classList.add('hidden');
            weatherContent.classList.remove('hidden');

        })
        .catch(error => {
            console.error('Error fetching weather data:', error);
        });
});
