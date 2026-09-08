-- ==============================================================================
-- ARUNACHAL PRADESH: ALL 28 DISTRICTS & 556 LOCALITIES MASTER SQL QUERY
-- Covers all 28 district-level administrative units and Capital Complex
-- Idempotent (Safe to run multiple times without creating duplicates)
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- ==============================================================================

-- 1. Ensure State 'Arunachal Pradesh' (AR)
INSERT INTO `states` (`code`, `name`)
SELECT 'AR', 'Arunachal Pradesh' WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'AR' OR `name` = 'Arunachal Pradesh');

SET @ar = (SELECT `id` FROM `states` WHERE `code` = 'AR' LIMIT 1);

-- 2. Ensure All 28 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT @ar, d.name FROM (
  SELECT 'Anjaw' AS name UNION ALL SELECT 'Bichom' UNION ALL SELECT 'Changlang' UNION ALL SELECT 'Dibang Valley' UNION ALL SELECT 'East Kameng' UNION ALL SELECT 'East Siang' UNION ALL SELECT 'Itanagar Capital Complex' UNION ALL SELECT 'Kamle' UNION ALL SELECT 'Keyi Panyor' UNION ALL SELECT 'Kra Daadi' UNION ALL SELECT 'Kurung Kumey' UNION ALL SELECT 'Lepa Rada' UNION ALL SELECT 'Lohit' UNION ALL SELECT 'Longding' UNION ALL SELECT 'Lower Dibang Valley' UNION ALL SELECT 'Lower Siang' UNION ALL SELECT 'Lower Subansiri' UNION ALL SELECT 'Namsai' UNION ALL SELECT 'Pakke-Kessang' UNION ALL SELECT 'Papum Pare' UNION ALL SELECT 'Shi Yomi' UNION ALL SELECT 'Siang' UNION ALL SELECT 'Tawang' UNION ALL SELECT 'Tirap' UNION ALL SELECT 'Upper Siang' UNION ALL SELECT 'Upper Subansiri' UNION ALL SELECT 'West Kameng' UNION ALL SELECT 'West Siang'
) d WHERE NOT EXISTS (
  SELECT 1 FROM `districts` WHERE `state_id` = @ar AND (
    LOWER(`name`) = LOWER(d.name) OR
    (d.name = 'Pakke-Kessang' AND LOWER(`name`) = 'pakke kessang')
  )
);

-- 3. Insert Localities for All 28 Districts

