<?php
session_start();
require '../../backend/db.php';

// Handle search and filter
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$category = isset($_GET['category']) && $_GET['category'] != '' ? $_GET['category'] : null;

if ($category) {
    $sql = "SELECT gigs.*, categories.name as category_name FROM gigs 
            JOIN categories ON gigs.category_id = categories.id 
            WHERE gigs.status = 'open' 
            AND gigs.category_id = ?
            AND (gigs.title LIKE ? OR gigs.description LIKE ?)
            ORDER BY gigs.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category, $search, $search]);
} else {
    $sql = "SELECT gigs.*, categories.name as category_name FROM gigs 
            JOIN categories ON gigs.category_id = categories.id 
            WHERE gigs.status = 'open' 
            AND (gigs.title LIKE ? OR gigs.description LIKE ?)
            ORDER BY gigs.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search, $search]);
}
$gigs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge - Find Gigs</title>
<link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav>
        <div class="logo">SkillBridge</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="gigs.php">Find Gigs</a></li>
            <li><a href="post-gig.php">Post a Gig</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="../../backend/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="gigs-container">
        <h2>Available Gigs</h2>
        <p>Browse and apply for flexible opportunities near you.</p>

<div class="search-filter">
    <form method="GET" action="gigs.php">
        <input type="text" name="search" placeholder="Search gigs..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <select name="category">
            <option value="">All Categories</option>
            <option value="1" <?php echo (isset($_GET['category']) && $_GET['category'] == '1') ? 'selected' : ''; ?>>Events</option>
            <option value="2" <?php echo (isset($_GET['category']) && $_GET['category'] == '2') ? 'selected' : ''; ?>>Tutoring</option>
            <option value="3" <?php echo (isset($_GET['category']) && $_GET['category'] == '3') ? 'selected' : ''; ?>>Cleaning</option>
            <option value="4" <?php echo (isset($_GET['category']) && $_GET['category'] == '4') ? 'selected' : ''; ?>>Photography</option>
            <option value="5" <?php echo (isset($_GET['category']) && $_GET['category'] == '5') ? 'selected' : ''; ?>>Delivery</option>
            <option value="6" <?php echo (isset($_GET['category']) && $_GET['category'] == '6') ? 'selected' : ''; ?>>Retail</option>
            <option value="7" <?php echo (isset($_GET['category']) && $_GET['category'] == '7') ? 'selected' : ''; ?>>Admin</option>
            <option value="8" <?php echo (isset($_GET['category']) && $_GET['category'] == '8') ? 'selected' : ''; ?>>Creative</option>
        </select>
        <button type="submit">Search</button>
        <a href="gigs.php">Clear</a>
    </form>
</div>

        <div class="gigs-grid">
            <?php if(count($gigs) > 0): ?>
                <?php foreach($gigs as $gig): ?>
                    <div class="gig-card">
                        <span class="gig-category"><?php echo $gig['category_name']; ?></span>
                        <h3><?php echo $gig['title']; ?></h3>
                        <p><?php echo substr($gig['description'], 0, 100) . '...'; ?></p>
                        <div class="gig-meta">
                            <span>Location: <?php echo $gig['location']; ?></span>
                            <span>Pay: <?php echo $gig['pay']; ?></span>
                            <span>Duration: <?php echo $gig['duration']; ?></span>
                        </div>
                        <a href="gig-detail.php?id=<?php echo $gig['id']; ?>" class="apply-btn">View & Apply</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-gigs">No gigs available yet. Check back soon!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>