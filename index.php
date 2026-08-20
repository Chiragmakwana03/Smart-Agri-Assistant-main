<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Fetch latest market prices
try {
    $stmt = $pdo->query("SELECT * FROM market_prices LIMIT 5");
    $prices = $stmt->fetchAll();
} catch (PDOException $e) {
    $prices = [];
}
?>

<div class="dashboard-header">
    <h2>Dashboard Overview</h2>
    <p style="color: var(--text-muted);">Agricultural insights and weather tracking.</p>
</div>

<div class="stats-grid" style="margin-top: 1.5rem;">
    <!-- Weather Card -->
    <div class="stat-card" id="dashWeatherCard">
        <div class="stat-label"><i class="fas fa-cloud-sun"></i> Weather (<span id="dashCity">Loading...</span>)</div>
        <div class="stat-value" id="dashTemp">--°C</div>
        <div id="dashDesc" style="font-size: 0.8rem; color: #27ae60;">Fetching data...</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Quick Market Snippet -->
    <div class="table-container">
        <h3 style="margin-bottom: 1rem;">Live Market Prices</h3>
        <table>
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($prices as $price): ?>
                <tr>
                    <td><?php echo $price['crop_name']; ?></td>
                    <td class="price-up">₹<?php echo $price['price_per_kg']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="market_prices.php" style="display: block; margin-top: 1rem; color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.875rem;">View All Prices →</a>
    </div>
</div>

<script>
    // Live Weather for Dashboard (using Open-Meteo)
    const DEFAULT_CITY = 'Dahod';

    async function fetchDashWeather() {
        try {
            // 1. Geocoding
            const geoRes = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${DEFAULT_CITY}&count=1&language=en&format=json`);
            const geoData = await geoRes.json();
            
            if (geoData.results && geoData.results.length > 0) {
                const { latitude, longitude, name } = geoData.results[0];
                
                // 2. Current Weather
                const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true`);
                const weatherData = await weatherRes.json();
                
                document.getElementById('dashCity').innerText = name;
                document.getElementById('dashTemp').innerText = `${Math.round(weatherData.current_weather.temperature)}°C`;
                
                // Simple mapping for description
                const codes = { 0: 'Clear', 1: 'Mainly Clear', 2: 'Partly Cloudy', 3: 'Overcast', 45: 'Foggy', 95: 'Stormy' };
                document.getElementById('dashDesc').innerText = codes[weatherData.current_weather.weathercode] || 'Clear Sky';
            }
        } catch (e) {
            console.error("Weather load failed");
            document.getElementById('dashDesc').innerText = "Offline";
        }
    }
    fetchDashWeather();
</script>

<?php include 'includes/footer.php'; ?>
