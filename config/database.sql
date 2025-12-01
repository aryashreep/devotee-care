-- This script is for setting up the database for a fresh installation.
-- It uses `CREATE TABLE IF NOT EXISTS` to avoid errors if the database is already partially set up.

-- Create the roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

-- Insert the predefined roles only if they don't exist
INSERT IGNORE INTO roles (role_name) VALUES
('Admin'),
('Management'),
('Finance'),
('Editor'),
('End User');

-- Create the lookup tables
CREATE TABLE IF NOT EXISTS educations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS professions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS blood_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS sevas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS bhakti_sadans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS shiksha_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

INSERT IGNORE INTO shiksha_levels (name) VALUES
('Yet to accept'),
('Shraddhavan'),
('Krishna Sevaka'),
('Krishna Sadhaka'),
('Krishna Upasaka'),
('Srila Prabhupada ashraya'),
('Sri Guru-pada-ashraya'),
('Diksha');


-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    photo VARCHAR(255),
    date_of_birth DATE NOT NULL,
    marital_status ENUM('Single', 'Married', 'Divorced', 'Other') NOT NULL,
    marriage_anniversary_date DATE,
    password VARCHAR(255) NOT NULL,

    email VARCHAR(100),
    mobile_number VARCHAR(20) NOT NULL UNIQUE,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    pincode VARCHAR(20) NOT NULL,
    country VARCHAR(100) DEFAULT 'India',

    education_id INT,
    profession_id INT,
    blood_group_id INT NULL,

    is_initiated ENUM('Yes', 'No'),
    spiritual_master_name VARCHAR(255),
    chanting_rounds INT,
    second_initiation ENUM('Yes', 'No'),
    bhakti_sadan_id INT,
    has_life_membership ENUM('Yes', 'No'),
    life_member_no VARCHAR(100),
    life_member_temple VARCHAR(100),

    role_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (education_id) REFERENCES educations(id),
    FOREIGN KEY (profession_id) REFERENCES professions(id),
    FOREIGN KEY (bhakti_sadan_id) REFERENCES bhakti_sadans(id),
    FOREIGN KEY (blood_group_id) REFERENCES blood_groups(id)
);

-- Insert a default admin user if one doesn't exist
INSERT IGNORE INTO users (
    full_name, gender, date_of_birth, marital_status, password, mobile_number,
    address, city, state, pincode, role_id
) VALUES (
    'Admin User', 'Male', '1990-01-01', 'Single',
    '$2y$10$GRE0di5KCQnwxEWKhATEueoLEhfUKSC046hdAsDEElCvW4pCDWhjG', '1234567890',
    'N/A', 'N/A', 'N/A', 'N/A', 1
);

-- Create the user_languages table
CREATE TABLE IF NOT EXISTS user_languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    language_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
);

-- Create the user_sevas table
CREATE TABLE IF NOT EXISTS user_sevas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    seva_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seva_id) REFERENCES sevas(id) ON DELETE CASCADE
);

-- Create the user_shiksha_levels table
CREATE TABLE IF NOT EXISTS user_shiksha_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    shiksha_level_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shiksha_level_id) REFERENCES shiksha_levels(id) ON DELETE CASCADE
);

-- Create the dependants table
CREATE TABLE IF NOT EXISTS dependants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('Male', 'Female'),
    date_of_birth DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create the bhakti_sadan_leaders table
CREATE TABLE IF NOT EXISTS bhakti_sadan_leaders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bhakti_sadan_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (bhakti_sadan_id) REFERENCES bhakti_sadans(id) ON DELETE CASCADE
);
