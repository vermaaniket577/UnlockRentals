-- ==============================================================================
-- CHHATTISGARH: ALL 33 DISTRICTS & 520 LOCALITIES MASTER SQL QUERY
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Chhattisgarh' (CT)
INSERT INTO `states` (`code`, `name`)
SELECT 'CT', 'Chhattisgarh' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'CT' OR `name` = 'Chhattisgarh');

SET @ct = (SELECT `id` FROM `states` WHERE `code` = 'CT' LIMIT 1);

-- 2. Ensure All 33 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @ct, d.name FROM (
  SELECT 'Raipur' AS name UNION ALL SELECT 'Bilaspur' AS name UNION ALL SELECT 'Durg' AS name UNION ALL SELECT 'Korba' AS name UNION ALL SELECT 'Rajnandgaon' AS name UNION ALL SELECT 'Raigarh' AS name UNION ALL SELECT 'Bastar' AS name UNION ALL SELECT 'Surguja' AS name UNION ALL SELECT 'Balod' AS name UNION ALL SELECT 'Baloda Bazar' AS name UNION ALL SELECT 'Balrampur' AS name UNION ALL SELECT 'Bemetara' AS name UNION ALL SELECT 'Bijapur' AS name UNION ALL SELECT 'Dantewada' AS name UNION ALL SELECT 'Dhamtari' AS name UNION ALL SELECT 'Gariaband' AS name UNION ALL SELECT 'Gaurela-Pendra-Marwahi' AS name UNION ALL SELECT 'Janjgir-Champa' AS name UNION ALL SELECT 'Jashpur' AS name UNION ALL SELECT 'Kabirdham' AS name UNION ALL SELECT 'Kanker' AS name UNION ALL SELECT 'Khairagarh-Chhuikhadan-Gandai' AS name UNION ALL SELECT 'Kondagaon' AS name UNION ALL SELECT 'Koriya' AS name UNION ALL SELECT 'Mahasamund' AS name UNION ALL SELECT 'Manendragarh-Chirmiri-Bharatpur' AS name UNION ALL SELECT 'Mohla-Manpur-Ambagarh Chowki' AS name UNION ALL SELECT 'Mungeli' AS name UNION ALL SELECT 'Narayanpur' AS name UNION ALL SELECT 'Sakti' AS name UNION ALL SELECT 'Sarangarh-Bilaigarh' AS name UNION ALL SELECT 'Sukma' AS name UNION ALL SELECT 'Surajpur' AS name
) d WHERE NOT EXISTS (SELECT 1 FROM `districts` WHERE `state_id` = @ct AND LOWER(`name`) = LOWER(d.name));

