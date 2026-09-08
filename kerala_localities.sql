-- ==========================================================================
-- UnlockRentals - Complete Kerala Districts & Localities Setup
-- Covers all 14 Districts and 1381 Localities
-- ==========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure State Exists
INSERT INTO `states` (`name`, `code`)
SELECT 'Kerala', 'KL'
WHERE NOT EXISTS (SELECT 1 FROM `states` WHERE `code` = 'KL' OR `name` = 'Kerala');

-- 2. Ensure Districts Exist
INSERT INTO `districts` (`state_id`, `name`)
SELECT s.id, d.name
FROM `states` s
CROSS JOIN (
    SELECT 'Alappuzha' AS name UNION ALL
    SELECT 'Ernakulam' AS name UNION ALL
    SELECT 'Idukki' AS name UNION ALL
    SELECT 'Kannur' AS name UNION ALL
    SELECT 'Kasaragod' AS name UNION ALL
    SELECT 'Kollam' AS name UNION ALL
    SELECT 'Kottayam' AS name UNION ALL
    SELECT 'Kozhikode' AS name UNION ALL
    SELECT 'Malappuram' AS name UNION ALL
    SELECT 'Palakkad' AS name UNION ALL
    SELECT 'Pathanamthitta' AS name UNION ALL
    SELECT 'Thiruvananthapuram' AS name UNION ALL
    SELECT 'Thrissur' AS name UNION ALL
    SELECT 'Wayanad' AS name
) d
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND NOT EXISTS (
      SELECT 1 FROM `districts` existing 
      WHERE existing.state_id = s.id AND existing.name = d.name
  );

