-- ==========================================================================
-- UnlockRentals - Complete Assam Districts & Localities Setup
-- Covers all 35 Districts and 1021 Localities
-- ==========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure State Exists
INSERT INTO `states` (`name`, `code`)
SELECT 'Assam', 'AS'
WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'AS' OR `name` = 'Assam');

-- 2. Ensure Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT s.id, d.name
FROM `states` s
CROSS JOIN (
    SELECT 'Bajali' AS name UNION ALL
    SELECT 'Baksa' AS name UNION ALL
    SELECT 'Barpeta' AS name UNION ALL
    SELECT 'Biswanath' AS name UNION ALL
    SELECT 'Bongaigaon' AS name UNION ALL
    SELECT 'Cachar' AS name UNION ALL
    SELECT 'Charaideo' AS name UNION ALL
    SELECT 'Chirang' AS name UNION ALL
    SELECT 'Darrang' AS name UNION ALL
    SELECT 'Dhemaji' AS name UNION ALL
    SELECT 'Dhubri' AS name UNION ALL
    SELECT 'Dibrugarh' AS name UNION ALL
    SELECT 'Dima Hasao' AS name UNION ALL
    SELECT 'Goalpara' AS name UNION ALL
    SELECT 'Golaghat' AS name UNION ALL
    SELECT 'Hailakandi' AS name UNION ALL
    SELECT 'Hojai' AS name UNION ALL
    SELECT 'Jorhat' AS name UNION ALL
    SELECT 'Kamrup' AS name UNION ALL
    SELECT 'Kamrup Metropolitan' AS name UNION ALL
    SELECT 'Karbi Anglong' AS name UNION ALL
    SELECT 'Karimganj' AS name UNION ALL
    SELECT 'Kokrajhar' AS name UNION ALL
    SELECT 'Lakhimpur' AS name UNION ALL
    SELECT 'Majuli' AS name UNION ALL
    SELECT 'Morigaon' AS name UNION ALL
    SELECT 'Nagaon' AS name UNION ALL
    SELECT 'Nalbari' AS name UNION ALL
    SELECT 'Sivasagar' AS name UNION ALL
    SELECT 'Sonitpur' AS name UNION ALL
    SELECT 'South Salmara-Mankachar' AS name UNION ALL
    SELECT 'Tamulpur' AS name UNION ALL
    SELECT 'Tinsukia' AS name UNION ALL
    SELECT 'Udalguri' AS name UNION ALL
    SELECT 'West Karbi Anglong' AS name
) d
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND NOT EXISTS (
      SELECT 1 FROM `districts` existing 
      WHERE existing.state_id = s.id AND existing.name = d.name
  );

