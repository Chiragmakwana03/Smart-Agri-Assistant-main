<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';

try {
    $stmt = $pdo->query("SELECT * FROM schemes");
    $schemes = $stmt->fetchAll();
} catch (PDOException $e) {
    $schemes = [];
}
?>

<div class="dashboard-header">
    <h2>Government Schemes</h2>
    <p style="color: var(--text-muted);">Empowering farmers with financial and social security.</p>
</div>

<div class="schemes-grid" style="margin-top: 2rem;">
    <?php foreach($schemes as $scheme): ?>
    <div class="scheme-card">
        <h3><?php echo $scheme['title']; ?></h3>
        
        <div style="margin-bottom: 1rem;">
            <strong style="color: var(--primary-dark); font-size: 0.9rem;">ELIGIBILITY</strong>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 5px;"><?php echo $scheme['eligibility']; ?></p>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <strong style="color: var(--primary-dark); font-size: 0.9rem;">BENEFITS</strong>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 5px;"><?php echo $scheme['benefits']; ?></p>
        </div>
        
        <a href="<?php echo $scheme['portal_link']; ?>" target="_blank" class="btn btn-primary" style="width: 100%; text-align: center;">How to Apply</a>
    </div>
    <?php endforeach; ?>
</div>

<div class="glass-card" style="margin-top: 3rem; background: var(--primary-dark); color: white;">
    <h3>Need help with applications?</h3>
    <p style="margin-top: 10px; opacity: 0.8;">Visit your nearest Common Service Center (CSC) or contact our expert helpline for step-by-step assistance.</p>
    <a href="contact.php" class="btn" style="background: var(--accent); color: var(--primary-dark); margin-top: 1.5rem;">Contact Support</a>
</div>

<?php include 'includes/footer.php'; ?>
