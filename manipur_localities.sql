-- ==============================================================================
-- UnlockRentals - Complete Manipur Districts & Localities Master Setup
-- Official LGD & Administrative Directory for UnlockRentals.com
-- Covers all 16 Districts and 320+ Verified Localities, Towns & Neighborhoods
-- Compatible with: MySQL 5.7+, MariaDB 10+, phpMyAdmin, and SQLite
-- Idempotent: Safe to execute repeatedly without generating duplicates
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure State 'Manipur' (Code: MN) Exists
INSERT INTO `states` (`name`, `code`)
SELECT 'Manipur', 'MN'
WHERE NOT EXISTS (
    SELECT 1 FROM `states` WHERE `code` = 'MN' OR `name` = 'Manipur'
);

-- 2. Ensure All 16 Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT s.id, d.name
FROM `states` s
CROSS JOIN (
    SELECT 'Bishnupur' AS name UNION ALL
    SELECT 'Chandel' AS name UNION ALL
    SELECT 'Churachandpur' AS name UNION ALL
    SELECT 'Imphal East' AS name UNION ALL
    SELECT 'Imphal West' AS name UNION ALL
    SELECT 'Jiribam' AS name UNION ALL
    SELECT 'Kakching' AS name UNION ALL
    SELECT 'Kamjong' AS name UNION ALL
    SELECT 'Kangpokpi' AS name UNION ALL
    SELECT 'Noney' AS name UNION ALL
    SELECT 'Pherzawl' AS name UNION ALL
    SELECT 'Senapati' AS name UNION ALL
    SELECT 'Tamenglong' AS name UNION ALL
    SELECT 'Tengnoupal' AS name UNION ALL
    SELECT 'Thoubal' AS name UNION ALL
    SELECT 'Ukhrul' AS name
) d
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND NOT EXISTS (
      SELECT 1 FROM `districts` existing 
      WHERE existing.state_id = s.id AND existing.name = d.name
  );

-- 3. Insert District-by-District Locality Directory

