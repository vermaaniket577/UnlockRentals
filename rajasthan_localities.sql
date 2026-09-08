-- ==============================================================================
-- RAJASTHAN: ALL 41 DISTRICTS & 946 LOCALITIES MASTER SQL QUERY
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Rajasthan' (RJ)
INSERT INTO `states` (`code`, `name`)
SELECT 'RJ', 'Rajasthan' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'RJ' OR `name` = 'Rajasthan');

SET @rj = (SELECT `id` FROM `states` WHERE `code` = 'RJ' LIMIT 1);

-- 2. Ensure All 41 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @rj, d.name FROM (
  SELECT 'Ajmer' AS name UNION ALL SELECT 'Alwar' UNION ALL SELECT 'Balotra' UNION ALL SELECT 'Banswara' UNION ALL SELECT 'Baran' UNION ALL SELECT 'Barmer' UNION ALL SELECT 'Beawar' UNION ALL SELECT 'Bharatpur' UNION ALL SELECT 'Bhilwara' UNION ALL SELECT 'Bikaner' UNION ALL SELECT 'Bundi' UNION ALL SELECT 'Chittorgarh' UNION ALL SELECT 'Churu' UNION ALL SELECT 'Dausa' UNION ALL SELECT 'Deeg' UNION ALL SELECT 'Didwana-Kuchaman' UNION ALL SELECT 'Dholpur' UNION ALL SELECT 'Dungarpur' UNION ALL SELECT 'Hanumangarh' UNION ALL SELECT 'Jaipur' UNION ALL SELECT 'Jaisalmer' UNION ALL SELECT 'Jalore' UNION ALL SELECT 'Jhalawar' UNION ALL SELECT 'Jhunjhunu' UNION ALL SELECT 'Jodhpur' UNION ALL SELECT 'Karauli' UNION ALL SELECT 'Khairthal-Tijara' UNION ALL SELECT 'Kota' UNION ALL SELECT 'Kotputli-Behror' UNION ALL SELECT 'Nagaur' UNION ALL SELECT 'Pali' UNION ALL SELECT 'Phalodi' UNION ALL SELECT 'Pratapgarh' UNION ALL SELECT 'Rajsamand' UNION ALL SELECT 'Salumbar' UNION ALL SELECT 'Sawai Madhopur' UNION ALL SELECT 'Sikar' UNION ALL SELECT 'Sirohi' UNION ALL SELECT 'Sri Ganganagar' UNION ALL SELECT 'Tonk' UNION ALL SELECT 'Udaipur'
) d WHERE NOT EXISTS (SELECT 1 FROM `districts` WHERE `state_id` = @rj AND LOWER(`name`) = LOWER(d.name));

