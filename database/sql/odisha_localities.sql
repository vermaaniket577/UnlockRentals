-- ==========================================================
-- UnlockRentals - Complete Odisha Localities SQL Script
-- State: Odisha (OR) - All 30 Districts
-- Database: MySQL / MariaDB
-- ==========================================================

-- 1. Ensure `localities` table exists with `slug` column
CREATE TABLE IF NOT EXISTS `localities` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `district_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) DEFAULT NULL,
    INDEX `idx_localities_name` (`name`),
    INDEX `idx_localities_slug` (`slug`),
    CONSTRAINT `fk_localities_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Safely add `slug` column if `localities` table already exists without it
SET @exist_loc_slug := (
    SELECT COUNT(*) 
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
      AND TABLE_NAME = 'localities' 
      AND COLUMN_NAME = 'slug'
);
SET @sql_loc := IF(@exist_loc_slug = 0, 'ALTER TABLE `localities` ADD COLUMN `slug` VARCHAR(150) DEFAULT NULL AFTER `name`, ADD INDEX `idx_localities_slug` (`slug`)', 'SELECT 1');
PREPARE stmt_loc FROM @sql_loc;
EXECUTE stmt_loc;
DEALLOCATE PREPARE stmt_loc;

-- Get Odisha State ID
SET @odisha_id = (SELECT `id` FROM `states` WHERE `code` = 'OR' LIMIT 1);

-- Temporary Stored Procedure for Safe Idempotent Insertion
DROP PROCEDURE IF EXISTS `InsertOdishaLocality`;
DELIMITER $$
CREATE PROCEDURE `InsertOdishaLocality`(IN dist_name VARCHAR(100), IN loc_name VARCHAR(150), IN loc_slug VARCHAR(150))
BEGIN
    DECLARE v_dist_id BIGINT UNSIGNED;
    
    SELECT `id` INTO v_dist_id FROM `districts` 
    WHERE `name` = dist_name AND `state_id` = @odisha_id LIMIT 1;
    
    IF v_dist_id IS NOT NULL THEN
        IF NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = v_dist_id AND `name` = loc_name) THEN
            INSERT INTO `localities` (`district_id`, `name`, `slug`) VALUES (v_dist_id, loc_name, loc_slug);
        ELSE
            UPDATE `localities` SET `slug` = loc_slug WHERE `district_id` = v_dist_id AND `name` = loc_name;
        END IF;
    END IF;
END$$
DELIMITER ;

-- ==========================================================
-- 1. ANGUL
-- ==========================================================
CALL InsertOdishaLocality('Angul', 'Angul', 'angul');
CALL InsertOdishaLocality('Angul', 'Talcher', 'talcher');
CALL InsertOdishaLocality('Angul', 'Banarpal', 'banarpal');
CALL InsertOdishaLocality('Angul', 'Chhendipada', 'chhendipada');
CALL InsertOdishaLocality('Angul', 'Athamallik', 'athamallik');
CALL InsertOdishaLocality('Angul', 'Pallahara', 'pallahara');
CALL InsertOdishaLocality('Angul', 'Kaniha', 'kaniha');
CALL InsertOdishaLocality('Angul', 'Nalco Nagar', 'nalco-nagar');
CALL InsertOdishaLocality('Angul', 'Hakimpara', 'hakimpara');
CALL InsertOdishaLocality('Angul', 'Amalapada', 'amalapada');
CALL InsertOdishaLocality('Angul', 'Turanga', 'turanga');
CALL InsertOdishaLocality('Angul', 'Similipada', 'similipada');
CALL InsertOdishaLocality('Angul', 'Hulurisingha', 'hulurisingha');
CALL InsertOdishaLocality('Angul', 'Gandhi Marg', 'gandhi-marg');
CALL InsertOdishaLocality('Angul', 'Industrial Estate', 'industrial-estate');
CALL InsertOdishaLocality('Angul', 'Talcher Coalfields', 'talcher-coalfields');
CALL InsertOdishaLocality('Angul', 'Deulbera', 'deulbera');
CALL InsertOdishaLocality('Angul', 'MCL Colony', 'mcl-colony');

-- ==========================================================
-- 2. BALANGIR
-- ==========================================================
CALL InsertOdishaLocality('Balangir', 'Balangir', 'balangir');
CALL InsertOdishaLocality('Balangir', 'Titlagarh', 'titlagarh');
CALL InsertOdishaLocality('Balangir', 'Patnagarh', 'patnagarh');
CALL InsertOdishaLocality('Balangir', 'Kantabanji', 'kantabanji');
CALL InsertOdishaLocality('Balangir', 'Saintala', 'saintala');
CALL InsertOdishaLocality('Balangir', 'Loisingha', 'loisingha');
CALL InsertOdishaLocality('Balangir', 'Bangomunda', 'bangomunda');
CALL InsertOdishaLocality('Balangir', 'Muribahal', 'muribahal');
CALL InsertOdishaLocality('Balangir', 'Agalpur', 'agalpur');
CALL InsertOdishaLocality('Balangir', 'Gudvela', 'gudvela');
CALL InsertOdishaLocality('Balangir', 'Puintala', 'puintala');
CALL InsertOdishaLocality('Balangir', 'Deogaon', 'deogaon');
CALL InsertOdishaLocality('Balangir', 'Tusura', 'tusura');
CALL InsertOdishaLocality('Balangir', 'Sindhekela', 'sindhekela');
CALL InsertOdishaLocality('Balangir', 'Belpara', 'belpara');
CALL InsertOdishaLocality('Balangir', 'Khaprakhol', 'khaprakhol');

-- ==========================================================
-- 3. BALASORE
-- ==========================================================
CALL InsertOdishaLocality('Balasore', 'Balasore', 'balasore');
CALL InsertOdishaLocality('Balasore', 'Baleshwar', 'baleshwar');
CALL InsertOdishaLocality('Balasore', 'Jaleswar', 'jaleswar');
CALL InsertOdishaLocality('Balasore', 'Soro', 'soro');
CALL InsertOdishaLocality('Balasore', 'Nilagiri', 'nilagiri');
CALL InsertOdishaLocality('Balasore', 'Basta', 'basta');
CALL InsertOdishaLocality('Balasore', 'Bhograi', 'bhograi');
CALL InsertOdishaLocality('Balasore', 'Remuna', 'remuna');
CALL InsertOdishaLocality('Balasore', 'Simulia', 'simulia');
CALL InsertOdishaLocality('Balasore', 'Khantapara', 'khantapara');
CALL InsertOdishaLocality('Balasore', 'Chandipur', 'chandipur');
CALL InsertOdishaLocality('Balasore', 'Balipal', 'balipal');
CALL InsertOdishaLocality('Balasore', 'Sergarh', 'sergarh');
CALL InsertOdishaLocality('Balasore', 'Mitrapur', 'mitrapur');
CALL InsertOdishaLocality('Balasore', 'Rupsa', 'rupsa');
CALL InsertOdishaLocality('Balasore', 'Sahupada', 'sahupada');
CALL InsertOdishaLocality('Balasore', 'Motiganj', 'motiganj');
CALL InsertOdishaLocality('Balasore', 'Sunhat', 'sunhat');
CALL InsertOdishaLocality('Balasore', 'Teligadia', 'teligadia');

