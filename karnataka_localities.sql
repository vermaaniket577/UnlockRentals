-- ==============================================================================
-- KARNATAKA: ALL 31 DISTRICTS & 767 LOCALITIES MASTER SQL QUERY
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Karnataka' (KA)
INSERT INTO `states` (`code`, `name`)
SELECT 'KA', 'Karnataka' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'KA' OR `name` = 'Karnataka');

SET @ka = (SELECT `id` FROM `states` WHERE `code` = 'KA' LIMIT 1);

-- 2. Ensure All 31 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @ka, d.name FROM (
  SELECT 'Bagalkot' AS name UNION ALL SELECT 'Ballari' UNION ALL SELECT 'Belagavi' UNION ALL SELECT 'Bengaluru Rural' UNION ALL SELECT 'Bengaluru Urban' UNION ALL SELECT 'Bidar' UNION ALL SELECT 'Chamarajanagar' UNION ALL SELECT 'Chikkaballapur' UNION ALL SELECT 'Chikkamagaluru' UNION ALL SELECT 'Chitradurga' UNION ALL SELECT 'Dakshina Kannada' UNION ALL SELECT 'Davanagere' UNION ALL SELECT 'Dharwad' UNION ALL SELECT 'Gadag' UNION ALL SELECT 'Hassan' UNION ALL SELECT 'Haveri' UNION ALL SELECT 'Kalaburagi' UNION ALL SELECT 'Kodagu' UNION ALL SELECT 'Kolar' UNION ALL SELECT 'Koppal' UNION ALL SELECT 'Mandya' UNION ALL SELECT 'Mysuru' UNION ALL SELECT 'Raichur' UNION ALL SELECT 'Ramanagara' UNION ALL SELECT 'Shivamogga' UNION ALL SELECT 'Tumakuru' UNION ALL SELECT 'Udupi' UNION ALL SELECT 'Uttara Kannada' UNION ALL SELECT 'Vijayapura' UNION ALL SELECT 'Vijayanagara' UNION ALL SELECT 'Yadgir'
) d WHERE NOT EXISTS (
  SELECT 1 FROM `districts` WHERE `state_id` = @ka AND (
    LOWER(`name`) = LOWER(d.name) OR
    (d.name = 'Bagalkot' AND LOWER(`name`) = 'bagalkote')
  )
);

-- 3. Insert Localities for All 31 Districts

