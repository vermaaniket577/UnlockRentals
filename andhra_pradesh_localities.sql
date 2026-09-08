-- ==========================================================================
-- UnlockRentals - Complete Andhra Pradesh Districts & Localities Setup
-- Covers all 28 Districts and 1195 Localities
-- ==========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure State Exists
INSERT INTO `states` (`name`, `code`)
SELECT 'Andhra Pradesh', 'AP'
WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'AP' OR `name` = 'Andhra Pradesh');

-- 2. Ensure Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT s.id, d.name
FROM `states` s
CROSS JOIN (
    SELECT 'Alluri Sitharama Raju' AS name UNION ALL
    SELECT 'Anakapalli' AS name UNION ALL
    SELECT 'Ananthapuramu' AS name UNION ALL
    SELECT 'Annamayya' AS name UNION ALL
    SELECT 'Bapatla' AS name UNION ALL
    SELECT 'Chittoor' AS name UNION ALL
    SELECT 'Dr. B. R. Ambedkar Konaseema' AS name UNION ALL
    SELECT 'East Godavari' AS name UNION ALL
    SELECT 'Eluru' AS name UNION ALL
    SELECT 'Guntur' AS name UNION ALL
    SELECT 'Kakinada' AS name UNION ALL
    SELECT 'Krishna' AS name UNION ALL
    SELECT 'Kurnool' AS name UNION ALL
    SELECT 'Markapuram' AS name UNION ALL
    SELECT 'NTR' AS name UNION ALL
    SELECT 'Nandyal' AS name UNION ALL
    SELECT 'Palnadu' AS name UNION ALL
    SELECT 'Parvathipuram Manyam' AS name UNION ALL
    SELECT 'Polavaram' AS name UNION ALL
    SELECT 'Prakasam' AS name UNION ALL
    SELECT 'Sri Potti Sriramulu Nellore' AS name UNION ALL
    SELECT 'Sri Sathya Sai' AS name UNION ALL
    SELECT 'Srikakulam' AS name UNION ALL
    SELECT 'Tirupati' AS name UNION ALL
    SELECT 'Visakhapatnam' AS name UNION ALL
    SELECT 'Vizianagaram' AS name UNION ALL
    SELECT 'West Godavari' AS name UNION ALL
    SELECT 'YSR Kadapa' AS name
) d
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND NOT EXISTS (
      SELECT 1 FROM `districts` existing 
      WHERE existing.state_id = s.id AND existing.name = d.name
  );

