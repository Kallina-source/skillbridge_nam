<?php
session_start();

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

   <div class="dashboard-container">
    <div class="welcome-banner">
        <h2>Welcome back, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>You are logged in as a <?php echo $_SESSION['user_type']; ?>. Start exploring gigs or update your profile.</p>
    </div>
</div>
</body>
</html>