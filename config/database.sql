-- Drop tables if they exist
DROP TABLE IF EXISTS bhakti_sadan_leaders, user_languages, user_sevas, dependants, users, roles, educations, professions, spiritual_masters, blood_groups, languages, sevas, bhakti_sadans;

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

-- Create the bhakti_sadans table
CREATE TABLE bhakti_sadans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    initiated_name VARCHAR(100),
    gender ENUM('Male', 'Female') NOT NULL,
    photo VARCHAR(255),
    date_of_birth DATE NOT NULL,
    marital_status ENUM('Single', 'Married', 'Divorced', 'Other') NOT NULL,
    marriage_anniversary_date DATE,
    email VARCHAR(100),
    mobile_number VARCHAR(20) NOT NULL UNIQUE,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    pincode VARCHAR(20) NOT NULL,
    country VARCHAR(100) DEFAULT 'India',
    education_id INT,
    profession_id INT,
    bhakti_sadan_id INT,
    life_member_no VARCHAR(100),
    life_member_temple VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (education_id) REFERENCES educations(id),
    FOREIGN KEY (profession_id) REFERENCES professions(id),
    FOREIGN KEY (bhakti_sadan_id) REFERENCES bhakti_sadans(id)
);

-- Insert a default admin user
INSERT INTO users (
    full_name, gender, date_of_birth, marital_status, mobile_number,
    address, city, state, pincode, password, role_id
) VALUES (
    'Admin User', 'Male', '1990-01-01', 'Single', '1234567890',
    'N/A', 'N/A', 'N/A', 'N/A',
    '$2y$10$GRE0di5KCQnwxEWKhATEueoLEhfUKSC046hdAsDEElCvW4pCDWhjG', 1
);

-- Create the user_languages table
CREATE TABLE user_languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    language_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (language_id) REFERENCES languages(id)
);

-- Create the user_sevas table
CREATE TABLE user_sevas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    seva_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (seva_id) REFERENCES sevas(id)
);

-- Create the dependants table
CREATE TABLE dependants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('Male', 'Female'),
    date_of_birth DATE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create the bhakti_sadan_leaders table
CREATE TABLE bhakti_sadan_leaders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bhakti_sadan_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (bhakti_sadan_id) REFERENCES bhakti_sadans(id)
);