-- ==========================================================
-- 4. BARGARH
-- ==========================================================
CALL InsertOdishaLocality('Bargarh', 'Bargarh', 'bargarh');
CALL InsertOdishaLocality('Bargarh', 'Barpali', 'barpali');
CALL InsertOdishaLocality('Bargarh', 'Padampur', 'padampur');
CALL InsertOdishaLocality('Bargarh', 'Sohela', 'sohela');
CALL InsertOdishaLocality('Bargarh', 'Attabira', 'attabira');
CALL InsertOdishaLocality('Bargarh', 'Bijepur', 'bijepur');
CALL InsertOdishaLocality('Bargarh', 'Bhatli', 'bhatli');
CALL InsertOdishaLocality('Bargarh', 'Ambabhona', 'ambabhona');
CALL InsertOdishaLocality('Bargarh', 'Jharbandh', 'jharbandh');
CALL InsertOdishaLocality('Bargarh', 'Gaisilet', 'gaisilet');
CALL InsertOdishaLocality('Bargarh', 'Melchhamunda', 'melchhamunda');
CALL InsertOdishaLocality('Bargarh', 'Remunda', 'remunda');
CALL InsertOdishaLocality('Bargarh', 'Godbhaga', 'godbhaga');
CALL InsertOdishaLocality('Bargarh', 'Bardol', 'bardol');
CALL InsertOdishaLocality('Bargarh', 'Tora', 'tora');
CALL InsertOdishaLocality('Bargarh', 'Sambalpuri Colony', 'sambalpuri-colony');

-- ==========================================================
-- 5. BHADRAK
-- ==========================================================
CALL InsertOdishaLocality('Bhadrak', 'Bhadrak', 'bhadrak');
CALL InsertOdishaLocality('Bhadrak', 'Basudevpur', 'basudevpur');
CALL InsertOdishaLocality('Bhadrak', 'Chandabali', 'chandabali');
CALL InsertOdishaLocality('Bhadrak', 'Dhamnagar', 'dhamnagar');
CALL InsertOdishaLocality('Bhadrak', 'Bhandari Pokhari', 'bhandari-pokhari');
CALL InsertOdishaLocality('Bhadrak', 'Bonth', 'bonth');
CALL InsertOdishaLocality('Bhadrak', 'Tihidi', 'tihidi');
CALL InsertOdishaLocality('Bhadrak', 'Agarpada', 'agarpada');
CALL InsertOdishaLocality('Bhadrak', 'Charampa', 'charampa');
CALL InsertOdishaLocality('Bhadrak', 'Randia', 'randia');
CALL InsertOdishaLocality('Bhadrak', 'Gelpur', 'gelpur');
CALL InsertOdishaLocality('Bhadrak', 'Kuansh', 'kuansh');
CALL InsertOdishaLocality('Bhadrak', 'Nalanga', 'nalanga');
CALL InsertOdishaLocality('Bhadrak', 'Bant', 'bant');
CALL InsertOdishaLocality('Bhadrak', 'Eram', 'eram');
CALL InsertOdishaLocality('Bhadrak', 'Dhamra', 'dhamra');

-- ==========================================================
-- 6. BOUDH
-- ==========================================================
CALL InsertOdishaLocality('Boudh', 'Boudh', 'boudh');
CALL InsertOdishaLocality('Boudh', 'Kantamal', 'kantamal');
CALL InsertOdishaLocality('Boudh', 'Harabhanga', 'harabhanga');
CALL InsertOdishaLocality('Boudh', 'Manamunda', 'manamunda');
CALL InsertOdishaLocality('Boudh', 'Purunakatak', 'purunakatak');
CALL InsertOdishaLocality('Boudh', 'Tikarpada', 'tikarpada');
CALL InsertOdishaLocality('Boudh', 'Baunsuni', 'baunsuni');
CALL InsertOdishaLocality('Boudh', 'Brahmani', 'brahmani');
CALL InsertOdishaLocality('Boudh', 'Dhalpur', 'dhalpur');
CALL InsertOdishaLocality('Boudh', 'Charichhak', 'charichhak');

-- ==========================================================
-- 7. CUTTACK
-- ==========================================================
CALL InsertOdishaLocality('Cuttack', 'Cuttack', 'cuttack');
CALL InsertOdishaLocality('Cuttack', 'Choudwar', 'choudwar');
CALL InsertOdishaLocality('Cuttack', 'Athagarh', 'athagarh');
CALL InsertOdishaLocality('Cuttack', 'Banki', 'banki');
CALL InsertOdishaLocality('Cuttack', 'Salepur', 'salepur');
CALL InsertOdishaLocality('Cuttack', 'Niali', 'niali');
CALL InsertOdishaLocality('Cuttack', 'Narsinghpur', 'narsinghpur');
CALL InsertOdishaLocality('Cuttack', 'Tigiria', 'tigiria');
CALL InsertOdishaLocality('Cuttack', 'Naraj', 'naraj');
CALL InsertOdishaLocality('Cuttack', 'Barang', 'barang');
CALL InsertOdishaLocality('Cuttack', 'Phulnakhara', 'phulnakhara');
CALL InsertOdishaLocality('Cuttack', 'Bidanasi', 'bidanasi');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 1', 'cda-sector-1');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 2', 'cda-sector-2');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 6', 'cda-sector-6');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 7', 'cda-sector-7');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 9', 'cda-sector-9');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 10', 'cda-sector-10');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 11', 'cda-sector-11');
CALL InsertOdishaLocality('Cuttack', 'CDA Sector 13', 'cda-sector-13');
CALL InsertOdishaLocality('Cuttack', 'Link Road', 'link-road');
CALL InsertOdishaLocality('Cuttack', 'College Square', 'college-square');
CALL InsertOdishaLocality('Cuttack', 'Badambadi', 'badambadi');
CALL InsertOdishaLocality('Cuttack', 'Buxi Bazaar', 'buxi-bazaar');
CALL InsertOdishaLocality('Cuttack', 'Chandinichowk', 'chandinichowk');
CALL InsertOdishaLocality('Cuttack', 'Ranihat', 'ranihat');
CALL InsertOdishaLocality('Cuttack', 'Mangalabag', 'mangalabag');
CALL InsertOdishaLocality('Cuttack', 'Tulsipur', 'tulsipur');
CALL InsertOdishaLocality('Cuttack', 'Jobra', 'jobra');
CALL InsertOdishaLocality('Cuttack', 'Khan Nagar', 'khan-nagar');
CALL InsertOdishaLocality('Cuttack', 'Dolamundai', 'dolamundai');
CALL InsertOdishaLocality('Cuttack', 'Chauliaganj', 'chauliaganj');
CALL InsertOdishaLocality('Cuttack', 'Jagatpur', 'jagatpur');
CALL InsertOdishaLocality('Cuttack', 'Madhupatna', 'madhupatna');
CALL InsertOdishaLocality('Cuttack', 'Shelter Chhak', 'shelter-chhak');
CALL InsertOdishaLocality('Cuttack', 'Naya Bazaar', 'naya-bazaar');

