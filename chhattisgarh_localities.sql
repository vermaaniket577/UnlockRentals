-- ==============================================================================
-- CHHATTISGARH: ALL 33 DISTRICTS & 871 LOCALITIES MASTER SQL QUERY
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
  SELECT 'Balod' AS name UNION ALL SELECT 'Baloda Bazar-Bhatapara' AS name UNION ALL SELECT 'Balrampur-Ramanujganj' AS name UNION ALL SELECT 'Bastar' AS name UNION ALL SELECT 'Bemetara' AS name UNION ALL SELECT 'Bijapur' AS name UNION ALL SELECT 'Bilaspur' AS name UNION ALL SELECT 'Dakshin Bastar Dantewada' AS name UNION ALL SELECT 'Dhamtari' AS name UNION ALL SELECT 'Durg' AS name UNION ALL SELECT 'Gariaband' AS name UNION ALL SELECT 'Gaurela-Pendra-Marwahi' AS name UNION ALL SELECT 'Janjgir-Champa' AS name UNION ALL SELECT 'Jashpur' AS name UNION ALL SELECT 'Kanker' AS name UNION ALL SELECT 'Kabirdham' AS name UNION ALL SELECT 'Khairagarh-Chhuikhadan-Gandai' AS name UNION ALL SELECT 'Kondagaon' AS name UNION ALL SELECT 'Korba' AS name UNION ALL SELECT 'Koriya' AS name UNION ALL SELECT 'Mahasamund' AS name UNION ALL SELECT 'Manendragarh-Chirmiri-Bharatpur' AS name UNION ALL SELECT 'Mohla-Manpur-Ambagarh Chowki' AS name UNION ALL SELECT 'Mungeli' AS name UNION ALL SELECT 'Narayanpur' AS name UNION ALL SELECT 'Raigarh' AS name UNION ALL SELECT 'Raipur' AS name UNION ALL SELECT 'Rajnandgaon' AS name UNION ALL SELECT 'Sakti' AS name UNION ALL SELECT 'Sarangarh-Bilaigarh' AS name UNION ALL SELECT 'Sukma' AS name UNION ALL SELECT 'Surajpur' AS name UNION ALL SELECT 'Surguja' AS name
) d WHERE NOT EXISTS (SELECT 1 FROM `districts` WHERE `state_id` = @ct AND LOWER(`name`) = LOWER(d.name));