-- 3. Insert Localities District-by-District
-- --------------------------------------------------------------------------
-- District: Bajali (24 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baghbor Road Area' AS name UNION ALL
    SELECT 'Baghmara' AS name UNION ALL
    SELECT 'Bajali' AS name UNION ALL
    SELECT 'Bamunbari' AS name UNION ALL
    SELECT 'Barkshetri-side areas' AS name UNION ALL
    SELECT 'Bhabanipur' AS name UNION ALL
    SELECT 'Bhawanipur' AS name UNION ALL
    SELECT 'Charia' AS name UNION ALL
    SELECT 'Dhamdhama' AS name UNION ALL
    SELECT 'Howly Road Area' AS name UNION ALL
    SELECT 'Jania-side areas' AS name UNION ALL
    SELECT 'Kayakuchi' AS name UNION ALL
    SELECT 'Mandia Road Area' AS name UNION ALL
    SELECT 'Medhikuchi' AS name UNION ALL
    SELECT 'Nityananda' AS name UNION ALL
    SELECT 'Pahumara' AS name UNION ALL
    SELECT 'Patacharkuchi' AS name UNION ALL
    SELECT 'Pathsala' AS name UNION ALL
    SELECT 'Pathsala Town' AS name UNION ALL
    SELECT 'Pub Bajali' AS name UNION ALL
    SELECT 'Sarthebari' AS name UNION ALL
    SELECT 'Sarupeta' AS name UNION ALL
    SELECT 'Sila' AS name UNION ALL
    SELECT 'Uttar Bajali' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Bajali'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Baksa (24 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baganpara' AS name UNION ALL
    SELECT 'Baganpara Chowk' AS name UNION ALL
    SELECT 'Barama' AS name UNION ALL
    SELECT 'Barama Road Area' AS name UNION ALL
    SELECT 'Bhairabkunda-side areas' AS name UNION ALL
    SELECT 'Bhuyapara' AS name UNION ALL
    SELECT 'Dhamdhama-side area' AS name UNION ALL
    SELECT 'Dihira' AS name UNION ALL
    SELECT 'Gabardhana' AS name UNION ALL
    SELECT 'Goreswar' AS name UNION ALL
    SELECT 'Jalah' AS name UNION ALL
    SELECT 'Kachubari' AS name UNION ALL
    SELECT 'Kharma' AS name UNION ALL
    SELECT 'Kumarikata' AS name UNION ALL
    SELECT 'Mathanguri' AS name UNION ALL
    SELECT 'Mushalpur' AS name UNION ALL
    SELECT 'Nagrijuli' AS name UNION ALL
    SELECT 'Naharpara' AS name UNION ALL
    SELECT 'Salbari' AS name UNION ALL
    SELECT 'Simla' AS name UNION ALL
    SELECT 'Suklai' AS name UNION ALL
    SELECT 'Tamulpur Border' AS name UNION ALL
    SELECT 'Tamulpur-side area' AS name UNION ALL
    SELECT 'Tihu Road Area' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Baksa'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Barpeta (32 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baghbar' AS name UNION ALL
    SELECT 'Bahari' AS name UNION ALL
    SELECT 'Barpeta' AS name UNION ALL
    SELECT 'Barpeta Road' AS name UNION ALL
    SELECT 'Barpeta Road Railway Area' AS name UNION ALL
    SELECT 'Barpeta Town' AS name UNION ALL
    SELECT 'Barpeta Town (Satras)' AS name UNION ALL
    SELECT 'Bhawanipur' AS name UNION ALL
    SELECT 'Bhella' AS name UNION ALL
    SELECT 'Bohori' AS name UNION ALL
    SELECT 'Chakchaka' AS name UNION ALL
    SELECT 'Chenga' AS name UNION ALL
    SELECT 'Chenga Road' AS name UNION ALL
    SELECT 'Doulashal' AS name UNION ALL
    SELECT 'Ganakkuchi' AS name UNION ALL
    SELECT 'Gobardhana' AS name UNION ALL
    SELECT 'Howly' AS name UNION ALL
    SELECT 'Howly Town' AS name UNION ALL
    SELECT 'Jania' AS name UNION ALL
    SELECT 'Kalgachia' AS name UNION ALL
    SELECT 'Kalgachia Town' AS name UNION ALL
    SELECT 'Kayakuchi' AS name UNION ALL
    SELECT 'Mandia' AS name UNION ALL
    SELECT 'Medhikuchi' AS name UNION ALL
    SELECT 'Paka Betbari' AS name UNION ALL
    SELECT 'Pathsala Road Area' AS name UNION ALL
    SELECT 'Rupshi' AS name UNION ALL
    SELECT 'Sarthebari' AS name UNION ALL
    SELECT 'Sarthebari (Bell Metal)' AS name UNION ALL
    SELECT 'Satra Nagari' AS name UNION ALL
    SELECT 'Sorbhog' AS name UNION ALL
    SELECT 'Sorbhog Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Barpeta'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Biswanath (26 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bagmara' AS name UNION ALL
    SELECT 'Balipara-side area' AS name UNION ALL
    SELECT 'Behali' AS name UNION ALL
    SELECT 'Behali Road Area' AS name UNION ALL
    SELECT 'Bihpuria Road Area' AS name UNION ALL
    SELECT 'Biswanath Chariali' AS name UNION ALL
    SELECT 'Biswanath Town' AS name UNION ALL
    SELECT 'Borgang' AS name UNION ALL
    SELECT 'Borgang Market' AS name UNION ALL
    SELECT 'Chaiduar' AS name UNION ALL
    SELECT 'Chariali Town' AS name UNION ALL
    SELECT 'Gohpur' AS name UNION ALL
    SELECT 'Gohpur Town' AS name UNION ALL
    SELECT 'Halem' AS name UNION ALL
    SELECT 'Helem' AS name UNION ALL
    SELECT 'Jamugurihat' AS name UNION ALL
    SELECT 'Kharijapikha' AS name UNION ALL
    SELECT 'Monabari' AS name UNION ALL
    SELECT 'Owgaon' AS name UNION ALL
    SELECT 'Panpur' AS name UNION ALL
    SELECT 'Pub-Chaiduar' AS name UNION ALL
    SELECT 'Pub-Gingia' AS name UNION ALL
    SELECT 'Sakomatha' AS name UNION ALL
    SELECT 'Sootea' AS name UNION ALL
    SELECT 'Sootea Town' AS name UNION ALL
    SELECT 'Uttar Gingia' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Biswanath'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Bongaigaon (26 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Abhayapuri' AS name UNION ALL
    SELECT 'Abhayapuri Town' AS name UNION ALL
    SELECT 'Birjhora' AS name UNION ALL
    SELECT 'Boitamari' AS name UNION ALL
    SELECT 'Boitamari Market' AS name UNION ALL
    SELECT 'Bongaigaon' AS name UNION ALL
    SELECT 'Bongaigaon Railway Area' AS name UNION ALL
    SELECT 'Bongaigaon Town' AS name UNION ALL
    SELECT 'Borpara' AS name UNION ALL
    SELECT 'Chapaguri' AS name UNION ALL
    SELECT 'Chapar-side area' AS name UNION ALL
    SELECT 'Dangtal' AS name UNION ALL
    SELECT 'Dhaligaon (BGR IOCL Area)' AS name UNION ALL
    SELECT 'Dolaigaon' AS name UNION ALL
    SELECT 'Jogighopa' AS name UNION ALL
    SELECT 'Khagrabari' AS name UNION ALL
    SELECT 'Manikpur' AS name UNION ALL
    SELECT 'Mayapuri' AS name UNION ALL
    SELECT 'Mulagaon' AS name UNION ALL
    SELECT 'New Bongaigaon' AS name UNION ALL
    SELECT 'New Bongaigaon Railway Colony' AS name UNION ALL
    SELECT 'North Bongaigaon' AS name UNION ALL
    SELECT 'Salbari Road' AS name UNION ALL
    SELECT 'South Bongaigaon' AS name UNION ALL
    SELECT 'Srijangram' AS name UNION ALL
    SELECT 'Tapattary' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Bongaigaon'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Cachar (37 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ambassa' AS name UNION ALL
    SELECT 'Ambicapatty' AS name UNION ALL
    SELECT 'Ambikapur' AS name UNION ALL
    SELECT 'Annapurna' AS name UNION ALL
    SELECT 'Assam University Area' AS name UNION ALL
    SELECT 'Banskandi' AS name UNION ALL
    SELECT 'Binnakandi' AS name UNION ALL
    SELECT 'Borkhola' AS name UNION ALL
    SELECT 'Central Road' AS name UNION ALL
    SELECT 'Das Colony' AS name UNION ALL
    SELECT 'Dholai' AS name UNION ALL
    SELECT 'Ghungoor' AS name UNION ALL
    SELECT 'Hailakandi Road Area' AS name UNION ALL
    SELECT 'Hospital Road' AS name UNION ALL
    SELECT 'Irongmara' AS name UNION ALL
    SELECT 'Itkhola' AS name UNION ALL
    SELECT 'Janiganj' AS name UNION ALL
    SELECT 'Kalain' AS name UNION ALL
    SELECT 'Kanakpur' AS name UNION ALL
    SELECT 'Katigorah' AS name UNION ALL
    SELECT 'Lakhipur' AS name UNION ALL
    SELECT 'Malugram' AS name UNION ALL
    SELECT 'Meherpur' AS name UNION ALL
    SELECT 'NIT Silchar Area' AS name UNION ALL
    SELECT 'National Highway Area' AS name UNION ALL
    SELECT 'Premtola' AS name UNION ALL
    SELECT 'Rangirkhari' AS name UNION ALL
    SELECT 'Rosekandy' AS name UNION ALL
    SELECT 'Silchar' AS name UNION ALL
    SELECT 'Silchar Town' AS name UNION ALL
    SELECT 'Sonai' AS name UNION ALL
    SELECT 'Sonai Road' AS name UNION ALL
    SELECT 'Tarapur' AS name UNION ALL
    SELECT 'Tarapur Silchar' AS name UNION ALL
    SELECT 'Udarbond' AS name UNION ALL
    SELECT 'Udharbond' AS name UNION ALL
    SELECT 'Vivekananda Road Area' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Cachar'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Charaideo (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bengenabari' AS name UNION ALL
    SELECT 'Borhat' AS name UNION ALL
    SELECT 'Borpukhuri' AS name UNION ALL
    SELECT 'Charaideo' AS name UNION ALL
    SELECT 'Charaideo Maidam (UNESCO Heritage)' AS name UNION ALL
    SELECT 'Charaideo Maidam Area' AS name UNION ALL
    SELECT 'Dehing' AS name UNION ALL
    SELECT 'Demow-side area' AS name UNION ALL
    SELECT 'Kakatibari' AS name UNION ALL
    SELECT 'Mahmora' AS name UNION ALL
    SELECT 'Moran Road Area' AS name UNION ALL
    SELECT 'Moranhat' AS name UNION ALL
    SELECT 'Namrup Road Area' AS name UNION ALL
    SELECT 'Nazira' AS name UNION ALL
    SELECT 'Patsaku' AS name UNION ALL
    SELECT 'Rajapukhuri' AS name UNION ALL
    SELECT 'Rajmai' AS name UNION ALL
    SELECT 'Santak' AS name UNION ALL
    SELECT 'Sapekhati' AS name UNION ALL
    SELECT 'Sapekhati Town' AS name UNION ALL
    SELECT 'Simaluguri' AS name UNION ALL
    SELECT 'Simaluguri Town' AS name UNION ALL
    SELECT 'Sonaighat' AS name UNION ALL
    SELECT 'Sonari' AS name UNION ALL
    SELECT 'Sonari Town' AS name UNION ALL
    SELECT 'Suffry' AS name UNION ALL
    SELECT 'Tipong' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Charaideo'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Chirang (23 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amteka' AS name UNION ALL
    SELECT 'Basugaon' AS name UNION ALL
    SELECT 'Basugaon Town' AS name UNION ALL
    SELECT 'Bengtol' AS name UNION ALL
    SELECT 'Bhur' AS name UNION ALL
    SELECT 'Bijni' AS name UNION ALL
    SELECT 'Bijni Town' AS name UNION ALL
    SELECT 'Bongaigaon Road Area' AS name UNION ALL
    SELECT 'Borobazar' AS name UNION ALL
    SELECT 'Chapaguri' AS name UNION ALL
    SELECT 'Dhaligaon' AS name UNION ALL
    SELECT 'India-Bhutan Road Area' AS name UNION ALL
    SELECT 'Kachugaon' AS name UNION ALL
    SELECT 'Kachugaon-side area' AS name UNION ALL
    SELECT 'Kajalgaon' AS name UNION ALL
    SELECT 'Kajalgaon Town' AS name UNION ALL
    SELECT 'Panbari' AS name UNION ALL
    SELECT 'Patiladaha-side area' AS name UNION ALL
    SELECT 'Runikhata' AS name UNION ALL
    SELECT 'Runikhata Market' AS name UNION ALL
    SELECT 'Sidli' AS name UNION ALL
    SELECT 'Sidli Road' AS name UNION ALL
    SELECT 'Tukrajhar' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Chirang'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Darrang (23 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Balipara' AS name UNION ALL
    SELECT 'Besimari' AS name UNION ALL
    SELECT 'Bhakatpara' AS name UNION ALL
    SELECT 'Bhergaon-side area' AS name UNION ALL
    SELECT 'Burha' AS name UNION ALL
    SELECT 'Dalgaon' AS name UNION ALL
    SELECT 'Dalgaon Town' AS name UNION ALL
    SELECT 'Dhula' AS name UNION ALL
    SELECT 'Dumunichowki' AS name UNION ALL
    SELECT 'Gerimari' AS name UNION ALL
    SELECT 'Hazarikapara' AS name UNION ALL
    SELECT 'Kalaigaon' AS name UNION ALL
    SELECT 'Khandajan' AS name UNION ALL
    SELECT 'Kharupetia' AS name UNION ALL
    SELECT 'Kharupetia Town' AS name UNION ALL
    SELECT 'Mangaldai' AS name UNION ALL
    SELECT 'Mangaldai Town' AS name UNION ALL
    SELECT 'Mazbat Road Area' AS name UNION ALL
    SELECT 'Pachim Mangaldai' AS name UNION ALL
    SELECT 'Patharighat' AS name UNION ALL
    SELECT 'Rowta Road Area' AS name UNION ALL
    SELECT 'Sipajhar' AS name UNION ALL
    SELECT 'Sipajhar Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Darrang'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Dhemaji (22 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bordoloni' AS name UNION ALL
    SELECT 'Bordoloni Road Area' AS name UNION ALL
    SELECT 'Dhemaji' AS name UNION ALL
    SELECT 'Dhemaji Town' AS name UNION ALL
    SELECT 'Dikhari' AS name UNION ALL
    SELECT 'Gerukamukh' AS name UNION ALL
    SELECT 'Gogamukh' AS name UNION ALL
    SELECT 'Gogamukh Town' AS name UNION ALL
    SELECT 'Jainagar' AS name UNION ALL
    SELECT 'Jonai' AS name UNION ALL
    SELECT 'Jonai Town' AS name UNION ALL
    SELECT 'Laimekuri' AS name UNION ALL
    SELECT 'Machkhowa' AS name UNION ALL
    SELECT 'Machkhowa Market' AS name UNION ALL
    SELECT 'Mingmang' AS name UNION ALL
    SELECT 'Murkongselek' AS name UNION ALL
    SELECT 'Samarajan' AS name UNION ALL
    SELECT 'Silapathar' AS name UNION ALL
    SELECT 'Silapathar Town' AS name UNION ALL
    SELECT 'Simen Chapori' AS name UNION ALL
    SELECT 'Sissiborgaon' AS name UNION ALL
    SELECT 'Telam' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Dhemaji'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Dhubri (26 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Agomani' AS name UNION ALL
    SELECT 'Bagribari' AS name UNION ALL
    SELECT 'Balajan' AS name UNION ALL
    SELECT 'Bilasipara' AS name UNION ALL
    SELECT 'Bilasipara Town' AS name UNION ALL
    SELECT 'Boglamari' AS name UNION ALL
    SELECT 'Chapar' AS name UNION ALL
    SELECT 'Debitola' AS name UNION ALL
    SELECT 'Dhubri' AS name UNION ALL
    SELECT 'Dhubri Town' AS name UNION ALL
    SELECT 'Fakirganj' AS name UNION ALL
    SELECT 'Gauripur' AS name UNION ALL
    SELECT 'Gauripur Town' AS name UNION ALL
    SELECT 'Golakganj' AS name UNION ALL
    SELECT 'Golakganj Town' AS name UNION ALL
    SELECT 'Gouripur Railway Area' AS name UNION ALL
    SELECT 'Halakura' AS name UNION ALL
    SELECT 'Mahamaya' AS name UNION ALL
    SELECT 'Panbari' AS name UNION ALL
    SELECT 'Raniganj' AS name UNION ALL
    SELECT 'Salkocha' AS name UNION ALL
    SELECT 'Sapatgram' AS name UNION ALL
    SELECT 'Sapatgram Town' AS name UNION ALL
    SELECT 'South Salmara-side area' AS name UNION ALL
    SELECT 'Tamarhat' AS name UNION ALL
    SELECT 'Tamarhat Market' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Dhubri'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Dibrugarh (38 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'AMC Road' AS name UNION ALL
    SELECT 'Amolapatty' AS name UNION ALL
    SELECT 'BCPL Lepetkata Area' AS name UNION ALL
    SELECT 'Banipur' AS name UNION ALL
    SELECT 'Boiragimoth' AS name UNION ALL
    SELECT 'Chabua' AS name UNION ALL
    SELECT 'Chiring Chapori' AS name UNION ALL
    SELECT 'Chowkidingee' AS name UNION ALL
    SELECT 'Chowkidinghee' AS name UNION ALL
    SELECT 'Convoy Road' AS name UNION ALL
    SELECT 'Dibrugarh' AS name UNION ALL
    SELECT 'Dibrugarh City' AS name UNION ALL
    SELECT 'Dibrugarh University Area' AS name UNION ALL
    SELECT 'Dinjan' AS name UNION ALL
    SELECT 'Duliajan' AS name UNION ALL
    SELECT 'Dulijan (Oil India HQ)' AS name UNION ALL
    SELECT 'Garpara' AS name UNION ALL
    SELECT 'Graham Bazar' AS name UNION ALL
    SELECT 'Jalan Nagar' AS name UNION ALL
    SELECT 'Joypur' AS name UNION ALL
    SELECT 'Khalihamari' AS name UNION ALL
    SELECT 'Khowang' AS name UNION ALL
    SELECT 'Khowangghat' AS name UNION ALL
    SELECT 'Lahowal' AS name UNION ALL
    SELECT 'Mancotta Road' AS name UNION ALL
    SELECT 'Milan Nagar' AS name UNION ALL
    SELECT 'Mohanbari (Airport Area)' AS name UNION ALL
    SELECT 'Moran' AS name UNION ALL
    SELECT 'Naharkatia' AS name UNION ALL
    SELECT 'Naharkatiya' AS name UNION ALL
    SELECT 'Naliapool' AS name UNION ALL
    SELECT 'Namrup' AS name UNION ALL
    SELECT 'Namrup (Fertilizer Town)' AS name UNION ALL
    SELECT 'Paltan Bazar' AS name UNION ALL
    SELECT 'Panitola' AS name UNION ALL
    SELECT 'Seujpur' AS name UNION ALL
    SELECT 'Tengakhat' AS name UNION ALL
    SELECT 'Tingkhong' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Dibrugarh'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Dima Hasao (23 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Asalu' AS name UNION ALL
    SELECT 'Dittokcherra' AS name UNION ALL
    SELECT 'Diyungbra' AS name UNION ALL
    SELECT 'Diyungmukh' AS name UNION ALL
    SELECT 'Haflong' AS name UNION ALL
    SELECT 'Haflong (Hill Station)' AS name UNION ALL
    SELECT 'Haflong Town' AS name UNION ALL
    SELECT 'Harangajao' AS name UNION ALL
    SELECT 'Hatikhali' AS name UNION ALL
    SELECT 'Jatinga' AS name UNION ALL
    SELECT 'Jatinga Road Area' AS name UNION ALL
    SELECT 'Langting' AS name UNION ALL
    SELECT 'Lower Haflong' AS name UNION ALL
    SELECT 'Mahur' AS name UNION ALL
    SELECT 'Mahur Town' AS name UNION ALL
    SELECT 'Maibang' AS name UNION ALL
    SELECT 'Maibang Town' AS name UNION ALL
    SELECT 'Meidang' AS name UNION ALL
    SELECT 'New Haflong' AS name UNION ALL
    SELECT 'Panimur' AS name UNION ALL
    SELECT 'Semkhor' AS name UNION ALL
    SELECT 'Umrangso' AS name UNION ALL
    SELECT 'Umrangso Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Dima Hasao'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Goalpara (29 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Agia' AS name UNION ALL
    SELECT 'Ambari' AS name UNION ALL
    SELECT 'Baladmari' AS name UNION ALL
    SELECT 'Baladmari Char' AS name UNION ALL
    SELECT 'Balijana' AS name UNION ALL
    SELECT 'Bhati Para' AS name UNION ALL
    SELECT 'Boguwan' AS name UNION ALL
    SELECT 'Darakh' AS name UNION ALL
    SELECT 'Darangiri' AS name UNION ALL
    SELECT 'Dhupdhara' AS name UNION ALL
    SELECT 'Dhupdhara Town' AS name UNION ALL
    SELECT 'Dudhnai' AS name UNION ALL
    SELECT 'Dudhnoi' AS name UNION ALL
    SELECT 'Dudhnoi Town' AS name UNION ALL
    SELECT 'Goalpara' AS name UNION ALL
    SELECT 'Goalpara Town' AS name UNION ALL
    SELECT 'Jaleswar' AS name UNION ALL
    SELECT 'Krishnai' AS name UNION ALL
    SELECT 'Krishnai Town' AS name UNION ALL
    SELECT 'Lakhipur' AS name UNION ALL
    SELECT 'Lakhipur (Goalpara)' AS name UNION ALL
    SELECT 'Lakhipur Town' AS name UNION ALL
    SELECT 'Marrnoi' AS name UNION ALL
    SELECT 'Matia' AS name UNION ALL
    SELECT 'Matia Town' AS name UNION ALL
    SELECT 'Mornoi' AS name UNION ALL
    SELECT 'Pancharatna' AS name UNION ALL
    SELECT 'Rangjuli' AS name UNION ALL
    SELECT 'Rangjuli Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Goalpara'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Golaghat (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Barpathar' AS name UNION ALL
    SELECT 'Bokakhat' AS name UNION ALL
    SELECT 'Bokakhat (Kaziranga Gateway)' AS name UNION ALL
    SELECT 'Bokakhat Town' AS name UNION ALL
    SELECT 'Borpathar' AS name UNION ALL
    SELECT 'Dergaon' AS name UNION ALL
    SELECT 'Dergaon (Police Academy Area)' AS name UNION ALL
    SELECT 'Dergaon Town' AS name UNION ALL
    SELECT 'Dhansiri' AS name UNION ALL
    SELECT 'Doigrung' AS name UNION ALL
    SELECT 'Furkating' AS name UNION ALL
    SELECT 'Furkating Town' AS name UNION ALL
    SELECT 'Gamariguri' AS name UNION ALL
    SELECT 'Ghiladhari' AS name UNION ALL
    SELECT 'Golaghat' AS name UNION ALL
    SELECT 'Golaghat Town' AS name UNION ALL
    SELECT 'Kacharihat' AS name UNION ALL
    SELECT 'Kamarbandha Ali' AS name UNION ALL
    SELECT 'Khumtai' AS name UNION ALL
    SELECT 'Merapani' AS name UNION ALL
    SELECT 'Morangi' AS name UNION ALL
    SELECT 'Numaligarh' AS name UNION ALL
    SELECT 'Numaligarh Refinery Area' AS name UNION ALL
    SELECT 'Puranigudam Road Area' AS name UNION ALL
    SELECT 'Rengma' AS name UNION ALL
    SELECT 'Sarupathar' AS name UNION ALL
    SELECT 'Sarupathar Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Golaghat'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Hailakandi (24 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Algapur' AS name UNION ALL
    SELECT 'Algapur Town' AS name UNION ALL
    SELECT 'Bashdahar' AS name UNION ALL
    SELECT 'Bilaipur' AS name UNION ALL
    SELECT 'College Road' AS name UNION ALL
    SELECT 'Gharmura' AS name UNION ALL
    SELECT 'Gharmura Market' AS name UNION ALL
    SELECT 'Hailakandi' AS name UNION ALL
    SELECT 'Hailakandi Town' AS name UNION ALL
    SELECT 'Kalibari Road' AS name UNION ALL
    SELECT 'Kanchanpur' AS name UNION ALL
    SELECT 'Katlicherra' AS name UNION ALL
    SELECT 'Katlicherra Town' AS name UNION ALL
    SELECT 'Lala' AS name UNION ALL
    SELECT 'Lala Town' AS name UNION ALL
    SELECT 'Matijuri' AS name UNION ALL
    SELECT 'Mohanpur' AS name UNION ALL
    SELECT 'Monacherra' AS name UNION ALL
    SELECT 'Narainpur' AS name UNION ALL
    SELECT 'Panchgram' AS name UNION ALL
    SELECT 'Panchgram Industrial Area' AS name UNION ALL
    SELECT 'Ramnathpur' AS name UNION ALL
    SELECT 'Rupacherra' AS name UNION ALL
    SELECT 'Station Road Area' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Hailakandi'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Hojai (24 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Barpathar' AS name UNION ALL
    SELECT 'Doboka' AS name UNION ALL
    SELECT 'Doboka Town' AS name UNION ALL
    SELECT 'Hojai' AS name UNION ALL
    SELECT 'Hojai Town' AS name UNION ALL
    SELECT 'Jamunamukh' AS name UNION ALL
    SELECT 'Jamunamukh Town' AS name UNION ALL
    SELECT 'Jugijan' AS name UNION ALL
    SELECT 'Kaki-side area' AS name UNION ALL
    SELECT 'Kampur-side area' AS name UNION ALL
    SELECT 'Kapashbari' AS name UNION ALL
    SELECT 'Lanka' AS name UNION ALL
    SELECT 'Lanka Town' AS name UNION ALL
    SELECT 'Lumding' AS name UNION ALL
    SELECT 'Lumding (Railway Junction)' AS name UNION ALL
    SELECT 'Lumding Town' AS name UNION ALL
    SELECT 'Murajhar' AS name UNION ALL
    SELECT 'Nilbagan' AS name UNION ALL
    SELECT 'Nilbagan Town' AS name UNION ALL
    SELECT 'Pub-Jamunamukh' AS name UNION ALL
    SELECT 'Radhanagar' AS name UNION ALL
    SELECT 'Sankardev Nagar' AS name UNION ALL
    SELECT 'Senchoa' AS name UNION ALL
    SELECT 'Udali' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Hojai'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Jorhat (36 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'AT Road' AS name UNION ALL
    SELECT 'AT Road Jorhat' AS name UNION ALL
    SELECT 'Baghmora' AS name UNION ALL
    SELECT 'Barbheta (Assam Agri Univ)' AS name UNION ALL
    SELECT 'Borbheta' AS name UNION ALL
    SELECT 'Choladhara' AS name UNION ALL
    SELECT 'Chowk Bazar' AS name UNION ALL
    SELECT 'Cinamara' AS name UNION ALL
    SELECT 'Cinnamora' AS name UNION ALL
    SELECT 'Dergaon Road Area' AS name UNION ALL
    SELECT 'Gar-Ali' AS name UNION ALL
    SELECT 'Jorhat' AS name UNION ALL
    SELECT 'Jorhat City' AS name UNION ALL
    SELECT 'Jorhat Town' AS name UNION ALL
    SELECT 'Kakajan' AS name UNION ALL
    SELECT 'Kendriya Vidyalaya Area' AS name UNION ALL
    SELECT 'Kenduguri' AS name UNION ALL
    SELECT 'Lahdoigarh' AS name UNION ALL
    SELECT 'Lichubari' AS name UNION ALL
    SELECT 'Majuli Border' AS name UNION ALL
    SELECT 'Malow Ali' AS name UNION ALL
    SELECT 'Mariani' AS name UNION ALL
    SELECT 'Mariani Town' AS name UNION ALL
    SELECT 'Meleng' AS name UNION ALL
    SELECT 'Na-Ali' AS name UNION ALL
    SELECT 'Pulibor' AS name UNION ALL
    SELECT 'Rowriah' AS name UNION ALL
    SELECT 'Rowriah (Airport Area)' AS name UNION ALL
    SELECT 'Selenghat' AS name UNION ALL
    SELECT 'Sotai' AS name UNION ALL
    SELECT 'Tarajan' AS name UNION ALL
    SELECT 'Tarajan Road' AS name UNION ALL
    SELECT 'Teok' AS name UNION ALL
    SELECT 'Teok Town' AS name UNION ALL
    SELECT 'Titabor' AS name UNION ALL
    SELECT 'Titabor Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Jorhat'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kamrup (35 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amingaon' AS name UNION ALL
    SELECT 'Amingaon (District HQ)' AS name UNION ALL
    SELECT 'Azara-side rural area' AS name UNION ALL
    SELECT 'Baihata Chariali' AS name UNION ALL
    SELECT 'Boko' AS name UNION ALL
    SELECT 'Boko Town' AS name UNION ALL
    SELECT 'Bongshar' AS name UNION ALL
    SELECT 'Chaygaon' AS name UNION ALL
    SELECT 'Chaygaon Town' AS name UNION ALL
    SELECT 'Goreswar' AS name UNION ALL
    SELECT 'Goreswar Road Area' AS name UNION ALL
    SELECT 'Goroimari' AS name UNION ALL
    SELECT 'Hajo' AS name UNION ALL
    SELECT 'Hajo Town' AS name UNION ALL
    SELECT 'Kamalpur' AS name UNION ALL
    SELECT 'Kharupetia-side corridor' AS name UNION ALL
    SELECT 'Khetri' AS name UNION ALL
    SELECT 'Madanpur' AS name UNION ALL
    SELECT 'Mirza' AS name UNION ALL
    SELECT 'Mirza Town' AS name UNION ALL
    SELECT 'Nagarbera' AS name UNION ALL
    SELECT 'North Guwahati' AS name UNION ALL
    SELECT 'North Guwahati (IIT Guwahati Area)' AS name UNION ALL
    SELECT 'Palasbari' AS name UNION ALL
    SELECT 'Palashbari' AS name UNION ALL
    SELECT 'Palashbari Town' AS name UNION ALL
    SELECT 'Pub Kamrup' AS name UNION ALL
    SELECT 'Rampur' AS name UNION ALL
    SELECT 'Rangia' AS name UNION ALL
    SELECT 'Rangia Town' AS name UNION ALL
    SELECT 'Sarthebari Road Area' AS name UNION ALL
    SELECT 'Singra' AS name UNION ALL
    SELECT 'Sualkuchi' AS name UNION ALL
    SELECT 'Sualkuchi (Silk Town)' AS name UNION ALL
    SELECT 'Sualkuchi Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Kamrup'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kamrup Metropolitan (78 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adabari' AS name UNION ALL
    SELECT 'Airport Road' AS name UNION ALL
    SELECT 'Amingaon' AS name UNION ALL
    SELECT 'Athgaon' AS name UNION ALL
    SELECT 'Azara' AS name UNION ALL
    SELECT 'Bagharbori' AS name UNION ALL
    SELECT 'Bamunimaidam Industrial Area' AS name UNION ALL
    SELECT 'Bamunimaidan' AS name UNION ALL
    SELECT 'Basistha' AS name UNION ALL
    SELECT 'Basistha Chariali' AS name UNION ALL
    SELECT 'Beltola' AS name UNION ALL
    SELECT 'Bhangagarh' AS name UNION ALL
    SELECT 'Bharalumukh' AS name UNION ALL
    SELECT 'Bhetapara' AS name UNION ALL
    SELECT 'Borbari' AS name UNION ALL
    SELECT 'Borjhar' AS name UNION ALL
    SELECT 'Borjhar (Airport Area)' AS name UNION ALL
    SELECT 'Chandmari' AS name UNION ALL
    SELECT 'Chandrapur' AS name UNION ALL
    SELECT 'Christian Basti' AS name UNION ALL
    SELECT 'Dharapur' AS name UNION ALL
    SELECT 'Dhirenpara' AS name UNION ALL
    SELECT 'Dispur' AS name UNION ALL
    SELECT 'Dispur (State Capital)' AS name UNION ALL
    SELECT 'Fancy Bazaar' AS name UNION ALL
    SELECT 'Fancy Bazar' AS name UNION ALL
    SELECT 'Fatasil Ambari' AS name UNION ALL
    SELECT 'GS Road' AS name UNION ALL
    SELECT 'Ganeshguri' AS name UNION ALL
    SELECT 'Garchuk' AS name UNION ALL
    SELECT 'Geetanagar' AS name UNION ALL
    SELECT 'Gopinath Nagar' AS name UNION ALL
    SELECT 'Guwahati' AS name UNION ALL
    SELECT 'Guwahati City' AS name UNION ALL
    SELECT 'Hatigaon' AS name UNION ALL
    SELECT 'Hengrabari' AS name UNION ALL
    SELECT 'Jalukbari' AS name UNION ALL
    SELECT 'Jalukbari (Gauhati University)' AS name UNION ALL
    SELECT 'Jatia' AS name UNION ALL
    SELECT 'Joymati Nagar' AS name UNION ALL
    SELECT 'Kahikuchi' AS name UNION ALL
    SELECT 'Kahilipara' AS name UNION ALL
    SELECT 'Khanapara' AS name UNION ALL
    SELECT 'Kumarpara' AS name UNION ALL
    SELECT 'Lachit Nagar' AS name UNION ALL
    SELECT 'Lal Ganesh' AS name UNION ALL
    SELECT 'Lalganesh' AS name UNION ALL
    SELECT 'Lalmati' AS name UNION ALL
    SELECT 'Lokhra' AS name UNION ALL
    SELECT 'Machkhowa' AS name UNION ALL
    SELECT 'Maligaon' AS name UNION ALL
    SELECT 'Maligaon (NFR HQ)' AS name UNION ALL
    SELECT 'Narengi' AS name UNION ALL
    SELECT 'Narengi Tiniali' AS name UNION ALL
    SELECT 'Noonmati' AS name UNION ALL
    SELECT 'Noonmati (Guwahati Refinery)' AS name UNION ALL
    SELECT 'North Guwahati' AS name UNION ALL
    SELECT 'Paltan Bazaar' AS name UNION ALL
    SELECT 'Paltan Bazar' AS name UNION ALL
    SELECT 'Pan Bazaar' AS name UNION ALL
    SELECT 'Pandu' AS name UNION ALL
    SELECT 'Panikhaiti' AS name UNION ALL
    SELECT 'Patharkuwari' AS name UNION ALL
    SELECT 'RG Baruah Road (Zoo Road)' AS name UNION ALL
    SELECT 'Rehabari' AS name UNION ALL
    SELECT 'Rupnagar' AS name UNION ALL
    SELECT 'Santipur' AS name UNION ALL
    SELECT 'Sarusajai' AS name UNION ALL
    SELECT 'Silpukhuri' AS name UNION ALL
    SELECT 'Six Mile' AS name UNION ALL
    SELECT 'Sonapur' AS name UNION ALL
    SELECT 'Sreenagar' AS name UNION ALL
    SELECT 'Ulubari' AS name UNION ALL
    SELECT 'Uzan Bazaar' AS name UNION ALL
    SELECT 'Uzanbazar' AS name UNION ALL
    SELECT 'VIP Road' AS name UNION ALL
    SELECT 'Zoo Road' AS name UNION ALL
    SELECT 'Zoo Road Tiniali' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Kamrup Metropolitan'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Karbi Anglong (30 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baithalangso' AS name UNION ALL
    SELECT 'Bakalia' AS name UNION ALL
    SELECT 'Bokajan' AS name UNION ALL
    SELECT 'Bokajan (Cement Plant)' AS name UNION ALL
    SELECT 'Bokajan Town' AS name UNION ALL
    SELECT 'Borlangpher' AS name UNION ALL
    SELECT 'Dhansiri' AS name UNION ALL
    SELECT 'Dillai' AS name UNION ALL
    SELECT 'Dillai Tiniali' AS name UNION ALL
    SELECT 'Diphu' AS name UNION ALL
    SELECT 'Diphu Town' AS name UNION ALL
    SELECT 'Dokmoka' AS name UNION ALL
    SELECT 'Dokmoka Town' AS name UNION ALL
    SELECT 'Donkamukam' AS name UNION ALL
    SELECT 'Hamren' AS name UNION ALL
    SELECT 'Hamren-side area' AS name UNION ALL
    SELECT 'Howraghat' AS name UNION ALL
    SELECT 'Howraghat Town' AS name UNION ALL
    SELECT 'Khatkhati' AS name UNION ALL
    SELECT 'Kheroni' AS name UNION ALL
    SELECT 'Langhin' AS name UNION ALL
    SELECT 'Longnit' AS name UNION ALL
    SELECT 'Lower Deopani' AS name UNION ALL
    SELECT 'Manja' AS name UNION ALL
    SELECT 'Nihanglangso' AS name UNION ALL
    SELECT 'Phuloni' AS name UNION ALL
    SELECT 'Rongapahar' AS name UNION ALL
    SELECT 'Rongmongwe' AS name UNION ALL
    SELECT 'Silonijan' AS name UNION ALL
    SELECT 'Upper Deopani' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Karbi Anglong'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Karimganj (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Anipur' AS name UNION ALL
    SELECT 'Badarpur' AS name UNION ALL
    SELECT 'Badarpur Town' AS name UNION ALL
    SELECT 'Bazarghat' AS name UNION ALL
    SELECT 'Bazaricherra' AS name UNION ALL
    SELECT 'Bhairab Nagar' AS name UNION ALL
    SELECT 'Chargola' AS name UNION ALL
    SELECT 'Cheragi' AS name UNION ALL
    SELECT 'Dullavcherra' AS name UNION ALL
    SELECT 'Kaliganj' AS name UNION ALL
    SELECT 'Kanisail' AS name UNION ALL
    SELECT 'Karimganj' AS name UNION ALL
    SELECT 'Karimganj Town' AS name UNION ALL
    SELECT 'Longai' AS name UNION ALL
    SELECT 'Lowairpoa' AS name UNION ALL
    SELECT 'Mission Road' AS name UNION ALL
    SELECT 'Nilambazar' AS name UNION ALL
    SELECT 'Nilambazar Town' AS name UNION ALL
    SELECT 'Patharkandi' AS name UNION ALL
    SELECT 'Patharkandi Town' AS name UNION ALL
    SELECT 'Patherkandi Road Area' AS name UNION ALL
    SELECT 'Ramkrishna Nagar' AS name UNION ALL
    SELECT 'Ramkrishna Nagar Town' AS name UNION ALL
    SELECT 'Ratabari' AS name UNION ALL
    SELECT 'Sadarghat' AS name UNION ALL
    SELECT 'Station Road' AS name UNION ALL
    SELECT 'Suprakandi' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Karimganj'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kokrajhar (26 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Balajan' AS name UNION ALL
    SELECT 'Basugaon-side area' AS name UNION ALL
    SELECT 'Bhawraguri' AS name UNION ALL
    SELECT 'Bhowraguri' AS name UNION ALL
    SELECT 'Bishmuri' AS name UNION ALL
    SELECT 'Borobazar-side area' AS name UNION ALL
    SELECT 'Dotma' AS name UNION ALL
    SELECT 'Dotma Town' AS name UNION ALL
    SELECT 'Fakiragram' AS name UNION ALL
    SELECT 'Gossaigaon' AS name UNION ALL
    SELECT 'Gossaigaon Town' AS name UNION ALL
    SELECT 'Hatigaon' AS name UNION ALL
    SELECT 'Kachubari' AS name UNION ALL
    SELECT 'Kachugaon' AS name UNION ALL
    SELECT 'Kazigaon' AS name UNION ALL
    SELECT 'Kokrajhar' AS name UNION ALL
    SELECT 'Kokrajhar Town' AS name UNION ALL
    SELECT 'Moinaguri' AS name UNION ALL
    SELECT 'Patgaon' AS name UNION ALL
    SELECT 'Salakati' AS name UNION ALL
    SELECT 'Salakati Industrial Area' AS name UNION ALL
    SELECT 'Saraibil' AS name UNION ALL
    SELECT 'Serfanguri' AS name UNION ALL
    SELECT 'Serfanguri Town' AS name UNION ALL
    SELECT 'Tengapara' AS name UNION ALL
    SELECT 'Ultapani' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Kokrajhar'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Lakhimpur (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Azad Nagar' AS name UNION ALL
    SELECT 'Banderdewa-side corridor' AS name UNION ALL
    SELECT 'Bihpuria' AS name UNION ALL
    SELECT 'Bihpuria Town' AS name UNION ALL
    SELECT 'Boginadi' AS name UNION ALL
    SELECT 'Dhakuakhana' AS name UNION ALL
    SELECT 'Dhakuakhana Town' AS name UNION ALL
    SELECT 'Ghilamara' AS name UNION ALL
    SELECT 'Ghunasuti' AS name UNION ALL
    SELECT 'Harmoti' AS name UNION ALL
    SELECT 'Harmoti Town' AS name UNION ALL
    SELECT 'Kakoi' AS name UNION ALL
    SELECT 'Khelmati' AS name UNION ALL
    SELECT 'Laluk' AS name UNION ALL
    SELECT 'Laluk Town' AS name UNION ALL
    SELECT 'Naoboicha' AS name UNION ALL
    SELECT 'Narayanpur' AS name UNION ALL
    SELECT 'Narayanpur Town' AS name UNION ALL
    SELECT 'North Lakhimpur' AS name UNION ALL
    SELECT 'North Lakhimpur Town' AS name UNION ALL
    SELECT 'Nowboicha' AS name UNION ALL
    SELECT 'Nowboicha Road' AS name UNION ALL
    SELECT 'Panigaon' AS name UNION ALL
    SELECT 'Phukan Nagar' AS name UNION ALL
    SELECT 'Sissiborgaon Road Area' AS name UNION ALL
    SELECT 'Subansiri' AS name UNION ALL
    SELECT 'Telahi' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Lakhimpur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Majuli (24 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ahatguri' AS name UNION ALL
    SELECT 'Auniati' AS name UNION ALL
    SELECT 'Auniati Satra Area' AS name UNION ALL
    SELECT 'Bengenaati' AS name UNION ALL
    SELECT 'Bhogpur' AS name UNION ALL
    SELECT 'Bongaon Majuli' AS name UNION ALL
    SELECT 'Bonori' AS name UNION ALL
    SELECT 'Chamaguri' AS name UNION ALL
    SELECT 'Dakhinpat' AS name UNION ALL
    SELECT 'Dakhinpat Satra Area' AS name UNION ALL
    SELECT 'Garamur' AS name UNION ALL
    SELECT 'Garmur Civil Centre' AS name UNION ALL
    SELECT 'Garmur Town' AS name UNION ALL
    SELECT 'Jengraimukh' AS name UNION ALL
    SELECT 'Jorhat Ferry Ghat' AS name UNION ALL
    SELECT 'Kamalabari' AS name UNION ALL
    SELECT 'Kamalabari Town' AS name UNION ALL
    SELECT 'Kamlabari Ferry Area' AS name UNION ALL
    SELECT 'Majuli College Area' AS name UNION ALL
    SELECT 'Nimati Ghat-side area' AS name UNION ALL
    SELECT 'Phulani' AS name UNION ALL
    SELECT 'Rawnapar' AS name UNION ALL
    SELECT 'Salmora' AS name UNION ALL
    SELECT 'Uttar Kamalabari' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Majuli'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Morigaon (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baralimari' AS name UNION ALL
    SELECT 'Barapujia' AS name UNION ALL
    SELECT 'Barbhag' AS name UNION ALL
    SELECT 'Barpeta Road' AS name UNION ALL
    SELECT 'Bhakatgaon' AS name UNION ALL
    SELECT 'Bhuragaon' AS name UNION ALL
    SELECT 'Bhurbandha' AS name UNION ALL
    SELECT 'Dharamtul' AS name UNION ALL
    SELECT 'Dharamtul Town' AS name UNION ALL
    SELECT 'Jagi Bhakatgaon' AS name UNION ALL
    SELECT 'Jagiroad' AS name UNION ALL
    SELECT 'Jagiroad (Paper Mill / Tech Area)' AS name UNION ALL
    SELECT 'Jagiroad Town' AS name UNION ALL
    SELECT 'Kachari Gaon' AS name UNION ALL
    SELECT 'Kapili' AS name UNION ALL
    SELECT 'Laharighat' AS name UNION ALL
    SELECT 'Laharighat Town' AS name UNION ALL
    SELECT 'Lahorighat Road Area' AS name UNION ALL
    SELECT 'Manaha' AS name UNION ALL
    SELECT 'Mayong' AS name UNION ALL
    SELECT 'Mayong (Land of Magic)' AS name UNION ALL
    SELECT 'Mayong Town' AS name UNION ALL
    SELECT 'Moirabari' AS name UNION ALL
    SELECT 'Morigaon' AS name UNION ALL
    SELECT 'Morigaon Town' AS name UNION ALL
    SELECT 'Nellie' AS name UNION ALL
    SELECT 'Pobitora-side area' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Morigaon'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Nagaon (37 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amdubi' AS name UNION ALL
    SELECT 'Amolapatty' AS name UNION ALL
    SELECT 'Barhampur' AS name UNION ALL
    SELECT 'Batadrava' AS name UNION ALL
    SELECT 'Batadrava Town' AS name UNION ALL
    SELECT 'Bebejia' AS name UNION ALL
    SELECT 'Chaparmukh' AS name UNION ALL
    SELECT 'Christianpatty' AS name UNION ALL
    SELECT 'Daccapatty' AS name UNION ALL
    SELECT 'Dhing' AS name UNION ALL
    SELECT 'Dhing Town' AS name UNION ALL
    SELECT 'Haibargaon' AS name UNION ALL
    SELECT 'Hatigaon' AS name UNION ALL
    SELECT 'Juria' AS name UNION ALL
    SELECT 'Kachua' AS name UNION ALL
    SELECT 'Kaliabor' AS name UNION ALL
    SELECT 'Kaliabor Town' AS name UNION ALL
    SELECT 'Kampur' AS name UNION ALL
    SELECT 'Kampur Town' AS name UNION ALL
    SELECT 'Kandali' AS name UNION ALL
    SELECT 'Kolia Bor' AS name UNION ALL
    SELECT 'Lanka-side corridor' AS name UNION ALL
    SELECT 'Laokhowa' AS name UNION ALL
    SELECT 'Marigaon Road Area' AS name UNION ALL
    SELECT 'Nagaon' AS name UNION ALL
    SELECT 'Nagaon City' AS name UNION ALL
    SELECT 'Nonoi' AS name UNION ALL
    SELECT 'Panigaon' AS name UNION ALL
    SELECT 'Puranigudam' AS name UNION ALL
    SELECT 'Raha' AS name UNION ALL
    SELECT 'Raha Town' AS name UNION ALL
    SELECT 'Rupahihat' AS name UNION ALL
    SELECT 'Rupahihat Town' AS name UNION ALL
    SELECT 'Rupohihat' AS name UNION ALL
    SELECT 'Samaguri' AS name UNION ALL
    SELECT 'Samaguri Town' AS name UNION ALL
    SELECT 'Singia' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Nagaon'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Nalbari (28 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Banekuchi' AS name UNION ALL
    SELECT 'Banekuchi Town' AS name UNION ALL
    SELECT 'Barbhag' AS name UNION ALL
    SELECT 'Barbhitha' AS name UNION ALL
    SELECT 'Barkhetri' AS name UNION ALL
    SELECT 'Barkhetri Road Area' AS name UNION ALL
    SELECT 'Barkshetri' AS name UNION ALL
    SELECT 'Chamata' AS name UNION ALL
    SELECT 'Doulashal' AS name UNION ALL
    SELECT 'Ghograpar' AS name UNION ALL
    SELECT 'Ghograpar Town' AS name UNION ALL
    SELECT 'Haribhanga' AS name UNION ALL
    SELECT 'Hatichong' AS name UNION ALL
    SELECT 'Kaithalkuchi' AS name UNION ALL
    SELECT 'Khatihari' AS name UNION ALL
    SELECT 'Morowa' AS name UNION ALL
    SELECT 'Mukalmua' AS name UNION ALL
    SELECT 'Mukalmua Road' AS name UNION ALL
    SELECT 'Mukalmua Town' AS name UNION ALL
    SELECT 'Nalbari' AS name UNION ALL
    SELECT 'Nalbari Town' AS name UNION ALL
    SELECT 'Naldhara' AS name UNION ALL
    SELECT 'Paschim Nalbari' AS name UNION ALL
    SELECT 'Pipalibari' AS name UNION ALL
    SELECT 'Pub Nalbari' AS name UNION ALL
    SELECT 'Sonkuchi' AS name UNION ALL
    SELECT 'Tihu' AS name UNION ALL
    SELECT 'Tihu Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Nalbari'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Sivasagar (26 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amguri' AS name UNION ALL
    SELECT 'Amguri Town' AS name UNION ALL
    SELECT 'Bhatiapar' AS name UNION ALL
    SELECT 'Borpukhuri' AS name UNION ALL
    SELECT 'Demow' AS name UNION ALL
    SELECT 'Demow Town' AS name UNION ALL
    SELECT 'Dikhowmukh' AS name UNION ALL
    SELECT 'Gaurisagar' AS name UNION ALL
    SELECT 'Joysagar' AS name UNION ALL
    SELECT 'Joysagar Town' AS name UNION ALL
    SELECT 'Khelua' AS name UNION ALL
    SELECT 'Lakwa' AS name UNION ALL
    SELECT 'Mahmora-side area' AS name UNION ALL
    SELECT 'Meteka' AS name UNION ALL
    SELECT 'Moran Road Area' AS name UNION ALL
    SELECT 'Nazira' AS name UNION ALL
    SELECT 'Nazira (ONGC HQ)' AS name UNION ALL
    SELECT 'Nazira Town' AS name UNION ALL
    SELECT 'Panbecha' AS name UNION ALL
    SELECT 'Patsaku' AS name UNION ALL
    SELECT 'Simaluguri' AS name UNION ALL
    SELECT 'Simaluguri Town' AS name UNION ALL
    SELECT 'Sivasagar' AS name UNION ALL
    SELECT 'Sivasagar Town' AS name UNION ALL
    SELECT 'Sonari-side corridor' AS name UNION ALL
    SELECT 'Thowra' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Sivasagar'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Sonitpur (33 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Balipara' AS name UNION ALL
    SELECT 'Balipara Town' AS name UNION ALL
    SELECT 'Barchalla' AS name UNION ALL
    SELECT 'Barchalla Town' AS name UNION ALL
    SELECT 'Bhakatpara' AS name UNION ALL
    SELECT 'Bishwanath Border' AS name UNION ALL
    SELECT 'Biswanath Road Area' AS name UNION ALL
    SELECT 'Borsola' AS name UNION ALL
    SELECT 'Chariduar' AS name UNION ALL
    SELECT 'Chowk Bazar' AS name UNION ALL
    SELECT 'Dekargaon' AS name UNION ALL
    SELECT 'Dekargaon Town' AS name UNION ALL
    SELECT 'Dhekiajuli' AS name UNION ALL
    SELECT 'Dhekiajuli Town' AS name UNION ALL
    SELECT 'Gohpur-side corridor' AS name UNION ALL
    SELECT 'Jamugurihat' AS name UNION ALL
    SELECT 'Ketekibari' AS name UNION ALL
    SELECT 'Mahabhairab' AS name UNION ALL
    SELECT 'Mission Chariali' AS name UNION ALL
    SELECT 'Murkongselek Road Area' AS name UNION ALL
    SELECT 'Napaam' AS name UNION ALL
    SELECT 'Parbatiea' AS name UNION ALL
    SELECT 'Poruwa Chariali' AS name UNION ALL
    SELECT 'Rangapara' AS name UNION ALL
    SELECT 'Rangapara Town' AS name UNION ALL
    SELECT 'Rowta Road Area' AS name UNION ALL
    SELECT 'Sootea-side corridor' AS name UNION ALL
    SELECT 'Tezpur' AS name UNION ALL
    SELECT 'Tezpur City' AS name UNION ALL
    SELECT 'Tezpur Town' AS name UNION ALL
    SELECT 'Tezpur University (Napaam)' AS name UNION ALL
    SELECT 'Thelamara' AS name UNION ALL
    SELECT 'Tribeni' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Sonitpur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: South Salmara-Mankachar (23 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amedubi' AS name UNION ALL
    SELECT 'Asarkandi' AS name UNION ALL
    SELECT 'Borokalia' AS name UNION ALL
    SELECT 'Dhanua' AS name UNION ALL
    SELECT 'Fekamari' AS name UNION ALL
    SELECT 'Fekamari Town' AS name UNION ALL
    SELECT 'Hatsingimari' AS name UNION ALL
    SELECT 'Hatsingimari Town' AS name UNION ALL
    SELECT 'Jhawdanga' AS name UNION ALL
    SELECT 'Jordanga' AS name UNION ALL
    SELECT 'Kakripara' AS name UNION ALL
    SELECT 'Kharma' AS name UNION ALL
    SELECT 'Kharuabandha' AS name UNION ALL
    SELECT 'Mahendraganj' AS name UNION ALL
    SELECT 'Mankachar' AS name UNION ALL
    SELECT 'Mankachar Road Area' AS name UNION ALL
    SELECT 'Mankachar Town' AS name UNION ALL
    SELECT 'Salkocha-side area' AS name UNION ALL
    SELECT 'South Salmara' AS name UNION ALL
    SELECT 'South Salmara Town' AS name UNION ALL
    SELECT 'Sukchar' AS name UNION ALL
    SELECT 'Sukhchar' AS name UNION ALL
    SELECT 'Tumni' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'South Salmara-Mankachar'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Tamulpur (21 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bagaribari' AS name UNION ALL
    SELECT 'Balipara' AS name UNION ALL
    SELECT 'Bhairabkunda-side corridor' AS name UNION ALL
    SELECT 'Bhergaon Road Area' AS name UNION ALL
    SELECT 'Bhuyapara' AS name UNION ALL
    SELECT 'Darangajuli' AS name UNION ALL
    SELECT 'Dwarkuchi' AS name UNION ALL
    SELECT 'Goreswar Road' AS name UNION ALL
    SELECT 'Goreswar-side area' AS name UNION ALL
    SELECT 'Kalipur' AS name UNION ALL
    SELECT 'Kumarikata' AS name UNION ALL
    SELECT 'Kumarikata Town' AS name UNION ALL
    SELECT 'Nagrijuli' AS name UNION ALL
    SELECT 'Nagrijuli Town' AS name UNION ALL
    SELECT 'No. 1 Tamulpur' AS name UNION ALL
    SELECT 'Subankhata' AS name UNION ALL
    SELECT 'Subankhata Town' AS name UNION ALL
    SELECT 'Suklai' AS name UNION ALL
    SELECT 'Suklai Market' AS name UNION ALL
    SELECT 'Tamulpur' AS name UNION ALL
    SELECT 'Tamulpur Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Tamulpur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Tinsukia (37 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bordoloi Nagar' AS name UNION ALL
    SELECT 'Bordumsa' AS name UNION ALL
    SELECT 'Borguri' AS name UNION ALL
    SELECT 'Chapakhowa' AS name UNION ALL
    SELECT 'Dangari' AS name UNION ALL
    SELECT 'Dhola' AS name UNION ALL
    SELECT 'Dibrugarh Road Area' AS name UNION ALL
    SELECT 'Digboi' AS name UNION ALL
    SELECT 'Digboi (Oldest Oil Refinery)' AS name UNION ALL
    SELECT 'Digboi Town' AS name UNION ALL
    SELECT 'Doomdooma' AS name UNION ALL
    SELECT 'Doomdooma Town' AS name UNION ALL
    SELECT 'Hapjan' AS name UNION ALL
    SELECT 'Hijuguri' AS name UNION ALL
    SELECT 'Itakhuli' AS name UNION ALL
    SELECT 'Jagun' AS name UNION ALL
    SELECT 'Kakopather' AS name UNION ALL
    SELECT 'Laipuli' AS name UNION ALL
    SELECT 'Ledo' AS name UNION ALL
    SELECT 'Makum' AS name UNION ALL
    SELECT 'Makum Town' AS name UNION ALL
    SELECT 'Margherita' AS name UNION ALL
    SELECT 'Margherita (Coal City)' AS name UNION ALL
    SELECT 'Margherita Town' AS name UNION ALL
    SELECT 'Namdang' AS name UNION ALL
    SELECT 'Parbatia' AS name UNION ALL
    SELECT 'Pengeri' AS name UNION ALL
    SELECT 'Philobari' AS name UNION ALL
    SELECT 'Rongagaon' AS name UNION ALL
    SELECT 'Rupai' AS name UNION ALL
    SELECT 'Sadiya' AS name UNION ALL
    SELECT 'Sadiya Town' AS name UNION ALL
    SELECT 'Talap' AS name UNION ALL
    SELECT 'Tinsukia' AS name UNION ALL
    SELECT 'Tinsukia City' AS name UNION ALL
    SELECT 'Tinsukia Railway Area' AS name UNION ALL
    SELECT 'Tinsukia Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Tinsukia'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Udalguri (25 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bengbari' AS name UNION ALL
    SELECT 'Bhairabkunda' AS name UNION ALL
    SELECT 'Bhergaon' AS name UNION ALL
    SELECT 'Dhupguri' AS name UNION ALL
    SELECT 'Dimakuchi' AS name UNION ALL
    SELECT 'Harisinga' AS name UNION ALL
    SELECT 'Harisinga Town' AS name UNION ALL
    SELECT 'Kalaigaon' AS name UNION ALL
    SELECT 'Kalaigaon Road' AS name UNION ALL
    SELECT 'Kalaigaon Town' AS name UNION ALL
    SELECT 'Khoirabari' AS name UNION ALL
    SELECT 'Mazbat' AS name UNION ALL
    SELECT 'Mazbat Town' AS name UNION ALL
    SELECT 'Missamari-side area' AS name UNION ALL
    SELECT 'Orang-side corridor' AS name UNION ALL
    SELECT 'Paneri' AS name UNION ALL
    SELECT 'Paneri Town' AS name UNION ALL
    SELECT 'Purani Pukhuri' AS name UNION ALL
    SELECT 'Rowta' AS name UNION ALL
    SELECT 'Rowta Road Area' AS name UNION ALL
    SELECT 'Rowta Town' AS name UNION ALL
    SELECT 'Tangla' AS name UNION ALL
    SELECT 'Tangla Town' AS name UNION ALL
    SELECT 'Udalguri' AS name UNION ALL
    SELECT 'Udalguri Town' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'Udalguri'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: West Karbi Anglong (26 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amri' AS name UNION ALL
    SELECT 'Amri Road Area' AS name UNION ALL
    SELECT 'Baithalangso' AS name UNION ALL
    SELECT 'Baithalangso Market' AS name UNION ALL
    SELECT 'Baithalangso Town' AS name UNION ALL
    SELECT 'Donkamokam' AS name UNION ALL
    SELECT 'Donkamukam' AS name UNION ALL
    SELECT 'Donkamukam Town' AS name UNION ALL
    SELECT 'Hamren' AS name UNION ALL
    SELECT 'Hamren Road Area' AS name UNION ALL
    SELECT 'Hamren Town' AS name UNION ALL
    SELECT 'Jengkha' AS name UNION ALL
    SELECT 'Jengkha Town' AS name UNION ALL
    SELECT 'Karbi Anglong Border Area' AS name UNION ALL
    SELECT 'Kheroni' AS name UNION ALL
    SELECT 'Kheroni Town' AS name UNION ALL
    SELECT 'Kothiatoli-side corridor' AS name UNION ALL
    SELECT 'Langpi' AS name UNION ALL
    SELECT 'Langpi Town' AS name UNION ALL
    SELECT 'Panimur' AS name UNION ALL
    SELECT 'Phuloni' AS name UNION ALL
    SELECT 'Rengma' AS name UNION ALL
    SELECT 'Rongkhang' AS name UNION ALL
    SELECT 'Rongkhang Town' AS name UNION ALL
    SELECT 'Silonijan Road Area' AS name UNION ALL
    SELECT 'Umpanai' AS name
) l
WHERE (s.code = 'AS' OR s.name = 'Assam')
  AND d.name = 'West Karbi Anglong'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

SET FOREIGN_KEY_CHECKS = 1;
