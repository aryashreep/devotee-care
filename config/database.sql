-- Drop tables if they exist
DROP TABLE IF EXISTS bhakti_sadan_leaders, bhakti_sadans, users, roles, educations, professions, spiritual_masters, blood_groups, languages, sevas;

-- Create the roles table
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

-- Insert the predefined roles
INSERT INTO roles (role_name) VALUES
('Admin'),
('Management'),
('Finance'),
('Editor'),
('End User');

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    mobile_number VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    bhakti_sadan_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Create the bhakti_sadans table
CREATE TABLE bhakti_sadans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT
);

-- Create the bhakti_sadan_leaders table
CREATE TABLE bhakti_sadan_leaders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bhakti_sadan_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (bhakti_sadan_id) REFERENCES bhakti_sadans(id)
);

-- Create the educations table
CREATE TABLE educations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create the professions table
CREATE TABLE professions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Insert a default admin user
INSERT INTO users (full_name, mobile_number, email, password, role_id) VALUES
('Admin User', '1234567890', 'admin@example.com', '$2y$10$GRE0di5KCQnwxEWKhATEueoLEhfUKSC046hdAsDEElCvW4pCDWhjG', 1);

-- Create the spiritual_masters table
CREATE TABLE spiritual_masters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create the blood_groups table
CREATE TABLE blood_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create the languages table
CREATE TABLE languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create the sevas table
CREATE TABLE sevas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
