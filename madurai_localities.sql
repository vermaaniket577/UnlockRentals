-- ==============================================================================
-- MADURAI DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safe for live production database
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'Achampathu' AS name UNION ALL
    SELECT 'Airport Road' AS name UNION ALL
    SELECT 'Alagappan Nagar' AS name UNION ALL
    SELECT 'Alagar Kovil Road' AS name UNION ALL
    SELECT 'Alagarkoil Road' AS name UNION ALL
    SELECT 'Alampatti' AS name UNION ALL
    SELECT 'Alanganallur' AS name UNION ALL
    SELECT 'Alanganallur Bus Stand' AS name UNION ALL
    SELECT 'Alanganallur Road' AS name UNION ALL
    SELECT 'Alanganallur Town' AS name UNION ALL
    SELECT 'Anaiyur' AS name UNION ALL
    SELECT 'Anaiyur Bus Stand' AS name UNION ALL
    SELECT 'Anaiyur Main Road' AS name UNION ALL
    SELECT 'Anand Nagar' AS name UNION ALL
    SELECT 'Andalpuram' AS name UNION ALL
    SELECT 'Anna Nagar' AS name UNION ALL
    SELECT 'Anna Nagar East' AS name UNION ALL
    SELECT 'Anna Nagar Main Road' AS name UNION ALL
    SELECT 'Anna Nagar West' AS name UNION ALL
    SELECT 'Arapalayam' AS name UNION ALL
    SELECT 'Arapalayam Bus Stand' AS name UNION ALL
    SELECT 'Arapalayam Main Road' AS name UNION ALL
    SELECT 'Arasaradi' AS name UNION ALL
    SELECT 'Arasaradi Main Road' AS name UNION ALL
    SELECT 'Arittapatti' AS name UNION ALL
    SELECT 'Aruldaspuram' AS name UNION ALL
    SELECT 'Avaniyapuram' AS name UNION ALL
    SELECT 'Avaniyapuram Bus Stand' AS name UNION ALL
    SELECT 'Avaniyapuram Junction' AS name UNION ALL
    SELECT 'Avaniyapuram Main Road' AS name UNION ALL
    SELECT 'Avaniyapuram Road' AS name UNION ALL
    SELECT 'Ayyur' AS name UNION ALL
    SELECT 'Bethaniyapuram' AS name UNION ALL
    SELECT 'Bibikulam' AS name UNION ALL
    SELECT 'Bibikulam Main Road' AS name UNION ALL
    SELECT 'Bypass Road' AS name UNION ALL
    SELECT 'Chellampatti' AS name UNION ALL
    SELECT 'Chinna Chokkikulam' AS name UNION ALL
    SELECT 'Chinthamani' AS name UNION ALL
    SELECT 'Chokkikulam' AS name UNION ALL
    SELECT 'Chokkikulam Main Road' AS name UNION ALL
    SELECT 'Dindigul Road' AS name UNION ALL
    SELECT 'Edayapatti' AS name UNION ALL
    SELECT 'Ellis Nagar' AS name UNION ALL
    SELECT 'Elumalai' AS name UNION ALL
    SELECT 'Goripalayam' AS name UNION ALL
    SELECT 'Goripalayam Junction' AS name UNION ALL
    SELECT 'Goripalayam Main Road' AS name UNION ALL
    SELECT 'Harveypatti' AS name UNION ALL
    SELECT 'Harvipatti' AS name UNION ALL
    SELECT 'Iyer Bungalow' AS name UNION ALL
    SELECT 'Iyer Bungalow Main Road' AS name UNION ALL
    SELECT 'Jaihindpuram' AS name UNION ALL
    SELECT 'K. Pudur' AS name UNION ALL
    SELECT 'K. Pudur Industrial Area' AS name UNION ALL
    SELECT 'K.K. Nagar Housing Board' AS name UNION ALL
    SELECT 'K.P. Road' AS name UNION ALL
    SELECT 'K.Pudur Bus Stand Area' AS name UNION ALL
    SELECT 'K.Pudur Main Road' AS name UNION ALL
    SELECT 'KK Nagar' AS name UNION ALL
    SELECT 'KK Nagar East' AS name UNION ALL
    SELECT 'KK Nagar Main Road' AS name UNION ALL
    SELECT 'KK Nagar West' AS name UNION ALL
    SELECT 'Kachirampatti' AS name UNION ALL
    SELECT 'Kalavasal' AS name UNION ALL
    SELECT 'Kalavasal Junction' AS name UNION ALL
    SELECT 'Kalavasal Main Road' AS name UNION ALL
    SELECT 'Kallandiri' AS name UNION ALL
    SELECT 'Kamarajar Salai' AS name UNION ALL
    SELECT 'Kannanendal' AS name UNION ALL
    SELECT 'Kappalur' AS name UNION ALL
    SELECT 'Karadikal' AS name UNION ALL
    SELECT 'Karadipatti' AS name UNION ALL
    SELECT 'Karungalakudi' AS name UNION ALL
    SELECT 'Karupatti' AS name UNION ALL
    SELECT 'Karuppatti' AS name UNION ALL
    SELECT 'Karuppayurani' AS name UNION ALL
    SELECT 'Kattupatti' AS name UNION ALL
    SELECT 'Keelavalavu' AS name UNION ALL
    SELECT 'Kochadai' AS name UNION ALL
    SELECT 'Kochadai Main Road' AS name UNION ALL
    SELECT 'Koilpatti' AS name UNION ALL
    SELECT 'Kondayampatti' AS name UNION ALL
    SELECT 'Koodal Nagar' AS name UNION ALL
    SELECT 'Koodal Nagar Main Road' AS name UNION ALL
    SELECT 'Koodal Nagar Railway Area' AS name UNION ALL
    SELECT 'Koodal Nagar Railway Station Area' AS name UNION ALL
    SELECT 'Kottampatti' AS name UNION ALL
    SELECT 'Kottampatti Road' AS name UNION ALL
    SELECT 'Kovilpatti' AS name UNION ALL
    SELECT 'Krishnapuram' AS name UNION ALL
    SELECT 'Krishnapuram Colony' AS name UNION ALL
    SELECT 'Kunnathur' AS name UNION ALL
    SELECT 'Kurinjinagar' AS name UNION ALL
    SELECT 'Kutladampatti' AS name UNION ALL
    SELECT 'Madurai' AS name UNION ALL
    SELECT 'Madurai Junction' AS name UNION ALL
    SELECT 'Madurai Kamaraj University Area' AS name UNION ALL
    SELECT 'Madurai Main' AS name UNION ALL
    SELECT 'Madurai Railway Station Area' AS name UNION ALL
    SELECT 'Madurai-Melur Road' AS name UNION ALL
    SELECT 'Mahal Area' AS name UNION ALL
    SELECT 'Mattuthavani' AS name UNION ALL
    SELECT 'Mattuthavani Bus Stand' AS name UNION ALL
    SELECT 'Mattuthavani Integrated Bus Terminus Area' AS name UNION ALL
    SELECT 'Melur' AS name UNION ALL
    SELECT 'Melur Bus Stand Area' AS name UNION ALL
    SELECT 'Melur Main Road' AS name UNION ALL
    SELECT 'Melur Railway Station Area' AS name UNION ALL
    SELECT 'Melur Road' AS name UNION ALL
    SELECT 'Melur Town' AS name UNION ALL
    SELECT 'Moondrumavadi' AS name UNION ALL
    SELECT 'Munichalai' AS name UNION ALL
    SELECT 'Nagamalai' AS name UNION ALL
    SELECT 'Nagamalai Main Road' AS name UNION ALL
    SELECT 'Nagamalai Pudukottai' AS name UNION ALL
    SELECT 'Narasingam' AS name UNION ALL
    SELECT 'Narimedu' AS name UNION ALL
    SELECT 'Narimedu Main Road' AS name UNION ALL
    SELECT 'Nattarmangalam' AS name UNION ALL
    SELECT 'Navinipatti' AS name UNION ALL
    SELECT 'Nilaiyur' AS name UNION ALL
    SELECT 'North Masi Street' AS name UNION ALL
    SELECT 'Othakadai' AS name UNION ALL
    SELECT 'Othakadai Junction' AS name UNION ALL
    SELECT 'Othakadai Main Road' AS name UNION ALL
    SELECT 'P & T Nagar' AS name UNION ALL
    SELECT 'Palamedu' AS name UNION ALL
    SELECT 'Palamedu Bus Stand' AS name UNION ALL
    SELECT 'Palamedu Town' AS name UNION ALL
    SELECT 'Palanganatham' AS name UNION ALL
    SELECT 'Palkalai Nagar' AS name UNION ALL
    SELECT 'Pasumalai' AS name UNION ALL
    SELECT 'Peraiyur' AS name UNION ALL
    SELECT 'Peraiyur Bus Stand' AS name UNION ALL
    SELECT 'Peraiyur Road' AS name UNION ALL
    SELECT 'Peraiyur Town' AS name UNION ALL
    SELECT 'Periyar' AS name UNION ALL
    SELECT 'Periyar Bus Stand Area' AS name UNION ALL
    SELECT 'Perungudi' AS name UNION ALL
    SELECT 'Pethaniyapuram' AS name UNION ALL
    SELECT 'Ponmeni' AS name UNION ALL
    SELECT 'Ponnagaram' AS name UNION ALL
    SELECT 'Pulipatti' AS name UNION ALL
    SELECT 'Puthuthamarai' AS name UNION ALL
    SELECT 'Race Course Road' AS name UNION ALL
    SELECT 'Rameswaram Road' AS name UNION ALL
    SELECT 'Reserve Line' AS name UNION ALL
    SELECT 'Ring Road' AS name UNION ALL
    SELECT 'S.S. Colony' AS name UNION ALL
    SELECT 'SS Colony' AS name UNION ALL
    SELECT 'Sakkimangalam' AS name UNION ALL
    SELECT 'Samayanallur' AS name UNION ALL
    SELECT 'Saptur' AS name UNION ALL
    SELECT 'Sathamangalam' AS name UNION ALL
    SELECT 'Sedapatti' AS name UNION ALL
    SELECT 'Sellur' AS name UNION ALL
    SELECT 'Sellur Bus Stand' AS name UNION ALL
    SELECT 'Sellur Main Road' AS name UNION ALL
    SELECT 'Sellur Railway Area' AS name UNION ALL
    SELECT 'Sembatti' AS name UNION ALL
    SELECT 'Sengapadai' AS name UNION ALL
    SELECT 'Shenoy Nagar' AS name UNION ALL
    SELECT 'Sholavandan' AS name UNION ALL
    SELECT 'Simmakkal' AS name UNION ALL
    SELECT 'Simmakkal Main Road' AS name UNION ALL
    SELECT 'Sivagangai Road' AS name UNION ALL
    SELECT 'Sivananda Nagar' AS name UNION ALL
    SELECT 'Solavandan' AS name UNION ALL
    SELECT 'Solavandan Railway Station Area' AS name UNION ALL
    SELECT 'Solavandan Town' AS name UNION ALL
    SELECT 'South Masi Street' AS name UNION ALL
    SELECT 'Surakkundu' AS name UNION ALL
    SELECT 'Surveyor Colony' AS name UNION ALL
    SELECT 'T. Kallupatti' AS name UNION ALL
    SELECT 'T. Kallupatti Road' AS name UNION ALL
    SELECT 'TPK Road' AS name UNION ALL
    SELECT 'Tallakulam' AS name UNION ALL
    SELECT 'Tallakulam Main Road' AS name UNION ALL
    SELECT 'Tallakulam Perumal Koil Area' AS name UNION ALL
    SELECT 'Teppakulam' AS name UNION ALL
    SELECT 'Thanakkankulam' AS name UNION ALL
    SELECT 'Theni Road' AS name UNION ALL
    SELECT 'Thenpalanji' AS name UNION ALL
    SELECT 'Thirumangalam' AS name UNION ALL
    SELECT 'Thirumangalam Bus Stand' AS name UNION ALL
    SELECT 'Thirumangalam Main Road' AS name UNION ALL
    SELECT 'Thirumangalam Railway Station Area' AS name UNION ALL
    SELECT 'Thirumangalam Road' AS name UNION ALL
    SELECT 'Thirumangalam Town' AS name UNION ALL
    SELECT 'Thirunagar' AS name UNION ALL
    SELECT 'Thirunagar 1st Stop' AS name UNION ALL
    SELECT 'Thirunagar 2nd Stop' AS name UNION ALL
    SELECT 'Thirunagar 3rd Stop' AS name UNION ALL
    SELECT 'Thirunagar 4th Stop' AS name UNION ALL
    SELECT 'Thirunagar 5th Stop' AS name UNION ALL
    SELECT 'Thirunagar 6th Stop' AS name UNION ALL
    SELECT 'Thirunagar 7th Stop' AS name UNION ALL
    SELECT 'Thirunagar 8th Stop' AS name UNION ALL
    SELECT 'Thirunagar Bus Stand' AS name UNION ALL
    SELECT 'Thirunagar Main Road' AS name UNION ALL
    SELECT 'Thirunagar Road' AS name UNION ALL
    SELECT 'Thiruparankundram' AS name UNION ALL
    SELECT 'Thiruparankundram Bus Stand' AS name UNION ALL
    SELECT 'Thiruparankundram Temple Area' AS name UNION ALL
    SELECT 'Thiruparankundram Town' AS name UNION ALL
    SELECT 'Thiruppalai' AS name UNION ALL
    SELECT 'Thiruppalai Bus Stand' AS name UNION ALL
    SELECT 'Thiruppalai Main Road' AS name UNION ALL
    SELECT 'Thirupparankundram' AS name UNION ALL
    SELECT 'Thiruvedagam' AS name UNION ALL
    SELECT 'Town Hall Road' AS name UNION ALL
    SELECT 'Ulaganeri' AS name UNION ALL
    SELECT 'Usilampatti' AS name UNION ALL
    SELECT 'Usilampatti Bus Stand' AS name UNION ALL
    SELECT 'Usilampatti Railway Station Area' AS name UNION ALL
    SELECT 'Usilampatti Road' AS name UNION ALL
    SELECT 'Usilampatti Town' AS name UNION ALL
    SELECT 'Vadipatti' AS name UNION ALL
    SELECT 'Vadipatti Bus Stand' AS name UNION ALL
    SELECT 'Vadipatti Railway Station Area' AS name UNION ALL
    SELECT 'Vadipatti Road' AS name UNION ALL
    SELECT 'Vadipatti Town' AS name UNION ALL
    SELECT 'Valayankulam' AS name UNION ALL
    SELECT 'Vandiyur' AS name UNION ALL
    SELECT 'Vellaripatti' AS name UNION ALL
    SELECT 'Vilangudi' AS name UNION ALL
    SELECT 'Villapuram' AS name UNION ALL
    SELECT 'Villapuram Housing Board' AS name UNION ALL
    SELECT 'Viraganoor' AS name UNION ALL
    SELECT 'Viraganoor Road' AS name UNION ALL
    SELECT 'Virudhunagar Road' AS name UNION ALL
    SELECT 'Y. Othakadai' AS name
) AS loc
JOIN `districts` d ON d.name = 'Madurai'
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