-- ==========================================================
-- 8. DEOGARH
-- ==========================================================
CALL InsertOdishaLocality('Deogarh', 'Deogarh', 'deogarh');
CALL InsertOdishaLocality('Deogarh', 'Barkote', 'barkote');
CALL InsertOdishaLocality('Deogarh', 'Reamal', 'reamal');
CALL InsertOdishaLocality('Deogarh', 'Tileibani', 'tileibani');
CALL InsertOdishaLocality('Deogarh', 'Kundheigola', 'kundheigola');
CALL InsertOdishaLocality('Deogarh', 'Kankadahada', 'kankadahada');
CALL InsertOdishaLocality('Deogarh', 'Balam', 'balam');
CALL InsertOdishaLocality('Deogarh', 'Riamal', 'riamal');

-- ==========================================================
-- 9. DHENKANAL
-- ==========================================================
CALL InsertOdishaLocality('Dhenkanal', 'Dhenkanal', 'dhenkanal');
CALL InsertOdishaLocality('Dhenkanal', 'Kamakhyanagar', 'kamakhyanagar');
CALL InsertOdishaLocality('Dhenkanal', 'Hindol', 'hindol');
CALL InsertOdishaLocality('Dhenkanal', 'Bhuban', 'bhuban');
CALL InsertOdishaLocality('Dhenkanal', 'Gondia', 'gondia');
CALL InsertOdishaLocality('Dhenkanal', 'Parjang', 'parjang');
CALL InsertOdishaLocality('Dhenkanal', 'Odapada', 'odapada');
CALL InsertOdishaLocality('Dhenkanal', 'Kankadahad', 'kankadahad');
CALL InsertOdishaLocality('Dhenkanal', 'Balimi', 'balimi');
CALL InsertOdishaLocality('Dhenkanal', 'Motanga', 'motanga');
CALL InsertOdishaLocality('Dhenkanal', 'Mahabirod', 'mahabirod');
CALL InsertOdishaLocality('Dhenkanal', 'Meramandali', 'meramandali');
CALL InsertOdishaLocality('Dhenkanal', 'Bhapur', 'bhapur');
CALL InsertOdishaLocality('Dhenkanal', 'Tumusinga', 'tumusinga');
CALL InsertOdishaLocality('Dhenkanal', 'Rasol', 'rasol');

-- ==========================================================
-- 10. GAJAPATI
-- ==========================================================
CALL InsertOdishaLocality('Gajapati', 'Paralakhemundi', 'paralakhemundi');
CALL InsertOdishaLocality('Gajapati', 'Kashinagar', 'kashinagar');
CALL InsertOdishaLocality('Gajapati', 'R. Udayagiri', 'r-udayagiri');
CALL InsertOdishaLocality('Gajapati', 'Mohana', 'mohana');
CALL InsertOdishaLocality('Gajapati', 'Gumma', 'gumma');
CALL InsertOdishaLocality('Gajapati', 'Nuagada', 'nuagada');
CALL InsertOdishaLocality('Gajapati', 'Rayagada', 'rayagada-gajapati');
CALL InsertOdishaLocality('Gajapati', 'Garabandha', 'garabandha');
CALL InsertOdishaLocality('Gajapati', 'Adaba', 'adaba');
CALL InsertOdishaLocality('Gajapati', 'Serango', 'serango');

-- ==========================================================
-- 11. GANJAM
-- ==========================================================
CALL InsertOdishaLocality('Ganjam', 'Berhampur', 'berhampur');
CALL InsertOdishaLocality('Ganjam', 'Chhatrapur', 'chhatrapur');
CALL InsertOdishaLocality('Ganjam', 'Gopalpur', 'gopalpur');
CALL InsertOdishaLocality('Ganjam', 'Aska', 'aska');
CALL InsertOdishaLocality('Ganjam', 'Hinjilicut', 'hinjilicut');
CALL InsertOdishaLocality('Ganjam', 'Bhanjanagar', 'bhanjanagar');
CALL InsertOdishaLocality('Ganjam', 'Buguda', 'buguda');
CALL InsertOdishaLocality('Ganjam', 'Polasara', 'polasara');
CALL InsertOdishaLocality('Ganjam', 'Khallikote', 'khallikote');
CALL InsertOdishaLocality('Ganjam', 'Purushottampur', 'purushottampur');
CALL InsertOdishaLocality('Ganjam', 'Digapahandi', 'digapahandi');
CALL InsertOdishaLocality('Ganjam', 'Kabisuryanagar', 'kabisuryanagar');
CALL InsertOdishaLocality('Ganjam', 'Kodala', 'kodala');
CALL InsertOdishaLocality('Ganjam', 'Rambha', 'rambha');
CALL InsertOdishaLocality('Ganjam', 'Sheragada', 'sheragada');
CALL InsertOdishaLocality('Ganjam', 'Sanakhemundi', 'sanakhemundi');
CALL InsertOdishaLocality('Ganjam', 'Patrapur', 'patrapur');
CALL InsertOdishaLocality('Ganjam', 'Belaguntha', 'belaguntha');
CALL InsertOdishaLocality('Ganjam', 'Sorada', 'sorada');
CALL InsertOdishaLocality('Ganjam', 'Dharakote', 'dharakote');
CALL InsertOdishaLocality('Ganjam', 'Kukudakhandi', 'kukudakhandi');
CALL InsertOdishaLocality('Ganjam', 'Rangeilunda', 'rangeilunda');
CALL InsertOdishaLocality('Ganjam', 'Lanjipalli', 'lanjipalli');
CALL InsertOdishaLocality('Ganjam', 'Gosaninua Gaon', 'gosaninua-gaon');
CALL InsertOdishaLocality('Ganjam', 'Engineering School Road', 'engineering-school-road');
CALL InsertOdishaLocality('Ganjam', 'Gate Bazaar', 'gate-bazaar');
CALL InsertOdishaLocality('Ganjam', 'Courtpeta', 'courtpeta');
CALL InsertOdishaLocality('Ganjam', 'Kamapalli', 'kamapalli');
CALL InsertOdishaLocality('Ganjam', 'New Bus Stand', 'new-bus-stand');
CALL InsertOdishaLocality('Ganjam', 'Hillpatna', 'hillpatna');
CALL InsertOdishaLocality('Ganjam', 'Ankuli', 'ankuli');
CALL InsertOdishaLocality('Ganjam', 'Haladiapadar', 'haladiapadar');
CALL InsertOdishaLocality('Ganjam', 'Golanthara', 'golanthara');

