-- ==========================================================================
-- UnlockRentals - Complete Meghalaya Districts & Localities Setup
-- Covers all 12 Districts and 329 Localities
-- ==========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure State Exists
INSERT INTO `states` (`name`, `code`)
SELECT 'Meghalaya', 'ML'
WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'ML' OR `name` = 'Meghalaya');

-- 2. Ensure Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT s.id, d.name
FROM `states` s
CROSS JOIN (
    SELECT 'East Garo Hills' AS name UNION ALL
    SELECT 'East Jaintia Hills' AS name UNION ALL
    SELECT 'East Khasi Hills' AS name UNION ALL
    SELECT 'Eastern West Khasi Hills' AS name UNION ALL
    SELECT 'North Garo Hills' AS name UNION ALL
    SELECT 'Ri Bhoi' AS name UNION ALL
    SELECT 'South Garo Hills' AS name UNION ALL
    SELECT 'South West Garo Hills' AS name UNION ALL
    SELECT 'South West Khasi Hills' AS name UNION ALL
    SELECT 'West Garo Hills' AS name UNION ALL
    SELECT 'West Jaintia Hills' AS name UNION ALL
    SELECT 'West Khasi Hills' AS name
) d
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND NOT EXISTS (
      SELECT 1 FROM `districts` existing 
      WHERE existing.state_id = s.id AND existing.name = d.name
  );

