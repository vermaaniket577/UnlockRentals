-- ==============================================================================
-- KARUR DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safe for live production database
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'Andankoil' AS name UNION ALL
    SELECT 'Andankoil East' AS name UNION ALL
    SELECT 'Andankoil West' AS name UNION ALL
    SELECT 'Andipattakkottai' AS name UNION ALL
    SELECT 'Anna Nagar' AS name UNION ALL
    SELECT 'Aravakurichi' AS name UNION ALL
    SELECT 'Aravakurichi Bus Stand Area' AS name UNION ALL
    SELECT 'Aravakurichi Main Road' AS name UNION ALL
    SELECT 'Aravakurichi Road' AS name UNION ALL
    SELECT 'Aravakurichi Town' AS name UNION ALL
    SELECT 'Athur' AS name UNION ALL
    SELECT 'Ayyarmalai' AS name UNION ALL
    SELECT 'Chinnadharapuram' AS name UNION ALL
    SELECT 'Chinnadharapuram Bus Stand' AS name UNION ALL
    SELECT 'Chinnadharapuram Town' AS name UNION ALL
    SELECT 'Chinthalavadi' AS name UNION ALL
    SELECT 'Coimbatore Road' AS name UNION ALL
    SELECT 'Dindigul Road' AS name UNION ALL
    SELECT 'Elavanasur' AS name UNION ALL
    SELECT 'Erode Road' AS name UNION ALL
    SELECT 'Esanatham' AS name UNION ALL
    SELECT 'Gandhi Nagar' AS name UNION ALL
    SELECT 'Gandhigramam' AS name UNION ALL
    SELECT 'Inam Karur' AS name UNION ALL
    SELECT 'Inungur' AS name UNION ALL
    SELECT 'K.Paramathi' AS name UNION ALL
    SELECT 'K.Paramathi Bus Stand Area' AS name UNION ALL
    SELECT 'K.Paramathi Road' AS name UNION ALL
    SELECT 'K.Paramathi Town' AS name UNION ALL
    SELECT 'Kadaparai' AS name UNION ALL
    SELECT 'Kadavur' AS name UNION ALL
    SELECT 'Kakkavadi' AS name UNION ALL
    SELECT 'Kamarajapuram' AS name UNION ALL
    SELECT 'Karuppampalayam' AS name UNION ALL
    SELECT 'Karur' AS name UNION ALL
    SELECT 'Karur Bus Stand Area' AS name UNION ALL
    SELECT 'Karur Bus Stand Commercial Area' AS name UNION ALL
    SELECT 'Karur Railway Station Area' AS name UNION ALL
    SELECT 'Karur Road' AS name UNION ALL
    SELECT 'Karur Textile Market Area' AS name UNION ALL
    SELECT 'Karur Town' AS name UNION ALL
    SELECT 'Karur-Erode Road' AS name UNION ALL
    SELECT 'Kodanthur' AS name UNION ALL
    SELECT 'Kovai Road' AS name UNION ALL
    SELECT 'Kovilur' AS name UNION ALL
    SELECT 'Koyampalli' AS name UNION ALL
    SELECT 'Krishnarayapuram' AS name UNION ALL
    SELECT 'Krishnarayapuram Bus Stand Area' AS name UNION ALL
    SELECT 'Krishnarayapuram Road' AS name UNION ALL
    SELECT 'Krishnarayapuram Town' AS name UNION ALL
    SELECT 'Kulithalai' AS name UNION ALL
    SELECT 'Kulithalai Bus Stand Area' AS name UNION ALL
    SELECT 'Kulithalai Main Road' AS name UNION ALL
    SELECT 'Kulithalai Railway Station Area' AS name UNION ALL
    SELECT 'Kulithalai Road' AS name UNION ALL
    SELECT 'Kulithalai Town' AS name UNION ALL
    SELECT 'Kuppam' AS name UNION ALL
    SELECT 'Kuppam Village' AS name UNION ALL
    SELECT 'Lalapet' AS name UNION ALL
    SELECT 'Lalapet Bus Stand' AS name UNION ALL
    SELECT 'Lalapet Railway Station Area' AS name UNION ALL
    SELECT 'MGR Nagar' AS name UNION ALL
    SELECT 'Madurai Road' AS name UNION ALL
    SELECT 'Mahadanapuram' AS name UNION ALL
    SELECT 'Manjanaickenpatti' AS name UNION ALL
    SELECT 'Manmangalam' AS name UNION ALL
    SELECT 'Marudur' AS name UNION ALL
    SELECT 'Mayanur' AS name UNION ALL
    SELECT 'Mayanur Dam Area' AS name UNION ALL
    SELECT 'Mayanur Railway Station Area' AS name UNION ALL
    SELECT 'Mayanur Town' AS name UNION ALL
    SELECT 'Moolimangalam' AS name UNION ALL
    SELECT 'Munnur' AS name UNION ALL
    SELECT 'Nadanthai' AS name UNION ALL
    SELECT 'Nadayanur' AS name UNION ALL
    SELECT 'Nangavaram' AS name UNION ALL
    SELECT 'Nanniyur' AS name UNION ALL
    SELECT 'Nehru Nagar' AS name UNION ALL
    SELECT 'Neidalur' AS name UNION ALL
    SELECT 'Pallapatti' AS name UNION ALL
    SELECT 'Pallapatti Bus Stand' AS name UNION ALL
    SELECT 'Pallapatti Main Road' AS name UNION ALL
    SELECT 'Pallapatti Road' AS name UNION ALL
    SELECT 'Pallapatti Town' AS name UNION ALL
    SELECT 'Pannapatti' AS name UNION ALL
    SELECT 'Pasupathipalayam' AS name UNION ALL
    SELECT 'Pasupathipalayam Main Road' AS name UNION ALL
    SELECT 'Pasupathipalayam Railway Area' AS name UNION ALL
    SELECT 'Pathiripatti' AS name UNION ALL
    SELECT 'Pugalur' AS name UNION ALL
    SELECT 'Pugalur Bus Stand' AS name UNION ALL
    SELECT 'Pugalur Railway Station Area' AS name UNION ALL
    SELECT 'Pugalur Town' AS name UNION ALL
    SELECT 'Puliyur' AS name UNION ALL
    SELECT 'Punjai Pugalur' AS name UNION ALL
    SELECT 'Punnam' AS name UNION ALL
    SELECT 'Railway Colony' AS name UNION ALL
    SELECT 'Railway Station Area' AS name UNION ALL
    SELECT 'Rajapuram' AS name UNION ALL
    SELECT 'Ramakrishnapuram' AS name UNION ALL
    SELECT 'Ramanujam Nagar' AS name UNION ALL
    SELECT 'Rayanoor' AS name UNION ALL
    SELECT 'Salem Road' AS name UNION ALL
    SELECT 'Sanapiratti' AS name UNION ALL
    SELECT 'Sellandipalayam' AS name UNION ALL
    SELECT 'Sengal' AS name UNION ALL
    SELECT 'Sengunthapuram' AS name UNION ALL
    SELECT 'Thanthoni' AS name UNION ALL
    SELECT 'Thanthonimalai' AS name UNION ALL
    SELECT 'Thanthonimalai Main Road' AS name UNION ALL
    SELECT 'Thanthonimalai Road' AS name UNION ALL
    SELECT 'Thanthonimalai Temple Area' AS name UNION ALL
    SELECT 'Thirumanilaiyur' AS name UNION ALL
    SELECT 'Thogamalai' AS name UNION ALL
    SELECT 'Thogamalai Bus Stand' AS name UNION ALL
    SELECT 'Thogamalai Town' AS name UNION ALL
    SELECT 'Trichy Road' AS name UNION ALL
    SELECT 'Uppidamangalam' AS name UNION ALL
    SELECT 'Vadivel Nagar' AS name UNION ALL
    SELECT 'Vaiganallur' AS name UNION ALL
    SELECT 'Vaiyapuri Nagar' AS name UNION ALL
    SELECT 'Velayuthampalayam' AS name UNION ALL
    SELECT 'Velayuthampalayam Bus Stand' AS name UNION ALL
    SELECT 'Velayuthampalayam Industrial Area' AS name UNION ALL
    SELECT 'Velayuthampalayam Main Road' AS name UNION ALL
    SELECT 'Velayuthampalayam Town' AS name UNION ALL
    SELECT 'Vengamedu' AS name UNION ALL
    SELECT 'Vengamedu Main Road' AS name UNION ALL
    SELECT 'Veppangudi' AS name
) AS loc
JOIN `districts` d ON d.name = 'Karur'
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
