<?php
session_start();
require '../../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch student or employer profile
if($_SESSION['user_type'] == 'student') {
    $sql = "SELECT * FROM student_profiles WHERE user_id = ?";
} else {
    $sql = "SELECT * FROM employer_profiles WHERE user_id = ?";
}
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$profile = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge - Profile</title>
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

    <div class="profile-container">
    <h2>My Profile</h2>
    <p>Update your profile information below.</p>

    <form action="../../backend/update-profile.php" method="POST" enctype="multipart/form-data">
        
        <!-- Profile Picture -->
        <div class="profile-pic-section">
            <?php if(!empty($user['profile_picture'])): ?>
                <img src="../../assets/uploads/<?php echo $user['profile_picture']; ?>" class="profile-pic-preview">
            <?php else: ?>
                <div class="profile-pic-placeholder">No Photo</div>
            <?php endif; ?>
            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>
        </div>

        <!-- Basic Info -->
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Your phone number">
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" value="<?php echo $user['location']; ?>" placeholder="e.g. Windhoek">
        </div>

        <?php if($_SESSION['user_type'] == 'student'): ?>
        <!-- Student Fields -->
        <div class="form-group">
            <label>University</label>
            <input type="text" name="university" value="<?php echo $profile['university'] ?? ''; ?>" placeholder="e.g. UNAM, NUST">
        </div>

        <div class="form-group">
            <label>Course</label>
            <input type="text" name="course" value="<?php echo $profile['course'] ?? ''; ?>" placeholder="e.g. Computer Science">
        </div>

        <div class="form-group">
            <label>Year of Study</label>
            <input type="text" name="year_of_study" value="<?php echo $profile['year_of_study'] ?? ''; ?>" placeholder="e.g. 2nd Year">
        </div>

        <div class="form-group">
            <label>Skills</label>
            <input type="text" name="skills" value="<?php echo $profile['skills'] ?? ''; ?>" placeholder="e.g. Photography, Graphic Design, Tutoring">
        </div>

        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="4" placeholder="Tell employers about yourself..."><?php echo $profile['bio'] ?? ''; ?></textarea>
        </div>

        <?php elseif($_SESSION['user_type'] == 'employer'): ?>
        <!-- Employer Fields -->
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" value="<?php echo $profile['company_name'] ?? ''; ?>" placeholder="Your company or business name">
        </div>

        <div class="form-group">
            <label>Industry</label>
            <input type="text" name="industry" value="<?php echo $profile['industry'] ?? ''; ?>" placeholder="e.g. Events, Retail, Education">
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="text" name="website" value="<?php echo $profile['website'] ?? ''; ?>" placeholder="e.g. www.yourcompany.com">
        </div>

        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="4" placeholder="Tell students about your company..."><?php echo $profile['bio'] ?? ''; ?></textarea>
        </div>

        <?php endif; ?>

        <button type="submit">Save Profile</button>
    </form>
</div>

    </div>

</body>
</html>