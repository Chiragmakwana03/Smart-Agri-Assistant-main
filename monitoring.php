<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="dashboard-header">
    <h2>Live Farm Monitoring</h2>
    <p style="color: var(--text-muted);">Real-time IoT data from your fields.</p>
</div>

<!-- Live Cards Section -->
<div class="stats-grid" style="margin-top: 2rem;">
    <div class="stat-card">
        <div class="stat-label"><i class="fas fa-tint" style="color: #3498db;"></i> Soil Moisture</div>
        <div class="stat-value" id="liveMoisture">--%</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Latest Reading</div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fas fa-thermometer-half" style="color: #e67e22;"></i> Temperature</div>
        <div class="stat-value" id="liveTemp">--°C</div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Latest Reading</div>
    </div>
    <div class="stat-card" id="pumpStatusCard">
        <div class="stat-label"><i class="fas fa-power-off"></i> Pump Status</div>
        <div class="stat-value" id="livePump">OFF</div>
        <div style="margin-top: 10px;">
            <button id="toggleBtn" onclick="controlPump()" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; width: 100%;">Switch ON</button>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Chart Section -->
    <div class="glass-card">
        <h3>Soil Moisture Trend</h3>
        <canvas id="moistureChart" style="margin-top: 1.5rem;"></canvas>
    </div>

    <!-- Latest Data Table -->
    <div class="table-container">
        <h3>Last 10 Records</h3>
        <table style="margin-top: 1rem;">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Moisture</th>
                    <th>Temp</th>
                    <th>Pump</th>
                </tr>
            </thead>
            <tbody id="historyTableBody">
                <!-- Rows injected by JS -->
                <tr><td colspan="4" style="text-align:center;">Loading data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    let moistureChart;

    function initChart(data) {
        const ctx = document.getElementById('moistureChart').getContext('2d');
        moistureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(r => new Date(r.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})),
                datasets: [{
                    label: 'Soil Moisture (%)',
                    data: data.map(r => r.soil_moisture),
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });
    }

    function controlPump() {
        const currentStatus = document.getElementById('livePump').innerText;
        const newStatus = (currentStatus === 'OFF') ? 1 : 0;
        window.location.href = `api/toggle_pump.php?status=${newStatus}`;
    }

    async function updateDashboard() {
        try {
            const res = await fetch('api/get_monitoring_stats.php');
            const data = await res.json();
            
            if (data.status === 'success') {
                // Update Cards
                if (data.latest) {
                    document.getElementById('liveMoisture').innerText = data.latest.soil_moisture + '%';
                    document.getElementById('liveTemp').innerText = data.latest.temperature + '°C';
                    
                    const pumpEl = document.getElementById('livePump');
                    const pumpCard = document.getElementById('pumpStatusCard');
                    const toggleBtn = document.getElementById('toggleBtn');
                    
                    pumpEl.innerText = data.latest.pump_status;
                    
                    if (data.latest.pump_status.toUpperCase() === 'ON') {
                        pumpCard.style.background = '#e8f5e9';
                        pumpEl.style.color = '#2d6a4f';
                        toggleBtn.innerText = 'Switch OFF';
                        toggleBtn.className = 'btn btn-danger';
                    } else {
                        pumpCard.style.background = '#fff';
                        pumpEl.style.color = 'inherit';
                        toggleBtn.innerText = 'Switch ON';
                        toggleBtn.className = 'btn btn-primary';
                    }
                }

                // Update Table
                const tableBody = document.getElementById('historyTableBody');
                tableBody.innerHTML = '';
                // data.history is reversed from chronological (so latest first in table)
                const historyForTable = [...data.history].reverse();
                historyForTable.forEach(row => {
                    const time = new Date(row.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    tableBody.innerHTML += `
                        <tr>
                            <td>${time}</td>
                            <td>${row.soil_moisture}%</td>
                            <td>${row.temperature}°C</td>
                            <td style="color: ${row.pump_status == 'ON' ? '#27ae60' : '#e74c3c'}; font-weight: bold;">${row.pump_status}</td>
                        </tr>
                    `;
                });

                // Update Chart
                if (!moistureChart) {
                    initChart(data.history);
                } else {
                    moistureChart.data.labels = data.history.map(r => new Date(r.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}));
                    moistureChart.data.datasets[0].data = data.history.map(r => r.soil_moisture);
                    moistureChart.update('none'); // Update without animation for smoothness
                }
            }
        } catch (e) {
            console.error("Failed to fetch dashboard data", e);
        }
    }

    // Initial load and set interval
    updateDashboard();
    setInterval(updateDashboard, 5000);
</script>

<?php include 'includes/footer.php'; ?>