-- 3. Insert Localities for All 33 Districts
-- District: Raipur (52 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Raipur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Pandri' AS name UNION ALL SELECT 'Civil Lines Raipur' AS name UNION ALL SELECT 'Devendra Nagar' AS name UNION ALL SELECT 'Shankar Nagar' AS name UNION ALL
  SELECT 'Telibandha' AS name UNION ALL SELECT 'VIP Road' AS name UNION ALL SELECT 'VIP Road Raipur' AS name UNION ALL SELECT 'Samta Colony' AS name UNION ALL
  SELECT 'Choubey Colony' AS name UNION ALL SELECT 'Tatibandh' AS name UNION ALL SELECT 'GE Road' AS name UNION ALL SELECT 'GE Road Raipur' AS name UNION ALL
  SELECT 'Gudhiyari' AS name UNION ALL SELECT 'Fafadih' AS name UNION ALL SELECT 'Mowa' AS name UNION ALL SELECT 'Khamardih' AS name UNION ALL
  SELECT 'Saddu' AS name UNION ALL SELECT 'Amlidih' AS name UNION ALL SELECT 'Bhatagaon' AS name UNION ALL SELECT 'Kota Raipur' AS name UNION ALL
  SELECT 'DDU Nagar' AS name UNION ALL SELECT 'Sunder Nagar' AS name UNION ALL SELECT 'Tagore Nagar' AS name UNION ALL SELECT 'Katora Talab' AS name UNION ALL
  SELECT 'Byron Bazar' AS name UNION ALL SELECT 'Shailendra Nagar' AS name UNION ALL SELECT 'Baijnathpara' AS name UNION ALL SELECT 'Gol Bazar' AS name UNION ALL
  SELECT 'Malviya Road' AS name UNION ALL SELECT 'Jai Stambh Chowk' AS name UNION ALL SELECT 'Pandri Cloth Market' AS name UNION ALL SELECT 'Urla Industrial Area' AS name UNION ALL
  SELECT 'Siltara Industrial Area' AS name UNION ALL SELECT 'Bhanpuri' AS name UNION ALL SELECT 'Sarona' AS name UNION ALL SELECT 'Rawabhata' AS name UNION ALL
  SELECT 'Nava Raipur (Atal Nagar)' AS name UNION ALL SELECT 'Capitol Complex' AS name UNION ALL SELECT 'Purkhauti Muktangan' AS name UNION ALL SELECT 'Jungle Safari area' AS name UNION ALL
  SELECT 'IIIT Raipur area' AS name UNION ALL SELECT 'IIM Raipur area' AS name UNION ALL SELECT 'Central Park Nava Raipur' AS name UNION ALL SELECT 'Abhanpur' AS name UNION ALL
  SELECT 'Arang' AS name UNION ALL SELECT 'Tilda Newra' AS name UNION ALL SELECT 'Mandir Hasaud' AS name UNION ALL SELECT 'Kharora' AS name UNION ALL
  SELECT 'Gobra Nawapara' AS name UNION ALL SELECT 'Dharsiwa' AS name UNION ALL SELECT 'Birgaon' AS name UNION ALL SELECT 'Mana Camp (Airport Area)' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bilaspur (34 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bilaspur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Civil Lines Bilaspur' AS name UNION ALL SELECT 'Vyapar Vihar' AS name UNION ALL SELECT 'Link Road' AS name UNION ALL SELECT 'Link Road Bilaspur' AS name UNION ALL
  SELECT 'Sarkanda' AS name UNION ALL SELECT 'Rajendra Nagar Bilaspur' AS name UNION ALL SELECT 'Mangla' AS name UNION ALL SELECT 'Nehru Nagar Bilaspur' AS name UNION ALL
  SELECT 'Tifra' AS name UNION ALL SELECT 'Sirgitti Industrial Area' AS name UNION ALL SELECT 'Torwa' AS name UNION ALL SELECT 'Koni' AS name UNION ALL
  SELECT 'Dayalband' AS name UNION ALL SELECT 'Jarhabhatha' AS name UNION ALL SELECT 'Subhash Nagar Bilaspur' AS name UNION ALL SELECT 'Telipara' AS name UNION ALL
  SELECT 'Old Bus Stand' AS name UNION ALL SELECT 'New Bus Stand Bodri' AS name UNION ALL SELECT 'High Court Area (Bodri)' AS name UNION ALL SELECT 'Seepat Road' AS name UNION ALL
  SELECT 'Uslapur' AS name UNION ALL SELECT 'Chakarbhata' AS name UNION ALL SELECT 'Hemu Nagar' AS name UNION ALL SELECT 'Magarpara' AS name UNION ALL
  SELECT 'Kududand' AS name UNION ALL SELECT 'Ratanpur (Maa Mahamaya)' AS name UNION ALL SELECT 'Kota (Bilaspur)' AS name UNION ALL SELECT 'Takhatpur' AS name UNION ALL
  SELECT 'Bilha' AS name UNION ALL SELECT 'Masturi' AS name UNION ALL SELECT 'Seepat (NTPC Area)' AS name UNION ALL SELECT 'Mallhar (Ancient Heritage)' AS name UNION ALL
  SELECT 'Sakri' AS name UNION ALL SELECT 'Chakarbhata Camp' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Durg (50 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Durg' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bhilai Steel City' AS name UNION ALL SELECT 'Sector 1 Bhilai' AS name UNION ALL SELECT 'Sector 2 Bhilai' AS name UNION ALL SELECT 'Sector 3 Bhilai' AS name UNION ALL
  SELECT 'Sector 4 Bhilai' AS name UNION ALL SELECT 'Sector 5 Bhilai' AS name UNION ALL SELECT 'Sector 6 Bhilai' AS name UNION ALL SELECT 'Sector 7 Bhilai' AS name UNION ALL
  SELECT 'Sector 8 Bhilai' AS name UNION ALL SELECT 'Sector 9 Bhilai' AS name UNION ALL SELECT 'Sector 10 Bhilai' AS name UNION ALL SELECT 'Nehru Nagar (East & West)' AS name UNION ALL
  SELECT 'Smriti Nagar' AS name UNION ALL SELECT 'Supela' AS name UNION ALL SELECT 'Power House' AS name UNION ALL SELECT 'Power House Bhilai' AS name UNION ALL
  SELECT 'Vaishali Nagar Bhilai' AS name UNION ALL SELECT 'Junwani' AS name UNION ALL SELECT 'Kohka' AS name UNION ALL SELECT 'Radhika Nagar' AS name UNION ALL
  SELECT 'Surya Treasure Island Mall area' AS name UNION ALL SELECT 'Civic Centre' AS name UNION ALL SELECT 'Khursipar' AS name UNION ALL SELECT 'Charoda' AS name UNION ALL
  SELECT 'Bhilai 3' AS name UNION ALL SELECT 'Jamul Industrial Area' AS name UNION ALL SELECT 'Kumhari' AS name UNION ALL SELECT 'Kailash Nagar' AS name UNION ALL
  SELECT 'Pragati Nagar' AS name UNION ALL SELECT 'Priyadarshini Nagar' AS name UNION ALL SELECT 'Risali' AS name UNION ALL SELECT 'Durg City' AS name UNION ALL
  SELECT 'Padmanabhpur' AS name UNION ALL SELECT 'Adarsh Nagar Durg' AS name UNION ALL SELECT 'Ganjpara' AS name UNION ALL SELECT 'Mohan Nagar Durg' AS name UNION ALL
  SELECT 'Titurdih' AS name UNION ALL SELECT 'Deepak Nagar' AS name UNION ALL SELECT 'Station Road Durg' AS name UNION ALL SELECT 'Kasaridih' AS name UNION ALL
  SELECT 'Borsi' AS name UNION ALL SELECT 'Borsi Extension' AS name UNION ALL SELECT 'Potia Kala' AS name UNION ALL SELECT 'Utai Road' AS name UNION ALL
  SELECT 'Pulgaon' AS name UNION ALL SELECT 'Patan (Durg)' AS name UNION ALL SELECT 'Dhamdha' AS name UNION ALL SELECT 'Utai' AS name UNION ALL
  SELECT 'Ahiwara' AS name UNION ALL SELECT 'Borai Industrial Area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Korba (28 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Korba' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Korba City' AS name UNION ALL SELECT 'TP Nagar Korba' AS name UNION ALL SELECT 'Transport Nagar' AS name UNION ALL SELECT 'Kosabadi' AS name UNION ALL
  SELECT 'CSEB Colony' AS name UNION ALL SELECT 'Balco Township' AS name UNION ALL SELECT 'NTPC Township (Jamnipali)' AS name UNION ALL SELECT 'Kusmunda' AS name UNION ALL
  SELECT 'Gevra Project Area' AS name UNION ALL SELECT 'Dipka' AS name UNION ALL SELECT 'Darri' AS name UNION ALL SELECT 'Risdi' AS name UNION ALL
  SELECT 'Manikpur' AS name UNION ALL SELECT 'Rampur Korba' AS name UNION ALL SELECT 'Rajgamar' AS name UNION ALL SELECT 'Budhwari' AS name UNION ALL
  SELECT 'Niharika' AS name UNION ALL SELECT 'Banki Mongra' AS name UNION ALL SELECT 'Pali Road' AS name UNION ALL SELECT 'Chhuri' AS name UNION ALL
  SELECT 'Katghora' AS name UNION ALL SELECT 'Pali (Korba)' AS name UNION ALL SELECT 'Kartala' AS name UNION ALL SELECT 'Hardibazar' AS name UNION ALL
  SELECT 'Pondi Uproda' AS name UNION ALL SELECT 'Kudmura' AS name UNION ALL SELECT 'Bango Dam area' AS name UNION ALL SELECT 'Chaiturgarh area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Rajnandgaon (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Rajnandgaon' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Rajnandgaon Town' AS name UNION ALL SELECT 'Civil Lines Rajnandgaon' AS name UNION ALL SELECT 'Basantpur' AS name UNION ALL SELECT 'Lakhmi Nagar' AS name UNION ALL
  SELECT 'Kaurinbhatha' AS name UNION ALL SELECT 'Ganj Line' AS name UNION ALL SELECT 'Cinema Line' AS name UNION ALL SELECT 'Nandai' AS name UNION ALL
  SELECT 'Ramnagar Rajnandgaon' AS name UNION ALL SELECT 'Shankar Nagar' AS name UNION ALL SELECT 'Chikhali' AS name UNION ALL SELECT 'Pendri' AS name UNION ALL
  SELECT 'Tedesara Industrial Area' AS name UNION ALL SELECT 'Motipur' AS name UNION ALL SELECT 'Bajrangpur' AS name UNION ALL SELECT 'Dongargarh (Maa Bamleshwari)' AS name UNION ALL
  SELECT 'Dongargaon' AS name UNION ALL SELECT 'Chhuria' AS name UNION ALL SELECT 'Somni' AS name UNION ALL SELECT 'Ghumka' AS name UNION ALL
  SELECT 'Mohara' AS name UNION ALL SELECT 'Baghera' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Raigarh (23 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Raigarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Raigarh City' AS name UNION ALL SELECT 'Boirdad' AS name UNION ALL SELECT 'Kirodimal Nagar' AS name UNION ALL SELECT 'Dhimrapur' AS name UNION ALL
  SELECT 'Jindal Industrial Area' AS name UNION ALL SELECT 'Chakradhar Nagar' AS name UNION ALL SELECT 'Station Road Raigarh' AS name UNION ALL SELECT 'Sanjay Complex' AS name UNION ALL
  SELECT 'Gauri Shankar Temple area' AS name UNION ALL SELECT 'Rambhata' AS name UNION ALL SELECT 'Darogapara' AS name UNION ALL SELECT 'Kotwali Road' AS name UNION ALL
  SELECT 'Jagatpur' AS name UNION ALL SELECT 'Chandmari' AS name UNION ALL SELECT 'Kabir Chowk' AS name UNION ALL SELECT 'Kharsia' AS name UNION ALL
  SELECT 'Gharghoda' AS name UNION ALL SELECT 'Lailunga' AS name UNION ALL SELECT 'Tamnar' AS name UNION ALL SELECT 'Pussore' AS name UNION ALL
  SELECT 'Dharamjaigarh' AS name UNION ALL SELECT 'Kapu' AS name UNION ALL SELECT 'Chhal' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bastar (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bastar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jagdalpur City' AS name UNION ALL SELECT 'Dharampura' AS name UNION ALL SELECT 'Bodhbhat' AS name UNION ALL SELECT 'Geedam Road' AS name UNION ALL
  SELECT 'Chitrakote Road' AS name UNION ALL SELECT 'Tirathgarh Road' AS name UNION ALL SELECT 'Sanjay Market' AS name UNION ALL SELECT 'Motitalab' AS name UNION ALL
  SELECT 'Kumbharkot' AS name UNION ALL SELECT 'Sirhasar Chowk' AS name UNION ALL SELECT 'Balaji Ward' AS name UNION ALL SELECT 'Pratapganj' AS name UNION ALL
  SELECT 'Naya Munda' AS name UNION ALL SELECT 'Adawal' AS name UNION ALL SELECT 'Asna' AS name UNION ALL SELECT 'Hatkachora' AS name UNION ALL
  SELECT 'Nagarnar (Steel Plant Area)' AS name UNION ALL SELECT 'Bastar Town' AS name UNION ALL SELECT 'Tokapal' AS name UNION ALL SELECT 'Lohandiguda' AS name UNION ALL
  SELECT 'Bakawand' AS name UNION ALL SELECT 'Darbha' AS name UNION ALL SELECT 'Bastanar' AS name UNION ALL SELECT 'Chitrakote' AS name UNION ALL
  SELECT 'Kanger Valley National Park area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Surguja (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Surguja' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Ambikapur City' AS name UNION ALL SELECT 'Ring Road Ambikapur' AS name UNION ALL SELECT 'Gandhi Chowk Ambikapur' AS name UNION ALL SELECT 'Banaras Road' AS name UNION ALL
  SELECT 'Bilaspur Road' AS name UNION ALL SELECT 'Ramanujganj Road' AS name UNION ALL SELECT 'Sadar Road Ambikapur' AS name UNION ALL SELECT 'Brahma Road' AS name UNION ALL
  SELECT 'Kedarpur' AS name UNION ALL SELECT 'Ghari Chowk' AS name UNION ALL SELECT 'Godhanpur' AS name UNION ALL SELECT 'Namnakala' AS name UNION ALL
  SELECT 'Mahamaya Mandir area' AS name UNION ALL SELECT 'Mission Chowk' AS name UNION ALL SELECT 'Chopra Colony' AS name UNION ALL SELECT 'Mainpat (Hill Station)' AS name UNION ALL
  SELECT 'Tiger Point' AS name UNION ALL SELECT 'Tibetan Camp Mainpat' AS name UNION ALL SELECT 'Sitapur' AS name UNION ALL SELECT 'Lundra' AS name UNION ALL
  SELECT 'Lakhanpur' AS name UNION ALL SELECT 'Udaipur (Surguja)' AS name UNION ALL SELECT 'Batauli' AS name UNION ALL SELECT 'Darima (Airport Area)' AS name UNION ALL
  SELECT 'Raghunathpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Balod (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Balod' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Balod Town' AS name UNION ALL SELECT 'Dalli Rajhara (Mining Township)' AS name UNION ALL SELECT 'Gunderdehi' AS name UNION ALL SELECT 'Dondi Luhara' AS name UNION ALL
  SELECT 'Gurur' AS name UNION ALL SELECT 'Dondi' AS name UNION ALL SELECT 'Sanod' AS name UNION ALL SELECT 'Kusumkasa' AS name UNION ALL
  SELECT 'Arjunda' AS name UNION ALL SELECT 'Sikosa' AS name UNION ALL SELECT 'Lohara' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Baloda Bazar (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Baloda Bazar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Baloda Bazar Town' AS name UNION ALL SELECT 'Bhatapara (Railway Junction)' AS name UNION ALL SELECT 'Kasdol' AS name UNION ALL SELECT 'Simga' AS name UNION ALL
  SELECT 'Palari' AS name UNION ALL SELECT 'Lawon' AS name UNION ALL SELECT 'Giraudpuri (Satnami Pilgrimage)' AS name UNION ALL SELECT 'Suhela' AS name UNION ALL
  SELECT 'Sonakhan' AS name UNION ALL SELECT 'Bhatgaon (Baloda Bazar)' AS name UNION ALL SELECT 'Rawan (Cement Zone)' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Balrampur (10 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Balrampur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Balrampur Town (CG)' AS name UNION ALL SELECT 'Ramanujganj' AS name UNION ALL SELECT 'Kusmi' AS name UNION ALL SELECT 'Samri' AS name UNION ALL
  SELECT 'Rajpur (Balrampur)' AS name UNION ALL SELECT 'Wadrafnagar' AS name UNION ALL SELECT 'Shankargarh' AS name UNION ALL SELECT 'Tatapani (Hot Springs)' AS name UNION ALL
  SELECT 'Raghunathnagar' AS name UNION ALL SELECT 'Dhorpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bemetara (9 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bemetara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bemetara Town' AS name UNION ALL SELECT 'Saja' AS name UNION ALL SELECT 'Berla' AS name UNION ALL SELECT 'Nawagarh (Bemetara)' AS name UNION ALL
  SELECT 'Thanakhamria' AS name UNION ALL SELECT 'Deokar' AS name UNION ALL SELECT 'Khandwa (Bemetara)' AS name UNION ALL SELECT 'Parpodi' AS name UNION ALL
  SELECT 'Sambalpur (Bemetara)' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bijapur (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bijapur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bijapur Town (CG)' AS name UNION ALL SELECT 'Bhopalpatnam' AS name UNION ALL SELECT 'Awapalli' AS name UNION ALL SELECT 'Bhairamgarh' AS name UNION ALL
  SELECT 'Usur' AS name UNION ALL SELECT 'Gangaloor' AS name UNION ALL SELECT 'Kutru' AS name UNION ALL SELECT 'Modakpal' AS name UNION ALL
  SELECT 'Madded' AS name UNION ALL SELECT 'Basaguda' AS name UNION ALL SELECT 'Bedre' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dantewada (10 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Dantewada' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dantewada Town (Maa Danteshwari Temple)' AS name UNION ALL SELECT 'Kirandul (NMDC Mining Complex)' AS name UNION ALL SELECT 'Bacheli (NMDC Area)' AS name UNION ALL SELECT 'Geedam' AS name UNION ALL
  SELECT 'Katekalyan' AS name UNION ALL SELECT 'Kuakonda' AS name UNION ALL SELECT 'Barsur (Twin Ganesha Temple Area)' AS name UNION ALL SELECT 'Bhansi' AS name UNION ALL
  SELECT 'Kamalur' AS name UNION ALL SELECT 'Palnar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dhamtari (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Dhamtari' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dhamtari City' AS name UNION ALL SELECT 'Ratnabandh' AS name UNION ALL SELECT 'Rudri (Civil Hub)' AS name UNION ALL SELECT 'Sihawa Road' AS name UNION ALL
  SELECT 'Raipur Road Dhamtari' AS name UNION ALL SELECT 'Danitola' AS name UNION ALL SELECT 'Itwari Bazar' AS name UNION ALL SELECT 'Gangrel Dam (Ravishankar Sagar) Area' AS name UNION ALL
  SELECT 'Kurud' AS name UNION ALL SELECT 'Nagari' AS name UNION ALL SELECT 'Sihawa (Mahanadi Origin)' AS name UNION ALL SELECT 'Magarlod' AS name UNION ALL
  SELECT 'Bhakhara' AS name UNION ALL SELECT 'Megha' AS name UNION ALL SELECT 'Gujra' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Gariaband (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Gariaband' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gariaband Town' AS name UNION ALL SELECT 'Rajim (Triveni Sangam Pilgrimage)' AS name UNION ALL SELECT 'Chhura' AS name UNION ALL SELECT 'Mainpur' AS name UNION ALL
  SELECT 'Deobhog' AS name UNION ALL SELECT 'Fingeshwar' AS name UNION ALL SELECT 'Rasela' AS name UNION ALL SELECT 'Kholagarh' AS name UNION ALL
  SELECT 'Ghatarani Temple Area' AS name UNION ALL SELECT 'Jatmayi Temple Area' AS name UNION ALL SELECT 'Bindranawagarh' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Gaurela-Pendra-Marwahi (8 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Gaurela-Pendra-Marwahi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gaurela Town' AS name UNION ALL SELECT 'Pendra Road (Railway Hub)' AS name UNION ALL SELECT 'Pendra Town' AS name UNION ALL SELECT 'Marwahi' AS name UNION ALL
  SELECT 'Semra' AS name UNION ALL SELECT 'Kotmi' AS name UNION ALL SELECT 'Kabir Chabutra (Amarkantak Border)' AS name UNION ALL SELECT 'Dhanpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Janjgir-Champa (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Janjgir-Champa' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Janjgir Town' AS name UNION ALL SELECT 'Champa (Kosa Silk Hub)' AS name UNION ALL SELECT 'Naila (Railway Station Area)' AS name UNION ALL SELECT 'Akaltara' AS name UNION ALL
  SELECT 'Pamgarh' AS name UNION ALL SELECT 'Nawagarh (Janjgir)' AS name UNION ALL SELECT 'Baloda (Janjgir)' AS name UNION ALL SELECT 'Shivrinarayan (Triveni Sangam)' AS name UNION ALL
  SELECT 'Kharod (Laxmaneshwar Temple)' AS name UNION ALL SELECT 'Rahod' AS name UNION ALL SELECT 'Banari' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jashpur (12 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Jashpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jashpur Nagar' AS name UNION ALL SELECT 'Kunkuri (Cathedral Area)' AS name UNION ALL SELECT 'Pathalgaon' AS name UNION ALL SELECT 'Bagicha' AS name UNION ALL
  SELECT 'Duldula' AS name UNION ALL SELECT 'Farsabahar' AS name UNION ALL SELECT 'Kansabel' AS name UNION ALL SELECT 'Manora' AS name UNION ALL
  SELECT 'Sanna' AS name UNION ALL SELECT 'Kotba' AS name UNION ALL SELECT 'Lodh Falls Area' AS name UNION ALL SELECT 'Kailash Gufa Area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kabirdham (10 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Kabirdham' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kawardha Town' AS name UNION ALL SELECT 'Bhoramdeo Temple Area' AS name UNION ALL SELECT 'Bodla' AS name UNION ALL SELECT 'Pandariya' AS name UNION ALL
  SELECT 'Sahaspur Lohara' AS name UNION ALL SELECT 'Rengakhar' AS name UNION ALL SELECT 'Chilphi (Ghat Valley)' AS name UNION ALL SELECT 'Pipariya (Kawardha)' AS name UNION ALL
  SELECT 'Dasrangpur' AS name UNION ALL SELECT 'Pandatarai' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kanker (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Kanker' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kanker Town' AS name UNION ALL SELECT 'Charama' AS name UNION ALL SELECT 'Narharpur' AS name UNION ALL SELECT 'Antagarh' AS name UNION ALL
  SELECT 'Bhanupratappur' AS name UNION ALL SELECT 'Pakhanjore' AS name UNION ALL SELECT 'Koyalibeda' AS name UNION ALL SELECT 'Kanker Palace Area' AS name UNION ALL
  SELECT 'Gadiya Mountain Area' AS name UNION ALL SELECT 'Dudhawa Dam Area' AS name UNION ALL SELECT 'Sambalpur (Kanker)' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Khairagarh-Chhuikhadan-Gandai (8 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Khairagarh-Chhuikhadan-Gandai' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Khairagarh (Music University)' AS name UNION ALL SELECT 'Chhuikhadan' AS name UNION ALL SELECT 'Gandai' AS name UNION ALL SELECT 'Salhewara' AS name UNION ALL
  SELECT 'Bakarkatta' AS name UNION ALL SELECT 'Jalbandha' AS name UNION ALL SELECT 'Khamaria' AS name UNION ALL SELECT 'Dongargarh Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kondagaon (9 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Kondagaon' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kondagaon Town (Bell Metal Craft Hub)' AS name UNION ALL SELECT 'Keshkal (Keshkal Ghat Valley)' AS name UNION ALL SELECT 'Makdi' AS name UNION ALL SELECT 'Pharasgaon' AS name UNION ALL
  SELECT 'Bade Rajpur' AS name UNION ALL SELECT 'Dahikonga' AS name UNION ALL SELECT 'Mardapal' AS name UNION ALL SELECT 'Vishrampuri' AS name UNION ALL
  SELECT 'Golawand' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Koriya (8 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Koriya' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Baikunthpur (District HQ)' AS name UNION ALL SELECT 'Sonhat' AS name UNION ALL SELECT 'Patna (Koriya)' AS name UNION ALL SELECT 'Charcha Colliery' AS name UNION ALL
  SELECT 'Gurughasidas National Park Area' AS name UNION ALL SELECT 'Amritdhara Falls Area' AS name UNION ALL SELECT 'Nagar (Koriya)' AS name UNION ALL SELECT 'Pondi' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mahasamund (12 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Mahasamund' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mahasamund Town' AS name UNION ALL SELECT 'Sirpur (World Heritage Archaeological Site)' AS name UNION ALL SELECT 'Saraipali' AS name UNION ALL SELECT 'Basna' AS name UNION ALL
  SELECT 'Pithora' AS name UNION ALL SELECT 'Bagbahra' AS name UNION ALL SELECT 'Komakhan' AS name UNION ALL SELECT 'Tumgaon' AS name UNION ALL
  SELECT 'Birkoni Industrial Area' AS name UNION ALL SELECT 'Jhalap' AS name UNION ALL SELECT 'Patewa' AS name UNION ALL SELECT 'Singhora Border' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Manendragarh-Chirmiri-Bharatpur (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Manendragarh-Chirmiri-Bharatpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Manendragarh' AS name UNION ALL SELECT 'Chirmiri (Haldibadi, Godaripara)' AS name UNION ALL SELECT 'Domanhill' AS name UNION ALL SELECT 'Kurasia Colliery' AS name UNION ALL
  SELECT 'Bharatpur (Janakpur)' AS name UNION ALL SELECT 'Khadgawan' AS name UNION ALL SELECT 'Ledri' AS name UNION ALL SELECT 'Jhagrakhand' AS name UNION ALL
  SELECT 'Kelhari' AS name UNION ALL SELECT 'Kotadol' AS name UNION ALL SELECT 'Ramgarh (MCB)' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mohla-Manpur-Ambagarh Chowki (9 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Mohla-Manpur-Ambagarh Chowki' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mohla' AS name UNION ALL SELECT 'Manpur' AS name UNION ALL SELECT 'Ambagarh Chowki' AS name UNION ALL SELECT 'Koracha' AS name UNION ALL
  SELECT 'Chilhati' AS name UNION ALL SELECT 'Vasadi' AS name UNION ALL SELECT 'Bandha' AS name UNION ALL SELECT 'Aundhi' AS name UNION ALL
  SELECT 'Gotatola' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mungeli (8 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Mungeli' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mungeli Town' AS name UNION ALL SELECT 'Lormi' AS name UNION ALL SELECT 'Pathariya' AS name UNION ALL SELECT 'Setganga' AS name UNION ALL
  SELECT 'Achanakmar Tiger Reserve Area' AS name UNION ALL SELECT 'Jarhagaon' AS name UNION ALL SELECT 'Madku Dweep Area' AS name UNION ALL SELECT 'Kunda' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Narayanpur (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Narayanpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Narayanpur Town' AS name UNION ALL SELECT 'Orchha (Abujhmad Heartland)' AS name UNION ALL SELECT 'Chhotedongar' AS name UNION ALL SELECT 'Dhaodai' AS name UNION ALL
  SELECT 'Benur' AS name UNION ALL SELECT 'Dhanora' AS name UNION ALL SELECT 'Kurushnar' AS name UNION ALL SELECT 'Irakbhat' AS name UNION ALL
  SELECT 'Bakulwahi' AS name UNION ALL SELECT 'Sonpur (Narayanpur)' AS name UNION ALL SELECT 'Garhbengal' AS name UNION ALL SELECT 'Ghotul Area' AS name UNION ALL
  SELECT 'Abujhmar Hills' AS name UNION ALL SELECT 'Tadoki Road' AS name UNION ALL SELECT 'Badedongar Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sakti (9 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Sakti' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sakti Town' AS name UNION ALL SELECT 'Malkharoda' AS name UNION ALL SELECT 'Dabhra' AS name UNION ALL SELECT 'Jaijaipur' AS name UNION ALL
  SELECT 'Chandrapur (Maa Chandrahasini Temple)' AS name UNION ALL SELECT 'Hasaud' AS name UNION ALL SELECT 'Adbhar' AS name UNION ALL SELECT 'Baradwar' AS name UNION ALL
  SELECT 'Pothia' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sarangarh-Bilaigarh (10 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Sarangarh-Bilaigarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sarangarh' AS name UNION ALL SELECT 'Bilaigarh' AS name UNION ALL SELECT 'Baramkela' AS name UNION ALL SELECT 'Bhatgaon (Bilaigarh)' AS name UNION ALL
  SELECT 'Sarsiwa' AS name UNION ALL SELECT 'Timarlaga' AS name UNION ALL SELECT 'Godam' AS name UNION ALL SELECT 'Hardi' AS name UNION ALL
  SELECT 'Dongripali' AS name UNION ALL SELECT 'Gomarda Wildlife Sanctuary Area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sukma (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Sukma' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sukma Town' AS name UNION ALL SELECT 'Konta (Triveni Border Hub)' AS name UNION ALL SELECT 'Dornapal' AS name UNION ALL SELECT 'Chintagufa' AS name UNION ALL
  SELECT 'Tongpal' AS name UNION ALL SELECT 'Errabor' AS name UNION ALL SELECT 'Kukanar' AS name UNION ALL SELECT 'Bheji' AS name UNION ALL
  SELECT 'Injeram' AS name UNION ALL SELECT 'Polampalli' AS name UNION ALL SELECT 'Chintalnar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Surajpur (11 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Surajpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Surajpur Town' AS name UNION ALL SELECT 'Bhaiyathan' AS name UNION ALL SELECT 'Pratappur (Surajpur)' AS name UNION ALL SELECT 'Oudgi' AS name UNION ALL
  SELECT 'Ramanujnagar' AS name UNION ALL SELECT 'Premnagar' AS name UNION ALL SELECT 'Bishrampur (Coal Mining Area)' AS name UNION ALL SELECT 'Shivpur (Kudarghat / Maa Mahamaya)' AS name UNION ALL
  SELECT 'Chandarpur' AS name UNION ALL SELECT 'Telgaon' AS name UNION ALL SELECT 'Tamor Pingla Wildlife Sanctuary Area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