-- 3. Insert Localities District-by-District
-- --------------------------------------------------------------------------
-- District: Alappuzha (93 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Ala' AS name UNION ALL
    SELECT 'Alappuzha' AS name UNION ALL
    SELECT 'Alappuzha Beach' AS name UNION ALL
    SELECT 'Alappuzha Town' AS name UNION ALL
    SELECT 'Ambalappuzha' AS name UNION ALL
    SELECT 'Arattupuzha' AS name UNION ALL
    SELECT 'Arookkutty' AS name UNION ALL
    SELECT 'Aroor' AS name UNION ALL
    SELECT 'Arthunkal' AS name UNION ALL
    SELECT 'Aryad' AS name UNION ALL
    SELECT 'Bharanikkavu' AS name UNION ALL
    SELECT 'Champakulam' AS name UNION ALL
    SELECT 'Chengannur' AS name UNION ALL
    SELECT 'Chennithala' AS name UNION ALL
    SELECT 'Cheppad' AS name UNION ALL
    SELECT 'Cheriyanad' AS name UNION ALL
    SELECT 'Cherthala' AS name UNION ALL
    SELECT 'Cheruthana' AS name UNION ALL
    SELECT 'Chingoli' AS name UNION ALL
    SELECT 'Chunakkara' AS name UNION ALL
    SELECT 'Edathva' AS name UNION ALL
    SELECT 'Ennakkad' AS name UNION ALL
    SELECT 'Ezhupunna' AS name UNION ALL
    SELECT 'Haripad' AS name UNION ALL
    SELECT 'Kadakkarappally' AS name UNION ALL
    SELECT 'Kainakari' AS name UNION ALL
    SELECT 'Kalarcode' AS name UNION ALL
    SELECT 'Kalavoor' AS name UNION ALL
    SELECT 'Kandallur' AS name UNION ALL
    SELECT 'Kanjikuzhi' AS name UNION ALL
    SELECT 'Kannamangalam' AS name UNION ALL
    SELECT 'Karthikappally' AS name UNION ALL
    SELECT 'Karuvatta' AS name UNION ALL
    SELECT 'Kattanam' AS name UNION ALL
    SELECT 'Kavalam' AS name UNION ALL
    SELECT 'Kayamkulam' AS name UNION ALL
    SELECT 'Keerikkad' AS name UNION ALL
    SELECT 'Kodamthuruthu' AS name UNION ALL
    SELECT 'Kokkothamangalam' AS name UNION ALL
    SELECT 'Kommady' AS name UNION ALL
    SELECT 'Krishnapuram' AS name UNION ALL
    SELECT 'Kumarapuram' AS name UNION ALL
    SELECT 'Kunnumma' AS name UNION ALL
    SELECT 'Kurattissery' AS name UNION ALL
    SELECT 'Kuthiyathode' AS name UNION ALL
    SELECT 'Kuttanad' AS name UNION ALL
    SELECT 'Mannancherry' AS name UNION ALL
    SELECT 'Mannar' AS name UNION ALL
    SELECT 'Mararikkulam' AS name UNION ALL
    SELECT 'Mararikulam' AS name UNION ALL
    SELECT 'Mavelikkara' AS name UNION ALL
    SELECT 'Muhamma' AS name UNION ALL
    SELECT 'Mulakkuzha' AS name UNION ALL
    SELECT 'Mullakkal' AS name UNION ALL
    SELECT 'Muthukulam' AS name UNION ALL
    SELECT 'Muttar' AS name UNION ALL
    SELECT 'Nedumudi' AS name UNION ALL
    SELECT 'Neelamperoor' AS name UNION ALL
    SELECT 'Nooranad' AS name UNION ALL
    SELECT 'Palamel' AS name UNION ALL
    SELECT 'Pallippad' AS name UNION ALL
    SELECT 'Pallippuram' AS name UNION ALL
    SELECT 'Panavally' AS name UNION ALL
    SELECT 'Pandanad' AS name UNION ALL
    SELECT 'Pathirappally' AS name UNION ALL
    SELECT 'Pathiyoor' AS name UNION ALL
    SELECT 'Pattanakkad' AS name UNION ALL
    SELECT 'Peringala' AS name UNION ALL
    SELECT 'Perumbalam' AS name UNION ALL
    SELECT 'Pulinkunnu' AS name UNION ALL
    SELECT 'Puliyoor' AS name UNION ALL
    SELECT 'Punnamada' AS name UNION ALL
    SELECT 'Punnapra' AS name UNION ALL
    SELECT 'Purakkad' AS name UNION ALL
    SELECT 'Puthuppally' AS name UNION ALL
    SELECT 'Ramankari' AS name UNION ALL
    SELECT 'Thaikkattussery' AS name UNION ALL
    SELECT 'Thakazhi' AS name UNION ALL
    SELECT 'Thalavadi' AS name UNION ALL
    SELECT 'Thamarakkulam' AS name UNION ALL
    SELECT 'Thanneermukkam' AS name UNION ALL
    SELECT 'Thathampally' AS name UNION ALL
    SELECT 'Thazhakkara' AS name UNION ALL
    SELECT 'Thiruvanvandoor' AS name UNION ALL
    SELECT 'Thrikkunnappuzha' AS name UNION ALL
    SELECT 'Thuravoor' AS name UNION ALL
    SELECT 'Vallikunnam' AS name UNION ALL
    SELECT 'Vayalar' AS name UNION ALL
    SELECT 'Vazhicherry' AS name UNION ALL
    SELECT 'Veeyapuram' AS name UNION ALL
    SELECT 'Veliyanad' AS name UNION ALL
    SELECT 'Venmani' AS name UNION ALL
    SELECT 'Vettiyar' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Alappuzha'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Ernakulam (91 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Alangad' AS name UNION ALL
    SELECT 'Aluva' AS name UNION ALL
    SELECT 'Amballur' AS name UNION ALL
    SELECT 'Angamaly' AS name UNION ALL
    SELECT 'Ayyampuzha' AS name UNION ALL
    SELECT 'Chellanam' AS name UNION ALL
    SELECT 'Chendamangalam' AS name UNION ALL
    SELECT 'Chengamanad' AS name UNION ALL
    SELECT 'Cherai' AS name UNION ALL
    SELECT 'Cheranalloor' AS name UNION ALL
    SELECT 'Choornikkara' AS name UNION ALL
    SELECT 'Edakochi' AS name UNION ALL
    SELECT 'Edappally' AS name UNION ALL
    SELECT 'Edavanakkad' AS name UNION ALL
    SELECT 'Elamakkara' AS name UNION ALL
    SELECT 'Elamkulam' AS name UNION ALL
    SELECT 'Elankunnapuzha' AS name UNION ALL
    SELECT 'Eloor' AS name UNION ALL
    SELECT 'Ernakulam' AS name UNION ALL
    SELECT 'Ezhikkara' AS name UNION ALL
    SELECT 'Fort Kochi' AS name UNION ALL
    SELECT 'Infopark' AS name UNION ALL
    SELECT 'Infopark Area' AS name UNION ALL
    SELECT 'Kadamakkudy' AS name UNION ALL
    SELECT 'Kadavanthra' AS name UNION ALL
    SELECT 'Kakkanad' AS name UNION ALL
    SELECT 'Kalady' AS name UNION ALL
    SELECT 'Kalamassery' AS name UNION ALL
    SELECT 'Kaloor' AS name UNION ALL
    SELECT 'Karukutty' AS name UNION ALL
    SELECT 'Karumaloor' AS name UNION ALL
    SELECT 'Keecheri' AS name UNION ALL
    SELECT 'Keezhmad' AS name UNION ALL
    SELECT 'Kizhakkambalam' AS name UNION ALL
    SELECT 'Kochi' AS name UNION ALL
    SELECT 'Kochi City' AS name UNION ALL
    SELECT 'Koothattukulam' AS name UNION ALL
    SELECT 'Kothad' AS name UNION ALL
    SELECT 'Kothamangalam' AS name UNION ALL
    SELECT 'Kottuvally' AS name UNION ALL
    SELECT 'Kumbalam' AS name UNION ALL
    SELECT 'Kundannoor' AS name UNION ALL
    SELECT 'Kunnukara' AS name UNION ALL
    SELECT 'Kuzhuppilly' AS name UNION ALL
    SELECT 'MG Road' AS name UNION ALL
    SELECT 'MG Road Kochi' AS name UNION ALL
    SELECT 'Malayattoor' AS name UNION ALL
    SELECT 'Manakkunnam' AS name UNION ALL
    SELECT 'Manjapra' AS name UNION ALL
    SELECT 'Maradu' AS name UNION ALL
    SELECT 'Marine Drive' AS name UNION ALL
    SELECT 'Marine Drive Kochi' AS name UNION ALL
    SELECT 'Mattancherry' AS name UNION ALL
    SELECT 'Mattur' AS name UNION ALL
    SELECT 'Mookkannur' AS name UNION ALL
    SELECT 'Mulanthuruthy' AS name UNION ALL
    SELECT 'Mulavukad' AS name UNION ALL
    SELECT 'Muvattupuzha' AS name UNION ALL
    SELECT 'Nayarambalam' AS name UNION ALL
    SELECT 'Nedumbassery' AS name UNION ALL
    SELECT 'Nettoor' AS name UNION ALL
    SELECT 'Njarakkal' AS name UNION ALL
    SELECT 'North Paravur' AS name UNION ALL
    SELECT 'Palarivattom' AS name UNION ALL
    SELECT 'Pallippuram' AS name UNION ALL
    SELECT 'Palluruthy' AS name UNION ALL
    SELECT 'Panampilly Nagar' AS name UNION ALL
    SELECT 'Parakkadavu' AS name UNION ALL
    SELECT 'Paravur' AS name UNION ALL
    SELECT 'Pattimattom' AS name UNION ALL
    SELECT 'Perumbavoor' AS name UNION ALL
    SELECT 'Piravom' AS name UNION ALL
    SELECT 'Ponnurunni' AS name UNION ALL
    SELECT 'Puthencruz' AS name UNION ALL
    SELECT 'Puthenvelikkara' AS name UNION ALL
    SELECT 'Puthuvype' AS name UNION ALL
    SELECT 'Ravipuram' AS name UNION ALL
    SELECT 'Rayamangalam' AS name UNION ALL
    SELECT 'SmartCity' AS name UNION ALL
    SELECT 'Thevara' AS name UNION ALL
    SELECT 'Thiruvankulam' AS name UNION ALL
    SELECT 'Thoppumpady' AS name UNION ALL
    SELECT 'Thrikkakara' AS name UNION ALL
    SELECT 'Tripunithura' AS name UNION ALL
    SELECT 'Vadakkekkara' AS name UNION ALL
    SELECT 'Varapuzha' AS name UNION ALL
    SELECT 'Vazhakkala' AS name UNION ALL
    SELECT 'Vazhakkulam' AS name UNION ALL
    SELECT 'Vengola' AS name UNION ALL
    SELECT 'Vypin' AS name UNION ALL
    SELECT 'Vyttila' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Ernakulam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Idukki (73 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adimali' AS name UNION ALL
    SELECT 'Adimaly' AS name UNION ALL
    SELECT 'Alakkode' AS name UNION ALL
    SELECT 'Anakkara' AS name UNION ALL
    SELECT 'Anavilasam' AS name UNION ALL
    SELECT 'Arakkulam' AS name UNION ALL
    SELECT 'Ayyappancoil' AS name UNION ALL
    SELECT 'Baisonvally' AS name UNION ALL
    SELECT 'Chakkupallam' AS name UNION ALL
    SELECT 'Chathurangapara' AS name UNION ALL
    SELECT 'Cheruthoni' AS name UNION ALL
    SELECT 'Chinnakanal' AS name UNION ALL
    SELECT 'Devikulam' AS name UNION ALL
    SELECT 'Elappally' AS name UNION ALL
    SELECT 'Elappara' AS name UNION ALL
    SELECT 'Erattayar' AS name UNION ALL
    SELECT 'Idukki' AS name UNION ALL
    SELECT 'Kalkoonthal' AS name UNION ALL
    SELECT 'Kanchiyar' AS name UNION ALL
    SELECT 'Kanjikuzhi' AS name UNION ALL
    SELECT 'Kannan Devan Hills' AS name UNION ALL
    SELECT 'Kanthalloor' AS name UNION ALL
    SELECT 'Kanthippara' AS name UNION ALL
    SELECT 'Karimannoor' AS name UNION ALL
    SELECT 'Karimkunnam' AS name UNION ALL
    SELECT 'Karunapuram' AS name UNION ALL
    SELECT 'Kattappana' AS name UNION ALL
    SELECT 'Keezhanthoor' AS name UNION ALL
    SELECT 'Kodikulam' AS name UNION ALL
    SELECT 'Kokkayar' AS name UNION ALL
    SELECT 'Konnathady' AS name UNION ALL
    SELECT 'Kottakamboor' AS name UNION ALL
    SELECT 'Kudayathoor' AS name UNION ALL
    SELECT 'Kumaramangalam' AS name UNION ALL
    SELECT 'Kumily' AS name UNION ALL
    SELECT 'Kumily (Thekkady)' AS name UNION ALL
    SELECT 'Kunjithanny' AS name UNION ALL
    SELECT 'Manakkad' AS name UNION ALL
    SELECT 'Manjumala' AS name UNION ALL
    SELECT 'Mankulam' AS name UNION ALL
    SELECT 'Mannamkandam' AS name UNION ALL
    SELECT 'Marayoor' AS name UNION ALL
    SELECT 'Munnar' AS name UNION ALL
    SELECT 'Muttom' AS name UNION ALL
    SELECT 'Nedumkandam' AS name UNION ALL
    SELECT 'Neyyassery' AS name UNION ALL
    SELECT 'Painavu' AS name UNION ALL
    SELECT 'Pallivasal' AS name UNION ALL
    SELECT 'Pampadumpara' AS name UNION ALL
    SELECT 'Parathodu' AS name UNION ALL
    SELECT 'Peermade' AS name UNION ALL
    SELECT 'Peerumade' AS name UNION ALL
    SELECT 'Periyar' AS name UNION ALL
    SELECT 'Peruvanthanam' AS name UNION ALL
    SELECT 'Pooppara' AS name UNION ALL
    SELECT 'Purapuzha' AS name UNION ALL
    SELECT 'Rajakkad' AS name UNION ALL
    SELECT 'Rajakumari' AS name UNION ALL
    SELECT 'Santhanpara' AS name UNION ALL
    SELECT 'Thankamony' AS name UNION ALL
    SELECT 'Thekkady' AS name UNION ALL
    SELECT 'Thodupuzha' AS name UNION ALL
    SELECT 'Udumbanchola' AS name UNION ALL
    SELECT 'Udumbannoor' AS name UNION ALL
    SELECT 'Upputhara' AS name UNION ALL
    SELECT 'Upputhode' AS name UNION ALL
    SELECT 'Vagamon' AS name UNION ALL
    SELECT 'Vandanmedu' AS name UNION ALL
    SELECT 'Vannapuram' AS name UNION ALL
    SELECT 'Vathikudy' AS name UNION ALL
    SELECT 'Vattavada' AS name UNION ALL
    SELECT 'Vellathuval' AS name UNION ALL
    SELECT 'Velliyamattom' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Idukki'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kannur (129 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Alakode' AS name UNION ALL
    SELECT 'Alappadamba' AS name UNION ALL
    SELECT 'Andur' AS name UNION ALL
    SELECT 'Anjarakandy' AS name UNION ALL
    SELECT 'Anthoor' AS name UNION ALL
    SELECT 'Aralam' AS name UNION ALL
    SELECT 'Ayyamkunnu' AS name UNION ALL
    SELECT 'Azhikode' AS name UNION ALL
    SELECT 'Chakkarakkal' AS name UNION ALL
    SELECT 'Chavassery' AS name UNION ALL
    SELECT 'Chelery' AS name UNION ALL
    SELECT 'Chengalayi' AS name UNION ALL
    SELECT 'Cherukunnu' AS name UNION ALL
    SELECT 'Cheruthazham' AS name UNION ALL
    SELECT 'Cheruvanchery' AS name UNION ALL
    SELECT 'Chirakkal' AS name UNION ALL
    SELECT 'Chokli' AS name UNION ALL
    SELECT 'Chuzhali' AS name UNION ALL
    SELECT 'Dharmadam' AS name UNION ALL
    SELECT 'Dharmadom' AS name UNION ALL
    SELECT 'Edakkad' AS name UNION ALL
    SELECT 'Eramam' AS name UNION ALL
    SELECT 'Erancholi' AS name UNION ALL
    SELECT 'Eruvatty' AS name UNION ALL
    SELECT 'Eruveshi' AS name UNION ALL
    SELECT 'Ezhome' AS name UNION ALL
    SELECT 'Irikkur' AS name UNION ALL
    SELECT 'Iritty' AS name UNION ALL
    SELECT 'Kadambur' AS name UNION ALL
    SELECT 'Kadannappally' AS name UNION ALL
    SELECT 'Kadirur' AS name UNION ALL
    SELECT 'Kalliad' AS name UNION ALL
    SELECT 'Kalliasseri' AS name UNION ALL
    SELECT 'Kandankunnu' AS name UNION ALL
    SELECT 'Kanichar' AS name UNION ALL
    SELECT 'Kankol' AS name UNION ALL
    SELECT 'Kannadiparamba' AS name UNION ALL
    SELECT 'Kannapuram' AS name UNION ALL
    SELECT 'Kannavam' AS name UNION ALL
    SELECT 'Kannur' AS name UNION ALL
    SELECT 'Kannur City' AS name UNION ALL
    SELECT 'Karivellur' AS name UNION ALL
    SELECT 'Kayaralam' AS name UNION ALL
    SELECT 'Keezhallur' AS name UNION ALL
    SELECT 'Keezhur' AS name UNION ALL
    SELECT 'Kelakam' AS name UNION ALL
    SELECT 'Kodiyeri' AS name UNION ALL
    SELECT 'Kolacheri' AS name UNION ALL
    SELECT 'Kolari' AS name UNION ALL
    SELECT 'Kolavallur' AS name UNION ALL
    SELECT 'Kolayad' AS name UNION ALL
    SELECT 'Koodali' AS name UNION ALL
    SELECT 'Koothuparamba' AS name UNION ALL
    SELECT 'Kooveri' AS name UNION ALL
    SELECT 'Korom' AS name UNION ALL
    SELECT 'Kottayam' AS name UNION ALL
    SELECT 'Kottiyoor' AS name UNION ALL
    SELECT 'Kunhimangalam' AS name UNION ALL
    SELECT 'Kurumathur' AS name UNION ALL
    SELECT 'Kuthuparamba' AS name UNION ALL
    SELECT 'Kuttiyattoor' AS name UNION ALL
    SELECT 'Kuttiyeri' AS name UNION ALL
    SELECT 'Kuttur' AS name UNION ALL
    SELECT 'Madayi' AS name UNION ALL
    SELECT 'Makrery' AS name UNION ALL
    SELECT 'Malappattam' AS name UNION ALL
    SELECT 'Mananthery' AS name UNION ALL
    SELECT 'Manathana' AS name UNION ALL
    SELECT 'Mangattidam' AS name UNION ALL
    SELECT 'Maniyoor' AS name UNION ALL
    SELECT 'Mattannur' AS name UNION ALL
    SELECT 'Mattool' AS name UNION ALL
    SELECT 'Mavilayi' AS name UNION ALL
    SELECT 'Mayyil' AS name UNION ALL
    SELECT 'Mokery' AS name UNION ALL
    SELECT 'Morazha' AS name UNION ALL
    SELECT 'Muzhakkunn' AS name UNION ALL
    SELECT 'Muzhappilangad' AS name UNION ALL
    SELECT 'Naduvil' AS name UNION ALL
    SELECT 'Narath' AS name UNION ALL
    SELECT 'New Mahe' AS name UNION ALL
    SELECT 'Nidiyanga' AS name UNION ALL
    SELECT 'Nuchiyad' AS name UNION ALL
    SELECT 'Padiyoor' AS name UNION ALL
    SELECT 'Paduvilayi' AS name UNION ALL
    SELECT 'Pallikkunnu' AS name UNION ALL
    SELECT 'Panapuzha' AS name UNION ALL
    SELECT 'Panniyannur' AS name UNION ALL
    SELECT 'Panniyoor' AS name UNION ALL
    SELECT 'Panoor' AS name UNION ALL
    SELECT 'Pappinisseri' AS name UNION ALL
    SELECT 'Pariyaram' AS name UNION ALL
    SELECT 'Pattanur' AS name UNION ALL
    SELECT 'Pattiam' AS name UNION ALL
    SELECT 'Pattuvam' AS name UNION ALL
    SELECT 'Payam' AS name UNION ALL
    SELECT 'Payyambalam' AS name UNION ALL
    SELECT 'Payyannur' AS name UNION ALL
    SELECT 'Payyanur' AS name UNION ALL
    SELECT 'Payyavoor' AS name UNION ALL
    SELECT 'Pazhassi' AS name UNION ALL
    SELECT 'Peralam' AS name UNION ALL
    SELECT 'Peringalam' AS name UNION ALL
    SELECT 'Peringathur' AS name UNION ALL
    SELECT 'Peringome' AS name UNION ALL
    SELECT 'Perinthatta' AS name UNION ALL
    SELECT 'Pinarayi' AS name UNION ALL
    SELECT 'Pulingome' AS name UNION ALL
    SELECT 'Puthur' AS name UNION ALL
    SELECT 'Ramanthali' AS name UNION ALL
    SELECT 'Shivapuram' AS name UNION ALL
    SELECT 'Sreekandapuram' AS name UNION ALL
    SELECT 'Taliparamba' AS name UNION ALL
    SELECT 'Thalassery' AS name UNION ALL
    SELECT 'Thillankery' AS name UNION ALL
    SELECT 'Thimiri' AS name UNION ALL
    SELECT 'Thirumeni' AS name UNION ALL
    SELECT 'Thiruvangad' AS name UNION ALL
    SELECT 'Tholambra' AS name UNION ALL
    SELECT 'Thripangothur' AS name UNION ALL
    SELECT 'Udayagiri' AS name UNION ALL
    SELECT 'Valapattanam' AS name UNION ALL
    SELECT 'Vayakkara' AS name UNION ALL
    SELECT 'Vayathur' AS name UNION ALL
    SELECT 'Velladu' AS name UNION ALL
    SELECT 'Vellarvalli' AS name UNION ALL
    SELECT 'Vellora' AS name UNION ALL
    SELECT 'Vellur' AS name UNION ALL
    SELECT 'Villamana' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Kannur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kasaragod (127 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adhur' AS name UNION ALL
    SELECT 'Adoor' AS name UNION ALL
    SELECT 'Ajanur' AS name UNION ALL
    SELECT 'Ambalathara' AS name UNION ALL
    SELECT 'Angadimogaru' AS name UNION ALL
    SELECT 'Arikady' AS name UNION ALL
    SELECT 'Badaje' AS name UNION ALL
    SELECT 'Badiadka' AS name UNION ALL
    SELECT 'Badiyadka' AS name UNION ALL
    SELECT 'Badoor' AS name UNION ALL
    SELECT 'Balal' AS name UNION ALL
    SELECT 'Bandadka' AS name UNION ALL
    SELECT 'Bangra Manjeshwar' AS name UNION ALL
    SELECT 'Bare' AS name UNION ALL
    SELECT 'Bayar' AS name UNION ALL
    SELECT 'Bedadka' AS name UNION ALL
    SELECT 'Bekal' AS name UNION ALL
    SELECT 'Bekal Area' AS name UNION ALL
    SELECT 'Bekoor' AS name UNION ALL
    SELECT 'Bela' AS name UNION ALL
    SELECT 'Bellur' AS name UNION ALL
    SELECT 'Belur' AS name UNION ALL
    SELECT 'Bheemanady' AS name UNION ALL
    SELECT 'Bombrana' AS name UNION ALL
    SELECT 'Cheemeni' AS name UNION ALL
    SELECT 'Chemnad' AS name UNION ALL
    SELECT 'Chengala' AS name UNION ALL
    SELECT 'Cheruvathur' AS name UNION ALL
    SELECT 'Chippar' AS name UNION ALL
    SELECT 'Chithari' AS name UNION ALL
    SELECT 'Chittarikkal' AS name UNION ALL
    SELECT 'Delampady' AS name UNION ALL
    SELECT 'Edanad' AS name UNION ALL
    SELECT 'Enmakaje' AS name UNION ALL
    SELECT 'Heroor' AS name UNION ALL
    SELECT 'Hosabettu' AS name UNION ALL
    SELECT 'Hosdurg' AS name UNION ALL
    SELECT 'Ichilampady' AS name UNION ALL
    SELECT 'Ichilangod' AS name UNION ALL
    SELECT 'Kadambar' AS name UNION ALL
    SELECT 'Kalanad' AS name UNION ALL
    SELECT 'Kaliyoor' AS name UNION ALL
    SELECT 'Kallar' AS name UNION ALL
    SELECT 'Kalnad' AS name UNION ALL
    SELECT 'Kanhangad' AS name UNION ALL
    SELECT 'Karadka' AS name UNION ALL
    SELECT 'Karindalam' AS name UNION ALL
    SELECT 'Karivedakam' AS name UNION ALL
    SELECT 'Kasaragod' AS name UNION ALL
    SELECT 'Kasaragod Town' AS name UNION ALL
    SELECT 'Kattukukke' AS name UNION ALL
    SELECT 'Kayyar' AS name UNION ALL
    SELECT 'Kayyur' AS name UNION ALL
    SELECT 'Keekan' AS name UNION ALL
    SELECT 'Kidoor' AS name UNION ALL
    SELECT 'Kilayikode' AS name UNION ALL
    SELECT 'Kinanoor' AS name UNION ALL
    SELECT 'Kodakkad' AS name UNION ALL
    SELECT 'Kodalamogaru' AS name UNION ALL
    SELECT 'Kodibail' AS name UNION ALL
    SELECT 'Kodom' AS name UNION ALL
    SELECT 'Koipady' AS name UNION ALL
    SELECT 'Kolathur' AS name UNION ALL
    SELECT 'Koliyoor' AS name UNION ALL
    SELECT 'Kubanoor' AS name UNION ALL
    SELECT 'Kudalmarkala' AS name UNION ALL
    SELECT 'Kudlu' AS name UNION ALL
    SELECT 'Kuloor' AS name UNION ALL
    SELECT 'Kumbadaje' AS name UNION ALL
    SELECT 'Kumbla' AS name UNION ALL
    SELECT 'Kunjathur' AS name UNION ALL
    SELECT 'Kuttikole' AS name UNION ALL
    SELECT 'Madhur' AS name UNION ALL
    SELECT 'Madikai' AS name UNION ALL
    SELECT 'Majibail' AS name UNION ALL
    SELECT 'Maloth' AS name UNION ALL
    SELECT 'Mangalpady' AS name UNION ALL
    SELECT 'Maniyat' AS name UNION ALL
    SELECT 'Manjeshwar' AS name UNION ALL
    SELECT 'Meenja' AS name UNION ALL
    SELECT 'Mogral' AS name UNION ALL
    SELECT 'Moodambail' AS name UNION ALL
    SELECT 'Mugu' AS name UNION ALL
    SELECT 'Mulinja' AS name UNION ALL
    SELECT 'Muliyar' AS name UNION ALL
    SELECT 'Munnad' AS name UNION ALL
    SELECT 'Muttathody' AS name UNION ALL
    SELECT 'Nekraje' AS name UNION ALL
    SELECT 'Nettanige' AS name UNION ALL
    SELECT 'Nileshwar' AS name UNION ALL
    SELECT 'Nirchal' AS name UNION ALL
    SELECT 'Padne' AS name UNION ALL
    SELECT 'Padre' AS name UNION ALL
    SELECT 'Pady' AS name UNION ALL
    SELECT 'Paivalike' AS name UNION ALL
    SELECT 'Palavayal' AS name UNION ALL
    SELECT 'Pallikkara' AS name UNION ALL
    SELECT 'Panathady' AS name UNION ALL
    SELECT 'Panayal' AS name UNION ALL
    SELECT 'Parappa' AS name UNION ALL
    SELECT 'Pathur' AS name UNION ALL
    SELECT 'Patla' AS name UNION ALL
    SELECT 'Pavoor' AS name UNION ALL
    SELECT 'Periya' AS name UNION ALL
    SELECT 'Perumbala' AS name UNION ALL
    SELECT 'Pilicode' AS name UNION ALL
    SELECT 'Pullur' AS name UNION ALL
    SELECT 'Puthige' AS name UNION ALL
    SELECT 'Puthur' AS name UNION ALL
    SELECT 'Sheni' AS name UNION ALL
    SELECT 'Shiribagilu' AS name UNION ALL
    SELECT 'Shiriya' AS name UNION ALL
    SELECT 'South Thrikkaripur' AS name UNION ALL
    SELECT 'Talikala' AS name UNION ALL
    SELECT 'Thayanur' AS name UNION ALL
    SELECT 'Thekkil' AS name UNION ALL
    SELECT 'Thimiri' AS name UNION ALL
    SELECT 'Thrikkaripur' AS name UNION ALL
    SELECT 'Ubrangala' AS name UNION ALL
    SELECT 'Udinoor' AS name UNION ALL
    SELECT 'Udma' AS name UNION ALL
    SELECT 'Udyawar' AS name UNION ALL
    SELECT 'Uppala' AS name UNION ALL
    SELECT 'Valiyaparamba' AS name UNION ALL
    SELECT 'Vidyanagar' AS name UNION ALL
    SELECT 'Vorkady' AS name UNION ALL
    SELECT 'West Eleri' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Kasaragod'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kollam (74 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adichanalloor' AS name UNION ALL
    SELECT 'Alappad' AS name UNION ALL
    SELECT 'Alayamon' AS name UNION ALL
    SELECT 'Anchal' AS name UNION ALL
    SELECT 'Aryancavu' AS name UNION ALL
    SELECT 'Asramam' AS name UNION ALL
    SELECT 'Ayoor' AS name UNION ALL
    SELECT 'Chadayamangalam' AS name UNION ALL
    SELECT 'Chathannoor' AS name UNION ALL
    SELECT 'Chavara' AS name UNION ALL
    SELECT 'Chinnakada' AS name UNION ALL
    SELECT 'Chirakkara' AS name UNION ALL
    SELECT 'Chithara' AS name UNION ALL
    SELECT 'Clappana' AS name UNION ALL
    SELECT 'East Kallada' AS name UNION ALL
    SELECT 'Edamulakkal' AS name UNION ALL
    SELECT 'Elamadu' AS name UNION ALL
    SELECT 'Elampalloor' AS name UNION ALL
    SELECT 'Eravipuram' AS name UNION ALL
    SELECT 'Ezhukone' AS name UNION ALL
    SELECT 'Ittiva' AS name UNION ALL
    SELECT 'Kadakkal' AS name UNION ALL
    SELECT 'Kadappakada' AS name UNION ALL
    SELECT 'Kalluvathukkal' AS name UNION ALL
    SELECT 'Karavaloor' AS name UNION ALL
    SELECT 'Kareepra' AS name UNION ALL
    SELECT 'Karunagappalli' AS name UNION ALL
    SELECT 'Karunagappally' AS name UNION ALL
    SELECT 'Karunagappally North' AS name UNION ALL
    SELECT 'Karunagappally South' AS name UNION ALL
    SELECT 'Kavanad' AS name UNION ALL
    SELECT 'Kollam' AS name UNION ALL
    SELECT 'Kollam City' AS name UNION ALL
    SELECT 'Kottamkara' AS name UNION ALL
    SELECT 'Kottarakkara' AS name UNION ALL
    SELECT 'Kottiyam' AS name UNION ALL
    SELECT 'Kulakkada' AS name UNION ALL
    SELECT 'Kulasekharapuram' AS name UNION ALL
    SELECT 'Kulathupuzha' AS name UNION ALL
    SELECT 'Kummil' AS name UNION ALL
    SELECT 'Kundara' AS name UNION ALL
    SELECT 'Kureepuzha' AS name UNION ALL
    SELECT 'Mayyanad' AS name UNION ALL
    SELECT 'Meenad' AS name UNION ALL
    SELECT 'Mylom' AS name UNION ALL
    SELECT 'Neduvathur' AS name UNION ALL
    SELECT 'Neendakara' AS name UNION ALL
    SELECT 'Nilamel' AS name UNION ALL
    SELECT 'Oachira' AS name UNION ALL
    SELECT 'Odanavattom' AS name UNION ALL
    SELECT 'Paravur' AS name UNION ALL
    SELECT 'Parippally' AS name UNION ALL
    SELECT 'Pathanapuram' AS name UNION ALL
    SELECT 'Pavithreswaram' AS name UNION ALL
    SELECT 'Perayam' AS name UNION ALL
    SELECT 'Polayathode' AS name UNION ALL
    SELECT 'Poothakkulam' AS name UNION ALL
    SELECT 'Pooyappally' AS name UNION ALL
    SELECT 'Poruvazhy' AS name UNION ALL
    SELECT 'Punalur' AS name UNION ALL
    SELECT 'Sakthikulangara' AS name UNION ALL
    SELECT 'Sasthamkotta' AS name UNION ALL
    SELECT 'Sooranad' AS name UNION ALL
    SELECT 'Tangasseri' AS name UNION ALL
    SELECT 'Thalavoor' AS name UNION ALL
    SELECT 'Thazhava' AS name UNION ALL
    SELECT 'Thazhuthala' AS name UNION ALL
    SELECT 'Thenmala' AS name UNION ALL
    SELECT 'Thevally' AS name UNION ALL
    SELECT 'Thirumullavaram' AS name UNION ALL
    SELECT 'Thodiyoor' AS name UNION ALL
    SELECT 'Ummannoor' AS name UNION ALL
    SELECT 'Veliyam' AS name UNION ALL
    SELECT 'Vettikkavala' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Kollam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kottayam (87 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Aimanam' AS name UNION ALL
    SELECT 'Akalakunnam' AS name UNION ALL
    SELECT 'Anikkad' AS name UNION ALL
    SELECT 'Arpookara' AS name UNION ALL
    SELECT 'Athirampuzha' AS name UNION ALL
    SELECT 'Ayarkunnam' AS name UNION ALL
    SELECT 'Bharananganam' AS name UNION ALL
    SELECT 'Changanassery' AS name UNION ALL
    SELECT 'Chempu' AS name UNION ALL
    SELECT 'Chengalam' AS name UNION ALL
    SELECT 'Cheruvally' AS name UNION ALL
    SELECT 'Chirakkadavu' AS name UNION ALL
    SELECT 'Edakkunnam' AS name UNION ALL
    SELECT 'Elackad' AS name UNION ALL
    SELECT 'Elamgulam' AS name UNION ALL
    SELECT 'Elikkulam' AS name UNION ALL
    SELECT 'Erattupetta' AS name UNION ALL
    SELECT 'Erumeli' AS name UNION ALL
    SELECT 'Ettumanoor' AS name UNION ALL
    SELECT 'Kadanad' AS name UNION ALL
    SELECT 'Kadaplamattom' AS name UNION ALL
    SELECT 'Kaduthuruthy' AS name UNION ALL
    SELECT 'Kaipuzha' AS name UNION ALL
    SELECT 'Kallara' AS name UNION ALL
    SELECT 'Kanakkari' AS name UNION ALL
    SELECT 'Kangazha' AS name UNION ALL
    SELECT 'Kanjirappally' AS name UNION ALL
    SELECT 'Karukachal' AS name UNION ALL
    SELECT 'Kidangoor' AS name UNION ALL
    SELECT 'Kondoor' AS name UNION ALL
    SELECT 'Kooroppada' AS name UNION ALL
    SELECT 'Koottickal' AS name UNION ALL
    SELECT 'Koovappally' AS name UNION ALL
    SELECT 'Koruthode' AS name UNION ALL
    SELECT 'Kothanalloor' AS name UNION ALL
    SELECT 'Kottayam' AS name UNION ALL
    SELECT 'Kottayam Town' AS name UNION ALL
    SELECT 'Kulasekharamangalam' AS name UNION ALL
    SELECT 'Kumarakom' AS name UNION ALL
    SELECT 'Kuravilangad' AS name UNION ALL
    SELECT 'Lalam' AS name UNION ALL
    SELECT 'Madappally' AS name UNION ALL
    SELECT 'Manarcad' AS name UNION ALL
    SELECT 'Manimala' AS name UNION ALL
    SELECT 'Manjoor' AS name UNION ALL
    SELECT 'Meenachil' AS name UNION ALL
    SELECT 'Meenadam' AS name UNION ALL
    SELECT 'Melukavu' AS name UNION ALL
    SELECT 'Monippally' AS name UNION ALL
    SELECT 'Moonilavu' AS name UNION ALL
    SELECT 'Mulakulam' AS name UNION ALL
    SELECT 'Mundakayam' AS name UNION ALL
    SELECT 'Muttuchira' AS name UNION ALL
    SELECT 'Nedumkunnam' AS name UNION ALL
    SELECT 'Njeezhoor' AS name UNION ALL
    SELECT 'Onamthuruth' AS name UNION ALL
    SELECT 'Paippad' AS name UNION ALL
    SELECT 'Pala' AS name UNION ALL
    SELECT 'Pambady' AS name UNION ALL
    SELECT 'Pampady' AS name UNION ALL
    SELECT 'Panachikkad' AS name UNION ALL
    SELECT 'Peroor' AS name UNION ALL
    SELECT 'Perumbaikad' AS name UNION ALL
    SELECT 'Poonjar' AS name UNION ALL
    SELECT 'Poonjar Thekkekara' AS name UNION ALL
    SELECT 'Poovarany' AS name UNION ALL
    SELECT 'Puthuppally' AS name UNION ALL
    SELECT 'Ramapuram' AS name UNION ALL
    SELECT 'Teekoy' AS name UNION ALL
    SELECT 'Thalanadu' AS name UNION ALL
    SELECT 'Thalappalam' AS name UNION ALL
    SELECT 'Thalayazham' AS name UNION ALL
    SELECT 'Thiruvarpu' AS name UNION ALL
    SELECT 'Thottackad' AS name UNION ALL
    SELECT 'Udayanapuram' AS name UNION ALL
    SELECT 'Uzhavoor' AS name UNION ALL
    SELECT 'Vadayar' AS name UNION ALL
    SELECT 'Vaikom' AS name UNION ALL
    SELECT 'Vakathanam' AS name UNION ALL
    SELECT 'Vallichira' AS name UNION ALL
    SELECT 'Vazhappally' AS name UNION ALL
    SELECT 'Vazhoor' AS name UNION ALL
    SELECT 'Vechoor' AS name UNION ALL
    SELECT 'Veliyannoor' AS name UNION ALL
    SELECT 'Vellavoor' AS name UNION ALL
    SELECT 'Velloor' AS name UNION ALL
    SELECT 'Vijayapuram' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Kottayam'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Kozhikode (136 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Arayidathupalam' AS name UNION ALL
    SELECT 'Arikkulam' AS name UNION ALL
    SELECT 'Atholi' AS name UNION ALL
    SELECT 'Avidanallur' AS name UNION ALL
    SELECT 'Ayancheri' AS name UNION ALL
    SELECT 'Azhiyur' AS name UNION ALL
    SELECT 'Balussery' AS name UNION ALL
    SELECT 'Beach Road Kozhikode' AS name UNION ALL
    SELECT 'Beypore' AS name UNION ALL
    SELECT 'Calicut' AS name UNION ALL
    SELECT 'Chakkittappara' AS name UNION ALL
    SELECT 'Changaroth' AS name UNION ALL
    SELECT 'Chathamangalam' AS name UNION ALL
    SELECT 'Chekkiad' AS name UNION ALL
    SELECT 'Chelannur' AS name UNION ALL
    SELECT 'Chelavur' AS name UNION ALL
    SELECT 'Chemancheri' AS name UNION ALL
    SELECT 'Chempanoda' AS name UNION ALL
    SELECT 'Chengottukavu' AS name UNION ALL
    SELECT 'Cheruvannur' AS name UNION ALL
    SELECT 'Chevayur' AS name UNION ALL
    SELECT 'Chorode' AS name UNION ALL
    SELECT 'Edachery' AS name UNION ALL
    SELECT 'Elathur' AS name UNION ALL
    SELECT 'Engapuzha' AS name UNION ALL
    SELECT 'Eramala' AS name UNION ALL
    SELECT 'Eranhipalam' AS name UNION ALL
    SELECT 'Eravattur' AS name UNION ALL
    SELECT 'Feroke' AS name UNION ALL
    SELECT 'Iringal' AS name UNION ALL
    SELECT 'Kadalundi' AS name UNION ALL
    SELECT 'Kakkad' AS name UNION ALL
    SELECT 'Kakkodi' AS name UNION ALL
    SELECT 'Kakkur' AS name UNION ALL
    SELECT 'Kallai' AS name UNION ALL
    SELECT 'Kallayi' AS name UNION ALL
    SELECT 'Kanthalad' AS name UNION ALL
    SELECT 'Karuvanthiruthi' AS name UNION ALL
    SELECT 'Kasaba' AS name UNION ALL
    SELECT 'Kattippara' AS name UNION ALL
    SELECT 'Kavilumpara' AS name UNION ALL
    SELECT 'Kayakkodi' AS name UNION ALL
    SELECT 'Kayanna' AS name UNION ALL
    SELECT 'Kedavur' AS name UNION ALL
    SELECT 'Keezhariyur' AS name UNION ALL
    SELECT 'Kinalur' AS name UNION ALL
    SELECT 'Kizhakkoth' AS name UNION ALL
    SELECT 'Kodanchery' AS name UNION ALL
    SELECT 'Kodiyathur' AS name UNION ALL
    SELECT 'Koduvally' AS name UNION ALL
    SELECT 'Koodaranji' AS name UNION ALL
    SELECT 'Koodathai' AS name UNION ALL
    SELECT 'Koorachundu' AS name UNION ALL
    SELECT 'Koothali' AS name UNION ALL
    SELECT 'Kottappally' AS name UNION ALL
    SELECT 'Kottooli' AS name UNION ALL
    SELECT 'Kottur' AS name UNION ALL
    SELECT 'Koyilandy' AS name UNION ALL
    SELECT 'Kozhikode' AS name UNION ALL
    SELECT 'Kozhikode City' AS name UNION ALL
    SELECT 'Kozhukkallur' AS name UNION ALL
    SELECT 'Kumaranallur' AS name UNION ALL
    SELECT 'Kunnamangalam' AS name UNION ALL
    SELECT 'Kunnummal' AS name UNION ALL
    SELECT 'Kuruvattoor' AS name UNION ALL
    SELECT 'Kuttikkattoor' AS name UNION ALL
    SELECT 'Kuttiyadi' AS name UNION ALL
    SELECT 'Madavoor' AS name UNION ALL
    SELECT 'Mananchira' AS name UNION ALL
    SELECT 'Maniyur' AS name UNION ALL
    SELECT 'Mankavu' AS name UNION ALL
    SELECT 'Maruthonkara' AS name UNION ALL
    SELECT 'Mavoor' AS name UNION ALL
    SELECT 'Mavoor Road' AS name UNION ALL
    SELECT 'Medical College' AS name UNION ALL
    SELECT 'Medical College Kozhikode' AS name UNION ALL
    SELECT 'Menhanyam' AS name UNION ALL
    SELECT 'Meppayyur' AS name UNION ALL
    SELECT 'Moodadi' AS name UNION ALL
    SELECT 'Mukkam' AS name UNION ALL
    SELECT 'Nadakkavu' AS name UNION ALL
    SELECT 'Nadapuram' AS name UNION ALL
    SELECT 'Naduvannur' AS name UNION ALL
    SELECT 'Nagaram' AS name UNION ALL
    SELECT 'Nanminda' AS name UNION ALL
    SELECT 'Narikkuni' AS name UNION ALL
    SELECT 'Narippatta' AS name UNION ALL
    SELECT 'Neeleswaram' AS name UNION ALL
    SELECT 'Nellikode' AS name UNION ALL
    SELECT 'Nellippoyil' AS name UNION ALL
    SELECT 'Nochad' AS name UNION ALL
    SELECT 'Olavanna' AS name UNION ALL
    SELECT 'Onchiyam' AS name UNION ALL
    SELECT 'Palayad' AS name UNION ALL
    SELECT 'Palayam Kozhikode' AS name UNION ALL
    SELECT 'Paleri' AS name UNION ALL
    SELECT 'Panangad' AS name UNION ALL
    SELECT 'Panthalayani' AS name UNION ALL
    SELECT 'Pantheerankavu' AS name UNION ALL
    SELECT 'Payyoli' AS name UNION ALL
    SELECT 'Perambra' AS name UNION ALL
    SELECT 'Perumanna' AS name UNION ALL
    SELECT 'Peruvayal' AS name UNION ALL
    SELECT 'Poolakkode' AS name UNION ALL
    SELECT 'Pottammal' AS name UNION ALL
    SELECT 'Purameri' AS name UNION ALL
    SELECT 'Puthiyangadi' AS name UNION ALL
    SELECT 'Puthiyara' AS name UNION ALL
    SELECT 'Puthuppady' AS name UNION ALL
    SELECT 'Puthur' AS name UNION ALL
    SELECT 'Ramanattukara' AS name UNION ALL
    SELECT 'Raroth' AS name UNION ALL
    SELECT 'Sivapuram' AS name UNION ALL
    SELECT 'Thalakulathur' AS name UNION ALL
    SELECT 'Thamarassery' AS name UNION ALL
    SELECT 'Thikkodi' AS name UNION ALL
    SELECT 'Thinur' AS name UNION ALL
    SELECT 'Thiruvallur' AS name UNION ALL
    SELECT 'Thiruvambady' AS name UNION ALL
    SELECT 'Thondayad' AS name UNION ALL
    SELECT 'Thuneri' AS name UNION ALL
    SELECT 'Thurayur' AS name UNION ALL
    SELECT 'Ulliyeri' AS name UNION ALL
    SELECT 'Unnikulam' AS name UNION ALL
    SELECT 'Vadakara' AS name UNION ALL
    SELECT 'Valayam' AS name UNION ALL
    SELECT 'Vanimel' AS name UNION ALL
    SELECT 'Vavad' AS name UNION ALL
    SELECT 'Velam' AS name UNION ALL
    SELECT 'Vellayil' AS name UNION ALL
    SELECT 'Vellimadukunnu' AS name UNION ALL
    SELECT 'Vengeri' AS name UNION ALL
    SELECT 'Vilangad' AS name UNION ALL
    SELECT 'Villyappally' AS name UNION ALL
    SELECT 'Viyyur' AS name UNION ALL
    SELECT 'West Hill' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Kozhikode'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Malappuram (138 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Alamcode' AS name UNION ALL
    SELECT 'Aliparamba' AS name UNION ALL
    SELECT 'Amarambalam' AS name UNION ALL
    SELECT 'Anakkayam' AS name UNION ALL
    SELECT 'Ananthavoor' AS name UNION ALL
    SELECT 'Angadipuram' AS name UNION ALL
    SELECT 'Arakkuparamba' AS name UNION ALL
    SELECT 'Areekode' AS name UNION ALL
    SELECT 'Ariyallur' AS name UNION ALL
    SELECT 'Athavanad' AS name UNION ALL
    SELECT 'Chembrassery' AS name UNION ALL
    SELECT 'Cheriyamundam' AS name UNION ALL
    SELECT 'Chokkad' AS name UNION ALL
    SELECT 'Chungathara' AS name UNION ALL
    SELECT 'Down Hill' AS name UNION ALL
    SELECT 'Edakkara' AS name UNION ALL
    SELECT 'Edappal' AS name UNION ALL
    SELECT 'Edappatta' AS name UNION ALL
    SELECT 'Edarikode' AS name UNION ALL
    SELECT 'Edavanna' AS name UNION ALL
    SELECT 'Edayur' AS name UNION ALL
    SELECT 'Elamkulam' AS name UNION ALL
    SELECT 'Elankur' AS name UNION ALL
    SELECT 'Ezhuvathiruthy' AS name UNION ALL
    SELECT 'Irimbiliyam' AS name UNION ALL
    SELECT 'Kalady' AS name UNION ALL
    SELECT 'Kalikavu' AS name UNION ALL
    SELECT 'Kalpakanchery' AS name UNION ALL
    SELECT 'Kannamangalam' AS name UNION ALL
    SELECT 'Karakunnu' AS name UNION ALL
    SELECT 'Kariavattom' AS name UNION ALL
    SELECT 'Karulai' AS name UNION ALL
    SELECT 'Karuvarakundu' AS name UNION ALL
    SELECT 'Kattipparuthi' AS name UNION ALL
    SELECT 'Kavannoor' AS name UNION ALL
    SELECT 'Keezhattur' AS name UNION ALL
    SELECT 'Kizhuparamba' AS name UNION ALL
    SELECT 'Kodur' AS name UNION ALL
    SELECT 'Kondotty' AS name UNION ALL
    SELECT 'Koottilangadi' AS name UNION ALL
    SELECT 'Kottakkal' AS name UNION ALL
    SELECT 'Kottapadi' AS name UNION ALL
    SELECT 'Kozhichena' AS name UNION ALL
    SELECT 'Kozhikode Road area' AS name UNION ALL
    SELECT 'Kurumbathur' AS name UNION ALL
    SELECT 'Kuruva' AS name UNION ALL
    SELECT 'Kuruvambalam' AS name UNION ALL
    SELECT 'Kuttippuram' AS name UNION ALL
    SELECT 'Malappuram' AS name UNION ALL
    SELECT 'Malappuram Town' AS name UNION ALL
    SELECT 'Mampad' AS name UNION ALL
    SELECT 'Mangalam' AS name UNION ALL
    SELECT 'Manjeri' AS name UNION ALL
    SELECT 'Mankada' AS name UNION ALL
    SELECT 'Marakkara' AS name UNION ALL
    SELECT 'Maranchery' AS name UNION ALL
    SELECT 'Melattur' AS name UNION ALL
    SELECT 'Melmuri' AS name UNION ALL
    SELECT 'Moonniyur' AS name UNION ALL
    SELECT 'Moorkkanad' AS name UNION ALL
    SELECT 'Moothedam' AS name UNION ALL
    SELECT 'Munduparamba' AS name UNION ALL
    SELECT 'Naduvattom' AS name UNION ALL
    SELECT 'Nannambra' AS name UNION ALL
    SELECT 'Nannamukku' AS name UNION ALL
    SELECT 'Narukara' AS name UNION ALL
    SELECT 'Nediyiruppu' AS name UNION ALL
    SELECT 'Neduva' AS name UNION ALL
    SELECT 'Nenmini' AS name UNION ALL
    SELECT 'Nilambur' AS name UNION ALL
    SELECT 'Niramaruthur' AS name UNION ALL
    SELECT 'Omachapuzha' AS name UNION ALL
    SELECT 'Oorakam' AS name UNION ALL
    SELECT 'Othukkungal' AS name UNION ALL
    SELECT 'Ozhur' AS name UNION ALL
    SELECT 'Pallikkal' AS name UNION ALL
    SELECT 'Panakkad' AS name UNION ALL
    SELECT 'Pandallur' AS name UNION ALL
    SELECT 'Pandikkad' AS name UNION ALL
    SELECT 'Panthavoor' AS name UNION ALL
    SELECT 'Parappanangadi' AS name UNION ALL
    SELECT 'Parappur' AS name UNION ALL
    SELECT 'Pariyapuram' AS name UNION ALL
    SELECT 'Pathaikara' AS name UNION ALL
    SELECT 'Payyanad' AS name UNION ALL
    SELECT 'Perakamanna' AS name UNION ALL
    SELECT 'Perinthalmanna' AS name UNION ALL
    SELECT 'Perumanna' AS name UNION ALL
    SELECT 'Perumpadappa' AS name UNION ALL
    SELECT 'Peruvallur' AS name UNION ALL
    SELECT 'Ponmala' AS name UNION ALL
    SELECT 'Ponmundam' AS name UNION ALL
    SELECT 'Ponnani' AS name UNION ALL
    SELECT 'Ponnani Nagaram' AS name UNION ALL
    SELECT 'Pookkottur' AS name UNION ALL
    SELECT 'Porur' AS name UNION ALL
    SELECT 'Pothukal' AS name UNION ALL
    SELECT 'Pulamantol' AS name UNION ALL
    SELECT 'Pulikkal' AS name UNION ALL
    SELECT 'Pullipadam' AS name UNION ALL
    SELECT 'Pulpatta' AS name UNION ALL
    SELECT 'Purathur' AS name UNION ALL
    SELECT 'Puthupparamb' AS name UNION ALL
    SELECT 'Puzhakkattiri' AS name UNION ALL
    SELECT 'Tanalur' AS name UNION ALL
    SELECT 'Tanur' AS name UNION ALL
    SELECT 'Thalakkad' AS name UNION ALL
    SELECT 'Thavanur' AS name UNION ALL
    SELECT 'Thazhekode' AS name UNION ALL
    SELECT 'Thenhippalam' AS name UNION ALL
    SELECT 'Thennala' AS name UNION ALL
    SELECT 'Thiruvali' AS name UNION ALL
    SELECT 'Thrikkandiyur' AS name UNION ALL
    SELECT 'Tirunavaya' AS name UNION ALL
    SELECT 'Tirur' AS name UNION ALL
    SELECT 'Tirurangadi' AS name UNION ALL
    SELECT 'Trikkalangode' AS name UNION ALL
    SELECT 'Triprangode' AS name UNION ALL
    SELECT 'Tuvvur' AS name UNION ALL
    SELECT 'Up Hill' AS name UNION ALL
    SELECT 'Urangattiri' AS name UNION ALL
    SELECT 'Vadakkangara' AS name UNION ALL
    SELECT 'Valambur' AS name UNION ALL
    SELECT 'Valanchery' AS name UNION ALL
    SELECT 'Valapuram' AS name UNION ALL
    SELECT 'Valavannur' AS name UNION ALL
    SELECT 'Vallikkunnu' AS name UNION ALL
    SELECT 'Vattamkulam' AS name UNION ALL
    SELECT 'Vazhakkad' AS name UNION ALL
    SELECT 'Vazhayur' AS name UNION ALL
    SELECT 'Vazhikkadavu' AS name UNION ALL
    SELECT 'Veliyankode' AS name UNION ALL
    SELECT 'Vengara' AS name UNION ALL
    SELECT 'Vettathur' AS name UNION ALL
    SELECT 'Vettikattiri' AS name UNION ALL
    SELECT 'Vettilappara' AS name UNION ALL
    SELECT 'Vettom' AS name UNION ALL
    SELECT 'Wandoor' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Malappuram'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Palakkad (105 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Agali' AS name UNION ALL
    SELECT 'Akathethara' AS name UNION ALL
    SELECT 'Alanallur' AS name UNION ALL
    SELECT 'Alathur' AS name UNION ALL
    SELECT 'Anakara' AS name UNION ALL
    SELECT 'Attappady' AS name UNION ALL
    SELECT 'Ayilur' AS name UNION ALL
    SELECT 'Callissery' AS name UNION ALL
    SELECT 'Chandranagar' AS name UNION ALL
    SELECT 'Cherpulassery' AS name UNION ALL
    SELECT 'Chittur' AS name UNION ALL
    SELECT 'Coyalmannam' AS name UNION ALL
    SELECT 'Elappully' AS name UNION ALL
    SELECT 'Elavanchery' AS name UNION ALL
    SELECT 'Erimayur' AS name UNION ALL
    SELECT 'Eruthiyampathy' AS name UNION ALL
    SELECT 'Kadampazhipuram' AS name UNION ALL
    SELECT 'Kallamala' AS name UNION ALL
    SELECT 'Kallekkad' AS name UNION ALL
    SELECT 'Kalmandapam' AS name UNION ALL
    SELECT 'Kanjikode' AS name UNION ALL
    SELECT 'Kanjikode Industrial Area' AS name UNION ALL
    SELECT 'Kannadi' AS name UNION ALL
    SELECT 'Kannambra' AS name UNION ALL
    SELECT 'Kappur' AS name UNION ALL
    SELECT 'Karakurussi' AS name UNION ALL
    SELECT 'Karimba' AS name UNION ALL
    SELECT 'Karimpuzha' AS name UNION ALL
    SELECT 'Kavasery' AS name UNION ALL
    SELECT 'Keralassery' AS name UNION ALL
    SELECT 'Kizhakkenchery' AS name UNION ALL
    SELECT 'Kodumbu' AS name UNION ALL
    SELECT 'Koduvayur' AS name UNION ALL
    SELECT 'Kollengode' AS name UNION ALL
    SELECT 'Kongad' AS name UNION ALL
    SELECT 'Koppam' AS name UNION ALL
    SELECT 'Kottayi' AS name UNION ALL
    SELECT 'Kottoppadam' AS name UNION ALL
    SELECT 'Kozhinjampara' AS name UNION ALL
    SELECT 'Kulukkallur' AS name UNION ALL
    SELECT 'Kumaramputhur' AS name UNION ALL
    SELECT 'Kuthannur' AS name UNION ALL
    SELECT 'Kuzhalmannam' AS name UNION ALL
    SELECT 'Malampuzha' AS name UNION ALL
    SELECT 'Mangalam Dam' AS name UNION ALL
    SELECT 'Mankara' AS name UNION ALL
    SELECT 'Mannarkkad' AS name UNION ALL
    SELECT 'Mannur' AS name UNION ALL
    SELECT 'Mathur' AS name UNION ALL
    SELECT 'Melarcode' AS name UNION ALL
    SELECT 'Moolathara' AS name UNION ALL
    SELECT 'Mundur' AS name UNION ALL
    SELECT 'Muthalamada' AS name UNION ALL
    SELECT 'Muthuthala' AS name UNION ALL
    SELECT 'Nagalassery' AS name UNION ALL
    SELECT 'Nallepully' AS name UNION ALL
    SELECT 'Nellaya' AS name UNION ALL
    SELECT 'Nemmara' AS name UNION ALL
    SELECT 'Olavakkode' AS name UNION ALL
    SELECT 'Ongallur' AS name UNION ALL
    SELECT 'Ottapalam' AS name UNION ALL
    SELECT 'Ozhalapathy' AS name UNION ALL
    SELECT 'Palakayam' AS name UNION ALL
    SELECT 'Palakkad' AS name UNION ALL
    SELECT 'Palakkad Town' AS name UNION ALL
    SELECT 'Pallassana' AS name UNION ALL
    SELECT 'Paruthur' AS name UNION ALL
    SELECT 'Pattambi' AS name UNION ALL
    SELECT 'Pattenchery' AS name UNION ALL
    SELECT 'Pattithara' AS name UNION ALL
    SELECT 'Payyanedam' AS name UNION ALL
    SELECT 'Perukotukurussi' AS name UNION ALL
    SELECT 'Perumatty' AS name UNION ALL
    SELECT 'Peruvemba' AS name UNION ALL
    SELECT 'Pirayiri' AS name UNION ALL
    SELECT 'Polpully' AS name UNION ALL
    SELECT 'Pookkottukavu' AS name UNION ALL
    SELECT 'Pottassery' AS name UNION ALL
    SELECT 'Pudussery' AS name UNION ALL
    SELECT 'Puthukode' AS name UNION ALL
    SELECT 'Puthunagaram' AS name UNION ALL
    SELECT 'Shoranur' AS name UNION ALL
    SELECT 'Sreekrishnapuram' AS name UNION ALL
    SELECT 'Sultanpet' AS name UNION ALL
    SELECT 'Tarur' AS name UNION ALL
    SELECT 'Thachampara' AS name UNION ALL
    SELECT 'Thachanattukara' AS name UNION ALL
    SELECT 'Thattamangalam' AS name UNION ALL
    SELECT 'Thekkedesom' AS name UNION ALL
    SELECT 'Thenkurissi' AS name UNION ALL
    SELECT 'Thirumittakode' AS name UNION ALL
    SELECT 'Thiruvegapura' AS name UNION ALL
    SELECT 'Thrikadeeri' AS name UNION ALL
    SELECT 'Thrithala' AS name UNION ALL
    SELECT 'Vadakarapathy' AS name UNION ALL
    SELECT 'Vadakkenchery' AS name UNION ALL
    SELECT 'Vadavannur' AS name UNION ALL
    SELECT 'Vallapuzha' AS name UNION ALL
    SELECT 'Vallengi' AS name UNION ALL
    SELECT 'Vandazhy' AS name UNION ALL
    SELECT 'Vandithavalam' AS name UNION ALL
    SELECT 'Vellinezhi' AS name UNION ALL
    SELECT 'Vilayur' AS name UNION ALL
    SELECT 'Walayar' AS name UNION ALL
    SELECT 'Yakkara' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Palakkad'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Pathanamthitta (71 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Adoor' AS name UNION ALL
    SELECT 'Angadi' AS name UNION ALL
    SELECT 'Anicad' AS name UNION ALL
    SELECT 'Aranmula' AS name UNION ALL
    SELECT 'Aruvappulam' AS name UNION ALL
    SELECT 'Athikkayam' AS name UNION ALL
    SELECT 'Ayiroor' AS name UNION ALL
    SELECT 'Chengannur border area' AS name UNION ALL
    SELECT 'Chenneerkara' AS name UNION ALL
    SELECT 'Cherukole' AS name UNION ALL
    SELECT 'Chittar' AS name UNION ALL
    SELECT 'Elanthoor' AS name UNION ALL
    SELECT 'Enadimangalam' AS name UNION ALL
    SELECT 'Enathu' AS name UNION ALL
    SELECT 'Erathu' AS name UNION ALL
    SELECT 'Eraviperoor' AS name UNION ALL
    SELECT 'Ezhamkulam' AS name UNION ALL
    SELECT 'Ezhumattoor' AS name UNION ALL
    SELECT 'Iravan' AS name UNION ALL
    SELECT 'Kadampanadu' AS name UNION ALL
    SELECT 'Kadapra' AS name UNION ALL
    SELECT 'Kalanjoor' AS name UNION ALL
    SELECT 'Kallooppara' AS name UNION ALL
    SELECT 'Kaviyoor' AS name UNION ALL
    SELECT 'Kidangannur' AS name UNION ALL
    SELECT 'Kodumon' AS name UNION ALL
    SELECT 'Koipuram' AS name UNION ALL
    SELECT 'Kollamula' AS name UNION ALL
    SELECT 'Konni' AS name UNION ALL
    SELECT 'Konnithazham' AS name UNION ALL
    SELECT 'Koodal' AS name UNION ALL
    SELECT 'Kottangal' AS name UNION ALL
    SELECT 'Kozhencherry' AS name UNION ALL
    SELECT 'Kozhenchery' AS name UNION ALL
    SELECT 'Kulanada' AS name UNION ALL
    SELECT 'Kunnamthanam' AS name UNION ALL
    SELECT 'Kurampala' AS name UNION ALL
    SELECT 'Kuttoor' AS name UNION ALL
    SELECT 'Malayalapuzha' AS name UNION ALL
    SELECT 'Mallappally' AS name UNION ALL
    SELECT 'Mallappuzhassery' AS name UNION ALL
    SELECT 'Mezhuveli' AS name UNION ALL
    SELECT 'Muthoor' AS name UNION ALL
    SELECT 'Mylapra' AS name UNION ALL
    SELECT 'Naranganam' AS name UNION ALL
    SELECT 'Nedumpuram' AS name UNION ALL
    SELECT 'Nilackal' AS name UNION ALL
    SELECT 'Niranam' AS name UNION ALL
    SELECT 'Omalloor' AS name UNION ALL
    SELECT 'Pallickal' AS name UNION ALL
    SELECT 'Pamba' AS name UNION ALL
    SELECT 'Pandalam' AS name UNION ALL
    SELECT 'Pandalam Thekkekara' AS name UNION ALL
    SELECT 'Pathanamthitta' AS name UNION ALL
    SELECT 'Pathanamthitta Town' AS name UNION ALL
    SELECT 'Pazhavangadi' AS name UNION ALL
    SELECT 'Peringara' AS name UNION ALL
    SELECT 'Perumpetty' AS name UNION ALL
    SELECT 'Perunad' AS name UNION ALL
    SELECT 'Pramadom' AS name UNION ALL
    SELECT 'Puramattam' AS name UNION ALL
    SELECT 'Ranni' AS name UNION ALL
    SELECT 'Sabarimala' AS name UNION ALL
    SELECT 'Seethathode' AS name UNION ALL
    SELECT 'Thannithode' AS name UNION ALL
    SELECT 'Thelliyoor' AS name UNION ALL
    SELECT 'Thiruvalla' AS name UNION ALL
    SELECT 'Thottapuzhassery' AS name UNION ALL
    SELECT 'Thumpamon' AS name UNION ALL
    SELECT 'Vadasserikkara' AS name UNION ALL
    SELECT 'Vallicode' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Pathanamthitta'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Thiruvananthapuram (86 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Akkulam' AS name UNION ALL
    SELECT 'Amboori' AS name UNION ALL
    SELECT 'Anad' AS name UNION ALL
    SELECT 'Andoorkonam' AS name UNION ALL
    SELECT 'Aruvikkara' AS name UNION ALL
    SELECT 'Aryanad' AS name UNION ALL
    SELECT 'Aryancode' AS name UNION ALL
    SELECT 'Athiyanoor' AS name UNION ALL
    SELECT 'Attakulangara' AS name UNION ALL
    SELECT 'Attingal' AS name UNION ALL
    SELECT 'Azhoor' AS name UNION ALL
    SELECT 'Balaramapuram' AS name UNION ALL
    SELECT 'Beemapally' AS name UNION ALL
    SELECT 'Chacka' AS name UNION ALL
    SELECT 'Chala' AS name UNION ALL
    SELECT 'Chemmaruthy' AS name UNION ALL
    SELECT 'Cherunniyoor' AS name UNION ALL
    SELECT 'Chirayinkeezhu' AS name UNION ALL
    SELECT 'East Fort' AS name UNION ALL
    SELECT 'Edava' AS name UNION ALL
    SELECT 'Jagathy' AS name UNION ALL
    SELECT 'Kadakkavoor' AS name UNION ALL
    SELECT 'Kadinamkulam' AS name UNION ALL
    SELECT 'Kallambalam' AS name UNION ALL
    SELECT 'Kallara' AS name UNION ALL
    SELECT 'Kallikkadu' AS name UNION ALL
    SELECT 'Kalliyoor' AS name UNION ALL
    SELECT 'Kanjiramkulam' AS name UNION ALL
    SELECT 'Karakulam' AS name UNION ALL
    SELECT 'Karamana' AS name UNION ALL
    SELECT 'Karavaram' AS name UNION ALL
    SELECT 'Kattakkada' AS name UNION ALL
    SELECT 'Kazhakkoottam' AS name UNION ALL
    SELECT 'Kazhakuttam' AS name UNION ALL
    SELECT 'Kesavadasapuram' AS name UNION ALL
    SELECT 'Kilimanoor' AS name UNION ALL
    SELECT 'Kovalam' AS name UNION ALL
    SELECT 'Kowdiar' AS name UNION ALL
    SELECT 'Kumarapuram' AS name UNION ALL
    SELECT 'Kuravankonam' AS name UNION ALL
    SELECT 'Manacaud' AS name UNION ALL
    SELECT 'Mannanthala' AS name UNION ALL
    SELECT 'Medical College' AS name UNION ALL
    SELECT 'Medical College Area' AS name UNION ALL
    SELECT 'Menamkulam' AS name UNION ALL
    SELECT 'Nalanchira' AS name UNION ALL
    SELECT 'Nandancode' AS name UNION ALL
    SELECT 'Nedumangad' AS name UNION ALL
    SELECT 'Nemom' AS name UNION ALL
    SELECT 'Neyyattinkara' AS name UNION ALL
    SELECT 'Palayam' AS name UNION ALL
    SELECT 'Pallippuram' AS name UNION ALL
    SELECT 'Pappanamcode' AS name UNION ALL
    SELECT 'Parassala' AS name UNION ALL
    SELECT 'Pattom' AS name UNION ALL
    SELECT 'Peroorkada' AS name UNION ALL
    SELECT 'Pettah' AS name UNION ALL
    SELECT 'Poojappura' AS name UNION ALL
    SELECT 'Pothencode' AS name UNION ALL
    SELECT 'Pravachambalam' AS name UNION ALL
    SELECT 'Sasthamangalam' AS name UNION ALL
    SELECT 'Shangumukham' AS name UNION ALL
    SELECT 'Sreekaryam' AS name UNION ALL
    SELECT 'Statue' AS name UNION ALL
    SELECT 'Technopark' AS name UNION ALL
    SELECT 'Technopark Area' AS name UNION ALL
    SELECT 'Thampanoor' AS name UNION ALL
    SELECT 'Thiruvallam' AS name UNION ALL
    SELECT 'Thiruvananthapuram' AS name UNION ALL
    SELECT 'Thiruvananthapuram City' AS name UNION ALL
    SELECT 'Thycaud' AS name UNION ALL
    SELECT 'Ulloor' AS name UNION ALL
    SELECT 'Valiyathura' AS name UNION ALL
    SELECT 'Vamanapuram' AS name UNION ALL
    SELECT 'Vanchiyoor' AS name UNION ALL
    SELECT 'Varkala' AS name UNION ALL
    SELECT 'Varkala North' AS name UNION ALL
    SELECT 'Vazhichal' AS name UNION ALL
    SELECT 'Vazhuthacaud' AS name UNION ALL
    SELECT 'Vellanad' AS name UNION ALL
    SELECT 'Vellar' AS name UNION ALL
    SELECT 'Vellarada' AS name UNION ALL
    SELECT 'Vellayambalam' AS name UNION ALL
    SELECT 'Venjaramoodu' AS name UNION ALL
    SELECT 'Vizhinjam' AS name UNION ALL
    SELECT 'West Fort' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Thiruvananthapuram'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Thrissur (120 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Akathiyoor' AS name UNION ALL
    SELECT 'Alagappanagar' AS name UNION ALL
    SELECT 'Aloor' AS name UNION ALL
    SELECT 'Alur' AS name UNION ALL
    SELECT 'Amala Nagar' AS name UNION ALL
    SELECT 'Anandapuram' AS name UNION ALL
    SELECT 'Annamanada' AS name UNION ALL
    SELECT 'Athirappilly' AS name UNION ALL
    SELECT 'Ayyanthole' AS name UNION ALL
    SELECT 'Azhikode' AS name UNION ALL
    SELECT 'Brahmakulam' AS name UNION ALL
    SELECT 'Chalakudy' AS name UNION ALL
    SELECT 'Chavakkad' AS name UNION ALL
    SELECT 'Chelakkara' AS name UNION ALL
    SELECT 'Chemmanthatta' AS name UNION ALL
    SELECT 'Chendrappini' AS name UNION ALL
    SELECT 'Chengallur' AS name UNION ALL
    SELECT 'Cherpu' AS name UNION ALL
    SELECT 'Cheruthuruthy' AS name UNION ALL
    SELECT 'Choondal' AS name UNION ALL
    SELECT 'Chowwannur' AS name UNION ALL
    SELECT 'East Chalakudy' AS name UNION ALL
    SELECT 'East Fort Thrissur' AS name UNION ALL
    SELECT 'Edakkazhiyur' AS name UNION ALL
    SELECT 'Edathiruthy' AS name UNION ALL
    SELECT 'Edavilangu' AS name UNION ALL
    SELECT 'Elanjipra' AS name UNION ALL
    SELECT 'Elavally' AS name UNION ALL
    SELECT 'Engandiyur' AS name UNION ALL
    SELECT 'Eranellur' AS name UNION ALL
    SELECT 'Eriyad' AS name UNION ALL
    SELECT 'Eyyal' AS name UNION ALL
    SELECT 'Guruvayur' AS name UNION ALL
    SELECT 'Irinjalakuda' AS name UNION ALL
    SELECT 'Kadangode' AS name UNION ALL
    SELECT 'Kadappuram' AS name UNION ALL
    SELECT 'Kadavallur' AS name UNION ALL
    SELECT 'Kadikkad' AS name UNION ALL
    SELECT 'Kaipamangalam' AS name UNION ALL
    SELECT 'Kallettumkara' AS name UNION ALL
    SELECT 'Kallur' AS name UNION ALL
    SELECT 'Kandanassery' AS name UNION ALL
    SELECT 'Kanipayyur' AS name UNION ALL
    SELECT 'Karalam' AS name UNION ALL
    SELECT 'Karikkad' AS name UNION ALL
    SELECT 'Kattakampal' AS name UNION ALL
    SELECT 'Kattur' AS name UNION ALL
    SELECT 'Kiralur' AS name UNION ALL
    SELECT 'Kizhakkummuri' AS name UNION ALL
    SELECT 'Kodakara' AS name UNION ALL
    SELECT 'Kodassery' AS name UNION ALL
    SELECT 'Kodungallur' AS name UNION ALL
    SELECT 'Koolimuttam' AS name UNION ALL
    SELECT 'Koorkenchery' AS name UNION ALL
    SELECT 'Koratty' AS name UNION ALL
    SELECT 'Kundazhiyur' AS name UNION ALL
    SELECT 'Kunnamkulam' AS name UNION ALL
    SELECT 'Kuruvilassery' AS name UNION ALL
    SELECT 'Madathumpady' AS name UNION ALL
    SELECT 'Mala' AS name UNION ALL
    SELECT 'Mangad' AS name UNION ALL
    SELECT 'Mannuthy' AS name UNION ALL
    SELECT 'Mattathur' AS name UNION ALL
    SELECT 'Melur' AS name UNION ALL
    SELECT 'Mulankunnathukavu' AS name UNION ALL
    SELECT 'Mullassery' AS name UNION ALL
    SELECT 'Mupliyam' AS name UNION ALL
    SELECT 'Muringoor' AS name UNION ALL
    SELECT 'Muriyad' AS name UNION ALL
    SELECT 'Nandipulam' AS name UNION ALL
    SELECT 'Nattika' AS name UNION ALL
    SELECT 'Nellayi' AS name UNION ALL
    SELECT 'Ollur' AS name UNION ALL
    SELECT 'Orumanayur' AS name UNION ALL
    SELECT 'Padinjare Vemballur' AS name UNION ALL
    SELECT 'Padiyur' AS name UNION ALL
    SELECT 'Pallippuram' AS name UNION ALL
    SELECT 'Panangad' AS name UNION ALL
    SELECT 'Pappinivattom' AS name UNION ALL
    SELECT 'Parappukkara' AS name UNION ALL
    SELECT 'Pariyaram' AS name UNION ALL
    SELECT 'Pavaratty' AS name UNION ALL
    SELECT 'Pazhanji' AS name UNION ALL
    SELECT 'Perambra' AS name UNION ALL
    SELECT 'Perinjanam' AS name UNION ALL
    SELECT 'Perumpilavu' AS name UNION ALL
    SELECT 'Poomangalam' AS name UNION ALL
    SELECT 'Poothole' AS name UNION ALL
    SELECT 'Porkulam' AS name UNION ALL
    SELECT 'Potta' AS name UNION ALL
    SELECT 'Poyya' AS name UNION ALL
    SELECT 'Pudukad' AS name UNION ALL
    SELECT 'Pullur' AS name UNION ALL
    SELECT 'Punkunnam' AS name UNION ALL
    SELECT 'Punnayur' AS name UNION ALL
    SELECT 'Punnayurkulam' AS name UNION ALL
    SELECT 'Puthenchira' AS name UNION ALL
    SELECT 'Puzhakkal' AS name UNION ALL
    SELECT 'Swaraj Round' AS name UNION ALL
    SELECT 'Talikkulam' AS name UNION ALL
    SELECT 'Thayyur' AS name UNION ALL
    SELECT 'Thazhekkad' AS name UNION ALL
    SELECT 'Thekkumkara' AS name UNION ALL
    SELECT 'Thrissur' AS name UNION ALL
    SELECT 'Thrissur Town' AS name UNION ALL
    SELECT 'Trikkur' AS name UNION ALL
    SELECT 'Triprayar' AS name UNION ALL
    SELECT 'Vadakkekad' AS name UNION ALL
    SELECT 'Vadama' AS name UNION ALL
    SELECT 'Valappad' AS name UNION ALL
    SELECT 'Vallivattom' AS name UNION ALL
    SELECT 'Varandarappilly' AS name UNION ALL
    SELECT 'Vellarakkad' AS name UNION ALL
    SELECT 'Vellikulangara' AS name UNION ALL
    SELECT 'Velur' AS name UNION ALL
    SELECT 'Venkitangu' AS name UNION ALL
    SELECT 'Viyyur' AS name UNION ALL
    SELECT 'Wadakkanchery' AS name UNION ALL
    SELECT 'West Chalakudy' AS name UNION ALL
    SELECT 'West Fort Thrissur' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Thrissur'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

-- --------------------------------------------------------------------------
-- District: Wayanad (51 localities)
-- --------------------------------------------------------------------------
INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, l.name
FROM `districts` d
JOIN `states` s ON d.state_id = s.id
CROSS JOIN (
    SELECT 'Achooranam' AS name UNION ALL
    SELECT 'Ambalavayal' AS name UNION ALL
    SELECT 'Anjukunnu' AS name UNION ALL
    SELECT 'Cheeral' AS name UNION ALL
    SELECT 'Cherukattoor' AS name UNION ALL
    SELECT 'Chundel' AS name UNION ALL
    SELECT 'Edavaka' AS name UNION ALL
    SELECT 'Irulam' AS name UNION ALL
    SELECT 'Kalpetta' AS name UNION ALL
    SELECT 'Kanhirangad' AS name UNION ALL
    SELECT 'Kaniyambetta' AS name UNION ALL
    SELECT 'Kavummannam' AS name UNION ALL
    SELECT 'Kidanganad' AS name UNION ALL
    SELECT 'Kottathara' AS name UNION ALL
    SELECT 'Krishnagiri' AS name UNION ALL
    SELECT 'Kunnathidavaka' AS name UNION ALL
    SELECT 'Kuppadi' AS name UNION ALL
    SELECT 'Kuppadithara' AS name UNION ALL
    SELECT 'Kuruva Island area' AS name UNION ALL
    SELECT 'Mananthavady' AS name UNION ALL
    SELECT 'Meenangadi' AS name UNION ALL
    SELECT 'Meppadi' AS name UNION ALL
    SELECT 'Muppainad' AS name UNION ALL
    SELECT 'Muttil' AS name UNION ALL
    SELECT 'Nadavayal' AS name UNION ALL
    SELECT 'Nallurnad' AS name UNION ALL
    SELECT 'Nenmeni' AS name UNION ALL
    SELECT 'Noolpuzha' AS name UNION ALL
    SELECT 'Padinjarathara' AS name UNION ALL
    SELECT 'Panamaram' AS name UNION ALL
    SELECT 'Panamaram town' AS name UNION ALL
    SELECT 'Payyampally' AS name UNION ALL
    SELECT 'Periya' AS name UNION ALL
    SELECT 'Poothadi' AS name UNION ALL
    SELECT 'Porunnannur' AS name UNION ALL
    SELECT 'Pozhuthana' AS name UNION ALL
    SELECT 'Pulpally' AS name UNION ALL
    SELECT 'Purakkadi' AS name UNION ALL
    SELECT 'Sulthan Bathery' AS name UNION ALL
    SELECT 'Thariode' AS name UNION ALL
    SELECT 'Thavinjal' AS name UNION ALL
    SELECT 'Thirunelly' AS name UNION ALL
    SELECT 'Thomattuchal' AS name UNION ALL
    SELECT 'Thondernadu' AS name UNION ALL
    SELECT 'Thrikkaippetta' AS name UNION ALL
    SELECT 'Thrissilery' AS name UNION ALL
    SELECT 'Valad' AS name UNION ALL
    SELECT 'Vellamunda' AS name UNION ALL
    SELECT 'Vellarmala' AS name UNION ALL
    SELECT 'Vengappally' AS name UNION ALL
    SELECT 'Vythiri' AS name
) l
WHERE (s.code = 'KL' OR s.name = 'Kerala')
  AND d.name = 'Wayanad'
  AND NOT EXISTS (
      SELECT 1 FROM `localities` existing 
      WHERE existing.district_id = d.id AND existing.name = l.name
  );

SET FOREIGN_KEY_CHECKS = 1;
