-- ==============================================================================
-- NAMAKKAL DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safe for live production database
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'Agaya Gangai Falls Area' AS name UNION ALL
    SELECT 'Alampalayam' AS name UNION ALL
    SELECT 'Amman Nagar' AS name UNION ALL
    SELECT 'Anaikattipalayam' AS name UNION ALL
    SELECT 'Andagalur Gate' AS name UNION ALL
    SELECT 'Andipalaiyam' AS name UNION ALL
    SELECT 'Aniyapuram' AS name UNION ALL
    SELECT 'Anna Nagar' AS name UNION ALL
    SELECT 'Arapaleeswarar Temple Area' AS name UNION ALL
    SELECT 'Ardhanareeswarar Temple Area' AS name UNION ALL
    SELECT 'Ariyur' AS name UNION ALL
    SELECT 'Ariyur Nadu' AS name UNION ALL
    SELECT 'Attur Road' AS name UNION ALL
    SELECT 'Ayilpatti' AS name UNION ALL
    SELECT 'Belukurichi' AS name UNION ALL
    SELECT 'Bhavani Road' AS name UNION ALL
    SELECT 'Bodinaickenpatti' AS name UNION ALL
    SELECT 'Cauvery Nagar' AS name UNION ALL
    SELECT 'Cauvery River Area' AS name UNION ALL
    SELECT 'Cauvery River Bank Area' AS name UNION ALL
    SELECT 'Devanankurichi' AS name UNION ALL
    SELECT 'Devarigiri' AS name UNION ALL
    SELECT 'Elachipalayam' AS name UNION ALL
    SELECT 'Elachipalayam Main Road' AS name UNION ALL
    SELECT 'Elachipalayam Town' AS name UNION ALL
    SELECT 'Ernapuram' AS name UNION ALL
    SELECT 'Erode Road' AS name UNION ALL
    SELECT 'Erumapatty' AS name UNION ALL
    SELECT 'Fort Road' AS name UNION ALL
    SELECT 'Gandhi Nagar' AS name UNION ALL
    SELECT 'Gundur Nadu' AS name UNION ALL
    SELECT 'Housing Board Colony' AS name UNION ALL
    SELECT 'Jedarpalayam' AS name UNION ALL
    SELECT 'Jedarpalayam Dam Area' AS name UNION ALL
    SELECT 'Jedarpalayam Industrial Area' AS name UNION ALL
    SELECT 'Jedarpalayam Road' AS name UNION ALL
    SELECT 'Jedarpalayam Town' AS name UNION ALL
    SELECT 'Kabilarmalai' AS name UNION ALL
    SELECT 'Kabilarmalai Main Road' AS name UNION ALL
    SELECT 'Kabilarmalai Road' AS name UNION ALL
    SELECT 'Kabilarmalai Town' AS name UNION ALL
    SELECT 'Kakkaveri' AS name UNION ALL
    SELECT 'Kalangani' AS name UNION ALL
    SELECT 'Kalappanaickenpatti' AS name UNION ALL
    SELECT 'Kallipalayam' AS name UNION ALL
    SELECT 'Kamaraj Nagar' AS name UNION ALL
    SELECT 'Karur Road' AS name UNION ALL
    SELECT 'Karuveppampatti' AS name UNION ALL
    SELECT 'Kattur' AS name UNION ALL
    SELECT 'Keerambur' AS name UNION ALL
    SELECT 'Kokkaraianpettai' AS name UNION ALL
    SELECT 'Kokkarayanpettai' AS name UNION ALL
    SELECT 'Kolli Hills' AS name UNION ALL
    SELECT 'Kolli Hills Road' AS name UNION ALL
    SELECT 'Kollimalai' AS name UNION ALL
    SELECT 'Komarapalayam' AS name UNION ALL
    SELECT 'Kondichettipatti' AS name UNION ALL
    SELECT 'Kosavampatti' AS name UNION ALL
    SELECT 'Kothamangalam' AS name UNION ALL
    SELECT 'Kottai' AS name UNION ALL
    SELECT 'Kottai Medu' AS name UNION ALL
    SELECT 'Kumarapalayam' AS name UNION ALL
    SELECT 'Kumarapalayam Bus Stand' AS name UNION ALL
    SELECT 'Kumarapalayam Main Road' AS name UNION ALL
    SELECT 'Kumarapalayam Road' AS name UNION ALL
    SELECT 'Kumarapalayam Textile Area' AS name UNION ALL
    SELECT 'Kumarapalayam Town' AS name UNION ALL
    SELECT 'Kuppandapalayam' AS name UNION ALL
    SELECT 'MGR Nagar' AS name UNION ALL
    SELECT 'Mallasamudram' AS name UNION ALL
    SELECT 'Mallasamudram Bus Stand' AS name UNION ALL
    SELECT 'Mallasamudram Road' AS name UNION ALL
    SELECT 'Mallasamudram Town' AS name UNION ALL
    SELECT 'Marur' AS name UNION ALL
    SELECT 'Mettupatti' AS name UNION ALL
    SELECT 'Mohanur' AS name UNION ALL
    SELECT 'Mohanur Bus Stand' AS name UNION ALL
    SELECT 'Mohanur Commercial Area' AS name UNION ALL
    SELECT 'Mohanur Main Road' AS name UNION ALL
    SELECT 'Mohanur Road' AS name UNION ALL
    SELECT 'Mohanur Town' AS name UNION ALL
    SELECT 'Molipalli' AS name UNION ALL
    SELECT 'Mudalaipatti' AS name UNION ALL
    SELECT 'Muthugapatti' AS name UNION ALL
    SELECT 'Nadu Kombai' AS name UNION ALL
    SELECT 'Nainamalai' AS name UNION ALL
    SELECT 'Nallipalayam' AS name UNION ALL
    SELECT 'Namakkal' AS name UNION ALL
    SELECT 'Namakkal Bus Stand Area' AS name UNION ALL
    SELECT 'Namakkal Bus Stand Commercial Area' AS name UNION ALL
    SELECT 'Namakkal Fort Area' AS name UNION ALL
    SELECT 'Namakkal Lorry Body Building Area' AS name UNION ALL
    SELECT 'Namakkal New Bus Stand' AS name UNION ALL
    SELECT 'Namakkal Railway Station Area' AS name UNION ALL
    SELECT 'Namakkal Town' AS name UNION ALL
    SELECT 'Namakkal Truck Market Area' AS name UNION ALL
    SELECT 'Namakkal-Salem Road' AS name UNION ALL
    SELECT 'Namakkal-Tiruchengode Road' AS name UNION ALL
    SELECT 'Namakkal-Trichy Road' AS name UNION ALL
    SELECT 'Oruvandur' AS name UNION ALL
    SELECT 'Pachal' AS name UNION ALL
    SELECT 'Palapatti' AS name UNION ALL
    SELECT 'Pallipalayam' AS name UNION ALL
    SELECT 'Pallipalayam Agraharam' AS name UNION ALL
    SELECT 'Pallipalayam Bus Stand' AS name UNION ALL
    SELECT 'Pallipalayam Main Road' AS name UNION ALL
    SELECT 'Pallipalayam Textile Area' AS name UNION ALL
    SELECT 'Pallipalayam Town' AS name UNION ALL
    SELECT 'Pandamangalam' AS name UNION ALL
    SELECT 'Paramathi' AS name UNION ALL
    SELECT 'Paramathi Road' AS name UNION ALL
    SELECT 'Paramathi Town' AS name UNION ALL
    SELECT 'Paramathi Velur' AS name UNION ALL
    SELECT 'Paramathi Velur Bus Stand' AS name UNION ALL
    SELECT 'Paramathi Velur Commercial Area' AS name UNION ALL
    SELECT 'Paramathi Velur Road' AS name UNION ALL
    SELECT 'Pattanam' AS name UNION ALL
    SELECT 'Pattinam' AS name UNION ALL
    SELECT 'Periyakovilur' AS name UNION ALL
    SELECT 'Periyapatti' AS name UNION ALL
    SELECT 'Pettapalayam' AS name UNION ALL
    SELECT 'Pilikkalpalayam' AS name UNION ALL
    SELECT 'Pillaikalathur' AS name UNION ALL
    SELECT 'Pillanallur' AS name UNION ALL
    SELECT 'Ponnagaram' AS name UNION ALL
    SELECT 'Ponneri' AS name UNION ALL
    SELECT 'Pothanur' AS name UNION ALL
    SELECT 'Pothanur Main Road' AS name UNION ALL
    SELECT 'Pothanur Town' AS name UNION ALL
    SELECT 'Puduchatram' AS name UNION ALL
    SELECT 'Puliancholai Road' AS name UNION ALL
    SELECT 'Puthupalayam' AS name UNION ALL
    SELECT 'Puthuvalavu' AS name UNION ALL
    SELECT 'R. Pudupatti' AS name UNION ALL
    SELECT 'Ramapuram Pudur' AS name UNION ALL
    SELECT 'Rasampalayam' AS name UNION ALL
    SELECT 'Rasipuram' AS name UNION ALL
    SELECT 'Rasipuram Bus Stand' AS name UNION ALL
    SELECT 'Rasipuram Commercial Area' AS name UNION ALL
    SELECT 'Rasipuram Main Road' AS name UNION ALL
    SELECT 'Rasipuram Railway Station Area' AS name UNION ALL
    SELECT 'Rasipuram Road' AS name UNION ALL
    SELECT 'Rasipuram Town' AS name UNION ALL
    SELECT 'Reddipatti' AS name UNION ALL
    SELECT 'Salem Road' AS name UNION ALL
    SELECT 'Sankari Road' AS name UNION ALL
    SELECT 'Seekuparai' AS name UNION ALL
    SELECT 'Seetharampalayam' AS name UNION ALL
    SELECT 'Selur Nadu' AS name UNION ALL
    SELECT 'Semmedu' AS name UNION ALL
    SELECT 'Sendamangalam' AS name UNION ALL
    SELECT 'Sendamangalam Bus Stand' AS name UNION ALL
    SELECT 'Sendamangalam Main Road' AS name UNION ALL
    SELECT 'Sendamangalam Road' AS name UNION ALL
    SELECT 'Sendamangalam Town' AS name UNION ALL
    SELECT 'Solakkadu' AS name UNION ALL
    SELECT 'Solasiramani' AS name UNION ALL
    SELECT 'Teachers Colony' AS name UNION ALL
    SELECT 'Textile Market Area' AS name UNION ALL
    SELECT 'Thalambadi' AS name UNION ALL
    SELECT 'Thillaipuram' AS name UNION ALL
    SELECT 'Thirumalaipatti' AS name UNION ALL
    SELECT 'Tiruchengode' AS name UNION ALL
    SELECT 'Tiruchengode Bus Stand' AS name UNION ALL
    SELECT 'Tiruchengode Hill Area' AS name UNION ALL
    SELECT 'Tiruchengode Industrial Area' AS name UNION ALL
    SELECT 'Tiruchengode Main Road' AS name UNION ALL
    SELECT 'Tiruchengode Railway Station Area' AS name UNION ALL
    SELECT 'Tiruchengode Road' AS name UNION ALL
    SELECT 'Tiruchengode Town' AS name UNION ALL
    SELECT 'Trichy Road' AS name UNION ALL
    SELECT 'Vadugam' AS name UNION ALL
    SELECT 'Valappur Nadu' AS name UNION ALL
    SELECT 'Valayapatti' AS name UNION ALL
    SELECT 'Valvil Oori Nagar' AS name UNION ALL
    SELECT 'Vasalur Nadu' AS name UNION ALL
    SELECT 'Vasanthapuram' AS name UNION ALL
    SELECT 'Velur' AS name UNION ALL
    SELECT 'Velur Road' AS name UNION ALL
    SELECT 'Velur Town' AS name UNION ALL
    SELECT 'Vennaimalai' AS name UNION ALL
    SELECT 'Vennandur' AS name UNION ALL
    SELECT 'Vennandur Bus Stand' AS name UNION ALL
    SELECT 'Vennandur Town' AS name UNION ALL
    SELECT 'Vettambadi' AS name
) AS loc
JOIN `districts` d ON d.name = 'Namakkal'
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
