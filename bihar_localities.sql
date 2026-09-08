-- ==============================================================================
-- BIHAR: ALL 38 DISTRICTS & 1566 LOCALITIES MASTER SQL QUERY
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Bihar' (BR)
INSERT INTO `states` (`code`, `name`)
SELECT 'BR', 'Bihar' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'BR' OR `name` = 'Bihar');

SET @br = (SELECT `id` FROM `states` WHERE `code` = 'BR' LIMIT 1);

-- 2. Ensure All 38 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @br, d.name FROM (
  SELECT 'Araria' AS name UNION ALL SELECT 'Arwal' AS name UNION ALL SELECT 'Aurangabad' AS name UNION ALL SELECT 'Banka' AS name UNION ALL SELECT 'Begusarai' AS name UNION ALL SELECT 'Bhagalpur' AS name UNION ALL SELECT 'Bhojpur' AS name UNION ALL SELECT 'Buxar' AS name UNION ALL SELECT 'Darbhanga' AS name UNION ALL SELECT 'East Champaran' AS name UNION ALL SELECT 'Gaya' AS name UNION ALL SELECT 'Gopalganj' AS name UNION ALL SELECT 'Jamui' AS name UNION ALL SELECT 'Jehanabad' AS name UNION ALL SELECT 'Kaimur' AS name UNION ALL SELECT 'Katihar' AS name UNION ALL SELECT 'Khagaria' AS name UNION ALL SELECT 'Kishanganj' AS name UNION ALL SELECT 'Lakhisarai' AS name UNION ALL SELECT 'Madhepura' AS name UNION ALL SELECT 'Madhubani' AS name UNION ALL SELECT 'Munger' AS name UNION ALL SELECT 'Muzaffarpur' AS name UNION ALL SELECT 'Nalanda' AS name UNION ALL SELECT 'Nawada' AS name UNION ALL SELECT 'Patna' AS name UNION ALL SELECT 'Purnia' AS name UNION ALL SELECT 'Rohtas' AS name UNION ALL SELECT 'Saharsa' AS name UNION ALL SELECT 'Samastipur' AS name UNION ALL SELECT 'Saran' AS name UNION ALL SELECT 'Sheikhpura' AS name UNION ALL SELECT 'Sheohar' AS name UNION ALL SELECT 'Sitamarhi' AS name UNION ALL SELECT 'Siwan' AS name UNION ALL SELECT 'Supaul' AS name UNION ALL SELECT 'Vaishali' AS name UNION ALL SELECT 'West Champaran' AS name
) d WHERE NOT EXISTS (SELECT 1 FROM `districts` WHERE `state_id` = @br AND LOWER(`name`) = LOWER(d.name));