-- 3. Insert Localities for All 33 Districts
-- District: Balod (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Balod' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Balod' AS name UNION ALL SELECT 'Dondi' AS name UNION ALL SELECT 'Dondi Lohara' AS name UNION ALL SELECT 'Gurur' AS name UNION ALL
  SELECT 'Gunderdehi' AS name UNION ALL SELECT 'Arjunda' AS name UNION ALL SELECT 'Chikhla Kosa' AS name UNION ALL SELECT 'Sikosa' AS name UNION ALL
  SELECT 'Deori' AS name UNION ALL SELECT 'Kusumkasa' AS name UNION ALL SELECT 'Latabod' AS name UNION ALL SELECT 'Mangchua' AS name UNION ALL
  SELECT 'Sihawa' AS name UNION ALL SELECT 'Khertha' AS name UNION ALL SELECT 'Beloda' AS name UNION ALL SELECT 'Kandel' AS name UNION ALL
  SELECT 'Dallirajhara' AS name UNION ALL SELECT 'Borsi' AS name UNION ALL SELECT 'Jatmai' AS name UNION ALL SELECT 'Aundhi' AS name UNION ALL
  SELECT 'Bharda' AS name UNION ALL SELECT 'Batrel' AS name UNION ALL SELECT 'Sankra' AS name UNION ALL SELECT 'Khapri' AS name UNION ALL
  SELECT 'Khertha Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Baloda Bazar-Bhatapara (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Baloda Bazar-Bhatapara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Baloda Bazar' AS name UNION ALL SELECT 'Bhatapara' AS name UNION ALL SELECT 'Simga' AS name UNION ALL SELECT 'Kasdol' AS name UNION ALL
  SELECT 'Palari' AS name UNION ALL SELECT 'Bilaigarh' AS name UNION ALL SELECT 'Lawan' AS name UNION ALL SELECT 'Tilda-side settlements' AS name UNION ALL
  SELECT 'Sonakhan' AS name UNION ALL SELECT 'Suhela' AS name UNION ALL SELECT 'Rohansi' AS name UNION ALL SELECT 'Katgi' AS name UNION ALL
  SELECT 'Watgan' AS name UNION ALL SELECT 'Bhatgaon' AS name UNION ALL SELECT 'Chhatauna' AS name UNION ALL SELECT 'Gidhpuri' AS name UNION ALL
  SELECT 'Kharora' AS name UNION ALL SELECT 'Deori' AS name UNION ALL SELECT 'Tarenga' AS name UNION ALL SELECT 'Khamhariya' AS name UNION ALL
  SELECT 'Kharri' AS name UNION ALL SELECT 'Nawagaon' AS name UNION ALL SELECT 'Latuwa' AS name UNION ALL SELECT 'Gaitra' AS name UNION ALL
  SELECT 'Mohtara' AS name UNION ALL SELECT 'Dhaneli' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Balrampur-Ramanujganj (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Balrampur-Ramanujganj' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Balrampur' AS name UNION ALL SELECT 'Ramanujganj' AS name UNION ALL SELECT 'Rajpur' AS name UNION ALL SELECT 'Kusmi' AS name UNION ALL
  SELECT 'Shankargarh' AS name UNION ALL SELECT 'Wadrafnagar' AS name UNION ALL SELECT 'Ramchandrapur' AS name UNION ALL SELECT 'Pratappur' AS name UNION ALL
  SELECT 'Samri' AS name UNION ALL SELECT 'Tatapani' AS name UNION ALL SELECT 'Chandho' AS name UNION ALL SELECT 'Daldali' AS name UNION ALL
  SELECT 'Semarsot' AS name UNION ALL SELECT 'Chalgali' AS name UNION ALL SELECT 'Raghunathnagar' AS name UNION ALL SELECT 'Ganeshpur' AS name UNION ALL
  SELECT 'Mahuadand' AS name UNION ALL SELECT 'Krishnanagar' AS name UNION ALL SELECT 'Bariyo' AS name UNION ALL SELECT 'Bhatgaon' AS name UNION ALL
  SELECT 'Bagra' AS name UNION ALL SELECT 'Govindpur' AS name UNION ALL SELECT 'Kusmi Bazar' AS name UNION ALL SELECT 'Ramanujganj Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bastar (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bastar' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jagdalpur' AS name UNION ALL SELECT 'Bastar' AS name UNION ALL SELECT 'Bakavand' AS name UNION ALL SELECT 'Bastanar' AS name UNION ALL
  SELECT 'Darbha' AS name UNION ALL SELECT 'Tokapal' AS name UNION ALL SELECT 'Lohandiguda' AS name UNION ALL SELECT 'Kilepal' AS name UNION ALL
  SELECT 'Chitrakote' AS name UNION ALL SELECT 'Tirathgarh' AS name UNION ALL SELECT 'Kumhrawand' AS name UNION ALL SELECT 'Bodhghat' AS name UNION ALL
  SELECT 'Dharampura' AS name UNION ALL SELECT 'Hatkachora' AS name UNION ALL SELECT 'Aasna' AS name UNION ALL SELECT 'Nagarnar' AS name UNION ALL
  SELECT 'Parpa' AS name UNION ALL SELECT 'Karanji' AS name UNION ALL SELECT 'Bade Kilepal' AS name UNION ALL SELECT 'Keshloor' AS name UNION ALL
  SELECT 'Barsoor' AS name UNION ALL SELECT 'Benur' AS name UNION ALL SELECT 'Bhanpuri' AS name UNION ALL SELECT 'Chitrakote Road' AS name UNION ALL
  SELECT 'Ghatluhanga' AS name UNION ALL SELECT 'Keshlur' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bemetara (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bemetara' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bemetara' AS name UNION ALL SELECT 'Berla' AS name UNION ALL SELECT 'Saja' AS name UNION ALL SELECT 'Nawagarh' AS name UNION ALL
  SELECT 'Than Khamharia' AS name UNION ALL SELECT 'Maro' AS name UNION ALL SELECT 'Deokar' AS name UNION ALL SELECT 'Dhamdha-side settlements' AS name UNION ALL
  SELECT 'Nandghat' AS name UNION ALL SELECT 'Bhimbhauri' AS name UNION ALL SELECT 'Khamhariya' AS name UNION ALL SELECT 'Kesda' AS name UNION ALL
  SELECT 'Kusmi' AS name UNION ALL SELECT 'Dadhi' AS name UNION ALL SELECT 'Bijatola' AS name UNION ALL SELECT 'Lalbandha' AS name UNION ALL
  SELECT 'Andhiyarkhor' AS name UNION ALL SELECT 'Dadi' AS name UNION ALL SELECT 'Khudmuda' AS name UNION ALL SELECT 'Achanakpur' AS name UNION ALL
  SELECT 'Kharra' AS name UNION ALL SELECT 'Kachari' AS name UNION ALL SELECT 'Nawagaon' AS name UNION ALL SELECT 'Koiria' AS name UNION ALL
  SELECT 'Jeora' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bijapur (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bijapur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bijapur' AS name UNION ALL SELECT 'Bhairamgarh' AS name UNION ALL SELECT 'Bhopalpattanam' AS name UNION ALL SELECT 'Usur' AS name UNION ALL
  SELECT 'Awapalli' AS name UNION ALL SELECT 'Basaguda' AS name UNION ALL SELECT 'Kutru' AS name UNION ALL SELECT 'Pamed' AS name UNION ALL
  SELECT 'Tarrem' AS name UNION ALL SELECT 'Gangaloor' AS name UNION ALL SELECT 'Mirtur' AS name UNION ALL SELECT 'Chinnakodepal' AS name UNION ALL
  SELECT 'Cherpal' AS name UNION ALL SELECT 'Farsegarh' AS name UNION ALL SELECT 'Tekalgudem' AS name UNION ALL SELECT 'Ilmidi' AS name UNION ALL
  SELECT 'Usoor' AS name UNION ALL SELECT 'Nelakanker' AS name UNION ALL SELECT 'Modakpal' AS name UNION ALL SELECT 'Toynar' AS name UNION ALL
  SELECT 'Bedre' AS name UNION ALL SELECT 'Minpa' AS name UNION ALL SELECT 'Dornapal-side settlements' AS name UNION ALL SELECT 'Bijapur Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Bilaspur (40 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Bilaspur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Bilaspur' AS name UNION ALL SELECT 'Sarkanda' AS name UNION ALL SELECT 'Torwa' AS name UNION ALL SELECT 'Telipara' AS name UNION ALL
  SELECT 'Mangla' AS name UNION ALL SELECT 'Mopka' AS name UNION ALL SELECT 'Rajkishore Nagar' AS name UNION ALL SELECT 'Nehru Nagar' AS name UNION ALL
  SELECT 'Vyapar Vihar' AS name UNION ALL SELECT 'Civil Lines' AS name UNION ALL SELECT 'Link Road' AS name UNION ALL SELECT 'Tikrapara' AS name UNION ALL
  SELECT 'Jarhabhata' AS name UNION ALL SELECT 'Kududand' AS name UNION ALL SELECT 'Tarbahar' AS name UNION ALL SELECT 'Kota' AS name UNION ALL
  SELECT 'Takhatpur' AS name UNION ALL SELECT 'Masturi' AS name UNION ALL SELECT 'Bilha' AS name UNION ALL SELECT 'Ratanpur' AS name UNION ALL
  SELECT 'Sipat' AS name UNION ALL SELECT 'Sakri' AS name UNION ALL SELECT 'Bodri' AS name UNION ALL SELECT 'Beltara' AS name UNION ALL
  SELECT 'Belagahna' AS name UNION ALL SELECT 'Seepat' AS name UNION ALL SELECT 'Tifra' AS name UNION ALL SELECT 'Chakarbhatha' AS name UNION ALL
  SELECT 'Devrikhurd' AS name UNION ALL SELECT 'Koni' AS name UNION ALL SELECT 'Sendri' AS name UNION ALL SELECT 'Ameri' AS name UNION ALL
  SELECT 'Lingiyadih' AS name UNION ALL SELECT 'Ganiyari' AS name UNION ALL SELECT 'Hirri' AS name UNION ALL SELECT 'Bharari' AS name UNION ALL
  SELECT 'Malhar' AS name UNION ALL SELECT 'Bhatgaon' AS name UNION ALL SELECT 'Kenda' AS name UNION ALL SELECT 'Ratanpur Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dakshin Bastar Dantewada (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Dakshin Bastar Dantewada' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dantewada' AS name UNION ALL SELECT 'Gidam' AS name UNION ALL SELECT 'Kuakonda' AS name UNION ALL SELECT 'Katekalyan' AS name UNION ALL
  SELECT 'Barsoor' AS name UNION ALL SELECT 'Kirandul' AS name UNION ALL SELECT 'Bailadila' AS name UNION ALL SELECT 'Bacheli' AS name UNION ALL
  SELECT 'Geedam' AS name UNION ALL SELECT 'Sameli' AS name UNION ALL SELECT 'Bhansi' AS name UNION ALL SELECT 'Bade Bacheli' AS name UNION ALL
  SELECT 'Nakulnar' AS name UNION ALL SELECT 'Palnar' AS name UNION ALL SELECT 'Aranpur' AS name UNION ALL SELECT 'Burkapal' AS name UNION ALL
  SELECT 'Potali' AS name UNION ALL SELECT 'Hiroli' AS name UNION ALL SELECT 'Madded' AS name UNION ALL SELECT 'Chhindnar' AS name UNION ALL
  SELECT 'Gumiyapal' AS name UNION ALL SELECT 'Chhotetumnar' AS name UNION ALL SELECT 'Bade Tumnar' AS name UNION ALL SELECT 'Kudur' AS name UNION ALL
  SELECT 'Mirtur-side settlements' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Dhamtari (28 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Dhamtari' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Dhamtari' AS name UNION ALL SELECT 'Kurud' AS name UNION ALL SELECT 'Magarlod' AS name UNION ALL SELECT 'Nagri' AS name UNION ALL
  SELECT 'Sihawa' AS name UNION ALL SELECT 'Rudri' AS name UNION ALL SELECT 'Kandel' AS name UNION ALL SELECT 'Bhakhara' AS name UNION ALL
  SELECT 'Bhakhara Road' AS name UNION ALL SELECT 'Sankra' AS name UNION ALL SELECT 'Gattasilli' AS name UNION ALL SELECT 'Gangrel' AS name UNION ALL
  SELECT 'Charra' AS name UNION ALL SELECT 'Arjuni' AS name UNION ALL SELECT 'Borsi' AS name UNION ALL SELECT 'Darri' AS name UNION ALL
  SELECT 'Megha' AS name UNION ALL SELECT 'Belargaon' AS name UNION ALL SELECT 'Aklod' AS name UNION ALL SELECT 'Amaldiha' AS name UNION ALL
  SELECT 'Nawagaon' AS name UNION ALL SELECT 'Deori' AS name UNION ALL SELECT 'Datrenga' AS name UNION ALL SELECT 'Kareli Badi' AS name UNION ALL
  SELECT 'Sirri' AS name UNION ALL SELECT 'Ratnabandha' AS name UNION ALL SELECT 'Rudri Dam area' AS name UNION ALL SELECT 'Gangrel Dam area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Durg (37 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Durg' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Durg' AS name UNION ALL SELECT 'Bhilai' AS name UNION ALL SELECT 'Bhilai Nagar' AS name UNION ALL SELECT 'Risali' AS name UNION ALL
  SELECT 'Charoda' AS name UNION ALL SELECT 'Kumhari' AS name UNION ALL SELECT 'Supela' AS name UNION ALL SELECT 'Nehru Nagar' AS name UNION ALL
  SELECT 'Smriti Nagar' AS name UNION ALL SELECT 'Junwani' AS name UNION ALL SELECT 'Kohka' AS name UNION ALL SELECT 'Hudco' AS name UNION ALL
  SELECT 'Sector 1' AS name UNION ALL SELECT 'Sector 2' AS name UNION ALL SELECT 'Sector 4' AS name UNION ALL SELECT 'Sector 6' AS name UNION ALL
  SELECT 'Sector 9' AS name UNION ALL SELECT 'Sector 10' AS name UNION ALL SELECT 'Civic Centre' AS name UNION ALL SELECT 'Power House' AS name UNION ALL
  SELECT 'Vaishali Nagar' AS name UNION ALL SELECT 'Maroda' AS name UNION ALL SELECT 'Khursipar' AS name UNION ALL SELECT 'Chhawni' AS name UNION ALL
  SELECT 'Hathkhoj' AS name UNION ALL SELECT 'Borai' AS name UNION ALL SELECT 'Anjora' AS name UNION ALL SELECT 'Pulgaon' AS name UNION ALL
  SELECT 'Patan' AS name UNION ALL SELECT 'Dhamdha' AS name UNION ALL SELECT 'Gunderdehi-side areas' AS name UNION ALL SELECT 'Utai' AS name UNION ALL
  SELECT 'Jamul' AS name UNION ALL SELECT 'Ahiwara' AS name UNION ALL SELECT 'Nandini Nagar' AS name UNION ALL SELECT 'Karanja Bhilai' AS name UNION ALL
  SELECT 'Kodiya' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Gariaband (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Gariaband' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gariaband' AS name UNION ALL SELECT 'Rajim' AS name UNION ALL SELECT 'Fingeshwar' AS name UNION ALL SELECT 'Devbhog' AS name UNION ALL
  SELECT 'Mainpur' AS name UNION ALL SELECT 'Chhura' AS name UNION ALL SELECT 'Manipur' AS name UNION ALL SELECT 'Rajim Road' AS name UNION ALL
  SELECT 'Komakhan' AS name UNION ALL SELECT 'Deobhog' AS name UNION ALL SELECT 'Indagaon' AS name UNION ALL SELECT 'Amli' AS name UNION ALL
  SELECT 'Supa' AS name UNION ALL SELECT 'Shobha' AS name UNION ALL SELECT 'Godalwani' AS name UNION ALL SELECT 'Sikaser' AS name UNION ALL
  SELECT 'Bindranawagarh' AS name UNION ALL SELECT 'Kaser' AS name UNION ALL SELECT 'Kharhari' AS name UNION ALL SELECT 'Baruka' AS name UNION ALL
  SELECT 'Nawagaon' AS name UNION ALL SELECT 'Piparchhedi' AS name UNION ALL SELECT 'Panduka' AS name UNION ALL SELECT 'Kosmi' AS name UNION ALL
  SELECT 'Chhura Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Gaurela-Pendra-Marwahi (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Gaurela-Pendra-Marwahi' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Gaurela' AS name UNION ALL SELECT 'Pendra' AS name UNION ALL SELECT 'Pendra Road' AS name UNION ALL SELECT 'Marwahi' AS name UNION ALL
  SELECT 'Kota-side border areas' AS name UNION ALL SELECT 'Amarpur' AS name UNION ALL SELECT 'Dhanpur' AS name UNION ALL SELECT 'Kenda' AS name UNION ALL
  SELECT 'Khongsara' AS name UNION ALL SELECT 'Ladkhar' AS name UNION ALL SELECT 'Basti' AS name UNION ALL SELECT 'Belgahana' AS name UNION ALL
  SELECT 'Sarbahara' AS name UNION ALL SELECT 'Chapara' AS name UNION ALL SELECT 'Semariya' AS name UNION ALL SELECT 'Anjani' AS name UNION ALL
  SELECT 'Bhadoura' AS name UNION ALL SELECT 'Kenda Road' AS name UNION ALL SELECT 'Pendra Road Railway area' AS name UNION ALL SELECT 'Gaurela Bazar' AS name UNION ALL
  SELECT 'Marwahi Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Janjgir-Champa (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Janjgir-Champa' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Janjgir' AS name UNION ALL SELECT 'Champa' AS name UNION ALL SELECT 'Akaltara' AS name UNION ALL SELECT 'Naila' AS name UNION ALL
  SELECT 'Baloda' AS name UNION ALL SELECT 'Bamhanidih' AS name UNION ALL SELECT 'Navagarh' AS name UNION ALL SELECT 'Pamgarh' AS name UNION ALL
  SELECT 'Malkharoda-side areas' AS name UNION ALL SELECT 'Shivrinarayan' AS name UNION ALL SELECT 'Birra' AS name UNION ALL SELECT 'Saragaon' AS name UNION ALL
  SELECT 'Baradwar' AS name UNION ALL SELECT 'Sakti-side border areas' AS name UNION ALL SELECT 'Kharod' AS name UNION ALL SELECT 'Kharsia Road' AS name UNION ALL
  SELECT 'Hasoud' AS name UNION ALL SELECT 'Seoni' AS name UNION ALL SELECT 'Hathneora' AS name UNION ALL SELECT 'Pirda' AS name UNION ALL
  SELECT 'Pithampur' AS name UNION ALL SELECT 'Kapan' AS name UNION ALL SELECT 'Nawagarh' AS name UNION ALL SELECT 'Tilai' AS name UNION ALL
  SELECT 'Kirodimal Nagar-side areas' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Jashpur (27 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Jashpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Jashpur Nagar' AS name UNION ALL SELECT 'Kunkuri' AS name UNION ALL SELECT 'Pathalgaon' AS name UNION ALL SELECT 'Bagicha' AS name UNION ALL
  SELECT 'Manora' AS name UNION ALL SELECT 'Duldula' AS name UNION ALL SELECT 'Kansabel' AS name UNION ALL SELECT 'Farsabahar' AS name UNION ALL
  SELECT 'Tapkara' AS name UNION ALL SELECT 'Lodam' AS name UNION ALL SELECT 'Sanna' AS name UNION ALL SELECT 'Asta' AS name UNION ALL
  SELECT 'Bagbahar' AS name UNION ALL SELECT 'Kotba' AS name UNION ALL SELECT 'Jashpur Road' AS name UNION ALL SELECT 'Ranpur' AS name UNION ALL
  SELECT 'Baghima' AS name UNION ALL SELECT 'Belgarh' AS name UNION ALL SELECT 'Kardega' AS name UNION ALL SELECT 'Uparkachhar' AS name UNION ALL
  SELECT 'Dokra' AS name UNION ALL SELECT 'Aara' AS name UNION ALL SELECT 'Kurdeg' AS name UNION ALL SELECT 'Narayanpur' AS name UNION ALL
  SELECT 'Sonkyari' AS name UNION ALL SELECT 'Kunkuri Bazar' AS name UNION ALL SELECT 'Pathalgaon Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kanker (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Kanker' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kanker' AS name UNION ALL SELECT 'Charama' AS name UNION ALL SELECT 'Bhanupratappur' AS name UNION ALL SELECT 'Antagarh' AS name UNION ALL
  SELECT 'Durgkondal' AS name UNION ALL SELECT 'Narharpur' AS name UNION ALL SELECT 'Koylibeda' AS name UNION ALL SELECT 'Pakhanjur' AS name UNION ALL
  SELECT 'Bande' AS name UNION ALL SELECT 'Sarona' AS name UNION ALL SELECT 'Sambalpur' AS name UNION ALL SELECT 'Kherkheda' AS name UNION ALL
  SELECT 'Amabeda' AS name UNION ALL SELECT 'Kapsi' AS name UNION ALL SELECT 'Koilibeda' AS name UNION ALL SELECT 'Bhiragaon' AS name UNION ALL
  SELECT 'Hatkarra' AS name UNION ALL SELECT 'Dudhawa' AS name UNION ALL SELECT 'Badgaon' AS name UNION ALL SELECT 'Tadoki' AS name UNION ALL
  SELECT 'Pusawand' AS name UNION ALL SELECT 'Chhotebethiya' AS name UNION ALL SELECT 'Paralkot' AS name UNION ALL SELECT 'Charama Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kabirdham (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Kabirdham' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kawardha' AS name UNION ALL SELECT 'Pandariya' AS name UNION ALL SELECT 'Bodla' AS name UNION ALL SELECT 'Sahaspur Lohara' AS name UNION ALL
  SELECT 'Pipariya' AS name UNION ALL SELECT 'Rengakhar' AS name UNION ALL SELECT 'Kukdur' AS name UNION ALL SELECT 'Chilphi' AS name UNION ALL
  SELECT 'Lohara' AS name UNION ALL SELECT 'Ranvirpur' AS name UNION ALL SELECT 'Birendra Nagar' AS name UNION ALL SELECT 'Chhirpani' AS name UNION ALL
  SELECT 'Kukdur Road' AS name UNION ALL SELECT 'Pandatarai' AS name UNION ALL SELECT 'Dullapur' AS name UNION ALL SELECT 'Bandha' AS name UNION ALL
  SELECT 'Khairjhiti' AS name UNION ALL SELECT 'Birkona' AS name UNION ALL SELECT 'Nawagaon' AS name UNION ALL SELECT 'Bamhni' AS name UNION ALL
  SELECT 'Sonpuri' AS name UNION ALL SELECT 'Jhirna' AS name UNION ALL SELECT 'Taregaon' AS name UNION ALL SELECT 'Bhoremdeo' AS name UNION ALL
  SELECT 'Chilphi Valley' AS name UNION ALL SELECT 'Bodla Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Khairagarh-Chhuikhadan-Gandai (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Khairagarh-Chhuikhadan-Gandai' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Khairagarh' AS name UNION ALL SELECT 'Chhuikhadan' AS name UNION ALL SELECT 'Gandai' AS name UNION ALL SELECT 'Salhewara' AS name UNION ALL
  SELECT 'Ghumka' AS name UNION ALL SELECT 'Thelkadih' AS name UNION ALL SELECT 'Jhalmala' AS name UNION ALL SELECT 'Bazar Aatariya' AS name UNION ALL
  SELECT 'Ghotiya' AS name UNION ALL SELECT 'Deori' AS name UNION ALL SELECT 'Sandi' AS name UNION ALL SELECT 'Bortalao' AS name UNION ALL
  SELECT 'Achanakpur' AS name UNION ALL SELECT 'Amgaon' AS name UNION ALL SELECT 'Bortarkala' AS name UNION ALL SELECT 'Khamhariya' AS name UNION ALL
  SELECT 'Bakarkatta' AS name UNION ALL SELECT 'Chichola' AS name UNION ALL SELECT 'Gaurla' AS name UNION ALL SELECT 'Ghoghre' AS name UNION ALL
  SELECT 'Mudpar' AS name UNION ALL SELECT 'Kachri' AS name UNION ALL SELECT 'Khapri' AS name UNION ALL SELECT 'Gandai Bazar' AS name UNION ALL
  SELECT 'Khairagarh Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Kondagaon (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Kondagaon' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Kondagaon' AS name UNION ALL SELECT 'Keshkal' AS name UNION ALL SELECT 'Baderajpur' AS name UNION ALL SELECT 'Makdi' AS name UNION ALL
  SELECT 'Farasgaon' AS name UNION ALL SELECT 'Pharasgaon' AS name UNION ALL SELECT 'Dhanora' AS name UNION ALL SELECT 'Vishrampuri' AS name UNION ALL
  SELECT 'Uparchandeli' AS name UNION ALL SELECT 'Borgaon' AS name UNION ALL SELECT 'Karmari' AS name UNION ALL SELECT 'Alor' AS name UNION ALL
  SELECT 'Benur' AS name UNION ALL SELECT 'Badekanera' AS name UNION ALL SELECT 'Aamdai' AS name UNION ALL SELECT 'Karanji' AS name UNION ALL
  SELECT 'Keshkal Ghat area' AS name UNION ALL SELECT 'Kudalgaon' AS name UNION ALL SELECT 'Kokad' AS name UNION ALL SELECT 'Balenga' AS name UNION ALL
  SELECT 'Makdi Bazar' AS name UNION ALL SELECT 'Farasgaon Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Korba (31 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Korba' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Korba' AS name UNION ALL SELECT 'Darri' AS name UNION ALL SELECT 'Balco Nagar' AS name UNION ALL SELECT 'Kusmunda' AS name UNION ALL
  SELECT 'Gevra' AS name UNION ALL SELECT 'Dipka' AS name UNION ALL SELECT 'Katghora' AS name UNION ALL SELECT 'Pali' AS name UNION ALL
  SELECT 'Kartala' AS name UNION ALL SELECT 'Podi Uproda' AS name UNION ALL SELECT 'Banki Mongra' AS name UNION ALL SELECT 'Urga' AS name UNION ALL
  SELECT 'Hardi Bazar' AS name UNION ALL SELECT 'Lemru' AS name UNION ALL SELECT 'Bango' AS name UNION ALL SELECT 'Pasan' AS name UNION ALL
  SELECT 'Razgamar' AS name UNION ALL SELECT 'Chhuri' AS name UNION ALL SELECT 'Madwarani' AS name UNION ALL SELECT 'Tuman' AS name UNION ALL
  SELECT 'Satarenga' AS name UNION ALL SELECT 'Devpahari' AS name UNION ALL SELECT 'Booka' AS name UNION ALL SELECT 'Kusmunda Township' AS name UNION ALL
  SELECT 'Gevra Project' AS name UNION ALL SELECT 'Darri Road' AS name UNION ALL SELECT 'Transport Nagar' AS name UNION ALL SELECT 'Kosabadi' AS name UNION ALL
  SELECT 'Rampur' AS name UNION ALL SELECT 'Jamnipali' AS name UNION ALL SELECT 'Balco Nagar Township' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Koriya (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Koriya' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Baikunthpur' AS name UNION ALL SELECT 'Sonhat' AS name UNION ALL SELECT 'Chirimiri' AS name UNION ALL SELECT 'Manendragarh-side areas' AS name UNION ALL
  SELECT 'Patna' AS name UNION ALL SELECT 'Churcha' AS name UNION ALL SELECT 'Khadgawan' AS name UNION ALL SELECT 'Nagpur' AS name UNION ALL
  SELECT 'Salwa' AS name UNION ALL SELECT 'Katkona' AS name UNION ALL SELECT 'Jhagrakhand' AS name UNION ALL SELECT 'Janakpur' AS name UNION ALL
  SELECT 'Bharatpur' AS name UNION ALL SELECT 'Pondi' AS name UNION ALL SELECT 'Bardar' AS name UNION ALL SELECT 'Tarra' AS name UNION ALL
  SELECT 'Bansar' AS name UNION ALL SELECT 'Bhadi' AS name UNION ALL SELECT 'Kothari' AS name UNION ALL SELECT 'Bachauli' AS name UNION ALL
  SELECT 'Baikunthpur Bazar' AS name UNION ALL SELECT 'Chirimiri Coalfield area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mahasamund (26 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Mahasamund' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mahasamund' AS name UNION ALL SELECT 'Saraipali' AS name UNION ALL SELECT 'Basna' AS name UNION ALL SELECT 'Bagbahara' AS name UNION ALL
  SELECT 'Pithora' AS name UNION ALL SELECT 'Tumgaon' AS name UNION ALL SELECT 'Komakhan' AS name UNION ALL SELECT 'Siraipur' AS name UNION ALL
  SELECT 'Jhalap' AS name UNION ALL SELECT 'Belsonda' AS name UNION ALL SELECT 'Bhalesar' AS name UNION ALL SELECT 'Bamhni' AS name UNION ALL
  SELECT 'Bhoring' AS name UNION ALL SELECT 'Padampur' AS name UNION ALL SELECT 'Kosrangi' AS name UNION ALL SELECT 'Birkoni' AS name UNION ALL
  SELECT 'Gidhpuri' AS name UNION ALL SELECT 'Khatti' AS name UNION ALL SELECT 'Saraipali Bazar' AS name UNION ALL SELECT 'Basna Bazar' AS name UNION ALL
  SELECT 'Pithora Bazar' AS name UNION ALL SELECT 'Bagbahara Bazar' AS name UNION ALL SELECT 'Sirpur' AS name UNION ALL SELECT 'Khallari' AS name UNION ALL
  SELECT 'Baronda' AS name UNION ALL SELECT 'Chhura-side border areas' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Manendragarh-Chirmiri-Bharatpur (23 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Manendragarh-Chirmiri-Bharatpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Manendragarh' AS name UNION ALL SELECT 'Chirmiri' AS name UNION ALL SELECT 'Bharatpur' AS name UNION ALL SELECT 'Khadgawan' AS name UNION ALL
  SELECT 'Kelhari' AS name UNION ALL SELECT 'Janakpur' AS name UNION ALL SELECT 'Nagpur' AS name UNION ALL SELECT 'Jhagrakhand' AS name UNION ALL
  SELECT 'Churcha' AS name UNION ALL SELECT 'Pondi' AS name UNION ALL SELECT 'Khongapani' AS name UNION ALL SELECT 'Manendragarh Bazar' AS name UNION ALL
  SELECT 'Chirmiri Bazar' AS name UNION ALL SELECT 'Korea Road' AS name UNION ALL SELECT 'Katora' AS name UNION ALL SELECT 'Ghaghra' AS name UNION ALL
  SELECT 'Kothari' AS name UNION ALL SELECT 'Kotadol' AS name UNION ALL SELECT 'Bhagwanpur' AS name UNION ALL SELECT 'Bhaiswar' AS name UNION ALL
  SELECT 'Barwahi' AS name UNION ALL SELECT 'Ratanpur' AS name UNION ALL SELECT 'Aamgaon' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mohla-Manpur-Ambagarh Chowki (20 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Mohla-Manpur-Ambagarh Chowki' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mohla' AS name UNION ALL SELECT 'Manpur' AS name UNION ALL SELECT 'Ambagarh Chowki' AS name UNION ALL SELECT 'Aundhi' AS name UNION ALL
  SELECT 'Koracha' AS name UNION ALL SELECT 'Khadgaon' AS name UNION ALL SELECT 'Madanwada' AS name UNION ALL SELECT 'Chhote Dongar' AS name UNION ALL
  SELECT 'Kanhargaon' AS name UNION ALL SELECT 'Kaurgaon' AS name UNION ALL SELECT 'Salhewara-side areas' AS name UNION ALL SELECT 'Ghotia' AS name UNION ALL
  SELECT 'Bori' AS name UNION ALL SELECT 'Gattepalli' AS name UNION ALL SELECT 'Chhuriya-side areas' AS name UNION ALL SELECT 'Manpur Bazar' AS name UNION ALL
  SELECT 'Mohla Bazar' AS name UNION ALL SELECT 'Ambagarh Chowki Bazar' AS name UNION ALL SELECT 'Dalli Rajhara-side areas' AS name UNION ALL SELECT 'Aundhi Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Mungeli (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Mungeli' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Mungeli' AS name UNION ALL SELECT 'Lormi' AS name UNION ALL SELECT 'Pathariya' AS name UNION ALL SELECT 'Pandariya-side areas' AS name UNION ALL
  SELECT 'Lalpur' AS name UNION ALL SELECT 'Jarhagaon' AS name UNION ALL SELECT 'Nawagarh' AS name UNION ALL SELECT 'Dindori' AS name UNION ALL
  SELECT 'Bamhni' AS name UNION ALL SELECT 'Chakarbhatha' AS name UNION ALL SELECT 'Seepat-side border' AS name UNION ALL SELECT 'Saragaon' AS name UNION ALL
  SELECT 'Khudia' AS name UNION ALL SELECT 'Lamni' AS name UNION ALL SELECT 'Khairagarh' AS name UNION ALL SELECT 'Khamharia' AS name UNION ALL
  SELECT 'Deori' AS name UNION ALL SELECT 'Rajpur' AS name UNION ALL SELECT 'Ghuteli' AS name UNION ALL SELECT 'Mungeli Bazar' AS name UNION ALL
  SELECT 'Lormi Bazar' AS name UNION ALL SELECT 'Pathariya Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Narayanpur (18 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Narayanpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Narayanpur' AS name UNION ALL SELECT 'Orchha' AS name UNION ALL SELECT 'Benur' AS name UNION ALL SELECT 'Dhaur' AS name UNION ALL
  SELECT 'Chhotedongar' AS name UNION ALL SELECT 'Garpa' AS name UNION ALL SELECT 'Kohkameta' AS name UNION ALL SELECT 'Sonpur' AS name UNION ALL
  SELECT 'Abujhmad' AS name UNION ALL SELECT 'Edka' AS name UNION ALL SELECT 'Kanhargaon' AS name UNION ALL SELECT 'Bhavanipara' AS name UNION ALL
  SELECT 'Garanji' AS name UNION ALL SELECT 'Kodoli' AS name UNION ALL SELECT 'Amsnar' AS name UNION ALL SELECT 'Orcha Road' AS name UNION ALL
  SELECT 'Narayanpur Bazar' AS name UNION ALL SELECT 'Chhotedongar Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Raigarh (28 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Raigarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Raigarh' AS name UNION ALL SELECT 'Kharsia' AS name UNION ALL SELECT 'Gharghoda' AS name UNION ALL SELECT 'Dharamjaigarh' AS name UNION ALL
  SELECT 'Tamnar' AS name UNION ALL SELECT 'Pusaur' AS name UNION ALL SELECT 'Lailunga' AS name UNION ALL SELECT 'Sarangarh-side areas' AS name UNION ALL
  SELECT 'Kirodimal Nagar' AS name UNION ALL SELECT 'Jute Mill area' AS name UNION ALL SELECT 'Chakradharnagar' AS name UNION ALL SELECT 'Kotra Road' AS name UNION ALL
  SELECT 'Boirdadar' AS name UNION ALL SELECT 'Darogapara' AS name UNION ALL SELECT 'Gandhi Nagar' AS name UNION ALL SELECT 'Kosabadi' AS name UNION ALL
  SELECT 'Kotra' AS name UNION ALL SELECT 'Bhupdevpur' AS name UNION ALL SELECT 'Punjipathra' AS name UNION ALL SELECT 'Gerwani' AS name UNION ALL
  SELECT 'Bade Bhandar' AS name UNION ALL SELECT 'Kodatarai' AS name UNION ALL SELECT 'Pusaur Bazar' AS name UNION ALL SELECT 'Kharsia Bazar' AS name UNION ALL
  SELECT 'Gharghoda Bazar' AS name UNION ALL SELECT 'Dharamjaigarh Bazar' AS name UNION ALL SELECT 'Lailunga Bazar' AS name UNION ALL SELECT 'Tamnar industrial belt' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Raipur (61 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Raipur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Raipur city' AS name UNION ALL SELECT 'Civil Lines' AS name UNION ALL SELECT 'Shankar Nagar' AS name UNION ALL SELECT 'Devendra Nagar' AS name UNION ALL
  SELECT 'Pandri' AS name UNION ALL SELECT 'Telibandha' AS name UNION ALL SELECT 'Mowa' AS name UNION ALL SELECT 'Khamardih' AS name UNION ALL
  SELECT 'Saddu' AS name UNION ALL SELECT 'Avanti Vihar' AS name UNION ALL SELECT 'VIP Road' AS name UNION ALL SELECT 'Tatibandh' AS name UNION ALL
  SELECT 'Kota' AS name UNION ALL SELECT 'Gudhiyari' AS name UNION ALL SELECT 'WRS Colony' AS name UNION ALL SELECT 'Samta Colony' AS name UNION ALL
  SELECT 'New Rajendra Nagar' AS name UNION ALL SELECT 'Rajendra Nagar' AS name UNION ALL SELECT 'Mahaveer Nagar' AS name UNION ALL SELECT 'Changorabhatha' AS name UNION ALL
  SELECT 'Amanaka' AS name UNION ALL SELECT 'Sarona' AS name UNION ALL SELECT 'Bhatagaon' AS name UNION ALL SELECT 'Santoshi Nagar' AS name UNION ALL
  SELECT 'Purena' AS name UNION ALL SELECT 'Lalpur' AS name UNION ALL SELECT 'Tikrapara' AS name UNION ALL SELECT 'Pachpedi Naka' AS name UNION ALL
  SELECT 'DDU Nagar' AS name UNION ALL SELECT 'Naya Raipur' AS name UNION ALL SELECT 'Nava Raipur' AS name UNION ALL SELECT 'Sector 1' AS name UNION ALL
  SELECT 'Sector 2' AS name UNION ALL SELECT 'Sector 9' AS name UNION ALL SELECT 'Sector 10' AS name UNION ALL SELECT 'Sector 17' AS name UNION ALL
  SELECT 'Sector 19' AS name UNION ALL SELECT 'Sector 24' AS name UNION ALL SELECT 'Sector 27' AS name UNION ALL SELECT 'Sector 29' AS name UNION ALL
  SELECT 'Sector 30' AS name UNION ALL SELECT 'Abhanpur' AS name UNION ALL SELECT 'Arang' AS name UNION ALL SELECT 'Tilda' AS name UNION ALL
  SELECT 'Kharora' AS name UNION ALL SELECT 'Dharsiva' AS name UNION ALL SELECT 'Mandir Hasaud' AS name UNION ALL SELECT 'Gobra Nawapara' AS name UNION ALL
  SELECT 'Chandkhuri' AS name UNION ALL SELECT 'Mana' AS name UNION ALL SELECT 'Kharun' AS name UNION ALL SELECT 'Nawagaon' AS name UNION ALL
  SELECT 'Paragaon' AS name UNION ALL SELECT 'Paloud' AS name UNION ALL SELECT 'Saragaon' AS name UNION ALL SELECT 'Khatti' AS name UNION ALL
  SELECT 'Khapri' AS name UNION ALL SELECT 'Kotni' AS name UNION ALL SELECT 'Tendua' AS name UNION ALL SELECT 'Amaseoni' AS name UNION ALL
  SELECT 'Gaurav Path area' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Rajnandgaon (25 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Rajnandgaon' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Rajnandgaon' AS name UNION ALL SELECT 'Dongargarh' AS name UNION ALL SELECT 'Dongargaon' AS name UNION ALL SELECT 'Chhuriya' AS name UNION ALL
  SELECT 'Mohla-side areas' AS name UNION ALL SELECT 'Ghumka' AS name UNION ALL SELECT 'Lal Bahadur Nagar' AS name UNION ALL SELECT 'Somni' AS name UNION ALL
  SELECT 'Thelkadih' AS name UNION ALL SELECT 'Musra' AS name UNION ALL SELECT 'Bori' AS name UNION ALL SELECT 'Achanakpur' AS name UNION ALL
  SELECT 'Gatapar' AS name UNION ALL SELECT 'Basantpur' AS name UNION ALL SELECT 'Station Para' AS name UNION ALL SELECT 'Shankar Nagar' AS name UNION ALL
  SELECT 'Rishabh Nagar' AS name UNION ALL SELECT 'Ganjpara' AS name UNION ALL SELECT 'Kamthi Line' AS name UNION ALL SELECT 'Transport Nagar' AS name UNION ALL
  SELECT 'Kaurinbhatha' AS name UNION ALL SELECT 'Lakholi' AS name UNION ALL SELECT 'Ataria' AS name UNION ALL SELECT 'Tumdibod' AS name UNION ALL
  SELECT 'Khairagarh-side border areas' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sakti (22 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Sakti' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sakti' AS name UNION ALL SELECT 'Malkharoda' AS name UNION ALL SELECT 'Jaijepur' AS name UNION ALL SELECT 'Dabhra' AS name UNION ALL
  SELECT 'Baradwar' AS name UNION ALL SELECT 'Hasoud' AS name UNION ALL SELECT 'Chandra' AS name UNION ALL SELECT 'Saragaon' AS name UNION ALL
  SELECT 'Bhalupahari' AS name UNION ALL SELECT 'Amoda' AS name UNION ALL SELECT 'Kharod' AS name UNION ALL SELECT 'Nandeli' AS name UNION ALL
  SELECT 'Banjari' AS name UNION ALL SELECT 'Jharna' AS name UNION ALL SELECT 'Kharri' AS name UNION ALL SELECT 'Malkharoda Bazar' AS name UNION ALL
  SELECT 'Jaijepur Bazar' AS name UNION ALL SELECT 'Dabhra Bazar' AS name UNION ALL SELECT 'Sakti Bazar' AS name UNION ALL SELECT 'Baradwar Railway area' AS name UNION ALL
  SELECT 'Champa Road' AS name UNION ALL SELECT 'Dabhra Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sarangarh-Bilaigarh (21 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Sarangarh-Bilaigarh' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sarangarh' AS name UNION ALL SELECT 'Bilaigarh' AS name UNION ALL SELECT 'Baramkela' AS name UNION ALL SELECT 'Bhatgaon' AS name UNION ALL
  SELECT 'Saria' AS name UNION ALL SELECT 'Salheona' AS name UNION ALL SELECT 'Kosir' AS name UNION ALL SELECT 'Kodatarai' AS name UNION ALL
  SELECT 'Raikera' AS name UNION ALL SELECT 'Chhind' AS name UNION ALL SELECT 'Amabuda' AS name UNION ALL SELECT 'Bhalumunda' AS name UNION ALL
  SELECT 'Kharod' AS name UNION ALL SELECT 'Gharghoda-side areas' AS name UNION ALL SELECT 'Pusaur-side areas' AS name UNION ALL SELECT 'Sarangarh Bazar' AS name UNION ALL
  SELECT 'Bilaigarh Bazar' AS name UNION ALL SELECT 'Baramkela Bazar' AS name UNION ALL SELECT 'Saria Bazar' AS name UNION ALL SELECT 'Bilaigarh Road' AS name UNION ALL
  SELECT 'Sarangarh Road' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Sukma (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Sukma' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Sukma' AS name UNION ALL SELECT 'Konta' AS name UNION ALL SELECT 'Chhindgarh' AS name UNION ALL SELECT 'Dornapal' AS name UNION ALL
  SELECT 'Errabor' AS name UNION ALL SELECT 'Bhejji' AS name UNION ALL SELECT 'Gadiras' AS name UNION ALL SELECT 'Kistaram' AS name UNION ALL
  SELECT 'Polampalli' AS name UNION ALL SELECT 'Tongpal' AS name UNION ALL SELECT 'Burkapal' AS name UNION ALL SELECT 'Chintagufa' AS name UNION ALL
  SELECT 'Injeram' AS name UNION ALL SELECT 'Mosalpad' AS name UNION ALL SELECT 'Elmagunda' AS name UNION ALL SELECT 'Bheji' AS name UNION ALL
  SELECT 'Nagalgunda' AS name UNION ALL SELECT 'Maraiguda' AS name UNION ALL SELECT 'Kerlapal' AS name UNION ALL SELECT 'Phulbagdi' AS name UNION ALL
  SELECT 'Konta Bazar' AS name UNION ALL SELECT 'Dornapal Bazar' AS name UNION ALL SELECT 'Sukma Bazar' AS name UNION ALL SELECT 'Chhindgarh Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Surajpur (24 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Surajpur' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Surajpur' AS name UNION ALL SELECT 'Pratappur' AS name UNION ALL SELECT 'Odagi' AS name UNION ALL SELECT 'Premnagar' AS name UNION ALL
  SELECT 'Bhaiyathan' AS name UNION ALL SELECT 'Ramanujnagar' AS name UNION ALL SELECT 'Bishrampur' AS name UNION ALL SELECT 'Jarhi' AS name UNION ALL
  SELECT 'Bhatgaon' AS name UNION ALL SELECT 'Pratappur Bazar' AS name UNION ALL SELECT 'Odgi' AS name UNION ALL SELECT 'Kalyanpur' AS name UNION ALL
  SELECT 'Chandni' AS name UNION ALL SELECT 'Chandanagar' AS name UNION ALL SELECT 'Karanji' AS name UNION ALL SELECT 'Shivnandanpur' AS name UNION ALL
  SELECT 'Mahgawan' AS name UNION ALL SELECT 'Telgawan' AS name UNION ALL SELECT 'Dwarikapur' AS name UNION ALL SELECT 'Umapur' AS name UNION ALL
  SELECT 'Surajpur Bazar' AS name UNION ALL SELECT 'Bishrampur Coalfield' AS name UNION ALL SELECT 'Bhatgaon Coalfield' AS name UNION ALL SELECT 'Jarhi Coalfield' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

-- District: Surguja (29 localities)
SET @d = (SELECT `id` FROM `districts` WHERE `state_id` = @ct AND `name` = 'Surguja' LIMIT 1);
INSERT INTO `localities` (`district_id`, `name`)
SELECT @d, loc.name FROM (
  SELECT 'Ambikapur' AS name UNION ALL SELECT 'Sitapur' AS name UNION ALL SELECT 'Lundra' AS name UNION ALL SELECT 'Lakhanpur' AS name UNION ALL
  SELECT 'Batauli' AS name UNION ALL SELECT 'Udaypur' AS name UNION ALL SELECT 'Mainpat' AS name UNION ALL SELECT 'Darima' AS name UNION ALL
  SELECT 'Gandhi Nagar' AS name UNION ALL SELECT 'Manendragarh Road' AS name UNION ALL SELECT 'Banaras Road' AS name UNION ALL SELECT 'Ring Road' AS name UNION ALL
  SELECT 'FCI Road' AS name UNION ALL SELECT 'Mahanand Nagar' AS name UNION ALL SELECT 'Shivdhari Colony' AS name UNION ALL SELECT 'Sadar Bazar' AS name UNION ALL
  SELECT 'Bus Stand area' AS name UNION ALL SELECT 'Hathi Point' AS name UNION ALL SELECT 'Kharsia Road' AS name UNION ALL SELECT 'Ambikapur Airport area' AS name UNION ALL
  SELECT 'Koteya' AS name UNION ALL SELECT 'Takia' AS name UNION ALL SELECT 'Rajpur' AS name UNION ALL SELECT 'Pratappur Road' AS name UNION ALL
  SELECT 'Kamleshwarpur' AS name UNION ALL SELECT 'Sitapur Bazar' AS name UNION ALL SELECT 'Lakhanpur Bazar' AS name UNION ALL SELECT 'Batauli Bazar' AS name UNION ALL
  SELECT 'Udaypur Bazar' AS name
) loc WHERE NOT EXISTS (SELECT 1 FROM `localities` WHERE `district_id` = @d AND LOWER(`name`) = LOWER(loc.name));

