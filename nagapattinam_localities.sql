-- ==============================================================================
-- NAGAPATTINAM DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safe for live production database
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'Agastheeswaram' AS name UNION ALL
    SELECT 'Akkaraipettai' AS name UNION ALL
    SELECT 'Akkaraipettai Road' AS name UNION ALL
    SELECT 'Anthanapettai' AS name UNION ALL
    SELECT 'Basilica Area' AS name UNION ALL
    SELECT 'Enangudi' AS name UNION ALL
    SELECT 'Enangudi Main Road' AS name UNION ALL
    SELECT 'Enangudi Town' AS name UNION ALL
    SELECT 'Ettukudi' AS name UNION ALL
    SELECT 'Kachanam' AS name UNION ALL
    SELECT 'Kadambadi' AS name UNION ALL
    SELECT 'Kadinevayal' AS name UNION ALL
    SELECT 'Kallar' AS name UNION ALL
    SELECT 'Kameswaram' AS name UNION ALL
    SELECT 'Kangalanchery' AS name UNION ALL
    SELECT 'Karaikal Road' AS name UNION ALL
    SELECT 'Karuvelangadai' AS name UNION ALL
    SELECT 'Keechankuppam' AS name UNION ALL
    SELECT 'Keelaiyur Road' AS name UNION ALL
    SELECT 'Keezhvelur' AS name UNION ALL
    SELECT 'Kilvelur' AS name UNION ALL
    SELECT 'Kilvelur Bus Stand' AS name UNION ALL
    SELECT 'Kilvelur Main Road' AS name UNION ALL
    SELECT 'Kilvelur Railway Station Area' AS name UNION ALL
    SELECT 'Kilvelur Road' AS name UNION ALL
    SELECT 'Kilvelur Town' AS name UNION ALL
    SELECT 'Kizhvelur' AS name UNION ALL
    SELECT 'Kizhvelur Road' AS name UNION ALL
    SELECT 'Kodiakarai' AS name UNION ALL
    SELECT 'Kodiakarai Beach' AS name UNION ALL
    SELECT 'Kodiakarai Wildlife Area' AS name UNION ALL
    SELECT 'Kodiakkarai (Point Calimere)' AS name UNION ALL
    SELECT 'Kodiyakkarai' AS name UNION ALL
    SELECT 'Kodiyakkarai Forest Area' AS name UNION ALL
    SELECT 'Kodiyakkarai Road' AS name UNION ALL
    SELECT 'Kottur' AS name UNION ALL
    SELECT 'Kuthalam Road' AS name UNION ALL
    SELECT 'Kuttalam' AS name UNION ALL
    SELECT 'Kuttalam Main Road' AS name UNION ALL
    SELECT 'Kuttalam Road Area' AS name UNION ALL
    SELECT 'Manakkudi' AS name UNION ALL
    SELECT 'Maruthur' AS name UNION ALL
    SELECT 'Meenambanallur' AS name UNION ALL
    SELECT 'Mutharasanallur' AS name UNION ALL
    SELECT 'Nagapattinam' AS name UNION ALL
    SELECT 'Nagapattinam Bus Stand Area' AS name UNION ALL
    SELECT 'Nagapattinam Port Area' AS name UNION ALL
    SELECT 'Nagapattinam Railway Station Area' AS name UNION ALL
    SELECT 'Nagapattinam Road' AS name UNION ALL
    SELECT 'Nagapattinam Town' AS name UNION ALL
    SELECT 'Nagore' AS name UNION ALL
    SELECT 'Nagore Beach Area' AS name UNION ALL
    SELECT 'Nagore Bus Stand' AS name UNION ALL
    SELECT 'Nagore Dargah Area' AS name UNION ALL
    SELECT 'Nagore Main Road' AS name UNION ALL
    SELECT 'Nagore Railway Station Area' AS name UNION ALL
    SELECT 'Nagore Road' AS name UNION ALL
    SELECT 'Nagore Town' AS name UNION ALL
    SELECT 'Nambiyar Nagar' AS name UNION ALL
    SELECT 'Nannilam Road' AS name UNION ALL
    SELECT 'Neela East Street' AS name UNION ALL
    SELECT 'Neela South Street' AS name UNION ALL
    SELECT 'Neela West Street' AS name UNION ALL
    SELECT 'New Beach Road' AS name UNION ALL
    SELECT 'Palpannai' AS name UNION ALL
    SELECT 'Pandaravadai' AS name UNION ALL
    SELECT 'Pappakovil' AS name UNION ALL
    SELECT 'Point Calimere' AS name UNION ALL
    SELECT 'Point Calimere Area' AS name UNION ALL
    SELECT 'Poravachery' AS name UNION ALL
    SELECT 'Public Office Road' AS name UNION ALL
    SELECT 'Pushpavanam' AS name UNION ALL
    SELECT 'Samanthanpettai' AS name UNION ALL
    SELECT 'Sellur' AS name UNION ALL
    SELECT 'Sikkal' AS name UNION ALL
    SELECT 'Sikkal Main Road' AS name UNION ALL
    SELECT 'Sikkal Road' AS name UNION ALL
    SELECT 'Sikkal Temple Area' AS name UNION ALL
    SELECT 'Sikkal Town' AS name UNION ALL
    SELECT 'Thagattur' AS name UNION ALL
    SELECT 'Thalaignayar' AS name UNION ALL
    SELECT 'Thalaignayar Main Road' AS name UNION ALL
    SELECT 'Thalaignayar Town' AS name UNION ALL
    SELECT 'Thalainayar' AS name UNION ALL
    SELECT 'Therkku Poigainallur' AS name UNION ALL
    SELECT 'Therku Poigainallur' AS name UNION ALL
    SELECT 'Therkupanaiyur' AS name UNION ALL
    SELECT 'Thevur' AS name UNION ALL
    SELECT 'Thirukkuvalai' AS name UNION ALL
    SELECT 'Thirukkuvalai Main Road' AS name UNION ALL
    SELECT 'Thirukkuvalai Road' AS name UNION ALL
    SELECT 'Thirukkuvalai Town' AS name UNION ALL
    SELECT 'Thirukuvalai' AS name UNION ALL
    SELECT 'Thirumarugal' AS name UNION ALL
    SELECT 'Thirumarugal Bus Stand' AS name UNION ALL
    SELECT 'Thirumarugal Main Road' AS name UNION ALL
    SELECT 'Thirumarugal Road' AS name UNION ALL
    SELECT 'Thirumarugal Town' AS name UNION ALL
    SELECT 'Thirupoondi' AS name UNION ALL
    SELECT 'Thiruppugalur' AS name UNION ALL
    SELECT 'Thiruvaimur' AS name UNION ALL
    SELECT 'Thittachery' AS name UNION ALL
    SELECT 'Thittachery Bus Stand' AS name UNION ALL
    SELECT 'Thittachery Main Road' AS name UNION ALL
    SELECT 'Thittachery Road' AS name UNION ALL
    SELECT 'Thittachery Town' AS name UNION ALL
    SELECT 'Vadakku Poigainallur' AS name UNION ALL
    SELECT 'Vadakkupanaiyur' AS name UNION ALL
    SELECT 'Valivalam' AS name UNION ALL
    SELECT 'Vedaranayam Beach Area' AS name UNION ALL
    SELECT 'Vedaranyam' AS name UNION ALL
    SELECT 'Vedaranyam Bus Stand' AS name UNION ALL
    SELECT 'Vedaranyam Main Road' AS name UNION ALL
    SELECT 'Vedaranyam Railway Area' AS name UNION ALL
    SELECT 'Vedaranyam Road' AS name UNION ALL
    SELECT 'Vedaranyam Salt Area' AS name UNION ALL
    SELECT 'Vedaranyam Town' AS name UNION ALL
    SELECT 'Velankanni' AS name UNION ALL
    SELECT 'Velankanni Beach Area' AS name UNION ALL
    SELECT 'Velankanni Bus Stand' AS name UNION ALL
    SELECT 'Velankanni Church Area' AS name UNION ALL
    SELECT 'Velankanni East' AS name UNION ALL
    SELECT 'Velankanni Main Road' AS name UNION ALL
    SELECT 'Velankanni Road' AS name UNION ALL
    SELECT 'Velankanni Town' AS name UNION ALL
    SELECT 'Velankanni West' AS name UNION ALL
    SELECT 'Velipalayam' AS name UNION ALL
    SELECT 'Vellankanni Railway Station Area' AS name UNION ALL
    SELECT 'Vellapallam' AS name UNION ALL
    SELECT 'Vettar River Area' AS name UNION ALL
    SELECT 'Voimedu' AS name
) AS loc
JOIN `districts` d ON d.name = 'Nagapattinam'
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
