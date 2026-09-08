-- ==============================================================================
-- KRISHNAGIRI DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safe for live production database
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'A. M. Halli' AS name UNION ALL
    SELECT 'Achettipalli' AS name UNION ALL
    SELECT 'Aiyur' AS name UNION ALL
    SELECT 'Alasanatham' AS name UNION ALL
    SELECT 'Alasanatham Industrial Area' AS name UNION ALL
    SELECT 'Anchetti' AS name UNION ALL
    SELECT 'Anchetty' AS name UNION ALL
    SELECT 'Anchetty Bus Stand' AS name UNION ALL
    SELECT 'Anchetty Main Road' AS name UNION ALL
    SELECT 'Anchetty Road' AS name UNION ALL
    SELECT 'Anchetty Town' AS name UNION ALL
    SELECT 'Anekal Border Area' AS name UNION ALL
    SELECT 'Anekal Road' AS name UNION ALL
    SELECT 'Anthivadi' AS name UNION ALL
    SELECT 'Athipadi' AS name UNION ALL
    SELECT 'Avalapalli' AS name UNION ALL
    SELECT 'Avalapalli Industrial Area' AS name UNION ALL
    SELECT 'Avoor' AS name UNION ALL
    SELECT 'Bagalur' AS name UNION ALL
    SELECT 'Bagalur Industrial Area' AS name UNION ALL
    SELECT 'Bagalur Road' AS name UNION ALL
    SELECT 'Bagepalli Road' AS name UNION ALL
    SELECT 'Balinayanapalli' AS name UNION ALL
    SELECT 'Bangalore Road' AS name UNION ALL
    SELECT 'Bargur' AS name UNION ALL
    SELECT 'Bargur Bus Stand' AS name UNION ALL
    SELECT 'Bargur Main Road' AS name UNION ALL
    SELECT 'Bargur Road' AS name UNION ALL
    SELECT 'Bargur Town' AS name UNION ALL
    SELECT 'Barur' AS name UNION ALL
    SELECT 'Basthi' AS name UNION ALL
    SELECT 'Begepalli' AS name UNION ALL
    SELECT 'Belagondapalli' AS name UNION ALL
    SELECT 'Belagondapalli Industrial Area' AS name UNION ALL
    SELECT 'Bennangur' AS name UNION ALL
    SELECT 'Berigai' AS name UNION ALL
    SELECT 'Boganapalli' AS name UNION ALL
    SELECT 'Chandrapatti' AS name UNION ALL
    SELECT 'Chennai Road' AS name UNION ALL
    SELECT 'Chennai-Bengaluru Highway' AS name UNION ALL
    SELECT 'Chennapalli' AS name UNION ALL
    SELECT 'Chikka Thirupathi' AS name UNION ALL
    SELECT 'Denkanikottai' AS name UNION ALL
    SELECT 'Denkanikottai Bus Stand' AS name UNION ALL
    SELECT 'Denkanikottai Main Road' AS name UNION ALL
    SELECT 'Denkanikottai Road' AS name UNION ALL
    SELECT 'Denkanikottai Town' AS name UNION ALL
    SELECT 'Devasamudram' AS name UNION ALL
    SELECT 'Dharmapuri Road' AS name UNION ALL
    SELECT 'Doddamanchi' AS name UNION ALL
    SELECT 'Gandhi Road' AS name UNION ALL
    SELECT 'Gangaleri' AS name UNION ALL
    SELECT 'Gummalapuram' AS name UNION ALL
    SELECT 'Gurubarapalli' AS name UNION ALL
    SELECT 'Hanumanthapuram' AS name UNION ALL
    SELECT 'Hogenakkal Road' AS name UNION ALL
    SELECT 'Hosur' AS name UNION ALL
    SELECT 'Hosur Industrial Area' AS name UNION ALL
    SELECT 'Hosur New Bus Stand' AS name UNION ALL
    SELECT 'Hosur Old Bus Stand' AS name UNION ALL
    SELECT 'Hosur Railway Station Area' AS name UNION ALL
    SELECT 'Hosur Road' AS name UNION ALL
    SELECT 'Hosur SIPCOT' AS name UNION ALL
    SELECT 'Hosur Town' AS name UNION ALL
    SELECT 'Jagir Venkatapuram' AS name UNION ALL
    SELECT 'Jambukuttapatti' AS name UNION ALL
    SELECT 'Jawalagiri' AS name UNION ALL
    SELECT 'Jegadevi' AS name UNION ALL
    SELECT 'Kaganur' AS name UNION ALL
    SELECT 'Kakkadasam' AS name UNION ALL
    SELECT 'Kalasapalli' AS name UNION ALL
    SELECT 'Kallavi' AS name UNION ALL
    SELECT 'Kallavi Road' AS name UNION ALL
    SELECT 'Kallipatti' AS name UNION ALL
    SELECT 'Kammampalli' AS name UNION ALL
    SELECT 'Kandikuppam' AS name UNION ALL
    SELECT 'Karandapalli' AS name UNION ALL
    SELECT 'Karapattu' AS name UNION ALL
    SELECT 'Kattiganapalli' AS name UNION ALL
    SELECT 'Kattinayanapalli' AS name UNION ALL
    SELECT 'Kaveripattinam' AS name UNION ALL
    SELECT 'Kaveripattinam Bus Stand' AS name UNION ALL
    SELECT 'Kaveripattinam Main Road' AS name UNION ALL
    SELECT 'Kaveripattinam Road' AS name UNION ALL
    SELECT 'Kaveripattinam Town' AS name UNION ALL
    SELECT 'Kelamangalam' AS name UNION ALL
    SELECT 'Kelamangalam Bus Stand' AS name UNION ALL
    SELECT 'Kelamangalam Industrial Area' AS name UNION ALL
    SELECT 'Kelamangalam Main Road' AS name UNION ALL
    SELECT 'Kelamangalam Road' AS name UNION ALL
    SELECT 'Kelamangalam Road Area' AS name UNION ALL
    SELECT 'Kelamangalam Town' AS name UNION ALL
    SELECT 'Kethanapalli' AS name UNION ALL
    SELECT 'Kodiyalam' AS name UNION ALL
    SELECT 'Konanginaickenpatti' AS name UNION ALL
    SELECT 'Kondampatti' AS name UNION ALL
    SELECT 'Koppakarai' AS name UNION ALL
    SELECT 'Kothagondapalli' AS name UNION ALL
    SELECT 'Kothapalli' AS name UNION ALL
    SELECT 'Kottaiyur' AS name UNION ALL
    SELECT 'Kottapatti' AS name UNION ALL
    SELECT 'Kottur' AS name UNION ALL
    SELECT 'Krishnagiri' AS name UNION ALL
    SELECT 'Krishnagiri Bus Stand Area' AS name UNION ALL
    SELECT 'Krishnagiri Railway Station Area' AS name UNION ALL
    SELECT 'Krishnagiri Road' AS name UNION ALL
    SELECT 'Krishnagiri Town' AS name UNION ALL
    SELECT 'Kullampatti' AS name UNION ALL
    SELECT 'Kumbalam' AS name UNION ALL
    SELECT 'Kumudepalli' AS name UNION ALL
    SELECT 'Kundarapalli' AS name UNION ALL
    SELECT 'Kundumaranapalli' AS name UNION ALL
    SELECT 'Kunnathur' AS name UNION ALL
    SELECT 'Kuppam' AS name UNION ALL
    SELECT 'Kuppam Road' AS name UNION ALL
    SELECT 'Kurubarapalli' AS name UNION ALL
    SELECT 'Londonpet' AS name UNION ALL
    SELECT 'Madepalli' AS name UNION ALL
    SELECT 'Mallinayanapalli' AS name UNION ALL
    SELECT 'Marampatti' AS name UNION ALL
    SELECT 'Marandahalli' AS name UNION ALL
    SELECT 'Marandahalli Road' AS name UNION ALL
    SELECT 'Marappanahalli' AS name UNION ALL
    SELECT 'Marasandiram' AS name UNION ALL
    SELECT 'Marichettihalli' AS name UNION ALL
    SELECT 'Mathigiri' AS name UNION ALL
    SELECT 'Mathigiri Industrial Area' AS name UNION ALL
    SELECT 'Mathur' AS name UNION ALL
    SELECT 'Mathur Bus Stand' AS name UNION ALL
    SELECT 'Mathur Main Road' AS name UNION ALL
    SELECT 'Mathur Road' AS name UNION ALL
    SELECT 'Mathur Town' AS name UNION ALL
    SELECT 'Mittapalli' AS name UNION ALL
    SELECT 'Mookandapalli' AS name UNION ALL
    SELECT 'Mookandapalli Industrial Area' AS name UNION ALL
    SELECT 'Mottur' AS name UNION ALL
    SELECT 'Murukkampatti' AS name UNION ALL
    SELECT 'Muthali' AS name UNION ALL
    SELECT 'Nachikuppam' AS name UNION ALL
    SELECT 'Nagamangalam' AS name UNION ALL
    SELECT 'Nagojanahalli' AS name UNION ALL
    SELECT 'Nallur' AS name UNION ALL
    SELECT 'Nerlagiri' AS name UNION ALL
    SELECT 'New Pet' AS name UNION ALL
    SELECT 'Old Pet' AS name UNION ALL
    SELECT 'Onnalvadi' AS name UNION ALL
    SELECT 'Orappam' AS name UNION ALL
    SELECT 'Paiyur' AS name UNION ALL
    SELECT 'Peddappampatti' AS name UNION ALL
    SELECT 'Perandapalli' AS name UNION ALL
    SELECT 'Perandapalli Industrial Area' AS name UNION ALL
    SELECT 'Pethanapalli' AS name UNION ALL
    SELECT 'Pochampalli' AS name UNION ALL
    SELECT 'Pochampalli Bus Stand' AS name UNION ALL
    SELECT 'Pochampalli Main Road' AS name UNION ALL
    SELECT 'Pochampalli Road' AS name UNION ALL
    SELECT 'Pochampalli Town' AS name UNION ALL
    SELECT 'Poonapalli' AS name UNION ALL
    SELECT 'Pudur' AS name UNION ALL
    SELECT 'Puliyampatti' AS name UNION ALL
    SELECT 'Puliyur' AS name UNION ALL
    SELECT 'Rayakottai' AS name UNION ALL
    SELECT 'Rayakottai Bus Stand' AS name UNION ALL
    SELECT 'Rayakottai Railway Station Area' AS name UNION ALL
    SELECT 'Rayakottai Road' AS name UNION ALL
    SELECT 'Rayakottai Town' AS name UNION ALL
    SELECT 'Salamarathupatti' AS name UNION ALL
    SELECT 'Salem Road' AS name UNION ALL
    SELECT 'Samalpatti' AS name UNION ALL
    SELECT 'Samanapalli' AS name UNION ALL
    SELECT 'Sanasandiram' AS name UNION ALL
    SELECT 'Santhur' AS name UNION ALL
    SELECT 'Shoolagiri' AS name UNION ALL
    SELECT 'Shoolagiri Bus Stand' AS name UNION ALL
    SELECT 'Shoolagiri Industrial Area' AS name UNION ALL
    SELECT 'Shoolagiri Main Road' AS name UNION ALL
    SELECT 'Shoolagiri Road' AS name UNION ALL
    SELECT 'Shoolagiri Town' AS name UNION ALL
    SELECT 'Singarapettai' AS name UNION ALL
    SELECT 'Singarapettai Bus Stand' AS name UNION ALL
    SELECT 'Singarapettai Town' AS name UNION ALL
    SELECT 'Sipcot Phase 1 Hosur' AS name UNION ALL
    SELECT 'Sipcot Phase 2 Hosur' AS name UNION ALL
    SELECT 'Sipcot Phase I' AS name UNION ALL
    SELECT 'Sipcot Phase II' AS name UNION ALL
    SELECT 'Soolagiri Industrial Area' AS name UNION ALL
    SELECT 'Sundekuppam' AS name UNION ALL
    SELECT 'Thally' AS name UNION ALL
    SELECT 'Thally Bus Stand' AS name UNION ALL
    SELECT 'Thally Road' AS name UNION ALL
    SELECT 'Thally Town' AS name UNION ALL
    SELECT 'Thatrahalli' AS name UNION ALL
    SELECT 'Theertham' AS name UNION ALL
    SELECT 'Thimmanapalli' AS name UNION ALL
    SELECT 'Thimmapuram' AS name UNION ALL
    SELECT 'Thorapalli' AS name UNION ALL
    SELECT 'Thorapalli Industrial Area' AS name UNION ALL
    SELECT 'Tirupattur Road' AS name UNION ALL
    SELECT 'Udayandahalli' AS name UNION ALL
    SELECT 'Udedurgam' AS name UNION ALL
    SELECT 'Ulagam' AS name UNION ALL
    SELECT 'Ullukkurukki' AS name UNION ALL
    SELECT 'Urigam' AS name UNION ALL
    SELECT 'Uthangarai' AS name UNION ALL
    SELECT 'Uthangarai Bus Stand' AS name UNION ALL
    SELECT 'Uthangarai Main Road' AS name UNION ALL
    SELECT 'Uthangarai Road' AS name UNION ALL
    SELECT 'Uthangarai Town' AS name UNION ALL
    SELECT 'Varattanapalli' AS name UNION ALL
    SELECT 'Veppalampatti' AS name UNION ALL
    SELECT 'Veppanapalli' AS name UNION ALL
    SELECT 'Veppanapalli Bus Stand' AS name UNION ALL
    SELECT 'Veppanapalli Main Road' AS name UNION ALL
    SELECT 'Veppanapalli Road' AS name UNION ALL
    SELECT 'Veppanapalli Town' AS name UNION ALL
    SELECT 'Zuzuvadi' AS name UNION ALL
    SELECT 'Zuzuvadi Industrial Area' AS name
) AS loc
JOIN `districts` d ON d.name = 'Krishnagiri'
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