-- ==========================================================
-- 12. JAGATSINGHPUR
-- ==========================================================
CALL InsertOdishaLocality('Jagatsinghpur', 'Jagatsinghpur', 'jagatsinghpur');
CALL InsertOdishaLocality('Jagatsinghpur', 'Paradeep', 'paradeep');
CALL InsertOdishaLocality('Jagatsinghpur', 'Kujang', 'kujang');
CALL InsertOdishaLocality('Jagatsinghpur', 'Balikuda', 'balikuda');
CALL InsertOdishaLocality('Jagatsinghpur', 'Tirtol', 'tirtol');
CALL InsertOdishaLocality('Jagatsinghpur', 'Raghunathpur', 'raghunathpur');
CALL InsertOdishaLocality('Jagatsinghpur', 'Naugaon', 'naugaon');
CALL InsertOdishaLocality('Jagatsinghpur', 'Biridi', 'biridi');
CALL InsertOdishaLocality('Jagatsinghpur', 'Ersama', 'ersama');
CALL InsertOdishaLocality('Jagatsinghpur', 'Paradipgarh', 'paradipgarh');
CALL InsertOdishaLocality('Jagatsinghpur', 'Atharbanki', 'atharbanki');
CALL InsertOdishaLocality('Jagatsinghpur', 'Abhayachandrapur', 'abhayachandrapur');
CALL InsertOdishaLocality('Jagatsinghpur', 'Dolipur', 'dolipur');
CALL InsertOdishaLocality('Jagatsinghpur', 'Madhuban', 'madhuban');
CALL InsertOdishaLocality('Jagatsinghpur', 'Badapadia', 'badapadia');

-- ==========================================================
-- 13. JAJPUR
-- ==========================================================
CALL InsertOdishaLocality('Jajpur', 'Jajpur', 'jajpur');
CALL InsertOdishaLocality('Jajpur', 'Jajpur Road', 'jajpur-road');
CALL InsertOdishaLocality('Jajpur', 'Vyasanagar', 'vyasanagar');
CALL InsertOdishaLocality('Jajpur', 'Dharmasala', 'dharmasala');
CALL InsertOdishaLocality('Jajpur', 'Panikoili', 'panikoili');
CALL InsertOdishaLocality('Jajpur', 'Binjharpur', 'binjharpur');
CALL InsertOdishaLocality('Jajpur', 'Bari', 'bari');
CALL InsertOdishaLocality('Jajpur', 'Rasulpur', 'rasulpur');
CALL InsertOdishaLocality('Jajpur', 'Sukinda', 'sukinda');
CALL InsertOdishaLocality('Jajpur', 'Korei', 'korei');
CALL InsertOdishaLocality('Jajpur', 'Dasarathpur', 'dasarathpur');
CALL InsertOdishaLocality('Jajpur', 'Danagadi', 'danagadi');
CALL InsertOdishaLocality('Jajpur', 'Chandikhol', 'chandikhol');
CALL InsertOdishaLocality('Jajpur', 'Kalinganagar', 'kalinganagar');
CALL InsertOdishaLocality('Jajpur', 'Duburi', 'duburi');
CALL InsertOdishaLocality('Jajpur', 'Jakhapura', 'jakhapura');
CALL InsertOdishaLocality('Jajpur', 'Byree', 'byree');
CALL InsertOdishaLocality('Jajpur', 'Mangalpur', 'mangalpur');

-- ==========================================================
-- 14. JHARSUGUDA
-- ==========================================================
CALL InsertOdishaLocality('Jharsuguda', 'Jharsuguda', 'jharsuguda');
CALL InsertOdishaLocality('Jharsuguda', 'Brajarajnagar', 'brajarajnagar');
CALL InsertOdishaLocality('Jharsuguda', 'Belpahar', 'belpahar');
CALL InsertOdishaLocality('Jharsuguda', 'Lakhanpur', 'lakhanpur');
CALL InsertOdishaLocality('Jharsuguda', 'Laikera', 'laikera');
CALL InsertOdishaLocality('Jharsuguda', 'Kolabira', 'kolabira');
CALL InsertOdishaLocality('Jharsuguda', 'Kirimira', 'kirimira');
CALL InsertOdishaLocality('Jharsuguda', 'Kirmira', 'kirmira');
CALL InsertOdishaLocality('Jharsuguda', 'Bandhbahal', 'bandhbahal');
CALL InsertOdishaLocality('Jharsuguda', 'Orient Area', 'orient-area');
CALL InsertOdishaLocality('Jharsuguda', 'Gandhi Chowk', 'gandhi-chowk');
CALL InsertOdishaLocality('Jharsuguda', 'Sarbahal', 'sarbahal');
CALL InsertOdishaLocality('Jharsuguda', 'Beheramal', 'beheramal');
CALL InsertOdishaLocality('Jharsuguda', 'Station Road', 'station-road');
CALL InsertOdishaLocality('Jharsuguda', 'Sarandamal', 'sarandamal');
CALL InsertOdishaLocality('Jharsuguda', 'Mangal Bazaar', 'mangal-bazaar');

-- ==========================================================
-- 15. KALAHANDI
-- ==========================================================
CALL InsertOdishaLocality('Kalahandi', 'Bhawanipatna', 'bhawanipatna');
CALL InsertOdishaLocality('Kalahandi', 'Dharamgarh', 'dharamgarh');
CALL InsertOdishaLocality('Kalahandi', 'Junagarh', 'junagarh');
CALL InsertOdishaLocality('Kalahandi', 'Kesinga', 'kesinga');
CALL InsertOdishaLocality('Kalahandi', 'Narla', 'narla');
CALL InsertOdishaLocality('Kalahandi', 'Lanjigarh', 'lanjigarh');
CALL InsertOdishaLocality('Kalahandi', 'Golamunda', 'golamunda');
CALL InsertOdishaLocality('Kalahandi', 'Kalampur', 'kalampur');
CALL InsertOdishaLocality('Kalahandi', 'Kokasara', 'kokasara');
CALL InsertOdishaLocality('Kalahandi', 'Thuamul Rampur', 'thuamul-rampur');
CALL InsertOdishaLocality('Kalahandi', 'Jaipatna', 'jaipatna');
CALL InsertOdishaLocality('Kalahandi', 'M. Rampur', 'm-rampur');
CALL InsertOdishaLocality('Kalahandi', 'Karlamunda', 'karlamunda');
CALL InsertOdishaLocality('Kalahandi', 'Madanpur Rampur', 'madanpur-rampur');
CALL InsertOdishaLocality('Kalahandi', 'Sadar', 'sadar-kalahandi');
CALL InsertOdishaLocality('Kalahandi', 'Risida', 'risida');

