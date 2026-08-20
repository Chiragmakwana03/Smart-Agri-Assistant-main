<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="logo">
        <i class="fas fa-seedling"></i>
        <h1>Agri<span>Assist</span></h1>
    </div>
    <ul class="nav-links">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="monitoring.php" class="nav-link <?php echo $current_page == 'monitoring.php' ? 'active' : ''; ?>">
                <i class="fas fa-broadcast-tower"></i>
                <span>Live Monitoring</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="weather.php" class="nav-link <?php echo $current_page == 'weather.php' ? 'active' : ''; ?>">
                <i class="fas fa-cloud-sun"></i>
                <span>Weather</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="market_prices.php" class="nav-link <?php echo $current_page == 'market_prices.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                <span>Market Prices</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="crop_advice.php" class="nav-link <?php echo $current_page == 'crop_advice.php' ? 'active' : ''; ?>">
                <i class="fas fa-leaf"></i>
                <span>Crop Advice</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="schemes.php" class="nav-link <?php echo $current_page == 'schemes.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice"></i>
                <span>Govt Schemes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="ai_assistant.php" class="nav-link <?php echo $current_page == 'ai_assistant.php' ? 'active' : ''; ?>">
                <i class="fas fa-robot"></i>
                <span>AI Assistant</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="contact.php" class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">
                <i class="fas fa-headset"></i>
                <span>Expert Help</span>
            </a>
        </li>
    </ul>

    <div style="margin-top: auto; padding-top: 2rem;">
        <a href="logout.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

<main class="main-content">
    <header class="top-header">
        <div class="search-bar">
            <!-- Search placeholder -->
        </div>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?></div>
        </div>
    </header>