-- District 1/31: Bagalkot (23 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` IN ('Bagalkot', 'Bagalkote') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bagalkot' AS name UNION ALL SELECT 'Jamkhandi' UNION ALL SELECT 'Mudhol' UNION ALL SELECT 'Badami' UNION ALL
  SELECT 'Guledgudd' UNION ALL SELECT 'Hungund' UNION ALL SELECT 'Ilkal' UNION ALL SELECT 'Rabkavi-Banhatti' UNION ALL
  SELECT 'Bilagi' UNION ALL SELECT 'Mahalingpur' UNION ALL SELECT 'Terdal' UNION ALL SELECT 'Lokapur' UNION ALL
  SELECT 'Kerur' UNION ALL SELECT 'Kulgeri Cross' UNION ALL SELECT 'Aminagad' UNION ALL SELECT 'Guledagudda' UNION ALL
  SELECT 'Navanagar' UNION ALL SELECT 'Vidyagiri' UNION ALL SELECT 'Navanagar Extension' UNION ALL SELECT 'Bagalkot Rural' UNION ALL
  SELECT 'Badami Rural' UNION ALL SELECT 'Jamkhandi Rural' UNION ALL SELECT 'Mudhol Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 2/31: Ballari (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Ballari' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Ballari' AS name UNION ALL SELECT 'Hospet' UNION ALL SELECT 'Siruguppa' UNION ALL SELECT 'Sandur' UNION ALL
  SELECT 'Kudligi' UNION ALL SELECT 'Hadagali' UNION ALL SELECT 'Harapanahalli' UNION ALL SELECT 'Huvina Hadagali' UNION ALL
  SELECT 'Kampli' UNION ALL SELECT 'Kurugodu' UNION ALL SELECT 'Toranagallu' UNION ALL SELECT 'Kudatini' UNION ALL
  SELECT 'Hospet Road' UNION ALL SELECT 'Cantonment' UNION ALL SELECT 'Gandhi Nagar' UNION ALL SELECT 'Cowl Bazaar' UNION ALL
  SELECT 'Patel Nagar' UNION ALL SELECT 'Infantry Road' UNION ALL SELECT 'Vijayanagar Colony' UNION ALL SELECT 'Parvathi Nagar' UNION ALL
  SELECT 'Allipur' UNION ALL SELECT 'Hagari' UNION ALL SELECT 'Mariyammanahalli' UNION ALL SELECT 'Kampli Rural' UNION ALL
  SELECT 'Sandur Rural' UNION ALL SELECT 'Siruguppa Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 3/31: Belagavi (38 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Belagavi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Belagavi' AS name UNION ALL SELECT 'Gokak' UNION ALL SELECT 'Athani' UNION ALL SELECT 'Chikkodi' UNION ALL
  SELECT 'Bailhongal' UNION ALL SELECT 'Ramdurg' UNION ALL SELECT 'Saundatti' UNION ALL SELECT 'Hukeri' UNION ALL
  SELECT 'Khanapur' UNION ALL SELECT 'Nippani' UNION ALL SELECT 'Raibag' UNION ALL SELECT 'Mudhol Road' UNION ALL
  SELECT 'Shahapur' UNION ALL SELECT 'Tilakwadi' UNION ALL SELECT 'Sadashiv Nagar' UNION ALL SELECT 'Hindwadi' UNION ALL
  SELECT 'Vadgaon' UNION ALL SELECT 'Angol' UNION ALL SELECT 'Udyambag' UNION ALL SELECT 'Mahantesh Nagar' UNION ALL
  SELECT 'Nehru Nagar' UNION ALL SELECT 'Bhagya Nagar' UNION ALL SELECT 'Ganeshpur' UNION ALL SELECT 'Auto Nagar' UNION ALL
  SELECT 'Kanbargi' UNION ALL SELECT 'Kakati' UNION ALL SELECT 'Majgaon' UNION ALL SELECT 'Sambra' UNION ALL
  SELECT 'Machhe' UNION ALL SELECT 'Peeranwadi' UNION ALL SELECT 'Camp' UNION ALL SELECT 'Nanawadi' UNION ALL
  SELECT 'Shivaji Nagar' UNION ALL SELECT 'Azam Nagar' UNION ALL SELECT 'Chikkodi Rural' UNION ALL SELECT 'Gokak Rural' UNION ALL
  SELECT 'Athani Rural' UNION ALL SELECT 'Nippani Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 4/31: Bengaluru Rural (27 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Bengaluru Rural' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Devanahalli' AS name UNION ALL SELECT 'Doddaballapur' UNION ALL SELECT 'Hoskote' UNION ALL SELECT 'Nelamangala' UNION ALL
  SELECT 'Doddaballapur Town' UNION ALL SELECT 'Devanahalli Town' UNION ALL SELECT 'Hoskote Town' UNION ALL SELECT 'Nelamangala Town' UNION ALL
  SELECT 'Nandi' UNION ALL SELECT 'Vijayapura' UNION ALL SELECT 'Chikkajala' UNION ALL SELECT 'Bettahalasur' UNION ALL
  SELECT 'Budigere' UNION ALL SELECT 'Budigere Cross' UNION ALL SELECT 'Kannamangala' UNION ALL SELECT 'Kolar Road' UNION ALL
  SELECT 'Nallur' UNION ALL SELECT 'Kundana' UNION ALL SELECT 'Sadahalli' UNION ALL SELECT 'Shettigere' UNION ALL
  SELECT 'Doddaballapur Road' UNION ALL SELECT 'Dobbaspet' UNION ALL SELECT 'Solur' UNION ALL SELECT 'Thyamagondlu' UNION ALL
  SELECT 'Tavarekere' UNION ALL SELECT 'Kasaba' UNION ALL SELECT 'Hoskote Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 5/31: Bengaluru Urban (90 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Bengaluru Urban' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bengaluru' AS name UNION ALL SELECT 'Whitefield' UNION ALL SELECT 'Electronic City' UNION ALL SELECT 'Koramangala' UNION ALL
  SELECT 'Indiranagar' UNION ALL SELECT 'Jayanagar' UNION ALL SELECT 'JP Nagar' UNION ALL SELECT 'BTM Layout' UNION ALL
  SELECT 'HSR Layout' UNION ALL SELECT 'Marathahalli' UNION ALL SELECT 'Bellandur' UNION ALL SELECT 'Sarjapur Road' UNION ALL
  SELECT 'Hebbal' UNION ALL SELECT 'Yelahanka' UNION ALL SELECT 'Rajajinagar' UNION ALL SELECT 'Malleshwaram' UNION ALL
  SELECT 'Vijayanagar' UNION ALL SELECT 'Banashankari' UNION ALL SELECT 'Basavanagudi' UNION ALL SELECT 'Basaveshwaranagar' UNION ALL
  SELECT 'Nagarbhavi' UNION ALL SELECT 'Kengeri' UNION ALL SELECT 'RR Nagar' UNION ALL SELECT 'Uttarahalli' UNION ALL
  SELECT 'Kanakapura Road' UNION ALL SELECT 'Bannerghatta Road' UNION ALL SELECT 'Hosur Road' UNION ALL SELECT 'Bommanahalli' UNION ALL
  SELECT 'Begur' UNION ALL SELECT 'Harlur' UNION ALL SELECT 'Kudlu' UNION ALL SELECT 'Singasandra' UNION ALL
  SELECT 'Garvebhavipalya' UNION ALL SELECT 'Bommasandra' UNION ALL SELECT 'Attibele' UNION ALL SELECT 'Chandapura' UNION ALL
  SELECT 'Hoskerehalli' UNION ALL SELECT 'Padmanabhanagar' UNION ALL SELECT 'JP Nagar Phase 1' UNION ALL SELECT 'JP Nagar Phase 2' UNION ALL
  SELECT 'JP Nagar Phase 3' UNION ALL SELECT 'JP Nagar Phase 4' UNION ALL SELECT 'JP Nagar Phase 5' UNION ALL SELECT 'JP Nagar Phase 6' UNION ALL
  SELECT 'JP Nagar Phase 7' UNION ALL SELECT 'JP Nagar Phase 8' UNION ALL SELECT 'JP Nagar Phase 9' UNION ALL SELECT 'JP Nagar Phase 10' UNION ALL
  SELECT 'Arekere' UNION ALL SELECT 'Gottigere' UNION ALL SELECT 'Hulimavu' UNION ALL SELECT 'Arakere' UNION ALL
  SELECT 'Bilekahalli' UNION ALL SELECT 'Doddakallasandra' UNION ALL SELECT 'Konanakunte' UNION ALL SELECT 'Vasanth Nagar' UNION ALL
  SELECT 'Richmond Town' UNION ALL SELECT 'Shivajinagar' UNION ALL SELECT 'Frazer Town' UNION ALL SELECT 'Cox Town' UNION ALL
  SELECT 'Cooke Town' UNION ALL SELECT 'HBR Layout' UNION ALL SELECT 'HRBR Layout' UNION ALL SELECT 'Kalyan Nagar' UNION ALL
  SELECT 'Kammanahalli' UNION ALL SELECT 'Banaswadi' UNION ALL SELECT 'Lingarajapuram' UNION ALL SELECT 'Kacharakanahalli' UNION ALL
  SELECT 'Nagawara' UNION ALL SELECT 'Thanisandra' UNION ALL SELECT 'Jakkur' UNION ALL SELECT 'Vidyaranyapura' UNION ALL
  SELECT 'Sahakar Nagar' UNION ALL SELECT 'Sanjaynagar' UNION ALL SELECT 'Ganganagar' UNION ALL SELECT 'RT Nagar' UNION ALL
  SELECT 'Mathikere' UNION ALL SELECT 'Sadashivanagar' UNION ALL SELECT 'Dollars Colony' UNION ALL SELECT 'Yeshwanthpur' UNION ALL
  SELECT 'Peenya' UNION ALL SELECT 'Jalahalli' UNION ALL SELECT 'Dasarahalli' UNION ALL SELECT 'Kumbalgodu' UNION ALL
  SELECT 'Kengeri Satellite Town' UNION ALL SELECT 'Magadi Road' UNION ALL SELECT 'Chandra Layout' UNION ALL SELECT 'Kamakshipalya' UNION ALL
  SELECT 'Mahalakshmi Layout' UNION ALL SELECT 'Nandini Layout'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 6/31: Bidar (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Bidar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bidar' AS name UNION ALL SELECT 'Basavakalyan' UNION ALL SELECT 'Bhalki' UNION ALL SELECT 'Aurad' UNION ALL
  SELECT 'Homnabad' UNION ALL SELECT 'Chitguppa' UNION ALL SELECT 'Hulsur' UNION ALL SELECT 'Bidar Rural' UNION ALL
  SELECT 'Basavakalyan Rural' UNION ALL SELECT 'Bhalki Rural' UNION ALL SELECT 'Gandhi Gunj' UNION ALL SELECT 'Old City' UNION ALL
  SELECT 'Naubad' UNION ALL SELECT 'Udgir Road' UNION ALL SELECT 'Mailoor' UNION ALL SELECT 'Shahapur' UNION ALL
  SELECT 'Papnash' UNION ALL SELECT 'Barid Shahi' UNION ALL SELECT 'Humnabad Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 7/31: Chamarajanagar (16 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Chamarajanagar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chamarajanagar' AS name UNION ALL SELECT 'Kollegal' UNION ALL SELECT 'Gundlupet' UNION ALL SELECT 'Yelandur' UNION ALL
  SELECT 'Hanur' UNION ALL SELECT 'Santhemarahalli' UNION ALL SELECT 'Terakanambi' UNION ALL SELECT 'Begur' UNION ALL
  SELECT 'Hongalli' UNION ALL SELECT 'Chamarajanagar Town' UNION ALL SELECT 'Kollegal Town' UNION ALL SELECT 'Gundlupet Town' UNION ALL
  SELECT 'Yelandur Town' UNION ALL SELECT 'Hanur Town' UNION ALL SELECT 'Chamarajanagar Rural' UNION ALL SELECT 'Kollegal Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 8/31: Chikkaballapur (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Chikkaballapur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chikkaballapur' AS name UNION ALL SELECT 'Chintamani' UNION ALL SELECT 'Sidlaghatta' UNION ALL SELECT 'Bagepalli' UNION ALL
  SELECT 'Gudibande' UNION ALL SELECT 'Gauribidanur' UNION ALL SELECT 'Chelur' UNION ALL SELECT 'Peresandra' UNION ALL
  SELECT 'Nandi' UNION ALL SELECT 'Nandi Hills' UNION ALL SELECT 'Chikkaballapur Town' UNION ALL SELECT 'Chintamani Town' UNION ALL
  SELECT 'Sidlaghatta Town' UNION ALL SELECT 'Gauribidanur Town' UNION ALL SELECT 'Bagepalli Town' UNION ALL SELECT 'Gudibande Town' UNION ALL
  SELECT 'Chikkaballapur Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 9/31: Chikkamagaluru (23 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Chikkamagaluru' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chikkamagaluru' AS name UNION ALL SELECT 'Kadur' UNION ALL SELECT 'Koppa' UNION ALL SELECT 'Mudigere' UNION ALL
  SELECT 'Narasimharajapura' UNION ALL SELECT 'Sringeri' UNION ALL SELECT 'Tarikere' UNION ALL SELECT 'Ajjampura' UNION ALL
  SELECT 'Balehonnur' UNION ALL SELECT 'Birur' UNION ALL SELECT 'Aldur' UNION ALL SELECT 'Belur Road' UNION ALL
  SELECT 'Vijayapura' UNION ALL SELECT 'Basavanahalli' UNION ALL SELECT 'Chikkamagaluru Town' UNION ALL SELECT 'Chikkamagaluru Rural' UNION ALL
  SELECT 'Mullayanagiri' UNION ALL SELECT 'Hirekolale' UNION ALL SELECT 'Kalasa' UNION ALL SELECT 'Banakal' UNION ALL
  SELECT 'Mudigere Town' UNION ALL SELECT 'Koppa Town' UNION ALL SELECT 'Sringeri Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 10/31: Chitradurga (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Chitradurga' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Chitradurga' AS name UNION ALL SELECT 'Challakere' UNION ALL SELECT 'Hiriyur' UNION ALL SELECT 'Holalkere' UNION ALL
  SELECT 'Hosadurga' UNION ALL SELECT 'Molakalmuru' UNION ALL SELECT 'Bharamasagara' UNION ALL SELECT 'Nayakanahatti' UNION ALL
  SELECT 'Chitradurga Town' UNION ALL SELECT 'Chitradurga Rural' UNION ALL SELECT 'Hiriyur Town' UNION ALL SELECT 'Challakere Town' UNION ALL
  SELECT 'Holalkere Town' UNION ALL SELECT 'Hosadurga Town' UNION ALL SELECT 'Molakalmuru Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 11/31: Dakshina Kannada (43 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Dakshina Kannada' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mangaluru' AS name UNION ALL SELECT 'Puttur' UNION ALL SELECT 'Bantwal' UNION ALL SELECT 'Belthangady' UNION ALL
  SELECT 'Sullia' UNION ALL SELECT 'Moodbidri' UNION ALL SELECT 'Ullal' UNION ALL SELECT 'Mulki' UNION ALL
  SELECT 'Surathkal' UNION ALL SELECT 'Kotekar' UNION ALL SELECT 'Deralakatte' UNION ALL SELECT 'Kankanady' UNION ALL
  SELECT 'Bejai' UNION ALL SELECT 'Kadri' UNION ALL SELECT 'Falnir' UNION ALL SELECT 'Hampankatta' UNION ALL
  SELECT 'Attavar' UNION ALL SELECT 'Balmatta' UNION ALL SELECT 'Kodialbail' UNION ALL SELECT 'Mannagudda' UNION ALL
  SELECT 'Boloor' UNION ALL SELECT 'Urwa' UNION ALL SELECT 'Kottara' UNION ALL SELECT 'Kulshekar' UNION ALL
  SELECT 'Pumpwell' UNION ALL SELECT 'Jeppu' UNION ALL SELECT 'Valencia' UNION ALL SELECT 'Pandeshwar' UNION ALL
  SELECT 'Bendoor' UNION ALL SELECT 'Kavoor' UNION ALL SELECT 'Kulur' UNION ALL SELECT 'Bajpe' UNION ALL
  SELECT 'Adyar' UNION ALL SELECT 'Thokkottu' UNION ALL SELECT 'Konaje' UNION ALL SELECT 'Natekal' UNION ALL
  SELECT 'Permannur' UNION ALL SELECT 'Kallapu' UNION ALL SELECT 'Puttur Town' UNION ALL SELECT 'Bantwal Town' UNION ALL
  SELECT 'Moodbidri Town' UNION ALL SELECT 'Sullia Town' UNION ALL SELECT 'Belthangady Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 12/31: Davanagere (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Davanagere' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Davanagere' AS name UNION ALL SELECT 'Harihar' UNION ALL SELECT 'Channagiri' UNION ALL SELECT 'Honnali' UNION ALL
  SELECT 'Jagalur' UNION ALL SELECT 'Nyamathi' UNION ALL SELECT 'Harapanahalli' UNION ALL SELECT 'Kundur' UNION ALL
  SELECT 'Mayakonda' UNION ALL SELECT 'Davanagere Town' UNION ALL SELECT 'Davanagere Rural' UNION ALL SELECT 'Vidyanagar' UNION ALL
  SELECT 'PJ Extension' UNION ALL SELECT 'MCC' UNION ALL SELECT 'SS Layout' UNION ALL SELECT 'Shamanur' UNION ALL
  SELECT 'Hadadi Road' UNION ALL SELECT 'Kunduwada' UNION ALL SELECT 'Harihar Town' UNION ALL SELECT 'Channagiri Town' UNION ALL
  SELECT 'Honnali Town' UNION ALL SELECT 'Jagalur Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 13/31: Dharwad (27 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Dharwad' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dharwad' AS name UNION ALL SELECT 'Hubballi' UNION ALL SELECT 'Kalghatgi' UNION ALL SELECT 'Kundgol' UNION ALL
  SELECT 'Navalgund' UNION ALL SELECT 'Alnavar' UNION ALL SELECT 'Annigeri' UNION ALL SELECT 'Gokul Road' UNION ALL
  SELECT 'Vidyanagar' UNION ALL SELECT 'Keshwapur' UNION ALL SELECT 'Deshpande Nagar' UNION ALL SELECT 'Hubballi Old Town' UNION ALL
  SELECT 'Unkal' UNION ALL SELECT 'Gokul' UNION ALL SELECT 'Tarihal' UNION ALL SELECT 'Hosur' UNION ALL
  SELECT 'Chennamma Circle' UNION ALL SELECT 'Shirur Park' UNION ALL SELECT 'Sattur' UNION ALL SELECT 'Navanagar' UNION ALL
  SELECT 'Airport Road' UNION ALL SELECT 'Dharwad City' UNION ALL SELECT 'Dharwad Rural' UNION ALL SELECT 'Hubballi Rural' UNION ALL
  SELECT 'Kalghatgi Town' UNION ALL SELECT 'Kundgol Town' UNION ALL SELECT 'Navalgund Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 14/31: Gadag (16 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Gadag' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gadag' AS name UNION ALL SELECT 'Betageri' UNION ALL SELECT 'Ron' UNION ALL SELECT 'Nargund' UNION ALL
  SELECT 'Shirahatti' UNION ALL SELECT 'Gajendragad' UNION ALL SELECT 'Lakshmeshwar' UNION ALL SELECT 'Mundargi' UNION ALL
  SELECT 'Gadag-Betageri' UNION ALL SELECT 'Dambal' UNION ALL SELECT 'Gajendragad Town' UNION ALL SELECT 'Ron Town' UNION ALL
  SELECT 'Nargund Town' UNION ALL SELECT 'Shirahatti Town' UNION ALL SELECT 'Lakshmeshwar Town' UNION ALL SELECT 'Mundargi Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 15/31: Hassan (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Hassan' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Hassan' AS name UNION ALL SELECT 'Belur' UNION ALL SELECT 'Arsikere' UNION ALL SELECT 'Channarayapatna' UNION ALL
  SELECT 'Alur' UNION ALL SELECT 'Arkalgud' UNION ALL SELECT 'Sakleshpur' UNION ALL SELECT 'Holenarasipura' UNION ALL
  SELECT 'Shravanabelagola' UNION ALL SELECT 'Dudda' UNION ALL SELECT 'Banavara' UNION ALL SELECT 'Javagal' UNION ALL
  SELECT 'Konanur' UNION ALL SELECT 'Mosale' UNION ALL SELECT 'Hassan City' UNION ALL SELECT 'Hassan Rural' UNION ALL
  SELECT 'Belur Town' UNION ALL SELECT 'Arsikere Town' UNION ALL SELECT 'Sakleshpur Town' UNION ALL SELECT 'Channarayapatna Town' UNION ALL
  SELECT 'Holenarasipura Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 16/31: Haveri (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Haveri' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Haveri' AS name UNION ALL SELECT 'Ranebennur' UNION ALL SELECT 'Byadgi' UNION ALL SELECT 'Hangal' UNION ALL
  SELECT 'Hirekerur' UNION ALL SELECT 'Savanur' UNION ALL SELECT 'Shiggaon' UNION ALL SELECT 'Rattihalli' UNION ALL
  SELECT 'Bankapura' UNION ALL SELECT 'Motebennur' UNION ALL SELECT 'Haveri Town' UNION ALL SELECT 'Haveri Rural' UNION ALL
  SELECT 'Ranebennur Town' UNION ALL SELECT 'Byadgi Town' UNION ALL SELECT 'Hangal Town' UNION ALL SELECT 'Hirekerur Town' UNION ALL
  SELECT 'Savanur Town' UNION ALL SELECT 'Shiggaon Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 17/31: Kalaburagi (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Kalaburagi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kalaburagi' AS name UNION ALL SELECT 'Afzalpur' UNION ALL SELECT 'Aland' UNION ALL SELECT 'Chincholi' UNION ALL
  SELECT 'Chittapur' UNION ALL SELECT 'Jewargi' UNION ALL SELECT 'Sedam' UNION ALL SELECT 'Shahabad' UNION ALL
  SELECT 'Wadi' UNION ALL SELECT 'Kamalapur' UNION ALL SELECT 'Kalaburagi City' UNION ALL SELECT 'Station Bazar' UNION ALL
  SELECT 'Super Market' UNION ALL SELECT 'Sedam Road' UNION ALL SELECT 'Aland Road' UNION ALL SELECT 'Ring Road' UNION ALL
  SELECT 'Kuvempu Nagar' UNION ALL SELECT 'Bramhapur' UNION ALL SELECT 'Kotnoor' UNION ALL SELECT 'Naganhalli' UNION ALL
  SELECT 'Hagaribommanahalli' UNION ALL SELECT 'Chittapur Town' UNION ALL SELECT 'Sedam Town' UNION ALL SELECT 'Jewargi Town' UNION ALL
  SELECT 'Afzalpur Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 18/31: Kodagu (16 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Kodagu' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Madikeri' AS name UNION ALL SELECT 'Virajpet' UNION ALL SELECT 'Somwarpet' UNION ALL SELECT 'Kushalnagar' UNION ALL
  SELECT 'Gonikoppal' UNION ALL SELECT 'Virajpet Town' UNION ALL SELECT 'Napoklu' UNION ALL SELECT 'Suntikoppa' UNION ALL
  SELECT 'Siddapura' UNION ALL SELECT 'Ammathi' UNION ALL SELECT 'Ponnampet' UNION ALL SELECT 'Gonikoppal Town' UNION ALL
  SELECT 'Somwarpet Town' UNION ALL SELECT 'Kushalnagar Town' UNION ALL SELECT 'Madikeri Town' UNION ALL SELECT 'Madikeri Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 19/31: Kolar (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Kolar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kolar' AS name UNION ALL SELECT 'KGF' UNION ALL SELECT 'Bangarapet' UNION ALL SELECT 'Malur' UNION ALL
  SELECT 'Mulbagal' UNION ALL SELECT 'Srinivaspur' UNION ALL SELECT 'Bethamangala' UNION ALL SELECT 'Robertsonpet' UNION ALL
  SELECT 'Kolar Gold Fields' UNION ALL SELECT 'Kolar Town' UNION ALL SELECT 'Kolar Rural' UNION ALL SELECT 'Malur Town' UNION ALL
  SELECT 'Mulbagal Town' UNION ALL SELECT 'Srinivaspur Town' UNION ALL SELECT 'Bangarapet Town' UNION ALL SELECT 'Narasapura' UNION ALL
  SELECT 'Vemagal' UNION ALL SELECT 'Tekal'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 20/31: Koppal (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Koppal' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Koppal' AS name UNION ALL SELECT 'Gangavathi' UNION ALL SELECT 'Kushtagi' UNION ALL SELECT 'Yelburga' UNION ALL
  SELECT 'Kanakagiri' UNION ALL SELECT 'Karatagi' UNION ALL SELECT 'Munirabad' UNION ALL SELECT 'Kuknoor' UNION ALL
  SELECT 'Anegundi' UNION ALL SELECT 'Koppal Town' UNION ALL SELECT 'Gangavathi Town' UNION ALL SELECT 'Kushtagi Town' UNION ALL
  SELECT 'Yelburga Town' UNION ALL SELECT 'Kanakagiri Town' UNION ALL SELECT 'Karatagi Town' UNION ALL SELECT 'Hampi Road' UNION ALL
  SELECT 'Hospet Road'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 21/31: Mandya (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Mandya' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mandya' AS name UNION ALL SELECT 'Maddur' UNION ALL SELECT 'Malavalli' UNION ALL SELECT 'Srirangapatna' UNION ALL
  SELECT 'Pandavapura' UNION ALL SELECT 'Krishnarajpet' UNION ALL SELECT 'Nagamangala' UNION ALL SELECT 'Kirugavalu' UNION ALL
  SELECT 'Bellur' UNION ALL SELECT 'Melukote' UNION ALL SELECT 'Mandya Town' UNION ALL SELECT 'Mandya Rural' UNION ALL
  SELECT 'Maddur Town' UNION ALL SELECT 'Malavalli Town' UNION ALL SELECT 'Srirangapatna Town' UNION ALL SELECT 'Pandavapura Town' UNION ALL
  SELECT 'KR Pet Town' UNION ALL SELECT 'Nagamangala Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 22/31: Mysuru (39 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Mysuru' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mysuru' AS name UNION ALL SELECT 'Nanjangud' UNION ALL SELECT 'Hunsur' UNION ALL SELECT 'T. Narasipura' UNION ALL
  SELECT 'H.D. Kote' UNION ALL SELECT 'Periyapatna' UNION ALL SELECT 'Krishnarajanagara' UNION ALL SELECT 'Bannur' UNION ALL
  SELECT 'Srirampura' UNION ALL SELECT 'Hebbal' UNION ALL SELECT 'Vijayanagar' UNION ALL SELECT 'Kuvempunagar' UNION ALL
  SELECT 'Saraswathipuram' UNION ALL SELECT 'Jayalakshmipuram' UNION ALL SELECT 'Vontikoppal' UNION ALL SELECT 'Gokulam' UNION ALL
  SELECT 'Yadavagiri' UNION ALL SELECT 'Lakshmipuram' UNION ALL SELECT 'Nazarbad' UNION ALL SELECT 'Ashokapuram' UNION ALL
  SELECT 'Bannimantap' UNION ALL SELECT 'Rajiv Nagar' UNION ALL SELECT 'Udayagiri' UNION ALL SELECT 'Hebbal Industrial Area' UNION ALL
  SELECT 'Hootagalli' UNION ALL SELECT 'Belavadi' UNION ALL SELECT 'Dattagalli' UNION ALL SELECT 'Bogadi' UNION ALL
  SELECT 'Ramakrishnanagar' UNION ALL SELECT 'JP Nagar' UNION ALL SELECT 'Alanahalli' UNION ALL SELECT 'Kadakola' UNION ALL
  SELECT 'Koorgalli' UNION ALL SELECT 'Belagola' UNION ALL SELECT 'Infosys Campus Area' UNION ALL SELECT 'Nanjangud Town' UNION ALL
  SELECT 'Hunsur Town' UNION ALL SELECT 'T. Narasipura Town' UNION ALL SELECT 'Periyapatna Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 23/31: Raichur (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Raichur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Raichur' AS name UNION ALL SELECT 'Manvi' UNION ALL SELECT 'Sindhanur' UNION ALL SELECT 'Devadurga' UNION ALL
  SELECT 'Lingasugur' UNION ALL SELECT 'Maski' UNION ALL SELECT 'Sirwar' UNION ALL SELECT 'Mudgal' UNION ALL
  SELECT 'Shaktinagar' UNION ALL SELECT 'Yeramarus' UNION ALL SELECT 'Raichur City' UNION ALL SELECT 'Station Road' UNION ALL
  SELECT 'Devadurga Town' UNION ALL SELECT 'Manvi Town' UNION ALL SELECT 'Sindhanur Town' UNION ALL SELECT 'Lingasugur Town' UNION ALL
  SELECT 'Maski Town' UNION ALL SELECT 'Mudgal Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 24/31: Ramanagara (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Ramanagara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Ramanagara' AS name UNION ALL SELECT 'Channapatna' UNION ALL SELECT 'Kanakapura' UNION ALL SELECT 'Magadi' UNION ALL
  SELECT 'Bidadi' UNION ALL SELECT 'Harohalli' UNION ALL SELECT 'Sathanur' UNION ALL SELECT 'Kodihalli' UNION ALL
  SELECT 'Ramanagara Town' UNION ALL SELECT 'Channapatna Town' UNION ALL SELECT 'Kanakapura Town' UNION ALL SELECT 'Magadi Town' UNION ALL
  SELECT 'Bidadi Industrial Area' UNION ALL SELECT 'Harohalli Industrial Area' UNION ALL SELECT 'Ramanagara Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 25/31: Shivamogga (23 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Shivamogga' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Shivamogga' AS name UNION ALL SELECT 'Bhadravati' UNION ALL SELECT 'Sagar' UNION ALL SELECT 'Shikaripura' UNION ALL
  SELECT 'Sorab' UNION ALL SELECT 'Thirthahalli' UNION ALL SELECT 'Hosanagara' UNION ALL SELECT 'Shiralakoppa' UNION ALL
  SELECT 'Jog Falls' UNION ALL SELECT 'Gajanur' UNION ALL SELECT 'Vinobanagar' UNION ALL SELECT 'Vidyanagar' UNION ALL
  SELECT 'Savalanga Road' UNION ALL SELECT 'Gopala' UNION ALL SELECT 'Gandhi Nagar' UNION ALL SELECT 'Tilak Nagar' UNION ALL
  SELECT 'KR Puram' UNION ALL SELECT 'Bhadravati Town' UNION ALL SELECT 'Sagar Town' UNION ALL SELECT 'Shikaripura Town' UNION ALL
  SELECT 'Sorab Town' UNION ALL SELECT 'Thirthahalli Town' UNION ALL SELECT 'Hosanagara Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 26/31: Tumakuru (28 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Tumakuru' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Tumakuru' AS name UNION ALL SELECT 'Tiptur' UNION ALL SELECT 'Gubbi' UNION ALL SELECT 'Kunigal' UNION ALL
  SELECT 'Sira' UNION ALL SELECT 'Madhugiri' UNION ALL SELECT 'Pavagada' UNION ALL SELECT 'Koratagere' UNION ALL
  SELECT 'Chikkanayakanahalli' UNION ALL SELECT 'Turuvekere' UNION ALL SELECT 'Dobbaspet' UNION ALL SELECT 'Kyathsandra' UNION ALL
  SELECT 'Batawadi' UNION ALL SELECT 'Tumakuru City' UNION ALL SELECT 'Tumakuru Rural' UNION ALL SELECT 'SS Puram' UNION ALL
  SELECT 'BH Road' UNION ALL SELECT 'SIT Extension' UNION ALL SELECT 'Maruthi Nagar' UNION ALL SELECT 'Kuvempu Nagar' UNION ALL
  SELECT 'Vidyanagar' UNION ALL SELECT 'Gokul' UNION ALL SELECT 'Tiptur Town' UNION ALL SELECT 'Gubbi Town' UNION ALL
  SELECT 'Kunigal Town' UNION ALL SELECT 'Sira Town' UNION ALL SELECT 'Madhugiri Town' UNION ALL SELECT 'Pavagada Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 27/31: Udupi (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Udupi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Udupi' AS name UNION ALL SELECT 'Manipal' UNION ALL SELECT 'Kundapura' UNION ALL SELECT 'Karkala' UNION ALL
  SELECT 'Brahmavar' UNION ALL SELECT 'Kaup' UNION ALL SELECT 'Byndoor' UNION ALL SELECT 'Hebri' UNION ALL
  SELECT 'Saligrama' UNION ALL SELECT 'Padubidri' UNION ALL SELECT 'Malpe' UNION ALL SELECT 'Santhekatte' UNION ALL
  SELECT 'Kinnimulki' UNION ALL SELECT 'Ajjarkad' UNION ALL SELECT 'Indrali' UNION ALL SELECT 'Bannanje' UNION ALL
  SELECT 'Parkala' UNION ALL SELECT 'Manipal End Point' UNION ALL SELECT 'Eshwar Nagar' UNION ALL SELECT 'Tiger Circle' UNION ALL
  SELECT 'Udupi Town' UNION ALL SELECT 'Kundapura Town' UNION ALL SELECT 'Karkala Town' UNION ALL SELECT 'Kaup Town' UNION ALL
  SELECT 'Byndoor Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 28/31: Uttara Kannada (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Uttara Kannada' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Karwar' AS name UNION ALL SELECT 'Sirsi' UNION ALL SELECT 'Kumta' UNION ALL SELECT 'Honnavar' UNION ALL
  SELECT 'Bhatkal' UNION ALL SELECT 'Ankola' UNION ALL SELECT 'Dandeli' UNION ALL SELECT 'Yellapur' UNION ALL
  SELECT 'Siddapur' UNION ALL SELECT 'Mundgod' UNION ALL SELECT 'Haliyal' UNION ALL SELECT 'Joida' UNION ALL
  SELECT 'Gokarna' UNION ALL SELECT 'Murudeshwar' UNION ALL SELECT 'Kumta Town' UNION ALL SELECT 'Honnavar Town' UNION ALL
  SELECT 'Bhatkal Town' UNION ALL SELECT 'Ankola Town' UNION ALL SELECT 'Sirsi Town' UNION ALL SELECT 'Karwar Town' UNION ALL
  SELECT 'Dandeli Town' UNION ALL SELECT 'Yellapur Town' UNION ALL SELECT 'Gokarna Town' UNION ALL SELECT 'Murudeshwar Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 29/31: Vijayapura (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Vijayapura' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Vijayapura' AS name UNION ALL SELECT 'Indi' UNION ALL SELECT 'Basavana Bagewadi' UNION ALL SELECT 'Muddebihal' UNION ALL
  SELECT 'Sindagi' UNION ALL SELECT 'Devar Hippargi' UNION ALL SELECT 'Talikoti' UNION ALL SELECT 'Chadchan' UNION ALL
  SELECT 'Babaleshwar' UNION ALL SELECT 'Tikota' UNION ALL SELECT 'Vijayapura City' UNION ALL SELECT 'Solapur Road' UNION ALL
  SELECT 'Athani Road' UNION ALL SELECT 'Station Road' UNION ALL SELECT 'Gol Gumbaz Area' UNION ALL SELECT 'Sainik School Area' UNION ALL
  SELECT 'Adarsh Nagar' UNION ALL SELECT 'Jala Nagar' UNION ALL SELECT 'Navabharat Nagar' UNION ALL SELECT 'Indi Town' UNION ALL
  SELECT 'Sindagi Town' UNION ALL SELECT 'Muddebihal Town' UNION ALL SELECT 'Basavana Bagewadi Town' UNION ALL SELECT 'Talikoti Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 30/31: Vijayanagara (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Vijayanagara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Hosapete' AS name UNION ALL SELECT 'Harapanahalli' UNION ALL SELECT 'Kudligi' UNION ALL SELECT 'Huvina Hadagali' UNION ALL
  SELECT 'Hoovinahadagali' UNION ALL SELECT 'Hospet' UNION ALL SELECT 'Kamalapur' UNION ALL SELECT 'Hampi' UNION ALL
  SELECT 'Kampli' UNION ALL SELECT 'Mariyammanahalli' UNION ALL SELECT 'Kottur' UNION ALL SELECT 'Kudatini' UNION ALL
  SELECT 'Toranagallu' UNION ALL SELECT 'Gadiganur' UNION ALL SELECT 'Hampi Road' UNION ALL SELECT 'Hospet City' UNION ALL
  SELECT 'Hosapete Town' UNION ALL SELECT 'Hampi Town' UNION ALL SELECT 'Harapanahalli Town' UNION ALL SELECT 'Kudligi Town' UNION ALL
  SELECT 'Kampli Town'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 31/31: Yadgir (15 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ka AND `name` = 'Yadgir' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Yadgir' AS name UNION ALL SELECT 'Shahapur' UNION ALL SELECT 'Shorapur' UNION ALL SELECT 'Gurmitkal' UNION ALL
  SELECT 'Vadgera' UNION ALL SELECT 'Hunasagi' UNION ALL SELECT 'Hattikuni' UNION ALL SELECT 'Yadgir Town' UNION ALL
  SELECT 'Shahapur Town' UNION ALL SELECT 'Shorapur Town' UNION ALL SELECT 'Gurmitkal Town' UNION ALL SELECT 'Hunasagi Town' UNION ALL
  SELECT 'Yadgir Rural' UNION ALL SELECT 'Shahapur Rural' UNION ALL SELECT 'Shorapur Rural'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));