-- ==========================================================
-- 16. KANDHAMAL
-- ==========================================================
CALL InsertOdishaLocality('Kandhamal', 'Phulbani', 'phulbani');
CALL InsertOdishaLocality('Kandhamal', 'Baliguda', 'baliguda');
CALL InsertOdishaLocality('Kandhamal', 'G. Udayagiri', 'g-udayagiri');
CALL InsertOdishaLocality('Kandhamal', 'Daringbadi', 'daringbadi');
CALL InsertOdishaLocality('Kandhamal', 'Tikabali', 'tikabali');
CALL InsertOdishaLocality('Kandhamal', 'Raikia', 'raikia');
CALL InsertOdishaLocality('Kandhamal', 'Tumudibandh', 'tumudibandh');
CALL InsertOdishaLocality('Kandhamal', 'K. Nuagaon', 'k-nuagaon');
CALL InsertOdishaLocality('Kandhamal', 'Chakapad', 'chakapad');
CALL InsertOdishaLocality('Kandhamal', 'Khajuripada', 'khajuripada');
CALL InsertOdishaLocality('Kandhamal', 'Phiringia', 'phiringia');
CALL InsertOdishaLocality('Kandhamal', 'Kotagarh', 'kotagarh');
CALL InsertOdishaLocality('Kandhamal', 'Brahmanigaon', 'brahmanigaon');
CALL InsertOdishaLocality('Kandhamal', 'Sarangada', 'sarangada');

-- ==========================================================
-- 17. KENDRAPARA
-- ==========================================================
CALL InsertOdishaLocality('Kendrapara', 'Kendrapara', 'kendrapara');
CALL InsertOdishaLocality('Kendrapara', 'Pattamundai', 'pattamundai');
CALL InsertOdishaLocality('Kendrapara', 'Rajkanika', 'rajkanika');
CALL InsertOdishaLocality('Kendrapara', 'Aul', 'aul');
CALL InsertOdishaLocality('Kendrapara', 'Rajnagar', 'rajnagar');
CALL InsertOdishaLocality('Kendrapara', 'Mahakalapada', 'mahakalapada');
CALL InsertOdishaLocality('Kendrapara', 'Derabish', 'derabish');
CALL InsertOdishaLocality('Kendrapara', 'Marsaghai', 'marsaghai');
CALL InsertOdishaLocality('Kendrapara', 'Garadpur', 'garadpur');
CALL InsertOdishaLocality('Kendrapara', 'Nikirai', 'nikirai');
CALL InsertOdishaLocality('Kendrapara', 'Indupur', 'indupur');
CALL InsertOdishaLocality('Kendrapara', 'Pattamundai Bazar', 'pattamundai-bazar');
CALL InsertOdishaLocality('Kendrapara', 'Sanamangala', 'sanamangala');

-- ==========================================================
-- 18. KEONJHAR
-- ==========================================================
CALL InsertOdishaLocality('Keonjhar', 'Keonjhar', 'keonjhar');
CALL InsertOdishaLocality('Keonjhar', 'Barbil', 'barbil');
CALL InsertOdishaLocality('Keonjhar', 'Joda', 'joda');
CALL InsertOdishaLocality('Keonjhar', 'Anandapur', 'anandapur');
CALL InsertOdishaLocality('Keonjhar', 'Champua', 'champua');
CALL InsertOdishaLocality('Keonjhar', 'Ghatgaon', 'ghatgaon');
CALL InsertOdishaLocality('Keonjhar', 'Patna', 'patna');
CALL InsertOdishaLocality('Keonjhar', 'Banspal', 'banspal');
CALL InsertOdishaLocality('Keonjhar', 'Telkoi', 'telkoi');
CALL InsertOdishaLocality('Keonjhar', 'Hatadihi', 'hatadihi');
CALL InsertOdishaLocality('Keonjhar', 'Jhumpura', 'jhumpura');
CALL InsertOdishaLocality('Keonjhar', 'Saharpada', 'saharpada');
CALL InsertOdishaLocality('Keonjhar', 'Harichandanpur', 'harichandanpur');
CALL InsertOdishaLocality('Keonjhar', 'Baria', 'baria');
CALL InsertOdishaLocality('Keonjhar', 'Bolani', 'bolani');
CALL InsertOdishaLocality('Keonjhar', 'Kiriburu Road', 'kiriburu-road');
CALL InsertOdishaLocality('Keonjhar', 'Joda East', 'joda-east');
CALL InsertOdishaLocality('Keonjhar', 'Joda West', 'joda-west');

-- ==========================================================
-- 19. KHORDHA (including Bhubaneswar Hub)
-- ==========================================================
CALL InsertOdishaLocality('Khordha', 'Bhubaneswar', 'bhubaneswar');
CALL InsertOdishaLocality('Khordha', 'Khordha', 'khordha');
CALL InsertOdishaLocality('Khordha', 'Jatani', 'jatani');
CALL InsertOdishaLocality('Khordha', 'Balipatna', 'balipatna');
CALL InsertOdishaLocality('Khordha', 'Balianta', 'balianta');
CALL InsertOdishaLocality('Khordha', 'Banapur', 'banapur');
CALL InsertOdishaLocality('Khordha', 'Tangi', 'tangi');
CALL InsertOdishaLocality('Khordha', 'Begunia', 'begunia');
CALL InsertOdishaLocality('Khordha', 'Bolagarh', 'bolagarh');
CALL InsertOdishaLocality('Khordha', 'Chilika', 'chilika');
CALL InsertOdishaLocality('Khordha', 'Balugaon', 'balugaon');
CALL InsertOdishaLocality('Khordha', 'Khurda Road', 'khurda-road');
CALL InsertOdishaLocality('Khordha', 'Patia', 'patia');
CALL InsertOdishaLocality('Khordha', 'Chandrasekharpur', 'chandrasekharpur');
CALL InsertOdishaLocality('Khordha', 'Sailashree Vihar', 'sailashree-vihar');
CALL InsertOdishaLocality('Khordha', 'Damana', 'damana');
CALL InsertOdishaLocality('Khordha', 'KIIT Square', 'kiit-square');
CALL InsertOdishaLocality('Khordha', 'Nandan Kanan', 'nandan-kanan');
CALL InsertOdishaLocality('Khordha', 'Jaydev Vihar', 'jaydev-vihar');
CALL InsertOdishaLocality('Khordha', 'Saheed Nagar', 'saheed-nagar');
CALL InsertOdishaLocality('Khordha', 'Sahid Nagar', 'sahid-nagar');
CALL InsertOdishaLocality('Khordha', 'Rasulgarh', 'rasulgarh');
CALL InsertOdishaLocality('Khordha', 'Mancheswar', 'mancheswar');
CALL InsertOdishaLocality('Khordha', 'Bomikhal', 'bomikhal');
CALL InsertOdishaLocality('Khordha', 'Laxmi Sagar', 'laxmi-sagar');
CALL InsertOdishaLocality('Khordha', 'Old Town', 'old-town');
CALL InsertOdishaLocality('Khordha', 'Khandagiri', 'khandagiri');
CALL InsertOdishaLocality('Khordha', 'Jagamara', 'jagamara');
CALL InsertOdishaLocality('Khordha', 'Dumduma', 'dumduma');
CALL InsertOdishaLocality('Khordha', 'Tamando', 'tamando');
CALL InsertOdishaLocality('Khordha', 'Patrapada', 'patrapada');
CALL InsertOdishaLocality('Khordha', 'Ghatikia', 'ghatikia');
CALL InsertOdishaLocality('Khordha', 'Sundarpada', 'sundarpada');
CALL InsertOdishaLocality('Khordha', 'Pokhariput', 'pokhariput');
CALL InsertOdishaLocality('Khordha', 'Airport Area', 'airport-area-bhubaneswar');
CALL InsertOdishaLocality('Khordha', 'Baramunda', 'baramunda');
CALL InsertOdishaLocality('Khordha', 'Nayapalli', 'nayapalli');
CALL InsertOdishaLocality('Khordha', 'IRC Village', 'irc-village');
CALL InsertOdishaLocality('Khordha', 'Unit 4', 'unit-4');
CALL InsertOdishaLocality('Khordha', 'Unit 6', 'unit-6');
CALL InsertOdishaLocality('Khordha', 'Unit 8', 'unit-8');
CALL InsertOdishaLocality('Khordha', 'Unit 9', 'unit-9');
CALL InsertOdishaLocality('Khordha', 'Unit 3', 'unit-3');
CALL InsertOdishaLocality('Khordha', 'Unit 1', 'unit-1');
CALL InsertOdishaLocality('Khordha', 'VSS Nagar', 'vss-nagar');
CALL InsertOdishaLocality('Khordha', 'Acharya Vihar', 'acharya-vihar');
CALL InsertOdishaLocality('Khordha', 'BJB Nagar', 'bjb-nagar');
CALL InsertOdishaLocality('Khordha', 'Forest Park', 'forest-park');
CALL InsertOdishaLocality('Khordha', 'Shyamapur', 'shyamapur');
CALL InsertOdishaLocality('Khordha', 'Jagannath Vihar', 'jagannath-vihar');
CALL InsertOdishaLocality('Khordha', 'GGP Colony', 'ggp-colony');