-- 3. Insert Localities for All 41 Districts
-- District: Ajmer (31 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Ajmer' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Ajmer City' AS name UNION ALL SELECT 'Ajmer' UNION ALL SELECT 'Vaishali Nagar' UNION ALL SELECT 'Panchsheel Nagar' UNION ALL
  SELECT 'Civil Lines' UNION ALL SELECT 'Adarsh Nagar' UNION ALL SELECT 'Ajmer Road' UNION ALL SELECT 'Beawar Road' UNION ALL
  SELECT 'Jaipur Road' UNION ALL SELECT 'Pushkar Road' UNION ALL SELECT 'Nagra' UNION ALL SELECT 'Kotra' UNION ALL
  SELECT 'Ramganj' UNION ALL SELECT 'Ganj' UNION ALL SELECT 'Dargah Bazaar' UNION ALL SELECT 'Kaiser Ganj' UNION ALL
  SELECT 'Hathi Bhata' UNION ALL SELECT 'Chandravardai Nagar' UNION ALL SELECT 'Ana Sagar' UNION ALL SELECT 'Ana Sagar Link Road' UNION ALL
  SELECT 'Shastri Nagar' UNION ALL SELECT 'Makarwali' UNION ALL SELECT 'Chachiyawas' UNION ALL SELECT 'Foy Sagar' UNION ALL
  SELECT 'Ghooghra' UNION ALL SELECT 'Janana' UNION ALL SELECT 'Pushkar' UNION ALL SELECT 'Kishangarh' UNION ALL
  SELECT 'Nasirabad' UNION ALL SELECT 'Sarwar' UNION ALL SELECT 'Kekri'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Alwar (33 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Alwar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Alwar City' AS name UNION ALL SELECT 'Alwar' UNION ALL SELECT 'Moti Doongri' UNION ALL SELECT 'Scheme No. 1' UNION ALL
  SELECT 'Scheme No. 2' UNION ALL SELECT 'Aravali Vihar' UNION ALL SELECT 'Shanti Kunj' UNION ALL SELECT 'Kala Kuan' UNION ALL
  SELECT 'Malviya Nagar' UNION ALL SELECT 'Lajpat Nagar' UNION ALL SELECT 'Vijay Nagar' UNION ALL SELECT 'Ashok Vihar' UNION ALL
  SELECT 'Ram Nagar' UNION ALL SELECT 'Kabir Colony' UNION ALL SELECT 'NEB' UNION ALL SELECT 'Hope Circus' UNION ALL
  SELECT 'Company Bagh' UNION ALL SELECT 'Alwar City Centre' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'MIA' UNION ALL
  SELECT 'Matsya Industrial Area' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Delhi Road' UNION ALL SELECT 'Tijara Road' UNION ALL
  SELECT 'Bala Quila' UNION ALL SELECT 'Siliserh Road' UNION ALL SELECT 'Bhiwadi' UNION ALL SELECT 'Tijara' UNION ALL
  SELECT 'Khairthal' UNION ALL SELECT 'Behror' UNION ALL SELECT 'Rajgarh' UNION ALL SELECT 'Ramgarh' UNION ALL
  SELECT 'Thanagazi'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Balotra (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Balotra' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Balotra' AS name UNION ALL SELECT 'Jasol' UNION ALL SELECT 'Pachpadra' UNION ALL SELECT 'Siwana' UNION ALL
  SELECT 'Samdari' UNION ALL SELECT 'Sindhari' UNION ALL SELECT 'Kalyanpur' UNION ALL SELECT 'Guda Malani' UNION ALL
  SELECT 'Chohtan' UNION ALL SELECT 'Mokalsar' UNION ALL SELECT 'Parlu' UNION ALL SELECT 'Asada' UNION ALL
  SELECT 'Nakoda' UNION ALL SELECT 'Dhorimana' UNION ALL SELECT 'Baytu' UNION ALL SELECT 'Sindhari Road' UNION ALL
  SELECT 'Balotra Industrial Area' UNION ALL SELECT 'Jasol Industrial Area' UNION ALL SELECT 'Pachpadra Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Banswara (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Banswara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Banswara' AS name UNION ALL SELECT 'Talwara' UNION ALL SELECT 'Kushalgarh' UNION ALL SELECT 'Ghatol' UNION ALL
  SELECT 'Garhi' UNION ALL SELECT 'Bagidora' UNION ALL SELECT 'Anandpuri' UNION ALL SELECT 'Sajjangarh' UNION ALL
  SELECT 'Arthuna' UNION ALL SELECT 'Abapura' UNION ALL SELECT 'Tripura Sundari' UNION ALL SELECT 'Ganoda' UNION ALL
  SELECT 'Lohariya' UNION ALL SELECT 'Partapur' UNION ALL SELECT 'Khamera' UNION ALL SELECT 'Jagpura' UNION ALL
  SELECT 'Nai Abadi' UNION ALL SELECT 'Industrial Area' UNION ALL SELECT 'Dahod Road' UNION ALL SELECT 'Udaipur Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Baran (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Baran' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Baran' AS name UNION ALL SELECT 'Anta' UNION ALL SELECT 'Chhabra' UNION ALL SELECT 'Chhipabarod' UNION ALL
  SELECT 'Mangrol' UNION ALL SELECT 'Atru' UNION ALL SELECT 'Kishanganj' UNION ALL SELECT 'Shahabad' UNION ALL
  SELECT 'Kelwara' UNION ALL SELECT 'Siswali' UNION ALL SELECT 'Kawai' UNION ALL SELECT 'Bapcha' UNION ALL
  SELECT 'Bhanwargarh' UNION ALL SELECT 'Baran Road' UNION ALL SELECT 'Kota Road' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Civil Lines' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Barmer (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Barmer' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Barmer' AS name UNION ALL SELECT 'Balotra' UNION ALL SELECT 'Baytu' UNION ALL SELECT 'Chohtan' UNION ALL
  SELECT 'Dhorimana' UNION ALL SELECT 'Gudamalani' UNION ALL SELECT 'Sheo' UNION ALL SELECT 'Ramsar' UNION ALL
  SELECT 'Sindhari' UNION ALL SELECT 'Shiv' UNION ALL SELECT 'Samdari' UNION ALL SELECT 'Kalyanpur' UNION ALL
  SELECT 'Siwana' UNION ALL SELECT 'Barmer Agore' UNION ALL SELECT 'Barmer City' UNION ALL SELECT 'Mahaveer Nagar' UNION ALL
  SELECT 'Gandhi Nagar' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Jaisalmer Road' UNION ALL SELECT 'Jodhpur Road' UNION ALL
  SELECT 'Rajasthan Housing Board Area' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Beawar (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Beawar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Beawar' AS name UNION ALL SELECT 'Masuda' UNION ALL SELECT 'Vijaynagar' UNION ALL SELECT 'Jawaja' UNION ALL
  SELECT 'Asind' UNION ALL SELECT 'Badnor' UNION ALL SELECT 'Todgarh' UNION ALL SELECT 'Barakhera' UNION ALL
  SELECT 'Roopangarh' UNION ALL SELECT 'Beawar Road' UNION ALL SELECT 'Ajmer Road' UNION ALL SELECT 'Jaipur Road' UNION ALL
  SELECT 'Pali Road' UNION ALL SELECT 'Chandni Chowk' UNION ALL SELECT 'Surajpole' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Mahaveer Nagar' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bharatpur (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Bharatpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bharatpur' AS name UNION ALL SELECT 'Mathura Gate' UNION ALL SELECT 'Krishna Nagar' UNION ALL SELECT 'Jawahar Nagar' UNION ALL
  SELECT 'Ranjeet Nagar' UNION ALL SELECT 'Sanjay Nagar' UNION ALL SELECT 'Shastri Nagar' UNION ALL SELECT 'Tilak Nagar' UNION ALL
  SELECT 'Vijay Nagar' UNION ALL SELECT 'Gopalgarh' UNION ALL SELECT 'Sewar' UNION ALL SELECT 'Kumher' UNION ALL
  SELECT 'Bayana' UNION ALL SELECT 'Deeg' UNION ALL SELECT 'Weir' UNION ALL SELECT 'Nadbai' UNION ALL
  SELECT 'Rupbas' UNION ALL SELECT 'Uchchain' UNION ALL SELECT 'Kaman' UNION ALL SELECT 'Pahari' UNION ALL
  SELECT 'Nagar' UNION ALL SELECT 'Roopwas' UNION ALL SELECT 'Bharatpur Junction Area' UNION ALL SELECT 'Agra Road' UNION ALL
  SELECT 'Jaipur Road' UNION ALL SELECT 'Mathura Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bhilwara (27 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Bhilwara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bhilwara' AS name UNION ALL SELECT 'Subhash Nagar' UNION ALL SELECT 'Shastri Nagar' UNION ALL SELECT 'R.K. Colony' UNION ALL
  SELECT 'Azad Nagar' UNION ALL SELECT 'Gandhi Nagar' UNION ALL SELECT 'Patel Nagar' UNION ALL SELECT 'Nehru Nagar' UNION ALL
  SELECT 'Pur Road' UNION ALL SELECT 'Bhupalpura' UNION ALL SELECT 'Sangam Vihar' UNION ALL SELECT 'Vidyut Nagar' UNION ALL
  SELECT 'Sanjay Colony' UNION ALL SELECT 'Bhopal Ganj' UNION ALL SELECT 'Chandrashekhar Azad Nagar' UNION ALL SELECT 'Industrial Area' UNION ALL
  SELECT 'RIICO Area' UNION ALL SELECT 'Mandal' UNION ALL SELECT 'Shahpura' UNION ALL SELECT 'Asind' UNION ALL
  SELECT 'Gulabpura' UNION ALL SELECT 'Gangapur' UNION ALL SELECT 'Jahazpur' UNION ALL SELECT 'Bijolia' UNION ALL
  SELECT 'Kotri' UNION ALL SELECT 'Raipur' UNION ALL SELECT 'Mandalgarh'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bikaner (30 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Bikaner' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bikaner' AS name UNION ALL SELECT 'Rani Bazaar' UNION ALL SELECT 'Sardarpura' UNION ALL SELECT 'Gandhi Colony' UNION ALL
  SELECT 'Civil Lines' UNION ALL SELECT 'Shastri Nagar' UNION ALL SELECT 'Karni Nagar' UNION ALL SELECT 'Pawanpuri' UNION ALL
  SELECT 'JNV Colony' UNION ALL SELECT 'Mukta Prasad Nagar' UNION ALL SELECT 'Tilak Nagar' UNION ALL SELECT 'Gangashahar' UNION ALL
  SELECT 'Bhinasar' UNION ALL SELECT 'Naya Shahar' UNION ALL SELECT 'Kote Gate' UNION ALL SELECT 'Junagarh' UNION ALL
  SELECT 'Lalgarh' UNION ALL SELECT 'Lalgarh Palace Area' UNION ALL SELECT 'Nokha' UNION ALL SELECT 'Lunkaransar' UNION ALL
  SELECT 'Dungargarh' UNION ALL SELECT 'Khajuwala' UNION ALL SELECT 'Chhattargarh' UNION ALL SELECT 'Kolayat' UNION ALL
  SELECT 'Deshnok' UNION ALL SELECT 'Udasar' UNION ALL SELECT 'RIICO Industrial Area' UNION ALL SELECT 'Jaipur Road' UNION ALL
  SELECT 'Gajner Road' UNION ALL SELECT 'Sri Ganganagar Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bundi (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Bundi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bundi' AS name UNION ALL SELECT 'Talera' UNION ALL SELECT 'Keshoraipatan' UNION ALL SELECT 'Nainwa' UNION ALL
  SELECT 'Indergarh' UNION ALL SELECT 'Lakheri' UNION ALL SELECT 'Kapren' UNION ALL SELECT 'Hindoli' UNION ALL
  SELECT 'Khatkar' UNION ALL SELECT 'Dugari' UNION ALL SELECT 'Sadar Bazaar' UNION ALL SELECT 'Civil Lines' UNION ALL
  SELECT 'Kota Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Housing Board' UNION ALL
  SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Chittorgarh (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Chittorgarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chittorgarh' AS name UNION ALL SELECT 'Gandhi Nagar' UNION ALL SELECT 'Pratap Nagar' UNION ALL SELECT 'Senthi' UNION ALL
  SELECT 'Meera Nagar' UNION ALL SELECT 'Nehru Nagar' UNION ALL SELECT 'Bhilwara Road' UNION ALL SELECT 'Udaipur Road' UNION ALL
  SELECT 'Kota Road' UNION ALL SELECT 'Nimbahera' UNION ALL SELECT 'Kapasan' UNION ALL SELECT 'Begun' UNION ALL
  SELECT 'Rawatbhata' UNION ALL SELECT 'Rashmi' UNION ALL SELECT 'Gangrar' UNION ALL SELECT 'Bari Sadri' UNION ALL
  SELECT 'Bassi' UNION ALL SELECT 'Bhadesar' UNION ALL SELECT 'Chanderiya' UNION ALL SELECT 'Industrial Area' UNION ALL
  SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Churu (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Churu' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Churu' AS name UNION ALL SELECT 'Ratangarh' UNION ALL SELECT 'Sujangarh' UNION ALL SELECT 'Rajgarh' UNION ALL
  SELECT 'Taranagar' UNION ALL SELECT 'Sardarshahar' UNION ALL SELECT 'Bidasar' UNION ALL SELECT 'Dungargarh' UNION ALL
  SELECT 'Rajaldesar' UNION ALL SELECT 'Chapar' UNION ALL SELECT 'Sadulpur' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Gandhi Nagar' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Nehru Nagar' UNION ALL SELECT 'Industrial Area' UNION ALL
  SELECT 'Jaipur Road' UNION ALL SELECT 'Jhunjhunu Road' UNION ALL SELECT 'Bikaner Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dausa (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Dausa' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dausa' AS name UNION ALL SELECT 'Bandikui' UNION ALL SELECT 'Lalsot' UNION ALL SELECT 'Mahwa' UNION ALL
  SELECT 'Sikrai' UNION ALL SELECT 'Baswa' UNION ALL SELECT 'Mandawar' UNION ALL SELECT 'Lawan' UNION ALL
  SELECT 'Ramgarh Pachwara' UNION ALL SELECT 'Bhandarej' UNION ALL SELECT 'Bhandana' UNION ALL SELECT 'Mehandipur Balaji' UNION ALL
  SELECT 'Agra Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Housing Board' UNION ALL
  SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Deeg (16 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Deeg' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Deeg' AS name UNION ALL SELECT 'Kaman' UNION ALL SELECT 'Nagar' UNION ALL SELECT 'Pahari' UNION ALL
  SELECT 'Sikri' UNION ALL SELECT 'Jurhera' UNION ALL SELECT 'Weir' UNION ALL SELECT 'Kumher' UNION ALL
  SELECT 'Khoh' UNION ALL SELECT 'Gopalgarh' UNION ALL SELECT 'Govardhan Road' UNION ALL SELECT 'Bharatpur Road' UNION ALL
  SELECT 'Mathura Road' UNION ALL SELECT 'Deeg Palace Area' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Housing Board Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Didwana-Kuchaman (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Didwana-Kuchaman' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Didwana' AS name UNION ALL SELECT 'Kuchaman City' UNION ALL SELECT 'Makrana' UNION ALL SELECT 'Ladnun' UNION ALL
  SELECT 'Nawa' UNION ALL SELECT 'Parbatsar' UNION ALL SELECT 'Jayal' UNION ALL SELECT 'Merta Road' UNION ALL
  SELECT 'Degana' UNION ALL SELECT 'Sanwli' UNION ALL SELECT 'Choti Khatu' UNION ALL SELECT 'Badi Khatu' UNION ALL
  SELECT 'Sambhar Road' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Nagaur Road' UNION ALL
  SELECT 'Industrial Area' UNION ALL SELECT 'Kuchaman Industrial Area' UNION ALL SELECT 'Makrana Marble Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dholpur (16 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Dholpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dholpur' AS name UNION ALL SELECT 'Bari' UNION ALL SELECT 'Rajakhera' UNION ALL SELECT 'Baseri' UNION ALL
  SELECT 'Saipau' UNION ALL SELECT 'Mania' UNION ALL SELECT 'Sarmathura' UNION ALL SELECT 'Sepau' UNION ALL
  SELECT 'Dhaulpur City' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Civil Lines' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Agra Road' UNION ALL SELECT 'Gwalior Road' UNION ALL SELECT 'Morena Road' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dungarpur (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Dungarpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dungarpur' AS name UNION ALL SELECT 'Sagwara' UNION ALL SELECT 'Aspur' UNION ALL SELECT 'Galiyakot' UNION ALL
  SELECT 'Simalwara' UNION ALL SELECT 'Bicchiwara' UNION ALL SELECT 'Sabla' UNION ALL SELECT 'Chikhli' UNION ALL
  SELECT 'Ganeshpuri' UNION ALL SELECT 'Punali' UNION ALL SELECT 'Obri' UNION ALL SELECT 'Rani' UNION ALL
  SELECT 'Sabla Road' UNION ALL SELECT 'Udaipur Road' UNION ALL SELECT 'Sagwara Road' UNION ALL SELECT 'Industrial Area' UNION ALL
  SELECT 'Housing Board'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Hanumangarh (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Hanumangarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Hanumangarh' AS name UNION ALL SELECT 'Hanumangarh Town' UNION ALL SELECT 'Hanumangarh Junction' UNION ALL SELECT 'Bhadra' UNION ALL
  SELECT 'Nohar' UNION ALL SELECT 'Rawatsar' UNION ALL SELECT 'Pilibanga' UNION ALL SELECT 'Sangaria' UNION ALL
  SELECT 'Tibbi' UNION ALL SELECT 'Ellenabad Road' UNION ALL SELECT 'Suratgarh Road' UNION ALL SELECT 'Junction Road' UNION ALL
  SELECT 'Nai Abadi' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Civil Lines' UNION ALL SELECT 'Industrial Area' UNION ALL
  SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jaipur (54 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Jaipur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'C-Scheme' AS name UNION ALL SELECT 'Malviya Nagar' UNION ALL SELECT 'Mansarovar' UNION ALL SELECT 'Vaishali Nagar' UNION ALL
  SELECT 'Jagatpura' UNION ALL SELECT 'Pratap Nagar' UNION ALL SELECT 'Sanganer' UNION ALL SELECT 'Durgapura' UNION ALL
  SELECT 'Tonk Road' UNION ALL SELECT 'Sodala' UNION ALL SELECT 'Shyam Nagar' UNION ALL SELECT 'Civil Lines' UNION ALL
  SELECT 'Bani Park' UNION ALL SELECT 'Raja Park' UNION ALL SELECT 'Adarsh Nagar' UNION ALL SELECT 'Jawahar Nagar' UNION ALL
  SELECT 'Vidyadhar Nagar' UNION ALL SELECT 'Jhotwara' UNION ALL SELECT 'Nirman Nagar' UNION ALL SELECT 'Gopalpura' UNION ALL
  SELECT 'Gopalpura Bypass' UNION ALL SELECT 'Mahesh Nagar' UNION ALL SELECT 'Lal Kothi' UNION ALL SELECT 'Bajaj Nagar' UNION ALL
  SELECT 'Gandhi Nagar' UNION ALL SELECT 'Tilak Nagar' UNION ALL SELECT 'Ramgarh Mod' UNION ALL SELECT 'Amer' UNION ALL
  SELECT 'Kukas' UNION ALL SELECT 'Kanakpura' UNION ALL SELECT 'Kalwar Road' UNION ALL SELECT 'Sirsi Road' UNION ALL
  SELECT 'Ajmer Road' UNION ALL SELECT 'Delhi Road' UNION ALL SELECT 'Agra Road' UNION ALL SELECT 'Sikar Road' UNION ALL
  SELECT 'Sindhi Camp' UNION ALL SELECT 'Chandpole' UNION ALL SELECT 'Johari Bazaar' UNION ALL SELECT 'MI Road' UNION ALL
  SELECT 'Tripolia Bazaar' UNION ALL SELECT 'Chomu' UNION ALL SELECT 'Shahpura' UNION ALL SELECT 'Kotputli' UNION ALL
  SELECT 'Jamwa Ramgarh' UNION ALL SELECT 'Phulera' UNION ALL SELECT 'Sambhar' UNION ALL SELECT 'Chaksu' UNION ALL
  SELECT 'Bassi' UNION ALL SELECT 'Achrol' UNION ALL SELECT 'Bagru' UNION ALL SELECT 'Renwal' UNION ALL
  SELECT 'Jobner' UNION ALL SELECT 'Dudu'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jaisalmer (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Jaisalmer' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jaisalmer' AS name UNION ALL SELECT 'Dhanana' UNION ALL SELECT 'Pokaran' UNION ALL SELECT 'Ramgarh' UNION ALL
  SELECT 'Sam' UNION ALL SELECT 'Khuri' UNION ALL SELECT 'Tanot' UNION ALL SELECT 'Mohangarh' UNION ALL
  SELECT 'Fatehgarh' UNION ALL SELECT 'Nachna' UNION ALL SELECT 'Devikot' UNION ALL SELECT 'Lathi' UNION ALL
  SELECT 'Shahgarh' UNION ALL SELECT 'Kishangarh' UNION ALL SELECT 'Amar Sagar' UNION ALL SELECT 'Bada Bagh' UNION ALL
  SELECT 'Hanuman Chowk' UNION ALL SELECT 'Patwa Haveli Area' UNION ALL SELECT 'Gadisar Lake Area' UNION ALL SELECT 'Sam Road' UNION ALL
  SELECT 'Barmer Road' UNION ALL SELECT 'Jodhpur Road' UNION ALL SELECT 'Air Force Area' UNION ALL SELECT 'Indira Colony' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jalore (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Jalore' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jalore' AS name UNION ALL SELECT 'Bhinmal' UNION ALL SELECT 'Sanchore' UNION ALL SELECT 'Ahore' UNION ALL
  SELECT 'Raniwara' UNION ALL SELECT 'Sayla' UNION ALL SELECT 'Bagoda' UNION ALL SELECT 'Jaswantpura' UNION ALL
  SELECT 'Chitalwana' UNION ALL SELECT 'Sanchore Road' UNION ALL SELECT 'Bhinmal Road' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area' UNION ALL SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jhalawar (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Jhalawar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jhalawar' AS name UNION ALL SELECT 'Jhalrapatan' UNION ALL SELECT 'Bhawani Mandi' UNION ALL SELECT 'Aklera' UNION ALL
  SELECT 'Khanpur' UNION ALL SELECT 'Pirawa' UNION ALL SELECT 'Manohar Thana' UNION ALL SELECT 'Dag' UNION ALL
  SELECT 'Gangdhar' UNION ALL SELECT 'Sunel' UNION ALL SELECT 'Bakani' UNION ALL SELECT 'Asnawar' UNION ALL
  SELECT 'Pachpahar' UNION ALL SELECT 'Kota Road' UNION ALL SELECT 'Udaipur Road' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jhunjhunu (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Jhunjhunu' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jhunjhunu' AS name UNION ALL SELECT 'Khetri' UNION ALL SELECT 'Chirawa' UNION ALL SELECT 'Pilani' UNION ALL
  SELECT 'Nawalgarh' UNION ALL SELECT 'Surajgarh' UNION ALL SELECT 'Buhana' UNION ALL SELECT 'Udaipurwati' UNION ALL
  SELECT 'Mukundgarh' UNION ALL SELECT 'Mandawa' UNION ALL SELECT 'Dundlod' UNION ALL SELECT 'Bissau' UNION ALL
  SELECT 'Alsisar' UNION ALL SELECT 'Gudha Gorji' UNION ALL SELECT 'Singhana' UNION ALL SELECT 'Baggar' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area' UNION ALL SELECT 'Jaipur Road' UNION ALL
  SELECT 'Sikar Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jodhpur (46 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Jodhpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sardarpura' AS name UNION ALL SELECT 'Ratanada' UNION ALL SELECT 'Shastri Nagar' UNION ALL SELECT 'Paota' UNION ALL
  SELECT 'Chopasni' UNION ALL SELECT 'Chopasni Housing Board' UNION ALL SELECT 'Pal Road' UNION ALL SELECT 'Shikargarh' UNION ALL
  SELECT 'Jhalamand' UNION ALL SELECT 'Basni' UNION ALL SELECT 'Bhagat Ki Kothi' UNION ALL SELECT 'Pratap Nagar' UNION ALL
  SELECT 'Pratap Nagar Extension' UNION ALL SELECT 'AIIMS Road' UNION ALL SELECT 'Pali Road' UNION ALL SELECT 'Mandore' UNION ALL
  SELECT 'Banar' UNION ALL SELECT 'Chopsani Road' UNION ALL SELECT 'Residency Road' UNION ALL SELECT 'Circuit House Area' UNION ALL
  SELECT 'Rai Ka Bagh' UNION ALL SELECT 'Sojati Gate' UNION ALL SELECT 'Paota C Road' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Jalori Gate' UNION ALL SELECT 'Sardarpura C Road' UNION ALL SELECT 'Sardarpura B Road' UNION ALL SELECT 'Sardarpura A Road' UNION ALL
  SELECT 'Masuria' UNION ALL SELECT 'Kudi Bhagtasni' UNION ALL SELECT 'Kudi Housing Board' UNION ALL SELECT 'Shyam Nagar' UNION ALL
  SELECT 'Pal Gaon' UNION ALL SELECT 'Salawas' UNION ALL SELECT 'Boranada' UNION ALL SELECT 'Basni Industrial Area' UNION ALL
  SELECT 'Sangariya' UNION ALL SELECT 'Osian' UNION ALL SELECT 'Bilara' UNION ALL SELECT 'Bhopalgarh' UNION ALL
  SELECT 'Pipar City' UNION ALL SELECT 'Shergarh' UNION ALL SELECT 'Balesar' UNION ALL SELECT 'Luni' UNION ALL
  SELECT 'Tinwari' UNION ALL SELECT 'Mathania'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Karauli (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Karauli' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Karauli' AS name UNION ALL SELECT 'Hindaun City' UNION ALL SELECT 'Todabhim' UNION ALL SELECT 'Sapotra' UNION ALL
  SELECT 'Masalpur' UNION ALL SELECT 'Mandrayal' UNION ALL SELECT 'Shri Mahavirji' UNION ALL SELECT 'Nadoti' UNION ALL
  SELECT 'Kailadevi' UNION ALL SELECT 'Gudhachandraji' UNION ALL SELECT 'Suroth' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Dholpur Road' UNION ALL SELECT 'Bharatpur Road' UNION ALL
  SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Khairthal-Tijara (27 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Khairthal-Tijara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Khairthal' AS name UNION ALL SELECT 'Tijara' UNION ALL SELECT 'Bhiwadi' UNION ALL SELECT 'Tapukara' UNION ALL
  SELECT 'Dharuhera Road' UNION ALL SELECT 'Alampur' UNION ALL SELECT 'Kishangarh Bas' UNION ALL SELECT 'Kotkasim' UNION ALL
  SELECT 'Mundawar' UNION ALL SELECT 'Harsauli' UNION ALL SELECT 'Tijara Road' UNION ALL SELECT 'Alwar Road' UNION ALL
  SELECT 'Delhi Road' UNION ALL SELECT 'RIICO Industrial Area' UNION ALL SELECT 'Bhiwadi Industrial Area' UNION ALL SELECT 'UIT Bhiwadi' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Sector 1' UNION ALL SELECT 'Sector 2' UNION ALL SELECT 'Sector 3' UNION ALL
  SELECT 'Sector 4' UNION ALL SELECT 'Sector 5' UNION ALL SELECT 'Sector 6' UNION ALL SELECT 'Sector 7' UNION ALL
  SELECT 'Sector 8' UNION ALL SELECT 'Sector 9' UNION ALL SELECT 'Sector 10'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kota (36 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Kota' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Talwandi' AS name UNION ALL SELECT 'Mahaveer Nagar' UNION ALL SELECT 'Mahaveer Nagar Extension' UNION ALL SELECT 'Dadabari' UNION ALL
  SELECT 'Dadabari Extension' UNION ALL SELECT 'Vigyan Nagar' UNION ALL SELECT 'Indra Vihar' UNION ALL SELECT 'Rajeev Gandhi Nagar' UNION ALL
  SELECT 'Kunhadi' UNION ALL SELECT 'Landmark City' UNION ALL SELECT 'Jawahar Nagar' UNION ALL SELECT 'Gumanpura' UNION ALL
  SELECT 'Nayapura' UNION ALL SELECT 'Aerodrome Circle' UNION ALL SELECT 'Kota City' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Railway Colony' UNION ALL SELECT 'Rangbari' UNION ALL SELECT 'Sakatpura' UNION ALL SELECT 'Anantpura' UNION ALL
  SELECT 'DCM Road' UNION ALL SELECT 'Shrinath Puram' UNION ALL SELECT 'Swami Vivekanand Nagar' UNION ALL SELECT 'Kota Industrial Area' UNION ALL
  SELECT 'Chambal Garden Area' UNION ALL SELECT 'Bundi Road' UNION ALL SELECT 'Baran Road' UNION ALL SELECT 'Jhalawar Road' UNION ALL
  SELECT 'Rawatbhata Road' UNION ALL SELECT 'Ladpura' UNION ALL SELECT 'Ramganj Mandi' UNION ALL SELECT 'Sangod' UNION ALL
  SELECT 'Itawa' UNION ALL SELECT 'Pipalda' UNION ALL SELECT 'Sultanpur' UNION ALL SELECT 'Kanwas'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kotputli-Behror (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Kotputli-Behror' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kotputli' AS name UNION ALL SELECT 'Behror' UNION ALL SELECT 'Neemrana' UNION ALL SELECT 'Shahjahanpur' UNION ALL
  SELECT 'Bansur' UNION ALL SELECT 'Viratnagar' UNION ALL SELECT 'Paota' UNION ALL SELECT 'Pragpura' UNION ALL
  SELECT 'Hamjapur' UNION ALL SELECT 'Shahpura Road' UNION ALL SELECT 'Delhi-Jaipur Highway' UNION ALL SELECT 'Neemrana Industrial Area' UNION ALL
  SELECT 'Japanese Zone' UNION ALL SELECT 'RIICO Neemrana' UNION ALL SELECT 'Behror Industrial Area' UNION ALL SELECT 'Kotputli Industrial Area' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'NH-48 Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Nagaur (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Nagaur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Nagaur' AS name UNION ALL SELECT 'Merta City' UNION ALL SELECT 'Merta Road' UNION ALL SELECT 'Degana' UNION ALL
  SELECT 'Didwana' UNION ALL SELECT 'Kuchaman' UNION ALL SELECT 'Makrana' UNION ALL SELECT 'Ladnun' UNION ALL
  SELECT 'Jayal' UNION ALL SELECT 'Parbatsar' UNION ALL SELECT 'Nawa' UNION ALL SELECT 'Mundwa' UNION ALL
  SELECT 'Khinvsar' UNION ALL SELECT 'Kuchera' UNION ALL SELECT 'Basni' UNION ALL SELECT 'Riyan Badi' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Jodhpur Road' UNION ALL SELECT 'Bikaner Road' UNION ALL
  SELECT 'Industrial Area' UNION ALL SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Pali (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Pali' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Pali' AS name UNION ALL SELECT 'Sumerpur' UNION ALL SELECT 'Sojat' UNION ALL SELECT 'Jaitaran' UNION ALL
  SELECT 'Bali' UNION ALL SELECT 'Rani' UNION ALL SELECT 'Marwar Junction' UNION ALL SELECT 'Sadri' UNION ALL
  SELECT 'Desuri' UNION ALL SELECT 'Rohat' UNION ALL SELECT 'Raipur' UNION ALL SELECT 'Kharchi' UNION ALL
  SELECT 'Falna' UNION ALL SELECT 'Bagri Nagar' UNION ALL SELECT 'Jawai Bandh' UNION ALL SELECT 'Sojat Road' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area' UNION ALL SELECT 'RIICO Area' UNION ALL
  SELECT 'Jodhpur Road' UNION ALL SELECT 'Sumerpur Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Phalodi (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Phalodi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Phalodi' AS name UNION ALL SELECT 'Osian' UNION ALL SELECT 'Pokaran' UNION ALL SELECT 'Dechu' UNION ALL
  SELECT 'Bap' UNION ALL SELECT 'Lohawat' UNION ALL SELECT 'Baapini' UNION ALL SELECT 'Aau' UNION ALL
  SELECT 'Chamu' UNION ALL SELECT 'Tinwari' UNION ALL SELECT 'Kolu Pabuji' UNION ALL SELECT 'Khichan' UNION ALL
  SELECT 'Nagaur Road' UNION ALL SELECT 'Jodhpur Road' UNION ALL SELECT 'Jaisalmer Road' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Pratapgarh (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Pratapgarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Pratapgarh' AS name UNION ALL SELECT 'Chhoti Sadri' UNION ALL SELECT 'Dhariyawad' UNION ALL SELECT 'Arnod' UNION ALL
  SELECT 'Peepalkhoont' UNION ALL SELECT 'Dalot' UNION ALL SELECT 'Suhagpura' UNION ALL SELECT 'Rathanjana' UNION ALL
  SELECT 'Dhariyawad Road' UNION ALL SELECT 'Neemuch Road' UNION ALL SELECT 'Mandsaur Road' UNION ALL SELECT 'Udaipur Road' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Rajsamand (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Rajsamand' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Rajsamand' AS name UNION ALL SELECT 'Nathdwara' UNION ALL SELECT 'Kankroli' UNION ALL SELECT 'Amet' UNION ALL
  SELECT 'Deogarh' UNION ALL SELECT 'Railmagra' UNION ALL SELECT 'Khamnore' UNION ALL SELECT 'Kelwara' UNION ALL
  SELECT 'Charbhuja' UNION ALL SELECT 'Delwara' UNION ALL SELECT 'Haldi Ghati' UNION ALL SELECT 'Gaurav Path' UNION ALL
  SELECT 'Udaipur Road' UNION ALL SELECT 'Nathdwara Road' UNION ALL SELECT 'Bhilwara Road' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Salumbar (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Salumbar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Salumbar' AS name UNION ALL SELECT 'Sarada' UNION ALL SELECT 'Jhallara' UNION ALL SELECT 'Kherwara' UNION ALL
  SELECT 'Rishabhdeo' UNION ALL SELECT 'Jaisamand' UNION ALL SELECT 'Semari' UNION ALL SELECT 'Lasadia' UNION ALL
  SELECT 'Kotra' UNION ALL SELECT 'Rikhabdeo' UNION ALL SELECT 'Udaipur Road' UNION ALL SELECT 'Dungarpur Road' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sawai Madhopur (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Sawai Madhopur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sawai Madhopur' AS name UNION ALL SELECT 'Ranthambore' UNION ALL SELECT 'Gangapur City' UNION ALL SELECT 'Bonli' UNION ALL
  SELECT 'Khandar' UNION ALL SELECT 'Chauth Ka Barwara' UNION ALL SELECT 'Bamanwas' UNION ALL SELECT 'Malarna Dungar' UNION ALL
  SELECT 'Khilchipur' UNION ALL SELECT 'Sherpur' UNION ALL SELECT 'Kundera' UNION ALL SELECT 'Adinath Nagar' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Main Market' UNION ALL SELECT 'Ranthambore Road' UNION ALL
  SELECT 'Jaipur Road' UNION ALL SELECT 'Kota Road' UNION ALL SELECT 'Industrial Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sikar (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Sikar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sikar' AS name UNION ALL SELECT 'Fatehpur' UNION ALL SELECT 'Neem Ka Thana' UNION ALL SELECT 'Laxmangarh' UNION ALL
  SELECT 'Sri Madhopur' UNION ALL SELECT 'Khandela' UNION ALL SELECT 'Danta Ramgarh' UNION ALL SELECT 'Reengus' UNION ALL
  SELECT 'Ramgarh Shekhawati' UNION ALL SELECT 'Palsana' UNION ALL SELECT 'Piprali' UNION ALL SELECT 'Radhakishanpura' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Jhunjhunu Road' UNION ALL SELECT 'Fatehpur Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area' UNION ALL SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sirohi (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Sirohi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sirohi' AS name UNION ALL SELECT 'Mount Abu' UNION ALL SELECT 'Abu Road' UNION ALL SELECT 'Pindwara' UNION ALL
  SELECT 'Sheoganj' UNION ALL SELECT 'Reodar' UNION ALL SELECT 'Shivganj' UNION ALL SELECT 'Swaroopganj' UNION ALL
  SELECT 'Mandar' UNION ALL SELECT 'Anadara' UNION ALL SELECT 'Delwara' UNION ALL SELECT 'Abu Road Industrial Area' UNION ALL
  SELECT 'RIICO Area' UNION ALL SELECT 'Railway Colony' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Palanpur Road' UNION ALL
  SELECT 'Udaipur Road' UNION ALL SELECT 'Housing Board'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sri Ganganagar (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Sri Ganganagar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sri Ganganagar' AS name UNION ALL SELECT 'Suratgarh' UNION ALL SELECT 'Raisinghnagar' UNION ALL SELECT 'Anupgarh' UNION ALL
  SELECT 'Padampur' UNION ALL SELECT 'Sadulshahar' UNION ALL SELECT 'Karanpur' UNION ALL SELECT 'Gharsana' UNION ALL
  SELECT 'Vijaynagar' UNION ALL SELECT 'Kesrisinghpur' UNION ALL SELECT 'Rawla' UNION ALL SELECT 'Lalgarh Jattan' UNION ALL
  SELECT 'Ridmalsar' UNION ALL SELECT 'Chak 3 E Small' UNION ALL SELECT 'Hindumalkot' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Hanumangarh Road' UNION ALL SELECT 'Suratgarh Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Housing Board' UNION ALL
  SELECT 'Industrial Area' UNION ALL SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Tonk (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Tonk' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Tonk' AS name UNION ALL SELECT 'Deoli' UNION ALL SELECT 'Niwai' UNION ALL SELECT 'Malpura' UNION ALL
  SELECT 'Uniara' UNION ALL SELECT 'Todaraisingh' UNION ALL SELECT 'Newai' UNION ALL SELECT 'Peeplu' UNION ALL
  SELECT 'Aligarh' UNION ALL SELECT 'Nagarfort' UNION ALL SELECT 'Dooni' UNION ALL SELECT 'Banetha' UNION ALL
  SELECT 'Station Road' UNION ALL SELECT 'Jaipur Road' UNION ALL SELECT 'Kota Road' UNION ALL SELECT 'Sanganer Road' UNION ALL
  SELECT 'Housing Board' UNION ALL SELECT 'Industrial Area' UNION ALL SELECT 'RIICO Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Udaipur (57 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @rj AND `name` = 'Udaipur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Udaipur' AS name UNION ALL SELECT 'Hiran Magri' UNION ALL SELECT 'Sector 3' UNION ALL SELECT 'Sector 4' UNION ALL
  SELECT 'Sector 5' UNION ALL SELECT 'Sector 6' UNION ALL SELECT 'Sector 7' UNION ALL SELECT 'Sector 8' UNION ALL
  SELECT 'Sector 9' UNION ALL SELECT 'Sector 11' UNION ALL SELECT 'Sector 12' UNION ALL SELECT 'Sector 13' UNION ALL
  SELECT 'Sector 14' UNION ALL SELECT 'Bhuwana' UNION ALL SELECT 'Shobhagpura' UNION ALL SELECT 'Fatehpura' UNION ALL
  SELECT 'Panchwati' UNION ALL SELECT 'Saheli Nagar' UNION ALL SELECT 'Rani Road' UNION ALL SELECT 'Bhopalpura' UNION ALL
  SELECT 'Ashok Nagar' UNION ALL SELECT 'Sardarpura' UNION ALL SELECT 'Ambamata' UNION ALL SELECT 'Pologround' UNION ALL
  SELECT 'Surajpole' UNION ALL SELECT 'Hathipole' UNION ALL SELECT 'Bapu Bazaar' UNION ALL SELECT 'Delhi Gate' UNION ALL
  SELECT 'Udiapole' UNION ALL SELECT 'Pratap Nagar' UNION ALL SELECT 'Savina' UNION ALL SELECT 'Debari' UNION ALL
  SELECT 'Sukher' UNION ALL SELECT 'Goverdhan Vilas' UNION ALL SELECT 'Bedla' UNION ALL SELECT 'Kaladwas' UNION ALL
  SELECT 'Titardi' UNION ALL SELECT 'Balicha' UNION ALL SELECT 'Madri' UNION ALL SELECT 'Madri Industrial Area' UNION ALL
  SELECT 'Dabok' UNION ALL SELECT 'Nathdwara Road' UNION ALL SELECT 'Chittorgarh Road' UNION ALL SELECT 'Ahmedabad Road' UNION ALL
  SELECT 'Banswara Road' UNION ALL SELECT 'Salumber Road' UNION ALL SELECT 'Gogunda' UNION ALL SELECT 'Jhadol' UNION ALL
  SELECT 'Kherwara' UNION ALL SELECT 'Rishabhdeo' UNION ALL SELECT 'Salumbar' UNION ALL SELECT 'Mavli' UNION ALL
  SELECT 'Vallabhnagar' UNION ALL SELECT 'Bhindar' UNION ALL SELECT 'Kanor' UNION ALL SELECT 'Kotra' UNION ALL
  SELECT 'Sarada'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