-- ------------------------------------------------------------------------------
-- District 1/16: Bishnupur (URL slug: bishnupur)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bishnupur' AS name UNION ALL
    SELECT 'Nambol' AS name UNION ALL
    SELECT 'Moirang' AS name UNION ALL
    SELECT 'Kumbi' AS name UNION ALL
    SELECT 'Phubala' AS name UNION ALL
    SELECT 'Oinam' AS name UNION ALL
    SELECT 'Ningthoukhong' AS name UNION ALL
    SELECT 'Nachou' AS name UNION ALL
    SELECT 'Toubul' AS name UNION ALL
    SELECT 'Thanga' AS name UNION ALL
    SELECT 'Loktak' AS name UNION ALL
    SELECT 'Kwakta' AS name UNION ALL
    SELECT 'Bishnupur Bazar' AS name UNION ALL
    SELECT 'Moirang Bazar' AS name UNION ALL
    SELECT 'Nambol Bazar' AS name UNION ALL
    SELECT 'Oinam Bazar' AS name UNION ALL
    SELECT 'Sendra Island' AS name UNION ALL
    SELECT 'Keibul Lamjao' AS name UNION ALL
    SELECT 'Khoijuman' AS name UNION ALL
    SELECT 'Leimapokpam' AS name UNION ALL
    SELECT 'Potshangbam' AS name UNION ALL
    SELECT 'Kwaksiphai' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Bishnupur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 2/16: Chandel (URL slug: chandel)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Chandel' AS name UNION ALL
    SELECT 'Moreh' AS name UNION ALL
    SELECT 'Tengnoupal' AS name UNION ALL
    SELECT 'Machi' AS name UNION ALL
    SELECT 'Chakpikarong' AS name UNION ALL
    SELECT 'Sugnu' AS name UNION ALL
    SELECT 'Pallel' AS name UNION ALL
    SELECT 'Khengjoi' AS name UNION ALL
    SELECT 'Japhou' AS name UNION ALL
    SELECT 'Khoibu' AS name UNION ALL
    SELECT 'Lamlong' AS name UNION ALL
    SELECT 'Chingjaroi' AS name UNION ALL
    SELECT 'Bongli' AS name UNION ALL
    SELECT 'Kuraopokpi' AS name UNION ALL
    SELECT 'Laiching Khullen' AS name UNION ALL
    SELECT 'Sairel Atou' AS name UNION ALL
    SELECT 'Chandel HQ' AS name UNION ALL
    SELECT 'Maha Union' AS name UNION ALL
    SELECT 'Monsang Pantha' AS name UNION ALL
    SELECT 'Lambung' AS name UNION ALL
    SELECT 'Komlathabi' AS name UNION ALL
    SELECT 'Unapat' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Chandel'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 3/16: Churachandpur (URL slug: churachandpur)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Churachandpur' AS name UNION ALL
    SELECT 'Tuibong' AS name UNION ALL
    SELECT 'New Lamka' AS name UNION ALL
    SELECT 'Old Lamka' AS name UNION ALL
    SELECT 'Singngat' AS name UNION ALL
    SELECT 'Henglep' AS name UNION ALL
    SELECT 'Thanlon' AS name UNION ALL
    SELECT 'Tipaimukh' AS name UNION ALL
    SELECT 'Saikot' AS name UNION ALL
    SELECT 'Tuibuong' AS name UNION ALL
    SELECT 'Songpi' AS name UNION ALL
    SELECT 'Behiang' AS name UNION ALL
    SELECT 'Sangaikot' AS name UNION ALL
    SELECT 'Tuilaphai' AS name UNION ALL
    SELECT 'Vangai Range' AS name UNION ALL
    SELECT 'Rengkai' AS name UNION ALL
    SELECT 'Bungmual' AS name UNION ALL
    SELECT 'Pearsonmun' AS name UNION ALL
    SELECT 'Salem Veng' AS name UNION ALL
    SELECT 'Tedim Road' AS name UNION ALL
    SELECT 'Hill Town' AS name UNION ALL
    SELECT 'Zenhang Lamka' AS name UNION ALL
    SELECT 'Thingkangphai' AS name UNION ALL
    SELECT 'D. Phailian' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Churachandpur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 4/16: Imphal East (URL slug: imphal-east)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Porompat' AS name UNION ALL
    SELECT 'Heingang' AS name UNION ALL
    SELECT 'Khurai' AS name UNION ALL
    SELECT 'Lamlong' AS name UNION ALL
    SELECT 'Koirengei' AS name UNION ALL
    SELECT 'Sawombung' AS name UNION ALL
    SELECT 'Lamlai' AS name UNION ALL
    SELECT 'Keirao' AS name UNION ALL
    SELECT 'Kongpal' AS name UNION ALL
    SELECT 'Wangkhei' AS name UNION ALL
    SELECT 'Chingarel' AS name UNION ALL
    SELECT 'Andro' AS name UNION ALL
    SELECT 'Yairipok' AS name UNION ALL
    SELECT 'Pangei' AS name UNION ALL
    SELECT 'Top' AS name UNION ALL
    SELECT 'Palace Compound' AS name UNION ALL
    SELECT 'Kongba' AS name UNION ALL
    SELECT 'Ayangpalli' AS name UNION ALL
    SELECT 'Khabeisoi' AS name UNION ALL
    SELECT 'Mantripukhri East' AS name UNION ALL
    SELECT 'Kangla Sangomsang' AS name UNION ALL
    SELECT 'Nongada' AS name UNION ALL
    SELECT 'Minuthong' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Imphal East'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 5/16: Imphal West (URL slug: imphal-west)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Lamphel' AS name UNION ALL
    SELECT 'Uripok' AS name UNION ALL
    SELECT 'Singjamei' AS name UNION ALL
    SELECT 'Kwakeithel' AS name UNION ALL
    SELECT 'Sagolband' AS name UNION ALL
    SELECT 'Thangmeiband' AS name UNION ALL
    SELECT 'Keishamthong' AS name UNION ALL
    SELECT 'Patsoi' AS name UNION ALL
    SELECT 'Wangoi' AS name UNION ALL
    SELECT 'Sekmai' AS name UNION ALL
    SELECT 'Mayang Imphal' AS name UNION ALL
    SELECT 'Phayeng' AS name UNION ALL
    SELECT 'Khurkhul' AS name UNION ALL
    SELECT 'Langjing' AS name UNION ALL
    SELECT 'Iroisemba' AS name UNION ALL
    SELECT 'Malom' AS name UNION ALL
    SELECT 'Imphal City' AS name UNION ALL
    SELECT 'Thangal Bazar' AS name UNION ALL
    SELECT 'Paona Bazar' AS name UNION ALL
    SELECT 'Lamphelpat' AS name UNION ALL
    SELECT 'Langol' AS name UNION ALL
    SELECT 'Tera' AS name UNION ALL
    SELECT 'Lamsang' AS name UNION ALL
    SELECT 'RIMS Road' AS name UNION ALL
    SELECT 'Babupara' AS name UNION ALL
    SELECT 'Airport Road' AS name UNION ALL
    SELECT 'Changangei' AS name UNION ALL
    SELECT 'Naoremthong' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Imphal West'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 6/16: Jiribam (URL slug: jiribam)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Jiribam' AS name UNION ALL
    SELECT 'Babupara' AS name UNION ALL
    SELECT 'Kalinagar' AS name UNION ALL
    SELECT 'Borobekra' AS name UNION ALL
    SELECT 'Chotobekra' AS name UNION ALL
    SELECT 'Bhutangkhal' AS name UNION ALL
    SELECT 'Hilghat' AS name UNION ALL
    SELECT 'Kamranga' AS name UNION ALL
    SELECT 'Dibong' AS name UNION ALL
    SELECT 'Sonapur' AS name UNION ALL
    SELECT 'Latingkhal' AS name UNION ALL
    SELECT 'Mahadevpur' AS name UNION ALL
    SELECT 'Uchathol' AS name UNION ALL
    SELECT 'Chandranathpur' AS name UNION ALL
    SELECT 'Kashimpur' AS name UNION ALL
    SELECT 'Durgapur' AS name UNION ALL
    SELECT 'Kadamtala' AS name UNION ALL
    SELECT 'Gularthor' AS name UNION ALL
    SELECT 'Bidyanagar' AS name UNION ALL
    SELECT 'Chandrapur' AS name UNION ALL
    SELECT 'Lalpani' AS name UNION ALL
    SELECT 'Jakuradhor' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Jiribam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 7/16: Kakching (URL slug: kakching)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Kakching' AS name UNION ALL
    SELECT 'Waikhong' AS name UNION ALL
    SELECT 'Pallel' AS name UNION ALL
    SELECT 'Sugnu' AS name UNION ALL
    SELECT 'Hiyanglam' AS name UNION ALL
    SELECT 'Wabagai' AS name UNION ALL
    SELECT 'Langmeidong' AS name UNION ALL
    SELECT 'Pungdongbam' AS name UNION ALL
    SELECT 'Keirak' AS name UNION ALL
    SELECT 'Kwarok' AS name UNION ALL
    SELECT 'Mayang Lamjao' AS name UNION ALL
    SELECT 'Sekmai' AS name UNION ALL
    SELECT 'Serou' AS name UNION ALL
    SELECT 'Umathel' AS name UNION ALL
    SELECT 'Kakching Khunou' AS name UNION ALL
    SELECT 'Kakching Bazar' AS name UNION ALL
    SELECT 'Irengband' AS name UNION ALL
    SELECT 'Sora' AS name UNION ALL
    SELECT 'Pangaltabi' AS name UNION ALL
    SELECT 'Tangjeng' AS name UNION ALL
    SELECT 'Kakching Wairi' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Kakching'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 8/16: Kamjong (URL slug: kamjong)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Kamjong' AS name UNION ALL
    SELECT 'Chassad' AS name UNION ALL
    SELECT 'Phungyar' AS name UNION ALL
    SELECT 'Kasom Khullen' AS name UNION ALL
    SELECT 'Ningthi' AS name UNION ALL
    SELECT 'Shingcha' AS name UNION ALL
    SELECT 'Maku' AS name UNION ALL
    SELECT 'Hangkou' AS name UNION ALL
    SELECT 'Chahong Khullen' AS name UNION ALL
    SELECT 'Chahong Khunou' AS name UNION ALL
    SELECT 'K.Langli' AS name UNION ALL
    SELECT 'R.Langli' AS name UNION ALL
    SELECT 'Yentam' AS name UNION ALL
    SELECT 'Pihang' AS name UNION ALL
    SELECT 'Sampui' AS name UNION ALL
    SELECT 'Kamjong HQ' AS name UNION ALL
    SELECT 'Sahamphung' AS name UNION ALL
    SELECT 'Kangpat' AS name UNION ALL
    SELECT 'Bungpa' AS name UNION ALL
    SELECT 'Shakok' AS name UNION ALL
    SELECT 'Grihang' AS name UNION ALL
    SELECT 'Nambashi' AS name UNION ALL
    SELECT 'Chadong' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Kamjong'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 9/16: Kangpokpi (URL slug: kangpokpi)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Kangpokpi' AS name UNION ALL
    SELECT 'Saparmeina' AS name UNION ALL
    SELECT 'Motbung' AS name UNION ALL
    SELECT 'Saikul' AS name UNION ALL
    SELECT 'Saitu' AS name UNION ALL
    SELECT 'Kangchup' AS name UNION ALL
    SELECT 'Gamgiphai' AS name UNION ALL
    SELECT 'Taphou' AS name UNION ALL
    SELECT 'Leimakhong' AS name UNION ALL
    SELECT 'K. Songlung' AS name UNION ALL
    SELECT 'Keithelmanbi' AS name UNION ALL
    SELECT 'Irang' AS name UNION ALL
    SELECT 'Kalapahar' AS name UNION ALL
    SELECT 'Charhajare' AS name UNION ALL
    SELECT 'New Keithelmanbi' AS name UNION ALL
    SELECT 'Kangpokpi Bazar' AS name UNION ALL
    SELECT 'Saitu-Gamphazol' AS name UNION ALL
    SELECT 'Bungte Chiru' AS name UNION ALL
    SELECT 'Leikop' AS name UNION ALL
    SELECT 'Koubru Foothills' AS name UNION ALL
    SELECT 'Champhai' AS name UNION ALL
    SELECT 'Twilang Area' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Kangpokpi'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 10/16: Noney (URL slug: noney)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Noney' AS name UNION ALL
    SELECT 'Longmai' AS name UNION ALL
    SELECT 'Khumji' AS name UNION ALL
    SELECT 'Khoupum' AS name UNION ALL
    SELECT 'Haochong' AS name UNION ALL
    SELECT 'Nungba' AS name UNION ALL
    SELECT 'Makhuam' AS name UNION ALL
    SELECT 'Awangkhul' AS name UNION ALL
    SELECT 'Taobam' AS name UNION ALL
    SELECT 'Lukhambi' AS name UNION ALL
    SELECT 'Khongshang' AS name UNION ALL
    SELECT 'Oinamlong' AS name UNION ALL
    SELECT 'Lhangnom' AS name UNION ALL
    SELECT 'Leingangpokpi' AS name UNION ALL
    SELECT 'Khoupum Valley' AS name UNION ALL
    SELECT 'Rengpang' AS name UNION ALL
    SELECT 'Tupul' AS name UNION ALL
    SELECT 'Tupul Railway Area' AS name UNION ALL
    SELECT 'Rangkhung' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Noney'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 11/16: Pherzawl (URL slug: pherzawl)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Pherzawl' AS name UNION ALL
    SELECT 'Parbung' AS name UNION ALL
    SELECT 'Thanlon' AS name UNION ALL
    SELECT 'Tipaimukh' AS name UNION ALL
    SELECT 'Vangai Range' AS name UNION ALL
    SELECT 'Senvon' AS name UNION ALL
    SELECT 'Lungthul' AS name UNION ALL
    SELECT 'Phaitol' AS name UNION ALL
    SELECT 'Tuilaphai' AS name UNION ALL
    SELECT 'Sipuikawn' AS name UNION ALL
    SELECT 'Sartuinek' AS name UNION ALL
    SELECT 'Paldai' AS name UNION ALL
    SELECT 'Mualnuam' AS name UNION ALL
    SELECT 'Pherzawl HQ' AS name UNION ALL
    SELECT 'Damdiei' AS name UNION ALL
    SELECT 'Taithu' AS name UNION ALL
    SELECT 'Tulpui' AS name UNION ALL
    SELECT 'Sibapurikhal' AS name UNION ALL
    SELECT 'Lungthulien' AS name UNION ALL
    SELECT 'Rovakot' AS name UNION ALL
    SELECT 'Leisen' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Pherzawl'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 12/16: Senapati (URL slug: senapati)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Senapati' AS name UNION ALL
    SELECT 'Mao' AS name UNION ALL
    SELECT 'Tadubi' AS name UNION ALL
    SELECT 'Maram' AS name UNION ALL
    SELECT 'Paomata' AS name UNION ALL
    SELECT 'Purul' AS name UNION ALL
    SELECT 'Willong' AS name UNION ALL
    SELECT 'Makhan' AS name UNION ALL
    SELECT 'Liyai' AS name UNION ALL
    SELECT 'Oinam' AS name UNION ALL
    SELECT 'Koide' AS name UNION ALL
    SELECT 'Phaibung' AS name UNION ALL
    SELECT 'Song Song' AS name UNION ALL
    SELECT 'Chakumai' AS name UNION ALL
    SELECT 'Kathikho' AS name UNION ALL
    SELECT 'Makhel' AS name UNION ALL
    SELECT 'Taphou' AS name UNION ALL
    SELECT 'Saranamai' AS name UNION ALL
    SELECT 'Mao Gate' AS name UNION ALL
    SELECT 'Maram Centre' AS name UNION ALL
    SELECT 'Karong' AS name UNION ALL
    SELECT 'Tungjoy' AS name UNION ALL
    SELECT 'Chilivai' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Senapati'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 13/16: Tamenglong (URL slug: tamenglong)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Tamenglong' AS name UNION ALL
    SELECT 'Tamei' AS name UNION ALL
    SELECT 'Tousem' AS name UNION ALL
    SELECT 'Khongsang' AS name UNION ALL
    SELECT 'Nungba' AS name UNION ALL
    SELECT 'Tamenglong Khunou' AS name UNION ALL
    SELECT 'Bhalok' AS name UNION ALL
    SELECT 'Dailong' AS name UNION ALL
    SELECT 'Inem' AS name UNION ALL
    SELECT 'Kambiron' AS name UNION ALL
    SELECT 'Makhuam' AS name UNION ALL
    SELECT 'Tamei Khunou' AS name UNION ALL
    SELECT 'New Tamenglong' AS name UNION ALL
    SELECT 'Barak' AS name UNION ALL
    SELECT 'Tamenglong HQ' AS name UNION ALL
    SELECT 'Sibilong' AS name UNION ALL
    SELECT 'Phalong' AS name UNION ALL
    SELECT 'Inrianglu' AS name UNION ALL
    SELECT 'Kuilong' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Tamenglong'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 14/16: Tengnoupal (URL slug: tengnoupal)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Tengnoupal' AS name UNION ALL
    SELECT 'Moreh' AS name UNION ALL
    SELECT 'Pallel' AS name UNION ALL
    SELECT 'Machi' AS name UNION ALL
    SELECT 'Khudengthabi' AS name UNION ALL
    SELECT 'Molcham' AS name UNION ALL
    SELECT 'Saivom' AS name UNION ALL
    SELECT 'Kottlen' AS name UNION ALL
    SELECT 'Chandel Christian' AS name UNION ALL
    SELECT 'Yangoubung' AS name UNION ALL
    SELECT 'Khangbarol' AS name UNION ALL
    SELECT 'Sita' AS name UNION ALL
    SELECT 'T Minou' AS name UNION ALL
    SELECT 'T Molen' AS name UNION ALL
    SELECT 'T Khunou' AS name UNION ALL
    SELECT 'Moreh Town' AS name UNION ALL
    SELECT 'Moreh Bazar' AS name UNION ALL
    SELECT 'Sinam' AS name UNION ALL
    SELECT 'Kwatha' AS name UNION ALL
    SELECT 'Chamol' AS name UNION ALL
    SELECT 'Lokchao' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Tengnoupal'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 15/16: Thoubal (URL slug: thoubal)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Thoubal' AS name UNION ALL
    SELECT 'Lilong' AS name UNION ALL
    SELECT 'Yairipok' AS name UNION ALL
    SELECT 'Wangjing' AS name UNION ALL
    SELECT 'Khangabok' AS name UNION ALL
    SELECT 'Heirok' AS name UNION ALL
    SELECT 'Kakching Khunou' AS name UNION ALL
    SELECT 'Thoubal Haokha' AS name UNION ALL
    SELECT 'Thoubal Kshetri Leikai' AS name UNION ALL
    SELECT 'Charangpat' AS name UNION ALL
    SELECT 'Khongjom' AS name UNION ALL
    SELECT 'Leishangthem' AS name UNION ALL
    SELECT 'Tentha' AS name UNION ALL
    SELECT 'Athokpam' AS name UNION ALL
    SELECT 'Thoubal Ningombam' AS name UNION ALL
    SELECT 'Thoubal Bazar' AS name UNION ALL
    SELECT 'Thoubal Achouba' AS name UNION ALL
    SELECT 'Lilong Bazar' AS name UNION ALL
    SELECT 'Wangkhem' AS name UNION ALL
    SELECT 'Sangaiyumpham' AS name UNION ALL
    SELECT 'Haoreibi' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Thoubal'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

