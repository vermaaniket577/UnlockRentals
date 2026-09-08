-- ==============================================================================
-- SIKKIM: ALL 6 DISTRICTS & 590 LOCALITIES MASTER SQL QUERY
-- Covers: Gangtok, Gyalshing, Namchi, Mangan, Pakyong, Soreng
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Sikkim' (SK)
INSERT INTO `states` (`code`, `name`)
SELECT 'SK', 'Sikkim' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'SK' OR `name` = 'Sikkim');

SET @sk = (SELECT `id` FROM `states` WHERE `code` = 'SK' LIMIT 1);

-- 2. Ensure All 6 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @sk, d.name FROM (
  SELECT 'Gangtok' AS name UNION ALL SELECT 'Gyalshing' UNION ALL SELECT 'Namchi' UNION ALL SELECT 'Mangan' UNION ALL SELECT 'Pakyong' UNION ALL SELECT 'Soreng'
) d WHERE NOT EXISTS (SELECT 1 FROM `districts` WHERE `state_id` = @sk AND LOWER(`name`) = LOWER(d.name));

-- 3. Insert Localities for All 6 Districts

-- District 1/6: Gangtok (105 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @sk AND `name` IN ('Gangtok', 'Gangtok District') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gangtok' AS name UNION ALL SELECT 'MG Marg' UNION ALL SELECT 'Development Area' UNION ALL SELECT 'Deorali' UNION ALL
  SELECT 'Tadong' UNION ALL SELECT 'Sichey' UNION ALL SELECT 'Arithang' UNION ALL SELECT 'Upper Sichey' UNION ALL
  SELECT 'Lower Sichey' UNION ALL SELECT 'Diesel Power House' UNION ALL SELECT 'Denzong Colony' UNION ALL SELECT 'Burton Road' UNION ALL
  SELECT 'Lal Bazaar' UNION ALL SELECT 'Tibet Road' UNION ALL SELECT 'Chandmari' UNION ALL SELECT 'Development Area Extension' UNION ALL
  SELECT 'Ranipool' UNION ALL SELECT 'Tashi View Point' UNION ALL SELECT 'Lingdok' UNION ALL SELECT 'Nathang Valley' UNION ALL
  SELECT 'Gnathang' UNION ALL SELECT 'Kupup' UNION ALL SELECT 'Tsomgo' UNION ALL SELECT 'Sherathang' UNION ALL
  SELECT 'Nathu La' UNION ALL SELECT 'Rumtek' UNION ALL SELECT 'Rumtek Monastery' UNION ALL SELECT 'Lingdum' UNION ALL
  SELECT 'Ranka' UNION ALL SELECT 'Ranka Bazaar' UNION ALL SELECT 'Luing' UNION ALL SELECT 'Perbing' UNION ALL
  SELECT 'Rey' UNION ALL SELECT 'Mendu' UNION ALL SELECT 'Rawtey Rumtek' UNION ALL SELECT 'Samlik' UNION ALL
  SELECT 'Marchak' UNION ALL SELECT 'Namli' UNION ALL SELECT 'Martam' UNION ALL SELECT 'Nazitam' UNION ALL
  SELECT 'Beng' UNION ALL SELECT 'Phegyong' UNION ALL SELECT 'Sirwani' UNION ALL SELECT 'Chisopani' UNION ALL
  SELECT 'Khamdong' UNION ALL SELECT 'Dung Dung' UNION ALL SELECT 'Thasa' UNION ALL SELECT 'Simik Lingzey' UNION ALL
  SELECT 'Tumin' UNION ALL SELECT 'Patuk' UNION ALL SELECT 'Singbel' UNION ALL SELECT 'Samdong' UNION ALL
  SELECT 'Kambal' UNION ALL SELECT 'Rakdong Tintek' UNION ALL SELECT 'Nandok' UNION ALL SELECT 'Saramsa' UNION ALL
  SELECT 'Rongay' UNION ALL SELECT 'Tathangchen' UNION ALL SELECT 'Kopibari' UNION ALL SELECT 'Syari' UNION ALL
  SELECT 'Pakyong' UNION ALL SELECT 'Pakyong Bazaar' UNION ALL SELECT 'Parakha' UNION ALL SELECT 'Regu' UNION ALL
  SELECT 'Rhenock' UNION ALL SELECT 'Aritar' UNION ALL SELECT 'Sudunglakha' UNION ALL SELECT 'Taza' UNION ALL
  SELECT 'Amba' UNION ALL SELECT 'Tarpin' UNION ALL SELECT 'Chalamthang' UNION ALL SELECT 'Pacheykhani' UNION ALL
  SELECT 'West Pendam' UNION ALL SELECT 'Central Pendam' UNION ALL SELECT 'East Pendam' UNION ALL SELECT 'Duga' UNION ALL
  SELECT 'Sumin' UNION ALL SELECT 'Budang' UNION ALL SELECT 'Kamerey' UNION ALL SELECT 'Singtam' UNION ALL
  SELECT 'Singtam Bazaar' UNION ALL SELECT 'Majitar' UNION ALL SELECT 'Makha' UNION ALL SELECT 'Dikchu' UNION ALL
  SELECT 'Temi' UNION ALL SELECT 'Bojoghari' UNION ALL SELECT 'Burtuk' UNION ALL SELECT 'Chandmari Gangtok' UNION ALL
  SELECT 'Gangtok BAC' UNION ALL SELECT 'Gangtok City (State Capital)' UNION ALL SELECT 'Khamdong BAC' UNION ALL SELECT 'Lower Burtuk' UNION ALL
  SELECT 'MG Marg Gangtok' UNION ALL SELECT 'Martam BAC' UNION ALL SELECT 'Nam Nang' UNION ALL SELECT 'Nandok BAC' UNION ALL
  SELECT 'Nathula Gateway' UNION ALL SELECT 'Penlong' UNION ALL SELECT 'Rakdong Tintek BAC' UNION ALL SELECT 'Rangpo' UNION ALL
  SELECT 'Ranka BAC' UNION ALL SELECT 'Shyari' UNION ALL SELECT 'Sungava' UNION ALL SELECT 'Tsongmo Lake Area' UNION ALL
  SELECT 'Upper Burtuk'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 2/6: Gyalshing (97 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @sk AND `name` IN ('Gyalshing', 'Gyalshing District') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gyalshing' AS name UNION ALL SELECT 'Geyzing' UNION ALL SELECT 'Gyalshing Bazaar' UNION ALL SELECT 'Pelling' UNION ALL
  SELECT 'Upper Pelling' UNION ALL SELECT 'Lower Pelling' UNION ALL SELECT 'Chumbong' UNION ALL SELECT 'Yuksom' UNION ALL
  SELECT 'Yuksam' UNION ALL SELECT 'Yuksom Bazaar' UNION ALL SELECT 'Khecheopalri' UNION ALL SELECT 'Khecheopari' UNION ALL
  SELECT 'Dentam' UNION ALL SELECT 'Dentam Bazaar' UNION ALL SELECT 'Hee Martam' UNION ALL SELECT 'Tashiding' UNION ALL
  SELECT 'Tashiding Monastery' UNION ALL SELECT 'Karchi Mangnam' UNION ALL SELECT 'Dhupidara' UNION ALL SELECT 'Narkhola' UNION ALL
  SELECT 'Kongri' UNION ALL SELECT 'Labdang' UNION ALL SELECT 'Arithang' UNION ALL SELECT 'Chongrang' UNION ALL
  SELECT 'Garethang' UNION ALL SELECT 'Thingling' UNION ALL SELECT 'Melli' UNION ALL SELECT 'Melli Aching' UNION ALL
  SELECT 'Darap' UNION ALL SELECT 'Singyang' UNION ALL SELECT 'Chongphong' UNION ALL SELECT 'Yangten' UNION ALL
  SELECT 'Yangthang' UNION ALL SELECT 'Lingchom' UNION ALL SELECT 'Tikjya' UNION ALL SELECT 'Sardong' UNION ALL
  SELECT 'Lungzik' UNION ALL SELECT 'Bongten' UNION ALL SELECT 'Sapong' UNION ALL SELECT 'Karmatar' UNION ALL
  SELECT 'Gyaten' UNION ALL SELECT 'Maneybong' UNION ALL SELECT 'Sopakha' UNION ALL SELECT 'Sangkhu' UNION ALL
  SELECT 'Radukhandu' UNION ALL SELECT 'Pachrek' UNION ALL SELECT 'Hee Patal' UNION ALL SELECT 'Bermiok' UNION ALL
  SELECT 'Berthang' UNION ALL SELECT 'Chingthang' UNION ALL SELECT 'Sangadorji' UNION ALL SELECT 'Tadong' UNION ALL
  SELECT 'Rinchenpong' UNION ALL SELECT 'Samdong' UNION ALL SELECT 'Deythang' UNION ALL SELECT 'Takuthang' UNION ALL
  SELECT 'Suldung' UNION ALL SELECT 'Kamling' UNION ALL SELECT 'Mabong' UNION ALL SELECT 'Segeng' UNION ALL
  SELECT 'Legship' UNION ALL SELECT 'Rabdentse' UNION ALL SELECT 'Soreng Road' UNION ALL SELECT 'Arithang Chongrang' UNION ALL
  SELECT 'Chongrang BAC' UNION ALL SELECT 'Darap Singpheng' UNION ALL SELECT 'Dentam BAC' UNION ALL SELECT 'Dhupidara Narkhola' UNION ALL
  SELECT 'Gerethang' UNION ALL SELECT 'Gyalshing BAC' UNION ALL SELECT 'Gyalshing Nagar Panchayat' UNION ALL SELECT 'Gyalshing Town' UNION ALL
  SELECT 'Hee' UNION ALL SELECT 'Hee Martam BAC' UNION ALL SELECT 'Karchi' UNION ALL SELECT 'Khechodpalri' UNION ALL
  SELECT 'Khechopari' UNION ALL SELECT 'Kongri Labdang' UNION ALL SELECT 'Kyongsa' UNION ALL SELECT 'Mangnam' UNION ALL
  SELECT 'Martam' UNION ALL SELECT 'Meliaching' UNION ALL SELECT 'Nambu' UNION ALL SELECT 'Nambu Sindrabong' UNION ALL
  SELECT 'Omchung' UNION ALL SELECT 'Pelling (Kanchenjunga Views)' UNION ALL SELECT 'Pelling Bazaar' UNION ALL SELECT 'Pemayangtse' UNION ALL
  SELECT 'Rimbi' UNION ALL SELECT 'Sindrabong' UNION ALL SELECT 'Singpheng' UNION ALL SELECT 'Tikjuk' UNION ALL
  SELECT 'Tingbrum' UNION ALL SELECT 'Yangtey' UNION ALL SELECT 'Yuksom (First Capital)' UNION ALL SELECT 'Yuksom BAC' UNION ALL
  SELECT 'Yuksum'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 3/6: Namchi (171 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @sk AND `name` IN ('Namchi', 'Namchi District') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Namchi' AS name UNION ALL SELECT 'Namchi Bazaar' UNION ALL SELECT 'Purano Namchi' UNION ALL SELECT 'Gangyap' UNION ALL
  SELECT 'Dambudara' UNION ALL SELECT 'Upper Ghurpisey' UNION ALL SELECT 'Lower Ghurpisey' UNION ALL SELECT 'Upper Boomtar' UNION ALL
  SELECT 'Singhithang' UNION ALL SELECT 'Boomtar' UNION ALL SELECT 'Damthang' UNION ALL SELECT 'Chemchey' UNION ALL
  SELECT 'Chumlok' UNION ALL SELECT 'Chuba' UNION ALL SELECT 'Aifaltar' UNION ALL SELECT 'Assangthang' UNION ALL
  SELECT 'Barnyak' UNION ALL SELECT 'Barul' UNION ALL SELECT 'Bikmat' UNION ALL SELECT 'Bul' UNION ALL
  SELECT 'Donak' UNION ALL SELECT 'Doring' UNION ALL SELECT 'Gangchung' UNION ALL SELECT 'Gom' UNION ALL
  SELECT 'Jaubari' UNION ALL SELECT 'Kabrey' UNION ALL SELECT 'Kamarey' UNION ALL SELECT 'Kamrang' UNION ALL
  SELECT 'Karek' UNION ALL SELECT 'Khanamtek' UNION ALL SELECT 'Kitam' UNION ALL SELECT 'Kopchey' UNION ALL
  SELECT 'Lungchok' UNION ALL SELECT 'Mamley' UNION ALL SELECT 'Mamring' UNION ALL SELECT 'Maneydara' UNION ALL
  SELECT 'Maniram' UNION ALL SELECT 'Manpur' UNION ALL SELECT 'Mikkhola' UNION ALL SELECT 'Nagi' UNION ALL
  SELECT 'Nalam' UNION ALL SELECT 'Kolbung' UNION ALL SELECT 'Namphing' UNION ALL SELECT 'Nizrameng' UNION ALL
  SELECT 'Omchu' UNION ALL SELECT 'Pabong' UNION ALL SELECT 'Pakjer' UNION ALL SELECT 'Pakytam' UNION ALL
  SELECT 'Pallum' UNION ALL SELECT 'Pamphok' UNION ALL SELECT 'Passi' UNION ALL SELECT 'Perbing' UNION ALL
  SELECT 'Phalidara' UNION ALL SELECT 'Phong' UNION ALL SELECT 'Rabikhola' UNION ALL SELECT 'Rabitar' UNION ALL
  SELECT 'Rangpo' UNION ALL SELECT 'Rashyap' UNION ALL SELECT 'Rateypani' UNION ALL SELECT 'Rong' UNION ALL
  SELECT 'Sadam' UNION ALL SELECT 'Salleybong' UNION ALL SELECT 'Shyampani' UNION ALL SELECT 'Singtam' UNION ALL
  SELECT 'Sorok' UNION ALL SELECT 'Sukrabarey' UNION ALL SELECT 'Suntoley' UNION ALL SELECT 'Tanak' UNION ALL
  SELECT 'Tangzi' UNION ALL SELECT 'Tarku' UNION ALL SELECT 'Temi' UNION ALL SELECT 'Temi Tea Estate' UNION ALL
  SELECT 'Tingrithang' UNION ALL SELECT 'Tinzeer' UNION ALL SELECT 'Tokal' UNION ALL SELECT 'Tokdey' UNION ALL
  SELECT 'Turung' UNION ALL SELECT 'Wok' UNION ALL SELECT 'Jorethang' UNION ALL SELECT 'Jorethang Bazaar' UNION ALL
  SELECT 'Naya Bazar' UNION ALL SELECT 'Nayabazar' UNION ALL SELECT 'Shantinagar' UNION ALL SELECT 'Trikaleshwar' UNION ALL
  SELECT 'Daragaon' UNION ALL SELECT 'Majhigaon' UNION ALL SELECT 'Melli' UNION ALL SELECT 'Mellidara' UNION ALL
  SELECT 'Paiyong' UNION ALL SELECT 'Panchgharey' UNION ALL SELECT 'Poklok' UNION ALL SELECT 'Ramabung' UNION ALL
  SELECT 'Salghari' UNION ALL SELECT 'Sangbung' UNION ALL SELECT 'Sumbuk' UNION ALL SELECT 'Suntaley' UNION ALL
  SELECT 'Tinik' UNION ALL SELECT 'Turuk' UNION ALL SELECT 'Kerabari' UNION ALL SELECT 'Ravangla' UNION ALL
  SELECT 'Ravongla Bazaar' UNION ALL SELECT 'Rabong Bazaar' UNION ALL SELECT 'Bakhim' UNION ALL SELECT 'Barfung' UNION ALL
  SELECT 'Ben' UNION ALL SELECT 'Borong' UNION ALL SELECT 'Dalep' UNION ALL SELECT 'Deu' UNION ALL
  SELECT 'Deythang' UNION ALL SELECT 'Hingdam' UNION ALL SELECT 'Jarrong' UNION ALL SELECT 'Kewzing' UNION ALL
  SELECT 'Lamting' UNION ALL SELECT 'Legship' UNION ALL SELECT 'Lingding' UNION ALL SELECT 'Lingzo' UNION ALL
  SELECT 'Mangbrue' UNION ALL SELECT 'Namlung' UNION ALL SELECT 'Namprik' UNION ALL SELECT 'Phamthang' UNION ALL
  SELECT 'Polok' UNION ALL SELECT 'Ralong' UNION ALL SELECT 'Ralong Monastery' UNION ALL SELECT 'Rayong' UNION ALL
  SELECT 'Sada' UNION ALL SELECT 'Sanganath' UNION ALL SELECT 'Sangmoo' UNION ALL SELECT 'Thangsing' UNION ALL
  SELECT 'Tingmo' UNION ALL SELECT 'Tinkitam' UNION ALL SELECT 'Yangang' UNION ALL SELECT 'Lower Paiyong' UNION ALL
  SELECT 'Upper Paiyong' UNION ALL SELECT 'Gagyong' UNION ALL SELECT 'Kau' UNION ALL SELECT 'Kolthang' UNION ALL
  SELECT 'Lingi' UNION ALL SELECT 'Lingmo' UNION ALL SELECT 'Mangzing' UNION ALL SELECT 'Namphok' UNION ALL
  SELECT 'Neh-Brum' UNION ALL SELECT 'Pepthang' UNION ALL SELECT 'Rangang' UNION ALL SELECT 'Satam' UNION ALL
  SELECT 'Sokpay' UNION ALL SELECT 'Sripatam' UNION ALL SELECT 'Tokday' UNION ALL SELECT 'Boomtar Salleybong' UNION ALL
  SELECT 'Jorethang Nagar Panchayat' UNION ALL SELECT 'Kitam Manpur' UNION ALL SELECT 'Maniram Phalidara' UNION ALL SELECT 'Melli Bazaar' UNION ALL
  SELECT 'Melli Sumbuk' UNION ALL SELECT 'Melli Sumbuk BAC' UNION ALL SELECT 'Mickhola' UNION ALL SELECT 'Mickhola Singithang' UNION ALL
  SELECT 'Namchi BAC' UNION ALL SELECT 'Namchi Municipal Council' UNION ALL SELECT 'Namchi Town (Char Dham Area)' UNION ALL SELECT 'Namthang' UNION ALL
  SELECT 'Ravangla (Buddha Park)' UNION ALL SELECT 'Ravangla Bazaar' UNION ALL SELECT 'Rongbul' UNION ALL SELECT 'Sikip' UNION ALL
  SELECT 'Sorok Shyampani' UNION ALL SELECT 'Tashiding' UNION ALL SELECT 'Temi Tarku' UNION ALL SELECT 'Temi Tea Garden Area' UNION ALL
  SELECT 'Temi-Tarku BAC' UNION ALL SELECT 'Wok-Sikkip BAC' UNION ALL SELECT 'Yangyang BAC'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 4/6: Mangan (62 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @sk AND `name` IN ('Mangan', 'Mangan District') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mangan' AS name UNION ALL SELECT 'Mangan Bazaar' UNION ALL SELECT 'Upper Mangan' UNION ALL SELECT 'Lower Mangan' UNION ALL
  SELECT 'Singhik' UNION ALL SELECT 'Kabi' UNION ALL SELECT 'Kabi Lungchok' UNION ALL SELECT 'Phensong' UNION ALL
  SELECT 'Phudong' UNION ALL SELECT 'Rangrang' UNION ALL SELECT 'Lingdem' UNION ALL SELECT 'Lingthem' UNION ALL
  SELECT 'Dzongu' UNION ALL SELECT 'Lower Dzongu' UNION ALL SELECT 'Upper Dzongu' UNION ALL SELECT 'Manul' UNION ALL
  SELECT 'Men Rong Rong' UNION ALL SELECT 'Namok' UNION ALL SELECT 'Phamtam' UNION ALL SELECT 'Ramthang' UNION ALL
  SELECT 'Tingchim' UNION ALL SELECT 'Tingvong' UNION ALL SELECT 'Shipgyare' UNION ALL SELECT 'Chungthang' UNION ALL
  SELECT 'Chungthang Bazaar' UNION ALL SELECT 'Lachen' UNION ALL SELECT 'Lachen Bazaar' UNION ALL SELECT 'Lachung' UNION ALL
  SELECT 'Lachung Bazaar' UNION ALL SELECT 'Naga' UNION ALL SELECT 'Sakyong' UNION ALL SELECT 'Passingdong' UNION ALL
  SELECT 'Lingdong' UNION ALL SELECT 'Lingzya' UNION ALL SELECT 'Pentok' UNION ALL SELECT 'Lingdok' UNION ALL
  SELECT 'Toong' UNION ALL SELECT 'Dzongu North' UNION ALL SELECT 'Dzongu South' UNION ALL SELECT 'Sankalang' UNION ALL
  SELECT 'Sangkalan' UNION ALL SELECT 'Kalim' UNION ALL SELECT 'Rongong' UNION ALL SELECT 'Makha' UNION ALL
  SELECT 'Dikchu' UNION ALL SELECT 'Chungthang BAC' UNION ALL SELECT 'Dzongu BAC' UNION ALL SELECT 'Gurudongmar area' UNION ALL
  SELECT 'Hee-Gyathang' UNION ALL SELECT 'Kabi Tingda BAC' UNION ALL SELECT 'Lachen (Gurudongmar Gateway)' UNION ALL SELECT 'Lachen-Mangan area' UNION ALL
  SELECT 'Lachung (Yumthang Valley Gateway)' UNION ALL SELECT 'Mangan BAC' UNION ALL SELECT 'Mangan Town' UNION ALL SELECT 'Meyong' UNION ALL
  SELECT 'Passingdang' UNION ALL SELECT 'Phodong' UNION ALL SELECT 'Phodong Bazaar' UNION ALL SELECT 'Shipgyer' UNION ALL
  SELECT 'Thangu' UNION ALL SELECT 'Yumthang'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 5/6: Pakyong (77 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @sk AND `name` IN ('Pakyong', 'Pakyong District') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Pakyong' AS name UNION ALL SELECT 'Pakyong Bazaar' UNION ALL SELECT 'Pakyong Town' UNION ALL SELECT 'Pakyong Airport' UNION ALL
  SELECT 'Rorathang' UNION ALL SELECT 'Rongli' UNION ALL SELECT 'Rongli Bazaar' UNION ALL SELECT 'Rhenock' UNION ALL
  SELECT 'Rhenock Bazaar' UNION ALL SELECT 'Aritar' UNION ALL SELECT 'Aritar Bazaar' UNION ALL SELECT 'Lampokhari' UNION ALL
  SELECT 'Lampokhari Lake' UNION ALL SELECT 'Sudunglakha' UNION ALL SELECT 'Taza' UNION ALL SELECT 'Amba' UNION ALL
  SELECT 'Tarpin' UNION ALL SELECT 'Chalamthang' UNION ALL SELECT 'Pacheykhani' UNION ALL SELECT 'East Pendam' UNION ALL
  SELECT 'Central Pendam' UNION ALL SELECT 'West Pendam' UNION ALL SELECT 'Pendam' UNION ALL SELECT 'Duga' UNION ALL
  SELECT 'Sumin Lingzey' UNION ALL SELECT 'Budang' UNION ALL SELECT 'Kamerey' UNION ALL SELECT 'Singtam' UNION ALL
  SELECT 'Sirwani' UNION ALL SELECT 'Chisopani' UNION ALL SELECT 'Khamdong' UNION ALL SELECT 'Dung Dung' UNION ALL
  SELECT 'Thasa' UNION ALL SELECT 'Simik Lingzey' UNION ALL SELECT 'Lingzey' UNION ALL SELECT 'Nandok' UNION ALL
  SELECT 'Saramsa' UNION ALL SELECT 'Nandok Bazaar' UNION ALL SELECT 'Rongay' UNION ALL SELECT 'Tathangchen' UNION ALL
  SELECT 'Kopibari' UNION ALL SELECT 'Syari' UNION ALL SELECT 'Parakha' UNION ALL SELECT 'Regu' UNION ALL
  SELECT 'Regu Bazaar' UNION ALL SELECT 'Rangpo' UNION ALL SELECT 'Rangpo Bazaar' UNION ALL SELECT 'Rongchu' UNION ALL
  SELECT 'Rolep' UNION ALL SELECT 'Sankhu' UNION ALL SELECT 'Nathang' UNION ALL SELECT 'Assam Lingzey' UNION ALL
  SELECT 'Chumbong' UNION ALL SELECT 'Dalapchand' UNION ALL SELECT 'Dikchu' UNION ALL SELECT 'Duga BAC' UNION ALL
  SELECT 'Gnathang' UNION ALL SELECT 'Kupup' UNION ALL SELECT 'Linkey' UNION ALL SELECT 'Machong' UNION ALL
  SELECT 'Majhitar (SMIT Area)' UNION ALL SELECT 'Mamring' UNION ALL SELECT 'Namcheybong' UNION ALL SELECT 'Namcheybong BAC' UNION ALL
  SELECT 'Nathang Valley' UNION ALL SELECT 'Pakyong BAC' UNION ALL SELECT 'Pakyong Nagar Panchayat' UNION ALL SELECT 'Pakyong Town (Airport Area)' UNION ALL
  SELECT 'Parkha' UNION ALL SELECT 'Parkha BAC' UNION ALL SELECT 'Ralap' UNION ALL SELECT 'Rangpo Nagar Panchayat' UNION ALL
  SELECT 'Regu BAC' UNION ALL SELECT 'Rhenock BAC' UNION ALL SELECT 'Samardong' UNION ALL SELECT 'Tumin' UNION ALL
  SELECT 'Zuluk'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 6/6: Soreng (78 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @sk AND `name` IN ('Soreng', 'Soreng District') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Soreng' AS name UNION ALL SELECT 'Soreng Bazaar' UNION ALL SELECT 'Soreng Town' UNION ALL SELECT 'Kaluk' UNION ALL
  SELECT 'Kaluk Bazaar' UNION ALL SELECT 'Daramdin' UNION ALL SELECT 'Daramdin Bazaar' UNION ALL SELECT 'Mangalbarey' UNION ALL
  SELECT 'Mangalbarey Bazaar' UNION ALL SELECT 'Nayabazar' UNION ALL SELECT 'Naya Bazar' UNION ALL SELECT 'Sombarey' UNION ALL
  SELECT 'Rinchenpong' UNION ALL SELECT 'Chakung' UNION ALL SELECT 'Reshi' UNION ALL SELECT 'Sangadorji' UNION ALL
  SELECT 'Tadong' UNION ALL SELECT 'Samdong' UNION ALL SELECT 'Sribadam' UNION ALL SELECT 'Bara Samdong' UNION ALL
  SELECT 'Boom Reshi' UNION ALL SELECT 'Deythang' UNION ALL SELECT 'Parengaon' UNION ALL SELECT 'Takuthang' UNION ALL
  SELECT 'Suldung' UNION ALL SELECT 'Kamling' UNION ALL SELECT 'Mabong' UNION ALL SELECT 'Segeng' UNION ALL
  SELECT 'Khaniserbong' UNION ALL SELECT 'Suntaley' UNION ALL SELECT 'Chota Samdong' UNION ALL SELECT 'Arubotey' UNION ALL
  SELECT 'Gelling' UNION ALL SELECT 'Baiguney' UNION ALL SELECT 'Samsing' UNION ALL SELECT 'Pipaley' UNION ALL
  SELECT 'Mendogaon' UNION ALL SELECT 'Barbotey' UNION ALL SELECT 'Chumbong' UNION ALL SELECT 'Zoom' UNION ALL
  SELECT 'Malbasey' UNION ALL SELECT 'Bhudang' UNION ALL SELECT 'Mangsari' UNION ALL SELECT 'Mangarjung' UNION ALL
  SELECT 'Singling' UNION ALL SELECT 'Lower Timberbong' UNION ALL SELECT 'Upper Timberbong' UNION ALL SELECT 'Tharpu' UNION ALL
  SELECT 'Karthok' UNION ALL SELECT 'Bojek' UNION ALL SELECT 'Dodak' UNION ALL SELECT 'Burikhop' UNION ALL
  SELECT 'Rumbuk' UNION ALL SELECT 'Upper Fambong' UNION ALL SELECT 'Lower Fambong' UNION ALL SELECT 'Lungchok' UNION ALL
  SELECT 'Salyangdang' UNION ALL SELECT 'Siktam' UNION ALL SELECT 'Tikpur' UNION ALL SELECT 'Okhrey' UNION ALL
  SELECT 'Sapreynagi' UNION ALL SELECT 'Ribdi' UNION ALL SELECT 'Bhareng' UNION ALL SELECT 'Baiguney BAC' UNION ALL
  SELECT 'Chumbung' UNION ALL SELECT 'Chumbung-Chakung BAC' UNION ALL SELECT 'Daramdin BAC' UNION ALL SELECT 'Dentam' UNION ALL
  SELECT 'Hee' UNION ALL SELECT 'Jorethang' UNION ALL SELECT 'Jorethang Road' UNION ALL SELECT 'Lower Timburbong' UNION ALL
  SELECT 'Mangurjung' UNION ALL SELECT 'Sombaria' UNION ALL SELECT 'Soreng BAC' UNION ALL SELECT 'Soreng Nagar Panchayat' UNION ALL
  SELECT 'Timburbong' UNION ALL SELECT 'Upper Timburbong'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));
