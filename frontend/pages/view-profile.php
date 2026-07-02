<?php
session_start();
require '../../backend/db.php';

// Get user ID from URL
$profile_id = $_GET['id'];

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: gigs.php");
    exit();
}

// Fetch student or employer profile
if($user['user_type'] == 'student') {
    $sql = "SELECT * FROM student_profiles WHERE user_id = ?";
} else {
    $sql = "SELECT * FROM employer_profiles WHERE user_id = ?";
}
$stmt = $pdo->prepare($sql);
$stmt->execute([$profile_id]);
$profile = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge - <?php echo $user['full_name']; ?></title>
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

    <div class="view-profile-container">
    <div class="view-profile-card">
        
        <!-- Profile Header -->
        <div class="view-profile-header">
            <?php if(!empty($user['profile_picture'])): ?>
                <img src="../../assets/uploads/<?php echo $user['profile_picture']; ?>" class="profile-pic-preview">
            <?php else: ?>
                <div class="profile-pic-placeholder"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
            <?php endif; ?>
            <div class="view-profile-info">
                <h2><?php echo $user['full_name']; ?></h2>
                <p><?php echo ucfirst($user['user_type']); ?></p>
                <?php if(!empty($user['location'])): ?>
                    <p> <?php echo $user['location']; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <hr>

        <!-- Profile Details -->
        <?php if($user['user_type'] == 'student' && $profile): ?>
            <?php if(!empty($profile['bio'])): ?>
                <h3>About</h3>
                <p><?php echo $profile['bio']; ?></p>
            <?php endif; ?>

            <?php if(!empty($profile['skills'])): ?>
                <h3>Skills</h3>
                <p><?php echo $profile['skills']; ?></p>
            <?php endif; ?>

            <?php if(!empty($profile['university'])): ?>
                <h3>Education</h3>
                <p><?php echo $profile['university']; ?> — <?php echo $profile['course']; ?> (<?php echo $profile['year_of_study']; ?>)</p>
            <?php endif; ?>

        <?php elseif($user['user_type'] == 'employer' && $profile): ?>
            <?php if(!empty($profile['company_name'])): ?>
                <h3>Company</h3>
                <p><?php echo $profile['company_name']; ?></p>
            <?php endif; ?>

            <?php if(!empty($profile['industry'])): ?>
                <h3>Industry</h3>
                <p><?php echo $profile['industry']; ?></p>
            <?php endif; ?>

            <?php if(!empty($profile['bio'])): ?>
                <h3>About</h3>
                <p><?php echo $profile['bio']; ?></p>
            <?php endif; ?>

            <?php if(!empty($profile['website'])): ?>
                <h3>Website</h3>
                <a href="<?php echo $profile['website']; ?>" target="_blank"><?php echo $profile['website']; ?></a>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

</body>
</html>