<?php
session_start();
require '../../backend/db.php';

// Fetch all open gigs from database
$sql = "SELECT gigs.*, categories.name as category_name FROM gigs 
        JOIN categories ON gigs.category_id = categories.id 
        WHERE gigs.status = 'open' 
        ORDER BY gigs.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
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

        <div class="gigs-grid">
            <?php if(count($gigs) > 0): ?>
                <?php foreach($gigs as $gig): ?>
                    <div class="gig-card">
                        <span class="gig-category"><?php echo $gig['category_name']; ?></span>
                        <h3><?php echo $gig['title']; ?></h3>
                        <p><?php echo substr($gig['description'], 0, 100) . '...'; ?></p>
                        <div class="gig-meta">
                            <span> <?php echo $gig['location']; ?></span>
                            <span> <?php echo $gig['pay']; ?></span>
                            <span> <?php echo $gig['duration']; ?></span>
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