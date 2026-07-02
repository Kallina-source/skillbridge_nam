<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);

    // Handle profile picture upload
    $profile_picture = null;
    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = '../assets/uploads/';
        $filename = time() . '_' . $_FILES['profile_picture']['name'];
        $upload_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
            $profile_picture = $filename;
        }
    }

    // Update users table
    if ($profile_picture) {
        $sql = "UPDATE users SET full_name=?, phone=?, location=?, profile_picture=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$full_name, $phone, $location, $profile_picture, $user_id]);
    } else {
        $sql = "UPDATE users SET full_name=?, phone=?, location=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$full_name, $phone, $location, $user_id]);
    }

    // Update or insert student/employer profile
    if ($_SESSION['user_type'] == 'student') {
        $university = trim($_POST['university']);
        $course = trim($_POST['course']);
        $year_of_study = trim($_POST['year_of_study']);
        $skills = trim($_POST['skills']);
        $bio = trim($_POST['bio']);

        // Check if profile exists
        $check = $pdo->prepare("SELECT id FROM student_profiles WHERE user_id = ?");
        $check->execute([$user_id]);

        if ($check->fetch()) {
            $sql = "UPDATE student_profiles SET university=?, course=?, year_of_study=?, skills=?, bio=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$university, $course, $year_of_study, $skills, $bio, $user_id]);
        } else {
            $sql = "INSERT INTO student_profiles (user_id, university, course, year_of_study, skills, bio) VALUES (?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $university, $course, $year_of_study, $skills, $bio]);
        }

    } elseif ($_SESSION['user_type'] == 'employer') {
        $company_name = trim($_POST['company_name']);
        $industry = trim($_POST['industry']);
        $website = trim($_POST['website']);
        $bio = trim($_POST['bio']);

        $check = $pdo->prepare("SELECT id FROM employer_profiles WHERE user_id = ?");
        $check->execute([$user_id]);

        if ($check->fetch()) {
            $sql = "UPDATE employer_profiles SET company_name=?, industry=?, website=?, bio=? WHERE user_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$company_name, $industry, $website, $bio, $user_id]);
        } else {
            $sql = "INSERT INTO employer_profiles (user_id, company_name, industry, website, bio) VALUES (?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $company_name, $industry, $website, $bio]);
        }
    }

    // Update session name
    $_SESSION['user_name'] = $full_name;

    header("Location: http://localhost/skillbridge_nam/frontend/pages/profile.php");
    exit();
}
?>