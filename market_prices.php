<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT * FROM market_prices WHERE crop_name LIKE :search OR location LIKE :search");
        $stmt->execute(['search' => "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM market_prices");
    }
    $prices = $stmt->fetchAll();
} catch (PDOException $e) {
    $prices = [];
}
?>

<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2>Market Prices</h2>
        <p style="color: var(--text-muted);">Real-time crop prices across various markets.</p>
    </div>
    <form action="" method="GET" style="display: flex; gap: 10px;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search crop or mandi..." style="padding: 0.8rem 1.2rem; border-radius: 8px; border: 1px solid #ddd; outline: none; width: 250px;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="table-container" style="margin-top: 2rem;">
    <table>
        <thead>
            <tr>
                <th>Crop Name</th>
                <th>Price per KG</th>
                <th>Market Location</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($prices) > 0): ?>
                <?php foreach($prices as $price): ?>
                <tr>
                    <td style="font-weight: 600;"><?php echo $price['crop_name']; ?></td>
                    <td class="price-up">₹<?php echo $price['price_per_kg']; ?></td>
                    <td><i class="fas fa-location-dot" style="color: #e74c3c;"></i> <?php echo $price['location']; ?></td>
                    <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo date('d M, Y H:i', strtotime($price['updated_at'])); ?></td>
                    <td><a href="#" style="color: var(--primary);"><i class="fas fa-chart-area"></i> Trends</a></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem;">No market data found for "<?php echo htmlspecialchars($search); ?>"</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="glass-card" style="margin-top: 2rem;">
    <p style="font-size: 0.9rem; color: var(--text-muted);">* Prices are indicative and may vary based on quality and volume. Data updated every hour.</p>
</div>

<?php include 'includes/footer.php'; ?>
