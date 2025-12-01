-- This script is for updating an existing database to the latest schema.
-- Please run this script only ONCE on your existing database.
-- It is highly recommended to take a backup before running this script.

-- Note: Some of these commands might fail if the columns, tables, or constraints already exist.
-- You can safely ignore any "Duplicate column name", "Table already exists", or "Duplicate foreign key" errors.

-- Drop the now-unused spiritual_masters table
DROP TABLE IF EXISTS `spiritual_masters`;

-- Remove the old initiated_name column
ALTER TABLE `users` DROP COLUMN `initiated_name`;

-- Add new columns to the users table for the expanded registration flow
ALTER TABLE `users` ADD COLUMN `blood_group_id` INT NULL;
ALTER TABLE `users` ADD COLUMN `is_initiated` ENUM('Yes', 'No') NULL;
ALTER TABLE `users` ADD COLUMN `spiritual_master_name` VARCHAR(255) NULL;
ALTER TABLE `users` ADD COLUMN `chanting_rounds` INT NULL;
ALTER TABLE `users` ADD COLUMN `second_initiation` ENUM('Yes', 'No') NULL;
ALTER TABLE `users` ADD COLUMN `has_life_membership` ENUM('Yes', 'No') NULL;
ALTER TABLE `users` ADD `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `blood_group_id`;

-- Create the new shiksha_levels table
CREATE TABLE IF NOT EXISTS `shiksha_levels` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE
);

-- Insert the default shiksha levels
INSERT IGNORE INTO shiksha_levels (name) VALUES
('Yet to accept'),
('Shraddhavan'),
('Krishna Sevaka'),
('Krishna Sadhaka'),
('Krishna Upasaka'),
('Srila Prabhupada ashraya'),
('Sri Guru-pada-ashraya'),
('Diksha');


-- Create the new user_shiksha_levels table
CREATE TABLE IF NOT EXISTS `user_shiksha_levels` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `shiksha_level_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`shiksha_level_id`) REFERENCES `shiksha_levels`(`id`) ON DELETE CASCADE
);

-- Add the new foreign key constraint
ALTER TABLE `users` ADD CONSTRAINT `fk_blood_group` FOREIGN KEY (`blood_group_id`) REFERENCES `blood_groups`(`id`);

-- Note: The original ALTER commands for adding columns like gender, photo, etc., have been removed
-- as they should have been applied in the previous update. This script is for the LATEST changes.
-- Ensure you have run the previous version of this script before running this one.
