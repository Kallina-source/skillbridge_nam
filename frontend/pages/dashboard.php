<?php
session_start();
require '../../backend/db.php';

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge - Dashboard</title>
    <link rel="stylesheet" href="../css/Styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav>
<div class="logo"><i class="fas fa-briefcase"></i> SkillBridge</div>
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

   <div class="dashboard-container">
    <div class="welcome-banner">
        <h2>Welcome back, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>You are logged in as a <?php echo $_SESSION['user_type']; ?>. Start exploring gigs or update your profile.</p>
    <a href="view-profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="view-profile-btn">View My Profile</a>
    </div>

    <!-- QUICK STATS -->
<div class="dashboard-stats">
    <?php if($_SESSION['user_type'] == 'student'): ?>
        <?php
        $count = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
        $count->execute([$_SESSION['user_id']]);
        $total_applications = $count->fetchColumn();
        ?>
        <div class="stat-card">
            <h4><?php echo $total_applications; ?></h4>
            <p>Gigs Applied</p>
        </div>
        <div class="stat-card">
            <a href="gigs.php">Browse Gigs</a>
        </div>
        <div class="stat-card">
            <a href="profile.php">Edit Profile</a>
        </div>
    <?php elseif($_SESSION['user_type'] == 'employer'): ?>
        <?php
        $count = $pdo->prepare("SELECT COUNT(*) FROM gigs WHERE employer_id = ?");
        $count->execute([$_SESSION['user_id']]);
        $total_gigs = $count->fetchColumn();
        ?>
        <div class="stat-card">
            <h4><?php echo $total_gigs; ?></h4>
            <p>Gigs Posted</p>
        </div>
        <div class="stat-card">
            <a href="post-gig.php">Post a Gig</a>
        </div>
        <div class="stat-card">
            <a href="profile.php">Edit Profile</a>
        </div>
    <?php endif; ?>
</div>

    <?php if($_SESSION['user_type'] == 'student'): ?>
    
    <!-- STUDENT DASHBOARD -->
    <div class="dashboard-section">
        <h3>My Applications</h3>
        <?php
        $sql = "SELECT applications.*, gigs.title, gigs.location, gigs.pay 
                FROM applications 
                JOIN gigs ON applications.gig_id = gigs.id 
                WHERE applications.student_id = ?
                ORDER BY applications.applied_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $applications = $stmt->fetchAll();
        ?>

        <?php if(count($applications) > 0): ?>
            <?php foreach($applications as $app): ?>
                <div class="dashboard-card">
                    <h4><?php echo $app['title']; ?></h4>
                    <p>Location: <?php echo $app['location']; ?> | Pay: <?php echo $app['pay']; ?></p>
                    <span class="status-badge <?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-msg">You have not applied for any gigs yet. <a href="gigs.php">Browse Gigs</a></p>
        <?php endif; ?>
    </div>

    <?php elseif($_SESSION['user_type'] == 'employer'): ?>

    <!-- EMPLOYER DASHBOARD -->
    <div class="dashboard-section">
        <h3>My Posted Gigs</h3>
        <?php
        $sql = "SELECT * FROM gigs WHERE employer_id = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $my_gigs = $stmt->fetchAll();
        ?>

        <?php if(count($my_gigs) > 0): ?>
            <?php foreach($my_gigs as $gig): ?>
                <div class="dashboard-card">
    <h4><?php echo $gig['title']; ?></h4>
    <p>Location: <?php echo $gig['location']; ?> | Pay: <?php echo $gig['pay']; ?></p>
    <span class="status-badge <?php echo $gig['status']; ?>"><?php echo ucfirst($gig['status']); ?></span>
    <a href="applicants.php?gig_id=<?php echo $gig['id']; ?>" class="view-applicants-btn">View Applicants</a>
</div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-msg">You have not posted any gigs yet. <a href="post-gig.php">Post a Gig</a></p>
        <?php endif; ?>
    </div>

    <?php endif; ?>
</div>
</body>
</html>