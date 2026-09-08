-- ==============================================================================
-- Dadra and Nagar Haveli and Daman and Diu Localities Database Import
-- Total Districts: 3
-- Total Localities: 301
-- ==============================================================================

-- Ensure Dadra and Nagar Haveli and Daman and Diu state exists
INSERT INTO states (name, code)
SELECT 'Dadra and Nagar Haveli and Daman and Diu', 'DN'
WHERE NOT EXISTS (
    SELECT 1 FROM states WHERE code = 'DN' OR name = 'Dadra and Nagar Haveli and Daman and Diu'
);

-- Get State ID variable
SET @state_id = (SELECT id FROM states WHERE code = 'DN' OR name = 'Dadra and Nagar Haveli and Daman and Diu' LIMIT 1);

-- Insert Districts
INSERT INTO districts (state_id, name)
SELECT @state_id, 'Dadra and Nagar Haveli' UNION ALL
SELECT @state_id, 'Daman' UNION ALL
SELECT @state_id, 'Diu'
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insert Localities District-by-District
-- District: Dadra and Nagar Haveli (128 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Dadra and Nagar Haveli' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Silvassa' AS name UNION ALL
    SELECT 'Amli' AS name UNION ALL
    SELECT 'Piparia' AS name UNION ALL
    SELECT 'Samarvarni' AS name UNION ALL
    SELECT 'Athal' AS name UNION ALL
    SELECT 'Kherdi' AS name UNION ALL
    SELECT 'Naroli' AS name UNION ALL
    SELECT 'Rakholi' AS name UNION ALL
    SELECT 'Masat' AS name UNION ALL
    SELECT 'Dadra' AS name UNION ALL
    SELECT 'Karad' AS name UNION ALL
    SELECT 'Karajgam' AS name UNION ALL
    SELECT 'Dokmardi' AS name UNION ALL
    SELECT 'Dhapsa' AS name UNION ALL
    SELECT 'Saily' AS name UNION ALL
    SELECT 'Vasona' AS name UNION ALL
    SELECT 'Khanvel' AS name UNION ALL
    SELECT 'Velugam' AS name UNION ALL
    SELECT 'Amboli' AS name UNION ALL
    SELECT 'Kilvani' AS name UNION ALL
    SELECT 'Rudana' AS name UNION ALL
    SELECT 'Bindrabin' AS name UNION ALL
    SELECT 'Dudhani' AS name UNION ALL
    SELECT 'Surangi' AS name UNION ALL
    SELECT 'Mandoni' AS name UNION ALL
    SELECT 'Kherarbari' AS name UNION ALL
    SELECT 'Chauda' AS name UNION ALL
    SELECT 'Bedpa' AS name UNION ALL
    SELECT 'Kauncha' AS name UNION ALL
    SELECT 'Galonda' AS name UNION ALL
    SELECT 'Amli Industrial Area' AS name UNION ALL
    SELECT 'Amli Extension' AS name UNION ALL
    SELECT 'Piparia Industrial Area' AS name UNION ALL
    SELECT 'Silvassa Main Town' AS name UNION ALL
    SELECT 'Tokarkhada' AS name UNION ALL
    SELECT 'Masat Road' AS name UNION ALL
    SELECT 'Naroli Road' AS name UNION ALL
    SELECT 'Rakholi Road' AS name UNION ALL
    SELECT 'Dadra Road' AS name UNION ALL
    SELECT 'Khanvel Road' AS name UNION ALL
    SELECT 'Vapi-Silvassa Road' AS name UNION ALL
    SELECT 'Silvassa Bus Stand Area' AS name UNION ALL
    SELECT 'Silvassa Market Area' AS name UNION ALL
    SELECT 'Silvassa Railway/Transport Area' AS name UNION ALL
    SELECT 'Char Rasta' AS name UNION ALL
    SELECT 'Siliassa Town Area' AS name UNION ALL
    SELECT 'Amli Industrial Estate' AS name UNION ALL
    SELECT 'Amli Dockmardi' AS name UNION ALL
    SELECT 'Piparia-Amli' AS name UNION ALL
    SELECT 'Amli Main Road' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Amli Residential Area' AS name UNION ALL
    SELECT 'Piparia Main Road' AS name UNION ALL
    SELECT 'Piparia Residential Area' AS name UNION ALL
    SELECT 'Piparia-Silvassa Road' AS name UNION ALL
    SELECT 'Samarvarni Industrial Area' AS name UNION ALL
    SELECT 'Samarvarni Road' AS name UNION ALL
    SELECT 'Samarvarni Residential Area' AS name UNION ALL
    SELECT 'Silvassa-Samarvarni Road' AS name UNION ALL
    SELECT 'Athal Industrial Area' AS name UNION ALL
    SELECT 'Athal Main Road' AS name UNION ALL
    SELECT 'Athal Residential Area' AS name UNION ALL
    SELECT 'Athal-Silvassa Road' AS name UNION ALL
    SELECT 'Naroli Industrial Area' AS name UNION ALL
    SELECT 'Naroli Main Road' AS name UNION ALL
    SELECT 'Naroli-Silvassa Road' AS name UNION ALL
    SELECT 'Naroli Residential Area' AS name UNION ALL
    SELECT 'Naroli Check Post Area' AS name UNION ALL
    SELECT 'Rakholi Industrial Area' AS name UNION ALL
    SELECT 'Rakholi Industrial Estate' AS name UNION ALL
    SELECT 'Rakholi Main Road' AS name UNION ALL
    SELECT 'Rakholi-Silvassa Road' AS name UNION ALL
    SELECT 'Rakholi Residential Area' AS name UNION ALL
    SELECT 'Masat Industrial Area' AS name UNION ALL
    SELECT 'Masat Industrial Estate' AS name UNION ALL
    SELECT 'Masat Main Road' AS name UNION ALL
    SELECT 'Masat Residential Area' AS name UNION ALL
    SELECT 'Masat-Silvassa Road' AS name UNION ALL
    SELECT 'Dadra Industrial Area' AS name UNION ALL
    SELECT 'Dadra Industrial Estate' AS name UNION ALL
    SELECT 'Dadra Main Road' AS name UNION ALL
    SELECT 'Dadra Residential Area' AS name UNION ALL
    SELECT 'Dadra-Silvassa Road' AS name UNION ALL
    SELECT 'Dadra Nagar Area' AS name UNION ALL
    SELECT 'Karad Industrial Area' AS name UNION ALL
    SELECT 'Karad Main Road' AS name UNION ALL
    SELECT 'Karad Residential Area' AS name UNION ALL
    SELECT 'Karad-Silvassa Road' AS name UNION ALL
    SELECT 'Karajgam Industrial Area' AS name UNION ALL
    SELECT 'Karajgam Main Road' AS name UNION ALL
    SELECT 'Karajgam Residential Area' AS name UNION ALL
    SELECT 'Kherdi Industrial Area' AS name UNION ALL
    SELECT 'Kherdi Main Road' AS name UNION ALL
    SELECT 'Kherdi Residential Area' AS name UNION ALL
    SELECT 'Kherdi-Silvassa Road' AS name UNION ALL
    SELECT 'Khanvel Main Road' AS name UNION ALL
    SELECT 'Khanvel Market Area' AS name UNION ALL
    SELECT 'Khanvel Residential Area' AS name UNION ALL
    SELECT 'Khanvel Tribal Area' AS name UNION ALL
    SELECT 'Khanvel-Dudhani Road' AS name UNION ALL
    SELECT 'Khanvel-Mandoni Road' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Dudhani Lake Area' AS name UNION ALL
    SELECT 'Dudhani Main Road' AS name UNION ALL
    SELECT 'Dudhani Village Area' AS name UNION ALL
    SELECT 'Dudhani-Khanvel Road' AS name UNION ALL
    SELECT 'Mandoni Village' AS name UNION ALL
    SELECT 'Mandoni Main Road' AS name UNION ALL
    SELECT 'Mandoni-Khanvel Road' AS name UNION ALL
    SELECT 'Mandoni Rural Area' AS name UNION ALL
    SELECT 'Surangi Industrial Area' AS name UNION ALL
    SELECT 'Surangi Main Road' AS name UNION ALL
    SELECT 'Surangi Residential Area' AS name UNION ALL
    SELECT 'Surangi-Silvassa Road' AS name UNION ALL
    SELECT 'Saily Main Road' AS name UNION ALL
    SELECT 'Saily Village' AS name UNION ALL
    SELECT 'Saily Residential Area' AS name UNION ALL
    SELECT 'Saily-Silvassa Road' AS name UNION ALL
    SELECT 'Dhapsa Main Road' AS name UNION ALL
    SELECT 'Dhapsa Village' AS name UNION ALL
    SELECT 'Dhapsa Residential Area' AS name UNION ALL
    SELECT 'Vasona Village' AS name UNION ALL
    SELECT 'Vasona Main Road' AS name UNION ALL
    SELECT 'Vasona Residential Area' AS name UNION ALL
    SELECT 'Velugam Village' AS name UNION ALL
    SELECT 'Velugam Main Road' AS name UNION ALL
    SELECT 'Velugam Residential Area' AS name UNION ALL
    SELECT 'Silvassa City' AS name UNION ALL
    SELECT 'Khadoli' AS name UNION ALL
    SELECT 'Luhari' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

-- District: Daman (89 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Daman' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Moti Daman' AS name UNION ALL
    SELECT 'Fort Area' AS name UNION ALL
    SELECT 'Daman Fort Area' AS name UNION ALL
    SELECT 'Moti Daman Jetty' AS name UNION ALL
    SELECT 'Damanwada' AS name UNION ALL
    SELECT 'Ambawadi' AS name UNION ALL
    SELECT 'Bharwad Falia' AS name UNION ALL
    SELECT 'Thanapardi' AS name UNION ALL
    SELECT 'Bhamti' AS name UNION ALL
    SELECT 'Patlara' AS name UNION ALL
    SELECT 'Zari' AS name UNION ALL
    SELECT 'Pariyari' AS name UNION ALL
    SELECT 'Dabhel Road side' AS name UNION ALL
    SELECT 'Old Daman Town' AS name UNION ALL
    SELECT 'Collectorate Area' AS name UNION ALL
    SELECT 'Church Area' AS name UNION ALL
    SELECT 'Fort Road' AS name UNION ALL
    SELECT 'Nani Daman' AS name UNION ALL
    SELECT 'Kathiria' AS name UNION ALL
    SELECT 'Dalwada' AS name UNION ALL
    SELECT 'Bhimpore' AS name UNION ALL
    SELECT 'Varkund' AS name UNION ALL
    SELECT 'Marwad' AS name UNION ALL
    SELECT 'Kadaiya' AS name UNION ALL
    SELECT 'Dunetha' AS name UNION ALL
    SELECT 'Devka' AS name UNION ALL
    SELECT 'Devka Beach Area' AS name UNION ALL
    SELECT 'Ringanwada' AS name UNION ALL
    SELECT 'Kachigam' AS name UNION ALL
    SELECT 'Dabhel' AS name UNION ALL
    SELECT 'Tin Batti' AS name UNION ALL
    SELECT 'Khariwad' AS name UNION ALL
    SELECT 'Jetty Area' AS name UNION ALL
    SELECT 'Bus Stand Area' AS name UNION ALL
    SELECT 'Nani Daman Market' AS name UNION ALL
    SELECT 'Nani Daman Main Road' AS name UNION ALL
    SELECT 'Devka Road' AS name UNION ALL
    SELECT 'Airport Road' AS name UNION ALL
    SELECT 'Daman-Vapi Road' AS name UNION ALL
    SELECT 'Somnath Road' AS name UNION ALL
    SELECT 'Daman Industrial Estate Area' AS name UNION ALL
    SELECT 'Kachigam Industrial Area' AS name UNION ALL
    SELECT 'Kachigam Industrial Estate' AS name UNION ALL
    SELECT 'Kachigam Main Road' AS name UNION ALL
    SELECT 'Kachigam Residential Area' AS name UNION ALL
    SELECT 'Kachigam-Moti Daman Road' AS name UNION ALL
    SELECT 'Kachigam-Nani Daman Road' AS name UNION ALL
    SELECT 'Dabhel Industrial Area' AS name UNION ALL
    SELECT 'Dabhel Industrial Estate' AS name UNION ALL
    SELECT 'Dabhel Main Road' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Dabhel-Somnath Road' AS name UNION ALL
    SELECT 'Dabhel Residential Area' AS name UNION ALL
    SELECT 'Dabhel-Nani Daman Road' AS name UNION ALL
    SELECT 'Bhimpore Industrial Area' AS name UNION ALL
    SELECT 'Bhimpore Main Road' AS name UNION ALL
    SELECT 'Bhimpore Residential Area' AS name UNION ALL
    SELECT 'Bhimpore-Nani Daman Road' AS name UNION ALL
    SELECT 'Varkund Main Road' AS name UNION ALL
    SELECT 'Varkund Residential Area' AS name UNION ALL
    SELECT 'Varkund-Nani Daman Road' AS name UNION ALL
    SELECT 'Marwad Beach Area' AS name UNION ALL
    SELECT 'Marwad Main Road' AS name UNION ALL
    SELECT 'Marwad Residential Area' AS name UNION ALL
    SELECT 'Devka-Marwad Road' AS name UNION ALL
    SELECT 'Kadaiya Industrial Area' AS name UNION ALL
    SELECT 'Kadaiya Main Road' AS name UNION ALL
    SELECT 'Mirasol Area' AS name UNION ALL
    SELECT 'Kadaiya Residential Area' AS name UNION ALL
    SELECT 'Dunetha Main Road' AS name UNION ALL
    SELECT 'Dunetha Panchayat Area' AS name UNION ALL
    SELECT 'Dunetha Residential Area' AS name UNION ALL
    SELECT 'Dunetha-Bhensroad Area' AS name UNION ALL
    SELECT 'Devka Beach' AS name UNION ALL
    SELECT 'Devka Beach Road' AS name UNION ALL
    SELECT 'Devka Colony' AS name UNION ALL
    SELECT 'Devka Main Road' AS name UNION ALL
    SELECT 'Devka Residential Area' AS name UNION ALL
    SELECT 'Devka-Nani Daman Road' AS name UNION ALL
    SELECT 'Ringanwada Industrial Area' AS name UNION ALL
    SELECT 'Ringanwada Main Road' AS name UNION ALL
    SELECT 'Ringanwada Residential Area' AS name UNION ALL
    SELECT 'Daman-Vapi Main Road' AS name UNION ALL
    SELECT 'Ringanwada-Nani Daman Road' AS name UNION ALL
    SELECT 'Khariwad Main Road' AS name UNION ALL
    SELECT 'Khariwad Residential Area' AS name UNION ALL
    SELECT 'Khariwad Market Area' AS name UNION ALL
    SELECT 'Khariwad-Nani Daman' AS name UNION ALL
    SELECT 'Daman Beach Area' AS name UNION ALL
    SELECT 'Jampore Beach' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

-- District: Diu (84 localities)
SET @district_id = (SELECT id FROM districts WHERE state_id = @state_id AND name = 'Diu' LIMIT 1);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Diu' AS name UNION ALL
    SELECT 'Fort Area' AS name UNION ALL
    SELECT 'Fort Road' AS name UNION ALL
    SELECT 'Diu Town' AS name UNION ALL
    SELECT 'Diu Market' AS name UNION ALL
    SELECT 'Vegetable Market Area' AS name UNION ALL
    SELECT 'Bunder Road' AS name UNION ALL
    SELECT 'Jetty Area' AS name UNION ALL
    SELECT 'Ghoghla Road' AS name UNION ALL
    SELECT 'Nagoa Road' AS name UNION ALL
    SELECT 'Fudam Road' AS name UNION ALL
    SELECT 'Collectorate Area' AS name UNION ALL
    SELECT 'Police Station Area' AS name UNION ALL
    SELECT 'Hospital Area' AS name UNION ALL
    SELECT 'Gandhi Chowk' AS name UNION ALL
    SELECT 'Jawahar Chowk' AS name UNION ALL
    SELECT 'Bus Stand Area' AS name UNION ALL
    SELECT 'Port Area' AS name UNION ALL
    SELECT 'Beach Road' AS name UNION ALL
    SELECT 'Chakratirth Area' AS name UNION ALL
    SELECT 'Sunset Point Area' AS name UNION ALL
    SELECT 'Ghoghla' AS name UNION ALL
    SELECT 'Ghoghla Beach' AS name UNION ALL
    SELECT 'Ghoghla Main Road' AS name UNION ALL
    SELECT 'Ghoghla Panchayat Area' AS name UNION ALL
    SELECT 'Ghoghla Residential Area' AS name UNION ALL
    SELECT 'Ghoghla Market Area' AS name UNION ALL
    SELECT 'Ghoghla Coast Road' AS name UNION ALL
    SELECT 'Ghoghla-Diu Road' AS name UNION ALL
    SELECT 'Fudam' AS name UNION ALL
    SELECT 'Fudam Main Road' AS name UNION ALL
    SELECT 'Fudam Residential Area' AS name UNION ALL
    SELECT 'Nagoa Road-Fudam' AS name UNION ALL
    SELECT 'Collectorate/Nagoa Road vicinity' AS name UNION ALL
    SELECT 'Nagoa' AS name UNION ALL
    SELECT 'Nagoa Beach' AS name UNION ALL
    SELECT 'Nagoa Main Road' AS name UNION ALL
    SELECT 'Nagoa Residential Area' AS name UNION ALL
    SELECT 'Nagoa Coast' AS name UNION ALL
    SELECT 'Nagoa Beach Road' AS name UNION ALL
    SELECT 'Nagoa-Zolawadi Road' AS name UNION ALL
    SELECT 'Vanakbara' AS name UNION ALL
    SELECT 'Vanakbara Main Road' AS name UNION ALL
    SELECT 'Vanakbara Bunder Road' AS name UNION ALL
    SELECT 'Vanakbara Jetty Area' AS name UNION ALL
    SELECT 'Vanakbara Fishing Harbour Area' AS name UNION ALL
    SELECT 'Azad Chowk' AS name UNION ALL
    SELECT 'Vanakbara Market Area' AS name UNION ALL
    SELECT 'Mithiwadi' AS name UNION ALL
    SELECT 'Kalasheri' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

INSERT INTO localities (district_id, name)
SELECT @district_id, name FROM (
    SELECT 'Saudwadi Road' AS name UNION ALL
    SELECT 'Vanakbara Residential Area' AS name UNION ALL
    SELECT 'Saudwadi' AS name UNION ALL
    SELECT 'Saudwadi Main Road' AS name UNION ALL
    SELECT 'Khara Area' AS name UNION ALL
    SELECT 'Kharvawada' AS name UNION ALL
    SELECT 'Kharvawada Jetty' AS name UNION ALL
    SELECT 'Vadlimata' AS name UNION ALL
    SELECT 'Saudwadi Residential Area' AS name UNION ALL
    SELECT 'Saudwadi Fishing Area' AS name UNION ALL
    SELECT 'Par-Dagachi' AS name UNION ALL
    SELECT 'Bucharwada' AS name UNION ALL
    SELECT 'Bucharwada Main Road' AS name UNION ALL
    SELECT 'Bucharwada Pond Area' AS name UNION ALL
    SELECT 'Bucharwada Panchayat Area' AS name UNION ALL
    SELECT 'Bucharwada Residential Area' AS name UNION ALL
    SELECT 'Bucharwada Village Area' AS name UNION ALL
    SELECT 'Zolawadi' AS name UNION ALL
    SELECT 'Zolawadi Main Road' AS name UNION ALL
    SELECT 'Dangarwadi' AS name UNION ALL
    SELECT 'Zolawadi Residential Area' AS name UNION ALL
    SELECT 'Zolawadi Village Area' AS name UNION ALL
    SELECT 'Maharani' AS name UNION ALL
    SELECT 'Maharani Main Road' AS name UNION ALL
    SELECT 'Maharani Village Area' AS name UNION ALL
    SELECT 'Maharani Residential Area' AS name UNION ALL
    SELECT 'Vanakbara-Maharani Road' AS name UNION ALL
    SELECT 'Maharana Pratap' AS name UNION ALL
    SELECT 'Maharana Pratap Village' AS name UNION ALL
    SELECT 'Maharana Pratap Residential Area' AS name UNION ALL
    SELECT 'Vanakbara-Maharana Pratap Road' AS name UNION ALL
    SELECT 'Nagoa Beach Area' AS name UNION ALL
    SELECT 'Bucherwada' AS name UNION ALL
    SELECT 'Diu Fort Area' AS name
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM localities WHERE district_id = @district_id AND name = tmp.name
);