-- 3. Insert Localities for All 38 Districts
-- District: Araria (65 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Araria' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Araria' AS name UNION ALL SELECT 'Araria RS' AS name UNION ALL SELECT 'Araria Court' AS name UNION ALL SELECT 'Islampur' AS name UNION ALL
  SELECT 'Khairi' AS name UNION ALL SELECT 'Mahishi' AS name UNION ALL SELECT 'Mirganj' AS name UNION ALL SELECT 'Rampur' AS name UNION ALL
  SELECT 'Chandradei' AS name UNION ALL SELECT 'Bansbari' AS name UNION ALL SELECT 'Tarabari' AS name UNION ALL SELECT 'Kusiargaon' AS name UNION ALL
  SELECT 'Madanpur' AS name UNION ALL SELECT 'Dehti' AS name UNION ALL SELECT 'Haria' AS name UNION ALL SELECT 'Pokharia' AS name UNION ALL
  SELECT 'Gidaria' AS name UNION ALL SELECT 'Forbesganj' AS name UNION ALL SELECT 'Forbesganj RS' AS name UNION ALL SELECT 'Bathnaha' AS name UNION ALL
  SELECT 'Dholbazza' AS name UNION ALL SELECT 'Amhara' AS name UNION ALL SELECT 'Simraha' AS name UNION ALL SELECT 'Khawasapur' AS name UNION ALL
  SELECT 'Ramai' AS name UNION ALL SELECT 'Bardaha' AS name UNION ALL SELECT 'Majhua' AS name UNION ALL SELECT 'Parwaha' AS name UNION ALL
  SELECT 'Aurahi' AS name UNION ALL SELECT 'Haripur' AS name UNION ALL SELECT 'Sondaha' AS name UNION ALL SELECT 'Jokihat' AS name UNION ALL
  SELECT 'Kursakanta Road' AS name UNION ALL SELECT 'Bhagwanpur' AS name UNION ALL SELECT 'Chakai' AS name UNION ALL SELECT 'Dabhara' AS name UNION ALL
  SELECT 'Majgama' AS name UNION ALL SELECT 'Patharghatti' AS name UNION ALL SELECT 'Tekni' AS name UNION ALL SELECT 'Kakan' AS name UNION ALL
  SELECT 'Kharhat' AS name UNION ALL SELECT 'Narpatganj' AS name UNION ALL SELECT 'Bhargama' AS name UNION ALL SELECT 'Madhura' AS name UNION ALL
  SELECT 'Basmatiya' AS name UNION ALL SELECT 'Ghurna' AS name UNION ALL SELECT 'Sonapur' AS name UNION ALL SELECT 'Achra' AS name UNION ALL
  SELECT 'Bela' AS name UNION ALL SELECT 'Pithora' AS name UNION ALL SELECT 'Hasanpur' AS name UNION ALL SELECT 'Raniganj' AS name UNION ALL
  SELECT 'Belwa' AS name UNION ALL SELECT 'Bagulaha' AS name UNION ALL SELECT 'Gunwanti' AS name UNION ALL SELECT 'Kharsahi' AS name UNION ALL
  SELECT 'Kalabalua' AS name UNION ALL SELECT 'Parihari' AS name UNION ALL SELECT 'Sikti' AS name UNION ALL SELECT 'Khoragachh' AS name UNION ALL
  SELECT 'Kuanpokhar' AS name UNION ALL SELECT 'Benga' AS name UNION ALL SELECT 'Bara' AS name UNION ALL SELECT 'Amgachhi' AS name UNION ALL
  SELECT 'Pararia' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Arwal (36 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Arwal' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Arwal' AS name UNION ALL SELECT 'Arwal Bazaar' AS name UNION ALL SELECT 'Arwal Court' AS name UNION ALL SELECT 'Fakharpur' AS name UNION ALL
  SELECT 'Sakri' AS name UNION ALL SELECT 'Rampur Chauram' AS name UNION ALL SELECT 'Kalyanpur' AS name UNION ALL SELECT 'Mathia' AS name UNION ALL
  SELECT 'Sonbhadra' AS name UNION ALL SELECT 'Sarwarpur' AS name UNION ALL SELECT 'Bhadaisi' AS name UNION ALL SELECT 'Karpi' AS name UNION ALL
  SELECT 'Kinjar' AS name UNION ALL SELECT 'Belkhara' AS name UNION ALL SELECT 'Murari' AS name UNION ALL SELECT 'Puran' AS name UNION ALL
  SELECT 'Paharpur' AS name UNION ALL SELECT 'Bansi' AS name UNION ALL SELECT 'Belsar' AS name UNION ALL SELECT 'Kaler' AS name UNION ALL
  SELECT 'Kishni' AS name UNION ALL SELECT 'Jaypur' AS name UNION ALL SELECT 'Belawan' AS name UNION ALL SELECT 'Gopalpur' AS name UNION ALL
  SELECT 'Mainpura' AS name UNION ALL SELECT 'Kurtha' AS name UNION ALL SELECT 'Kurtha Bazaar' AS name UNION ALL SELECT 'Sachai' AS name UNION ALL
  SELECT 'Dawa' AS name UNION ALL SELECT 'Manikpur' AS name UNION ALL SELECT 'Nighwan' AS name UNION ALL SELECT 'Barah' AS name UNION ALL
  SELECT 'Suryapur' AS name UNION ALL SELECT 'Anua' AS name UNION ALL SELECT 'Khaira' AS name UNION ALL SELECT 'Rampur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Aurangabad (58 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Aurangabad' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Aurangabad' AS name UNION ALL SELECT 'Aurangabad Town' AS name UNION ALL SELECT 'Shahganj' AS name UNION ALL SELECT 'Jama Masjid area' AS name UNION ALL
  SELECT 'Ramabandh' AS name UNION ALL SELECT 'Adalat Road' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Karma Road' AS name UNION ALL
  SELECT 'Nawadih' AS name UNION ALL SELECT 'Daudnagar Road' AS name UNION ALL SELECT 'G.T. Road area' AS name UNION ALL SELECT 'Daudnagar' AS name UNION ALL
  SELECT 'Daudnagar Bazaar' AS name UNION ALL SELECT 'Arvind Nagar' AS name UNION ALL SELECT 'Goh Road' AS name UNION ALL SELECT 'Barun Road' AS name UNION ALL
  SELECT 'Khaspur' AS name UNION ALL SELECT 'Tarar' AS name UNION ALL SELECT 'Arai' AS name UNION ALL SELECT 'Amba' AS name UNION ALL
  SELECT 'Deo' AS name UNION ALL SELECT 'Deo Bazaar' AS name UNION ALL SELECT 'Deo Surya Mandir area' AS name UNION ALL SELECT 'Belsara' AS name UNION ALL
  SELECT 'Banshi' AS name UNION ALL SELECT 'Kasma' AS name UNION ALL SELECT 'Belwa' AS name UNION ALL SELECT 'Goh' AS name UNION ALL
  SELECT 'Goh Bazaar' AS name UNION ALL SELECT 'Rafiganj Road' AS name UNION ALL SELECT 'Upahara' AS name UNION ALL SELECT 'Dadhpi' AS name UNION ALL
  SELECT 'Deo Road' AS name UNION ALL SELECT 'Karma' AS name UNION ALL SELECT 'Rafiganj' AS name UNION ALL SELECT 'Rafiganj Bazaar' AS name UNION ALL
  SELECT 'G.T. Road' AS name UNION ALL SELECT 'Bhadauli' AS name UNION ALL SELECT 'Bhadwa' AS name UNION ALL SELECT 'Chakia' AS name UNION ALL
  SELECT 'Obra' AS name UNION ALL SELECT 'Obra Bazaar' AS name UNION ALL SELECT 'Belsar' AS name UNION ALL SELECT 'Sihuli' AS name UNION ALL
  SELECT 'Kara' AS name UNION ALL SELECT 'Obra Road' AS name UNION ALL SELECT 'Barun' AS name UNION ALL SELECT 'Barun Bazaar' AS name UNION ALL
  SELECT 'Anugrah Narayan Road' AS name UNION ALL SELECT 'Kanchanpur' AS name UNION ALL SELECT 'Son Nagar' AS name UNION ALL SELECT 'Nabinagar Road' AS name UNION ALL
  SELECT 'Nabinagar' AS name UNION ALL SELECT 'Nabinagar Bazaar' AS name UNION ALL SELECT 'Kajrat' AS name UNION ALL SELECT 'Teldiha' AS name UNION ALL
  SELECT 'Rajpur' AS name UNION ALL SELECT 'Jaynagar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Banka (49 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Banka' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Banka' AS name UNION ALL SELECT 'Banka Bazaar' AS name UNION ALL SELECT 'Banka Court' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Tilkamanjhi Road' AS name UNION ALL SELECT 'Katoria Road' AS name UNION ALL SELECT 'Amarpur Road' AS name UNION ALL SELECT 'Dhaka More' AS name UNION ALL
  SELECT 'Bounsi Road' AS name UNION ALL SELECT 'Amarpur' AS name UNION ALL SELECT 'Amarpur Bazaar' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL
  SELECT 'Bhawanipur' AS name UNION ALL SELECT 'Ratanpur' AS name UNION ALL SELECT 'Jhaua' AS name UNION ALL SELECT 'Sitalpur' AS name UNION ALL
  SELECT 'Baghmara' AS name UNION ALL SELECT 'Bausi' AS name UNION ALL SELECT 'Bounsi' AS name UNION ALL SELECT 'Bounsi Bazaar' AS name UNION ALL
  SELECT 'Mandar Hill' AS name UNION ALL SELECT 'Mandar' AS name UNION ALL SELECT 'Pathra' AS name UNION ALL SELECT 'Sikandra' AS name UNION ALL
  SELECT 'Dumri' AS name UNION ALL SELECT 'Bagdaha' AS name UNION ALL SELECT 'Katoria' AS name UNION ALL SELECT 'Katoria Bazaar' AS name UNION ALL
  SELECT 'Deoghar Road' AS name UNION ALL SELECT 'Chandan Road' AS name UNION ALL SELECT 'Suiya' AS name UNION ALL SELECT 'Telia' AS name UNION ALL
  SELECT 'Phulwaria' AS name UNION ALL SELECT 'Chandan' AS name UNION ALL SELECT 'Chandan Bazaar' AS name UNION ALL SELECT 'Lakhanpur' AS name UNION ALL
  SELECT 'Belhar Road' AS name UNION ALL SELECT 'Gidhaur' AS name UNION ALL SELECT 'Belhar' AS name UNION ALL SELECT 'Belhar Bazaar' AS name UNION ALL
  SELECT 'Baghaili' AS name UNION ALL SELECT 'Kurma' AS name UNION ALL SELECT 'Barmasia' AS name UNION ALL SELECT 'Rajaun' AS name UNION ALL
  SELECT 'Rajaun Bazaar' AS name UNION ALL SELECT 'Dhanaura' AS name UNION ALL SELECT 'Durgapur' AS name UNION ALL SELECT 'Kathail' AS name UNION ALL
  SELECT 'Beldar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Begusarai (57 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Begusarai' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Begusarai' AS name UNION ALL SELECT 'Begusarai Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'GD College Road' AS name UNION ALL
  SELECT 'Kali Sthan' AS name UNION ALL SELECT 'Vishnupur' AS name UNION ALL SELECT 'Pokharia' AS name UNION ALL SELECT 'Mahmadpur' AS name UNION ALL
  SELECT 'Naya Tola' AS name UNION ALL SELECT 'Kapasiya' AS name UNION ALL SELECT 'Rajendra Nagar' AS name UNION ALL SELECT 'Bishanpur' AS name UNION ALL
  SELECT 'Ratanpur' AS name UNION ALL SELECT 'Barauni' AS name UNION ALL SELECT 'Barauni IOC' AS name UNION ALL SELECT 'Refinery Township' AS name UNION ALL
  SELECT 'Garhara' AS name UNION ALL SELECT 'Phulwaria' AS name UNION ALL SELECT 'Bathouli' AS name UNION ALL SELECT 'Mokama Road' AS name UNION ALL
  SELECT 'Barauni Junction' AS name UNION ALL SELECT 'Simaria' AS name UNION ALL SELECT 'Rajwara' AS name UNION ALL SELECT 'Teghra' AS name UNION ALL
  SELECT 'Teghra Bazaar' AS name UNION ALL SELECT 'Barauni Road' AS name UNION ALL SELECT 'Dularpur' AS name UNION ALL SELECT 'Gaura' AS name UNION ALL
  SELECT 'Pidhauli' AS name UNION ALL SELECT 'Fateha' AS name UNION ALL SELECT 'Pakthaul' AS name UNION ALL SELECT 'Bachhwara' AS name UNION ALL
  SELECT 'Bachhwara Bazaar' AS name UNION ALL SELECT 'Teghra Road' AS name UNION ALL SELECT 'Arwa' AS name UNION ALL SELECT 'Chakdaulat' AS name UNION ALL
  SELECT 'Rudhauli' AS name UNION ALL SELECT 'Gangauli' AS name UNION ALL SELECT 'Rasidpur' AS name UNION ALL SELECT 'Bakhri' AS name UNION ALL
  SELECT 'Bakhri Bazaar' AS name UNION ALL SELECT 'Chak Hamid' AS name UNION ALL SELECT 'Bakhri Road' AS name UNION ALL SELECT 'Salona' AS name UNION ALL
  SELECT 'Shakarpura' AS name UNION ALL SELECT 'Cheria Bariarpur' AS name UNION ALL SELECT 'Bariarpur' AS name UNION ALL SELECT 'Maniappa' AS name UNION ALL
  SELECT 'Kumbhi' AS name UNION ALL SELECT 'Shahpur' AS name UNION ALL SELECT 'Basahi' AS name UNION ALL SELECT 'Manjhaul' AS name UNION ALL
  SELECT 'Manjhaul Bazaar' AS name UNION ALL SELECT 'Parora' AS name UNION ALL SELECT 'Rajakpur' AS name UNION ALL SELECT 'Gadhpura Road' AS name UNION ALL
  SELECT 'Sihma' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bhagalpur (45 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Bhagalpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bhagalpur' AS name UNION ALL SELECT 'Tilkamanjhi' AS name UNION ALL SELECT 'Adampur' AS name UNION ALL SELECT 'Nathnagar' AS name UNION ALL
  SELECT 'Champanagar' AS name UNION ALL SELECT 'Tatarpur' AS name UNION ALL SELECT 'Khanjarpur' AS name UNION ALL SELECT 'Barari' AS name UNION ALL
  SELECT 'Burhanath' AS name UNION ALL SELECT 'Kahalgaon Road' AS name UNION ALL SELECT 'Sabour Road' AS name UNION ALL SELECT 'Bhikhanpur' AS name UNION ALL
  SELECT 'Mirjanhat' AS name UNION ALL SELECT 'Habibpur' AS name UNION ALL SELECT 'Ishakchak' AS name UNION ALL SELECT 'Ghantaghar' AS name UNION ALL
  SELECT 'Mayaganj' AS name UNION ALL SELECT 'University Area' AS name UNION ALL SELECT 'T.N.B. College area' AS name UNION ALL SELECT 'Zero Mile' AS name UNION ALL
  SELECT 'Lalbagh' AS name UNION ALL SELECT 'Kabirpur' AS name UNION ALL SELECT 'Madni Nagar' AS name UNION ALL SELECT 'Bhawanipur' AS name UNION ALL
  SELECT 'Rani Talab' AS name UNION ALL SELECT 'Kahalgaon' AS name UNION ALL SELECT 'Kahalgaon Bazaar' AS name UNION ALL SELECT 'NTPC Kahalgaon' AS name UNION ALL
  SELECT 'Ekchari' AS name UNION ALL SELECT 'Ghogha' AS name UNION ALL SELECT 'Sultanganj Road' AS name UNION ALL SELECT 'Bihpur Road' AS name UNION ALL
  SELECT 'Sultanganj' AS name UNION ALL SELECT 'Sultanganj Bazaar' AS name UNION ALL SELECT 'Ajgaivinath' AS name UNION ALL SELECT 'Ganga Ghat' AS name UNION ALL
  SELECT 'Akbarnagar' AS name UNION ALL SELECT 'Asarganj Road' AS name UNION ALL SELECT 'Murarpur' AS name UNION ALL SELECT 'Pirpainti' AS name UNION ALL
  SELECT 'Pirpainti Bazaar' AS name UNION ALL SELECT 'Mirchaibari' AS name UNION ALL SELECT 'Barmasia' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL
  SELECT 'Mandro' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bhojpur (51 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Bhojpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Arrah' AS name UNION ALL SELECT 'Ara Town' AS name UNION ALL SELECT 'Nawada' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Pakri' AS name UNION ALL SELECT 'Babu Bazaar' AS name UNION ALL SELECT 'Shivganj' AS name UNION ALL SELECT 'Dharahara' AS name UNION ALL
  SELECT 'Mahadeva' AS name UNION ALL SELECT 'Katira' AS name UNION ALL SELECT 'Chandwa' AS name UNION ALL SELECT 'Gangi' AS name UNION ALL
  SELECT 'Jagdeo Nagar' AS name UNION ALL SELECT 'New Police Line' AS name UNION ALL SELECT 'Ramna Maidan' AS name UNION ALL SELECT 'Anaith' AS name UNION ALL
  SELECT 'Milki' AS name UNION ALL SELECT 'Jagdishpur' AS name UNION ALL SELECT 'Jagdishpur Bazaar' AS name UNION ALL SELECT 'Dulaur' AS name UNION ALL
  SELECT 'Dehri' AS name UNION ALL SELECT 'Sonbarsa' AS name UNION ALL SELECT 'Harigaon' AS name UNION ALL SELECT 'Narayanpur' AS name UNION ALL
  SELECT 'Ayar' AS name UNION ALL SELECT 'Piro' AS name UNION ALL SELECT 'Piro Bazaar' AS name UNION ALL SELECT 'Haswapur' AS name UNION ALL
  SELECT 'Shahpur Road' AS name UNION ALL SELECT 'Jitaura' AS name UNION ALL SELECT 'Barnaon' AS name UNION ALL SELECT 'Shahpur' AS name UNION ALL
  SELECT 'Shahpur Bazaar' AS name UNION ALL SELECT 'Karnamepur' AS name UNION ALL SELECT 'Belaur' AS name UNION ALL SELECT 'Dewa' AS name UNION ALL
  SELECT 'Bhadsara' AS name UNION ALL SELECT 'Bahoranpur' AS name UNION ALL SELECT 'Koilwar' AS name UNION ALL SELECT 'Koilwar Bazaar' AS name UNION ALL
  SELECT 'Kulharia' AS name UNION ALL SELECT 'Ara Road' AS name UNION ALL SELECT 'Sakaddi' AS name UNION ALL SELECT 'Babura' AS name UNION ALL
  SELECT 'Gidha' AS name UNION ALL SELECT 'Sandesh' AS name UNION ALL SELECT 'Sandesh Bazaar' AS name UNION ALL SELECT 'Chandi' AS name UNION ALL
  SELECT 'Akhgaon' AS name UNION ALL SELECT 'Panpuri' AS name UNION ALL SELECT 'Khairah' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Buxar (36 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Buxar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Buxar' AS name UNION ALL SELECT 'Buxar Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Civil Lines' AS name UNION ALL
  SELECT 'Ramrekha Ghat' AS name UNION ALL SELECT 'Golambar' AS name UNION ALL SELECT 'Charitravan' AS name UNION ALL SELECT 'P.P. Road' AS name UNION ALL
  SELECT 'Naya Bazaar' AS name UNION ALL SELECT 'Itarhi Road' AS name UNION ALL SELECT 'Dumraon Road' AS name UNION ALL SELECT 'Dumraon' AS name UNION ALL
  SELECT 'Dumraon Bazaar' AS name UNION ALL SELECT 'Rajgarh' AS name UNION ALL SELECT 'Mathila' AS name UNION ALL SELECT 'Naya Bhojpur' AS name UNION ALL
  SELECT 'Purana Bhojpur' AS name UNION ALL SELECT 'Ara Road' AS name UNION ALL SELECT 'Rajpur' AS name UNION ALL SELECT 'Rajpur Bazaar' AS name UNION ALL
  SELECT 'Akhauri Pur Gola' AS name UNION ALL SELECT 'Dehri' AS name UNION ALL SELECT 'Banni' AS name UNION ALL SELECT 'Chausa Road' AS name UNION ALL
  SELECT 'Chausa' AS name UNION ALL SELECT 'Chausa Bazaar' AS name UNION ALL SELECT 'Ramgarh' AS name UNION ALL SELECT 'Pawani' AS name UNION ALL
  SELECT 'Buxar Road' AS name UNION ALL SELECT 'Mahuari' AS name UNION ALL SELECT 'Itarhi' AS name UNION ALL SELECT 'Itarhi Bazaar' AS name UNION ALL
  SELECT 'Lakhan Dihra' AS name UNION ALL SELECT 'Narayanpur' AS name UNION ALL SELECT 'Chilhari' AS name UNION ALL SELECT 'Dehari' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Darbhanga (53 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Darbhanga' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Darbhanga' AS name UNION ALL SELECT 'Laheriasarai' AS name UNION ALL SELECT 'Darbhanga Tower' AS name UNION ALL SELECT 'Donar' AS name UNION ALL
  SELECT 'Benta' AS name UNION ALL SELECT 'Kathalbari' AS name UNION ALL SELECT 'Rambagh' AS name UNION ALL SELECT 'Mirzapur' AS name UNION ALL
  SELECT 'Mabbi' AS name UNION ALL SELECT 'Kadirabad' AS name UNION ALL SELECT 'University Area' AS name UNION ALL SELECT 'Allalpatti' AS name UNION ALL
  SELECT 'Bela' AS name UNION ALL SELECT 'Delhi More' AS name UNION ALL SELECT 'Basdeopur' AS name UNION ALL SELECT 'Bahadurpur' AS name UNION ALL
  SELECT 'Shivdhara' AS name UNION ALL SELECT 'Panda Sarai' AS name UNION ALL SELECT 'Naka No. 5' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Laheriasarai Station' AS name UNION ALL SELECT 'Benipur' AS name UNION ALL SELECT 'Bahera' AS name UNION ALL SELECT 'Bahera Bazaar' AS name UNION ALL
  SELECT 'Benipur Court' AS name UNION ALL SELECT 'Hariharpur' AS name UNION ALL SELECT 'Ughara' AS name UNION ALL SELECT 'Kanhai' AS name UNION ALL
  SELECT 'Kharajpur' AS name UNION ALL SELECT 'Biraul' AS name UNION ALL SELECT 'Biraul Bazaar' AS name UNION ALL SELECT 'Supaul' AS name UNION ALL
  SELECT 'Ghanshyampur Road' AS name UNION ALL SELECT 'Pokharam' AS name UNION ALL SELECT 'Kamtaul' AS name UNION ALL SELECT 'Sahri' AS name UNION ALL
  SELECT 'Jale' AS name UNION ALL SELECT 'Jale Bazaar' AS name UNION ALL SELECT 'Singhwara Road' AS name UNION ALL SELECT 'Asthua' AS name UNION ALL
  SELECT 'Rampur' AS name UNION ALL SELECT 'Deora Bandhauli' AS name UNION ALL SELECT 'Keoti' AS name UNION ALL SELECT 'Keoti Bazaar' AS name UNION ALL
  SELECT 'Ranway' AS name UNION ALL SELECT 'Paigambarpur' AS name UNION ALL SELECT 'Dighiar' AS name UNION ALL SELECT 'Madhopur' AS name UNION ALL
  SELECT 'Kothia' AS name UNION ALL SELECT 'Taralahi' AS name UNION ALL SELECT 'Dilawarpur' AS name UNION ALL SELECT 'Mekna' AS name UNION ALL
  SELECT 'Shobhampur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: East Champaran (52 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'East Champaran' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Motihari' AS name UNION ALL SELECT 'Motihari Town' AS name UNION ALL SELECT 'Chhatauni' AS name UNION ALL SELECT 'Belbanwa' AS name UNION ALL
  SELECT 'Agarwa' AS name UNION ALL SELECT 'Raja Bazaar' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Gandhi Chowk' AS name UNION ALL
  SELECT 'Munshi Singh College area' AS name UNION ALL SELECT 'Chandmari' AS name UNION ALL SELECT 'Bariyarpur' AS name UNION ALL SELECT 'Raghunathpur' AS name UNION ALL
  SELECT 'Turkaulia Road' AS name UNION ALL SELECT 'Madhuban Road' AS name UNION ALL SELECT 'Raxaul' AS name UNION ALL SELECT 'Raxaul Bazaar' AS name UNION ALL
  SELECT 'Main Road' AS name UNION ALL SELECT 'Sugauli Road' AS name UNION ALL SELECT 'Adapur Road' AS name UNION ALL SELECT 'Haraiya' AS name UNION ALL
  SELECT 'Lachhmipur' AS name UNION ALL SELECT 'Border Area' AS name UNION ALL SELECT 'Chakia' AS name UNION ALL SELECT 'Chakia Bazaar' AS name UNION ALL
  SELECT 'Kesaria Road' AS name UNION ALL SELECT 'Pipra' AS name UNION ALL SELECT 'Mehsi Road' AS name UNION ALL SELECT 'Barachakia' AS name UNION ALL
  SELECT 'Kachhawa' AS name UNION ALL SELECT 'Sugauli' AS name UNION ALL SELECT 'Sugauli Bazaar' AS name UNION ALL SELECT 'Ramgarhwa Road' AS name UNION ALL
  SELECT 'Bhawanipur' AS name UNION ALL SELECT 'Bagaha' AS name UNION ALL SELECT 'Harpur' AS name UNION ALL SELECT 'Belwa' AS name UNION ALL
  SELECT 'Kesaria' AS name UNION ALL SELECT 'Kesaria Bazaar' AS name UNION ALL SELECT 'Kesaria Stupa area' AS name UNION ALL SELECT 'Kalyanpur' AS name UNION ALL
  SELECT 'Bahuara' AS name UNION ALL SELECT 'Sitalpur' AS name UNION ALL SELECT 'Dhaka' AS name UNION ALL SELECT 'Dhaka Bazaar' AS name UNION ALL
  SELECT 'Pachpakari' AS name UNION ALL SELECT 'Banjaria' AS name UNION ALL SELECT 'Fatuha' AS name UNION ALL SELECT 'Ghodasahan Road' AS name UNION ALL
  SELECT 'Kundwa Chainpur' AS name UNION ALL SELECT 'Ghodasahan' AS name UNION ALL SELECT 'Ghodasahan Bazaar' AS name UNION ALL SELECT 'Purandara' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Gaya (58 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Gaya' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gaya' AS name UNION ALL SELECT 'Gaya Town' AS name UNION ALL SELECT 'Civil Lines' AS name UNION ALL SELECT 'Chand Chaura' AS name UNION ALL
  SELECT 'Tekari Road' AS name UNION ALL SELECT 'Swarajpuri Road' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'A.P. Colony' AS name UNION ALL
  SELECT 'Magadh Colony' AS name UNION ALL SELECT 'Rampur' AS name UNION ALL SELECT 'Manpur' AS name UNION ALL SELECT 'Bodh Gaya Road' AS name UNION ALL
  SELECT 'Gewalbigha' AS name UNION ALL SELECT 'Kareem Ganj' AS name UNION ALL SELECT 'Delha' AS name UNION ALL SELECT 'Paharpur' AS name UNION ALL
  SELECT 'Jai Prakash Nagar' AS name UNION ALL SELECT 'Vishnupad area' AS name UNION ALL SELECT 'Gol Bagicha' AS name UNION ALL SELECT 'Katari Hill' AS name UNION ALL
  SELECT 'Sidharthpuri' AS name UNION ALL SELECT 'Bodh Gaya' AS name UNION ALL SELECT 'Mahabodhi Temple area' AS name UNION ALL SELECT 'Sujata Road' AS name UNION ALL
  SELECT 'Tergar' AS name UNION ALL SELECT 'Bakraur' AS name UNION ALL SELECT 'Domuhan' AS name UNION ALL SELECT 'Mastipur' AS name UNION ALL
  SELECT 'Dharmaranya' AS name UNION ALL SELECT 'Japanese Temple area' AS name UNION ALL SELECT 'Tibetan Temple area' AS name UNION ALL SELECT 'Thai Temple area' AS name UNION ALL
  SELECT 'Tekari' AS name UNION ALL SELECT 'Tekari Bazaar' AS name UNION ALL SELECT 'Guraru Road' AS name UNION ALL SELECT 'Belaganj Road' AS name UNION ALL
  SELECT 'Atri Road' AS name UNION ALL SELECT 'Kespa' AS name UNION ALL SELECT 'Panchanpur' AS name UNION ALL SELECT 'Sherghati' AS name UNION ALL
  SELECT 'Sherghati Bazaar' AS name UNION ALL SELECT 'Imamganj Road' AS name UNION ALL SELECT 'Chhatarpur' AS name UNION ALL SELECT 'Gurua Road' AS name UNION ALL
  SELECT 'Chatti' AS name UNION ALL SELECT 'Fatehpur Road' AS name UNION ALL SELECT 'Wazirganj' AS name UNION ALL SELECT 'Wazirganj Bazaar' AS name UNION ALL
  SELECT 'Manpur Road' AS name UNION ALL SELECT 'Punawan' AS name UNION ALL SELECT 'Kenar' AS name UNION ALL SELECT 'Tikri Road' AS name UNION ALL
  SELECT 'Belaganj' AS name UNION ALL SELECT 'Belaganj Bazaar' AS name UNION ALL SELECT 'Gaya Road' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL
  SELECT 'Sobh' AS name UNION ALL SELECT 'Kurkihar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Gopalganj (37 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Gopalganj' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gopalganj' AS name UNION ALL SELECT 'Gopalganj Town' AS name UNION ALL SELECT 'Thawe Road' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Ambedkar Chowk' AS name UNION ALL SELECT 'Jadopur Road' AS name UNION ALL SELECT 'Mirganj Road' AS name UNION ALL SELECT 'Hajiapur' AS name UNION ALL
  SELECT 'Hariharpur' AS name UNION ALL SELECT 'Banjari' AS name UNION ALL SELECT 'Barauli' AS name UNION ALL SELECT 'Barauli Bazaar' AS name UNION ALL
  SELECT 'Sidhwalia Road' AS name UNION ALL SELECT 'Kalyanpur' AS name UNION ALL SELECT 'Batardeh' AS name UNION ALL SELECT 'Sonbarsa' AS name UNION ALL
  SELECT 'Mirganj' AS name UNION ALL SELECT 'Mirganj Bazaar' AS name UNION ALL SELECT 'Hathua Road' AS name UNION ALL SELECT 'Parsauni' AS name UNION ALL
  SELECT 'Jamunaha' AS name UNION ALL SELECT 'Line Bazaar' AS name UNION ALL SELECT 'Hathua' AS name UNION ALL SELECT 'Hathua Bazaar' AS name UNION ALL
  SELECT 'Hathua Palace area' AS name UNION ALL SELECT 'Barka Gaon' AS name UNION ALL SELECT 'Kuchaikote Road' AS name UNION ALL SELECT 'Kuchaikote' AS name UNION ALL
  SELECT 'Kuchaikote Bazaar' AS name UNION ALL SELECT 'Balthari' AS name UNION ALL SELECT 'Bhopatpur' AS name UNION ALL SELECT 'Jalalpur' AS name UNION ALL
  SELECT 'Thawe' AS name UNION ALL SELECT 'Thawe Bazaar' AS name UNION ALL SELECT 'Thawe Temple area' AS name UNION ALL SELECT 'Lakri' AS name UNION ALL
  SELECT 'Narainpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jamui (33 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Jamui' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jamui' AS name UNION ALL SELECT 'Jamui Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Kachahari Road' AS name UNION ALL
  SELECT 'Maharajganj' AS name UNION ALL SELECT 'Bazar Samiti' AS name UNION ALL SELECT 'Mahisouri' AS name UNION ALL SELECT 'Garhi' AS name UNION ALL
  SELECT 'Khaira Road' AS name UNION ALL SELECT 'Mallepur' AS name UNION ALL SELECT 'Jhajha' AS name UNION ALL SELECT 'Jhajha Bazaar' AS name UNION ALL
  SELECT 'Jamui Road' AS name UNION ALL SELECT 'Simultalla Road' AS name UNION ALL SELECT 'Rajla' AS name UNION ALL SELECT 'Chakai' AS name UNION ALL
  SELECT 'Chakai Bazaar' AS name UNION ALL SELECT 'Bamdaha' AS name UNION ALL SELECT 'Gidhaur Road' AS name UNION ALL SELECT 'Madhopur' AS name UNION ALL
  SELECT 'Katiya' AS name UNION ALL SELECT 'Saroun' AS name UNION ALL SELECT 'Gidhaur' AS name UNION ALL SELECT 'Gidhaur Bazaar' AS name UNION ALL
  SELECT 'Jhajha Road' AS name UNION ALL SELECT 'Dadpur' AS name UNION ALL SELECT 'Kumar Suraj' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL
  SELECT 'Khaira' AS name UNION ALL SELECT 'Khaira Bazaar' AS name UNION ALL SELECT 'Sondha' AS name UNION ALL SELECT 'Dighi' AS name UNION ALL
  SELECT 'Khesari' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jehanabad (27 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Jehanabad' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jehanabad' AS name UNION ALL SELECT 'Jehanabad Town' AS name UNION ALL SELECT 'Court Area' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Arwal Road' AS name UNION ALL SELECT 'Makhdumpur Road' AS name UNION ALL SELECT 'Kalpa' AS name UNION ALL SELECT 'Gandhi Maidan' AS name UNION ALL
  SELECT 'Eastend' AS name UNION ALL SELECT 'Kako Road' AS name UNION ALL SELECT 'Makhdumpur' AS name UNION ALL SELECT 'Makhdumpur Bazaar' AS name UNION ALL
  SELECT 'Barabar Road' AS name UNION ALL SELECT 'Kako' AS name UNION ALL SELECT 'Tehta' AS name UNION ALL SELECT 'Sultanpur' AS name UNION ALL
  SELECT 'Ghosi' AS name UNION ALL SELECT 'Ghosi Bazaar' AS name UNION ALL SELECT 'Sondhi' AS name UNION ALL SELECT 'Dhamaul' AS name UNION ALL
  SELECT 'Fatehpur' AS name UNION ALL SELECT 'Kurtha Road' AS name UNION ALL SELECT 'Kako Bazaar' AS name UNION ALL SELECT 'Sadar Bazaar' AS name UNION ALL
  SELECT 'Nandanpur' AS name UNION ALL SELECT 'Nauru' AS name UNION ALL SELECT 'Amthua' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kaimur (30 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Kaimur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bhabua' AS name UNION ALL SELECT 'Bhabua Town' AS name UNION ALL SELECT 'Bhabua Road' AS name UNION ALL SELECT 'Court Road' AS name UNION ALL
  SELECT 'Station Road' AS name UNION ALL SELECT 'Akhlaspur' AS name UNION ALL SELECT 'Chainpur Road' AS name UNION ALL SELECT 'Bhagwanpur Road' AS name UNION ALL
  SELECT 'Mohania' AS name UNION ALL SELECT 'Mohania Bazaar' AS name UNION ALL SELECT 'GT Road' AS name UNION ALL SELECT 'Chausa Road' AS name UNION ALL
  SELECT 'Ramgarh Road' AS name UNION ALL SELECT 'Kudra Road' AS name UNION ALL SELECT 'Chainpur' AS name UNION ALL SELECT 'Chainpur Bazaar' AS name UNION ALL
  SELECT 'Kaimur Hills' AS name UNION ALL SELECT 'Chand' AS name UNION ALL SELECT 'Lakshmanpur' AS name UNION ALL SELECT 'Rampur' AS name UNION ALL
  SELECT 'Ramgarh' AS name UNION ALL SELECT 'Ramgarh Bazaar' AS name UNION ALL SELECT 'Mohania Road' AS name UNION ALL SELECT 'Nuaon Road' AS name UNION ALL
  SELECT 'Kudra' AS name UNION ALL SELECT 'Kudra Bazaar' AS name UNION ALL SELECT 'Kudra Railway Station' AS name UNION ALL SELECT 'Sakri' AS name UNION ALL
  SELECT 'Parsawan' AS name UNION ALL SELECT 'Pusauli' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Katihar (34 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Katihar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Katihar' AS name UNION ALL SELECT 'Katihar Town' AS name UNION ALL SELECT 'Mirchaibari' AS name UNION ALL SELECT 'Mangal Bazaar' AS name UNION ALL
  SELECT 'Station Road' AS name UNION ALL SELECT 'New Market' AS name UNION ALL SELECT 'Binodpur' AS name UNION ALL SELECT 'Rajendra Path' AS name UNION ALL
  SELECT 'Durgapur' AS name UNION ALL SELECT 'Sirsa' AS name UNION ALL SELECT 'Barmasia' AS name UNION ALL SELECT 'Medical College Road' AS name UNION ALL
  SELECT 'Barsoi' AS name UNION ALL SELECT 'Barsoi Bazaar' AS name UNION ALL SELECT 'Barsoi Junction' AS name UNION ALL SELECT 'Balrampur' AS name UNION ALL
  SELECT 'Sultanpur' AS name UNION ALL SELECT 'Dhachna' AS name UNION ALL SELECT 'Raghunathpur' AS name UNION ALL SELECT 'Manihari' AS name UNION ALL
  SELECT 'Manihari Bazaar' AS name UNION ALL SELECT 'Ganga Ghat' AS name UNION ALL SELECT 'Mirganj' AS name UNION ALL SELECT 'Baghmara' AS name UNION ALL
  SELECT 'Nawabganj' AS name UNION ALL SELECT 'Korha' AS name UNION ALL SELECT 'Korha Bazaar' AS name UNION ALL SELECT 'Hasanganj Road' AS name UNION ALL
  SELECT 'Fatehpur' AS name UNION ALL SELECT 'Belsar' AS name UNION ALL SELECT 'Dighri' AS name UNION ALL SELECT 'Balrampur Bazaar' AS name UNION ALL
  SELECT 'Barsoi Road' AS name UNION ALL SELECT 'Maheshpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Khagaria (30 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Khagaria' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Khagaria' AS name UNION ALL SELECT 'Khagaria Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Mansi Road' AS name UNION ALL
  SELECT 'Rajendra Chowk' AS name UNION ALL SELECT 'Bazar Samiti' AS name UNION ALL SELECT 'Malgodam Road' AS name UNION ALL SELECT 'Chitragupta Nagar' AS name UNION ALL
  SELECT 'Mansi' AS name UNION ALL SELECT 'Mansi Junction' AS name UNION ALL SELECT 'Mansi Bazaar' AS name UNION ALL SELECT 'Sonbarsa' AS name UNION ALL
  SELECT 'Chukthi' AS name UNION ALL SELECT 'Gangauli' AS name UNION ALL SELECT 'Gogri' AS name UNION ALL SELECT 'Gogri Jamalpur' AS name UNION ALL
  SELECT 'Jamalpur' AS name UNION ALL SELECT 'Pasraha' AS name UNION ALL SELECT 'Narayanpur Road' AS name UNION ALL SELECT 'Parbatta' AS name UNION ALL
  SELECT 'Parbatta Bazaar' AS name UNION ALL SELECT 'Aguwani' AS name UNION ALL SELECT 'Mahaddipur' AS name UNION ALL SELECT 'Temtha' AS name UNION ALL
  SELECT 'Madhavpur' AS name UNION ALL SELECT 'Chautham' AS name UNION ALL SELECT 'Chautham Bazaar' AS name UNION ALL SELECT 'Durgapur' AS name UNION ALL
  SELECT 'Rohiyar' AS name UNION ALL SELECT 'Bharso' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kishanganj (31 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Kishanganj' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kishanganj' AS name UNION ALL SELECT 'Kishanganj Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Line Bazaar' AS name UNION ALL
  SELECT 'Khagra' AS name UNION ALL SELECT 'Milanpally' AS name UNION ALL SELECT 'Ramzan Bridge' AS name UNION ALL SELECT 'Dey Market' AS name UNION ALL
  SELECT 'Gandhi Chowk' AS name UNION ALL SELECT 'Thakurganj' AS name UNION ALL SELECT 'Thakurganj Bazaar' AS name UNION ALL SELECT 'Galgalia' AS name UNION ALL
  SELECT 'Pothia Road' AS name UNION ALL SELECT 'Churli' AS name UNION ALL SELECT 'Bhogdabar' AS name UNION ALL SELECT 'Bahadurganj' AS name UNION ALL
  SELECT 'Bahadurganj Bazaar' AS name UNION ALL SELECT 'Laxmipur' AS name UNION ALL SELECT 'Bhatgaon' AS name UNION ALL SELECT 'Chikabari' AS name UNION ALL
  SELECT 'Dogharia' AS name UNION ALL SELECT 'Pothia' AS name UNION ALL SELECT 'Pothia Bazaar' AS name UNION ALL SELECT 'Panasi' AS name UNION ALL
  SELECT 'Kolha' AS name UNION ALL SELECT 'Damalbari' AS name UNION ALL SELECT 'Kochadhaman' AS name UNION ALL SELECT 'Kochadhaman Bazaar' AS name UNION ALL
  SELECT 'Matiyari' AS name UNION ALL SELECT 'Tengarmari' AS name UNION ALL SELECT 'Hatigaon' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Lakhisarai (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Lakhisarai' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Lakhisarai' AS name UNION ALL SELECT 'Lakhisarai Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Bazar Samiti' AS name UNION ALL
  SELECT 'Purani Bazaar' AS name UNION ALL SELECT 'Naya Bazaar' AS name UNION ALL SELECT 'Vidyapeeth' AS name UNION ALL SELECT 'Garhi Bishanpur' AS name UNION ALL
  SELECT 'Barahiya' AS name UNION ALL SELECT 'Barahiya Bazaar' AS name UNION ALL SELECT 'Ganga Ghat' AS name UNION ALL SELECT 'Dumra' AS name UNION ALL
  SELECT 'Piplia' AS name UNION ALL SELECT 'Aunta' AS name UNION ALL SELECT 'Suryagarha' AS name UNION ALL SELECT 'Suryagarha Bazaar' AS name UNION ALL
  SELECT 'Medni Chowki' AS name UNION ALL SELECT 'Ramgarh Chowk Road' AS name UNION ALL SELECT 'Manikpur' AS name UNION ALL SELECT 'Ghoswari' AS name UNION ALL
  SELECT 'Ramgarh Chowk' AS name UNION ALL SELECT 'Ramgarh' AS name UNION ALL SELECT 'Chanan Road' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL
  SELECT 'Bhalui' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Madhepura (28 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Madhepura' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Madhepura' AS name UNION ALL SELECT 'Madhepura Town' AS name UNION ALL SELECT 'College Chowk' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'East Bypass' AS name UNION ALL SELECT 'West Bypass' AS name UNION ALL SELECT 'Purani Bazaar' AS name UNION ALL SELECT 'New Market' AS name UNION ALL
  SELECT 'Singheshwar Road' AS name UNION ALL SELECT 'Singheshwar' AS name UNION ALL SELECT 'Singheshwar Bazaar' AS name UNION ALL SELECT 'Singheshwar Temple area' AS name UNION ALL
  SELECT 'Jirwa' AS name UNION ALL SELECT 'Murliganj' AS name UNION ALL SELECT 'Murliganj Bazaar' AS name UNION ALL SELECT 'Bihari Ganj Road' AS name UNION ALL
  SELECT 'Jhitkiya' AS name UNION ALL SELECT 'Ratanpura' AS name UNION ALL SELECT 'Bihariganj' AS name UNION ALL SELECT 'Bihariganj Bazaar' AS name UNION ALL
  SELECT 'Udaikishanganj Road' AS name UNION ALL SELECT 'Bishunpur' AS name UNION ALL SELECT 'Parmanpur' AS name UNION ALL SELECT 'Udaikishanganj' AS name UNION ALL
  SELECT 'Udaikishanganj Bazaar' AS name UNION ALL SELECT 'Puraini' AS name UNION ALL SELECT 'Manpur' AS name UNION ALL SELECT 'Kishanganj Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Madhubani (50 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Madhubani' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Madhubani' AS name UNION ALL SELECT 'Madhubani Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Town Club Road' AS name UNION ALL
  SELECT 'Ganga Sagar Chowk' AS name UNION ALL SELECT 'Bata Chowk' AS name UNION ALL SELECT 'Tilak Chowk' AS name UNION ALL SELECT 'Ram Chowk' AS name UNION ALL
  SELECT 'Laldiggi' AS name UNION ALL SELECT 'Bhawara' AS name UNION ALL SELECT 'Jayanagar Road' AS name UNION ALL SELECT 'Jhanjharpur' AS name UNION ALL
  SELECT 'Jhanjharpur Bazaar' AS name UNION ALL SELECT 'Jhanjharpur RS' AS name UNION ALL SELECT 'Railway Station Road' AS name UNION ALL SELECT 'Lakhnaur Road' AS name UNION ALL
  SELECT 'Araria Sangram' AS name UNION ALL SELECT 'Jainagar' AS name UNION ALL SELECT 'Jainagar Bazaar' AS name UNION ALL SELECT 'Jainagar Railway Station' AS name UNION ALL
  SELECT 'Kamla Road' AS name UNION ALL SELECT 'Deodha' AS name UNION ALL SELECT 'Khajuli Road' AS name UNION ALL SELECT 'Jaynagar' AS name UNION ALL
  SELECT 'Jaynagar Bazar' AS name UNION ALL SELECT 'Railway Colony' AS name UNION ALL SELECT 'Kamla Ghat' AS name UNION ALL SELECT 'Devdha' AS name UNION ALL
  SELECT 'Inarwa' AS name UNION ALL SELECT 'Benipatti' AS name UNION ALL SELECT 'Benipatti Bazaar' AS name UNION ALL SELECT 'Madhwapur Road' AS name UNION ALL
  SELECT 'Bisfi Road' AS name UNION ALL SELECT 'Haripur' AS name UNION ALL SELECT 'Basaitha' AS name UNION ALL SELECT 'Rajnagar' AS name UNION ALL
  SELECT 'Rajnagar Bazaar' AS name UNION ALL SELECT 'Simri' AS name UNION ALL SELECT 'Andhratharhi Road' AS name UNION ALL SELECT 'Bhawanipur' AS name UNION ALL
  SELECT 'Phulparas' AS name UNION ALL SELECT 'Phulparas Bazaar' AS name UNION ALL SELECT 'Laukahi Road' AS name UNION ALL SELECT 'Ghoghardiha Road' AS name UNION ALL
  SELECT 'Siswar' AS name UNION ALL SELECT 'Laukaha' AS name UNION ALL SELECT 'Laukaha Bazaar' AS name UNION ALL SELECT 'Border Area' AS name UNION ALL
  SELECT 'Khutauna Road' AS name UNION ALL SELECT 'Khutauna' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Munger (35 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Munger' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Munger' AS name UNION ALL SELECT 'Munger Town' AS name UNION ALL SELECT 'Purabsarai' AS name UNION ALL SELECT 'Lal Darwaza' AS name UNION ALL
  SELECT 'Dilawarganj' AS name UNION ALL SELECT 'Jamalpur Road' AS name UNION ALL SELECT 'Bariarpur Road' AS name UNION ALL SELECT 'Kasturba Nagar' AS name UNION ALL
  SELECT 'Safiyabad' AS name UNION ALL SELECT 'Bashudevpur' AS name UNION ALL SELECT 'Shastri Nagar' AS name UNION ALL SELECT 'Belan Bazaar' AS name UNION ALL
  SELECT 'Daulatpur' AS name UNION ALL SELECT 'Jamalpur' AS name UNION ALL SELECT 'Jamalpur Railway Colony' AS name UNION ALL SELECT 'East Colony' AS name UNION ALL
  SELECT 'East Railway Colony' AS name UNION ALL SELECT 'Kharagpur Road' AS name UNION ALL SELECT 'Haveli Kharagpur Road' AS name UNION ALL SELECT 'Rampur' AS name UNION ALL
  SELECT 'Paharpur' AS name UNION ALL SELECT 'Haveli Kharagpur' AS name UNION ALL SELECT 'Kharagpur Bazaar' AS name UNION ALL SELECT 'Gangta' AS name UNION ALL
  SELECT 'Gangauli' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL SELECT 'Bhimbandh Road' AS name UNION ALL SELECT 'Bariarpur' AS name UNION ALL
  SELECT 'Bariarpur Bazaar' AS name UNION ALL SELECT 'Kharia' AS name UNION ALL SELECT 'Kalyanpur' AS name UNION ALL SELECT 'Bahadurpur' AS name UNION ALL
  SELECT 'Tarapur' AS name UNION ALL SELECT 'Tarapur Bazaar' AS name UNION ALL SELECT 'Asarganj Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Muzaffarpur (57 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Muzaffarpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Muzaffarpur' AS name UNION ALL SELECT 'Mithanpura' AS name UNION ALL SELECT 'Bairiya' AS name UNION ALL SELECT 'Ahiyapur' AS name UNION ALL
  SELECT 'Brahmpura' AS name UNION ALL SELECT 'Ramna' AS name UNION ALL SELECT 'Club Road' AS name UNION ALL SELECT 'Kalambagh Road' AS name UNION ALL
  SELECT 'Aghoria Bazaar' AS name UNION ALL SELECT 'Bhagwanpur' AS name UNION ALL SELECT 'Khabra' AS name UNION ALL SELECT 'Zero Mile' AS name UNION ALL
  SELECT 'Chandwara' AS name UNION ALL SELECT 'Sikandarpur' AS name UNION ALL SELECT 'Saraiyaganj' AS name UNION ALL SELECT 'Motijheel' AS name UNION ALL
  SELECT 'Maripur' AS name UNION ALL SELECT 'Company Bagh' AS name UNION ALL SELECT 'Bela' AS name UNION ALL SELECT 'Mithapur' AS name UNION ALL
  SELECT 'Kanti Road' AS name UNION ALL SELECT 'Kanti' AS name UNION ALL SELECT 'Kanti Bazaar' AS name UNION ALL SELECT 'Kanti Thermal Power area' AS name UNION ALL
  SELECT 'Panapur' AS name UNION ALL SELECT 'Raghunathpur' AS name UNION ALL SELECT 'Sadatpur' AS name UNION ALL SELECT 'Motipur' AS name UNION ALL
  SELECT 'Motipur Bazaar' AS name UNION ALL SELECT 'Baruraj' AS name UNION ALL SELECT 'Parsauni' AS name UNION ALL SELECT 'Mahwal' AS name UNION ALL
  SELECT 'Narhar' AS name UNION ALL SELECT 'Sakra' AS name UNION ALL SELECT 'Sakra Bazaar' AS name UNION ALL SELECT 'Dholi' AS name UNION ALL
  SELECT 'Muradpur' AS name UNION ALL SELECT 'Muraul' AS name UNION ALL SELECT 'Bariarpur' AS name UNION ALL SELECT 'Bochaha' AS name UNION ALL
  SELECT 'Bochaha Bazaar' AS name UNION ALL SELECT 'Majhauli' AS name UNION ALL SELECT 'Sarfuddinpur' AS name UNION ALL SELECT 'Kafipur' AS name UNION ALL
  SELECT 'Sahila Rampur' AS name UNION ALL SELECT 'Paroo' AS name UNION ALL SELECT 'Paroo Bazaar' AS name UNION ALL SELECT 'Saraiya Road' AS name UNION ALL
  SELECT 'Baya' AS name UNION ALL SELECT 'Bahdinpur' AS name UNION ALL SELECT 'Mohabbatpur' AS name UNION ALL SELECT 'Saraiya' AS name UNION ALL
  SELECT 'Saraiya Bazaar' AS name UNION ALL SELECT 'Rewa' AS name UNION ALL SELECT 'Mani Chhapra' AS name UNION ALL SELECT 'Basantpur' AS name UNION ALL
  SELECT 'Narangi Jiwan' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Nalanda (46 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Nalanda' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bihar Sharif' AS name UNION ALL SELECT 'Kachahari Road' AS name UNION ALL SELECT 'Ranchi Road' AS name UNION ALL SELECT 'Hospital More' AS name UNION ALL
  SELECT 'Sohsarai' AS name UNION ALL SELECT 'Magadh Colony' AS name UNION ALL SELECT 'Ramchandrapur' AS name UNION ALL SELECT 'Qamruddin Ganj' AS name UNION ALL
  SELECT 'Amber Chowk' AS name UNION ALL SELECT 'Bhainsasur' AS name UNION ALL SELECT 'Pulpar' AS name UNION ALL SELECT 'New Area' AS name UNION ALL
  SELECT 'Patel Nagar' AS name UNION ALL SELECT 'Shivpuri' AS name UNION ALL SELECT 'Devisarai' AS name UNION ALL SELECT 'Rajgir' AS name UNION ALL
  SELECT 'Rajgir Bazaar' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Nalanda Road' AS name UNION ALL SELECT 'Silao Road' AS name UNION ALL
  SELECT 'Veerayatan' AS name UNION ALL SELECT 'Zoo Safari area' AS name UNION ALL SELECT 'Venu Van area' AS name UNION ALL SELECT 'Kund area' AS name UNION ALL
  SELECT 'Japanese Temple area' AS name UNION ALL SELECT 'Hilsa' AS name UNION ALL SELECT 'Hilsa Bazaar' AS name UNION ALL SELECT 'Ekangarsarai Road' AS name UNION ALL
  SELECT 'Daniwan Road' AS name UNION ALL SELECT 'Patna Road' AS name UNION ALL SELECT 'Harnaut' AS name UNION ALL SELECT 'Harnaut Bazaar' AS name UNION ALL
  SELECT 'Railway Station Road' AS name UNION ALL SELECT 'Chandi Road' AS name UNION ALL SELECT 'Islampur' AS name UNION ALL SELECT 'Islampur Bazaar' AS name UNION ALL
  SELECT 'Hilsa Road' AS name UNION ALL SELECT 'Barna' AS name UNION ALL SELECT 'Chandi' AS name UNION ALL SELECT 'Chandi Bazaar' AS name UNION ALL
  SELECT 'Harnaut Road' AS name UNION ALL SELECT 'Daniwan' AS name UNION ALL SELECT 'Madhopur' AS name UNION ALL SELECT 'Ekangarsarai' AS name UNION ALL
  SELECT 'Ekangarsarai Bazaar' AS name UNION ALL SELECT 'Telhara' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Nawada (37 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Nawada' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Nawada' AS name UNION ALL SELECT 'Nawada Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'New Area' AS name UNION ALL
  SELECT 'Purani Bazaar' AS name UNION ALL SELECT 'Hospital Road' AS name UNION ALL SELECT 'Kachahari Road' AS name UNION ALL SELECT 'Mirdha Tola' AS name UNION ALL
  SELECT 'Gonawan' AS name UNION ALL SELECT 'Naya Tola' AS name UNION ALL SELECT 'Hisua' AS name UNION ALL SELECT 'Hisua Bazaar' AS name UNION ALL
  SELECT 'Rajgir Road' AS name UNION ALL SELECT 'Nawada Road' AS name UNION ALL SELECT 'Narhat Road' AS name UNION ALL SELECT 'Narhat' AS name UNION ALL
  SELECT 'Narhat Bazaar' AS name UNION ALL SELECT 'Khanwan' AS name UNION ALL SELECT 'Akbarpur Road' AS name UNION ALL SELECT 'Tetariya' AS name UNION ALL
  SELECT 'Warisaliganj' AS name UNION ALL SELECT 'Warisaliganj Bazaar' AS name UNION ALL SELECT 'Pakribarawan Road' AS name UNION ALL SELECT 'Shahpur' AS name UNION ALL
  SELECT 'Makhdumpur' AS name UNION ALL SELECT 'Pakribarawan' AS name UNION ALL SELECT 'Pakribarawan Bazaar' AS name UNION ALL SELECT 'Kadirganj' AS name UNION ALL
  SELECT 'Dhanarua' AS name UNION ALL SELECT 'Dhamni' AS name UNION ALL SELECT 'Rajauli' AS name UNION ALL SELECT 'Rajauli Bazaar' AS name UNION ALL
  SELECT 'Jharkhand Road' AS name UNION ALL SELECT 'Sirdala Road' AS name UNION ALL SELECT 'Harachak' AS name UNION ALL SELECT 'Sirdala' AS name UNION ALL
  SELECT 'Sirdala Bazaar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Patna (77 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Patna' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Patna' AS name UNION ALL SELECT 'Patna City' AS name UNION ALL SELECT 'Kankarbagh' AS name UNION ALL SELECT 'Rajendra Nagar' AS name UNION ALL
  SELECT 'Patliputra Colony' AS name UNION ALL SELECT 'Boring Road' AS name UNION ALL SELECT 'Boring Canal Road' AS name UNION ALL SELECT 'Bailey Road' AS name UNION ALL
  SELECT 'Fraser Road' AS name UNION ALL SELECT 'Exhibition Road' AS name UNION ALL SELECT 'Gandhi Maidan' AS name UNION ALL SELECT 'Dak Bungalow' AS name UNION ALL
  SELECT 'Sri Krishna Puri' AS name UNION ALL SELECT 'Anandpuri' AS name UNION ALL SELECT 'Kurji' AS name UNION ALL SELECT 'Digha' AS name UNION ALL
  SELECT 'Danapur' AS name UNION ALL SELECT 'Phulwarisharif' AS name UNION ALL SELECT 'Khagaul' AS name UNION ALL SELECT 'Patna Junction' AS name UNION ALL
  SELECT 'Gardanibagh' AS name UNION ALL SELECT 'Jakkanpur' AS name UNION ALL SELECT 'Mithapur' AS name UNION ALL SELECT 'Agamkuan' AS name UNION ALL
  SELECT 'Kumhrar' AS name UNION ALL SELECT 'Bahadurpur' AS name UNION ALL SELECT 'Gulzarbagh' AS name UNION ALL SELECT 'Rajeev Nagar' AS name UNION ALL
  SELECT 'Shastri Nagar' AS name UNION ALL SELECT 'Rukanpura' AS name UNION ALL SELECT 'Saguna More' AS name UNION ALL SELECT 'Ashiana Nagar' AS name UNION ALL
  SELECT 'Ramkrishna Nagar' AS name UNION ALL SELECT 'Ramjaipal Nagar' AS name UNION ALL SELECT 'Jagdeo Path' AS name UNION ALL SELECT 'Rupaspur' AS name UNION ALL
  SELECT 'Shivala' AS name UNION ALL SELECT 'Naubatpur' AS name UNION ALL SELECT 'Bihta Road' AS name UNION ALL SELECT 'Zero Mile' AS name UNION ALL
  SELECT 'Pahari' AS name UNION ALL SELECT 'Didarganj' AS name UNION ALL SELECT 'Fatuha Road' AS name UNION ALL SELECT 'Saguna' AS name UNION ALL
  SELECT 'Digha-Danapur Road' AS name UNION ALL SELECT 'Khagaul Road' AS name UNION ALL SELECT 'Nasriganj' AS name UNION ALL SELECT 'New Danapur' AS name UNION ALL
  SELECT 'Cantt Area' AS name UNION ALL SELECT 'Phulwari' AS name UNION ALL SELECT 'Phulwari Sharif' AS name UNION ALL SELECT 'AIIMS Road' AS name UNION ALL
  SELECT 'Anisabad' AS name UNION ALL SELECT 'Kurji Road' AS name UNION ALL SELECT 'Fatuha' AS name UNION ALL SELECT 'Fatuha Bazaar' AS name UNION ALL
  SELECT 'Daniwan' AS name UNION ALL SELECT 'Bakhtiyarpur Road' AS name UNION ALL SELECT 'Masaurhi Road' AS name UNION ALL SELECT 'Bihta' AS name UNION ALL
  SELECT 'Bihta Bazaar' AS name UNION ALL SELECT 'IIT Patna area' AS name UNION ALL SELECT 'Amhara' AS name UNION ALL SELECT 'Neora' AS name UNION ALL
  SELECT 'Koilwar Road' AS name UNION ALL SELECT 'Kanhauli' AS name UNION ALL SELECT 'Vishnupura' AS name UNION ALL SELECT 'Bakhtiyarpur' AS name UNION ALL
  SELECT 'Bakhtiyarpur Bazaar' AS name UNION ALL SELECT 'Salimpur' AS name UNION ALL SELECT 'Athmalgola Road' AS name UNION ALL SELECT 'Harnaut Road' AS name UNION ALL
  SELECT 'Masaurhi' AS name UNION ALL SELECT 'Masaurhi Bazaar' AS name UNION ALL SELECT 'Taregana' AS name UNION ALL SELECT 'Dhanarua Road' AS name UNION ALL
  SELECT 'Punpun Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Purnia (43 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Purnia' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Purnea' AS name UNION ALL SELECT 'Purnia' AS name UNION ALL SELECT 'Purnea City' AS name UNION ALL SELECT 'Purnia City' AS name UNION ALL
  SELECT 'Bhatta Bazaar' AS name UNION ALL SELECT 'Line Bazaar' AS name UNION ALL SELECT 'Khazanchi Haat' AS name UNION ALL SELECT 'Rambagh' AS name UNION ALL
  SELECT 'Gulabbagh' AS name UNION ALL SELECT 'Madhubani' AS name UNION ALL SELECT 'Sipahi Tola' AS name UNION ALL SELECT 'Forbesganj Road' AS name UNION ALL
  SELECT 'Station Road' AS name UNION ALL SELECT 'Polytechnic Chowk' AS name UNION ALL SELECT 'Belauri' AS name UNION ALL SELECT 'Zero Mile' AS name UNION ALL
  SELECT 'Maranga' AS name UNION ALL SELECT 'K-Hat' AS name UNION ALL SELECT 'Banmankhi' AS name UNION ALL SELECT 'Banmankhi Bazaar' AS name UNION ALL
  SELECT 'Janaki Nagar' AS name UNION ALL SELECT 'Sarsi' AS name UNION ALL SELECT 'Rupauli Road' AS name UNION ALL SELECT 'Saharsa Road' AS name UNION ALL
  SELECT 'Dhamdaha' AS name UNION ALL SELECT 'Dhamdaha Bazaar' AS name UNION ALL SELECT 'Krityanand Nagar Road' AS name UNION ALL SELECT 'Damgara' AS name UNION ALL
  SELECT 'Baisi' AS name UNION ALL SELECT 'Baisi Bazaar' AS name UNION ALL SELECT 'Amour Road' AS name UNION ALL SELECT 'Rautara' AS name UNION ALL
  SELECT 'Balrampur' AS name UNION ALL SELECT 'Amour' AS name UNION ALL SELECT 'Amour Bazaar' AS name UNION ALL SELECT 'Baisa' AS name UNION ALL
  SELECT 'Barbatta' AS name UNION ALL SELECT 'Jalalgarh Road' AS name UNION ALL SELECT 'Rupauli' AS name UNION ALL SELECT 'Rupauli Bazaar' AS name UNION ALL
  SELECT 'Bhawanipur' AS name UNION ALL SELECT 'Birouli' AS name UNION ALL SELECT 'Teterahi' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Rohtas (43 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Rohtas' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sasaram' AS name UNION ALL SELECT 'Sasaram Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'GT Road' AS name UNION ALL
  SELECT 'Fazalganj' AS name UNION ALL SELECT 'New Area' AS name UNION ALL SELECT 'Old GT Road' AS name UNION ALL SELECT 'Chowk' AS name UNION ALL
  SELECT 'Darigaon' AS name UNION ALL SELECT 'Karwandiya' AS name UNION ALL SELECT 'Dharmshala Road' AS name UNION ALL SELECT 'Ashok Nagar' AS name UNION ALL
  SELECT 'Dehri-on-Sone' AS name UNION ALL SELECT 'Dehri' AS name UNION ALL SELECT 'Dehri Bazaar' AS name UNION ALL SELECT 'Dalmianagar' AS name UNION ALL
  SELECT 'Jamuhar' AS name UNION ALL SELECT 'Son Nagar' AS name UNION ALL SELECT 'Akbarpur' AS name UNION ALL SELECT 'Baruna' AS name UNION ALL
  SELECT 'Dalmia Nagar Industrial Area' AS name UNION ALL SELECT 'Dehri Road' AS name UNION ALL SELECT 'New Dalmianagar' AS name UNION ALL SELECT 'Station Area' AS name UNION ALL
  SELECT 'Bikramganj' AS name UNION ALL SELECT 'Bikramganj Bazaar' AS name UNION ALL SELECT 'Dinara Road' AS name UNION ALL SELECT 'Karakat Road' AS name UNION ALL
  SELECT 'Sasaram Road' AS name UNION ALL SELECT 'Karakat' AS name UNION ALL SELECT 'Gorari' AS name UNION ALL SELECT 'Gorari Bazaar' AS name UNION ALL
  SELECT 'Bensagar' AS name UNION ALL SELECT 'Nasriganj Road' AS name UNION ALL SELECT 'Nokha' AS name UNION ALL SELECT 'Nokha Bazaar' AS name UNION ALL
  SELECT 'Rajpur' AS name UNION ALL SELECT 'Sisa' AS name UNION ALL SELECT 'Sanjhauli Road' AS name UNION ALL SELECT 'Chenari' AS name UNION ALL
  SELECT 'Chenari Bazaar' AS name UNION ALL SELECT 'Shiv Sagar Road' AS name UNION ALL SELECT 'Kaimur Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Saharsa (31 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Saharsa' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Saharsa' AS name UNION ALL SELECT 'Saharsa Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Super Market' AS name UNION ALL
  SELECT 'D.B. Road' AS name UNION ALL SELECT 'Gangjala' AS name UNION ALL SELECT 'Naya Bazaar' AS name UNION ALL SELECT 'Purab Bazaar' AS name UNION ALL
  SELECT 'Kahra' AS name UNION ALL SELECT 'Matsyagandha Road' AS name UNION ALL SELECT 'Bangaon Road' AS name UNION ALL SELECT 'Simri Bakhtiyarpur' AS name UNION ALL
  SELECT 'Bakhtiyarpur Bazaar' AS name UNION ALL SELECT 'Sonbarsa Road' AS name UNION ALL SELECT 'Saharsa Road' AS name UNION ALL SELECT 'Mahkhar' AS name UNION ALL
  SELECT 'Salkhua Road' AS name UNION ALL SELECT 'Sonbarsa' AS name UNION ALL SELECT 'Sonbarsa Raj' AS name UNION ALL SELECT 'Sonbarsa Bazaar' AS name UNION ALL
  SELECT 'Biratpur' AS name UNION ALL SELECT 'Kahra Road' AS name UNION ALL SELECT 'Salkhua' AS name UNION ALL SELECT 'Salkhua Bazaar' AS name UNION ALL
  SELECT 'Koparia' AS name UNION ALL SELECT 'Chiraia' AS name UNION ALL SELECT 'Ghat' AS name UNION ALL SELECT 'Mahishi' AS name UNION ALL
  SELECT 'Mahishi Bazaar' AS name UNION ALL SELECT 'Kandaha' AS name UNION ALL SELECT 'Balua' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Samastipur (40 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Samastipur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Samastipur' AS name UNION ALL SELECT 'Samastipur Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Kashipur' AS name UNION ALL
  SELECT 'Bahadurpur' AS name UNION ALL SELECT 'Mohanpur' AS name UNION ALL SELECT 'Tajpur Road' AS name UNION ALL SELECT 'Gola Road' AS name UNION ALL
  SELECT 'Rambabu Chowk' AS name UNION ALL SELECT 'Magardahi' AS name UNION ALL SELECT 'Dharampur' AS name UNION ALL SELECT 'Bazar Samiti' AS name UNION ALL
  SELECT 'Rosera' AS name UNION ALL SELECT 'Rosera Bazaar' AS name UNION ALL SELECT 'Shivaji Chowk' AS name UNION ALL SELECT 'Singhia Road' AS name UNION ALL
  SELECT 'Bibhutipur Road' AS name UNION ALL SELECT 'Dalsinghsarai' AS name UNION ALL SELECT 'Dalsinghsarai Bazaar' AS name UNION ALL SELECT 'Musrigharari Road' AS name UNION ALL
  SELECT 'Pusa Road' AS name UNION ALL SELECT 'Tajpur' AS name UNION ALL SELECT 'Tajpur Bazaar' AS name UNION ALL SELECT 'Musrigharari' AS name UNION ALL
  SELECT 'Hasanpur' AS name UNION ALL SELECT 'Morwa Road' AS name UNION ALL SELECT 'Chand Chaur' AS name UNION ALL SELECT 'Pusa' AS name UNION ALL
  SELECT 'Pusa Agriculture University area' AS name UNION ALL SELECT 'Harpur' AS name UNION ALL SELECT 'Mahmadpur' AS name UNION ALL SELECT 'Dighra' AS name UNION ALL
  SELECT 'Morwa' AS name UNION ALL SELECT 'Morwa Bazaar' AS name UNION ALL SELECT 'Halai' AS name UNION ALL SELECT 'Sarairanjan Road' AS name UNION ALL
  SELECT 'Sarairanjan' AS name UNION ALL SELECT 'Sarairanjan Bazaar' AS name UNION ALL SELECT 'Dalsinghsarai Road' AS name UNION ALL SELECT 'Chak Sikandar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Saran (42 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Saran' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chhapra' AS name UNION ALL SELECT 'Chhapra Town' AS name UNION ALL SELECT 'Bhagwan Bazaar' AS name UNION ALL SELECT 'Darshan Nagar' AS name UNION ALL
  SELECT 'Rajendra Stadium area' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Gandhi Chowk' AS name UNION ALL SELECT 'Sadar Hospital area' AS name UNION ALL
  SELECT 'Dabgarpara' AS name UNION ALL SELECT 'Jagdam College area' AS name UNION ALL SELECT 'Newaji Tola' AS name UNION ALL SELECT 'Sahebganj' AS name UNION ALL
  SELECT 'Sonpur' AS name UNION ALL SELECT 'Sonpur Bazaar' AS name UNION ALL SELECT 'Harihar Nath Temple area' AS name UNION ALL SELECT 'Railway Colony' AS name UNION ALL
  SELECT 'Dighwara Road' AS name UNION ALL SELECT 'Ganga-Gandak area' AS name UNION ALL SELECT 'Dighwara' AS name UNION ALL SELECT 'Dighwara Bazaar' AS name UNION ALL
  SELECT 'Awatar Nagar' AS name UNION ALL SELECT 'Sitalpur' AS name UNION ALL SELECT 'Nayagaon Road' AS name UNION ALL SELECT 'Marhaura' AS name UNION ALL
  SELECT 'Marhaura Bazaar' AS name UNION ALL SELECT 'Amnour Road' AS name UNION ALL SELECT 'Bheldi' AS name UNION ALL SELECT 'Taraiya Road' AS name UNION ALL
  SELECT 'Amnour' AS name UNION ALL SELECT 'Amnour Bazaar' AS name UNION ALL SELECT 'Parshurampur' AS name UNION ALL SELECT 'Marhaura Road' AS name UNION ALL
  SELECT 'Ekma' AS name UNION ALL SELECT 'Ekma Bazaar' AS name UNION ALL SELECT 'Rasulpur' AS name UNION ALL SELECT 'Chainpur' AS name UNION ALL
  SELECT 'Lakri' AS name UNION ALL SELECT 'Taraiya' AS name UNION ALL SELECT 'Taraiya Bazaar' AS name UNION ALL SELECT 'Parsa' AS name UNION ALL
  SELECT 'Mashrakh Road' AS name UNION ALL SELECT 'Dewar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sheikhpura (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Sheikhpura' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sheikhpura' AS name UNION ALL SELECT 'Sheikhpura Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Barbigha Road' AS name UNION ALL
  SELECT 'Kachahari Road' AS name UNION ALL SELECT 'College Road' AS name UNION ALL SELECT 'Bazar Samiti' AS name UNION ALL SELECT 'Barbigha' AS name UNION ALL
  SELECT 'Barbigha Bazaar' AS name UNION ALL SELECT 'Sheikhpura Road' AS name UNION ALL SELECT 'Patna Road' AS name UNION ALL SELECT 'Asthana' AS name UNION ALL
  SELECT 'Narayanpur' AS name UNION ALL SELECT 'Chewara' AS name UNION ALL SELECT 'Chewara Bazaar' AS name UNION ALL SELECT 'Lakshmipur' AS name UNION ALL
  SELECT 'Chakwara' AS name UNION ALL SELECT 'Ariari' AS name UNION ALL SELECT 'Ariari Bazaar' AS name UNION ALL SELECT 'Hasanpur' AS name UNION ALL
  SELECT 'Kasar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sheohar (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Sheohar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sheohar' AS name UNION ALL SELECT 'Sheohar Town' AS name UNION ALL SELECT 'Sheohar Bazaar' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Piprahi Road' AS name UNION ALL SELECT 'Dumri Katsari Road' AS name UNION ALL SELECT 'Piprahi' AS name UNION ALL SELECT 'Piprahi Bazaar' AS name UNION ALL
  SELECT 'Belsand Road' AS name UNION ALL SELECT 'Minapur' AS name UNION ALL SELECT 'Mahuawa' AS name UNION ALL SELECT 'Dumri Katsari' AS name UNION ALL
  SELECT 'Dumri' AS name UNION ALL SELECT 'Katsari' AS name UNION ALL SELECT 'Phulwaria' AS name UNION ALL SELECT 'Shyampur' AS name UNION ALL
  SELECT 'Purnahiya' AS name UNION ALL SELECT 'Purnahiya Bazaar' AS name UNION ALL SELECT 'Basant Jagjiwan' AS name UNION ALL SELECT 'Adalpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sitamarhi (40 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Sitamarhi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sitamarhi' AS name UNION ALL SELECT 'Sitamarhi Town' AS name UNION ALL SELECT 'Dumra' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Hospital Road' AS name UNION ALL SELECT 'College Road' AS name UNION ALL SELECT 'Mehsaul' AS name UNION ALL SELECT 'Rajopatti' AS name UNION ALL
  SELECT 'Ring Road' AS name UNION ALL SELECT 'Bypass Road' AS name UNION ALL SELECT 'Janakpur Road' AS name UNION ALL SELECT 'Mirchaibari' AS name UNION ALL
  SELECT 'Belsand' AS name UNION ALL SELECT 'Belsand Bazaar' AS name UNION ALL SELECT 'Parsauni' AS name UNION ALL SELECT 'Runni Saidpur Road' AS name UNION ALL
  SELECT 'Bhaluaha' AS name UNION ALL SELECT 'Pupri' AS name UNION ALL SELECT 'Pupri Bazaar' AS name UNION ALL SELECT 'Nanpur Road' AS name UNION ALL
  SELECT 'Bairgania' AS name UNION ALL SELECT 'Bairgania Bazaar' AS name UNION ALL SELECT 'Railway Station area' AS name UNION ALL SELECT 'Border Road' AS name UNION ALL
  SELECT 'Dheng Road' AS name UNION ALL SELECT 'Riga' AS name UNION ALL SELECT 'Riga Bazaar' AS name UNION ALL SELECT 'Riga Sugar Factory area' AS name UNION ALL
  SELECT 'Supaul' AS name UNION ALL SELECT 'Bhavdeopur' AS name UNION ALL SELECT 'Runni Saidpur' AS name UNION ALL SELECT 'Runni Bazaar' AS name UNION ALL
  SELECT 'Mehsi Road' AS name UNION ALL SELECT 'Sitamarhi Road' AS name UNION ALL SELECT 'Garha' AS name UNION ALL SELECT 'Sonbarsa' AS name UNION ALL
  SELECT 'Sonbarsa Bazaar' AS name UNION ALL SELECT 'Bakhri' AS name UNION ALL SELECT 'Indwa' AS name UNION ALL SELECT 'Parihar Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Siwan (30 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Siwan' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Siwan' AS name UNION ALL SELECT 'Siwan Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'J.P. Chowk' AS name UNION ALL
  SELECT 'Babunia Road' AS name UNION ALL SELECT 'Mahadeva' AS name UNION ALL SELECT 'Hospital Road' AS name UNION ALL SELECT 'Gandhi Maidan' AS name UNION ALL
  SELECT 'Tarwara Road' AS name UNION ALL SELECT 'Siswan Road' AS name UNION ALL SELECT 'Andar Road' AS name UNION ALL SELECT 'Mairwa Road' AS name UNION ALL
  SELECT 'Lakri' AS name UNION ALL SELECT 'Mairwa' AS name UNION ALL SELECT 'Mairwa Bazaar' AS name UNION ALL SELECT 'Guthani Road' AS name UNION ALL
  SELECT 'Darauli Road' AS name UNION ALL SELECT 'Maharajganj' AS name UNION ALL SELECT 'Maharajganj Bazaar' AS name UNION ALL SELECT 'Daronda Road' AS name UNION ALL
  SELECT 'Basantpur Road' AS name UNION ALL SELECT 'Darauli' AS name UNION ALL SELECT 'Darauli Bazaar' AS name UNION ALL SELECT 'Ganga Ghat' AS name UNION ALL
  SELECT 'Raghunathpur' AS name UNION ALL SELECT 'Raghunathpur Bazaar' AS name UNION ALL SELECT 'Ziradei' AS name UNION ALL SELECT 'Ziradei Bazaar' AS name UNION ALL
  SELECT 'Andar' AS name UNION ALL SELECT 'Andar Bazaar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Supaul (32 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Supaul' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Supaul' AS name UNION ALL SELECT 'Supaul Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Gandhi Maidan' AS name UNION ALL
  SELECT 'Bazar Samiti' AS name UNION ALL SELECT 'Malhad' AS name UNION ALL SELECT 'Pipra Road' AS name UNION ALL SELECT 'Triveniganj Road' AS name UNION ALL
  SELECT 'Birpur' AS name UNION ALL SELECT 'Birpur Bazaar' AS name UNION ALL SELECT 'Kosi Barrage' AS name UNION ALL SELECT 'Border Road' AS name UNION ALL
  SELECT 'Basantpur' AS name UNION ALL SELECT 'Bhimnagar' AS name UNION ALL SELECT 'Triveniganj' AS name UNION ALL SELECT 'Triveniganj Bazaar' AS name UNION ALL
  SELECT 'Chhatapur Road' AS name UNION ALL SELECT 'Jadia' AS name UNION ALL SELECT 'Chhatapur' AS name UNION ALL SELECT 'Chhatapur Bazaar' AS name UNION ALL
  SELECT 'Bhimnagar Road' AS name UNION ALL SELECT 'Forbesganj Road' AS name UNION ALL SELECT 'Nirmali' AS name UNION ALL SELECT 'Nirmali Bazaar' AS name UNION ALL
  SELECT 'Railway Station Road' AS name UNION ALL SELECT 'Koshi Road' AS name UNION ALL SELECT 'Marauna Road' AS name UNION ALL SELECT 'Raghopur' AS name UNION ALL
  SELECT 'Raghopur Bazaar' AS name UNION ALL SELECT 'Simrahi' AS name UNION ALL SELECT 'Pratapganj Road' AS name UNION ALL SELECT 'Ganpatganj' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Vaishali (40 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'Vaishali' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Hajipur' AS name UNION ALL SELECT 'Hajipur Town' AS name UNION ALL SELECT 'Gandhi Chowk' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL
  SELECT 'Rajendra Chowk' AS name UNION ALL SELECT 'Anwarpur' AS name UNION ALL SELECT 'Ramashish Chowk' AS name UNION ALL SELECT 'Jadhua' AS name UNION ALL
  SELECT 'Paswan Chowk' AS name UNION ALL SELECT 'Industrial Area' AS name UNION ALL SELECT 'Digghi' AS name UNION ALL SELECT 'Sonepur Road' AS name UNION ALL
  SELECT 'Mahua Road' AS name UNION ALL SELECT 'Minapur' AS name UNION ALL SELECT 'Lalganj Road' AS name UNION ALL SELECT 'Mahua' AS name UNION ALL
  SELECT 'Mahua Bazaar' AS name UNION ALL SELECT 'Patepur Road' AS name UNION ALL SELECT 'Jandaha Road' AS name UNION ALL SELECT 'Harpur' AS name UNION ALL
  SELECT 'Lalganj' AS name UNION ALL SELECT 'Lalganj Bazaar' AS name UNION ALL SELECT 'Vaishali Road' AS name UNION ALL SELECT 'Bhagwanpur' AS name UNION ALL
  SELECT 'Mahnar' AS name UNION ALL SELECT 'Mahnar Bazaar' AS name UNION ALL SELECT 'Desari Road' AS name UNION ALL SELECT 'Mohiuddinagar Road' AS name UNION ALL
  SELECT 'Jandaha' AS name UNION ALL SELECT 'Jandaha Bazaar' AS name UNION ALL SELECT 'Samastipur Road' AS name UNION ALL SELECT 'Rasulpur' AS name UNION ALL
  SELECT 'Patepur' AS name UNION ALL SELECT 'Patepur Bazaar' AS name UNION ALL SELECT 'Baligaon' AS name UNION ALL SELECT 'Raghopur' AS name UNION ALL
  SELECT 'Raghopur Diara' AS name UNION ALL SELECT 'Jurawanpur' AS name UNION ALL SELECT 'Fatehpur' AS name UNION ALL SELECT 'Chandpur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: West Champaran (47 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @br AND `name` = 'West Champaran' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bettiah' AS name UNION ALL SELECT 'Bettiah Town' AS name UNION ALL SELECT 'Station Road' AS name UNION ALL SELECT 'Kali Bagh' AS name UNION ALL
  SELECT 'Hospital Road' AS name UNION ALL SELECT 'Rajguru Chowk' AS name UNION ALL SELECT 'Lal Bazaar' AS name UNION ALL SELECT 'Bus Stand' AS name UNION ALL
  SELECT 'MJK College area' AS name UNION ALL SELECT 'Chhawani' AS name UNION ALL SELECT 'Banuchhapar' AS name UNION ALL SELECT 'Ganga Nagar' AS name UNION ALL
  SELECT 'New Colony' AS name UNION ALL SELECT 'Bagaha' AS name UNION ALL SELECT 'Bagaha Town' AS name UNION ALL SELECT 'Bagaha Bazaar' AS name UNION ALL
  SELECT 'Ramnagar Road' AS name UNION ALL SELECT 'Valmiki Nagar Road' AS name UNION ALL SELECT 'Harinagar Road' AS name UNION ALL SELECT 'Naurangia' AS name UNION ALL
  SELECT 'Valmiki Nagar' AS name UNION ALL SELECT 'Valmiki Tiger Reserve area' AS name UNION ALL SELECT 'Gandak Barrage' AS name UNION ALL SELECT 'Forest Colony' AS name UNION ALL
  SELECT 'Gajraula' AS name UNION ALL SELECT 'Chhatraul' AS name UNION ALL SELECT 'Ramnagar' AS name UNION ALL SELECT 'Ramnagar Bazaar' AS name UNION ALL
  SELECT 'Lauria Road' AS name UNION ALL SELECT 'Narkatiaganj' AS name UNION ALL SELECT 'Narkatiaganj Bazaar' AS name UNION ALL SELECT 'Bettiah Road' AS name UNION ALL
  SELECT 'Chanpatia Road' AS name UNION ALL SELECT 'Chanpatia' AS name UNION ALL SELECT 'Chanpatia Bazaar' AS name UNION ALL SELECT 'Narkatiaganj Road' AS name UNION ALL
  SELECT 'Gonauli' AS name UNION ALL SELECT 'Lauria' AS name UNION ALL SELECT 'Lauria Bazaar' AS name UNION ALL SELECT 'Lauria Nandangarh' AS name UNION ALL
  SELECT 'Mathia' AS name UNION ALL SELECT 'Mainatanr' AS name UNION ALL SELECT 'Mainatanr Bazaar' AS name UNION ALL SELECT 'Inarwa' AS name UNION ALL
  SELECT 'Belwa' AS name UNION ALL SELECT 'Bhawanipur' AS name UNION ALL SELECT 'Sikta Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

