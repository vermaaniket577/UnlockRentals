-- ==============================================================================
-- MAYILADUTHURAI DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safe for live production database
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'Achalpuram' AS name UNION ALL
    SELECT 'Akkur' AS name UNION ALL
    SELECT 'Akkur Railway Station Area' AS name UNION ALL
    SELECT 'Akkur Road' AS name UNION ALL
    SELECT 'Akkur Town' AS name UNION ALL
    SELECT 'Alakkudi' AS name UNION ALL
    SELECT 'Ananthamangalam' AS name UNION ALL
    SELECT 'Arupathy' AS name UNION ALL
    SELECT 'Avayambalpuram' AS name UNION ALL
    SELECT 'Brahmapureeswarar Temple Area' AS name UNION ALL
    SELECT 'Cauvery River Bank Area' AS name UNION ALL
    SELECT 'Chandrapadi' AS name UNION ALL
    SELECT 'Chidambaram Road' AS name UNION ALL
    SELECT 'Chinnangudi' AS name UNION ALL
    SELECT 'Danish Fort Area' AS name UNION ALL
    SELECT 'Dharmapuram' AS name UNION ALL
    SELECT 'Kadalangudi' AS name UNION ALL
    SELECT 'Kaikatti' AS name UNION ALL
    SELECT 'Kattuchery' AS name UNION ALL
    SELECT 'Kaveripoompattinam' AS name UNION ALL
    SELECT 'Kaveripoompattinam Beach Area' AS name UNION ALL
    SELECT 'Kollidam' AS name UNION ALL
    SELECT 'Kollidam Bus Stand' AS name UNION ALL
    SELECT 'Kollidam Main Road' AS name UNION ALL
    SELECT 'Kollidam Road' AS name UNION ALL
    SELECT 'Kollidam Town' AS name UNION ALL
    SELECT 'Komal' AS name UNION ALL
    SELECT 'Konerirajapuram' AS name UNION ALL
    SELECT 'Koranad' AS name UNION ALL
    SELECT 'Kumbakonam Road' AS name UNION ALL
    SELECT 'Kuthalam' AS name UNION ALL
    SELECT 'Kuthalam Bus Stand Area' AS name UNION ALL
    SELECT 'Kuthalam Railway Station Area' AS name UNION ALL
    SELECT 'Kuthalam Road' AS name UNION ALL
    SELECT 'Kuthalam Town' AS name UNION ALL
    SELECT 'Kuttalam Road' AS name UNION ALL
    SELECT 'Mahadhana Street' AS name UNION ALL
    SELECT 'Mahendrapalli' AS name UNION ALL
    SELECT 'Manalmedu' AS name UNION ALL
    SELECT 'Manalmedu Main Road' AS name UNION ALL
    SELECT 'Manalmedu Road' AS name UNION ALL
    SELECT 'Manalmedu Town' AS name UNION ALL
    SELECT 'Manikgramam' AS name UNION ALL
    SELECT 'Manikkapangu' AS name UNION ALL
    SELECT 'Mannampandal' AS name UNION ALL
    SELECT 'Mappadugai' AS name UNION ALL
    SELECT 'Maruthur' AS name UNION ALL
    SELECT 'Mayiladuthurai' AS name UNION ALL
    SELECT 'Mayiladuthurai Bus Stand Area' AS name UNION ALL
    SELECT 'Mayiladuthurai Junction' AS name UNION ALL
    SELECT 'Mayiladuthurai Railway Station Area' AS name UNION ALL
    SELECT 'Mayiladuthurai Road' AS name UNION ALL
    SELECT 'Mayiladuthurai Town' AS name UNION ALL
    SELECT 'Melaiyur' AS name UNION ALL
    SELECT 'Moovalur' AS name UNION ALL
    SELECT 'Nallathukudi' AS name UNION ALL
    SELECT 'Nangur' AS name UNION ALL
    SELECT 'Neppathur' AS name UNION ALL
    SELECT 'Palaiyur' AS name UNION ALL
    SELECT 'Pattamangalam' AS name UNION ALL
    SELECT 'Pazhaiyar' AS name UNION ALL
    SELECT 'Perambur' AS name UNION ALL
    SELECT 'Perumalkoil' AS name UNION ALL
    SELECT 'Poompuhar' AS name UNION ALL
    SELECT 'Poompuhar Beach Area' AS name UNION ALL
    SELECT 'Poompuhar Main Road' AS name UNION ALL
    SELECT 'Poompuhar Road' AS name UNION ALL
    SELECT 'Poompuhar Town' AS name UNION ALL
    SELECT 'Porayar' AS name UNION ALL
    SELECT 'Porayar Bus Stand Area' AS name UNION ALL
    SELECT 'Porayar Road' AS name UNION ALL
    SELECT 'Porayar Town' AS name UNION ALL
    SELECT 'Pudupattinam' AS name UNION ALL
    SELECT 'Puthur' AS name UNION ALL
    SELECT 'Sembanarkoil' AS name UNION ALL
    SELECT 'Sembanarkoil Main Road' AS name UNION ALL
    SELECT 'Sembanarkoil Road' AS name UNION ALL
    SELECT 'Sembanarkoil Town' AS name UNION ALL
    SELECT 'Sethur' AS name UNION ALL
    SELECT 'Siddharkadu' AS name UNION ALL
    SELECT 'Sirkali' AS name UNION ALL
    SELECT 'Sirkazhi' AS name UNION ALL
    SELECT 'Sirkazhi Bus Stand' AS name UNION ALL
    SELECT 'Sirkazhi Railway Station Area' AS name UNION ALL
    SELECT 'Sirkazhi Road' AS name UNION ALL
    SELECT 'Sirkazhi Town' AS name UNION ALL
    SELECT 'Thalainayar' AS name UNION ALL
    SELECT 'Tharangambadi' AS name UNION ALL
    SELECT 'Tharangambadi Beach Area' AS name UNION ALL
    SELECT 'Tharangambadi Road' AS name UNION ALL
    SELECT 'Tharangambadi Town' AS name UNION ALL
    SELECT 'Thenpathi' AS name UNION ALL
    SELECT 'Thiruindalur' AS name UNION ALL
    SELECT 'Thirukkurayalur' AS name UNION ALL
    SELECT 'Thirulogi' AS name UNION ALL
    SELECT 'Thirumanancheri' AS name UNION ALL
    SELECT 'Thirumangalam' AS name UNION ALL
    SELECT 'Thirumullaivasal' AS name UNION ALL
    SELECT 'Thirumullaivasal Beach Area' AS name UNION ALL
    SELECT 'Thirumullaivasal Main Road' AS name UNION ALL
    SELECT 'Thirumullaivasal Road' AS name UNION ALL
    SELECT 'Thirunangur' AS name UNION ALL
    SELECT 'Thirunangur Road' AS name UNION ALL
    SELECT 'Thirunangur Temple Area' AS name UNION ALL
    SELECT 'Thiruvaduthurai' AS name UNION ALL
    SELECT 'Thiruvalaputhur' AS name UNION ALL
    SELECT 'Thiruvengadu' AS name UNION ALL
    SELECT 'Thiruvengadu Road' AS name UNION ALL
    SELECT 'Thiruvengadu Temple Area' AS name UNION ALL
    SELECT 'Thiruvengadu Town' AS name UNION ALL
    SELECT 'Tirumullaivasal' AS name UNION ALL
    SELECT 'Tranquebar' AS name UNION ALL
    SELECT 'Vaitheeswaran Koil' AS name UNION ALL
    SELECT 'Vaitheeswaran Koil Railway Station Area' AS name UNION ALL
    SELECT 'Vaitheeswaran Koil Road' AS name UNION ALL
    SELECT 'Vaitheeswaran Koil Temple Area' AS name UNION ALL
    SELECT 'Vaitheeswarankoil' AS name UNION ALL
    SELECT 'Vanagiri' AS name UNION ALL
    SELECT 'Villiyanallur' AS name
) AS loc
JOIN `districts` d ON d.name = 'Mayiladuthurai'
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