-- 3. Insert Localities District-by-District
-- --------------------------------------------------------------------------
-- District: Alluri Sitharama Raju (44 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ananthagiri' AS name UNION ALL
    SELECT 'Ananthagiri Road' AS name UNION ALL
    SELECT 'Araku Railway Station Area' AS name UNION ALL
    SELECT 'Araku Road' AS name UNION ALL
    SELECT 'Araku Town' AS name UNION ALL
    SELECT 'Araku Valley' AS name UNION ALL
    SELECT 'Balimela Road area' AS name UNION ALL
    SELECT 'Borra' AS name UNION ALL
    SELECT 'Borra Caves Area' AS name UNION ALL
    SELECT 'Bus Stand Area' AS name UNION ALL
    SELECT 'Chaparai' AS name UNION ALL
    SELECT 'Chintapalle' AS name UNION ALL
    SELECT 'Dhara Konda' AS name UNION ALL
    SELECT 'Downuru' AS name UNION ALL
    SELECT 'Dumbriguda' AS name UNION ALL
    SELECT 'G.K. Veedhi' AS name UNION ALL
    SELECT 'G.K. Veedhi Road' AS name UNION ALL
    SELECT 'G.Madugula' AS name UNION ALL
    SELECT 'G.Madugula Road' AS name UNION ALL
    SELECT 'Hukumpeta' AS name UNION ALL
    SELECT 'ITDA Area' AS name UNION ALL
    SELECT 'Korukonda' AS name UNION ALL
    SELECT 'Kothapalle' AS name UNION ALL
    SELECT 'Kothuru' AS name UNION ALL
    SELECT 'Koyyuru' AS name UNION ALL
    SELECT 'Lammasingi' AS name UNION ALL
    SELECT 'Maredumilli' AS name UNION ALL
    SELECT 'Modakondamma Temple Area' AS name UNION ALL
    SELECT 'Munchingiputtu' AS name UNION ALL
    SELECT 'Nandigunta' AS name UNION ALL
    SELECT 'Paderu' AS name UNION ALL
    SELECT 'Paderu Market Area' AS name UNION ALL
    SELECT 'Padmapuram' AS name UNION ALL
    SELECT 'Pedabayalu' AS name UNION ALL
    SELECT 'Pedaguda' AS name UNION ALL
    SELECT 'Pedakonda' AS name UNION ALL
    SELECT 'Rajavommangi Road' AS name UNION ALL
    SELECT 'Rampachodavaram' AS name UNION ALL
    SELECT 'Sileru' AS name UNION ALL
    SELECT 'Sukuru' AS name UNION ALL
    SELECT 'Sunkarimetta' AS name UNION ALL
    SELECT 'Tajangi' AS name UNION ALL
    SELECT 'Tribal Museum Area' AS name UNION ALL
    SELECT 'Tribal Villages Area' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Alluri Sitharama Raju'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Anakapalli (59 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Akkireddypalem' AS name UNION ALL
    SELECT 'Anakapalle' AS name UNION ALL
    SELECT 'Anakapalli Town' AS name UNION ALL
    SELECT 'Atchutapuram' AS name UNION ALL
    SELECT 'Atchutapuram Industrial Area' AS name UNION ALL
    SELECT 'Brandix Area' AS name UNION ALL
    SELECT 'Butchayyapeta' AS name UNION ALL
    SELECT 'Cheedikada' AS name UNION ALL
    SELECT 'Chodavaram' AS name UNION ALL
    SELECT 'Devarapalle' AS name UNION ALL
    SELECT 'Dibbapalem' AS name UNION ALL
    SELECT 'Gangavaram' AS name UNION ALL
    SELECT 'Golugonda' AS name UNION ALL
    SELECT 'Haripuram' AS name UNION ALL
    SELECT 'Jawaharlal Nehru Pharma City' AS name UNION ALL
    SELECT 'Kasimkota' AS name UNION ALL
    SELECT 'Kasimkota Road' AS name UNION ALL
    SELECT 'Kinthali' AS name UNION ALL
    SELECT 'Kotauratla' AS name UNION ALL
    SELECT 'Lankelapalem' AS name UNION ALL
    SELECT 'Madugula' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Makavarapalem' AS name UNION ALL
    SELECT 'Market Area' AS name UNION ALL
    SELECT 'Munagapaka' AS name UNION ALL
    SELECT 'NH-16 Corridor' AS name UNION ALL
    SELECT 'Nagayyapalem' AS name UNION ALL
    SELECT 'Nakkapalle' AS name UNION ALL
    SELECT 'Narsipatnam' AS name UNION ALL
    SELECT 'Narsipatnam Road' AS name UNION ALL
    SELECT 'Narsipatnam Town' AS name UNION ALL
    SELECT 'Nathavaram' AS name UNION ALL
    SELECT 'Nehru Chowk Area' AS name UNION ALL
    SELECT 'Pappireddipalem' AS name UNION ALL
    SELECT 'Paravada' AS name UNION ALL
    SELECT 'Payakaraopeta' AS name UNION ALL
    SELECT 'Peda Boddepalli' AS name UNION ALL
    SELECT 'Pedda Jaggampeta' AS name UNION ALL
    SELECT 'Pharma City' AS name UNION ALL
    SELECT 'Pudimadaka' AS name UNION ALL
    SELECT 'RTC Complex Area' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramachandrapuram' AS name UNION ALL
    SELECT 'Rambilli' AS name UNION ALL
    SELECT 'Ravikamatham' AS name UNION ALL
    SELECT 'Relli Veedhi' AS name UNION ALL
    SELECT 'Rolugunta' AS name UNION ALL
    SELECT 'S.Rayavaram' AS name UNION ALL
    SELECT 'SEZ Area' AS name UNION ALL
    SELECT 'Sankaram' AS name UNION ALL
    SELECT 'Sarada Nagar' AS name UNION ALL
    SELECT 'Sarugudu' AS name UNION ALL
    SELECT 'Tamaram Road' AS name UNION ALL
    SELECT 'Thanam' AS name UNION ALL
    SELECT 'Town Area' AS name UNION ALL
    SELECT 'Vakapadu' AS name UNION ALL
    SELECT 'Yelamanchili' AS name UNION ALL
    SELECT 'Yelamanchili Road' AS name UNION ALL
    SELECT 'Yellamanchili' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Anakapalli'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Ananthapuramu (57 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Anantapur' AS name UNION ALL
    SELECT 'Anantapur Road' AS name UNION ALL
    SELECT 'Anantapur Town' AS name UNION ALL
    SELECT 'Aravind Nagar' AS name UNION ALL
    SELECT 'Ashok Nagar' AS name UNION ALL
    SELECT 'Atmakur' AS name UNION ALL
    SELECT 'Bellary Road' AS name UNION ALL
    SELECT 'Beluguppa' AS name UNION ALL
    SELECT 'Bommanahal' AS name UNION ALL
    SELECT 'Brahmasamudram' AS name UNION ALL
    SELECT 'Court Road' AS name UNION ALL
    SELECT 'D. Hirehal Road' AS name UNION ALL
    SELECT 'Dharmavaram' AS name UNION ALL
    SELECT 'Garladinne' AS name UNION ALL
    SELECT 'Gooty' AS name UNION ALL
    SELECT 'Gooty Fort Area' AS name UNION ALL
    SELECT 'Gooty Road' AS name UNION ALL
    SELECT 'Gummagatta' AS name UNION ALL
    SELECT 'Guntakal' AS name UNION ALL
    SELECT 'Kadiri Road' AS name UNION ALL
    SELECT 'Kalyandurg' AS name UNION ALL
    SELECT 'Kambadur' AS name UNION ALL
    SELECT 'Kanekal' AS name UNION ALL
    SELECT 'Kudair' AS name UNION ALL
    SELECT 'Kundurpi' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Maruthi Nagar' AS name UNION ALL
    SELECT 'Narpala' AS name UNION ALL
    SELECT 'Netaji Road' AS name UNION ALL
    SELECT 'New Guntakal' AS name UNION ALL
    SELECT 'New Town' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Pamidi' AS name UNION ALL
    SELECT 'Peddapappur' AS name UNION ALL
    SELECT 'Peddavadugur' AS name UNION ALL
    SELECT 'Putluru' AS name UNION ALL
    SELECT 'RTC Bus Stand Area' AS name UNION ALL
    SELECT 'Railway Colony' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramachandra Nagar' AS name UNION ALL
    SELECT 'Raptadu' AS name UNION ALL
    SELECT 'Raptadu Road' AS name UNION ALL
    SELECT 'Rayadurg' AS name UNION ALL
    SELECT 'Revenue Colony' AS name UNION ALL
    SELECT 'Sapthagiri Circle' AS name UNION ALL
    SELECT 'Settur' AS name UNION ALL
    SELECT 'Singanamala' AS name UNION ALL
    SELECT 'Srinagar Colony' AS name UNION ALL
    SELECT 'Tadipatri' AS name UNION ALL
    SELECT 'Tadipatri Road' AS name UNION ALL
    SELECT 'Town Area' AS name UNION ALL
    SELECT 'Uravakonda' AS name UNION ALL
    SELECT 'Vajrakarur' AS name UNION ALL
    SELECT 'Vidapanakal' AS name UNION ALL
    SELECT 'Vidapanakal Road' AS name UNION ALL
    SELECT 'Yadiki' AS name UNION ALL
    SELECT 'Yellanur' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Ananthapuramu'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Annamayya (41 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'B.Kothakota' AS name UNION ALL
    SELECT 'Basinikonda' AS name UNION ALL
    SELECT 'Bengaluru Road' AS name UNION ALL
    SELECT 'CTM Road' AS name UNION ALL
    SELECT 'Chinnamandem' AS name UNION ALL
    SELECT 'Galiveedu' AS name UNION ALL
    SELECT 'Gurramkonda' AS name UNION ALL
    SELECT 'Kadapa Road' AS name UNION ALL
    SELECT 'Kadiri Road' AS name UNION ALL
    SELECT 'Kalakada' AS name UNION ALL
    SELECT 'Kambhamvaripalle' AS name UNION ALL
    SELECT 'Kodur' AS name UNION ALL
    SELECT 'Lakkireddipalle' AS name UNION ALL
    SELECT 'Madanapalle' AS name UNION ALL
    SELECT 'Madanapalle Road' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Nandalur' AS name UNION ALL
    SELECT 'Nandalur Road' AS name UNION ALL
    SELECT 'Nehru Bazaar' AS name UNION ALL
    SELECT 'New Housing Board Area' AS name UNION ALL
    SELECT 'New Town' AS name UNION ALL
    SELECT 'Nimmanapalle' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Peddamandyam' AS name UNION ALL
    SELECT 'Penagalur' AS name UNION ALL
    SELECT 'Piler' AS name UNION ALL
    SELECT 'Piler Road' AS name UNION ALL
    SELECT 'Pileru' AS name UNION ALL
    SELECT 'Pullampeta' AS name UNION ALL
    SELECT 'Railway Kodur' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajampet' AS name UNION ALL
    SELECT 'Ramapuram' AS name UNION ALL
    SELECT 'Ramapuram Road' AS name UNION ALL
    SELECT 'Rayachoti' AS name UNION ALL
    SELECT 'Revenue Colony' AS name UNION ALL
    SELECT 'Sambepalle' AS name UNION ALL
    SELECT 'T.Sundupalle' AS name UNION ALL
    SELECT 'Thamballapalle' AS name UNION ALL
    SELECT 'Vayalpad' AS name UNION ALL
    SELECT 'Veeraballi' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Annamayya'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Bapatla (49 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Addanki' AS name UNION ALL
    SELECT 'Amruthalur' AS name UNION ALL
    SELECT 'Ballikurava' AS name UNION ALL
    SELECT 'Bapatla' AS name UNION ALL
    SELECT 'Bapatla Town' AS name UNION ALL
    SELECT 'Beach Road' AS name UNION ALL
    SELECT 'Bhattiprolu' AS name UNION ALL
    SELECT 'Bose Road' AS name UNION ALL
    SELECT 'Brahmanakoduru Road' AS name UNION ALL
    SELECT 'Chebrolu Road' AS name UNION ALL
    SELECT 'Cherukupalle' AS name UNION ALL
    SELECT 'Cherukupalli' AS name UNION ALL
    SELECT 'Chirala' AS name UNION ALL
    SELECT 'Chirala Road' AS name UNION ALL
    SELECT 'College Road' AS name UNION ALL
    SELECT 'Housing Board Colony' AS name UNION ALL
    SELECT 'Ipurupalem' AS name UNION ALL
    SELECT 'Ithanagar' AS name UNION ALL
    SELECT 'Kakumanu' AS name UNION ALL
    SELECT 'Karamchedu' AS name UNION ALL
    SELECT 'Karlapalem' AS name UNION ALL
    SELECT 'Kollur' AS name UNION ALL
    SELECT 'Kothapeta' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Martur' AS name UNION ALL
    SELECT 'Nagaram' AS name UNION ALL
    SELECT 'Nandivelugu Road' AS name UNION ALL
    SELECT 'Nizampatnam' AS name UNION ALL
    SELECT 'Ongole Road' AS name UNION ALL
    SELECT 'Parchur Road' AS name UNION ALL
    SELECT 'Peddabazar' AS name UNION ALL
    SELECT 'Penumudi Road' AS name UNION ALL
    SELECT 'Perala' AS name UNION ALL
    SELECT 'Pittalavanipalem' AS name UNION ALL
    SELECT 'Ponnur' AS name UNION ALL
    SELECT 'RTC Complex Area' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajupalem' AS name UNION ALL
    SELECT 'Ramalingeswara Pet' AS name UNION ALL
    SELECT 'Ramapuram' AS name UNION ALL
    SELECT 'Repalle' AS name UNION ALL
    SELECT 'Srinivasa Nagar' AS name UNION ALL
    SELECT 'Suryalanka Road' AS name UNION ALL
    SELECT 'Tenali' AS name UNION ALL
    SELECT 'Tsunduru' AS name UNION ALL
    SELECT 'Vemuru' AS name UNION ALL
    SELECT 'Vetapalem' AS name UNION ALL
    SELECT 'Vetapalem Road' AS name UNION ALL
    SELECT 'Yeddanapudi' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Bapatla'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Chittoor (47 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Baireddipalle' AS name UNION ALL
    SELECT 'Bangalore Road' AS name UNION ALL
    SELECT 'Bangarupalem' AS name UNION ALL
    SELECT 'Bethamangala Road' AS name UNION ALL
    SELECT 'Chittoor' AS name UNION ALL
    SELECT 'Chittoor Road' AS name UNION ALL
    SELECT 'Chittoor Town' AS name UNION ALL
    SELECT 'Dwarakanagar' AS name UNION ALL
    SELECT 'GD Nellore' AS name UNION ALL
    SELECT 'Gandhi Road' AS name UNION ALL
    SELECT 'Gangadhara Nellore' AS name UNION ALL
    SELECT 'Gangavaram' AS name UNION ALL
    SELECT 'Gudipala' AS name UNION ALL
    SELECT 'Gudupalle' AS name UNION ALL
    SELECT 'Iral' AS name UNION ALL
    SELECT 'K.V.B. Puram' AS name UNION ALL
    SELECT 'KR Palli' AS name UNION ALL
    SELECT 'Kanipakam Road' AS name UNION ALL
    SELECT 'Karvetinagar' AS name UNION ALL
    SELECT 'Kongareddipalle' AS name UNION ALL
    SELECT 'Kuppam' AS name UNION ALL
    SELECT 'MSR Circle' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mittoor' AS name UNION ALL
    SELECT 'Nagari' AS name UNION ALL
    SELECT 'Nindra' AS name UNION ALL
    SELECT 'Palamaner' AS name UNION ALL
    SELECT 'Palamaner Road' AS name UNION ALL
    SELECT 'Palasamudram' AS name UNION ALL
    SELECT 'Peddapanjani' AS name UNION ALL
    SELECT 'Penumur' AS name UNION ALL
    SELECT 'Pulicherla' AS name UNION ALL
    SELECT 'Punganur' AS name UNION ALL
    SELECT 'Putthur' AS name UNION ALL
    SELECT 'Puttur' AS name UNION ALL
    SELECT 'Puttur Road' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramakuppam' AS name UNION ALL
    SELECT 'Rompicherla' AS name UNION ALL
    SELECT 'Santhipuram' AS name UNION ALL
    SELECT 'Sri Rangarajapuram' AS name UNION ALL
    SELECT 'Thavanampalle' AS name UNION ALL
    SELECT 'Tirupati Road' AS name UNION ALL
    SELECT 'Vedurukuppam' AS name UNION ALL
    SELECT 'Vijayapuram' AS name UNION ALL
    SELECT 'Yadamari' AS name UNION ALL
    SELECT 'Yadamarri' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Chittoor'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Dr. B. R. Ambedkar Konaseema (32 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ainavilli' AS name UNION ALL
    SELECT 'Alamuru' AS name UNION ALL
    SELECT 'Allavaram' AS name UNION ALL
    SELECT 'Amalapuram' AS name UNION ALL
    SELECT 'Amalapuram Road' AS name UNION ALL
    SELECT 'Ambajipeta' AS name UNION ALL
    SELECT 'Ashok Nagar' AS name UNION ALL
    SELECT 'Atreyapuram' AS name UNION ALL
    SELECT 'Bank Street' AS name UNION ALL
    SELECT 'Clock Tower Area' AS name UNION ALL
    SELECT 'I. Polavaram' AS name UNION ALL
    SELECT 'Kakinada Road' AS name UNION ALL
    SELECT 'Kapileswarapuram' AS name UNION ALL
    SELECT 'Katrenikona' AS name UNION ALL
    SELECT 'Kothapeta' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Malikipuram' AS name UNION ALL
    SELECT 'Mamidikuduru' AS name UNION ALL
    SELECT 'Mandapeta' AS name UNION ALL
    SELECT 'Mummidivaram' AS name UNION ALL
    SELECT 'Mummivaram' AS name UNION ALL
    SELECT 'P. Gannavaram' AS name UNION ALL
    SELECT 'RTC Complex' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramachandrapuram' AS name UNION ALL
    SELECT 'Ramachandrapuram Road' AS name UNION ALL
    SELECT 'Ravulapalem' AS name UNION ALL
    SELECT 'Razole' AS name UNION ALL
    SELECT 'Sakhinetipalle' AS name UNION ALL
    SELECT 'Town Hall Road' AS name UNION ALL
    SELECT 'Uppalaguptam' AS name UNION ALL
    SELECT 'Yanam vicinity' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Dr. B. R. Ambedkar Konaseema'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: East Godavari (36 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'AVA Road' AS name UNION ALL
    SELECT 'Alcot Gardens' AS name UNION ALL
    SELECT 'Anaparthi' AS name UNION ALL
    SELECT 'Anaparthipadu' AS name UNION ALL
    SELECT 'Aryapuram' AS name UNION ALL
    SELECT 'Biccavolu' AS name UNION ALL
    SELECT 'Bommuru' AS name UNION ALL
    SELECT 'Chagallu' AS name UNION ALL
    SELECT 'Danavaipeta' AS name UNION ALL
    SELECT 'Devarapalle' AS name UNION ALL
    SELECT 'Diwancheruvu' AS name UNION ALL
    SELECT 'Dowleswaram' AS name UNION ALL
    SELECT 'Gandepalle' AS name UNION ALL
    SELECT 'Godavari Bund Road' AS name UNION ALL
    SELECT 'Gokavaram' AS name UNION ALL
    SELECT 'Gopalapuram' AS name UNION ALL
    SELECT 'Hukumpeta' AS name UNION ALL
    SELECT 'Kadiyam' AS name UNION ALL
    SELECT 'Korukonda' AS name UNION ALL
    SELECT 'Kotipalli Bus Stand Area' AS name UNION ALL
    SELECT 'Kovvur' AS name UNION ALL
    SELECT 'Lalacheruvu' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Morampudi' AS name UNION ALL
    SELECT 'NH-16 Corridor' AS name UNION ALL
    SELECT 'Nallajerla' AS name UNION ALL
    SELECT 'Nidadavole' AS name UNION ALL
    SELECT 'Prathipadu' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajamahendravaram' AS name UNION ALL
    SELECT 'Rajamahendravaram (Rajahmundry)' AS name UNION ALL
    SELECT 'Rajanagaram' AS name UNION ALL
    SELECT 'Seethanagaram' AS name UNION ALL
    SELECT 'Tadithota' AS name UNION ALL
    SELECT 'Tallapudi' AS name UNION ALL
    SELECT 'Undrajavaram' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'East Godavari'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Eluru (42 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Agiripalli' AS name UNION ALL
    SELECT 'Ashok Nagar' AS name UNION ALL
    SELECT 'Aswaraopeta Road' AS name UNION ALL
    SELECT 'Bhimadole' AS name UNION ALL
    SELECT 'Buttayagudem' AS name UNION ALL
    SELECT 'Chintalapudi' AS name UNION ALL
    SELECT 'Denduluru' AS name UNION ALL
    SELECT 'Dwaraka Tirumala' AS name UNION ALL
    SELECT 'Eluru' AS name UNION ALL
    SELECT 'Eluru City' AS name UNION ALL
    SELECT 'Eluru Road' AS name UNION ALL
    SELECT 'FCI Colony' AS name UNION ALL
    SELECT 'Fire Station Area' AS name UNION ALL
    SELECT 'Ganapavaram' AS name UNION ALL
    SELECT 'Jangareddygudem' AS name UNION ALL
    SELECT 'Kaikalur' AS name UNION ALL
    SELECT 'Kamavarapukota' AS name UNION ALL
    SELECT 'Koyyalagudem' AS name UNION ALL
    SELECT 'Lingapalem' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mandavalli' AS name UNION ALL
    SELECT 'Mini Bypass Road' AS name UNION ALL
    SELECT 'Mudinepalli' AS name UNION ALL
    SELECT 'Musunuru' AS name UNION ALL
    SELECT 'Mylavaram Road' AS name UNION ALL
    SELECT 'Nuzvid' AS name UNION ALL
    SELECT 'One Town' AS name UNION ALL
    SELECT 'Pedapadu' AS name UNION ALL
    SELECT 'Pedavegi' AS name UNION ALL
    SELECT 'Polavaram vicinity' AS name UNION ALL
    SELECT 'Powerpet' AS name UNION ALL
    SELECT 'R.R. Peta' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramachandra Rao Peta' AS name UNION ALL
    SELECT 'Sanivarapupeta' AS name UNION ALL
    SELECT 'Santhi Nagar' AS name UNION ALL
    SELECT 'T.Narsapuram' AS name UNION ALL
    SELECT 'Tangellamudi' AS name UNION ALL
    SELECT 'Two Town' AS name UNION ALL
    SELECT 'Unguturu' AS name UNION ALL
    SELECT 'Vatluru' AS name UNION ALL
    SELECT 'Velerupadu' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Eluru'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Guntur (64 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'AT Agraharam' AS name UNION ALL
    SELECT 'Achampet' AS name UNION ALL
    SELECT 'Amaravathi' AS name UNION ALL
    SELECT 'Amaravathi Road' AS name UNION ALL
    SELECT 'Amaravati Road' AS name UNION ALL
    SELECT 'Arundelpet' AS name UNION ALL
    SELECT 'Atmakuru' AS name UNION ALL
    SELECT 'Autonagar' AS name UNION ALL
    SELECT 'Autonagar Guntur' AS name UNION ALL
    SELECT 'Bapatla Border' AS name UNION ALL
    SELECT 'Bellamkonda' AS name UNION ALL
    SELECT 'Bose Road' AS name UNION ALL
    SELECT 'Brindavan Gardens' AS name UNION ALL
    SELECT 'Brodipet' AS name UNION ALL
    SELECT 'Burripalem Road' AS name UNION ALL
    SELECT 'Chandramouli Nagar' AS name UNION ALL
    SELECT 'Chebrolu' AS name UNION ALL
    SELECT 'Chebrolu Road' AS name UNION ALL
    SELECT 'Collectorate Area' AS name UNION ALL
    SELECT 'Duggirala' AS name UNION ALL
    SELECT 'Gorantla' AS name UNION ALL
    SELECT 'Gujjanagundla' AS name UNION ALL
    SELECT 'Guntur City' AS name UNION ALL
    SELECT 'Inner Ring Road' AS name UNION ALL
    SELECT 'Ithanagar' AS name UNION ALL
    SELECT 'Kakumanu' AS name UNION ALL
    SELECT 'Kollipara' AS name UNION ALL
    SELECT 'Koretipadu' AS name UNION ALL
    SELECT 'Koritepadu' AS name UNION ALL
    SELECT 'Lakshmipuram' AS name UNION ALL
    SELECT 'Lakshmipuram Main Road' AS name UNION ALL
    SELECT 'Laxmipuram' AS name UNION ALL
    SELECT 'Mahanadu Road vicinity' AS name UNION ALL
    SELECT 'Mangalagiri' AS name UNION ALL
    SELECT 'Medikonduru' AS name UNION ALL
    SELECT 'Morrispet' AS name UNION ALL
    SELECT 'Nallapadu' AS name UNION ALL
    SELECT 'Nandivelugu Road' AS name UNION ALL
    SELECT 'Navuluru' AS name UNION ALL
    SELECT 'Old Guntur' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Pattabhipuram' AS name UNION ALL
    SELECT 'Pedakakani' AS name UNION ALL
    SELECT 'Pedakurapadu' AS name UNION ALL
    SELECT 'Pedanandipadu' AS name UNION ALL
    SELECT 'Phirangipuram' AS name UNION ALL
    SELECT 'Ponnur' AS name UNION ALL
    SELECT 'Prakash Nagar' AS name UNION ALL
    SELECT 'Prathipadu' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramalingeswara Pet' AS name UNION ALL
    SELECT 'SVN Colony' AS name UNION ALL
    SELECT 'Seethanagaram' AS name UNION ALL
    SELECT 'Srinagar' AS name UNION ALL
    SELECT 'Syamala Nagar' AS name UNION ALL
    SELECT 'Tadepalle' AS name UNION ALL
    SELECT 'Tadepalli Road' AS name UNION ALL
    SELECT 'Tadikonda' AS name UNION ALL
    SELECT 'Tenali' AS name UNION ALL
    SELECT 'Thullur' AS name UNION ALL
    SELECT 'Undavalli' AS name UNION ALL
    SELECT 'Undavalli Road' AS name UNION ALL
    SELECT 'Vatticherukuru' AS name UNION ALL
    SELECT 'Vidyanagar Guntur' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Guntur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kakinada (39 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ashok Nagar' AS name UNION ALL
    SELECT 'Balaji Cheruvu' AS name UNION ALL
    SELECT 'Bhanugudi' AS name UNION ALL
    SELECT 'Cinema Road' AS name UNION ALL
    SELECT 'Gollaprolu' AS name UNION ALL
    SELECT 'Indrapalem' AS name UNION ALL
    SELECT 'JNTU Area' AS name UNION ALL
    SELECT 'Jagannaickpur' AS name UNION ALL
    SELECT 'Jaggampeta' AS name UNION ALL
    SELECT 'Kakinada' AS name UNION ALL
    SELECT 'Kakinada City' AS name UNION ALL
    SELECT 'Kakinada Port Area' AS name UNION ALL
    SELECT 'Kakinada SEZ vicinity' AS name UNION ALL
    SELECT 'Kirlampudi' AS name UNION ALL
    SELECT 'Kotananduru' AS name UNION ALL
    SELECT 'Kothapalle' AS name UNION ALL
    SELECT 'Madhavapatnam' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Pedapudi' AS name UNION ALL
    SELECT 'Peddapuram' AS name UNION ALL
    SELECT 'Peddapuram Road' AS name UNION ALL
    SELECT 'Pithapuram' AS name UNION ALL
    SELECT 'Prathipadu' AS name UNION ALL
    SELECT 'RTC Complex Area' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramanayyapeta' AS name UNION ALL
    SELECT 'Rowthulapudi' AS name UNION ALL
    SELECT 'Samalkota' AS name UNION ALL
    SELECT 'Samalkota Road' AS name UNION ALL
    SELECT 'Sankhavaram' AS name UNION ALL
    SELECT 'Sarpavaram' AS name UNION ALL
    SELECT 'Thondangi' AS name UNION ALL
    SELECT 'Tuni' AS name UNION ALL
    SELECT 'Tuni Road areas' AS name UNION ALL
    SELECT 'Tuni vicinity' AS name UNION ALL
    SELECT 'U.Kothapalli' AS name UNION ALL
    SELECT 'Vakalapudi' AS name UNION ALL
    SELECT 'Y. Ramavaram' AS name UNION ALL
    SELECT 'Yeleswaram' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Kakinada'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Krishna (37 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Avanigadda' AS name UNION ALL
    SELECT 'Bandar Road' AS name UNION ALL
    SELECT 'Bantumilli' AS name UNION ALL
    SELECT 'Challapalli' AS name UNION ALL
    SELECT 'Chilakalapudi' AS name UNION ALL
    SELECT 'Gandhi Nagar' AS name UNION ALL
    SELECT 'Ghantasala' AS name UNION ALL
    SELECT 'Gudivada' AS name UNION ALL
    SELECT 'Gudlavalleru' AS name UNION ALL
    SELECT 'Guduru' AS name UNION ALL
    SELECT 'Kaikalur' AS name UNION ALL
    SELECT 'Kalidindi' AS name UNION ALL
    SELECT 'Koneru Center' AS name UNION ALL
    SELECT 'Kruthivennu' AS name UNION ALL
    SELECT 'Machilipatnam' AS name UNION ALL
    SELECT 'Machilipatnam Road' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mandavalli' AS name UNION ALL
    SELECT 'Mopidevi' AS name UNION ALL
    SELECT 'Movva' AS name UNION ALL
    SELECT 'Mudinepalli' AS name UNION ALL
    SELECT 'Nagayalanka' AS name UNION ALL
    SELECT 'Nagayalanka Road' AS name UNION ALL
    SELECT 'Nandivada' AS name UNION ALL
    SELECT 'Nuzvid Road' AS name UNION ALL
    SELECT 'Pamarru' AS name UNION ALL
    SELECT 'Pamarru Road' AS name UNION ALL
    SELECT 'Pamidimukkala' AS name UNION ALL
    SELECT 'Pedana' AS name UNION ALL
    SELECT 'Pedana Road' AS name UNION ALL
    SELECT 'Pedaparupudi' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramanaidupet' AS name UNION ALL
    SELECT 'Santhi Nagar' AS name UNION ALL
    SELECT 'Thotlavalluru' AS name UNION ALL
    SELECT 'Unguturu' AS name UNION ALL
    SELECT 'Vuyyuru' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Krishna'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kurnool (46 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adoni' AS name UNION ALL
    SELECT 'Adoni Road' AS name UNION ALL
    SELECT 'Alur' AS name UNION ALL
    SELECT 'Ashok Nagar' AS name UNION ALL
    SELECT 'Aspari' AS name UNION ALL
    SELECT 'Auto Nagar' AS name UNION ALL
    SELECT 'B-Camp' AS name UNION ALL
    SELECT 'Bellary Road' AS name UNION ALL
    SELECT 'Bhagya Nagar' AS name UNION ALL
    SELECT 'Budhawarpet' AS name UNION ALL
    SELECT 'Budhwarpet' AS name UNION ALL
    SELECT 'C-Camp' AS name UNION ALL
    SELECT 'Chippagiri' AS name UNION ALL
    SELECT 'Collectorate Area' AS name UNION ALL
    SELECT 'Devanakonda' AS name UNION ALL
    SELECT 'Dhone' AS name UNION ALL
    SELECT 'Gayathri Estate' AS name UNION ALL
    SELECT 'Gonegandla' AS name UNION ALL
    SELECT 'Halaharvi' AS name UNION ALL
    SELECT 'Holagonda' AS name UNION ALL
    SELECT 'Joharapuram Road' AS name UNION ALL
    SELECT 'Kallur' AS name UNION ALL
    SELECT 'Kodumur' AS name UNION ALL
    SELECT 'Kosigi' AS name UNION ALL
    SELECT 'Kowthalam' AS name UNION ALL
    SELECT 'Kurnool' AS name UNION ALL
    SELECT 'Kurnool City' AS name UNION ALL
    SELECT 'Maddikera' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mantralayam' AS name UNION ALL
    SELECT 'NR Peta' AS name UNION ALL
    SELECT 'Nandavaram' AS name UNION ALL
    SELECT 'Nandyal Border' AS name UNION ALL
    SELECT 'Nandyal Road' AS name UNION ALL
    SELECT 'New Town' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Orvakal' AS name UNION ALL
    SELECT 'Pattikonda' AS name UNION ALL
    SELECT 'Peddakadubur' AS name UNION ALL
    SELECT 'Raghavendra Swamy Temple Area' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'SBI Colony' AS name UNION ALL
    SELECT 'Seshadri Nagar' AS name UNION ALL
    SELECT 'Venkataramana Colony' AS name UNION ALL
    SELECT 'Yemmiganur' AS name UNION ALL
    SELECT 'Yemmiganur Road' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Kurnool'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Markapuram (27 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ardhaveedu' AS name UNION ALL
    SELECT 'Bestavaripeta' AS name UNION ALL
    SELECT 'College Road' AS name UNION ALL
    SELECT 'Cumbum' AS name UNION ALL
    SELECT 'Darsi' AS name UNION ALL
    SELECT 'Donakonda' AS name UNION ALL
    SELECT 'Dornala Road' AS name UNION ALL
    SELECT 'Giddalur' AS name UNION ALL
    SELECT 'Giddalur Road' AS name UNION ALL
    SELECT 'Hanumanthunipadu' AS name UNION ALL
    SELECT 'Kanigiri' AS name UNION ALL
    SELECT 'Komarolu' AS name UNION ALL
    SELECT 'Kurichedu' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Markapur Road' AS name UNION ALL
    SELECT 'Markapuram' AS name UNION ALL
    SELECT 'Marripudi' AS name UNION ALL
    SELECT 'Pamur Road' AS name UNION ALL
    SELECT 'Peddaraveedu' AS name UNION ALL
    SELECT 'Podili' AS name UNION ALL
    SELECT 'Pullalacheruvu' AS name UNION ALL
    SELECT 'RTC Complex Area' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Tripuranthakam' AS name UNION ALL
    SELECT 'Veligandla' AS name UNION ALL
    SELECT 'Yerragondapalem' AS name UNION ALL
    SELECT 'Zarugumalli' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Markapuram'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: NTR (50 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'A.Konduru' AS name UNION ALL
    SELECT 'Ajit Singh Nagar' AS name UNION ALL
    SELECT 'Auto Nagar' AS name UNION ALL
    SELECT 'Auto Nagar Vijayawada' AS name UNION ALL
    SELECT 'Benz Circle' AS name UNION ALL
    SELECT 'Bhavanipuram' AS name UNION ALL
    SELECT 'Chandarlapadu' AS name UNION ALL
    SELECT 'Chittinagar' AS name UNION ALL
    SELECT 'Currency Nagar' AS name UNION ALL
    SELECT 'Enikepadu' AS name UNION ALL
    SELECT 'G.Konduru' AS name UNION ALL
    SELECT 'Gampalagudem' AS name UNION ALL
    SELECT 'Gollapudi' AS name UNION ALL
    SELECT 'Governorpet' AS name UNION ALL
    SELECT 'Gunadala' AS name UNION ALL
    SELECT 'Ibrahimpatnam' AS name UNION ALL
    SELECT 'Jaggaiahpet' AS name UNION ALL
    SELECT 'Jaggayyapeta' AS name UNION ALL
    SELECT 'Kanchikacherla' AS name UNION ALL
    SELECT 'Kankipadu' AS name UNION ALL
    SELECT 'Kanuru' AS name UNION ALL
    SELECT 'Khammam Road' AS name UNION ALL
    SELECT 'Krishna Lanka' AS name UNION ALL
    SELECT 'Labbipet' AS name UNION ALL
    SELECT 'MG Road Vijayawada' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Maruthi Nagar' AS name UNION ALL
    SELECT 'Moghalrajpuram' AS name UNION ALL
    SELECT 'Mylavaram' AS name UNION ALL
    SELECT 'Nandigama' AS name UNION ALL
    SELECT 'One Town' AS name UNION ALL
    SELECT 'Patamata' AS name UNION ALL
    SELECT 'Payakapuram' AS name UNION ALL
    SELECT 'Penamaluru' AS name UNION ALL
    SELECT 'Penuganchiprolu' AS name UNION ALL
    SELECT 'Poranki' AS name UNION ALL
    SELECT 'Ramavarappadu' AS name UNION ALL
    SELECT 'Reddigudem' AS name UNION ALL
    SELECT 'Satyanarayanapuram' AS name UNION ALL
    SELECT 'Singh Nagar' AS name UNION ALL
    SELECT 'Suryaraopet' AS name UNION ALL
    SELECT 'Tiruvuru' AS name UNION ALL
    SELECT 'Two Town' AS name UNION ALL
    SELECT 'Vatsavai' AS name UNION ALL
    SELECT 'Veerullapadu' AS name UNION ALL
    SELECT 'Vijayawada' AS name UNION ALL
    SELECT 'Vijayawada City' AS name UNION ALL
    SELECT 'Vijayawada Road' AS name UNION ALL
    SELECT 'Vijayawada Urban' AS name UNION ALL
    SELECT 'Vissannapeta' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'NTR'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Nandyal (40 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Allagadda' AS name UNION ALL
    SELECT 'Atmakur' AS name UNION ALL
    SELECT 'Atmakur (Nandyal)' AS name UNION ALL
    SELECT 'Atmakur Road' AS name UNION ALL
    SELECT 'Balaji Nagar' AS name UNION ALL
    SELECT 'Banaganapalle' AS name UNION ALL
    SELECT 'Bandi Atmakur' AS name UNION ALL
    SELECT 'Bethamcherla' AS name UNION ALL
    SELECT 'Chagalamarri' AS name UNION ALL
    SELECT 'Dhone' AS name UNION ALL
    SELECT 'Dornipadu' AS name UNION ALL
    SELECT 'Gadivemula' AS name UNION ALL
    SELECT 'Gospadu' AS name UNION ALL
    SELECT 'Government Hospital Area' AS name UNION ALL
    SELECT 'Jupadu Bungalow' AS name UNION ALL
    SELECT 'Koilkuntla' AS name UNION ALL
    SELECT 'Kolimigla' AS name UNION ALL
    SELECT 'Kurnool Road' AS name UNION ALL
    SELECT 'Mahanandi' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Miduthuru' AS name UNION ALL
    SELECT 'Nandavaram' AS name UNION ALL
    SELECT 'Nandikotkur' AS name UNION ALL
    SELECT 'Nandyal' AS name UNION ALL
    SELECT 'Nandyal Road' AS name UNION ALL
    SELECT 'Nandyal Town' AS name UNION ALL
    SELECT 'Noonepalle' AS name UNION ALL
    SELECT 'Owk' AS name UNION ALL
    SELECT 'Padmavati Nagar' AS name UNION ALL
    SELECT 'Panyam' AS name UNION ALL
    SELECT 'Peapully' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rudravaram' AS name UNION ALL
    SELECT 'Sanjamala' AS name UNION ALL
    SELECT 'Sanjeev Nagar' AS name UNION ALL
    SELECT 'Sirvel' AS name UNION ALL
    SELECT 'Srinivasa Nagar' AS name UNION ALL
    SELECT 'Srisailam' AS name UNION ALL
    SELECT 'Uyyalawada' AS name UNION ALL
    SELECT 'Velgode' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Nandyal'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Palnadu (33 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amaravathi' AS name UNION ALL
    SELECT 'Atchampet' AS name UNION ALL
    SELECT 'Bellamkonda' AS name UNION ALL
    SELECT 'Bollapalle' AS name UNION ALL
    SELECT 'Cement City Area' AS name UNION ALL
    SELECT 'Chilakaluripet' AS name UNION ALL
    SELECT 'Dachepalle' AS name UNION ALL
    SELECT 'Durgi' AS name UNION ALL
    SELECT 'Gurazala' AS name UNION ALL
    SELECT 'Ipuru' AS name UNION ALL
    SELECT 'Karempudi' AS name UNION ALL
    SELECT 'Krosuru' AS name UNION ALL
    SELECT 'Macherla' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Markapur Road' AS name UNION ALL
    SELECT 'Muppalla' AS name UNION ALL
    SELECT 'Narasaraopet' AS name UNION ALL
    SELECT 'Nekarikallu' AS name UNION ALL
    SELECT 'New Town' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Palnadu Road' AS name UNION ALL
    SELECT 'Pedakurapadu' AS name UNION ALL
    SELECT 'Peddagarlapadu' AS name UNION ALL
    SELECT 'Piduguralla' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rentachintala' AS name UNION ALL
    SELECT 'Rompicherla' AS name UNION ALL
    SELECT 'Sattenapalle' AS name UNION ALL
    SELECT 'Sattenapalli Road' AS name UNION ALL
    SELECT 'Savalyapuram' AS name UNION ALL
    SELECT 'Veldurthi' AS name UNION ALL
    SELECT 'Vinukonda' AS name UNION ALL
    SELECT 'Vurukonda' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Palnadu'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Parvathipuram Manyam (22 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Balijipeta' AS name UNION ALL
    SELECT 'Bhamini' AS name UNION ALL
    SELECT 'Garugubilli' AS name UNION ALL
    SELECT 'Gummalakshmipuram' AS name UNION ALL
    SELECT 'Jiyyammavalasa' AS name UNION ALL
    SELECT 'Komarada' AS name UNION ALL
    SELECT 'Kurupam' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Makkuva' AS name UNION ALL
    SELECT 'Pachipenta' AS name UNION ALL
    SELECT 'Palakonda' AS name UNION ALL
    SELECT 'Parvathipuram' AS name UNION ALL
    SELECT 'Parvathipuram Road' AS name UNION ALL
    SELECT 'RTC Complex Area' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Salur' AS name UNION ALL
    SELECT 'Salur Road' AS name UNION ALL
    SELECT 'Seethampeta' AS name UNION ALL
    SELECT 'Seethanagaram' AS name UNION ALL
    SELECT 'Srikakulam Road' AS name UNION ALL
    SELECT 'Vangara' AS name UNION ALL
    SELECT 'Veeraghattam' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Parvathipuram Manyam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Polavaram (24 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Addateegala' AS name UNION ALL
    SELECT 'Buttayagudem' AS name UNION ALL
    SELECT 'Chintur' AS name UNION ALL
    SELECT 'Devipatnam' AS name UNION ALL
    SELECT 'Forest Area' AS name UNION ALL
    SELECT 'Gangavaram' AS name UNION ALL
    SELECT 'Gokavaram Road' AS name UNION ALL
    SELECT 'ITDA Area' AS name UNION ALL
    SELECT 'Jeelugumilli' AS name UNION ALL
    SELECT 'Kukunoor' AS name UNION ALL
    SELECT 'Kunavaram' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Maredumilli' AS name UNION ALL
    SELECT 'Nellipaka' AS name UNION ALL
    SELECT 'Polavaram' AS name UNION ALL
    SELECT 'Rajahmundry Road' AS name UNION ALL
    SELECT 'Rajavommangi' AS name UNION ALL
    SELECT 'Rampachodavaram' AS name UNION ALL
    SELECT 'Rampachodavaram Road' AS name UNION ALL
    SELECT 'Tourist Road' AS name UNION ALL
    SELECT 'Vararamachandrapuram' AS name UNION ALL
    SELECT 'Velairpadu' AS name UNION ALL
    SELECT 'Velerupadu' AS name UNION ALL
    SELECT 'Y. Ramavaram' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Polavaram'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Prakasam (45 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Addanki' AS name UNION ALL
    SELECT 'Balaji Nagar' AS name UNION ALL
    SELECT 'Bestavaripeta' AS name UNION ALL
    SELECT 'Bhagyanagar' AS name UNION ALL
    SELECT 'Brindavan Gardens' AS name UNION ALL
    SELECT 'Chimakurthy' AS name UNION ALL
    SELECT 'Chirala Border' AS name UNION ALL
    SELECT 'Chirala vicinity' AS name UNION ALL
    SELECT 'Darsi' AS name UNION ALL
    SELECT 'Donakonda' AS name UNION ALL
    SELECT 'Giddalur' AS name UNION ALL
    SELECT 'Gudluru' AS name UNION ALL
    SELECT 'Inkollu' AS name UNION ALL
    SELECT 'Ipurupalem' AS name UNION ALL
    SELECT 'Janakavarampanguluru' AS name UNION ALL
    SELECT 'Kandukur' AS name UNION ALL
    SELECT 'Kanigiri' AS name UNION ALL
    SELECT 'Kothapatnam Road' AS name UNION ALL
    SELECT 'Kurnool Road' AS name UNION ALL
    SELECT 'Lawyerpet' AS name UNION ALL
    SELECT 'Maddipadu' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mangamuru Road' AS name UNION ALL
    SELECT 'Markapur' AS name UNION ALL
    SELECT 'Martur' AS name UNION ALL
    SELECT 'Mundlamuru' AS name UNION ALL
    SELECT 'Naguluppalapadu' AS name UNION ALL
    SELECT 'Ongole' AS name UNION ALL
    SELECT 'Ongole Road' AS name UNION ALL
    SELECT 'Ongole Town' AS name UNION ALL
    SELECT 'Pamur' AS name UNION ALL
    SELECT 'Parchur' AS name UNION ALL
    SELECT 'Perala' AS name UNION ALL
    SELECT 'Podili' AS name UNION ALL
    SELECT 'Ramnagar' AS name UNION ALL
    SELECT 'Santhanuthalapadu' AS name UNION ALL
    SELECT 'Santhapeta' AS name UNION ALL
    SELECT 'Singarayakonda' AS name UNION ALL
    SELECT 'Tangutur' AS name UNION ALL
    SELECT 'Tripuranthakam' AS name UNION ALL
    SELECT 'Trunk Road' AS name UNION ALL
    SELECT 'Venkateswara Colony' AS name UNION ALL
    SELECT 'Vetapalem' AS name UNION ALL
    SELECT 'Yerragondapalem' AS name UNION ALL
    SELECT 'Zarugumalli' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Prakasam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Sri Potti Sriramulu Nellore (51 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ananthasagaram' AS name UNION ALL
    SELECT 'Atmakur' AS name UNION ALL
    SELECT 'Atmakur (Nellore)' AS name UNION ALL
    SELECT 'Balaji Nagar' AS name UNION ALL
    SELECT 'Buchireddipalem' AS name UNION ALL
    SELECT 'Buchireddypalem' AS name UNION ALL
    SELECT 'Chejerla' AS name UNION ALL
    SELECT 'Chennai Road' AS name UNION ALL
    SELECT 'Dargamitta' AS name UNION ALL
    SELECT 'Duttalur' AS name UNION ALL
    SELECT 'Gudur' AS name UNION ALL
    SELECT 'Haranathapuram' AS name UNION ALL
    SELECT 'Indukurpet' AS name UNION ALL
    SELECT 'Jaladanki' AS name UNION ALL
    SELECT 'Kaluvoya' AS name UNION ALL
    SELECT 'Kavali' AS name UNION ALL
    SELECT 'Kavali Road' AS name UNION ALL
    SELECT 'Kodavalur' AS name UNION ALL
    SELECT 'Kovur' AS name UNION ALL
    SELECT 'Magunta Layout' AS name UNION ALL
    SELECT 'Magunta Subbarama Reddy Nagar' AS name UNION ALL
    SELECT 'Marripadu' AS name UNION ALL
    SELECT 'Muthukur' AS name UNION ALL
    SELECT 'Naidupet' AS name UNION ALL
    SELECT 'Naidupeta' AS name UNION ALL
    SELECT 'Nellore' AS name UNION ALL
    SELECT 'Nellore City' AS name UNION ALL
    SELECT 'Nellore RTC Area' AS name UNION ALL
    SELECT 'Nellore Road' AS name UNION ALL
    SELECT 'Podalakur' AS name UNION ALL
    SELECT 'Podalakur Road' AS name UNION ALL
    SELECT 'Pogathota' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramalingapuram' AS name UNION ALL
    SELECT 'Ramji Nagar' AS name UNION ALL
    SELECT 'Rapur' AS name UNION ALL
    SELECT 'Sangam' AS name UNION ALL
    SELECT 'Santhapet' AS name UNION ALL
    SELECT 'Seetharamapuram' AS name UNION ALL
    SELECT 'Stonehousepet' AS name UNION ALL
    SELECT 'Sullurpeta' AS name UNION ALL
    SELECT 'Tada' AS name UNION ALL
    SELECT 'Trunk Road' AS name UNION ALL
    SELECT 'Udayagiri' AS name UNION ALL
    SELECT 'VRC Centre' AS name UNION ALL
    SELECT 'Vakadu' AS name UNION ALL
    SELECT 'Vanam Thopu' AS name UNION ALL
    SELECT 'Vedayapalem' AS name UNION ALL
    SELECT 'Venkatagiri' AS name UNION ALL
    SELECT 'Vidavalur' AS name UNION ALL
    SELECT 'Vinjamur' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Sri Potti Sriramulu Nellore'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Sri Sathya Sai (39 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Agali' AS name UNION ALL
    SELECT 'Amarapuram' AS name UNION ALL
    SELECT 'Bengaluru Road' AS name UNION ALL
    SELECT 'Bukkapatnam' AS name UNION ALL
    SELECT 'Chennekothapalle' AS name UNION ALL
    SELECT 'Chilamathur' AS name UNION ALL
    SELECT 'Dharmavaram' AS name UNION ALL
    SELECT 'Dharmavaram Border' AS name UNION ALL
    SELECT 'Gandlapenta' AS name UNION ALL
    SELECT 'Ganesha Temple Area' AS name UNION ALL
    SELECT 'Gopuram Road' AS name UNION ALL
    SELECT 'Gorantla' AS name UNION ALL
    SELECT 'Gudibanda' AS name UNION ALL
    SELECT 'Hindupur' AS name UNION ALL
    SELECT 'Kadiri' AS name UNION ALL
    SELECT 'Kothacheruvu' AS name UNION ALL
    SELECT 'Lepakshi' AS name UNION ALL
    SELECT 'Madakasira' AS name UNION ALL
    SELECT 'Madanapalle Road' AS name UNION ALL
    SELECT 'Mudigubba' AS name UNION ALL
    SELECT 'Nallacheruvu' AS name UNION ALL
    SELECT 'Nallamada' AS name UNION ALL
    SELECT 'Nambulapulakunta' AS name UNION ALL
    SELECT 'New Town' AS name UNION ALL
    SELECT 'Obuladevaracheruvu' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Parigi' AS name UNION ALL
    SELECT 'Parigi Road' AS name UNION ALL
    SELECT 'Penukonda' AS name UNION ALL
    SELECT 'Prasanthi Nilayam' AS name UNION ALL
    SELECT 'Puttaparthi' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Ramagiri' AS name UNION ALL
    SELECT 'Roddam' AS name UNION ALL
    SELECT 'Sai Ramesh Nagar' AS name UNION ALL
    SELECT 'Somandepalle' AS name UNION ALL
    SELECT 'Tadimarri' AS name UNION ALL
    SELECT 'Tadipatri Road' AS name UNION ALL
    SELECT 'Talupula' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Sri Sathya Sai'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Srikakulam (37 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Amadalavalasa' AS name UNION ALL
    SELECT 'Arasavilli' AS name UNION ALL
    SELECT 'Balaga' AS name UNION ALL
    SELECT 'Day & Night Junction' AS name UNION ALL
    SELECT 'Etcherla' AS name UNION ALL
    SELECT 'GT Road' AS name UNION ALL
    SELECT 'Gara' AS name UNION ALL
    SELECT 'Gujarathipeta' AS name UNION ALL
    SELECT 'Ichchapuram' AS name UNION ALL
    SELECT 'Jalumuru' AS name UNION ALL
    SELECT 'Kalanagar' AS name UNION ALL
    SELECT 'Kanchili' AS name UNION ALL
    SELECT 'Kasibugga' AS name UNION ALL
    SELECT 'Kotabommali' AS name UNION ALL
    SELECT 'Lakshminarasupeta' AS name UNION ALL
    SELECT 'Laveru' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mandasa' AS name UNION ALL
    SELECT 'Narasannapeta' AS name UNION ALL
    SELECT 'New Bridge Road' AS name UNION ALL
    SELECT 'Palakonda Road' AS name UNION ALL
    SELECT 'Palasa' AS name UNION ALL
    SELECT 'Palasa-Kasibugga' AS name UNION ALL
    SELECT 'Peddapadu' AS name UNION ALL
    SELECT 'Polaki' AS name UNION ALL
    SELECT 'Ponduru' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajam' AS name UNION ALL
    SELECT 'Ranastalam' AS name UNION ALL
    SELECT 'Santhabommali' AS name UNION ALL
    SELECT 'Saravakota' AS name UNION ALL
    SELECT 'Seven Roads Junction' AS name UNION ALL
    SELECT 'Sompeta' AS name UNION ALL
    SELECT 'Srikakulam' AS name UNION ALL
    SELECT 'Srikakulam Town' AS name UNION ALL
    SELECT 'Tekkali' AS name UNION ALL
    SELECT 'Vajrapukotturu' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Srikakulam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Tirupati (55 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'AIR Bypass Road' AS name UNION ALL
    SELECT 'Airport Road' AS name UNION ALL
    SELECT 'Akkarampalle' AS name UNION ALL
    SELECT 'Alipiri' AS name UNION ALL
    SELECT 'Bhavani Nagar' AS name UNION ALL
    SELECT 'Buchinaidu Kandriga' AS name UNION ALL
    SELECT 'Chandragiri' AS name UNION ALL
    SELECT 'Chinnagottigallu' AS name UNION ALL
    SELECT 'Chittoor Road' AS name UNION ALL
    SELECT 'Dakkili' AS name UNION ALL
    SELECT 'Doravarisatram' AS name UNION ALL
    SELECT 'Gudur' AS name UNION ALL
    SELECT 'Gudur Road' AS name UNION ALL
    SELECT 'IS Mahal Road' AS name UNION ALL
    SELECT 'K.T. Road' AS name UNION ALL
    SELECT 'K.V.B. Puram' AS name UNION ALL
    SELECT 'Kapila Theertham Road' AS name UNION ALL
    SELECT 'Korlagunta' AS name UNION ALL
    SELECT 'Leelamahal Circle' AS name UNION ALL
    SELECT 'MR Palle' AS name UNION ALL
    SELECT 'MR Palli' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mangalam' AS name UNION ALL
    SELECT 'Nagalapuram' AS name UNION ALL
    SELECT 'Naidupeta vicinity' AS name UNION ALL
    SELECT 'Narayanavanam' AS name UNION ALL
    SELECT 'Padmavathi Puram' AS name UNION ALL
    SELECT 'Pakala' AS name UNION ALL
    SELECT 'Pellakur' AS name UNION ALL
    SELECT 'Pichatur' AS name UNION ALL
    SELECT 'Ramachandrapuram' AS name UNION ALL
    SELECT 'Ramanuja Circle' AS name UNION ALL
    SELECT 'Renigunta' AS name UNION ALL
    SELECT 'Renigunta Road' AS name UNION ALL
    SELECT 'SV Nagar' AS name UNION ALL
    SELECT 'Satyavedu' AS name UNION ALL
    SELECT 'Srikalahasti' AS name UNION ALL
    SELECT 'Srinivasa Puram' AS name UNION ALL
    SELECT 'Sullurpet vicinity' AS name UNION ALL
    SELECT 'Sullurpeta' AS name UNION ALL
    SELECT 'Tada vicinity' AS name UNION ALL
    SELECT 'Temple Area' AS name UNION ALL
    SELECT 'Thottambedu' AS name UNION ALL
    SELECT 'Tiruchanur' AS name UNION ALL
    SELECT 'Tirumala' AS name UNION ALL
    SELECT 'Tirumala Area' AS name UNION ALL
    SELECT 'Tirupati' AS name UNION ALL
    SELECT 'Tirupati Railway Station Area' AS name UNION ALL
    SELECT 'Tirupati Town' AS name UNION ALL
    SELECT 'Tuda Area' AS name UNION ALL
    SELECT 'Vadamalapeta' AS name UNION ALL
    SELECT 'Vaikuntapuram' AS name UNION ALL
    SELECT 'Varadaiahpalem' AS name UNION ALL
    SELECT 'Venkatagiri' AS name UNION ALL
    SELECT 'Yerpedu' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Tirupati'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Visakhapatnam (44 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Akkayyapalem' AS name UNION ALL
    SELECT 'Akkireddypalem' AS name UNION ALL
    SELECT 'Anakapalli Border' AS name UNION ALL
    SELECT 'Asilmetta' AS name UNION ALL
    SELECT 'Beach Road' AS name UNION ALL
    SELECT 'Beach Road Vizag' AS name UNION ALL
    SELECT 'Bheemili Road' AS name UNION ALL
    SELECT 'Bheemunipatnam (Bheemili)' AS name UNION ALL
    SELECT 'Chinna Waltair' AS name UNION ALL
    SELECT 'Daba Gardens' AS name UNION ALL
    SELECT 'Dwaraka Nagar' AS name UNION ALL
    SELECT 'Gajuwaka' AS name UNION ALL
    SELECT 'Gopalapatnam' AS name UNION ALL
    SELECT 'Jagadamba Junction' AS name UNION ALL
    SELECT 'Kancharapalem' AS name UNION ALL
    SELECT 'Kommadi' AS name UNION ALL
    SELECT 'Kurmannapalem' AS name UNION ALL
    SELECT 'Lawson\'s Bay Colony' AS name UNION ALL
    SELECT 'MVP Colony' AS name UNION ALL
    SELECT 'Maddilapalem' AS name UNION ALL
    SELECT 'Madhurawada' AS name UNION ALL
    SELECT 'Marripalem' AS name UNION ALL
    SELECT 'NAD Junction' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'One Town' AS name UNION ALL
    SELECT 'PM Palem' AS name UNION ALL
    SELECT 'Pendurthi' AS name UNION ALL
    SELECT 'Ram Nagar' AS name UNION ALL
    SELECT 'Ramakrishna Beach' AS name UNION ALL
    SELECT 'Rushikonda' AS name UNION ALL
    SELECT 'Scindia' AS name UNION ALL
    SELECT 'Seethammadhara' AS name UNION ALL
    SELECT 'Seethammadhara North Extension' AS name UNION ALL
    SELECT 'Sheela Nagar' AS name UNION ALL
    SELECT 'Simhachalam' AS name UNION ALL
    SELECT 'Siripuram' AS name UNION ALL
    SELECT 'Steel Plant Area' AS name UNION ALL
    SELECT 'Steel Plant Township' AS name UNION ALL
    SELECT 'Sujatha Nagar' AS name UNION ALL
    SELECT 'Visakhapatnam' AS name UNION ALL
    SELECT 'Visakhapatnam City' AS name UNION ALL
    SELECT 'Waltair' AS name UNION ALL
    SELECT 'Waltair Uplands' AS name UNION ALL
    SELECT 'Yendada' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Visakhapatnam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Vizianagaram (40 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Badangi' AS name UNION ALL
    SELECT 'Balaji Nagar' AS name UNION ALL
    SELECT 'Bhogapuram' AS name UNION ALL
    SELECT 'Bobbili' AS name UNION ALL
    SELECT 'Bondapalle' AS name UNION ALL
    SELECT 'Cantonment' AS name UNION ALL
    SELECT 'Cheepurupalle' AS name UNION ALL
    SELECT 'Cheepurupalli' AS name UNION ALL
    SELECT 'Dasannapeta' AS name UNION ALL
    SELECT 'Dattirajeru' AS name UNION ALL
    SELECT 'Denkada' AS name UNION ALL
    SELECT 'Fort Area' AS name UNION ALL
    SELECT 'Gajapathinagaram' AS name UNION ALL
    SELECT 'Gajapathinagaram Road' AS name UNION ALL
    SELECT 'Garividi' AS name UNION ALL
    SELECT 'Gurla' AS name UNION ALL
    SELECT 'Jami' AS name UNION ALL
    SELECT 'Jute Mill Area' AS name UNION ALL
    SELECT 'Korukonda Road' AS name UNION ALL
    SELECT 'Kota' AS name UNION ALL
    SELECT 'Kothavalasa' AS name UNION ALL
    SELECT 'Lakkavarapukota' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Merakamudidam' AS name UNION ALL
    SELECT 'Nellimarla' AS name UNION ALL
    SELECT 'Phool Bagh' AS name UNION ALL
    SELECT 'Pusapatirega' AS name UNION ALL
    SELECT 'RTC Complex' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajam' AS name UNION ALL
    SELECT 'Ramabhadrapuram' AS name UNION ALL
    SELECT 'Ring Road' AS name UNION ALL
    SELECT 'SKOTA Colony' AS name UNION ALL
    SELECT 'Salur' AS name UNION ALL
    SELECT 'Salur vicinity' AS name UNION ALL
    SELECT 'Srungavarapukota' AS name UNION ALL
    SELECT 'Three Lamps Junction' AS name UNION ALL
    SELECT 'Vepada' AS name UNION ALL
    SELECT 'Vizianagaram' AS name UNION ALL
    SELECT 'Vizianagaram Town' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'Vizianagaram'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: West Godavari (43 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Achanta' AS name UNION ALL
    SELECT 'Akividu' AS name UNION ALL
    SELECT 'Attili' AS name UNION ALL
    SELECT 'Bank Colony' AS name UNION ALL
    SELECT 'Bhimadole vicinity' AS name UNION ALL
    SELECT 'Bhimavaram' AS name UNION ALL
    SELECT 'Bhimavaram Railway Station Area' AS name UNION ALL
    SELECT 'Chagallu vicinity' AS name UNION ALL
    SELECT 'Ganapavaram' AS name UNION ALL
    SELECT 'Godavari Bund' AS name UNION ALL
    SELECT 'Housing Board Colony' AS name UNION ALL
    SELECT 'Iragavaram' AS name UNION ALL
    SELECT 'J.P. Road' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Mogalthur' AS name UNION ALL
    SELECT 'Mogaltur' AS name UNION ALL
    SELECT 'Nallajerla' AS name UNION ALL
    SELECT 'Nallajerla Road' AS name UNION ALL
    SELECT 'Narasapur' AS name UNION ALL
    SELECT 'Narasapur Road' AS name UNION ALL
    SELECT 'Narsapur' AS name UNION ALL
    SELECT 'Nidadavole' AS name UNION ALL
    SELECT 'Nidadavole Road' AS name UNION ALL
    SELECT 'Nidamarru' AS name UNION ALL
    SELECT 'One Town' AS name UNION ALL
    SELECT 'Palakollu' AS name UNION ALL
    SELECT 'Palakollu Road' AS name UNION ALL
    SELECT 'Penugonda' AS name UNION ALL
    SELECT 'Penumantra' AS name UNION ALL
    SELECT 'Peravali' AS name UNION ALL
    SELECT 'Poduru' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'T.P. Gudem Road' AS name UNION ALL
    SELECT 'Tadepalligudem' AS name UNION ALL
    SELECT 'Tanuku' AS name UNION ALL
    SELECT 'Tanuku Road' AS name UNION ALL
    SELECT 'Two Town' AS name UNION ALL
    SELECT 'Undi' AS name UNION ALL
    SELECT 'Undi Road' AS name UNION ALL
    SELECT 'Undrajavaram' AS name UNION ALL
    SELECT 'Veeravasaram' AS name UNION ALL
    SELECT 'Velpuru Road' AS name UNION ALL
    SELECT 'Yelamanchili' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'West Godavari'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: YSR Kadapa (52 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Akkayapalle' AS name UNION ALL
    SELECT 'B.Matam' AS name UNION ALL
    SELECT 'Badvel' AS name UNION ALL
    SELECT 'Brahmamgarimattam' AS name UNION ALL
    SELECT 'Chapadu' AS name UNION ALL
    SELECT 'Chennur' AS name UNION ALL
    SELECT 'Chinna Chowk' AS name UNION ALL
    SELECT 'Duvvur' AS name UNION ALL
    SELECT 'Galiveedu vicinity' AS name UNION ALL
    SELECT 'Gandhi Road' AS name UNION ALL
    SELECT 'Jammalamadugu' AS name UNION ALL
    SELECT 'Jammichettu' AS name UNION ALL
    SELECT 'Kadapa' AS name UNION ALL
    SELECT 'Kadapa City' AS name UNION ALL
    SELECT 'Kadapa Road' AS name UNION ALL
    SELECT 'Kamalapuram' AS name UNION ALL
    SELECT 'Khajipet' AS name UNION ALL
    SELECT 'Kondapuram' AS name UNION ALL
    SELECT 'Lakkireddipalle vicinity' AS name UNION ALL
    SELECT 'Lingala' AS name UNION ALL
    SELECT 'Madanapalle Road' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Maria Puram' AS name UNION ALL
    SELECT 'Muddanur' AS name UNION ALL
    SELECT 'Mydukur' AS name UNION ALL
    SELECT 'Mydukur Road' AS name UNION ALL
    SELECT 'NGO Colony' AS name UNION ALL
    SELECT 'New Town' AS name UNION ALL
    SELECT 'Old Town' AS name UNION ALL
    SELECT 'Pendlimarri' AS name UNION ALL
    SELECT 'Porumamilla' AS name UNION ALL
    SELECT 'Proddatur' AS name UNION ALL
    SELECT 'Proddatur Road' AS name UNION ALL
    SELECT 'Pulivendula' AS name UNION ALL
    SELECT 'Pulivendula Road' AS name UNION ALL
    SELECT 'RTC Colony' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajampet' AS name UNION ALL
    SELECT 'Ramanjaneyapuram' AS name UNION ALL
    SELECT 'Ramapuram' AS name UNION ALL
    SELECT 'Rayachoti Border' AS name UNION ALL
    SELECT 'Rayachoti Road' AS name UNION ALL
    SELECT 'Sankarapuram' AS name UNION ALL
    SELECT 'Sidhout' AS name UNION ALL
    SELECT 'Simhadripuram' AS name UNION ALL
    SELECT 'Srinivasa Nagar' AS name UNION ALL
    SELECT 'Veerapunayunipalle' AS name UNION ALL
    SELECT 'Vempalli' AS name UNION ALL
    SELECT 'Vidyut Nagar' AS name UNION ALL
    SELECT 'Yerraguntla' AS name UNION ALL
    SELECT 'Yerramukkapalli' AS name UNION ALL
    SELECT 'Yerramukkapalli Road' AS name
) l
WHERE (s.code = 'AP' OR s.name = 'Andhra Pradesh')
  AND d.name = 'YSR Kadapa'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

SET FOREIGN_KEY_CHECKS = 1;