-- ==========================================================
-- 20. KORAPUT
-- ==========================================================
CALL InsertOdishaLocality('Koraput', 'Koraput', 'koraput');
CALL InsertOdishaLocality('Koraput', 'Jeypore', 'jeypore');
CALL InsertOdishaLocality('Koraput', 'Sunabeda', 'sunabeda');
CALL InsertOdishaLocality('Koraput', 'Semiliguda', 'semiliguda');
CALL InsertOdishaLocality('Koraput', 'Kotpad', 'kotpad');
CALL InsertOdishaLocality('Koraput', 'Damanjodi', 'damanjodi');
CALL InsertOdishaLocality('Koraput', 'Laxmipur', 'laxmipur');
CALL InsertOdishaLocality('Koraput', 'Nandapur', 'nandapur');
CALL InsertOdishaLocality('Koraput', 'Pottangi', 'pottangi');
CALL InsertOdishaLocality('Koraput', 'Borrigumma', 'borrigumma');
CALL InsertOdishaLocality('Koraput', 'Bandhugaon', 'bandhugaon');
CALL InsertOdishaLocality('Koraput', 'Narayanpatna', 'narayanpatna');
CALL InsertOdishaLocality('Koraput', 'Lamtaput', 'lamtaput');
CALL InsertOdishaLocality('Koraput', 'Machkund', 'machkund');
CALL InsertOdishaLocality('Koraput', 'Deomali', 'deomali');
CALL InsertOdishaLocality('Koraput', 'Kakiriguma', 'kakiriguma');
CALL InsertOdishaLocality('Koraput', 'Dudhari', 'dudhari');

-- ==========================================================
-- 21. MALKANGIRI
-- ==========================================================
CALL InsertOdishaLocality('Malkangiri', 'Malkangiri', 'malkangiri');
CALL InsertOdishaLocality('Malkangiri', 'Balimela', 'balimela');
CALL InsertOdishaLocality('Malkangiri', 'Korukonda', 'korukonda');
CALL InsertOdishaLocality('Malkangiri', 'Chitrakonda', 'chitrakonda');
CALL InsertOdishaLocality('Malkangiri', 'Kalimela', 'kalimela');
CALL InsertOdishaLocality('Malkangiri', 'Mathili', 'mathili');
CALL InsertOdishaLocality('Malkangiri', 'Khairput', 'khairput');
CALL InsertOdishaLocality('Malkangiri', 'Podia', 'podia');
CALL InsertOdishaLocality('Malkangiri', 'Motu', 'motu');
CALL InsertOdishaLocality('Malkangiri', 'MV-79', 'mv-79');
CALL InsertOdishaLocality('Malkangiri', 'Govindapalli', 'govindapalli');
CALL InsertOdishaLocality('Malkangiri', 'Panasput', 'panasput');

-- ==========================================================
-- 22. MAYURBHANJ
-- ==========================================================
CALL InsertOdishaLocality('Mayurbhanj', 'Baripada', 'baripada');
CALL InsertOdishaLocality('Mayurbhanj', 'Rairangpur', 'rairangpur');
CALL InsertOdishaLocality('Mayurbhanj', 'Karanjia', 'karanjia');
CALL InsertOdishaLocality('Mayurbhanj', 'Udala', 'udala');
CALL InsertOdishaLocality('Mayurbhanj', 'Betnoti', 'betnoti');
CALL InsertOdishaLocality('Mayurbhanj', 'Badasahi', 'badasahi');
CALL InsertOdishaLocality('Mayurbhanj', 'Bangriposi', 'bangriposi');
CALL InsertOdishaLocality('Mayurbhanj', 'Jashipur', 'jashipur');
CALL InsertOdishaLocality('Mayurbhanj', 'Kaptipada', 'kaptipada');
CALL InsertOdishaLocality('Mayurbhanj', 'Khunta', 'khunta');
CALL InsertOdishaLocality('Mayurbhanj', 'Kuliana', 'kuliana');
CALL InsertOdishaLocality('Mayurbhanj', 'Rasgovindpur', 'rasgovindpur');
CALL InsertOdishaLocality('Mayurbhanj', 'Bisoi', 'bisoi');
CALL InsertOdishaLocality('Mayurbhanj', 'Kusumi', 'kusumi');
CALL InsertOdishaLocality('Mayurbhanj', 'Morada', 'morada');
CALL InsertOdishaLocality('Mayurbhanj', 'Suliapada', 'suliapada');
CALL InsertOdishaLocality('Mayurbhanj', 'Thakurmunda', 'thakurmunda');
CALL InsertOdishaLocality('Mayurbhanj', 'Baripada Town', 'baripada-town');
CALL InsertOdishaLocality('Mayurbhanj', 'Takatpur', 'takatpur');
CALL InsertOdishaLocality('Mayurbhanj', 'Puruna Baripada', 'puruna-baripada');