-- ------------------------------------------------------------------------------
-- District 16/16: Ukhrul (URL slug: ukhrul)
-- ------------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ukhrul' AS name UNION ALL
    SELECT 'Hunphun' AS name UNION ALL
    SELECT 'Jessami' AS name UNION ALL
    SELECT 'Kamjong' AS name UNION ALL
    SELECT 'Litan' AS name UNION ALL
    SELECT 'Shangshak' AS name UNION ALL
    SELECT 'Sirarakhong' AS name UNION ALL
    SELECT 'Phungyar' AS name UNION ALL
    SELECT 'Chingai' AS name UNION ALL
    SELECT 'Somdal' AS name UNION ALL
    SELECT 'Longpi' AS name UNION ALL
    SELECT 'Hundung' AS name UNION ALL
    SELECT 'Lambui' AS name UNION ALL
    SELECT 'Kachouphung' AS name UNION ALL
    SELECT 'Chatric' AS name UNION ALL
    SELECT 'Ringui' AS name UNION ALL
    SELECT 'Teinem' AS name UNION ALL
    SELECT 'Zalenbung' AS name UNION ALL
    SELECT 'Viewland' AS name UNION ALL
    SELECT 'Wino Bazar' AS name UNION ALL
    SELECT 'Phungreitang' AS name UNION ALL
    SELECT 'Shirui' AS name UNION ALL
    SELECT 'Tolloi' AS name UNION ALL
    SELECT 'Ngaiching' AS name UNION ALL
    SELECT 'Phangrei' AS name UNION ALL
    SELECT 'Kharasom' AS name
) l
WHERE (s.code = 'MN' OR s.name = 'Manipur')
  AND d.name = 'Ukhrul'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND LOWER(existing.name) = LOWER(l.name)
  );

SET FOREIGN_KEY_CHECKS = 1;
