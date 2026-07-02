<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav>
<div class="logo"><i class="fas fa-briefcase"></i> SkillBridge</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="gigs.php">Find Gigs</a></li>
            <li><a href="post-gig.php">Post a Gig</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
<div class="login-container">
    <h2>Login to Your Account</h2>
    <p>Welcome back! Please enter your credentials to access your account.</p>

/* This form sends data to the login PHP file */

<form action="../../backend/login.php" method="POST">        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>