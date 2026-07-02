<?php
session_start();
require '../../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Only employers can view applicants
if ($_SESSION['user_type'] != 'employer') {
    header("Location: dashboard.php");
    exit();
}

$gig_id = $_GET['gig_id'];

// Fetch gig details
$sql = "SELECT * FROM gigs WHERE id = ? AND employer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$gig_id, $_SESSION['user_id']]);
$gig = $stmt->fetch();

if (!$gig) {
    header("Location: dashboard.php");
    exit();
}

// Fetch all applicants
$sql = "SELECT applications.*, users.full_name, users.email, users.phone, users.location
        FROM applications 
        JOIN users ON applications.student_id = users.id
        WHERE applications.gig_id = ?
        ORDER BY applications.applied_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$gig_id]);
$applicants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge - Applicants</title>
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

    <div class="applicants-container">
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        
        <div class="applicants-card">
            <h2>Applicants for: <?php echo $gig['title']; ?></h2>
            <p><?php echo count($applicants); ?> application(s) received</p>

            <hr>

            <?php if(count($applicants) > 0): ?>
                <?php foreach($applicants as $applicant): ?>
                    <div class="applicant-card">
                        <div class="applicant-info">
                            <h4><?php echo $applicant['full_name']; ?></h4>
                            <p>Email: <?php echo $applicant['email']; ?></p>
                            <?php if(!empty($applicant['phone'])): ?>
                                <p>Phone: <?php echo $applicant['phone']; ?></p>
                            <?php endif; ?>
                            <?php if(!empty($applicant['location'])): ?>
                                <p>Location: <?php echo $applicant['location']; ?></p>
                            <?php endif; ?>
                            <?php if(!empty($applicant['cover_note'])): ?>
                                <p><strong>Cover Note:</strong> <?php echo $applicant['cover_note']; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="applicant-actions">
                            <span class="status-badge <?php echo $applicant['status']; ?>"><?php echo ucfirst($applicant['status']); ?></span>
                            <a href="view-profile.php?id=<?php echo $applicant['student_id']; ?>" class="view-applicants-btn">View Profile</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-msg">No applications received yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>