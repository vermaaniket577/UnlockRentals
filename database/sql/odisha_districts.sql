-- ==========================================================
-- UnlockRentals - Complete Odisha 30 Districts SQL Script
-- Database: MySQL / MariaDB
-- ==========================================================

-- 1. Ensure `states` table exists and insert Odisha (State Code: OR)
CREATE TABLE IF NOT EXISTS `states` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(5) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `states` (`code`, `name`)
VALUES ('OR', 'Odisha')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 2. Ensure `districts` table exists with `slug` column
CREATE TABLE IF NOT EXISTS `districts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `state_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) DEFAULT NULL,
    INDEX `idx_districts_name` (`name`),
    INDEX `idx_districts_slug` (`slug`),
    CONSTRAINT `fk_districts_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Safely add `slug` column if `districts` table already exists without it
SET @exist := (
    SELECT COUNT(*) 
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
      AND TABLE_NAME = 'districts' 
      AND COLUMN_NAME = 'slug'
);
SET @sqlstmt := IF(@exist = 0, 'ALTER TABLE `districts` ADD COLUMN `slug` VARCHAR(100) DEFAULT NULL AFTER `name`, ADD INDEX `idx_districts_slug` (`slug`)', 'SELECT 1');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3. Get Odisha state ID dynamically into variable
SET @odisha_id = (SELECT `id` FROM `states` WHERE `code` = 'OR' LIMIT 1);

-- 4. Insert / Update All 30 Official Districts of Odisha
INSERT INTO `districts` (`state_id`, `name`, `slug`)
VALUES 
    (@odisha_id, 'Angul', 'angul'),
    (@odisha_id, 'Balangir', 'balangir'),
    (@odisha_id, 'Balasore', 'balasore'),
    (@odisha_id, 'Bargarh', 'bargarh'),
    (@odisha_id, 'Bhadrak', 'bhadrak'),
    (@odisha_id, 'Boudh', 'boudh'),
    (@odisha_id, 'Cuttack', 'cuttack'),
    (@odisha_id, 'Deogarh', 'deogarh'),
    (@odisha_id, 'Dhenkanal', 'dhenkanal'),
    (@odisha_id, 'Gajapati', 'gajapati'),
    (@odisha_id, 'Ganjam', 'ganjam'),
    (@odisha_id, 'Jagatsinghpur', 'jagatsinghpur'),
    (@odisha_id, 'Jajpur', 'jajpur'),
    (@odisha_id, 'Jharsuguda', 'jharsuguda'),
    (@odisha_id, 'Kalahandi', 'kalahandi'),
    (@odisha_id, 'Kandhamal', 'kandhamal'),
    (@odisha_id, 'Kendrapara', 'kendrapara'),
    (@odisha_id, 'Keonjhar', 'keonjhar'),
    (@odisha_id, 'Khordha', 'khordha'),
    (@odisha_id, 'Koraput', 'koraput'),
    (@odisha_id, 'Malkangiri', 'malkangiri'),
    (@odisha_id, 'Mayurbhanj', 'mayurbhanj'),
    (@odisha_id, 'Nabarangpur', 'nabarangpur'),
    (@odisha_id, 'Nayagarh', 'nayagarh'),
    (@odisha_id, 'Nuapada', 'nuapada'),
    (@odisha_id, 'Puri', 'puri'),
    (@odisha_id, 'Rayagada', 'rayagada'),
    (@odisha_id, 'Sambalpur', 'sambalpur'),
    (@odisha_id, 'Subarnapur', 'subarnapur'),
    (@odisha_id, 'Sundargarh', 'sundargarh')
ON DUPLICATE KEY UPDATE 
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`);

-- ==========================================================
-- Option B: Standalone `odisha_districts` reference table
-- (If you want a dedicated standalone table with IDs 1 to 30)
-- ==========================================================
CREATE TABLE IF NOT EXISTS `odisha_districts` (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `state` VARCHAR(50) NOT NULL DEFAULT 'Odisha',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `odisha_districts` (`id`, `name`, `slug`)
VALUES 
    (1,  'Angul', 'angul'),
    (2,  'Balangir', 'balangir'),
    (3,  'Balasore', 'balasore'),
    (4,  'Bargarh', 'bargarh'),
    (5,  'Bhadrak', 'bhadrak'),
    (6,  'Boudh', 'boudh'),
    (7,  'Cuttack', 'cuttack'),
    (8,  'Deogarh', 'deogarh'),
    (9,  'Dhenkanal', 'dhenkanal'),
    (10, 'Gajapati', 'gajapati'),
    (11, 'Ganjam', 'ganjam'),
    (12, 'Jagatsinghpur', 'jagatsinghpur'),
    (13, 'Jajpur', 'jajpur'),
    (14, 'Jharsuguda', 'jharsuguda'),
    (15, 'Kalahandi', 'kalahandi'),
    (16, 'Kandhamal', 'kandhamal'),
    (17, 'Kendrapara', 'kendrapara'),
    (18, 'Keonjhar', 'keonjhar'),
    (19, 'Khordha', 'khordha'),
    (20, 'Koraput', 'koraput'),
    (21, 'Malkangiri', 'malkangiri'),
    (22, 'Mayurbhanj', 'mayurbhanj'),
    (23, 'Nabarangpur', 'nabarangpur'),
    (24, 'Nayagarh', 'nayagarh'),
    (25, 'Nuapada', 'nuapada'),
    (26, 'Puri', 'puri'),
    (27, 'Rayagada', 'rayagada'),
    (28, 'Sambalpur', 'sambalpur'),
    (29, 'Subarnapur', 'subarnapur'),
    (30, 'Sundargarh', 'sundargarh')
ON DUPLICATE KEY UPDATE 
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`);