-- District 1/28: Anjaw (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Anjaw' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Hawai' AS name UNION ALL SELECT 'Khawzar' UNION ALL SELECT 'Tezu Road' UNION ALL SELECT 'Hayuliang' UNION ALL
  SELECT 'Chaglohagam' UNION ALL SELECT 'Kibithoo' UNION ALL SELECT 'Walong' UNION ALL SELECT 'Dong' UNION ALL
  SELECT 'Metengliang' UNION ALL SELECT 'Manchal' UNION ALL SELECT 'Goiliang' UNION ALL SELECT 'Khawbri' UNION ALL
  SELECT 'Demwe' UNION ALL SELECT 'Chaglohagam Circle' UNION ALL SELECT 'Walong Circle' UNION ALL SELECT 'Kibithoo Circle' UNION ALL
  SELECT 'Hayuliang Khupa' UNION ALL SELECT 'Tidding' UNION ALL SELECT 'Kibithu (Easternmost Point)'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 2/28: Bichom (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Bichom' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Napangphung' AS name UNION ALL SELECT 'Bana' UNION ALL SELECT 'Lada' UNION ALL SELECT 'Nafra' UNION ALL
  SELECT 'Nafra Circle' UNION ALL SELECT 'Bichom' UNION ALL SELECT 'Khenewa' UNION ALL SELECT 'Bhalukpong Road' UNION ALL
  SELECT 'Kaspi' UNION ALL SELECT 'Tenga Valley' UNION ALL SELECT 'Shergaon Road' UNION ALL SELECT 'Rupa Road' UNION ALL
  SELECT 'Chakku' UNION ALL SELECT 'Chindit' UNION ALL SELECT 'Ramalingpam' UNION ALL SELECT 'Dahung' UNION ALL
  SELECT 'Mago' UNION ALL SELECT 'Sachidal' UNION ALL SELECT 'Namfri'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 3/28: Changlang (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Changlang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Changlang' AS name UNION ALL SELECT 'Changlang Town' UNION ALL SELECT 'Jairampur' UNION ALL SELECT 'Miao' UNION ALL
  SELECT 'Bordumsa' UNION ALL SELECT 'Kharsang' UNION ALL SELECT 'Nampong' UNION ALL SELECT 'Vijoynagar' UNION ALL
  SELECT 'Jairampur Bazaar' UNION ALL SELECT 'Tikhak' UNION ALL SELECT 'Manmao' UNION ALL SELECT 'Kantang' UNION ALL
  SELECT 'Yatdam' UNION ALL SELECT 'Jongkey' UNION ALL SELECT 'Innao' UNION ALL SELECT 'Khimyang' UNION ALL
  SELECT 'Khela' UNION ALL SELECT 'Lyngok' UNION ALL SELECT 'Namtok' UNION ALL SELECT 'Ranglum' UNION ALL
  SELECT 'Diyun' UNION ALL SELECT 'Namdapha Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 4/28: Dibang Valley (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Dibang Valley' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Anini' AS name UNION ALL SELECT 'Anini Town' UNION ALL SELECT 'Anelih' UNION ALL SELECT 'Etalin' UNION ALL
  SELECT 'Etalin Road' UNION ALL SELECT 'Dambuk Road' UNION ALL SELECT 'Mipi' UNION ALL SELECT 'Mouling' UNION ALL
  SELECT 'Malinye' UNION ALL SELECT 'Arzoo' UNION ALL SELECT 'Koronu' UNION ALL SELECT 'Emeo' UNION ALL
  SELECT 'Dre' UNION ALL SELECT 'Dree' UNION ALL SELECT 'Acheso' UNION ALL SELECT 'Anpum' UNION ALL
  SELECT 'Hunli' UNION ALL SELECT 'Iduli' UNION ALL SELECT 'Kebali' UNION ALL SELECT 'Kronli' UNION ALL
  SELECT 'Mishmi Hills'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 5/28: East Kameng (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'East Kameng' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Seppa' AS name UNION ALL SELECT 'Seppa Town' UNION ALL SELECT 'Chayang Tajo' UNION ALL SELECT 'Bameng' UNION ALL
  SELECT 'Pakoti' UNION ALL SELECT 'Lada' UNION ALL SELECT 'Khenewa' UNION ALL SELECT 'Sawa' UNION ALL
  SELECT 'Richukrong' UNION ALL SELECT 'Papu Valley' UNION ALL SELECT 'Pakte' UNION ALL SELECT 'Pake Kessang Road' UNION ALL
  SELECT 'Bameng Circle' UNION ALL SELECT 'Chayang Tajo Circle' UNION ALL SELECT 'Seppa Bazaar' UNION ALL SELECT 'Lumdung' UNION ALL
  SELECT 'Panchin' UNION ALL SELECT 'Sangdupota' UNION ALL SELECT 'Rani' UNION ALL SELECT 'Bana' UNION ALL
  SELECT 'Pipu' UNION ALL SELECT 'Pakke Kessang Border'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 6/28: East Siang (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'East Siang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Pasighat' AS name UNION ALL SELECT 'Pasighat Town' UNION ALL SELECT 'Ruksin' UNION ALL SELECT 'Mebo' UNION ALL
  SELECT 'Bilat' UNION ALL SELECT 'Kelek' UNION ALL SELECT 'Sille' UNION ALL SELECT 'Oyan' UNION ALL
  SELECT 'Rani' UNION ALL SELECT 'Sibo Korong' UNION ALL SELECT 'Geku' UNION ALL SELECT 'Mer' UNION ALL
  SELECT 'Jengging Road' UNION ALL SELECT 'Seren' UNION ALL SELECT 'Mirem' UNION ALL SELECT 'Nari' UNION ALL
  SELECT 'Borguli' UNION ALL SELECT 'Komsing' UNION ALL SELECT 'Kongkul' UNION ALL SELECT 'Pangin' UNION ALL
  SELECT 'Koyu'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 7/28: Itanagar Capital Complex (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Itanagar Capital Complex' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Itanagar' AS name UNION ALL SELECT 'Naharlagun' UNION ALL SELECT 'Nirjuli' UNION ALL SELECT 'Banderdewa' UNION ALL
  SELECT 'Chandan Nagar' UNION ALL SELECT 'Ganga' UNION ALL SELECT 'Ganga Market' UNION ALL SELECT 'Itanagar Market' UNION ALL
  SELECT 'Tinali' UNION ALL SELECT 'Bank Tinali' UNION ALL SELECT 'P Sector' UNION ALL SELECT 'C Sector' UNION ALL
  SELECT 'D Sector' UNION ALL SELECT 'E Sector' UNION ALL SELECT 'G Sector' UNION ALL SELECT 'Gohpur' UNION ALL
  SELECT 'H Sector' UNION ALL SELECT 'J Sector' UNION ALL SELECT 'K Sector' UNION ALL SELECT 'Legi' UNION ALL
  SELECT 'Lekhi' UNION ALL SELECT 'Papu Nallah' UNION ALL SELECT 'Chimpu' UNION ALL SELECT 'Vivek Vihar' UNION ALL
  SELECT 'Zoo Road' UNION ALL SELECT 'Jollang'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 8/28: Kamle (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Kamle' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Raga' AS name UNION ALL SELECT 'Raga Town' UNION ALL SELECT 'Daporijo Road' UNION ALL SELECT 'Mipyong' UNION ALL
  SELECT 'Kamporijo' UNION ALL SELECT 'Payeng' UNION ALL SELECT 'Dollungmukh' UNION ALL SELECT 'Gepen' UNION ALL
  SELECT 'Kherbari' UNION ALL SELECT 'Karak' UNION ALL SELECT 'Puchi Geko' UNION ALL SELECT 'Raga Circle' UNION ALL
  SELECT 'Mengi' UNION ALL SELECT 'Sikod' UNION ALL SELECT 'Talo' UNION ALL SELECT 'Panya' UNION ALL
  SELECT 'Kodo' UNION ALL SELECT 'Subansiri Valley' UNION ALL SELECT 'Giba'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 9/28: Keyi Panyor (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Keyi Panyor' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Yachuli' AS name UNION ALL SELECT 'Yachuli Town' UNION ALL SELECT 'Yazali' UNION ALL SELECT 'Yazali Town' UNION ALL
  SELECT 'Pistana' UNION ALL SELECT 'Deed' UNION ALL SELECT 'Panyor' UNION ALL SELECT 'Talo' UNION ALL
  SELECT 'Taba' UNION ALL SELECT 'Param Pahar' UNION ALL SELECT 'Hari' UNION ALL SELECT 'Sakiang' UNION ALL
  SELECT 'Dolungmukh' UNION ALL SELECT 'Pania' UNION ALL SELECT 'Hija' UNION ALL SELECT 'Potin' UNION ALL
  SELECT 'Raga Road' UNION ALL SELECT 'Old Yachuli' UNION ALL SELECT 'New Yachuli'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 10/28: Kra Daadi (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Kra Daadi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jamin' AS name UNION ALL SELECT 'Jamin Circle' UNION ALL SELECT 'Palin' UNION ALL SELECT 'Palin Town' UNION ALL
  SELECT 'Chambang' UNION ALL SELECT 'Tali' UNION ALL SELECT 'Tarak' UNION ALL SELECT 'Lengdi' UNION ALL
  SELECT 'Gangte' UNION ALL SELECT 'Pate' UNION ALL SELECT 'Tarak Pania' UNION ALL SELECT 'Yangte' UNION ALL
  SELECT 'Sangram' UNION ALL SELECT 'Pake' UNION ALL SELECT 'Pipsorang' UNION ALL SELECT 'Talo' UNION ALL
  SELECT 'Chambang Circle' UNION ALL SELECT 'Palin Circle' UNION ALL SELECT 'Tali Circle'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 11/28: Kurung Kumey (16 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Kurung Kumey' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Koloriang' AS name UNION ALL SELECT 'Koloriang Town' UNION ALL SELECT 'Nyapin' UNION ALL SELECT 'Damin' UNION ALL
  SELECT 'Parsi Parlo' UNION ALL SELECT 'Sangram' UNION ALL SELECT 'Sarli' UNION ALL SELECT 'Pania' UNION ALL
  SELECT 'Tarak Langdi' UNION ALL SELECT 'Chambang Road' UNION ALL SELECT 'Raga Road' UNION ALL SELECT 'Damin Circle' UNION ALL
  SELECT 'Nyapin Circle' UNION ALL SELECT 'Parsi Parlo Circle' UNION ALL SELECT 'Sarli Circle' UNION ALL SELECT 'Koloriang Bazaar'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 12/28: Lepa Rada (17 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Lepa Rada' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Basar' AS name UNION ALL SELECT 'Basar Town' UNION ALL SELECT 'Tirbin' UNION ALL SELECT 'Daring' UNION ALL
  SELECT 'Sago' UNION ALL SELECT 'Bame' UNION ALL SELECT 'Gensi' UNION ALL SELECT 'Yeggo' UNION ALL
  SELECT 'Likha' UNION ALL SELECT 'Gella' UNION ALL SELECT 'Payum' UNION ALL SELECT 'Tadin' UNION ALL
  SELECT 'Tirbin Circle' UNION ALL SELECT 'Daring Circle' UNION ALL SELECT 'Basar Circle' UNION ALL SELECT 'Sago Circle' UNION ALL
  SELECT 'Gensi Galo Hills'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 13/28: Lohit (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Lohit' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Tezu' AS name UNION ALL SELECT 'Tezu Town' UNION ALL SELECT 'Namsai Road' UNION ALL SELECT 'Wakro' UNION ALL
  SELECT 'Sunpura' UNION ALL SELECT 'Chongkham' UNION ALL SELECT 'Anjaw Road' UNION ALL SELECT 'Khawlik' UNION ALL
  SELECT 'Demwe' UNION ALL SELECT 'Sadiya' UNION ALL SELECT 'Lekang' UNION ALL SELECT 'Bordumsa Road' UNION ALL
  SELECT 'Parshuram Kund' UNION ALL SELECT 'Mahadevpur' UNION ALL SELECT 'Telluliang' UNION ALL SELECT 'Hayuliang Road' UNION ALL
  SELECT 'Tezu Bazaar' UNION ALL SELECT 'Wakro (Parasuram Kund Area)'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 14/28: Longding (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Longding' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Longding' AS name UNION ALL SELECT 'Longding Town' UNION ALL SELECT 'Kanubari' UNION ALL SELECT 'Pongchau' UNION ALL
  SELECT 'Lawnu' UNION ALL SELECT 'Turet' UNION ALL SELECT 'Wakka' UNION ALL SELECT 'Niausa' UNION ALL
  SELECT 'Namsang' UNION ALL SELECT 'Longo' UNION ALL SELECT 'Chubam' UNION ALL SELECT 'Khonsa Road' UNION ALL
  SELECT 'Tewai' UNION ALL SELECT 'Raho' UNION ALL SELECT 'Dadung' UNION ALL SELECT 'Pangchao' UNION ALL
  SELECT 'Konsa' UNION ALL SELECT 'Kamhua' UNION ALL SELECT 'Noknu'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 15/28: Lower Dibang Valley (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Lower Dibang Valley' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Roing' AS name UNION ALL SELECT 'Roing Town' UNION ALL SELECT 'Dambuk' UNION ALL SELECT 'Meka' UNION ALL
  SELECT 'Koronu' UNION ALL SELECT 'Tinali' UNION ALL SELECT 'Hunli Road' UNION ALL SELECT 'Desali' UNION ALL
  SELECT 'Kebali' UNION ALL SELECT 'Aohali' UNION ALL SELECT 'Paglam' UNION ALL SELECT 'Anpum' UNION ALL
  SELECT 'Iduli' UNION ALL SELECT 'Dambuk Road' UNION ALL SELECT 'Mayudia' UNION ALL SELECT 'Bolung' UNION ALL
  SELECT 'New Anpum' UNION ALL SELECT 'Roing Bazaar' UNION ALL SELECT 'Dambuk (Orange Festival Area)' UNION ALL SELECT 'Hunli'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 16/28: Lower Siang (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Lower Siang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Likabali' AS name UNION ALL SELECT 'Likabali Town' UNION ALL SELECT 'Kangku' UNION ALL SELECT 'Nari' UNION ALL
  SELECT 'Koyu' UNION ALL SELECT 'Gensi' UNION ALL SELECT 'Kora' UNION ALL SELECT 'Sipu' UNION ALL
  SELECT 'Kadmoli' UNION ALL SELECT 'Dipa' UNION ALL SELECT 'Geku' UNION ALL SELECT 'Kebang' UNION ALL
  SELECT 'Koyu Sissen' UNION ALL SELECT 'Joram' UNION ALL SELECT 'Bopi' UNION ALL SELECT 'Morang' UNION ALL
  SELECT 'Likha' UNION ALL SELECT 'Rottung'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 17/28: Lower Subansiri (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Lower Subansiri' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Ziro' AS name UNION ALL SELECT 'Ziro Town' UNION ALL SELECT 'Old Ziro' UNION ALL SELECT 'Hapoli' UNION ALL
  SELECT 'New Ziro' UNION ALL SELECT 'Hari' UNION ALL SELECT 'Hong' UNION ALL SELECT 'Hija' UNION ALL
  SELECT 'Bulla' UNION ALL SELECT 'Daporijo Road' UNION ALL SELECT 'Yachuli Road' UNION ALL SELECT 'Talo' UNION ALL
  SELECT 'Tajang' UNION ALL SELECT 'Panya' UNION ALL SELECT 'Siiro' UNION ALL SELECT 'Sibe' UNION ALL
  SELECT 'Bamin' UNION ALL SELECT 'Michi' UNION ALL SELECT 'Michi Reru' UNION ALL SELECT 'Yachuli' UNION ALL
  SELECT 'Ziro (UNESCO Tentative / Valley)' UNION ALL SELECT 'Raga Border'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 18/28: Namsai (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Namsai' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Namsai' AS name UNION ALL SELECT 'Namsai Town' UNION ALL SELECT 'Chongkham' UNION ALL SELECT 'Lathao' UNION ALL
  SELECT 'Lekang' UNION ALL SELECT 'Mahadevpur' UNION ALL SELECT 'Wakro Road' UNION ALL SELECT 'Meyong' UNION ALL
  SELECT 'Khamdu' UNION ALL SELECT 'Kherbari' UNION ALL SELECT 'Namsai Bazaar' UNION ALL SELECT 'Lathao Circle' UNION ALL
  SELECT 'Chongkham Circle' UNION ALL SELECT 'Mauw' UNION ALL SELECT 'Joram' UNION ALL SELECT 'Tengapani' UNION ALL
  SELECT 'Golden Pagoda Area' UNION ALL SELECT 'Chowkham'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 19/28: Pakke-Kessang (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` IN ('Pakke-Kessang', 'Pakke Kessang') LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Lemmi' AS name UNION ALL SELECT 'Lemmi Town' UNION ALL SELECT 'Seijosa' UNION ALL SELECT 'Pakke Kessang' UNION ALL
  SELECT 'Pizirang' UNION ALL SELECT 'Dissing Passo' UNION ALL SELECT 'Khenewa' UNION ALL SELECT 'Sawa' UNION ALL
  SELECT 'Tale Valley' UNION ALL SELECT 'Passo' UNION ALL SELECT 'Pakoti' UNION ALL SELECT 'Bhalukpong Road' UNION ALL
  SELECT 'Kallek' UNION ALL SELECT 'Pake Kessang' UNION ALL SELECT 'Seijosa Circle' UNION ALL SELECT 'Dissing-Passo Circle' UNION ALL
  SELECT 'Pakke Kessang Town' UNION ALL SELECT 'Pijirang'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 20/28: Papum Pare (23 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Papum Pare' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Yupia' AS name UNION ALL SELECT 'Doimukh' UNION ALL SELECT 'Kimin' UNION ALL SELECT 'Sagalee' UNION ALL
  SELECT 'Balijan' UNION ALL SELECT 'Naharlagun Road' UNION ALL SELECT 'Banderdewa' UNION ALL SELECT 'Hoj' UNION ALL
  SELECT 'Kakoi' UNION ALL SELECT 'Kimin Circle' UNION ALL SELECT 'Sagalee Town' UNION ALL SELECT 'Mengha' UNION ALL
  SELECT 'Parang' UNION ALL SELECT 'Sangdupota' UNION ALL SELECT 'Toru' UNION ALL SELECT 'Seijosa Road' UNION ALL
  SELECT 'Hapoli Road' UNION ALL SELECT 'Joram' UNION ALL SELECT 'Doimukh Bazaar' UNION ALL SELECT 'Itanagar (State Capital)' UNION ALL
  SELECT 'Naharlagun' UNION ALL SELECT 'Nirjuli (NERIST Area)' UNION ALL SELECT 'Mengio'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 21/28: Shi Yomi (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Shi Yomi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Tato' AS name UNION ALL SELECT 'Mechuka' UNION ALL SELECT 'Mechuka Town' UNION ALL SELECT 'Manigaon' UNION ALL
  SELECT 'Pidi' UNION ALL SELECT 'Monigong' UNION ALL SELECT 'Geling' UNION ALL SELECT 'Taliha Road' UNION ALL
  SELECT 'Yemsing' UNION ALL SELECT 'Darak' UNION ALL SELECT 'Kaying Road' UNION ALL SELECT 'Tato Circle' UNION ALL
  SELECT 'Mechuka Circle' UNION ALL SELECT 'Pidi Circle' UNION ALL SELECT 'Monigong Circle' UNION ALL SELECT 'Gelling' UNION ALL
  SELECT 'Karko' UNION ALL SELECT 'Dorjeeling' UNION ALL SELECT 'Mechuka (Menchuka Valley)'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 22/28: Siang (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Siang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Boleng' AS name UNION ALL SELECT 'Boleng Town' UNION ALL SELECT 'Pangin' UNION ALL SELECT 'Rebo' UNION ALL
  SELECT 'Panging' UNION ALL SELECT 'Kaying' UNION ALL SELECT 'Jengging' UNION ALL SELECT 'Riga' UNION ALL
  SELECT 'Bunung' UNION ALL SELECT 'Pangin Road' UNION ALL SELECT 'Sissen' UNION ALL SELECT 'Borguli' UNION ALL
  SELECT 'Bishing' UNION ALL SELECT 'Yemsing' UNION ALL SELECT 'Komsing' UNION ALL SELECT 'Sille' UNION ALL
  SELECT 'Geku' UNION ALL SELECT 'Rottung' UNION ALL SELECT 'Balinong' UNION ALL SELECT 'Rumgong'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 23/28: Tawang (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Tawang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Tawang' AS name UNION ALL SELECT 'Tawang Town' UNION ALL SELECT 'Old Tawang' UNION ALL SELECT 'New Tawang' UNION ALL
  SELECT 'Lumla' UNION ALL SELECT 'Jang' UNION ALL SELECT 'Thingbu' UNION ALL SELECT 'Bongleng' UNION ALL
  SELECT 'Mukto' UNION ALL SELECT 'Zemithang' UNION ALL SELECT 'Dirang Road' UNION ALL SELECT 'Sela Pass' UNION ALL
  SELECT 'Bum La' UNION ALL SELECT 'Shonga-Tser' UNION ALL SELECT 'Kitpi' UNION ALL SELECT 'Urgelling' UNION ALL
  SELECT 'Dudunghar' UNION ALL SELECT 'Sakpret' UNION ALL SELECT 'Mago' UNION ALL SELECT 'Nuranang' UNION ALL
  SELECT 'Jangchub' UNION ALL SELECT 'Tawang Monastery Area'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 24/28: Tirap (19 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Tirap' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Khonsa' AS name UNION ALL SELECT 'Khonsa Town' UNION ALL SELECT 'Deomali' UNION ALL SELECT 'Namsang' UNION ALL
  SELECT 'Borduria' UNION ALL SELECT 'Dadang' UNION ALL SELECT 'Lazu' UNION ALL SELECT 'Dadam' UNION ALL
  SELECT 'Soha' UNION ALL SELECT 'Noitong' UNION ALL SELECT 'Kheti' UNION ALL SELECT 'Khela' UNION ALL
  SELECT 'Chara' UNION ALL SELECT 'Tirap Bazaar' UNION ALL SELECT 'Deomali Bazaar' UNION ALL SELECT 'Lazu Circle' UNION ALL
  SELECT 'Dadeng' UNION ALL SELECT 'Namsang Circle' UNION ALL SELECT 'Laju'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 25/28: Upper Siang (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Upper Siang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Yingkiong' AS name UNION ALL SELECT 'Yingkiong Town' UNION ALL SELECT 'Jengging' UNION ALL SELECT 'Geku' UNION ALL
  SELECT 'Mariyang' UNION ALL SELECT 'Gelling' UNION ALL SELECT 'Mopom' UNION ALL SELECT 'Tuting' UNION ALL
  SELECT 'Migging' UNION ALL SELECT 'Kaying' UNION ALL SELECT 'Singa' UNION ALL SELECT 'Damro' UNION ALL
  SELECT 'Palling' UNION ALL SELECT 'Bishing' UNION ALL SELECT 'Pugging' UNION ALL SELECT 'Katan' UNION ALL
  SELECT 'Bolang' UNION ALL SELECT 'Yemsing'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 26/28: Upper Subansiri (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'Upper Subansiri' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Daporijo' AS name UNION ALL SELECT 'Daporijo Town' UNION ALL SELECT 'Dumporijo' UNION ALL SELECT 'Nacho' UNION ALL
  SELECT 'Taliha' UNION ALL SELECT 'Giba' UNION ALL SELECT 'Menga' UNION ALL SELECT 'Siyum' UNION ALL
  SELECT 'Taksing' UNION ALL SELECT 'Limeking' UNION ALL SELECT 'Sarli' UNION ALL SELECT 'Chetu' UNION ALL
  SELECT 'Marala' UNION ALL SELECT 'Muri' UNION ALL SELECT 'Sangram' UNION ALL SELECT 'Nacho Circle' UNION ALL
  SELECT 'Taliha Circle' UNION ALL SELECT 'Taksing Circle' UNION ALL SELECT 'Raga Border' UNION ALL SELECT 'Baririjo'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 27/28: West Kameng (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'West Kameng' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bomdila' AS name UNION ALL SELECT 'Bomdila Town' UNION ALL SELECT 'Dirang' UNION ALL SELECT 'Bhalukpong' UNION ALL
  SELECT 'Tawang Road' UNION ALL SELECT 'Rupa' UNION ALL SELECT 'Balemu' UNION ALL SELECT 'Shergaon' UNION ALL
  SELECT 'Kalaktang' UNION ALL SELECT 'Singchung' UNION ALL SELECT 'Tenga' UNION ALL SELECT 'Tipi' UNION ALL
  SELECT 'Jamiri' UNION ALL SELECT 'Sessa' UNION ALL SELECT 'Thembang' UNION ALL SELECT 'Chander' UNION ALL
  SELECT 'Nafra Road' UNION ALL SELECT 'Bomdila Bazaar' UNION ALL SELECT 'Dirang (Valley)' UNION ALL SELECT 'Tenga Valley'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District 28/28: West Siang (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ar AND `name` = 'West Siang' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Aalo' AS name UNION ALL SELECT 'Aalo Town' UNION ALL SELECT 'Along' UNION ALL SELECT 'Basar Road' UNION ALL
  SELECT 'Kamba' UNION ALL SELECT 'Likabali' UNION ALL SELECT 'Mechuka Road' UNION ALL SELECT 'Bagra' UNION ALL
  SELECT 'Darak' UNION ALL SELECT 'Daring' UNION ALL SELECT 'Kaying' UNION ALL SELECT 'Payum' UNION ALL
  SELECT 'Bame' UNION ALL SELECT 'Tirbin' UNION ALL SELECT 'Sibe' UNION ALL SELECT 'Yeggo' UNION ALL
  SELECT 'Liromoba' UNION ALL SELECT 'Monigong Road' UNION ALL SELECT 'Bolang' UNION ALL SELECT 'Aalo (Along)' UNION ALL
  SELECT 'Yomcha' UNION ALL SELECT 'Basar Border'
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));