-- ==========================================================
-- 23. NABARANGPUR
-- ==========================================================
CALL InsertOdishaLocality('Nabarangpur', 'Nabarangpur', 'nabarangpur');
CALL InsertOdishaLocality('Nabarangpur', 'Umerkote', 'umerkote');
CALL InsertOdishaLocality('Nabarangpur', 'Dabugam', 'dabugam');
CALL InsertOdishaLocality('Nabarangpur', 'Raighar', 'raighar');
CALL InsertOdishaLocality('Nabarangpur', 'Papadahandi', 'papadahandi');
CALL InsertOdishaLocality('Nabarangpur', 'Jharigam', 'jharigam');
CALL InsertOdishaLocality('Nabarangpur', 'Chandahandi', 'chandahandi');
CALL InsertOdishaLocality('Nabarangpur', 'Nandahandi', 'nandahandi');
CALL InsertOdishaLocality('Nabarangpur', 'Tentulikhunti', 'tentulikhunti');
CALL InsertOdishaLocality('Nabarangpur', 'Kosagumuda', 'kosagumuda');
CALL InsertOdishaLocality('Nabarangpur', 'Kodinga', 'kodinga');

-- ==========================================================
-- 24. NAYAGARH
-- ==========================================================
CALL InsertOdishaLocality('Nayagarh', 'Nayagarh', 'nayagarh');
CALL InsertOdishaLocality('Nayagarh', 'Khandapada', 'khandapada');
CALL InsertOdishaLocality('Nayagarh', 'Dasapalla', 'dasapalla');
CALL InsertOdishaLocality('Nayagarh', 'Ranpur', 'ranpur');
CALL InsertOdishaLocality('Nayagarh', 'Odagaon', 'odagaon');
CALL InsertOdishaLocality('Nayagarh', 'Nuagaon', 'nuagaon');
CALL InsertOdishaLocality('Nayagarh', 'Bhapur', 'bhapur-nayagarh');
CALL InsertOdishaLocality('Nayagarh', 'Gania', 'gania');
CALL InsertOdishaLocality('Nayagarh', 'Itamati', 'itamati');
CALL InsertOdishaLocality('Nayagarh', 'Sarankul', 'sarankul');
CALL InsertOdishaLocality('Nayagarh', 'Dasapalla Town', 'dasapalla-town');
CALL InsertOdishaLocality('Nayagarh', 'Kuanria', 'kuanria');
CALL InsertOdishaLocality('Nayagarh', 'Nayagarh Town', 'nayagarh-town');

-- ==========================================================
-- 25. NUAPADA
-- ==========================================================
CALL InsertOdishaLocality('Nuapada', 'Nuapada', 'nuapada');
CALL InsertOdishaLocality('Nuapada', 'Khariar', 'khariar');
CALL InsertOdishaLocality('Nuapada', 'Khariar Road', 'khariar-road');
CALL InsertOdishaLocality('Nuapada', 'Sinapali', 'sinapali');
CALL InsertOdishaLocality('Nuapada', 'Boden', 'boden');
CALL InsertOdishaLocality('Nuapada', 'Komna', 'komna');
CALL InsertOdishaLocality('Nuapada', 'Jonk', 'jonk');
CALL InsertOdishaLocality('Nuapada', 'Dharambandha', 'dharambandha');
CALL InsertOdishaLocality('Nuapada', 'Tarbod', 'tarbod');
CALL InsertOdishaLocality('Nuapada', 'Duajhar', 'duajhar');
CALL InsertOdishaLocality('Nuapada', 'Sunabeda', 'sunabeda-nuapada');

-- ==========================================================
-- 26. PURI
-- ==========================================================
CALL InsertOdishaLocality('Puri', 'Puri', 'puri');
CALL InsertOdishaLocality('Puri', 'Konark', 'konark');
CALL InsertOdishaLocality('Puri', 'Pipili', 'pipili');
CALL InsertOdishaLocality('Puri', 'Nimapara', 'nimapara');
CALL InsertOdishaLocality('Puri', 'Sakhigopal', 'sakhigopal');
CALL InsertOdishaLocality('Puri', 'Brahmagiri', 'brahmagiri');
CALL InsertOdishaLocality('Puri', 'Kakatpur', 'kakatpur');
CALL InsertOdishaLocality('Puri', 'Astaranga', 'astaranga');
CALL InsertOdishaLocality('Puri', 'Delanga', 'delanga');
CALL InsertOdishaLocality('Puri', 'Gop', 'gop');
CALL InsertOdishaLocality('Puri', 'Krushnaprasad', 'krushnaprasad');
CALL InsertOdishaLocality('Puri', 'Chandanpur', 'chandanpur');
CALL InsertOdishaLocality('Puri', 'Balighai', 'balighai');
CALL InsertOdishaLocality('Puri', 'Swargadwar', 'swargadwar');
CALL InsertOdishaLocality('Puri', 'Grand Road', 'grand-road');
CALL InsertOdishaLocality('Puri', 'Sea Beach', 'sea-beach-puri');
CALL InsertOdishaLocality('Puri', 'Chakratirtha Road', 'chakratirtha-road');
CALL InsertOdishaLocality('Puri', 'Baliapanda', 'baliapanda');
CALL InsertOdishaLocality('Puri', 'VIP Road', 'vip-road-puri');
CALL InsertOdishaLocality('Puri', 'Station Road', 'station-road-puri');
CALL InsertOdishaLocality('Puri', 'Dolamandap Sahi', 'dolamandap-sahi');
CALL InsertOdishaLocality('Puri', 'Narendra Kona', 'narendra-kona');

-- ==========================================================
-- 27. RAYAGADA
-- ==========================================================
CALL InsertOdishaLocality('Rayagada', 'Rayagada', 'rayagada');
CALL InsertOdishaLocality('Rayagada', 'Gunupur', 'gunupur');
CALL InsertOdishaLocality('Rayagada', 'Muniguda', 'muniguda');
CALL InsertOdishaLocality('Rayagada', 'Bissamcuttack', 'bissamcuttack');
CALL InsertOdishaLocality('Rayagada', 'Kashipur', 'kashipur');
CALL InsertOdishaLocality('Rayagada', 'Kalyansinghpur', 'kalyansinghpur');
CALL InsertOdishaLocality('Rayagada', 'Padmapur', 'padmapur');
CALL InsertOdishaLocality('Rayagada', 'Gudari', 'gudari');
CALL InsertOdishaLocality('Rayagada', 'Ramanaguda', 'ramanaguda');
CALL InsertOdishaLocality('Rayagada', 'Kolnara', 'kolnara');
CALL InsertOdishaLocality('Rayagada', 'Chandrapur', 'chandrapur');
CALL InsertOdishaLocality('Rayagada', 'Tikiri', 'tikiri');
CALL InsertOdishaLocality('Rayagada', 'Ambadola', 'ambadola');
CALL InsertOdishaLocality('Rayagada', 'Doraguda', 'doraguda');

