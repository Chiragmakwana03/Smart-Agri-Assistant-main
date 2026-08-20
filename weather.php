<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="dashboard-header">
    <h2>Real-Time Weather</h2>
    <p style="color: var(--text-muted);">Enter your city name to get exact weather updates.</p>
</div>

<div class="glass-card" style="margin-top: 2rem;">
    <div style="display: flex; gap: 10px;">
        <input type="text" id="cityInput" placeholder="Enter City Name (e.g. Dahod, Mumbai)" style="flex: 1; padding: 0.8rem 1.2rem; border-radius: 8px; border: 1px solid #ddd; outline: none;">
        <button id="getWeatherBtn" class="btn btn-primary"><i class="fas fa-search"></i> Get Weather</button>
    </div>
</div>

<div id="weatherResult" style="display: none; margin-top: 2rem;">
    <div class="weather-main">
        <div id="weatherIconContainer"></div>
        <div id="cityName" style="font-size: 1.2rem; opacity: 0.8; margin-bottom: 0.5rem;"></div>
        <div id="tempValue" style="font-size: 3rem; font-weight: 700;"></div>
        <div id="weatherDesc" style="font-size: 1.2rem; opacity: 0.9;"></div>
        <div style="margin-top: 1rem; display: flex; justify-content: center; gap: 30px;">
            <div><i class="fas fa-droplet"></i> Humidity: <span id="humidityValue"></span>%</div>
            <div><i class="fas fa-wind"></i> Wind: <span id="windValue"></span> km/h</div>
            <div><i class="fas fa-cloud"></i> Pressure: <span id="pressureValue"></span> hPa</div>
        </div>
    </div>

    <h3 style="margin: 2rem 0 1.5rem;">5-Day Forecast</h3>
    <div id="forecastGrid" class="forecast-grid">
        <!-- Forecast items will be injected here -->
    </div>
</div>

<div id="weatherLoader" style="text-align: center; padding: 4rem; display: none;">
    <i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: var(--primary);"></i>
    <p style="margin-top: 1rem;">Fetching live data...</p>
</div>

<div id="weatherError" style="text-align: center; padding: 4rem; display: none; color: #e74c3c;">
    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
    <p>City not found. Please try again with a valid city name.</p>
</div>

<script>
    const cityInput = document.getElementById('cityInput');
    const getWeatherBtn = document.getElementById('getWeatherBtn');
    const weatherResult = document.getElementById('weatherResult');
    const weatherLoader = document.getElementById('weatherLoader');
    const weatherError = document.getElementById('weatherError');

    // Mapping Open-Meteo weather codes to Font Awesome icons
    const weatherIcons = {
        0: 'fa-sun', // Clear sky
        1: 'fa-cloud-sun', 2: 'fa-cloud-sun', 3: 'fa-cloud', // Cloudy
        45: 'fa-smog', 48: 'fa-smog', // Fog
        51: 'fa-cloud-rain', 53: 'fa-cloud-rain', 55: 'fa-cloud-rain', // Drizzle
        61: 'fa-cloud-showers-heavy', 63: 'fa-cloud-showers-heavy', 65: 'fa-cloud-showers-heavy', // Rain
        71: 'fa-snowflake', 73: 'fa-snowflake', 75: 'fa-snowflake', // Snow
        80: 'fa-cloud-rain', 81: 'fa-cloud-rain', 82: 'fa-cloud-rain', // Showers
        95: 'fa-cloud-bolt', // Thunderstorm
    };

    const weatherDescriptions = {
        0: 'Clear Sky', 1: 'Mainly Clear', 2: 'Partly Cloudy', 3: 'Overcast',
        45: 'Foggy', 48: 'Depositing Rime Fog',
        51: 'Light Drizzle', 53: 'Moderate Drizzle', 55: 'Dense Drizzle',
        61: 'Slight Rain', 63: 'Moderate Rain', 65: 'Heavy Rain',
        71: 'Slight Snow', 73: 'Moderate Snow', 75: 'Heavy Snow',
        80: 'Slight Rain Showers', 81: 'Moderate Rain Showers', 82: 'Violent Rain Showers',
        95: 'Thunderstorm',
    };

    async function fetchWeather(city) {
        weatherResult.style.display = 'none';
        weatherError.style.display = 'none';
        weatherLoader.style.display = 'block';

        try {
            // 1. Geocoding
            const geoRes = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${city}&count=1&language=en&format=json`);
            const geoData = await geoRes.json();

            if (!geoData.results || geoData.results.length === 0) throw new Error('City not found');
            const { latitude, longitude, name, country } = geoData.results[0];

            // 2. Weather & Forecast
            const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true&daily=temperature_2m_max,temperature_2m_min,weathercode&timezone=auto`);
            const weatherData = await weatherRes.json();

            updateUI(name, country, weatherData);
        } catch (error) {
            console.error(error);
            weatherLoader.style.display = 'none';
            weatherError.style.display = 'block';
        }
    }

    function updateUI(cityName, country, data) {
        const current = data.current_weather;
        const daily = data.daily;

        // Update Main Weather
        document.getElementById('cityName').innerText = `${cityName}, ${country}`;
        document.getElementById('tempValue').innerText = `${Math.round(current.temperature)}°C`;
        document.getElementById('weatherDesc').innerText = weatherDescriptions[current.weathercode] || 'Clear';
        
        // Open-Meteo doesn't provide humidity/pressure in the basic current_weather, using dummy or fetching more
        document.getElementById('humidityValue').innerText = '--'; 
        document.getElementById('windValue').innerText = current.windspeed;
        document.getElementById('pressureValue').innerText = '--';
        
        const iconClass = weatherIcons[current.weathercode] || 'fa-sun';
        document.getElementById('weatherIconContainer').innerHTML = `<i class="fas ${iconClass}" style="font-size: 5rem; margin-bottom: 1rem;"></i>`;

        // Update Forecast
        const forecastGrid = document.getElementById('forecastGrid');
        forecastGrid.innerHTML = '';
        
        for (let i = 0; i < 5; i++) {
            const date = new Date(daily.time[i]);
            const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
            const icon = weatherIcons[daily.weathercode[i]] || 'fa-sun';
            
            const item = document.createElement('div');
            item.className = 'forecast-item';
            item.innerHTML = `
                <div style="font-weight: 600; color: var(--text-muted);">${dayName}</div>
                <i class="fas ${icon}" style="font-size: 2rem; color: var(--primary); margin: 1rem 0; display: block;"></i>
                <div style="font-weight: 700;">${Math.round(daily.temperature_2m_max[i])}°C</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">${Math.round(daily.temperature_2m_min[i])}°C</div>
            `;
            forecastGrid.appendChild(item);
        }

        weatherLoader.style.display = 'none';
        weatherResult.style.display = 'block';
    }

    getWeatherBtn.addEventListener('click', () => {
        if (cityInput.value) fetchWeather(cityInput.value);
    });

    cityInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && cityInput.value) fetchWeather(cityInput.value);
    });

    // Default load
    fetchWeather('Dahod');
</script>

<?php include 'includes/footer.php'; ?>
