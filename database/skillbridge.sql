-- SkillBridge Database Schema

-- 1. Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('student', 'employer') NOT NULL,
    profile_picture VARCHAR(255),
    phone VARCHAR(20),
    location VARCHAR(100),
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Student Profiles
CREATE TABLE student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    university VARCHAR(100),
    course VARCHAR(100),
    year_of_study VARCHAR(20),
    bio TEXT,
    skills TEXT,
    availability VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 3. Employer Profiles
CREATE TABLE employer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(100),
    industry VARCHAR(100),
    website VARCHAR(150),
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 4. Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    icon VARCHAR(50)
);

-- 5. Gigs
CREATE TABLE gigs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(100),
    pay VARCHAR(50),
    duration VARCHAR(50),
    requirements TEXT,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- 6. Applications
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gig_id INT NOT NULL,
    student_id INT NOT NULL,
    cover_note TEXT,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gig_id) REFERENCES gigs(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);