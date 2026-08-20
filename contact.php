<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="dashboard-header">
    <h2>Expert Help & Support</h2>
    <p style="color: var(--text-muted);">Get in touch with agricultural experts and technical support.</p>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Contact Form -->
    <div class="glass-card">
        <h3>Ask an Expert</h3>
        <form action="#" method="POST" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Subject</label>
                <input type="text" placeholder="e.g. Crop disease, Seed quality" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Your Message</label>
                <textarea rows="5" placeholder="Describe your issue in detail..." style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd; font-family: inherit;"></textarea>
            </div>
            <button type="button" class="btn btn-primary" onclick="alert('Message sent to expert! We will notify you via SMS.')">Send Message</button>
        </form>
    </div>

    <!-- FAQ / Support Info -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="stat-card" style="border-left: 4px solid #e67e22;">
            <h4><i class="fas fa-phone"></i> Toll-Free Helpline</h4>
            <p style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">1800-123-4567</p>
            <p style="font-size: 0.8rem; color: var(--text-muted);">Available 24/7 for farmers.</p>
        </div>

        <div class="table-container">
            <h3>Quick FAQ</h3>
            <div style="margin-top: 1rem;">
                <div style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <strong style="font-size: 0.9rem;">How to check soil health?</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Visit your local agriculture office with a soil sample for testing.</p>
                </div>
                <div style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <strong style="font-size: 0.9rem;">When to apply Urea?</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Best applied during the growing stage, ideally before irrigation.</p>
                </div>
                <div>
                    <strong style="font-size: 0.9rem;">How to track market prices?</strong>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">Use the 'Market Prices' section in this app for daily updates.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
