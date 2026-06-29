<?php
session_start();
require 'db.php';

// Must be logged in to apply
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gig_id = $_POST['gig_id'];
    $student_id = $_SESSION['user_id'];
    $cover_note = trim($_POST['cover_note']);

    // Check if already applied
    $check = $pdo->prepare("SELECT id FROM applications WHERE gig_id = ? AND student_id = ?");
    $check->execute([$gig_id, $student_id]);
    
    if ($check->fetch()) {
        die("You have already applied for this gig.");
    }

    // Save application
    $sql = "INSERT INTO applications (gig_id, student_id, cover_note) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gig_id, $student_id, $cover_note]);

    header("Location: http://localhost/skillbridge_nam/frontend/pages/gigs.php");
    exit();
}
?>