-- ==========================================================
-- 28. SAMBALPUR
-- ==========================================================
CALL InsertOdishaLocality('Sambalpur', 'Sambalpur', 'sambalpur');
CALL InsertOdishaLocality('Sambalpur', 'Burla', 'burla');
CALL InsertOdishaLocality('Sambalpur', 'Hirakud', 'hirakud');
CALL InsertOdishaLocality('Sambalpur', 'Rengali', 'rengali');
CALL InsertOdishaLocality('Sambalpur', 'Kuchinda', 'kuchinda');
CALL InsertOdishaLocality('Sambalpur', 'Bamra', 'bamra');
CALL InsertOdishaLocality('Sambalpur', 'Rairakhol', 'rairakhol');
CALL InsertOdishaLocality('Sambalpur', 'Naktideul', 'naktideul');
CALL InsertOdishaLocality('Sambalpur', 'Jamankira', 'jamankira');
CALL InsertOdishaLocality('Sambalpur', 'Jujumura', 'jujumura');
CALL InsertOdishaLocality('Sambalpur', 'Dhankauda', 'dhankauda');
CALL InsertOdishaLocality('Sambalpur', 'Maneswar', 'maneswar');
CALL InsertOdishaLocality('Sambalpur', 'Ainthapali', 'ainthapali');
CALL InsertOdishaLocality('Sambalpur', 'Dhanupali', 'dhanupali');
CALL InsertOdishaLocality('Sambalpur', 'Khetrajpur', 'khetrajpur');
CALL InsertOdishaLocality('Sambalpur', 'Modipara', 'modipara');
CALL InsertOdishaLocality('Sambalpur', 'Budharaja', 'budharaja');
CALL InsertOdishaLocality('Sambalpur', 'Sakhipara', 'sakhipara');
CALL InsertOdishaLocality('Sambalpur', 'Farm Road', 'farm-road');
CALL InsertOdishaLocality('Sambalpur', 'Hirakud Colony', 'hirakud-colony');
CALL InsertOdishaLocality('Sambalpur', 'Burla Town', 'burla-town');
CALL InsertOdishaLocality('Sambalpur', 'Sambalpur University Area', 'sambalpur-university-area');

-- ==========================================================
-- 29. SUBARNAPUR (SONEPUR)
-- ==========================================================
CALL InsertOdishaLocality('Subarnapur', 'Sonepur', 'sonepur');
CALL InsertOdishaLocality('Subarnapur', 'Birmaharajpur', 'birmaharajpur');
CALL InsertOdishaLocality('Subarnapur', 'Tarbha', 'tarbha');
CALL InsertOdishaLocality('Subarnapur', 'Ullunda', 'ullunda');
CALL InsertOdishaLocality('Subarnapur', 'Binika', 'binika');
CALL InsertOdishaLocality('Subarnapur', 'Dunguripali', 'dunguripali');
CALL InsertOdishaLocality('Subarnapur', 'Rampur', 'rampur');
CALL InsertOdishaLocality('Subarnapur', 'Binka', 'binka');
CALL InsertOdishaLocality('Subarnapur', 'Subalaya', 'subalaya');
CALL InsertOdishaLocality('Subarnapur', 'Sonepur Town', 'sonepur-town');
CALL InsertOdishaLocality('Subarnapur', 'Sindurpur', 'sindurpur');

-- ==========================================================
-- 30. SUNDARGARH (including Rourkela Hub)
-- ==========================================================
CALL InsertOdishaLocality('Sundargarh', 'Sundargarh', 'sundargarh');
CALL InsertOdishaLocality('Sundargarh', 'Rourkela', 'rourkela');
CALL InsertOdishaLocality('Sundargarh', 'Rajgangpur', 'rajgangpur');
CALL InsertOdishaLocality('Sundargarh', 'Biramitrapur', 'biramitrapur');
CALL InsertOdishaLocality('Sundargarh', 'Bonai', 'bonai');
CALL InsertOdishaLocality('Sundargarh', 'Sundargarh Town', 'sundargarh-town');
CALL InsertOdishaLocality('Sundargarh', 'Lathikata', 'lathikata');
CALL InsertOdishaLocality('Sundargarh', 'Kutra', 'kutra');
CALL InsertOdishaLocality('Sundargarh', 'Koida', 'koida');
CALL InsertOdishaLocality('Sundargarh', 'Gurundia', 'gurundia');
CALL InsertOdishaLocality('Sundargarh', 'Hemgir', 'hemgir');
CALL InsertOdishaLocality('Sundargarh', 'Lephripara', 'lephripara');
CALL InsertOdishaLocality('Sundargarh', 'Tangarpali', 'tangarpali');
CALL InsertOdishaLocality('Sundargarh', 'Balishankara', 'balishankara');
CALL InsertOdishaLocality('Sundargarh', 'Bisra', 'bisra');
CALL InsertOdishaLocality('Sundargarh', 'Panposh', 'panposh');
CALL InsertOdishaLocality('Sundargarh', 'Bondamunda', 'bondamunda');
CALL InsertOdishaLocality('Sundargarh', 'Chhend Colony', 'chhend-colony');
CALL InsertOdishaLocality('Sundargarh', 'Civil Township', 'civil-township');
CALL InsertOdishaLocality('Sundargarh', 'Sector 1', 'sector-1');
CALL InsertOdishaLocality('Sundargarh', 'Sector 2', 'sector-2');
CALL InsertOdishaLocality('Sundargarh', 'Sector 3', 'sector-3');
CALL InsertOdishaLocality('Sundargarh', 'Sector 4', 'sector-4');
CALL InsertOdishaLocality('Sundargarh', 'Sector 5', 'sector-5');
CALL InsertOdishaLocality('Sundargarh', 'Sector 6', 'sector-6');
CALL InsertOdishaLocality('Sundargarh', 'Sector 7', 'sector-7');
CALL InsertOdishaLocality('Sundargarh', 'Sector 8', 'sector-8');
CALL InsertOdishaLocality('Sundargarh', 'Sector 9', 'sector-9');
CALL InsertOdishaLocality('Sundargarh', 'Sector 10', 'sector-10');
CALL InsertOdishaLocality('Sundargarh', 'Sector 13', 'sector-13');
CALL InsertOdishaLocality('Sundargarh', 'Sector 15', 'sector-15');
CALL InsertOdishaLocality('Sundargarh', 'Udit Nagar', 'udit-nagar');
CALL InsertOdishaLocality('Sundargarh', 'Basanti Colony', 'basanti-colony');
CALL InsertOdishaLocality('Sundargarh', 'Koel Nagar', 'koel-nagar');
CALL InsertOdishaLocality('Sundargarh', 'Jagda', 'jagda');
CALL InsertOdishaLocality('Sundargarh', 'Kalunga', 'kalunga');
CALL InsertOdishaLocality('Sundargarh', 'Fertilizer Township', 'fertilizer-township');

-- Clean up temporary procedure
DROP PROCEDURE IF EXISTS `InsertOdishaLocality`;

SELECT COUNT(*) AS total_odisha_localities 
FROM `localities` l 
JOIN `districts` d ON l.district_id = d.id 
WHERE d.state_id = @odisha_id;
