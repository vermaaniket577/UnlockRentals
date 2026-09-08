-- ==============================================================================
-- KANYAKUMARI / KANNIYAKUMARI DISTRICT LOCALITIES INSERTION QUERY
-- Compatible with MySQL 5.7+, MariaDB, SQLite, and phpMyAdmin (cPanel)
-- Avoids duplicate entries & safely matches 'Kanniyakumari' or 'Kanyakumari'
-- ==============================================================================

INSERT INTO `localities` (`district_id`, `name`)
SELECT d.id, loc.name
FROM (
    SELECT 'Agastheeswaram' AS name UNION ALL
    SELECT 'Aloor' AS name UNION ALL
    SELECT 'Aralvaimozhi' AS name UNION ALL
    SELECT 'Aralvaimozhi Pass' AS name UNION ALL
    SELECT 'Aralvaimozhi Railway Station Area' AS name UNION ALL
    SELECT 'Aralvaimozhi Road' AS name UNION ALL
    SELECT 'Aralvaimozhi Town' AS name UNION ALL
    SELECT 'Arumanai' AS name UNION ALL
    SELECT 'Arumanai Bus Stand' AS name UNION ALL
    SELECT 'Arumanai Main Road' AS name UNION ALL
    SELECT 'Arumanai Town' AS name UNION ALL
    SELECT 'Arumanallur' AS name UNION ALL
    SELECT 'Asaripallam' AS name UNION ALL
    SELECT 'Ayacode' AS name UNION ALL
    SELECT 'Azhagappapuram' AS name UNION ALL
    SELECT 'Balaramapuram' AS name UNION ALL
    SELECT 'Beach Road' AS name UNION ALL
    SELECT 'Boothapandi' AS name UNION ALL
    SELECT 'Boothapandi Bus Stand' AS name UNION ALL
    SELECT 'Boothapandi Town' AS name UNION ALL
    SELECT 'Charode' AS name UNION ALL
    SELECT 'Chenbagaramanputhur' AS name UNION ALL
    SELECT 'Cheruvaloor' AS name UNION ALL
    SELECT 'Chettikulam' AS name UNION ALL
    SELECT 'Chinna Muttom' AS name UNION ALL
    SELECT 'Chinnamuttom' AS name UNION ALL
    SELECT 'Chinnathurai' AS name UNION ALL
    SELECT 'Chinnavilai' AS name UNION ALL
    SELECT 'Chitharal' AS name UNION ALL
    SELECT 'Chitharal Road' AS name UNION ALL
    SELECT 'Colachel' AS name UNION ALL
    SELECT 'Colachel Beach Area' AS name UNION ALL
    SELECT 'Colachel Bus Stand' AS name UNION ALL
    SELECT 'Colachel Port Area' AS name UNION ALL
    SELECT 'Colachel Town' AS name UNION ALL
    SELECT 'Court Road' AS name UNION ALL
    SELECT 'Derisanamcope' AS name UNION ALL
    SELECT 'East Car Street' AS name UNION ALL
    SELECT 'Eathamozhi' AS name UNION ALL
    SELECT 'Edaicode' AS name UNION ALL
    SELECT 'Edalakudi' AS name UNION ALL
    SELECT 'Enayam' AS name UNION ALL
    SELECT 'Erachakulam' AS name UNION ALL
    SELECT 'Eraniel' AS name UNION ALL
    SELECT 'Eraniel Railway Station Area' AS name UNION ALL
    SELECT 'Eraniel Road' AS name UNION ALL
    SELECT 'Eraviputhenthurai' AS name UNION ALL
    SELECT 'Esanthimangalam' AS name UNION ALL
    SELECT 'Ganapathipuram' AS name UNION ALL
    SELECT 'K.P. Road' AS name UNION ALL
    SELECT 'Kadayal' AS name UNION ALL
    SELECT 'Kadiyapattinam' AS name UNION ALL
    SELECT 'Kaliakkavilai' AS name UNION ALL
    SELECT 'Kaliakkavilai Bus Stand' AS name UNION ALL
    SELECT 'Kaliakkavilai Railway Station Area' AS name UNION ALL
    SELECT 'Kaliakkavilai Road' AS name UNION ALL
    SELECT 'Kaliakkavilai Town' AS name UNION ALL
    SELECT 'Kaliyakkavilai Road' AS name UNION ALL
    SELECT 'Kaliyal' AS name UNION ALL
    SELECT 'Kalkulam' AS name UNION ALL
    SELECT 'Kallankuzhi' AS name UNION ALL
    SELECT 'Kannanoor' AS name UNION ALL
    SELECT 'Kanniyakumari' AS name UNION ALL
    SELECT 'Kannumamoodu' AS name UNION ALL
    SELECT 'Kanyakumari' AS name UNION ALL
    SELECT 'Kanyakumari Beach Area' AS name UNION ALL
    SELECT 'Kanyakumari Bus Stand Area' AS name UNION ALL
    SELECT 'Kanyakumari Railway Station Area' AS name UNION ALL
    SELECT 'Kanyakumari Road' AS name UNION ALL
    SELECT 'Kanyakumari Town' AS name UNION ALL
    SELECT 'Kappukadu' AS name UNION ALL
    SELECT 'Karode' AS name UNION ALL
    SELECT 'Karungal' AS name UNION ALL
    SELECT 'Kattathurai' AS name UNION ALL
    SELECT 'Kaval Kinaru' AS name UNION ALL
    SELECT 'Keelamanakudy' AS name UNION ALL
    SELECT 'Killiyoor' AS name UNION ALL
    SELECT 'Kodayar' AS name UNION ALL
    SELECT 'Konam' AS name UNION ALL
    SELECT 'Kottar' AS name UNION ALL
    SELECT 'Kottar Road' AS name UNION ALL
    SELECT 'Kottaram' AS name UNION ALL
    SELECT 'Kovalam Road' AS name UNION ALL
    SELECT 'Krishnancoil' AS name UNION ALL
    SELECT 'Krishnankoil' AS name UNION ALL
    SELECT 'Kulasekaram' AS name UNION ALL
    SELECT 'Kulasekaram Bus Stand' AS name UNION ALL
    SELECT 'Kulasekaram Main Road' AS name UNION ALL
    SELECT 'Kulasekaram Road' AS name UNION ALL
    SELECT 'Kulasekaram Town' AS name UNION ALL
    SELECT 'Kulasekharam' AS name UNION ALL
    SELECT 'Kulithurai' AS name UNION ALL
    SELECT 'Kumarapuram' AS name UNION ALL
    SELECT 'Kunnathur' AS name UNION ALL
    SELECT 'Kuzhithurai' AS name UNION ALL
    SELECT 'Kuzhithurai East' AS name UNION ALL
    SELECT 'Kuzhithurai Road' AS name UNION ALL
    SELECT 'Kuzhithurai West' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Manali' AS name UNION ALL
    SELECT 'Manavalakurichi' AS name UNION ALL
    SELECT 'Manavalakurichi Beach Area' AS name UNION ALL
    SELECT 'Manavalakurichi Main Road' AS name UNION ALL
    SELECT 'Manavalakurichi Road' AS name UNION ALL
    SELECT 'Manavalakurichi Town' AS name UNION ALL
    SELECT 'Mandaikadu' AS name UNION ALL
    SELECT 'Mandaikadu Beach Area' AS name UNION ALL
    SELECT 'Mandaikadu Main Road' AS name UNION ALL
    SELECT 'Mandaikadu Road' AS name UNION ALL
    SELECT 'Mandaikadu Temple Area' AS name UNION ALL
    SELECT 'Mangad' AS name UNION ALL
    SELECT 'Mankuzhi' AS name UNION ALL
    SELECT 'Maravankudiyiruppu' AS name UNION ALL
    SELECT 'Marthandam' AS name UNION ALL
    SELECT 'Marthandam Bus Stand' AS name UNION ALL
    SELECT 'Marthandam Railway Station Area' AS name UNION ALL
    SELECT 'Marthandam Town' AS name UNION ALL
    SELECT 'Marthandanthurai' AS name UNION ALL
    SELECT 'Maruthancode' AS name UNION ALL
    SELECT 'Meenakshipuram' AS name UNION ALL
    SELECT 'Melamanakudy' AS name UNION ALL
    SELECT 'Melpuram' AS name UNION ALL
    SELECT 'Midalam' AS name UNION ALL
    SELECT 'Moolachel' AS name UNION ALL
    SELECT 'Munchirai' AS name UNION ALL
    SELECT 'Muppandal' AS name UNION ALL
    SELECT 'Muttom' AS name UNION ALL
    SELECT 'NGO Colony' AS name UNION ALL
    SELECT 'Nagercoil' AS name UNION ALL
    SELECT 'Nagercoil Road' AS name UNION ALL
    SELECT 'Nagercoil Town' AS name UNION ALL
    SELECT 'Nalloor' AS name UNION ALL
    SELECT 'Neerodi' AS name UNION ALL
    SELECT 'North Car Street' AS name UNION ALL
    SELECT 'Ozhuginasery' AS name UNION ALL
    SELECT 'Padmanabhapuram' AS name UNION ALL
    SELECT 'Padmanabhapuram Palace Area' AS name UNION ALL
    SELECT 'Pammam' AS name UNION ALL
    SELECT 'Panachamoodu' AS name UNION ALL
    SELECT 'Panagudi' AS name UNION ALL
    SELECT 'Panagudi Road' AS name UNION ALL
    SELECT 'Parakkai' AS name UNION ALL
    SELECT 'Parassala Road' AS name UNION ALL
    SELECT 'Parvathipuram' AS name UNION ALL
    SELECT 'Pechiparai' AS name UNION ALL
    SELECT 'Periya Muttom' AS name UNION ALL
    SELECT 'Periyavilai' AS name UNION ALL
    SELECT 'Peruvilai' AS name UNION ALL
    SELECT 'Ponmanai' AS name UNION ALL
    SELECT 'Poothurai' AS name UNION ALL
    SELECT 'Puliyoor Kurichi' AS name UNION ALL
    SELECT 'Punnai Nagar' AS name UNION ALL
    SELECT 'Puthalam' AS name UNION ALL
    SELECT 'Puthenthurai' AS name UNION ALL
    SELECT 'Puthery' AS name UNION ALL
    SELECT 'Rajakkamangalam' AS name UNION ALL
    SELECT 'Rajakkamangalamthurai' AS name UNION ALL
    SELECT 'Ramanputhur' AS name UNION ALL
    SELECT 'Sannathi Street' AS name UNION ALL
    SELECT 'Saraloor' AS name UNION ALL
    SELECT 'Siluvai Nagar' AS name UNION ALL
    SELECT 'Simon Colony' AS name UNION ALL
    SELECT 'South Car Street' AS name UNION ALL
    SELECT 'Suchindram' AS name UNION ALL
    SELECT 'Suchindram Road' AS name UNION ALL
    SELECT 'Suchindram Temple Area' AS name UNION ALL
    SELECT 'Suchindram Town' AS name UNION ALL
    SELECT 'Sunset Point Area' AS name UNION ALL
    SELECT 'Thalavaipuram' AS name UNION ALL
    SELECT 'Thamaraikulam' AS name UNION ALL
    SELECT 'Thazhakudy' AS name UNION ALL
    SELECT 'Thengapattanam' AS name UNION ALL
    SELECT 'Theroor' AS name UNION ALL
    SELECT 'Thiruvattar' AS name UNION ALL
    SELECT 'Thiruvattar Road' AS name UNION ALL
    SELECT 'Thiruvattar Temple Area' AS name UNION ALL
    SELECT 'Thiruvattar Town' AS name UNION ALL
    SELECT 'Thiruvithamcode' AS name UNION ALL
    SELECT 'Thoothoor' AS name UNION ALL
    SELECT 'Thovalai' AS name UNION ALL
    SELECT 'Thovalai Bus Stand' AS name UNION ALL
    SELECT 'Thovalai Flower Market Area' AS name UNION ALL
    SELECT 'Thovalai Road' AS name UNION ALL
    SELECT 'Thovalai Town' AS name UNION ALL
    SELECT 'Thuckalay' AS name UNION ALL
    SELECT 'Thuckalay Bus Stand' AS name UNION ALL
    SELECT 'Thuckalay Main Road' AS name UNION ALL
    SELECT 'Thuckalay Road' AS name UNION ALL
    SELECT 'Thuckalay Town' AS name UNION ALL
    SELECT 'Tirunelveli Road' AS name UNION ALL
    SELECT 'Trivandrum Road' AS name UNION ALL
    SELECT 'Vadasery' AS name UNION ALL
    SELECT 'Vadiveeswaram' AS name UNION ALL
    SELECT 'Vallavilai' AS name UNION ALL
    SELECT 'Vattakottai Road' AS name UNION ALL
    SELECT 'Vellamcode' AS name UNION ALL
    SELECT 'Vellamodi' AS name UNION ALL
    SELECT 'Vetturnimadam' AS name UNION ALL
    SELECT 'Vilavancode' AS name UNION ALL
    SELECT 'Vilavancode Road' AS name UNION ALL
    SELECT 'Vivekanandapuram' AS name UNION ALL
    SELECT 'Weavers Colony' AS name UNION ALL
    SELECT 'West Car Street' AS name
) AS loc
JOIN `districts` d ON d.name IN ('Kanniyakumari', 'Kanyakumari')
WHERE NOT EXISTS (
    SELECT 1 FROM `localities` l
    WHERE l.district_id = d.id AND LOWER(l.name) = LOWER(loc.name)
);
