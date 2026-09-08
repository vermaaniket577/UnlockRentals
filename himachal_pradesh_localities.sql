-- ==============================================================================
-- HIMACHAL PRADESH: ALL 12 DISTRICTS & 705 LOCALITIES MASTER SQL QUERY
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Himachal Pradesh' (HP)
INSERT INTO `states` (`code`, `name`)
SELECT 'HP', 'Himachal Pradesh' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'HP' OR `name` = 'Himachal Pradesh');

SET @hp = (SELECT `id` FROM `states` WHERE `code` = 'HP' LIMIT 1);

-- 2. Ensure All 12 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @hp, d.name FROM (
  SELECT 'Bilaspur' AS name UNION ALL SELECT 'Chamba' AS name UNION ALL SELECT 'Hamirpur' AS name UNION ALL SELECT 'Kangra' AS name UNION ALL SELECT 'Kinnaur' AS name UNION ALL SELECT 'Kullu' AS name UNION ALL SELECT 'Lahaul and Spiti' AS name UNION ALL SELECT 'Mandi' AS name UNION ALL SELECT 'Shimla' AS name UNION ALL SELECT 'Sirmaur' AS name UNION ALL SELECT 'Solan' AS name UNION ALL SELECT 'Una' AS name
) d WHERE NOT EXISTS (SELECT 1 FROM `districts` WHERE `state_id` = @hp AND LOWER(`name`) = LOWER(d.name));

