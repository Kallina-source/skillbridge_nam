<?php
session_start();

// Protect the page - only logged in users can access
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
    <title>SkillBridge - Post a Gig</title>
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

   <div class="post-gig-container">
    <h2>Post a Gig</h2>
    <p>Fill in the details below to post a new opportunity.</p>

    <!-- Form sends data to backend -->
    <form action="../../backend/post-gig.php" method="POST">
        
        <div class="form-group">
            <label>Gig Title</label>
            <input type="text" name="title" placeholder="e.g. Need 2 waiters for Saturday event" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">Select a category</option>
                <option value="1">Events</option>
                <option value="2">Tutoring</option>
                <option value="3">Cleaning</option>
                <option value="4">Photography</option>
                <option value="5">Delivery</option>
                <option value="6">Retail</option>
                <option value="7">Admin</option>
                <option value="8">Creative</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Describe the gig in detail..." required></textarea>
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" placeholder="e.g. Windhoek, Katutura" required>
        </div>

        <div class="form-group">
            <label>Pay</label>
            <input type="text" name="pay" placeholder="e.g. N$150 per day" required>
        </div>

        <div class="form-group">
            <label>Duration</label>
            <input type="text" name="duration" placeholder="e.g. 1 day, Weekend, 2 weeks" required>
        </div>

        <div class="form-group">
            <label>Requirements</label>
            <textarea name="requirements" rows="3" placeholder="e.g. Must be reliable, own transport preferred"></textarea>
        </div>

        <button type="submit">Post Gig</button>
    </form>
</div>

</body>
</html>