<?php
session_start();
require '../../backend/db.php';

// Get the gig ID from the URL
$gig_id = $_GET['id'];

// Fetch the gig details from database
$sql = "SELECT gigs.*, categories.name as category_name FROM gigs 
        JOIN categories ON gigs.category_id = categories.id 
        WHERE gigs.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$gig_id]);
$gig = $stmt->fetch();

// If gig not found redirect to gigs page
if (!$gig) {
    header("Location: gigs.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge - <?php echo $gig['title']; ?></title>
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

    <div class="gig-detail-container">
    <a href="gigs.php" class="back-btn">Back to Gigs</a>

    <div class="gig-detail-card">
        <span class="gig-category"><?php echo $gig['category_name']; ?></span>
        <h1><?php echo $gig['title']; ?></h1>
        <p>Posted by: <a href="view-profile.php?id=<?php echo $gig['employer_id']; ?>">View Employer Profile</a></p>
        <div class="gig-detail-meta">
            <span>Location: <?php echo $gig['location']; ?></span>
            <span>Pay: <?php echo $gig['pay']; ?></span>
            <span>Duration: <?php echo $gig['duration']; ?></span>
        </div>

        <hr>

        <h3>About this Gig</h3>
        <p><?php echo $gig['description']; ?></p>

        <h3>Requirements</h3>
        <p><?php echo $gig['requirements']; ?></p>

        <hr>

        <?php if(isset($_SESSION['user_id'])): ?>
            <?php
            $check = $pdo->prepare("SELECT id FROM applications WHERE gig_id = ? AND student_id = ?");
            $check->execute([$gig['id'], $_SESSION['user_id']]);
            $already_applied = $check->fetch();
            ?>

            <?php if($already_applied): ?>
                <p class="applied-msg">You have already applied for this gig.</p>
            <?php else: ?>
                <h3>Apply for this Gig</h3>
                <form action="../../backend/apply.php" method="POST">
                    <input type="hidden" name="gig_id" value="<?php echo $gig['id']; ?>">
                    <div class="form-group">
                        <label>Cover Note</label>
                        <textarea name="cover_note" rows="4" placeholder="Tell the employer why you are the right person for this gig..."></textarea>
                    </div>
                    <button type="submit">Submit Application</button>
                </form>
            <?php endif; ?>

        <?php else: ?>
            <p>You need to <a href="login.php">login</a> to apply for this gig.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>