-- 3. Insert Localities District-by-District
-- --------------------------------------------------------------------------
-- District: East Garo Hills (20 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adokgre-side areas' AS name UNION ALL
    SELECT 'Chisobibra' AS name UNION ALL
    SELECT 'Dambo Rongjeng' AS name UNION ALL
    SELECT 'Daribokgre' AS name UNION ALL
    SELECT 'Mendal' AS name UNION ALL
    SELECT 'Nangalbibra-side corridor' AS name UNION ALL
    SELECT 'Nengmandalgre' AS name UNION ALL
    SELECT 'Rongjeng' AS name UNION ALL
    SELECT 'Rongjeng Town' AS name UNION ALL
    SELECT 'Rongsak' AS name UNION ALL
    SELECT 'Samanda' AS name UNION ALL
    SELECT 'Samanda Road' AS name UNION ALL
    SELECT 'Songsak' AS name UNION ALL
    SELECT 'Songsak Town' AS name UNION ALL
    SELECT 'Songsang' AS name UNION ALL
    SELECT 'Williamnagar' AS name UNION ALL
    SELECT 'Williamnagar College Area' AS name UNION ALL
    SELECT 'Williamnagar Market' AS name UNION ALL
    SELECT 'Williamnagar Town' AS name UNION ALL
    SELECT 'Williamnagar–Tura Road corridor' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'East Garo Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: East Jaintia Hills (18 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bataw' AS name UNION ALL
    SELECT 'Byrnihat/Jaintia corridor' AS name UNION ALL
    SELECT 'East Jaintia Hills mining belt' AS name UNION ALL
    SELECT 'Khliehriat' AS name UNION ALL
    SELECT 'Khliehriat Market' AS name UNION ALL
    SELECT 'Khliehriat Town' AS name UNION ALL
    SELECT 'Khliehriat–Dawki Road' AS name UNION ALL
    SELECT 'Lad Rymbai' AS name UNION ALL
    SELECT 'Ladrymbai' AS name UNION ALL
    SELECT 'Lumshnong' AS name UNION ALL
    SELECT 'Lumshnong Industrial Area' AS name UNION ALL
    SELECT 'Malidor' AS name UNION ALL
    SELECT 'NH-6 corridor' AS name UNION ALL
    SELECT 'Ratacherra' AS name UNION ALL
    SELECT 'Saipung' AS name UNION ALL
    SELECT 'Sumer' AS name UNION ALL
    SELECT 'Sutnga' AS name UNION ALL
    SELECT 'Wapung' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'East Jaintia Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: East Khasi Hills (84 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bara Bazar' AS name UNION ALL
    SELECT 'Cherrapunji' AS name UNION ALL
    SELECT 'Cherrapunji (Sohra)' AS name UNION ALL
    SELECT 'Cleve Colony' AS name UNION ALL
    SELECT 'Dawki (Umngot River)' AS name UNION ALL
    SELECT 'Demseiniong' AS name UNION ALL
    SELECT 'Dhankheti' AS name UNION ALL
    SELECT 'Diengiong' AS name UNION ALL
    SELECT 'European Ward' AS name UNION ALL
    SELECT 'IIM Shillong Area' AS name UNION ALL
    SELECT 'Iewduh' AS name UNION ALL
    SELECT 'Jaiaw' AS name UNION ALL
    SELECT 'Jaikaw' AS name UNION ALL
    SELECT 'Jail Road' AS name UNION ALL
    SELECT 'Kench\'s Trace' AS name UNION ALL
    SELECT 'Khatarshnong' AS name UNION ALL
    SELECT 'Laban' AS name UNION ALL
    SELECT 'Lachumiere' AS name UNION ALL
    SELECT 'Laitkor' AS name UNION ALL
    SELECT 'Laitkroh' AS name UNION ALL
    SELECT 'Laitkynsew' AS name UNION ALL
    SELECT 'Laitumkhrah' AS name UNION ALL
    SELECT 'Lawsohtun' AS name UNION ALL
    SELECT 'Lumdiengri' AS name UNION ALL
    SELECT 'Lumshyiap' AS name UNION ALL
    SELECT 'Lyngkien' AS name UNION ALL
    SELECT 'Madanryting' AS name UNION ALL
    SELECT 'Malki' AS name UNION ALL
    SELECT 'Mawblei' AS name UNION ALL
    SELECT 'Mawdiangdiang' AS name UNION ALL
    SELECT 'Mawiong' AS name UNION ALL
    SELECT 'Mawkhar' AS name UNION ALL
    SELECT 'Mawkynrew' AS name UNION ALL
    SELECT 'Mawlai' AS name UNION ALL
    SELECT 'Mawlynnong (Cleanest Village)' AS name UNION ALL
    SELECT 'Mawmluh' AS name UNION ALL
    SELECT 'Mawngap' AS name UNION ALL
    SELECT 'Mawpat' AS name UNION ALL
    SELECT 'Mawphlang' AS name UNION ALL
    SELECT 'Mawprem' AS name UNION ALL
    SELECT 'Mawroh' AS name UNION ALL
    SELECT 'Mawryngkneng' AS name UNION ALL
    SELECT 'Mawsmai' AS name UNION ALL
    SELECT 'Mawsynram' AS name UNION ALL
    SELECT 'Mawsynram (Wettest Place)' AS name UNION ALL
    SELECT 'Mawsynram Market' AS name UNION ALL
    SELECT 'Motinagar' AS name UNION ALL
    SELECT 'Mylliem' AS name UNION ALL
    SELECT 'NEHU Area' AS name UNION ALL
    SELECT 'New Shillong Township (Mawdiangdiang)' AS name UNION ALL
    SELECT 'Nongkynrih' AS name UNION ALL
    SELECT 'Nongmensong' AS name UNION ALL
    SELECT 'Nongrah' AS name UNION ALL
    SELECT 'Nongriat' AS name UNION ALL
    SELECT 'Nongsohphoh' AS name UNION ALL
    SELECT 'Nongthymmai' AS name UNION ALL
    SELECT 'Oakland' AS name UNION ALL
    SELECT 'Paltan Bazar' AS name UNION ALL
    SELECT 'Police Bazar' AS name UNION ALL
    SELECT 'Police Bazar (PB)' AS name UNION ALL
    SELECT 'Polo' AS name UNION ALL
    SELECT 'Polo Grounds Area' AS name UNION ALL
    SELECT 'Pomlum' AS name UNION ALL
    SELECT 'Pynthorbah' AS name UNION ALL
    SELECT 'Pynursla' AS name UNION ALL
    SELECT 'Pynursla Town' AS name UNION ALL
    SELECT 'Riatbah' AS name UNION ALL
    SELECT 'Rilbong' AS name UNION ALL
    SELECT 'Risa Colony' AS name UNION ALL
    SELECT 'Rynjah' AS name UNION ALL
    SELECT 'Shella' AS name UNION ALL
    SELECT 'Shella Bholaganj' AS name UNION ALL
    SELECT 'Shillong' AS name UNION ALL
    SELECT 'Shillong City' AS name UNION ALL
    SELECT 'Smit' AS name UNION ALL
    SELECT 'Sohiong' AS name UNION ALL
    SELECT 'Sohra' AS name UNION ALL
    SELECT 'Sohra Market' AS name UNION ALL
    SELECT 'Sohra Town' AS name UNION ALL
    SELECT 'Tyrna' AS name UNION ALL
    SELECT 'Umpling' AS name UNION ALL
    SELECT 'Umroi Road' AS name UNION ALL
    SELECT 'Umtyrniuh' AS name UNION ALL
    SELECT 'Upper Shillong' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'East Khasi Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Eastern West Khasi Hills (15 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Khadphrang' AS name UNION ALL
    SELECT 'Kyllang Rock area' AS name UNION ALL
    SELECT 'Mairang' AS name UNION ALL
    SELECT 'Mairang Civil Hospital Area' AS name UNION ALL
    SELECT 'Mairang College Area' AS name UNION ALL
    SELECT 'Mairang Market' AS name UNION ALL
    SELECT 'Mairang Town' AS name UNION ALL
    SELECT 'Mairang surrounding villages' AS name UNION ALL
    SELECT 'Mairang–Nongstoin Road' AS name UNION ALL
    SELECT 'Mairang–Shillong Road' AS name UNION ALL
    SELECT 'Mawthadraishan' AS name UNION ALL
    SELECT 'Mawthadraishan Town' AS name UNION ALL
    SELECT 'Mawthadraishan rural belt' AS name UNION ALL
    SELECT 'Nongkhlaw-side areas' AS name UNION ALL
    SELECT 'Nongsteng' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'Eastern West Khasi Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: North Garo Hills (19 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adokgre' AS name UNION ALL
    SELECT 'Adokgre Town' AS name UNION ALL
    SELECT 'Bajengdoba' AS name UNION ALL
    SELECT 'Bajengdoba Road' AS name UNION ALL
    SELECT 'Bajengdoba Town' AS name UNION ALL
    SELECT 'Chandigre' AS name UNION ALL
    SELECT 'Dainadubi' AS name UNION ALL
    SELECT 'Damra-side areas' AS name UNION ALL
    SELECT 'Kharkutta' AS name UNION ALL
    SELECT 'Kharkutta Road' AS name UNION ALL
    SELECT 'Kharkutta Town' AS name UNION ALL
    SELECT 'Mendal' AS name UNION ALL
    SELECT 'Mendipathar (Railway Station Area)' AS name UNION ALL
    SELECT 'North Garo Hills rural belt' AS name UNION ALL
    SELECT 'Resubelpara' AS name UNION ALL
    SELECT 'Resubelpara Market' AS name UNION ALL
    SELECT 'Resubelpara Town' AS name UNION ALL
    SELECT 'Resubelpara–Tura Road' AS name UNION ALL
    SELECT 'Rongjeng-side areas' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'North Garo Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Ri Bhoi (29 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Bhoirymbong' AS name UNION ALL
    SELECT 'Byrnihat' AS name UNION ALL
    SELECT 'Byrnihat (Industrial Hub)' AS name UNION ALL
    SELECT 'Byrnihat Industrial Area' AS name UNION ALL
    SELECT 'Jirang' AS name UNION ALL
    SELECT 'Jowai Road/NH-6 corridor' AS name UNION ALL
    SELECT 'Khanapara Border' AS name UNION ALL
    SELECT 'Killing' AS name UNION ALL
    SELECT 'Kyrdemkulai' AS name UNION ALL
    SELECT 'Marngar' AS name UNION ALL
    SELECT 'Mawhati' AS name UNION ALL
    SELECT 'Nongkhrah' AS name UNION ALL
    SELECT 'Nongpoh' AS name UNION ALL
    SELECT 'Nongpoh Bypass' AS name UNION ALL
    SELECT 'Nongpoh College Area' AS name UNION ALL
    SELECT 'Nongpoh Market' AS name UNION ALL
    SELECT 'Nongpoh Town' AS name UNION ALL
    SELECT 'Nongpoh–Shillong Road' AS name UNION ALL
    SELECT 'Patharkhmah' AS name UNION ALL
    SELECT 'Umiam' AS name UNION ALL
    SELECT 'Umiam (Barapani Lake Area)' AS name UNION ALL
    SELECT 'Umiam Industrial Area' AS name UNION ALL
    SELECT 'Umiam Lake area' AS name UNION ALL
    SELECT 'Umiam–Byrnihat corridor' AS name UNION ALL
    SELECT 'Umling' AS name UNION ALL
    SELECT 'Umling Town' AS name UNION ALL
    SELECT 'Umran' AS name UNION ALL
    SELECT 'Umsning' AS name UNION ALL
    SELECT 'Umsning Town' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'Ri Bhoi'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: South Garo Hills (22 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baghmara' AS name UNION ALL
    SELECT 'Baghmara Civil Hospital Area' AS name UNION ALL
    SELECT 'Baghmara Market' AS name UNION ALL
    SELECT 'Baghmara Town' AS name UNION ALL
    SELECT 'Balpakram National Park Area' AS name UNION ALL
    SELECT 'Balpakram National Park area' AS name UNION ALL
    SELECT 'Bolsalgre' AS name UNION ALL
    SELECT 'Chokpot' AS name UNION ALL
    SELECT 'Chokpot Town' AS name UNION ALL
    SELECT 'Gasuapara' AS name UNION ALL
    SELECT 'Gasuapara Town' AS name UNION ALL
    SELECT 'Mahadeo' AS name UNION ALL
    SELECT 'Nangalbibra-side areas' AS name UNION ALL
    SELECT 'Nokrek-side corridor' AS name UNION ALL
    SELECT 'Rewak' AS name UNION ALL
    SELECT 'Rongara' AS name UNION ALL
    SELECT 'Ronggara' AS name UNION ALL
    SELECT 'Ronggara Town' AS name UNION ALL
    SELECT 'Siju' AS name UNION ALL
    SELECT 'Siju (Cave Area)' AS name UNION ALL
    SELECT 'Siju Caves area' AS name UNION ALL
    SELECT 'South Garo Hills forest belt' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'South Garo Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: South West Garo Hills (18 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ampati' AS name UNION ALL
    SELECT 'Ampati Civil Hospital Area' AS name UNION ALL
    SELECT 'Ampati Market' AS name UNION ALL
    SELECT 'Ampati Town' AS name UNION ALL
    SELECT 'Bangladesh-border settlements' AS name UNION ALL
    SELECT 'Betasing' AS name UNION ALL
    SELECT 'Betasing Town' AS name UNION ALL
    SELECT 'Boldamgre' AS name UNION ALL
    SELECT 'Damalgre' AS name UNION ALL
    SELECT 'Garobadha-side areas' AS name UNION ALL
    SELECT 'Mahendraganj' AS name UNION ALL
    SELECT 'Nangalbibra Road corridor' AS name UNION ALL
    SELECT 'Phulbari Road corridor' AS name UNION ALL
    SELECT 'Purakhasia' AS name UNION ALL
    SELECT 'Purakhasia Town' AS name UNION ALL
    SELECT 'Tikrikilla-side border areas' AS name UNION ALL
    SELECT 'Zikzak' AS name UNION ALL
    SELECT 'Zikzak Town' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'South West Garo Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: South West Khasi Hills (17 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Langrin' AS name UNION ALL
    SELECT 'Mawkyrwat' AS name UNION ALL
    SELECT 'Mawkyrwat Market' AS name UNION ALL
    SELECT 'Mawkyrwat Town' AS name UNION ALL
    SELECT 'Mawkyrwat–Ranikor Road' AS name UNION ALL
    SELECT 'Mawsaw' AS name UNION ALL
    SELECT 'Nongsteng' AS name UNION ALL
    SELECT 'Phlangdiloin' AS name UNION ALL
    SELECT 'Photjaud' AS name UNION ALL
    SELECT 'Rangblang' AS name UNION ALL
    SELECT 'Rangblang and Ranikor' AS name UNION ALL
    SELECT 'Ranikor' AS name UNION ALL
    SELECT 'Ranikor Market' AS name UNION ALL
    SELECT 'Ranikor Town' AS name UNION ALL
    SELECT 'Ranikor–Bangladesh border areas' AS name UNION ALL
    SELECT 'Wahkaji' AS name UNION ALL
    SELECT 'rural localities around Mawkyrwat' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'South West Khasi Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: West Garo Hills (37 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ampati Road corridor' AS name UNION ALL
    SELECT 'Araimile' AS name UNION ALL
    SELECT 'Arapetta' AS name UNION ALL
    SELECT 'Babupara' AS name UNION ALL
    SELECT 'Batabari' AS name UNION ALL
    SELECT 'Chandmari' AS name UNION ALL
    SELECT 'Chandmari Tura' AS name UNION ALL
    SELECT 'Chasingre' AS name UNION ALL
    SELECT 'Dadenggre' AS name UNION ALL
    SELECT 'Dakopgre' AS name UNION ALL
    SELECT 'Dalu' AS name UNION ALL
    SELECT 'Dalu (Border Town)' AS name UNION ALL
    SELECT 'Demdema' AS name UNION ALL
    SELECT 'Dobasipara' AS name UNION ALL
    SELECT 'Gambegre' AS name UNION ALL
    SELECT 'Garobadha' AS name UNION ALL
    SELECT 'Hawakhana' AS name UNION ALL
    SELECT 'Jengjal' AS name UNION ALL
    SELECT 'Lower Tura' AS name UNION ALL
    SELECT 'Mahendraganj Road corridor' AS name UNION ALL
    SELECT 'Matchakolgre' AS name UNION ALL
    SELECT 'New Tura' AS name UNION ALL
    SELECT 'Phulbari' AS name UNION ALL
    SELECT 'Raksamgre' AS name UNION ALL
    SELECT 'Ringrey' AS name UNION ALL
    SELECT 'Rongkhon' AS name UNION ALL
    SELECT 'Rongram' AS name UNION ALL
    SELECT 'Rongrong' AS name UNION ALL
    SELECT 'Selsella' AS name UNION ALL
    SELECT 'Tikrikilla' AS name UNION ALL
    SELECT 'Tura' AS name UNION ALL
    SELECT 'Tura Bazaar' AS name UNION ALL
    SELECT 'Tura Civil Hospital Area' AS name UNION ALL
    SELECT 'Tura College Area' AS name UNION ALL
    SELECT 'Tura Town' AS name UNION ALL
    SELECT 'Upper Tura' AS name UNION ALL
    SELECT 'Walbakgre' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'West Garo Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: West Jaintia Hills (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amlarem' AS name UNION ALL
    SELECT 'Amlarem Town' AS name UNION ALL
    SELECT 'Dawki-side corridor' AS name UNION ALL
    SELECT 'Ialong' AS name UNION ALL
    SELECT 'Iawmusiang' AS name UNION ALL
    SELECT 'Iawmusiang Market' AS name UNION ALL
    SELECT 'Jowai' AS name UNION ALL
    SELECT 'Jowai Civil Hospital Area' AS name UNION ALL
    SELECT 'Jowai College Area' AS name UNION ALL
    SELECT 'Jowai Market' AS name UNION ALL
    SELECT 'Jowai Town' AS name UNION ALL
    SELECT 'Jowai–Dawki Road' AS name UNION ALL
    SELECT 'Jowai–Muktapur Road' AS name UNION ALL
    SELECT 'Jowai–Shillong Road' AS name UNION ALL
    SELECT 'Khliehriat Road corridor' AS name UNION ALL
    SELECT 'Laskein' AS name UNION ALL
    SELECT 'Mowkaiaw' AS name UNION ALL
    SELECT 'Namdong' AS name UNION ALL
    SELECT 'Nartiang' AS name UNION ALL
    SELECT 'Nartiang (Monoliths)' AS name UNION ALL
    SELECT 'Nartiang Temple Area' AS name UNION ALL
    SELECT 'Raliang' AS name UNION ALL
    SELECT 'Shangpung' AS name UNION ALL
    SELECT 'Syntu Ksiar' AS name UNION ALL
    SELECT 'Thadlaskein' AS name UNION ALL
    SELECT 'Tyrshi Falls area' AS name UNION ALL
    SELECT 'Ummulong' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'West Jaintia Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: West Khasi Hills (23 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Kynshi-side areas' AS name UNION ALL
    SELECT 'Langrin Road' AS name UNION ALL
    SELECT 'Mairang Border' AS name UNION ALL
    SELECT 'Mairang-side areas' AS name UNION ALL
    SELECT 'Markasa' AS name UNION ALL
    SELECT 'Mawdoh' AS name UNION ALL
    SELECT 'Mawshynrut' AS name UNION ALL
    SELECT 'Mawthadraishan border areas' AS name UNION ALL
    SELECT 'Nonglang' AS name UNION ALL
    SELECT 'Nongspung' AS name UNION ALL
    SELECT 'Nongstoin' AS name UNION ALL
    SELECT 'Nongstoin Civil Hospital Area' AS name UNION ALL
    SELECT 'Nongstoin College Area' AS name UNION ALL
    SELECT 'Nongstoin Market' AS name UNION ALL
    SELECT 'Nongstoin Town' AS name UNION ALL
    SELECT 'Nongstoin–Mairang Road' AS name UNION ALL
    SELECT 'Nongstoin–Mawshynrut Road' AS name UNION ALL
    SELECT 'Pyndengumiong' AS name UNION ALL
    SELECT 'Rambrai' AS name UNION ALL
    SELECT 'Rambrai Town' AS name UNION ALL
    SELECT 'Ri Muliang' AS name UNION ALL
    SELECT 'Shallang' AS name UNION ALL
    SELECT 'Shallang Town' AS name
) l
WHERE (s.code = 'ML' OR s.name = 'Meghalaya')
  AND d.name = 'West Khasi Hills'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

SET FOREIGN_KEY_CHECKS = 1;
