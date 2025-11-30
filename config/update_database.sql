-- This script is for updating an existing database to the latest schema.
-- Please run this script only ONCE on your existing database.
-- It is highly recommended to take a backup before running this script.

-- Note: Some of these commands might fail if the columns or tables already exist.
-- You can safely ignore any "Duplicate column name" or "Table already exists" errors.

-- Add new columns to the users table
ALTER TABLE `users` ADD COLUMN `initiated_name` VARCHAR(100) NULL;
ALTER TABLE `users` ADD COLUMN `gender` ENUM('Male', 'Female') NOT NULL;
ALTER TABLE `users` ADD COLUMN `photo` VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN `date_of_birth` DATE NOT NULL;
ALTER TABLE `users` ADD COLUMN `marital_status` ENUM('Single', 'Married', 'Divorced', 'Other') NOT NULL;
ALTER TABLE `users` ADD COLUMN `marriage_anniversary_date` DATE NULL;
ALTER TABLE `users` ADD COLUMN `email` VARCHAR(100) NULL;
ALTER TABLE `users` ADD COLUMN `address` TEXT NOT NULL;
ALTER TABLE `users` ADD COLUMN `city` VARCHAR(100) NOT NULL;
ALTER TABLE `users` ADD COLUMN `state` VARCHAR(100) NOT NULL;
ALTER TABLE `users` ADD COLUMN `pincode` VARCHAR(20) NOT NULL;
ALTER TABLE `users` ADD COLUMN `country` VARCHAR(100) NULL DEFAULT 'India';
ALTER TABLE `users` ADD COLUMN `education_id` INT NULL;
ALTER TABLE `users` ADD COLUMN `profession_id` INT NULL;
ALTER TABLE `users` ADD COLUMN `bhakti_sadan_id` INT NULL;
ALTER TABLE `users` ADD COLUMN `life_member_no` VARCHAR(100) NULL;
ALTER TABLE `users` ADD COLUMN `life_member_temple` VARCHAR(100) NULL;
ALTER TABLE `users` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Create new lookup tables if they don't exist
CREATE TABLE IF NOT EXISTS `spiritual_masters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `blood_groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `languages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `sevas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);


-- Add foreign key constraints
-- These may fail if they already exist. You can ignore those errors.
ALTER TABLE `users` ADD CONSTRAINT `fk_education` FOREIGN KEY (`education_id`) REFERENCES `educations`(`id`);
ALTER TABLE `users` ADD CONSTRAINT `fk_profession` FOREIGN KEY (`profession_id`) REFERENCES `professions`(`id`);
ALTER TABLE `users` ADD CONSTRAINT `fk_bhakti_sadan` FOREIGN KEY (`bhakti_sadan_id`) REFERENCES `bhakti_sadans`(`id`);


-- Create new relational tables if they do not exist
CREATE TABLE IF NOT EXISTS `dependants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `name` VARCHAR(100) NOT NULL,
    `age` INT,
    `gender` ENUM('Male', 'Female'),
    `date_of_birth` DATE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE IF NOT EXISTS `user_languages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `language_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`language_id`) REFERENCES `languages`(`id`)
);

CREATE TABLE IF NOT EXISTS `user_sevas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `seva_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`seva_id`) REFERENCES `sevas`(`id`)
);

CREATE TABLE IF NOT EXISTS `bhakti_sadan_leaders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `bhakti_sadan_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`bhakti_sadan_id`) REFERENCES `bhakti_sadans`(`id`)
);
