<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$soil = isset($_POST['soil']) ? $_POST['soil'] : '';
$season = isset($_POST['season']) ? $_POST['season'] : '';

$advice = null;
$advice = null;
if ($soil && $season) {
    $cropData = [
        'Black' => [
            'Kharif' => [
                'crop' => 'Cotton / Soybean',
                'fertilizer' => 'NPK 12:32:16',
                'watering' => 'Every 10-15 days',
                'duration' => '160-180 days',
                'tips' => 'Black soil retains moisture well. Ensure proper spacing.',
                'pest_control' => 'Watch out for aphids.'
            ],
            'Rabi' => [
                'crop' => 'Gram (Chickpeas)',
                'fertilizer' => 'DAP (100kg/ha)',
                'watering' => 'Every 20-25 days',
                'duration' => '90-110 days',
                'tips' => 'Avoid overwatering to prevent root rot.',
                'pest_control' => 'Spray Neem oil for pod borer.'
            ],
            'Zaid' => [
                'crop' => 'Black Gram',
                'fertilizer' => 'Organic Manure',
                'watering' => 'Every 8-10 days',
                'duration' => '70-80 days',
                'tips' => 'Good for soil health.',
                'pest_control' => 'Watch for whitefly.'
            ]
        ],
        'Alluvial' => [
            'Kharif' => [
                'crop' => 'Rice (Paddy)',
                'fertilizer' => 'Urea & Zinc',
                'watering' => 'Every 3-5 days',
                'duration' => '120-150 days',
                'tips' => 'Keep water level 2-3 inches for first month.',
                'pest_control' => 'Stem borer monitoring is key.'
            ],
            'Rabi' => [
                'crop' => 'Wheat',
                'fertilizer' => 'Urea and DAP',
                'watering' => 'Every 15-20 days',
                'duration' => '120-130 days',
                'tips' => 'Critical stages: CRI and Flowering.',
                'pest_control' => 'Watch for Yellow Rust.'
            ],
            'Zaid' => [
                'crop' => 'Maize (Fodder)',
                'fertilizer' => 'NPK 19:19:19',
                'watering' => 'Every 7-9 days',
                'duration' => '60-70 days',
                'tips' => 'Harvest early for best fodder quality.',
                'pest_control' => 'Armyworm monitoring.'
            ]
        ],
        'Red' => [
            'Kharif' => [
                'crop' => 'Groundnut / Peanut',
                'fertilizer' => 'Gypsum & Super Phosphate',
                'watering' => 'Every 10-12 days',
                'duration' => '100-120 days',
                'tips' => 'Earthing up is necessary for pegging.',
                'pest_control' => 'Watch for Leaf Miner.'
            ],
            'Rabi' => [
                'crop' => 'Mustard',
                'fertilizer' => 'Sulphur & Boron',
                'watering' => 'Every 25-30 days',
                'duration' => '110-120 days',
                'tips' => 'Keep field weed-free during early growth.',
                'pest_control' => 'Aphid control is crucial.'
            ],
            'Zaid' => [
                'crop' => 'Moong (Green Gram)',
                'fertilizer' => 'Bio-fertilizers',
                'watering' => 'Every 7-10 days',
                'duration' => '65-75 days',
                'tips' => 'Short duration crop, improves soil nitrogen.',
                'pest_control' => 'Yellow mosaic virus prevention.'
            ]
        ],
        'Sandy' => [
            'Kharif' => [
                'crop' => 'Bajra (Pearl Millet)',
                'fertilizer' => 'Nitrogen & Zinc',
                'watering' => 'Every 15-18 days',
                'duration' => '80-90 days',
                'tips' => 'Highly drought tolerant crop.',
                'pest_control' => 'Birds protection during maturity.'
            ],
            'Rabi' => [
                'crop' => 'Potato',
                'fertilizer' => 'NPK 15:15:15',
                'watering' => 'Every 8-10 days',
                'duration' => '90-100 days',
                'tips' => 'Cover tubers properly to avoid greening.',
                'pest_control' => 'Late blight protection.'
            ],
            'Zaid' => [
                'crop' => 'Watermelon',
                'fertilizer' => 'Potash & Urea',
                'watering' => 'Every 4-6 days',
                'duration' => '80-90 days',
                'tips' => 'Avoid water on leaves to prevent fungi.',
                'pest_control' => 'Fruit fly management.'
            ]
        ]
    ];

    if (isset($cropData[$soil][$season])) {
        $advice = $cropData[$soil][$season];
    } else {
        $advice = [
            'crop' => 'Finger Millet (Ragi)',
            'fertilizer' => 'Organic Compost',
            'watering' => 'Every 12-15 days',
            'duration' => '110-120 days',
            'tips' => 'Suitable for most soil types with basic care.',
            'pest_control' => 'Neem oil spray for protection.'
        ];
    }
}
?>

<div class="dashboard-header">
    <h2>Crop Advisory</h2>
    <p style="color: var(--text-muted);">Get expert suggestions based on your local conditions.</p>
</div>

<div class="glass-card" style="margin-top: 2rem;">
    <form action="" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; align-items: flex-end;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Soil Type</label>
            <select name="soil" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                <option value="Black" <?php echo $soil == 'Black' ? 'selected' : ''; ?>>Black Soil</option>
                <option value="Alluvial" <?php echo $soil == 'Alluvial' ? 'selected' : ''; ?>>Alluvial Soil</option>
                <option value="Red" <?php echo $soil == 'Red' ? 'selected' : ''; ?>>Red Soil</option>
                <option value="Sandy" <?php echo $soil == 'Sandy' ? 'selected' : ''; ?>>Sandy Soil</option>
            </select>
        </div>
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Current Season</label>
            <select name="season" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ddd;">
                <option value="Kharif" <?php echo $season == 'Kharif' ? 'selected' : ''; ?>>Kharif (Monsoon)</option>
                <option value="Rabi" <?php echo $season == 'Rabi' ? 'selected' : ''; ?>>Rabi (Winter)</option>
                <option value="Zaid" <?php echo $season == 'Zaid' ? 'selected' : ''; ?>>Zaid (Summer)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 48px;">Get Suggestion</button>
    </form>
</div>

<?php if ($advice): ?>
<div class="stats-grid" style="margin-top: 2rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="stat-card" style="border-left: 5px solid var(--primary);">
        <div class="stat-label">Recommended Crop</div>
        <div class="stat-value" style="color: var(--primary); font-size: 1.5rem;"><?php echo $advice['crop']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fas fa-droplet" style="color: #3498db;"></i> Watering</div>
        <div class="stat-value" style="font-size: 1.2rem; color: #3498db;"><?php echo $advice['watering']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fas fa-calendar-check" style="color: #e67e22;"></i> Harvest Time</div>
        <div class="stat-value" style="font-size: 1.2rem; color: #e67e22;"><?php echo $advice['duration']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Fertilizer Guide</div>
        <div class="stat-value" style="font-size: 1.1rem;"><?php echo $advice['fertilizer']; ?></div>
    </div>
</div>

<div class="glass-card" style="margin-top: 2rem;">
    <h3>Farming Tips</h3>
    <div style="margin-top: 1rem;">
        <p><strong><i class="fas fa-info-circle"></i> Method:</strong> <?php echo $advice['tips']; ?></p>
        <p style="margin-top: 10px;"><strong><i class="fas fa-bug"></i> Protection:</strong> <?php echo $advice['pest_control']; ?></p>
    </div>
</div>
<?php else: ?>
<div style="text-align: center; padding: 4rem; color: var(--text-muted);">
    <i class="fas fa-seedling" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
    <p>Select your soil type and season to get customized advice.</p>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