-- 3. Insert Localities for All 12 Districts
-- District: Bilaspur (59 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Bilaspur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bilaspur' AS name UNION ALL SELECT 'Sadar Bilaspur' AS name UNION ALL SELECT 'Bilaspur Sadar' AS name UNION ALL SELECT 'Main Market' AS name UNION ALL
  SELECT 'Bandla' AS name UNION ALL SELECT 'Lakhanpur' AS name UNION ALL SELECT 'Chandpur' AS name UNION ALL SELECT 'Delag' AS name UNION ALL
  SELECT 'Panjgain' AS name UNION ALL SELECT 'Devli' AS name UNION ALL SELECT 'Badhayat' AS name UNION ALL SELECT 'Dhaon Kothi' AS name UNION ALL
  SELECT 'Dhar Tatoh' AS name UNION ALL SELECT 'Kuthera' AS name UNION ALL SELECT 'Namhol' AS name UNION ALL SELECT 'Niharkhan Basala' AS name UNION ALL
  SELECT 'Rani Kotla' AS name UNION ALL SELECT 'Soldha' AS name UNION ALL SELECT 'Siola' AS name UNION ALL SELECT 'Chhakoh' AS name UNION ALL
  SELECT 'Dhuni Panjail' AS name UNION ALL SELECT 'Bholi' AS name UNION ALL SELECT 'Deoth' AS name UNION ALL SELECT 'Jamli' AS name UNION ALL
  SELECT 'Chharol' AS name UNION ALL SELECT 'Rajpura' AS name UNION ALL SELECT 'Ghumarwin' AS name UNION ALL SELECT 'Bharari' AS name UNION ALL
  SELECT 'Ghandoal' AS name UNION ALL SELECT 'Harlog' AS name UNION ALL SELECT 'Kalol' AS name UNION ALL SELECT 'Talai' AS name UNION ALL
  SELECT 'Berthin' AS name UNION ALL SELECT 'Marhana' AS name UNION ALL SELECT 'Seoh' AS name UNION ALL SELECT 'Panol' AS name UNION ALL
  SELECT 'Kothi' AS name UNION ALL SELECT 'Luhnu' AS name UNION ALL SELECT 'Bhalouna' AS name UNION ALL SELECT 'Jhandutta' AS name UNION ALL
  SELECT 'Shah Talai' AS name UNION ALL SELECT 'Barthin' AS name UNION ALL SELECT 'Geharwin' AS name UNION ALL SELECT 'Badgaon' AS name UNION ALL
  SELECT 'Samoh' AS name UNION ALL SELECT 'Dadhol' AS name UNION ALL SELECT 'Gehra' AS name UNION ALL SELECT 'Kotlu' AS name UNION ALL
  SELECT 'Dharar' AS name UNION ALL SELECT 'Matla' AS name UNION ALL SELECT 'Paprola' AS name UNION ALL SELECT 'Shri Naina Devi Ji' AS name UNION ALL
  SELECT 'Naina Devi' AS name UNION ALL SELECT 'Swarghat' AS name UNION ALL SELECT 'Anandpur Sahib Road area' AS name UNION ALL SELECT 'Bhakra' AS name UNION ALL
  SELECT 'Ropar Road' AS name UNION ALL SELECT 'Bassi' AS name UNION ALL SELECT 'Bhakhra' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Chamba (63 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Chamba' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chamba' AS name UNION ALL SELECT 'Sultanpur' AS name UNION ALL SELECT 'Sarol' AS name UNION ALL SELECT 'Hardaspura' AS name UNION ALL
  SELECT 'Chaugan' AS name UNION ALL SELECT 'Banikhet' AS name UNION ALL SELECT 'Mugla' AS name UNION ALL SELECT 'Karian' AS name UNION ALL
  SELECT 'Parel' AS name UNION ALL SELECT 'Udaipur' AS name UNION ALL SELECT 'Mehla' AS name UNION ALL SELECT 'Bhatiyat' AS name UNION ALL
  SELECT 'Dalhousie' AS name UNION ALL SELECT 'Bakloh' AS name UNION ALL SELECT 'Subathu' AS name UNION ALL SELECT 'Lakkar Mandi' AS name UNION ALL
  SELECT 'Gandhi Chowk' AS name UNION ALL SELECT 'Subhash Chowk' AS name UNION ALL SELECT 'Kathlog' AS name UNION ALL SELECT 'Moti Tibba' AS name UNION ALL
  SELECT 'Dalhousie Cantonment' AS name UNION ALL SELECT 'Dainkund' AS name UNION ALL SELECT 'Khajjiar' AS name UNION ALL SELECT 'Bharmour' AS name UNION ALL
  SELECT 'Hadsar' AS name UNION ALL SELECT 'Kugti' AS name UNION ALL SELECT 'Holi' AS name UNION ALL SELECT 'Ghera' AS name UNION ALL
  SELECT 'Chamba Road' AS name UNION ALL SELECT 'Kugti Valley' AS name UNION ALL SELECT 'Mani Mahesh area' AS name UNION ALL SELECT 'Pangi' AS name UNION ALL
  SELECT 'Killar' AS name UNION ALL SELECT 'Sural' AS name UNION ALL SELECT 'Dharwas' AS name UNION ALL SELECT 'Hudan' AS name UNION ALL
  SELECT 'Sach Pass' AS name UNION ALL SELECT 'Mindhal' AS name UNION ALL SELECT 'Purthi' AS name UNION ALL SELECT 'Churah' AS name UNION ALL
  SELECT 'Tissa' AS name UNION ALL SELECT 'Bhanjraru' AS name UNION ALL SELECT 'Kalhel' AS name UNION ALL SELECT 'Chanju' AS name UNION ALL
  SELECT 'Bairagarh' AS name UNION ALL SELECT 'Devi Kothi' AS name UNION ALL SELECT 'Salooni Road' AS name UNION ALL SELECT 'Salooni' AS name UNION ALL
  SELECT 'Bhalai' AS name UNION ALL SELECT 'Dhandras' AS name UNION ALL SELECT 'Sundla' AS name UNION ALL SELECT 'Tikri' AS name UNION ALL
  SELECT 'Diur' AS name UNION ALL SELECT 'Kandwara' AS name UNION ALL SELECT 'Manjli' AS name UNION ALL SELECT 'Chowari' AS name UNION ALL
  SELECT 'Sihunta' AS name UNION ALL SELECT 'Jatog' AS name UNION ALL SELECT 'Bhatoli' AS name UNION ALL SELECT 'Tundi' AS name UNION ALL
  SELECT 'Samote' AS name UNION ALL SELECT 'Dharun' AS name UNION ALL SELECT 'Kakira' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Hamirpur (65 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Hamirpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Hamirpur' AS name UNION ALL SELECT 'Gandhi Chowk' AS name UNION ALL SELECT 'Bus Stand' AS name UNION ALL SELECT 'Bhota Chowk' AS name UNION ALL
  SELECT 'Anu' AS name UNION ALL SELECT 'Nadaun Road' AS name UNION ALL SELECT 'Sujanpur Road' AS name UNION ALL SELECT 'Badsar Road' AS name UNION ALL
  SELECT 'Tauni Devi' AS name UNION ALL SELECT 'Lambloo' AS name UNION ALL SELECT 'Nalti' AS name UNION ALL SELECT 'Balyut' AS name UNION ALL
  SELECT 'Baloh' AS name UNION ALL SELECT 'Jhaniara' AS name UNION ALL SELECT 'Dungri' AS name UNION ALL SELECT 'Kuthera' AS name UNION ALL
  SELECT 'Rangas' AS name UNION ALL SELECT 'Neri' AS name UNION ALL SELECT 'Barsar' AS name UNION ALL SELECT 'Bijhri' AS name UNION ALL
  SELECT 'Dhatwal' AS name UNION ALL SELECT 'Bhota' AS name UNION ALL SELECT 'Bani' AS name UNION ALL SELECT 'Bhaleth' AS name UNION ALL
  SELECT 'Samtana' AS name UNION ALL SELECT 'Dandru' AS name UNION ALL SELECT 'Chakmoh' AS name UNION ALL SELECT 'Bhareri' AS name UNION ALL
  SELECT 'Badaran' AS name UNION ALL SELECT 'Loharli' AS name UNION ALL SELECT 'Bhoranj' AS name UNION ALL SELECT 'Jahu' AS name UNION ALL
  SELECT 'Ladraur' AS name UNION ALL SELECT 'Dhabiri' AS name UNION ALL SELECT 'Kharwar' AS name UNION ALL SELECT 'Kharota' AS name UNION ALL
  SELECT 'Kanjian' AS name UNION ALL SELECT 'Patta' AS name UNION ALL SELECT 'Bhukkar' AS name UNION ALL SELECT 'Nadaun' AS name UNION ALL
  SELECT 'Galore' AS name UNION ALL SELECT 'Amb' AS name UNION ALL SELECT 'Jol Sappar' AS name UNION ALL SELECT 'Dhaneta' AS name UNION ALL
  SELECT 'Kohla' AS name UNION ALL SELECT 'Jalari' AS name UNION ALL SELECT 'Amroh' AS name UNION ALL SELECT 'Bhumpal' AS name UNION ALL
  SELECT 'Bara' AS name UNION ALL SELECT 'Kuthar' AS name UNION ALL SELECT 'Fattehpur' AS name UNION ALL SELECT 'Sujanpur' AS name UNION ALL
  SELECT 'Tira Sujanpur' AS name UNION ALL SELECT 'Chauki' AS name UNION ALL SELECT 'Bouru' AS name UNION ALL SELECT 'Utpur' AS name UNION ALL
  SELECT 'Kadhiyar' AS name UNION ALL SELECT 'Lag' AS name UNION ALL SELECT 'Lohakhar' AS name UNION ALL SELECT 'Gawardu' AS name UNION ALL
  SELECT 'Patnaun' AS name UNION ALL SELECT 'Siswan' AS name UNION ALL SELECT 'Thana' AS name UNION ALL SELECT 'Kot' AS name UNION ALL
  SELECT 'Kakriyar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kangra (98 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Kangra' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dharamshala' AS name UNION ALL SELECT 'McLeod Ganj' AS name UNION ALL SELECT 'Bhagsu Nag' AS name UNION ALL SELECT 'Naddi' AS name UNION ALL
  SELECT 'Forsyth Ganj' AS name UNION ALL SELECT 'Kotwali Bazaar' AS name UNION ALL SELECT 'Dari' AS name UNION ALL SELECT 'Sidhpur' AS name UNION ALL
  SELECT 'Sidhbari' AS name UNION ALL SELECT 'Yol' AS name UNION ALL SELECT 'Khanyara' AS name UNION ALL SELECT 'Rakkar' AS name UNION ALL
  SELECT 'Lower Dharamshala' AS name UNION ALL SELECT 'Upper Dharamshala' AS name UNION ALL SELECT 'Tapovan' AS name UNION ALL SELECT 'Sheela Chowk' AS name UNION ALL
  SELECT 'Gaggal' AS name UNION ALL SELECT 'Kangra Road' AS name UNION ALL SELECT 'Palampur' AS name UNION ALL SELECT 'Andretta' AS name UNION ALL
  SELECT 'Neugal' AS name UNION ALL SELECT 'Ghuggar' AS name UNION ALL SELECT 'Holta' AS name UNION ALL SELECT 'Bundla' AS name UNION ALL
  SELECT 'Tanda' AS name UNION ALL SELECT 'Maranda' AS name UNION ALL SELECT 'Sungal' AS name UNION ALL SELECT 'Lohna' AS name UNION ALL
  SELECT 'Dadh' AS name UNION ALL SELECT 'Chachian' AS name UNION ALL SELECT 'Jia' AS name UNION ALL SELECT 'Dhauladhar' AS name UNION ALL
  SELECT 'Rajpur' AS name UNION ALL SELECT 'Gopalpur' AS name UNION ALL SELECT 'Bir' AS name UNION ALL SELECT 'Baijnath Road' AS name UNION ALL
  SELECT 'Kangra' AS name UNION ALL SELECT 'Kangra Fort' AS name UNION ALL SELECT 'Purana Kangra' AS name UNION ALL SELECT 'New Kangra' AS name UNION ALL
  SELECT 'Mataur' AS name UNION ALL SELECT 'Nagrota Bagwan' AS name UNION ALL SELECT 'Baroh' AS name UNION ALL SELECT 'Rait' AS name UNION ALL
  SELECT 'Shahpur' AS name UNION ALL SELECT 'Nagrota Surian' AS name UNION ALL SELECT 'Jawalamukhi' AS name UNION ALL SELECT 'Dehra' AS name UNION ALL
  SELECT 'Dera Gopipur' AS name UNION ALL SELECT 'Baijnath' AS name UNION ALL SELECT 'Paprola' AS name UNION ALL SELECT 'Billing' AS name UNION ALL
  SELECT 'Chauntra' AS name UNION ALL SELECT 'Barot' AS name UNION ALL SELECT 'Multhan' AS name UNION ALL SELECT 'Rajgundha' AS name UNION ALL
  SELECT 'Tashi Jong' AS name UNION ALL SELECT 'Sansal' AS name UNION ALL SELECT 'Nurpur' AS name UNION ALL SELECT 'Jassur' AS name UNION ALL
  SELECT 'Kandwal' AS name UNION ALL SELECT 'Nagni' AS name UNION ALL SELECT 'Raja Ka Talab' AS name UNION ALL SELECT 'Gangath' AS name UNION ALL
  SELECT 'Rehan' AS name UNION ALL SELECT 'Indora' AS name UNION ALL SELECT 'Damtal' AS name UNION ALL SELECT 'Bhadrwara' AS name UNION ALL
  SELECT 'Paragpur' AS name UNION ALL SELECT 'Pragpur' AS name UNION ALL SELECT 'Dada Siba' AS name UNION ALL SELECT 'Bankhandi' AS name UNION ALL
  SELECT 'Dhalara' AS name UNION ALL SELECT 'Neharan Pukhar' AS name UNION ALL SELECT 'Chanour' AS name UNION ALL SELECT 'Daliara' AS name UNION ALL
  SELECT 'Bara' AS name UNION ALL SELECT 'Khabli' AS name UNION ALL SELECT 'Jaisinghpur' AS name UNION ALL SELECT 'Alampur' AS name UNION ALL
  SELECT 'Harsi' AS name UNION ALL SELECT 'Gadiara' AS name UNION ALL SELECT 'Sakoh' AS name UNION ALL SELECT 'Dagoh' AS name UNION ALL
  SELECT 'Tatehal' AS name UNION ALL SELECT 'Majhernu' AS name UNION ALL SELECT 'Chathambi' AS name UNION ALL SELECT 'Biara' AS name UNION ALL
  SELECT 'Nadli' AS name UNION ALL SELECT 'Khajoornu' AS name UNION ALL SELECT 'Daraman' AS name UNION ALL SELECT 'Ashapuri' AS name UNION ALL
  SELECT 'Nagrota' AS name UNION ALL SELECT 'Kuthiar' AS name UNION ALL SELECT 'Sunehar' AS name UNION ALL SELECT 'Pathiar' AS name UNION ALL
  SELECT 'Balugloa' AS name UNION ALL SELECT 'Matour' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kinnaur (36 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Kinnaur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Reckong Peo' AS name UNION ALL SELECT 'Kalpa' AS name UNION ALL SELECT 'Kothi' AS name UNION ALL SELECT 'Roghi' AS name UNION ALL
  SELECT 'Powari' AS name UNION ALL SELECT 'Tapri' AS name UNION ALL SELECT 'Nichar Road' AS name UNION ALL SELECT 'Peo Market' AS name UNION ALL
  SELECT 'Chini' AS name UNION ALL SELECT 'Batseri' AS name UNION ALL SELECT 'Sapni' AS name UNION ALL SELECT 'Pangi' AS name UNION ALL
  SELECT 'Shongtong' AS name UNION ALL SELECT 'Nichar' AS name UNION ALL SELECT 'Bhabanagar' AS name UNION ALL SELECT 'Wangtu' AS name UNION ALL
  SELECT 'Karcham' AS name UNION ALL SELECT 'Chaura' AS name UNION ALL SELECT 'Nichar Valley' AS name UNION ALL SELECT 'Pooh' AS name UNION ALL
  SELECT 'Nako' AS name UNION ALL SELECT 'Khab' AS name UNION ALL SELECT 'Kanam' AS name UNION ALL SELECT 'Spillow' AS name UNION ALL
  SELECT 'Namgya' AS name UNION ALL SELECT 'Chango' AS name UNION ALL SELECT 'Dubling' AS name UNION ALL SELECT 'Kaurik' AS name UNION ALL
  SELECT 'Sangla' AS name UNION ALL SELECT 'Sangla Valley' AS name UNION ALL SELECT 'Chitkul' AS name UNION ALL SELECT 'Rakcham' AS name UNION ALL
  SELECT 'Kamru' AS name UNION ALL SELECT 'Kupa' AS name UNION ALL SELECT 'Kalpa Valley' AS name UNION ALL SELECT 'Moorang' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kullu (59 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Kullu' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kullu' AS name UNION ALL SELECT 'Dhalpur' AS name UNION ALL SELECT 'Gandhi Nagar' AS name UNION ALL SELECT 'Sarvari' AS name UNION ALL
  SELECT 'Akhara Bazaar' AS name UNION ALL SELECT 'Ramshila' AS name UNION ALL SELECT 'Sultanpur' AS name UNION ALL SELECT 'Mohal' AS name UNION ALL
  SELECT 'Bhuntar' AS name UNION ALL SELECT 'Shamshi' AS name UNION ALL SELECT 'Bajaura' AS name UNION ALL SELECT 'Larji' AS name UNION ALL
  SELECT 'Katrain' AS name UNION ALL SELECT 'Raison' AS name UNION ALL SELECT 'Dobhi' AS name UNION ALL SELECT 'Manali' AS name UNION ALL
  SELECT 'Old Manali' AS name UNION ALL SELECT 'New Manali' AS name UNION ALL SELECT 'Vashisht' AS name UNION ALL SELECT 'Prini' AS name UNION ALL
  SELECT 'Aleo' AS name UNION ALL SELECT 'Shanag' AS name UNION ALL SELECT 'Bahang' AS name UNION ALL SELECT 'Solang Valley' AS name UNION ALL
  SELECT 'Palchan' AS name UNION ALL SELECT 'Burwa' AS name UNION ALL SELECT 'Nehru Kund' AS name UNION ALL SELECT 'Jagatsukh' AS name UNION ALL
  SELECT 'Naggar' AS name UNION ALL SELECT 'Haripur' AS name UNION ALL SELECT 'Banjar' AS name UNION ALL SELECT 'Jibhi' AS name UNION ALL
  SELECT 'Shoja' AS name UNION ALL SELECT 'Gushaini' AS name UNION ALL SELECT 'Tirthan Valley' AS name UNION ALL SELECT 'Jalori' AS name UNION ALL
  SELECT 'Ghiyaghi' AS name UNION ALL SELECT 'Chehni Kothi' AS name UNION ALL SELECT 'Bahu' AS name UNION ALL SELECT 'Seraj' AS name UNION ALL
  SELECT 'Ani' AS name UNION ALL SELECT 'Khanag' AS name UNION ALL SELECT 'Dalash' AS name UNION ALL SELECT 'Nirmand Road' AS name UNION ALL
  SELECT 'Jalori Pass' AS name UNION ALL SELECT 'Shikari Devi area' AS name UNION ALL SELECT 'Nirmand' AS name UNION ALL SELECT 'Rampur Road' AS name UNION ALL
  SELECT 'Bagipul' AS name UNION ALL SELECT 'Dutt Nagar' AS name UNION ALL SELECT 'Sarahan' AS name UNION ALL SELECT 'Bagi' AS name UNION ALL
  SELECT 'Arsu' AS name UNION ALL SELECT 'Sainj' AS name UNION ALL SELECT 'Neuli' AS name UNION ALL SELECT 'Shenshar' AS name UNION ALL
  SELECT 'Dehuri' AS name UNION ALL SELECT 'Shangarh' AS name UNION ALL SELECT 'Raila' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Lahaul and Spiti (37 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Lahaul and Spiti' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Keylong' AS name UNION ALL SELECT 'Tandi' AS name UNION ALL SELECT 'Sissu' AS name UNION ALL SELECT 'Gondhla' AS name UNION ALL
  SELECT 'Khoksar' AS name UNION ALL SELECT 'Gemur' AS name UNION ALL SELECT 'Jispa' AS name UNION ALL SELECT 'Darcha' AS name UNION ALL
  SELECT 'Baralacha' AS name UNION ALL SELECT 'Stingri' AS name UNION ALL SELECT 'Shansha' AS name UNION ALL SELECT 'Rangrik' AS name UNION ALL
  SELECT 'Udaipur' AS name UNION ALL SELECT 'Triloknath' AS name UNION ALL SELECT 'Miyar Valley' AS name UNION ALL SELECT 'Jahalma' AS name UNION ALL
  SELECT 'Thirot' AS name UNION ALL SELECT 'Tindi' AS name UNION ALL SELECT 'Killar Road' AS name UNION ALL SELECT 'Spiti' AS name UNION ALL
  SELECT 'Kaza' AS name UNION ALL SELECT 'Kibber' AS name UNION ALL SELECT 'Komic' AS name UNION ALL SELECT 'Langza' AS name UNION ALL
  SELECT 'Hikkim' AS name UNION ALL SELECT 'Dhankar' AS name UNION ALL SELECT 'Tabo' AS name UNION ALL SELECT 'Pin Valley' AS name UNION ALL
  SELECT 'Mudh' AS name UNION ALL SELECT 'Kungri' AS name UNION ALL SELECT 'Sagnam' AS name UNION ALL SELECT 'Losar' AS name UNION ALL
  SELECT 'Chicham' AS name UNION ALL SELECT 'Demul' AS name UNION ALL SELECT 'Lidang' AS name UNION ALL SELECT 'Nako' AS name UNION ALL
  SELECT 'Kaza Market' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mandi (65 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Mandi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mandi' AS name UNION ALL SELECT 'Mandi Sadar' AS name UNION ALL SELECT 'Dhalpur' AS name UNION ALL SELECT 'Samkhetar' AS name UNION ALL
  SELECT 'Purani Mandi' AS name UNION ALL SELECT 'Ram Nagar' AS name UNION ALL SELECT 'Sauli Khad' AS name UNION ALL SELECT 'Victoria Bridge' AS name UNION ALL
  SELECT 'Bhiuli' AS name UNION ALL SELECT 'Tarna' AS name UNION ALL SELECT 'Kodra' AS name UNION ALL SELECT 'Gutkar' AS name UNION ALL
  SELECT 'Nagwain' AS name UNION ALL SELECT 'Sundernagar' AS name UNION ALL SELECT 'Bhojpur' AS name UNION ALL SELECT 'Jawahar Nagar' AS name UNION ALL
  SELECT 'Dehar' AS name UNION ALL SELECT 'Bhatoli' AS name UNION ALL SELECT 'Mahadev' AS name UNION ALL SELECT 'Churag' AS name UNION ALL
  SELECT 'Slapper' AS name UNION ALL SELECT 'Kanaid' AS name UNION ALL SELECT 'Ner Chowk' AS name UNION ALL SELECT 'Sarkaghat' AS name UNION ALL
  SELECT 'Baldwara' AS name UNION ALL SELECT 'Bhadrota' AS name UNION ALL SELECT 'Dharampur' AS name UNION ALL SELECT 'Tihra' AS name UNION ALL
  SELECT 'Dhalwan' AS name UNION ALL SELECT 'Gopalpur' AS name UNION ALL SELECT 'Kalri' AS name UNION ALL SELECT 'Jahu' AS name UNION ALL
  SELECT 'Jogindernagar' AS name UNION ALL SELECT 'Chauntra' AS name UNION ALL SELECT 'Lad Bharol' AS name UNION ALL SELECT 'Baijnath Road' AS name UNION ALL
  SELECT 'Machhyal' AS name UNION ALL SELECT 'Ghatasani' AS name UNION ALL SELECT 'Sandhol' AS name UNION ALL SELECT 'Karsog' AS name UNION ALL
  SELECT 'Pangna' AS name UNION ALL SELECT 'Mamail' AS name UNION ALL SELECT 'Mahunag' AS name UNION ALL SELECT 'Bakrot' AS name UNION ALL
  SELECT 'Kamru' AS name UNION ALL SELECT 'Shikari Devi area' AS name UNION ALL SELECT 'Gohar' AS name UNION ALL SELECT 'Chail Chowk' AS name UNION ALL
  SELECT 'Kotla' AS name UNION ALL SELECT 'Bagsiad' AS name UNION ALL SELECT 'Jachh' AS name UNION ALL SELECT 'Seraj' AS name UNION ALL
  SELECT 'Nachan' AS name UNION ALL SELECT 'Thunag' AS name UNION ALL SELECT 'Janjehli' AS name UNION ALL SELECT 'Chhatri' AS name UNION ALL
  SELECT 'Bagachanogi' AS name UNION ALL SELECT 'Shikari Devi' AS name UNION ALL SELECT 'Lambathach' AS name UNION ALL SELECT 'Padhar' AS name UNION ALL
  SELECT 'Jogindernagar Road' AS name UNION ALL SELECT 'Tikken' AS name UNION ALL SELECT 'Drang' AS name UNION ALL SELECT 'Barot' AS name UNION ALL
  SELECT 'Bada Gram' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Shimla (74 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Shimla' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Shimla' AS name UNION ALL SELECT 'Shimla City' AS name UNION ALL SELECT 'Mall Road' AS name UNION ALL SELECT 'Ridge' AS name UNION ALL
  SELECT 'The Mall Shimla' AS name UNION ALL SELECT 'The Ridge' AS name UNION ALL SELECT 'Jakhoo' AS name UNION ALL SELECT 'Sanjauli' AS name UNION ALL
  SELECT 'Chotta Shimla' AS name UNION ALL SELECT 'New Shimla' AS name UNION ALL SELECT 'Panthaghati' AS name UNION ALL SELECT 'Kasumpti' AS name UNION ALL
  SELECT 'Vikasnagar' AS name UNION ALL SELECT 'Totu' AS name UNION ALL SELECT 'Summer Hill' AS name UNION ALL SELECT 'Boileauganj' AS name UNION ALL
  SELECT 'Tara Devi' AS name UNION ALL SELECT 'Dhalli' AS name UNION ALL SELECT 'Mashobra' AS name UNION ALL SELECT 'Kufri' AS name UNION ALL
  SELECT 'Fagu' AS name UNION ALL SELECT 'Shoghi' AS name UNION ALL SELECT 'Tutu' AS name UNION ALL SELECT 'Bharari' AS name UNION ALL
  SELECT 'Khalini' AS name UNION ALL SELECT 'Navbahar' AS name UNION ALL SELECT 'Kaithu' AS name UNION ALL SELECT 'Tutikandi' AS name UNION ALL
  SELECT 'Theog' AS name UNION ALL SELECT 'Matiana' AS name UNION ALL SELECT 'Cheog' AS name UNION ALL SELECT 'Kotgarh' AS name UNION ALL
  SELECT 'Narkanda Road' AS name UNION ALL SELECT 'Gumma' AS name UNION ALL SELECT 'Deori' AS name UNION ALL SELECT 'Koti' AS name UNION ALL
  SELECT 'Rampur' AS name UNION ALL SELECT 'Rampur Bushahr' AS name UNION ALL SELECT 'Sarahan' AS name UNION ALL SELECT 'Duttnagar' AS name UNION ALL
  SELECT 'Jhakri' AS name UNION ALL SELECT 'Jeori' AS name UNION ALL SELECT 'Nogli' AS name UNION ALL SELECT 'Khaneri' AS name UNION ALL
  SELECT 'Taklech' AS name UNION ALL SELECT 'Daranghati' AS name UNION ALL SELECT 'Rohru' AS name UNION ALL SELECT 'Chirgaon' AS name UNION ALL
  SELECT 'Tikri' AS name UNION ALL SELECT 'Sandasu' AS name UNION ALL SELECT 'Hatkoti' AS name UNION ALL SELECT 'Jubbal Road' AS name UNION ALL
  SELECT 'Shekhru' AS name UNION ALL SELECT 'Dodra' AS name UNION ALL SELECT 'Kwar' AS name UNION ALL SELECT 'Jubbal' AS name UNION ALL
  SELECT 'Kotkhai' AS name UNION ALL SELECT 'Baghi' AS name UNION ALL SELECT 'Kharapathar' AS name UNION ALL SELECT 'Rohru Road' AS name UNION ALL
  SELECT 'Sarain' AS name UNION ALL SELECT 'Sawra' AS name UNION ALL SELECT 'Chaupal' AS name UNION ALL SELECT 'Nerwa' AS name UNION ALL
  SELECT 'Deha' AS name UNION ALL SELECT 'Tharoch' AS name UNION ALL SELECT 'Kupvi' AS name UNION ALL SELECT 'Chambi' AS name UNION ALL
  SELECT 'Nehra' AS name UNION ALL SELECT 'Kumarsain' AS name UNION ALL SELECT 'Narkanda' AS name UNION ALL SELECT 'Thanedar' AS name UNION ALL
  SELECT 'Tani Jubbar' AS name UNION ALL SELECT 'Hatu Peak' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sirmaur (41 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Sirmaur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Nahan' AS name UNION ALL SELECT 'Nahan City' AS name UNION ALL SELECT 'Dadahu' AS name UNION ALL SELECT 'Kala Amb' AS name UNION ALL
  SELECT 'Trilokpur' AS name UNION ALL SELECT 'Jamta' AS name UNION ALL SELECT 'Kolar' AS name UNION ALL SELECT 'Birla' AS name UNION ALL
  SELECT 'Medical College Road' AS name UNION ALL SELECT 'Shambuwala' AS name UNION ALL SELECT 'Paonta Sahib' AS name UNION ALL SELECT 'Paonta' AS name UNION ALL
  SELECT 'Bata Mandi' AS name UNION ALL SELECT 'Rampur Ghat' AS name UNION ALL SELECT 'Puruwala' AS name UNION ALL SELECT 'Majra' AS name UNION ALL
  SELECT 'Dhaulakuan' AS name UNION ALL SELECT 'Sirmauri Tal' AS name UNION ALL SELECT 'Shillai Road' AS name UNION ALL SELECT 'Bhagani' AS name UNION ALL
  SELECT 'Kamrau' AS name UNION ALL SELECT 'Rajgarh' AS name UNION ALL SELECT 'Sarahan' AS name UNION ALL SELECT 'Haripurdhar' AS name UNION ALL
  SELECT 'Nohradhar' AS name UNION ALL SELECT 'Bagthan' AS name UNION ALL SELECT 'Nehr Pab' AS name UNION ALL SELECT 'Banalidhar' AS name UNION ALL
  SELECT 'Sangrah' AS name UNION ALL SELECT 'Renuka' AS name UNION ALL SELECT 'Bhuira' AS name UNION ALL SELECT 'Shillai' AS name UNION ALL
  SELECT 'Ronhat' AS name UNION ALL SELECT 'Ajroli' AS name UNION ALL SELECT 'Kupvi' AS name UNION ALL SELECT 'Timbi' AS name UNION ALL
  SELECT 'Bakras' AS name UNION ALL SELECT 'Ghanduri' AS name UNION ALL SELECT 'Renuka Ji' AS name UNION ALL SELECT 'Jalal' AS name UNION ALL
  SELECT 'Churdhar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Solan (60 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Solan' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Solan' AS name UNION ALL SELECT 'Solan Town' AS name UNION ALL SELECT 'Mall Road' AS name UNION ALL SELECT 'The Mall' AS name UNION ALL
  SELECT 'Chambaghat' AS name UNION ALL SELECT 'Saproon' AS name UNION ALL SELECT 'Kumarhatti' AS name UNION ALL SELECT 'Dharampur' AS name UNION ALL
  SELECT 'Deonghat' AS name UNION ALL SELECT 'Kotla Nala' AS name UNION ALL SELECT 'Shilly' AS name UNION ALL SELECT 'Barog' AS name UNION ALL
  SELECT 'Salogra' AS name UNION ALL SELECT 'Shoghi Road' AS name UNION ALL SELECT 'Baddi' AS name UNION ALL SELECT 'Bhatoli Kalan' AS name UNION ALL
  SELECT 'Bhatoli Khurd' AS name UNION ALL SELECT 'Bhud' AS name UNION ALL SELECT 'Barotiwala' AS name UNION ALL SELECT 'Jharmajri' AS name UNION ALL
  SELECT 'Manpura' AS name UNION ALL SELECT 'Lodhimajra' AS name UNION ALL SELECT 'Thana' AS name UNION ALL SELECT 'Sandholi' AS name UNION ALL
  SELECT 'Abharni' AS name UNION ALL SELECT 'Souri' AS name UNION ALL SELECT 'Talli' AS name UNION ALL SELECT 'Madhala' AS name UNION ALL
  SELECT 'Katha' AS name UNION ALL SELECT 'Nalagarh' AS name UNION ALL SELECT 'Ramshahr' AS name UNION ALL SELECT 'Kirpalpur' AS name UNION ALL
  SELECT 'Nanowal' AS name UNION ALL SELECT 'Panjhera' AS name UNION ALL SELECT 'Dabhota' AS name UNION ALL SELECT 'Bagheri' AS name UNION ALL
  SELECT 'Manguwal' AS name UNION ALL SELECT 'Changer' AS name UNION ALL SELECT 'Bhogpur' AS name UNION ALL SELECT 'Kasauli' AS name UNION ALL
  SELECT 'Garkhal' AS name UNION ALL SELECT 'Sanawar' AS name UNION ALL SELECT 'Dagshai' AS name UNION ALL SELECT 'Sabathu' AS name UNION ALL
  SELECT 'Kuthar' AS name UNION ALL SELECT 'Krishangarh' AS name UNION ALL SELECT 'Arki' AS name UNION ALL SELECT 'Darlaghat' AS name UNION ALL
  SELECT 'Kunihar' AS name UNION ALL SELECT 'Diggal' AS name UNION ALL SELECT 'Jainagar' AS name UNION ALL SELECT 'Manlog' AS name UNION ALL
  SELECT 'Saur' AS name UNION ALL SELECT 'Bayla' AS name UNION ALL SELECT 'Loharghat' AS name UNION ALL SELECT 'Kothi' AS name UNION ALL
  SELECT 'Kandaghat' AS name UNION ALL SELECT 'Chail' AS name UNION ALL SELECT 'Sadhupul' AS name UNION ALL SELECT 'Waknaghat' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Una (48 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @hp AND `name` = 'Una' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Una' AS name UNION ALL SELECT 'Una City' AS name UNION ALL SELECT 'Mehatpur' AS name UNION ALL SELECT 'Basdehra' AS name UNION ALL
  SELECT 'Santokhgarh' AS name UNION ALL SELECT 'Dehlan' AS name UNION ALL SELECT 'Arniala' AS name UNION ALL SELECT 'Malahat' AS name UNION ALL
  SELECT 'Bharolian Kalan' AS name UNION ALL SELECT 'Rampur' AS name UNION ALL SELECT 'Jankaur' AS name UNION ALL SELECT 'Basoli' AS name UNION ALL
  SELECT 'Ispur' AS name UNION ALL SELECT 'Ajouli' AS name UNION ALL SELECT 'Nangal Road' AS name UNION ALL SELECT 'Amb' AS name UNION ALL
  SELECT 'Mubarikpur' AS name UNION ALL SELECT 'Bharwain' AS name UNION ALL SELECT 'Chintpurni' AS name UNION ALL SELECT 'Andora' AS name UNION ALL
  SELECT 'Mairi' AS name UNION ALL SELECT 'Chururu' AS name UNION ALL SELECT 'Kuthera Khairla' AS name UNION ALL SELECT 'Ladoli' AS name UNION ALL
  SELECT 'Katauhar' AS name UNION ALL SELECT 'Gondpur Banehra' AS name UNION ALL SELECT 'Gagret' AS name UNION ALL SELECT 'Daulatpur Chowk' AS name UNION ALL
  SELECT 'Bhanjal' AS name UNION ALL SELECT 'Kalruhi' AS name UNION ALL SELECT 'Oel' AS name UNION ALL SELECT 'Ambota' AS name UNION ALL
  SELECT 'Haroli' AS name UNION ALL SELECT 'Tahliwal' AS name UNION ALL SELECT 'Bathu' AS name UNION ALL SELECT 'Bathri' AS name UNION ALL
  SELECT 'Panjawar' AS name UNION ALL SELECT 'Saloh' AS name UNION ALL SELECT 'Kangar' AS name UNION ALL SELECT 'Dulehar' AS name UNION ALL
  SELECT 'Bangana' AS name UNION ALL SELECT 'Lathiani' AS name UNION ALL SELECT 'Tihra' AS name UNION ALL SELECT 'Talmehra' AS name UNION ALL
  SELECT 'Dhundla' AS name UNION ALL SELECT 'Chintpurni Road' AS name UNION ALL SELECT 'Kutlehar' AS name UNION ALL SELECT 'Raipur Maidan' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

