-- ==============================================================================
-- Puducherry Union Territory Localities Database Import
-- Total Districts: 4
-- Total Localities: 277
-- ==============================================================================

-- Ensure Puducherry state exists
INSERT INTO states (name, code)
SELECT 'Puducherry', 'PY'
WHERE NOT EXISTS (
    SELECT 1 FROM states WHERE code = 'PY' OR name = 'Puducherry'
);

-- Get State ID variable
SET @state_id = (SELECT id FROM states WHERE code = 'PY' OR name = 'Puducherry' LIMIT 1);

-- Insert Districts
INSERT INTO districts (state_id, name)
SELECT @state_id, 'Puducherry' UNION ALL
SELECT @state_id, 'Karaikal' UNION ALL
SELECT @state_id, 'Mahe' UNION ALL
SELECT @state_id, 'Yanam'
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insert Localities District-by-District
-- District: Puducherry (178 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Puducherry' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Muthialpet' AS name UNION ALL
    SELECT 'Muthialpet East' AS name UNION ALL
    SELECT 'Muthialpet West' AS name UNION ALL
    SELECT 'Solai Nagar' AS name UNION ALL
    SELECT 'Vaithikuppam' AS name UNION ALL
    SELECT 'V.O.C. Nagar' AS name UNION ALL
    SELECT 'Thiruvalluvar Nagar' AS name UNION ALL
    SELECT 'Perumal Koil area' AS name UNION ALL
    SELECT 'Perumal Koil' AS name UNION ALL
    SELECT 'Kurusukuppam' AS name UNION ALL
    SELECT 'Raj Bhavan area' AS name UNION ALL
    SELECT 'Raj Bhavan' AS name UNION ALL
    SELECT 'Cathedral area' AS name UNION ALL
    SELECT 'Cathedral' AS name UNION ALL
    SELECT 'Goubert Nagar' AS name UNION ALL
    SELECT 'Elango Nagar' AS name UNION ALL
    SELECT 'Pudupalayam' AS name UNION ALL
    SELECT 'Pillaithottam' AS name UNION ALL
    SELECT 'Sakthi Nagar' AS name UNION ALL
    SELECT 'Anna Nagar' AS name UNION ALL
    SELECT 'Nellithope' AS name UNION ALL
    SELECT 'Orleanpet' AS name UNION ALL
    SELECT 'Vanarapet' AS name UNION ALL
    SELECT 'Periapalli' AS name UNION ALL
    SELECT 'Vambakeerapalayam' AS name UNION ALL
    SELECT 'Colas Nagar' AS name UNION ALL
    SELECT 'Nethaji Nagar' AS name UNION ALL
    SELECT 'Viduthalai Nagar' AS name UNION ALL
    SELECT 'Mudaliarpet' AS name UNION ALL
    SELECT 'Bharathidasan Nagar' AS name UNION ALL
    SELECT 'Ozhandai Keerapalayam' AS name UNION ALL
    SELECT 'Ozhandai Keerapalayam East' AS name UNION ALL
    SELECT 'Ozhandai Keerapalayam West' AS name UNION ALL
    SELECT 'Nainar Mandapam' AS name UNION ALL
    SELECT 'Thengaithittu' AS name UNION ALL
    SELECT 'Murungapakkam' AS name UNION ALL
    SELECT 'Murungapakkam East' AS name UNION ALL
    SELECT 'Murungapakkam West' AS name UNION ALL
    SELECT 'Kombakkam' AS name UNION ALL
    SELECT 'Alankuppam' AS name UNION ALL
    SELECT 'Ganapathichettikulam' AS name UNION ALL
    SELECT 'Kalapet' AS name UNION ALL
    SELECT 'Pillaichavady' AS name UNION ALL
    SELECT 'Samipillaithottam' AS name UNION ALL
    SELECT 'Karuvadikuppam' AS name UNION ALL
    SELECT 'Pethuchettypet' AS name UNION ALL
    SELECT 'Santhi Nagar' AS name UNION ALL
    SELECT 'Navarkulam' AS name UNION ALL
    SELECT 'Lawspet' AS name UNION ALL
    SELECT 'Krishna Nagar' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Rainbow Nagar' AS name UNION ALL
    SELECT 'Kamaraj Nagar' AS name UNION ALL
    SELECT 'Brindhavanam' AS name UNION ALL
    SELECT 'Saram' AS name UNION ALL
    SELECT 'Vinoba Nagar' AS name UNION ALL
    SELECT 'Ashok Nagar' AS name UNION ALL
    SELECT 'Rajaji Nagar' AS name UNION ALL
    SELECT 'Kumaran Nagar' AS name UNION ALL
    SELECT 'Kurinji Nagar' AS name UNION ALL
    SELECT 'Pakkamudayanpet' AS name UNION ALL
    SELECT 'Thattanchavady' AS name UNION ALL
    SELECT 'Thilaspet' AS name UNION ALL
    SELECT 'Coundanpalayam' AS name UNION ALL
    SELECT 'Kathirkamam' AS name UNION ALL
    SELECT 'Veemacoundanpalayam' AS name UNION ALL
    SELECT 'Indira Nagar' AS name UNION ALL
    SELECT 'V.P. Singh Nagar' AS name UNION ALL
    SELECT 'Meenatchipet' AS name UNION ALL
    SELECT 'Shanmugapuram' AS name UNION ALL
    SELECT 'Muthirapalayam' AS name UNION ALL
    SELECT 'Gandhithirunallur' AS name UNION ALL
    SELECT 'Dharmapuri' AS name UNION ALL
    SELECT 'Dr. Radhakrishnan Nagar' AS name UNION ALL
    SELECT 'Arumbathapuram' AS name UNION ALL
    SELECT 'Oulgaret North' AS name UNION ALL
    SELECT 'Oulgaret South' AS name UNION ALL
    SELECT 'Reddiyarpalayam' AS name UNION ALL
    SELECT 'Moogambigai Nagar' AS name UNION ALL
    SELECT 'Ellapillaichavady' AS name UNION ALL
    SELECT 'Jawahar Nagar' AS name UNION ALL
    SELECT 'Nadesan Nagar' AS name UNION ALL
    SELECT 'Ariankuppam West' AS name UNION ALL
    SELECT 'Ariankuppam East' AS name UNION ALL
    SELECT 'Kakayanthoppe' AS name UNION ALL
    SELECT 'Veerampattinam' AS name UNION ALL
    SELECT 'Odaively' AS name UNION ALL
    SELECT 'Manavely' AS name UNION ALL
    SELECT 'Nonankuppam' AS name UNION ALL
    SELECT 'Thavalakuppam' AS name UNION ALL
    SELECT 'Poornankuppam' AS name UNION ALL
    SELECT 'Nallavadu' AS name UNION ALL
    SELECT 'Andiyarpalayam' AS name UNION ALL
    SELECT 'Abishegapakkam' AS name UNION ALL
    SELECT 'TN Palayam' AS name UNION ALL
    SELECT 'Ariankuppam' AS name UNION ALL
    SELECT 'Ariankuppam Main Area' AS name UNION ALL
    SELECT 'Veerampattinam Beach Area' AS name UNION ALL
    SELECT 'Thavalakuppam Main Road' AS name UNION ALL
    SELECT 'Chunnambar vicinity' AS name UNION ALL
    SELECT 'Manavely North' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Manavely South' AS name UNION ALL
    SELECT 'Karayambuthur' AS name UNION ALL
    SELECT 'Manamedu' AS name UNION ALL
    SELECT 'Kuruvinatham' AS name UNION ALL
    SELECT 'Soriyankuppam' AS name UNION ALL
    SELECT 'Parikalpattu' AS name UNION ALL
    SELECT 'Bahour East' AS name UNION ALL
    SELECT 'Bahour West' AS name UNION ALL
    SELECT 'Seliamedu' AS name UNION ALL
    SELECT 'Kudiyiruppupalayam' AS name UNION ALL
    SELECT 'Kirumampakkam' AS name UNION ALL
    SELECT 'Pannithittu' AS name UNION ALL
    SELECT 'Pillayarkuppam' AS name UNION ALL
    SELECT 'Manapattu' AS name UNION ALL
    SELECT 'Mathikrishnapuram' AS name UNION ALL
    SELECT 'Bahour' AS name UNION ALL
    SELECT 'Bahour Main Area' AS name UNION ALL
    SELECT 'Karayampathur' AS name UNION ALL
    SELECT 'Parikkalpattu' AS name UNION ALL
    SELECT 'Chettipet' AS name UNION ALL
    SELECT 'Kunichempet' AS name UNION ALL
    SELECT 'Thirukkanur' AS name UNION ALL
    SELECT 'Mannadipet' AS name UNION ALL
    SELECT 'Kodathur' AS name UNION ALL
    SELECT 'Suthukeny' AS name UNION ALL
    SELECT 'Sandaipudukuppam' AS name UNION ALL
    SELECT 'Katterikuppam' AS name UNION ALL
    SELECT 'Vadhanur' AS name UNION ALL
    SELECT 'P.S. Palayam' AS name UNION ALL
    SELECT 'Mannadipet Main Area' AS name UNION ALL
    SELECT 'Maducarai West' AS name UNION ALL
    SELECT 'Maducarai East' AS name UNION ALL
    SELECT 'Sooramangalam' AS name UNION ALL
    SELECT 'Kalmandabam' AS name UNION ALL
    SELECT 'Pandasolanallur' AS name UNION ALL
    SELECT 'Nettapakkam' AS name UNION ALL
    SELECT 'Embalam' AS name UNION ALL
    SELECT 'Sembiapalyam' AS name UNION ALL
    SELECT 'Nathamedu' AS name UNION ALL
    SELECT 'Karickalampakkam' AS name UNION ALL
    SELECT 'Korkadu' AS name UNION ALL
    SELECT 'Maducarai' AS name UNION ALL
    SELECT 'Sedarapet' AS name UNION ALL
    SELECT 'Thodamanitham' AS name UNION ALL
    SELECT 'Pillaiyarkuppam' AS name UNION ALL
    SELECT 'Koodapakkam' AS name UNION ALL
    SELECT 'Poraiyur Agaram' AS name UNION ALL
    SELECT 'Villianur West' AS name UNION ALL
    SELECT 'Villianur Central' AS name UNION ALL
    SELECT 'Villianur East' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Kotaimedu' AS name UNION ALL
    SELECT 'Sulthanpet South' AS name UNION ALL
    SELECT 'Sulthanpet North' AS name UNION ALL
    SELECT 'Kurumbapet' AS name UNION ALL
    SELECT 'Odiampet East' AS name UNION ALL
    SELECT 'Odiampet West' AS name UNION ALL
    SELECT 'Kanuvapet' AS name UNION ALL
    SELECT 'Uruvaiyar' AS name UNION ALL
    SELECT 'Thirukanji' AS name UNION ALL
    SELECT 'Mangalam' AS name UNION ALL
    SELECT 'Sathamangalam' AS name UNION ALL
    SELECT 'Sivarathagam' AS name UNION ALL
    SELECT 'Ariyur South' AS name UNION ALL
    SELECT 'Ariyur North' AS name UNION ALL
    SELECT 'Villianur' AS name UNION ALL
    SELECT 'Ariyur' AS name UNION ALL
    SELECT 'Odiampet' AS name UNION ALL
    SELECT 'Sulthanpet' AS name UNION ALL
    SELECT 'Puducherry Town' AS name UNION ALL
    SELECT 'White Town (French Quarter)' AS name UNION ALL
    SELECT 'Heritage Town' AS name UNION ALL
    SELECT 'Oulgaret' AS name UNION ALL
    SELECT 'Ariyankuppam' AS name UNION ALL
    SELECT 'Reddiarpalayam' AS name UNION ALL
    SELECT 'Gorimedu Puducherry' AS name UNION ALL
    SELECT 'Kadirgamam' AS name UNION ALL
    SELECT 'Chunnambar Area' AS name UNION ALL
    SELECT 'Sedurapet' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

-- District: Karaikal (49 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Karaikal' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Keezhakasakudy' AS name UNION ALL
    SELECT 'Thalatheru' AS name UNION ALL
    SELECT 'Ammankoilpathu' AS name UNION ALL
    SELECT 'Koilpathu' AS name UNION ALL
    SELECT 'Nehru Nagar' AS name UNION ALL
    SELECT 'Puliankottai Salai' AS name UNION ALL
    SELECT 'Oppilamaniyar Koil' AS name UNION ALL
    SELECT 'Valatheru' AS name UNION ALL
    SELECT 'Maideenpalli' AS name UNION ALL
    SELECT 'Ammaiyarkoil' AS name UNION ALL
    SELECT 'Kadarsulthan' AS name UNION ALL
    SELECT 'Kothukulam' AS name UNION ALL
    SELECT 'Anthoniyar Koil' AS name UNION ALL
    SELECT 'Madhkadi' AS name UNION ALL
    SELECT 'Dharmapuram' AS name UNION ALL
    SELECT 'Oduthurai' AS name UNION ALL
    SELECT 'Akkaraivattam' AS name UNION ALL
    SELECT 'Karaikal Town' AS name UNION ALL
    SELECT 'Kottucherry' AS name UNION ALL
    SELECT 'Kottucherry North' AS name UNION ALL
    SELECT 'Kottucherry Main Area' AS name UNION ALL
    SELECT 'Nedungadu' AS name UNION ALL
    SELECT 'Nedungadu Main Area' AS name UNION ALL
    SELECT 'Kurumbakaram' AS name UNION ALL
    SELECT 'Ponbethy' AS name UNION ALL
    SELECT 'Melakasakudy' AS name UNION ALL
    SELECT 'Neravy' AS name UNION ALL
    SELECT 'Neravy North' AS name UNION ALL
    SELECT 'Neravy South' AS name UNION ALL
    SELECT 'Keezhamanai' AS name UNION ALL
    SELECT 'Vizhidiyur' AS name UNION ALL
    SELECT 'T.R. Pattinam' AS name UNION ALL
    SELECT 'T.R. Pattinam Central' AS name UNION ALL
    SELECT 'T.R. Pattinam East' AS name UNION ALL
    SELECT 'T.R. Pattinam North' AS name UNION ALL
    SELECT 'T.R. Pattinam South' AS name UNION ALL
    SELECT 'T.R. Pattinam Vanjore' AS name UNION ALL
    SELECT 'Vanjore' AS name UNION ALL
    SELECT 'Thirunallar' AS name UNION ALL
    SELECT 'Thirunallar North' AS name UNION ALL
    SELECT 'Thirunallar South' AS name UNION ALL
    SELECT 'Thirunallar Main Area' AS name UNION ALL
    SELECT 'Ambagarathur' AS name UNION ALL
    SELECT 'Nallambal' AS name UNION ALL
    SELECT 'Sethur' AS name UNION ALL
    SELECT 'Sellur' AS name UNION ALL
    SELECT 'Karukankudy' AS name UNION ALL
    SELECT 'Surakudy' AS name UNION ALL
    SELECT 'Pettai' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

-- District: Mahe (24 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Mahe' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Mahe' AS name UNION ALL
    SELECT 'Mahe North' AS name UNION ALL
    SELECT 'Mahe West' AS name UNION ALL
    SELECT 'Mahe South' AS name UNION ALL
    SELECT 'Chalakkara' AS name UNION ALL
    SELECT 'Chalakkara North' AS name UNION ALL
    SELECT 'Chalakkara South' AS name UNION ALL
    SELECT 'Chalakkara Main Area' AS name UNION ALL
    SELECT 'Palloor' AS name UNION ALL
    SELECT 'Palloor North' AS name UNION ALL
    SELECT 'Palloor South' AS name UNION ALL
    SELECT 'Palloor West' AS name UNION ALL
    SELECT 'Palloor Main Area' AS name UNION ALL
    SELECT 'Pandakkal' AS name UNION ALL
    SELECT 'Pandakkal North' AS name UNION ALL
    SELECT 'Pandakkal South' AS name UNION ALL
    SELECT 'Pandakkal Main Area' AS name UNION ALL
    SELECT 'Mahe Town' AS name UNION ALL
    SELECT 'Mahe Bazaar' AS name UNION ALL
    SELECT 'Mahe Fort Area' AS name UNION ALL
    SELECT 'Mahe Beach Area' AS name UNION ALL
    SELECT 'Mahe Riverfront' AS name UNION ALL
    SELECT 'Mahe Main Road' AS name UNION ALL
    SELECT 'Mahe Railway Area' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

-- District: Yanam (26 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Yanam' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Kanakalapeta' AS name UNION ALL
    SELECT 'Bheem Nagar' AS name UNION ALL
    SELECT 'Mettakuru North' AS name UNION ALL
    SELECT 'Mettakuru South' AS name UNION ALL
    SELECT 'Ambedkar Nagar' AS name UNION ALL
    SELECT 'Pillaraya' AS name UNION ALL
    SELECT 'Agraharam' AS name UNION ALL
    SELECT 'Pedapudi' AS name UNION ALL
    SELECT 'Pydikondal' AS name UNION ALL
    SELECT 'Kurusampeta' AS name UNION ALL
    SELECT 'Farampeta' AS name UNION ALL
    SELECT 'Dariyalathippa' AS name UNION ALL
    SELECT 'Guiriampet' AS name UNION ALL
    SELECT 'Savithri Nagar' AS name UNION ALL
    SELECT 'Yanam' AS name UNION ALL
    SELECT 'Yanam Town' AS name UNION ALL
    SELECT 'Old Yanam' AS name UNION ALL
    SELECT 'Main Road' AS name UNION ALL
    SELECT 'Market Area' AS name UNION ALL
    SELECT 'Yanam Riverside' AS name UNION ALL
    SELECT 'Ferry Road' AS name UNION ALL
    SELECT 'Mettakuru' AS name UNION ALL
    SELECT 'Kanikalapeta' AS name UNION ALL
    SELECT 'Mettakur' AS name UNION ALL
    SELECT 'Dariyalthippa' AS name UNION ALL
    SELECT 'Guerempeta' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

