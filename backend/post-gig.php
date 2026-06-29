<?php
session_start();
require 'db.php';

// Only logged in users can post gigs
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employer_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $pay = trim($_POST['pay']);
    $duration = trim($_POST['duration']);
    $requirements = trim($_POST['requirements']);

    // Save gig to database
    $sql = "INSERT INTO gigs (employer_id, category_id, title, description, location, pay, duration, requirements) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employer_id, $category_id, $title, $description, $location, $pay, $duration, $requirements]);

    header("Location: http://localhost/skillbridge_nam/frontend/pages/gigs.php");
    exit();
}
?>