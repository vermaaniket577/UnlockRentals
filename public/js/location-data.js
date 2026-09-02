/**
 * UnlockRentals — Indian Location Cascading & Auto-complete Engine
 * High-performance, browser-cached location dictionary
 */
(function(window) {
    'use strict';

    const _standardDistrictsByState = {
        "HR": ["Gurugram", "Faridabad", "Ambala", "Bhiwani", "Charkhi Dadri", "Fatehabad", "Hisar", "Jhajjar", "Jind", "Kaithal", "Karnal", "Kurukshetra", "Mahendragarh", "Nuh", "Palwal", "Panchkula", "Panipat", "Rewari", "Rohtak", "Sirsa", "Sonipat", "Yamunanagar"],
        "DL": ["New Delhi", "Central Delhi", "East Delhi", "North Delhi", "North East Delhi", "North West Delhi", "Shahdara", "South Delhi", "South East Delhi", "South West Delhi", "West Delhi"],
        "UP": ["Noida", "Greater Noida", "Ghaziabad", "Agra", "Aligarh", "Ambedkar Nagar", "Ayodhya", "Bareilly", "Gorakhpur", "Jhansi", "Kanpur", "Lucknow", "Mathura", "Meerut", "Moradabad", "Prayagraj", "Varanasi", "Saharanpur", "Firozabad", "Muzaffarnagar"],
        "MH": ["Mumbai", "Mumbai Suburban", "Pune", "Thane", "Navi Mumbai", "Nagpur", "Nashik", "Aurangabad", "Solapur", "Kolhapur", "Amravati", "Nanded", "Alibaug"],
        "KA": ["Bagalkote", "Ballari", "Belagavi", "Bengaluru Rural", "Bengaluru Urban", "Bengaluru", "Bidar", "Chamarajanagar", "Chikkaballapur", "Chikkamagaluru", "Chitradurga", "Dakshina Kannada", "Davanagere", "Dharwad", "Gadag", "Hassan", "Haveri", "Kalaburagi", "Kodagu", "Kolar", "Koppal", "Mandya", "Mysuru", "Raichur", "Ramanagara", "Shivamogga", "Tumakuru", "Udupi", "Uttara Kannada", "Vijayapura", "Vijayanagara", "Yadgir"],
        "PB": ["Chandigarh", "Amritsar", "Ludhiana", "Jalandhar", "Patiala", "Bathinda", "Mohali", "Hoshiarpur", "Pathankot", "Moga"],
        "RJ": ["Jaipur", "Jodhpur", "Kota", "Bikaner", "Ajmer", "Udaipur", "Bhilwara", "Alwar", "Bharatpur", "Sikar"],
        "GJ": ["Ahmedabad", "Amreli", "Anand", "Aravalli", "Banaskantha", "Bharuch", "Bhavnagar", "Botad", "Chhota Udaipur", "Dahod", "Dang", "Devbhumi Dwarka", "Gandhinagar", "Gir Somnath", "Jamnagar", "Junagadh", "Kheda", "Kutch", "Mahisagar", "Mehsana", "Morbi", "Narmada", "Navsari", "Panchmahal", "Patan", "Porbandar", "Rajkot", "Sabarkantha", "Surat", "Surendranagar", "Tapi", "Vadodara", "Valsad"],
        "WB": ["Kolkata", "Howrah", "North 24 Parganas", "South 24 Parganas", "Hooghly", "Durgapur", "Asansol", "Siliguri", "Darjeeling"],
        "TS": ["Adilabad", "Bhadradri Kothagudem", "Hanamkonda", "Hyderabad", "Jagitial", "Jangaon", "Jayashankar Bhupalpally", "Jogulamba Gadwal", "Kamareddy", "Karimnagar", "Khammam", "Komaram Bheem Asifabad", "Mahabubabad", "Mahabubnagar", "Mancherial", "Medak", "Medchal-Malkajgiri", "Mulugu", "Nagarkurnool", "Nalgonda", "Narayanpet", "Nirmal", "Nizamabad", "Peddapalli", "Rajanna Sircilla", "Rangareddy", "Sangareddy", "Siddipet", "Suryapet", "Vikarabad", "Wanaparthy", "Warangal", "Yadadri Bhuvanagiri"],
        "TN": ["Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kallakurichi", "Kancheepuram", "Kanniyakumari", "Karur", "Krishnagiri", "Madurai", "Mayiladuthurai", "Nagapattinam", "Namakkal", "Perambalur", "Pudukottai", "Ramanathapuram", "Ranipet", "Salem", "Sivaganga", "Tenkasi", "Thanjavur", "The Nilgiris", "Theni", "Thoothukudi", "Tiruchirappalli", "Tirunelveli", "Tirupathur", "Tiruppur", "Tiruvallur", "Tiruvannamalai", "Tiruvarur", "Vellore", "Viluppuram", "Virudhunagar"],
        "AP": ["Alluri Sitharama Raju", "Anakapalli", "Ananthapuramu", "Annamayya", "Bapatla", "Chittoor", "Dr. B. R. Ambedkar Konaseema", "East Godavari", "Eluru", "Guntur", "Kakinada", "Krishna", "Kurnool", "Nandyal", "NTR", "Palnadu", "Parvathipuram Manyam", "Prakasam", "Srikakulam", "Sri Potti Sriramulu Nellore", "Sri Sathya Sai", "Tirupati", "Visakhapatnam", "Vizianagaram", "West Godavari", "YSR Kadapa"],
        "MP": ["Agar Malwa", "Alirajpur", "Anuppur", "Ashoknagar", "Balaghat", "Barwani", "Betul", "Bhind", "Bhopal", "Burhanpur", "Chhatarpur", "Chhindwara", "Damoh", "Datia", "Dewas", "Dhar", "Dindori", "Guna", "Gwalior", "Harda", "Indore", "Jabalpur", "Jhabua", "Katni", "Khandwa", "Khargone", "Maihar", "Mandla", "Mandsaur", "Mauganj", "Morena", "Narmadapuram", "Narsinghpur", "Neemuch", "Niwari", "Pandhurna", "Panna", "Raisen", "Rajgarh", "Ratlam", "Rewa", "Sagar", "Satna", "Sehore", "Seoni", "Shahdol", "Shajapur", "Sheopur", "Shivpuri", "Sidhi", "Singrauli", "Tikamgarh", "Ujjain", "Umaria", "Vidisha"],
        "BR": ["Araria", "Arwal", "Aurangabad", "Banka", "Begusarai", "Bhagalpur", "Bhojpur", "Buxar", "Darbhanga", "East Champaran", "Gaya", "Gopalganj", "Jamui", "Jehanabad", "Kaimur", "Katihar", "Khagaria", "Kishanganj", "Lakhisarai", "Madhepura", "Madhubani", "Munger", "Muzaffarpur", "Nalanda", "Nawada", "Patna", "Purnia", "Rohtas", "Saharsa", "Samastipur", "Saran", "Sheikhpura", "Sheohar", "Sitamarhi", "Siwan", "Supaul", "Vaishali", "West Champaran"],
        "UK": ["Dehradun", "Haridwar", "Rishikesh", "Nainital", "Haldwani", "Roorkee", "Rudrapur", "Kashipur"],
        "HP": ["Bilaspur", "Chamba", "Hamirpur", "Kangra", "Kinnaur", "Kullu", "Lahaul and Spiti", "Mandi", "Shimla", "Sirmaur", "Solan", "Una"],
        "GA": ["North Goa", "South Goa"],
        "JH": ["Bokaro", "Chatra", "Deoghar", "Dhanbad", "Dumka", "East Singhbhum", "Garhwa", "Giridih", "Godda", "Gumla", "Hazaribagh", "Jamtara", "Khunti", "Koderma", "Latehar", "Lohardaga", "Pakur", "Palamu", "Ramgarh", "Ranchi", "Sahibganj", "Seraikela-Kharsawan", "Simdega", "West Singhbhum"],
        "CT": ["Raipur", "Bhilai", "Bilaspur", "Korba", "Durg", "Rajnandgaon"],
        "OR": ["Bhubaneswar", "Cuttack", "Rourkela", "Berhampur", "Sambalpur", "Puri"],
        "AS": ["Guwahati", "Silchar", "Dibrugarh", "Jorhat", "Nagaon", "Tinsukia"],
        "JK": ["Srinagar", "Jammu", "Anantnag", "Baramulla", "Kathua", "Udhampur"],
        "LA": ["Leh", "Kargil"],
        "KL": ["Alappuzha", "Ernakulam", "Idukki", "Kannur", "Kasaragod", "Kollam", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta", "Thiruvananthapuram", "Thrissur", "Wayanad"],
        "CH": ["Chandigarh", "Mohali", "Panchkula", "Zirakpur"]
    };

    const _stateNameToCode = {
        "HARYANA": "HR", "DELHI": "DL", "UTTAR PRADESH": "UP", "MAHARASHTRA": "MH",
        "KARNATAKA": "KA", "PUNJAB": "PB", "RAJASTHAN": "RJ", "GUJARAT": "GJ",
        "WEST BENGAL": "WB", "TELANGANA": "TS", "TAMIL NADU": "TN", "ANDHRA PRADESH": "AP",
        "MADHYA PRADESH": "MP", "BIHAR": "BR", "UTTARAKHAND": "UK", "HIMACHAL PRADESH": "HP",
        "GOA": "GA", "JHARKHAND": "JH", "CHHATTISGARH": "CT", "ODISHA": "OR", "ASSAM": "AS",
        "JAMMU AND KASHMIR": "JK", "LADAKH": "LA", "KERALA": "KL", "CHANDIGARH": "CH"
    };

    const _standardLocalities = {
        "ranchi": [
            "Airport Road", "Albert Ekka Chowk", "Anandpur", "Argora", "Argora Housing Colony", "Ashok Nagar", "Ashok Vihar", "Bahu Bazar", "Bariatu", "Bariatu Housing Colony",
            "Bariatu Road", "Bero", "Birsa Chowk", "Booty", "Booty More", "Boreya", "Chanho", "Church Road", "Chutia", "Circular Road",
            "Dhurwa", "Doranda", "Doranda Bazaar", "Firayalal Chowk", "Green Park Colony", "Harmu", "Harmu Housing Colony", "Hatia", "Hatma", "Hehal",
            "Hindpiri", "Hinoo", "Hotwar", "Irba", "Itki Road", "Jagannathpur", "Jora Talab", "Kadru", "Kamre", "Kanka",
            "Kanke", "Kanke Dam Area", "Kanke Road", "Kantatoli", "Karamtoli", "Kathal More", "Khelgaon", "Kokar", "Kusai Colony", "Kutchery Road",
            "Lalpur", "Lalpur Chowk", "Lowadih", "Lower Bazar", "Main Road", "Mandar", "Mesra", "Morabadi", "Morabadi Housing Colony", "Nagri",
            "Namkum", "Neori", "Nivaranpur", "Ormanjhi", "Overbridge Area", "Piska More", "Pithoria", "Pundag", "Ranchi Railway Station Area", "Ratu",
            "Ratu Road", "Sadar", "Singh More", "Sujata Chowk", "Sukurhuttu", "Sukhdeonagar", "Tatisilwai", "Tharpakhna", "Tupudana", "Upper Bazar"
        ],
        "ujjain": [
            "Adarsh Nagar", "Agar Road", "Alkapuri", "Ankpat", "Badnagar Road", "Badnagar Road Area", "Barnagar Road", "Bhairavgarh", "Chiman Ganj Mandi", "Chintaman",
            "Chintaman Ganesh", "Daulatganj", "Dewas Gate", "Dewas Road", "Dhanvantari Nagar", "Ekta Nagar", "Engineering College Road", "Fatehabad Road Area", "Freeganj", "Gadkalika",
            "Gopal Mandir Area", "Gram Khedi", "Gudri Bazaar", "Harifatak", "Indira Nagar", "Indore Gate", "Indore Road", "Jaisinghpura", "Kaliyadeh", "Kanthal",
            "Kharakuan", "Koyla Phatak", "Krishna Nagar", "Kshipra Vihar", "Lal Gate", "Lalpur", "Madhav Nagar", "Mahananda Nagar", "Mahakal Marg", "Mahashweta Nagar",
            "Maksi Road", "Malipura", "Mangrola", "Mehidpur Road Area", "MR-5 Road", "Muni Nagar", "Nagda Road", "Nagziri", "Nai Sadak", "Nanakheda",
            "Nanakheda Bus Stand Area", "Pandyakhedi", "Patni Bazaar", "Pithora", "Ramghat", "Rishi Nagar", "Sandipani Ashram Area", "Sant Nagar", "Sanwer Road", "Sarafa Bazaar",
            "Sethi Nagar", "Shastri Nagar", "Shivaji Park Colony", "Siddhavat", "Suraj Nagar", "Tower Chowk", "Triveni", "Triveni Vihar", "Undasa", "University Road",
            "Vasant Vihar", "Ved Nagar", "Vikram Nagar", "Vikram University Area", "Vivekanand Nagar"
        ],
        "chhatarpur": [
            "Adarsh Nagar", "Alipura", "Bada Malhera", "Bamitha", "Bijawar", "Bijawar Road", "Bundelkhand Colony", "Bus Stand Area", "Bus Stand Road", "Chandla",
            "Chhatarpur City", "Chhatarpur Railway Colony", "Choubey Colony", "Church Road", "Civil Lines", "Court Road Area", "District Hospital Area", "Galla Mandi Area", "Gandhi Nagar", "Garhi Malhara",
            "Gaurihar", "Ghuwara", "Guraiya", "Harpalpur", "Hatwara", "Housing Board Colony", "Industrial Area", "Ishanagar", "Jawahar Road", "Kakun",
            "Khajuraho", "Khajuraho Road", "Kotwali Road", "Kulpahar", "Laundi", "Maharaja Chhatrasal Nagar", "Maharajpur", "Mahoba Road", "Narayanpura", "Naugaon Road",
            "New Chhatarpur", "Nowgong", "Nowgong Road", "Old Chhatarpur", "Pahadi", "Panna Naka", "Panwari", "Pathapur", "Police Line Area", "Railway Station Road",
            "Raj Nagar", "Rajnagar", "Sagar Road", "Sarwai", "Satai", "Satai Road", "Shanti Nagar", "Subhash Nagar", "Tikamgarh Road", "Vivekanand Nagar"
        ],
        "cuttack": [
            "Athagarh", "Badamba", "Badambadi", "Balu Bazaar", "Banki", "Baramba", "Barang", "Bhadimunda", "Bidanasi", "Buxi Bazaar",
            "CDA Main Road", "CDA Sector 1", "CDA Sector 2", "CDA Sector 3", "CDA Sector 4", "CDA Sector 5", "CDA Sector 6", "CDA Sector 7", "CDA Sector 8", "CDA Sector 9",
            "CDA Sector 10", "CDA Sector 11", "CDA Sector 12", "CDA Sector 13", "CDA Sector 14", "CDA Sector 15", "CDA Sector 16", "CDA Sector 17", "Chauliaganj", "Chhatra Bazaar",
            "Choudhury Bazaar", "Choudwar", "College Square", "Damapada", "Dargha Bazaar", "Dolamundai", "Gandarpur", "Gopalpur", "Gurudijhatia", "Haripur",
            "Jagatpur", "Jobra", "Kalyan Nagar", "Kanpur", "Kathajodi Vihar", "Kesharpur", "Khan Nagar", "Khuntuni", "Kishannagar", "Link Road",
            "Madhupatna", "Mahanga", "Mangalabag", "Markat Nagar", "Meria Bazaar", "Naraj", "Narsinghpur", "Naya Bazaar", "Naya Sadak", "Nayabazar",
            "Nemalo", "Niali Road", "Nischintakoili", "Nuapatna", "Phulnakhara", "Pratap Nagari", "Raghunathpur", "Rajendra Nagar", "Ranihat", "Ring Road",
            "Salipur", "Sati Chaura", "Sector 6 Market", "Shelter Chhak", "Sikharpur", "Sutahat", "Tangi", "Tigiria", "Trishulia", "Tulsipur"
        ],
        "south goa": [
            "Agonda", "Ambaulim", "Aquem", "Arossim", "Assolda", "Assolna", "Avedem", "Balli", "Bandora", "Barcem",
            "Benaulim", "Betalbatim", "Betora", "Betul", "Bogmalo", "Borda", "Borim", "Canaguinim", "Canacona", "Cansaulim",
            "Carmona", "Cavelossim", "Chandor", "Chaudi", "Chicalim", "Chimbel", "Chinchinim", "Colem", "Colomb", "Colva",
            "Comba", "Cortalim", "Cotigao", "Cuncolim", "Curchorem", "Curtorim", "Curti", "Dabolim", "Dharbandora", "Dramapur",
            "Fatorda", "Fatorpa", "Fatrade", "Galgibaga", "Gogol", "Headland Sada", "Issorcim", "Kalay", "Khotigao", "Loliem",
            "Loutolim", "Macasana", "Majorda", "Mangor Hill", "Marcaim", "Margao", "Maxem", "Mobor", "Molcarnem", "Mollem",
            "Nagorcem", "Naqueri", "Navelim", "Neturlim", "New Vaddem", "Pajifond", "Palolem", "Paroda", "Patnem", "Pirla",
            "Poinguinim", "Ponda", "Quepem", "Queula", "Rachol", "Raia", "Rajbag", "Rivona", "Sadolxem", "Sancoale",
            "Sanguem", "Sanvordem", "Shiroda", "Sirvoi", "Talpona", "Uguem", "Usgao", "Utorda", "Varca", "Vasco da Gama",
            "Velim", "Velsao", "Verna", "Xeldem", "Zalor", "Zuarinagar"
        ],
        "north goa": [
            "Advalpal", "Agarwada", "Aldona", "Altinho", "Amona", "Anjuna", "Anjuna Beach", "Arambol", "Arpora", "Ashwem",
            "Assagao", "Assonora", "Baga", "Baga Beach", "Bambolim", "Bastora", "Batim", "Bicholim", "Bordem", "Calangute",
            "Calangute Beach", "Campal", "Camurlim", "Candolim", "Candolim Beach", "Caranzalem", "Casnem", "Chapora", "Chimbel", "Colvale",
            "Corgao", "Corjuem", "Cotorem", "Curti", "Dando", "Dona Paula", "Goa Velha", "Guirim", "Guleli", "Harambol",
            "Harvalem", "Honda", "Ibrampur", "Karapur", "Keri", "Khotode", "Latambarcem", "Little Vagator", "Maem", "Mandrem",
            "Mapusa", "Mauxi", "Mayem", "Merces", "Miramar", "Moira", "Mopa", "Morjim", "Morlem", "Mulgao",
            "Nachinola", "Nagargao", "Nagoa", "Navelim", "Nerul", "Old Goa", "Ozran", "Paliem", "Panaji", "Parcem",
            "Parra", "Pernem", "Pilerne", "Poriem", "Porvorim", "Querem", "Querim", "Reis Magos", "Revora", "Ribandar",
            "Saligao", "Salvador do Mundo", "Sangolda", "Sanquelim", "Santa Cruz", "Sanvordem", "Sattari", "Sinquerim", "Sinquerim Beach", "Siolim",
            "Socorro", "St Inez", "Surla", "Taleigao", "Thane", "Tiracol", "Tivim", "Tuem", "Vagator", "Valpoi",
            "Verla Canca", "Zarme"
        ],
        "wayanad": [
            "Achooranam", "Ambalavayal", "Ambukuthi", "Arattupara", "Banasura", "Beenachi", "Boyce Estate", "Cheengode", "Cheeral", "Chooralmala",
            "Chundale", "Edakkal", "Edavaka", "Irulam", "Kainatty", "Kalloor", "Kalpetta", "Kalpetta North", "Kalpetta South", "Kambalakkad",
            "Kaniyambetta", "Kappimala", "Karapuzha", "Kattikkulam", "Kolagappara", "Koleri", "Kottathara", "Krishnagiri", "Kumbalakkad", "Kuppadi",
            "Lakkidi", "Mananthavady", "Manjoora", "Meppadi", "Moolankavu", "Mullankolly", "Mundakkai", "Muttil", "Nenmeni", "Noolpuzha",
            "Padinjarathara", "Panamaram", "Panamaram Town", "Periya", "Pookode", "Pozhuthana", "Pulpally", "Rippon", "Sugandhagiri", "Sulthan Bathery",
            "Thariyode", "Thavinhal", "Thirunelly", "Vadakkanad", "Vellamunda", "Vythiri", "Vythiri Town"
        ],
        "thiruvananthapuram": [
            "Akkulam", "Anayara", "Andoorkonam", "Arappura", "Attingal", "Attukal", "Bakery Junction", "Balaramapuram", "Beemapally", "Chacka",
            "Chalai", "Chavadimukku", "DPI", "East Fort", "Fort", "Jagathy", "Kaniyapuram", "Karamana", "Karikkakom", "Karyavattom",
            "Kattakkada", "Kazhakkoottam", "Kesavadasapuram", "Killi", "Kowdiar", "Kovalam", "Kudappanakunnu", "Kulathoor", "Kumarapuram", "Manacaud",
            "Mangalapuram", "Mannanthala", "Maruthankuzhi", "Medical College", "Menamkulam", "MG Road", "Mudavanmugal", "Muttathara", "Nalanchira", "Nanthancode",
            "Nemom", "Nettayam", "Neyyattinkara", "Palayam", "Palkulangara", "Pangappara", "Pappanamcode", "Paruthikuzhi", "Pattom", "Peroorkada",
            "Pettah", "Peyad", "Poojappura", "Poovar", "Pothencode", "Pravachambalam", "Sasthamangalam", "Shangumugham", "Sreekariyam", "Statue",
            "Technopark", "Thampanoor", "Thirumala", "Thycaud", "Ulloor", "Vallakkadavu", "Vattiyoorkavu", "Vazhuthacaud", "Veli", "Vellayambalam",
            "Vembayam", "Venjaramoodu", "Vizhinjam"
        ],
        "trivandrum": [
            "Akkulam", "Anayara", "Andoorkonam", "Arappura", "Attingal", "Attukal", "Bakery Junction", "Balaramapuram", "Beemapally", "Chacka",
            "Chalai", "Chavadimukku", "DPI", "East Fort", "Fort", "Jagathy", "Kaniyapuram", "Karamana", "Karikkakom", "Karyavattom",
            "Kattakkada", "Kazhakkoottam", "Kesavadasapuram", "Killi", "Kowdiar", "Kovalam", "Kudappanakunnu", "Kulathoor", "Kumarapuram", "Manacaud",
            "Mangalapuram", "Mannanthala", "Maruthankuzhi", "Medical College", "Menamkulam", "MG Road", "Mudavanmugal", "Muttathara", "Nalanchira", "Nanthancode",
            "Nemom", "Nettayam", "Neyyattinkara", "Palayam", "Palkulangara", "Pangappara", "Pappanamcode", "Paruthikuzhi", "Pattom", "Peroorkada",
            "Pettah", "Peyad", "Poojappura", "Poovar", "Pothencode", "Pravachambalam", "Sasthamangalam", "Shangumugham", "Sreekariyam", "Statue",
            "Technopark", "Thampanoor", "Thirumala", "Thycaud", "Ulloor", "Vallakkadavu", "Vattiyoorkavu", "Vazhuthacaud", "Veli", "Vellayambalam",
            "Vembayam", "Venjaramoodu", "Vizhinjam"
        ],
        "leh": [
            "Agling", "Alchi", "Basgo", "Changspa", "Chemrey", "Choglamsar", "Chubi", "Chushul", "Diskit", "Durbuk",
            "Fort Road", "Gya", "Hanle", "Hemis", "Housing Colony", "Hunder", "Karzoo", "Khalsar", "Khaltse", "Khardung",
            "Lamayuru", "Leh Town", "Likir", "Lower Tukcha", "Main Bazaar", "Matho", "Miru", "Nang", "Nimmu", "Nyoma",
            "Old Leh Road", "Panamik", "Phyang", "Rumtse", "Saboo", "Sakti", "Sankar", "Saspol", "Shey", "Skara",
            "Spituk", "Stok", "Sumur", "Tangtse", "Tangyar", "Thiksey", "Turtuk", "Upper Tukcha", "Upshi", "Zangsti"
        ],
        "kargil": [
            "Akchamal", "Baroo", "Barsoo", "Batalik", "Batalik Road Area", "Batalik Sector", "Biama", "Bodh Kharbu", "Chiktan", "Chulichan",
            "Dah", "Darchiks", "Drass", "Faroona", "Garkone", "Goma Kargil", "Hardass", "Hunderman", "Kargil Airport Area", "Kargil Bazaar",
            "Kargil Town", "Karith", "Karkit", "Karkitchoo", "Khaltse Road Area", "Khangral", "Kharul", "Khumbathang", "Lalung", "Minji",
            "Mulbekh", "Panikhar", "Parkachik", "Pashkum", "Saliskote", "Sankoo", "Shakar Chiktan", "Shargole", "Shilikchey", "Sodh",
            "Stakpa", "Taisuru", "Tambis", "Thang", "Thasgam", "TSG Block Area", "Umba", "Wakha"
        ],
        "ambedkar nagar": [
            "Ahirauli", "Akbarpur", "Alapur", "Asopur", "Bahorikpur", "Bangaon", "Bariyawan", "Baskhari", "Bhaisoli", "Bhiti",
            "Bihra", "Dullahpur", "Hanswar", "Iltifatganj", "Jahangirganj", "Jaitpur", "Jalalpur", "Kalyanpur", "Katehari", "Khajuri",
            "Kichhauchha", "Kodra", "Lakshmanpur", "Lakshmipur", "Mahrua", "Malipur", "Mijhaura", "Miranpur", "Nagpur", "Narharpur",
            "Pahiti", "Pratap Pur", "Rajesultanpur", "Ram Nagar", "Ramnagar", "Sarsawan", "Semari", "Shahzadpur", "Sikandarpur", "Tanda"
        ],
        "lucknow": [
            "Aishbagh", "Alambagh", "Aliganj", "Amar Shaheed Path", "Aminabad", "Arjunganj", "Ashiyana", "Ayodhya Road", "Balaganj", "Bangla Bazar",
            "Bharwara", "Bijnor Road", "Butler Colony", "Chandralok", "Charbagh", "Chinhat", "Chowk", "Daliganj", "Deva Road", "Dubagga",
            "Eldeco Greens", "Eldeco Udyan", "Faizabad Road", "Golf City", "Gomti Nagar", "Gomti Nagar Extension", "Hardoi Road", "Hazratganj", "IIM Road", "Indira Nagar",
            "Jankipuram", "Jankipuram Extension", "Kaiserbagh", "Kakori", "Kalyanpur", "Kamta", "Kanpur Road", "Kapoorthala", "Khurram Nagar", "Krishna Nagar",
            "Kursi Road", "Lalbagh", "Lucknow Cantonment", "Mahanagar", "Malihabad", "Mall Avenue", "Matiyari", "Mohanlalganj", "Munshipulia", "Naka Hindola",
            "New Hyderabad", "Nirala Nagar", "Nishatganj", "Paper Mill Colony", "Para", "Raebareli Road", "Rajajipuram", "Rajendra Nagar", "Ring Road", "Ruchi Khand",
            "Sadar", "Sarojini Nagar", "Shaheed Path", "Sitapur Road", "South City", "Sultanpur Road", "Sushant Golf City", "Telibagh", "Thakurganj", "Transport Nagar",
            "Triveni Nagar", "University Road", "Vibhuti Khand", "Vikas Nagar", "Viram Khand", "Vivek Khand", "Vrindavan Yojana", "Wazirganj", "Yahiyaganj"
        ],
        "gurugram": [
            "Ardee City", "Ashok Vihar Phase 1", "Ashok Vihar Phase 2", "Badshahpur", "Bahadurgarh Road", "Basai", "Basai Road", "Bhondsi", "Bhim Nagar", "Civil Lines",
            "Cyber City", "Cyber Hub", "Dayanand Colony", "DLF Cyber City", "DLF City Phase 1", "DLF City Phase 2", "DLF City Phase 3", "DLF City Phase 4", "DLF City Phase 5", "DLF Phase 1",
            "DLF Phase 2", "DLF Phase 3", "DLF Phase 4", "DLF Phase 5", "Dwarka Expressway", "Farrukhnagar", "Gandhi Nagar", "Garhi Harsaru", "Golf Course Extension Road", "Golf Course Road",
            "Greenwood City", "Gwal Pahari", "Hans Enclave", "Heera Nagar", "IFFCO Chowk", "IMT Manesar", "Jacobpura", "Jalvayu Vihar", "Jharsa", "Jyoti Park",
            "Kadipur", "Kanhai", "Kherki Daula", "Krishna Colony", "Laxman Vihar", "Madanpuri", "Malibu Towne", "Manesar", "Maruti Kunj", "Mayfield Garden",
            "MG Road", "Mianwali Colony", "Model Town", "Mohan Nagar", "Nathupur", "New Gurgaon", "New Palam Vihar", "New Railway Road", "Nirvana Country", "Old Delhi Road",
            "Old Gurgaon", "Old Railway Road", "Palam Vihar", "Palam Vihar Extension", "Pataudi", "Pataudi Road", "Patel Nagar", "Rajendra Park", "Rajiv Chowk", "Ratan Vihar",
            "Rosewood City", "Sadar Bazar", "Sector 1", "Sector 2", "Sector 3", "Sector 3A", "Sector 4", "Sector 5", "Sector 6", "Sector 7",
            "Sector 9", "Sector 9A", "Sector 10", "Sector 10A", "Sector 11", "Sector 12", "Sector 12A", "Sector 14", "Sector 15", "Sector 15 Part 1",
            "Sector 15 Part 2", "Sector 17", "Sector 17A", "Sector 17B", "Sector 18", "Sector 21", "Sector 22", "Sector 22A", "Sector 23", "Sector 23A",
            "Sector 24", "Sector 25", "Sector 26", "Sector 27", "Sector 28", "Sector 29", "Sector 30", "Sector 31", "Sector 32", "Sector 33",
            "Sector 34", "Sector 35", "Sector 37", "Sector 37C", "Sector 37D", "Sector 38", "Sector 39", "Sector 40", "Sector 41", "Sector 42",
            "Sector 43", "Sector 44", "Sector 45", "Sector 46", "Sector 47", "Sector 48", "Sector 49", "Sector 50", "Sector 51", "Sector 52",
            "Sector 53", "Sector 54", "Sector 55", "Sector 56", "Sector 57", "Sector 58", "Sector 59", "Sector 60", "Sector 61", "Sector 62",
            "Sector 63", "Sector 63A", "Sector 64", "Sector 65", "Sector 66", "Sector 67", "Sector 67A", "Sector 68", "Sector 69", "Sector 70",
            "Sector 70A", "Sector 71", "Sector 72", "Sector 73", "Sector 74", "Sector 74A", "Sector 75", "Sector 76", "Sector 77", "Sector 78",
            "Sector 79", "Sector 80", "Sector 81", "Sector 81A", "Sector 82", "Sector 82A", "Sector 83", "Sector 84", "Sector 85", "Sector 86",
            "Sector 87", "Sector 88", "Sector 88A", "Sector 88B", "Sector 89", "Sector 89A", "Sector 89B", "Sector 90", "Sector 91", "Sector 92",
            "Sector 93", "Sector 94", "Sector 95", "Sector 95A", "Sector 95B", "Sector 99", "Sector 99A", "Sector 102", "Sector 103", "Sector 104",
            "Sector 105", "Sector 106", "Sector 107", "Sector 108", "Sector 109", "Sector 110", "Sector 110A", "Sector 111", "Sector 112", "Sector 113",
            "Sector 114", "Sector 115", "Sheetla Colony", "Sheetla Mata Road", "Shivaji Nagar", "Sohna", "Sohna Road", "South City 1", "South City 2", "Southern Peripheral Road (SPR)",
            "Subhash Nagar", "Sun City", "Surat Nagar", "Sushant Lok 1", "Sushant Lok 2", "Sushant Lok 3", "Udyog Vihar Phase 1", "Udyog Vihar Phase 2", "Udyog Vihar Phase 3", "Udyog Vihar Phase 4",
            "Udyog Vihar Phase 5", "Uppal Southend", "Vatika City", "Vipul World", "Wazirabad"
        ],
        "gurgaon": [
            "Ardee City", "Ashok Vihar Phase 1", "Ashok Vihar Phase 2", "Badshahpur", "Bahadurgarh Road", "Basai", "Basai Road", "Bhondsi", "Bhim Nagar", "Civil Lines",
            "Cyber City", "Cyber Hub", "Dayanand Colony", "DLF Cyber City", "DLF City Phase 1", "DLF City Phase 2", "DLF City Phase 3", "DLF City Phase 4", "DLF City Phase 5", "DLF Phase 1",
            "DLF Phase 2", "DLF Phase 3", "DLF Phase 4", "DLF Phase 5", "Dwarka Expressway", "Farrukhnagar", "Gandhi Nagar", "Garhi Harsaru", "Golf Course Extension Road", "Golf Course Road",
            "Greenwood City", "Gwal Pahari", "Hans Enclave", "Heera Nagar", "IFFCO Chowk", "IMT Manesar", "Jacobpura", "Jalvayu Vihar", "Jharsa", "Jyoti Park",
            "Kadipur", "Kanhai", "Kherki Daula", "Krishna Colony", "Laxman Vihar", "Madanpuri", "Malibu Towne", "Manesar", "Maruti Kunj", "Mayfield Garden",
            "MG Road", "Mianwali Colony", "Model Town", "Mohan Nagar", "Nathupur", "New Gurgaon", "New Palam Vihar", "New Railway Road", "Nirvana Country", "Old Delhi Road",
            "Old Gurgaon", "Old Railway Road", "Palam Vihar", "Palam Vihar Extension", "Pataudi", "Pataudi Road", "Patel Nagar", "Rajendra Park", "Rajiv Chowk", "Ratan Vihar",
            "Rosewood City", "Sadar Bazar", "Sector 1", "Sector 2", "Sector 3", "Sector 3A", "Sector 4", "Sector 5", "Sector 6", "Sector 7",
            "Sector 9", "Sector 9A", "Sector 10", "Sector 10A", "Sector 11", "Sector 12", "Sector 12A", "Sector 14", "Sector 15", "Sector 15 Part 1",
            "Sector 15 Part 2", "Sector 17", "Sector 17A", "Sector 17B", "Sector 18", "Sector 21", "Sector 22", "Sector 22A", "Sector 23", "Sector 23A",
            "Sector 24", "Sector 25", "Sector 26", "Sector 27", "Sector 28", "Sector 29", "Sector 30", "Sector 31", "Sector 32", "Sector 33",
            "Sector 34", "Sector 35", "Sector 37", "Sector 37C", "Sector 37D", "Sector 38", "Sector 39", "Sector 40", "Sector 41", "Sector 42",
            "Sector 43", "Sector 44", "Sector 45", "Sector 46", "Sector 47", "Sector 48", "Sector 49", "Sector 50", "Sector 51", "Sector 52",
            "Sector 53", "Sector 54", "Sector 55", "Sector 56", "Sector 57", "Sector 58", "Sector 59", "Sector 60", "Sector 61", "Sector 62",
            "Sector 63", "Sector 63A", "Sector 64", "Sector 65", "Sector 66", "Sector 67", "Sector 67A", "Sector 68", "Sector 69", "Sector 70",
            "Sector 70A", "Sector 71", "Sector 72", "Sector 73", "Sector 74", "Sector 74A", "Sector 75", "Sector 76", "Sector 77", "Sector 78",
            "Sector 79", "Sector 80", "Sector 81", "Sector 81A", "Sector 82", "Sector 82A", "Sector 83", "Sector 84", "Sector 85", "Sector 86",
            "Sector 87", "Sector 88", "Sector 88A", "Sector 88B", "Sector 89", "Sector 89A", "Sector 89B", "Sector 90", "Sector 91", "Sector 92",
            "Sector 93", "Sector 94", "Sector 95", "Sector 95A", "Sector 95B", "Sector 99", "Sector 99A", "Sector 102", "Sector 103", "Sector 104",
            "Sector 105", "Sector 106", "Sector 107", "Sector 108", "Sector 109", "Sector 110", "Sector 110A", "Sector 111", "Sector 112", "Sector 113",
            "Sector 114", "Sector 115", "Sheetla Colony", "Sheetla Mata Road", "Shivaji Nagar", "Sohna", "Sohna Road", "South City 1", "South City 2", "Southern Peripheral Road (SPR)",
            "Subhash Nagar", "Sun City", "Surat Nagar", "Sushant Lok 1", "Sushant Lok 2", "Sushant Lok 3", "Udyog Vihar Phase 1", "Udyog Vihar Phase 2", "Udyog Vihar Phase 3", "Udyog Vihar Phase 4",
            "Udyog Vihar Phase 5", "Uppal Southend", "Vatika City", "Vipul World", "Wazirabad"
        ],
        "faridabad": ["Sector 14", "Sector 15", "Sector 16", "Sector 21C", "Sector 28", "Sector 37", "Greenfield Colony", "Neharpar", "NIT Faridabad", "Surajkund Road"],
        "new delhi": ["Connaught Place", "Chanakyapuri", "Barakhamba", "Khan Market", "Lutyens Delhi", "Panchsheel Park", "Golf Links", "Jor Bagh"],
        "south delhi": ["Hauz Khas", "Saket", "Greater Kailash 1", "Greater Kailash 2", "Green Park", "Defence Colony", "Lajpat Nagar", "Malviya Nagar", "Vasant Kunj", "Vasant Vihar", "South Extension", "Gulmohar Park", "Alaknanda", "Kalkaji", "Okhla"],
        "noida": [
            "Agahpur", "Amrapali", "Arun Vihar", "Atta Market", "Bahlolpur", "Baraula", "Barola", "Bhangel", "Bishanpura", "Botanical Garden Area", "Brahmaputra Market", "Buddh Vihar", "Central Noida", "Chhalera", "Chhaprauli", "Chotpur", "City Centre", "Dadri Road", "Dalit Prerna Sthal Area", "Defence Colony Area", "DND Flyway Area", "Film City", "Garhi Chaukhandi", "Gejha", "Gijhore", "Golf Course Road", "Harola", "Hindon Vihar", "Hoshiarpur", "Hosiery Complex", "Jal Vihar", "Jhundpura", "Kachhera", "Kakrala", "Kalyanpur", "Kanarsi", "Khora Colony", "Kondli Border", "Kulesara", "Lajpat Nagar", "Logix City Centre Area", "Lotus Boulevard Area", "Mahagun Area", "Maharishi Nagar", "Mamura", "Mamura Industrial Area", "Metro Hospital Area", "Morna", "Nagli Sakrawati", "Naya Bans", "Nithari", "Noida Expressway", "Noida Extension Border", "Pali", "Parthala", "Phase 2 Industrial Area", "Phase 3", "Pusta Road", "Raipur Khadar", "Rajat Vihar", "Rajendra Nagar", "Rajnigandha Chowk Area", "Rasoolpur Nawada", "Sadarpur", "Salarpur", "Taj Highway Area", "Transport Nagar", "Udayagiri", "Udyog Marg", "UPSIDC Industrial Area", "Vishwakarma Road",
            "Sector 1", "Sector 2", "Sector 3", "Sector 4", "Sector 5", "Sector 6", "Sector 7", "Sector 8", "Sector 9", "Sector 10",
            "Sector 11", "Sector 12", "Sector 14", "Sector 14A", "Sector 15", "Sector 15A", "Sector 16", "Sector 16A (Film City)", "Sector 16B", "Sector 17",
            "Sector 18", "Sector 19", "Sector 20", "Sector 21", "Sector 21A", "Sector 22", "Sector 23", "Sector 24", "Sector 25", "Sector 26",
            "Sector 27", "Sector 28", "Sector 29", "Sector 30", "Sector 31", "Sector 32", "Sector 33", "Sector 34", "Sector 35", "Sector 36",
            "Sector 37", "Sector 38", "Sector 38A", "Sector 39", "Sector 40", "Sector 41", "Sector 42", "Sector 43", "Sector 44", "Sector 45",
            "Sector 46", "Sector 47", "Sector 48", "Sector 49", "Sector 50", "Sector 51", "Sector 52", "Sector 53", "Sector 54", "Sector 55",
            "Sector 56", "Sector 57", "Sector 58", "Sector 59", "Sector 60", "Sector 61", "Sector 62", "Sector 63", "Sector 63A", "Sector 64",
            "Sector 65", "Sector 66", "Sector 67", "Sector 68", "Sector 69", "Sector 70", "Sector 71", "Sector 72", "Sector 73", "Sector 74",
            "Sector 75", "Sector 76", "Sector 77", "Sector 78", "Sector 79", "Sector 80", "Sector 81", "Sector 82", "Sector 83", "Sector 84",
            "Sector 85", "Sector 86", "Sector 87", "Sector 88", "Sector 89", "Sector 90", "Sector 91", "Sector 92", "Sector 93", "Sector 93A",
            "Sector 93B", "Sector 94", "Sector 95", "Sector 96", "Sector 97", "Sector 98", "Sector 99", "Sector 100", "Sector 101", "Sector 102",
            "Sector 104", "Sector 105", "Sector 106", "Sector 107", "Sector 108", "Sector 109", "Sector 110", "Sector 111", "Sector 112",
            "Sector 113", "Sector 115", "Sector 116", "Sector 117", "Sector 118", "Sector 119", "Sector 120", "Sector 121", "Sector 122",
            "Sector 123", "Sector 124", "Sector 125", "Sector 126", "Sector 127", "Sector 128", "Sector 129", "Sector 130", "Sector 131",
            "Sector 132", "Sector 133", "Sector 134", "Sector 135", "Sector 136", "Sector 137", "Sector 138", "Sector 140", "Sector 140A",
            "Sector 141", "Sector 142", "Sector 143", "Sector 143B", "Sector 144", "Sector 145", "Sector 146", "Sector 147", "Sector 148",
            "Sector 149", "Sector 150", "Sector 151", "Sector 152", "Sector 153", "Sector 154", "Sector 155", "Sector 156", "Sector 157",
            "Sector 158", "Sector 159", "Sector 160", "Sector 161", "Sector 162", "Sector 163", "Sector 164", "Sector 165", "Sector 166",
            "Sector 167", "Sector 168"
        ],
        "gautam buddha nagar": [
            "Sector 1", "Sector 15", "Sector 18", "Sector 50", "Sector 62", "Sector 75", "Sector 76", "Sector 78", "Sector 93A", "Sector 128", "Sector 137", "Sector 150", "Noida Expressway", "Greater Noida", "Noida Extension", "Pari Chowk", "Knowledge Park"
        ],
        "greater noida": ["Knowledge Park", "Pari Chowk", "Alpha 1", "Alpha 2", "Beta 1", "Beta 2", "Gamma 1", "Delta 1", "Omnicron", "Zeta", "Greater Noida West", "Techzone 4"],
        "bilaspur": [
            "Adarsh Nagar", "Agrasen Chowk", "Ameri", "Anand Nagar", "Anjani Nagar", "Arpa Colony", "Ashok Nagar", "Ashok Vihar", "Ayodhya Nagar", "Bahatarai", "Bahatarai Road", "Balmiki Nagar", "Bannak Chowk", "Basant Vihar", "Beharikhar", "Belpan", "Bilha Road", "Bilaspur Railway Colony", "Birkona", "Bodri", "Brahm Vihar", "Budhwari Bazar", "Bus Stand Area", "Central University Area", "Chakarbhatha", "Chandela Nagar", "Chandni Chowk", "Chingrajpara", "CIMS Area", "Civil Lines", "Dabripara", "Dayalband", "Deendayal Nagar", "Deorikhurd", "Devendra Nagar", "Dindayal Colony", "Durganagar", "Gandhi Chowk", "Ganesh Nagar", "Ganga Nagar", "Gol Bazar", "Gondpara", "Gopal Nagar", "Gudiyari", "Gurughasi Das Nagar", "Hemu Nagar", "Hirapur", "Housing Board Colony", "Imlipara", "Indira Colony", "Indu Udyan Area", "Industrial Area Bilaspur", "Jarhabhatha", "Juna Bilaspur", "Junwani", "Khamtarai", "Koni", "Koni Road", "Kranti Nagar", "Kududand", "Kumharpara", "Kusmunda Road", "Kutchery Chowk", "Lingiyadih", "Link Road", "Lormi Road Area", "Maharana Pratap Nagar", "Mangla", "Mangla Chowk", "Masturi Road", "Minimata Nagar", "Mission Hospital Area", "Mopka", "Mungeli Road", "Narmada Nagar", "Naya Para", "Nehru Chowk", "Nehru Nagar", "New Sarkanda", "Nutan Colony", "Panchsheel Nagar", "Patel Nagar", "Pendra Road Area", "Pragati Nagar", "Priyadarshini Nagar", "Pushkar Colony", "Railway Colony", "Railway Station Area", "Rajiv Plaza Area", "Rajiv Vihar", "Rajendra Nagar", "Rajkishore Nagar", "Ratanpur Road", "Ravi Shankar Shukla Nagar", "Ring Road", "Rishabh Nagar", "Sarkanda", "Seepat Industrial Area", "Seepat Road", "Shankar Nagar", "Shanti Nagar", "Shubham Vihar", "Sindhi Colony", "Sipat", "Sirgitti", "Sirgitti Industrial Area", "Son Ganga Colony", "Subhash Nagar", "Talapara", "Tarbahar", "Telipara", "Tifra", "Tifra Industrial Area", "Tikrapara", "Tilak Nagar", "Torwa", "Urja Nagar", "Uslapur", "Uslapur Railway Area", "Vasant Vihar", "Vinoba Nagar", "Vivekanand Nagar", "Vyapar Vihar"
        ],
        "chhindwara": [
            "Ajaniya", "Bamhani", "Bangaon", "Bararipura", "Bhaisadand", "Bhajipani", "Bhanadehi", "Bhoola Mohgaon", "Bhutera", "Bijepani", "Bohna", "Boriya", "Chandangaon", "Chanhiyakala", "Chanhiyakhurd", "Chargaon", "Chargaon Prahlad", "Chhindwara Sanchar Colony", "Chitrakut Complex", "Co-operative Bank Colony", "Dangawani Pipariya", "Dhamaniya", "Gandhi Ganj", "Ganesh Colony", "Gangiwada", "Ghatparasia", "Gondra", "Gulabara", "Gurraiya", "Housing Board Colony", "Indira Colony", "Janta Colony", "Kukda Jagat", "Lal Baugh", "Nai Abadi", "Police Line", "Professor's Colony", "Samta Colony", "Shivam Sundaram Colony", "Shrivastav Colony", "Sinchai Colony", "Teacher's Colony", "Vivekanand Colony"
        ],
        "damoh": [
            "Aamchopra", "Adarsh Nagar", "Anand Nagar", "Ashok Nagar", "Bada Bazar", "Bajariya", "Balakot", "Bandakpur Road", "Bandakpur Road Area", "Banshankari Nagar", "Bharat Nagar", "Bus Stand Area", "Choubey Colony", "City Kotwali Area", "Civil Lines", "College Area", "Damoh Railway Colony", "Deendayal Chowk", "Deendayal Nagar", "Dhanushdhari Nagar", "District Hospital Area", "Dubey Colony", "Galla Mandi Area", "Gandhi Chowk", "Gandhi Nagar", "Ghanta Ghar", "Gopal Nagar", "Gyanoday Nagar", "Harsh Nagar", "Hatta Road", "Hatta Road Area", "Hirde Nagar", "Housing Board Colony", "Imlai Road Area", "Indira Colony", "Industrial Area", "Itwari Bazar", "Jabalpur Road", "Jabalpur Road Industrial Area", "Jai Nagar", "Jain Nagar", "Jatashankar Area", "Jawahar Nagar", "Kachhari Chowk", "Kanchan Nagar", "Kanchan Vihar", "Katra Bazaar", "Khamaria Road Area", "Khermai Road Area", "Killai Naka", "Krishna Nagar", "Labour Colony", "Lakshmi Nagar", "Lalbagh Area", "LIG Colony", "Madiya Mohalla", "Mahakali Nagar", "Maharana Pratap Nagar", "Malaiya Nagar", "Manas Nagar", "Mandi Area", "Mangal Nagar", "Mission Hospital Area", "Model Town", "Mungeli Road", "Mungeli Road Area", "Naya Bazaar", "Nehru Chowk", "Nehru Nagar", "New Housing Board Colony", "Nutan Colony", "Panna Road", "Panna Road Area", "Patel Nagar", "Police Line Area", "Pragati Nagar", "Priyadarshini Nagar", "Purana Bazaar", "Purani Damoh", "Raghunath Nagar", "Railway Colony", "Railway Station Area", "Raj Palace Area", "Rajendra Nagar", "Ram Nagar", "Ramkrishna Nagar", "Rani Kamlapati Nagar", "Ratan Nagar", "Sadar Bazaar", "Sagar Road", "Sagar Road Area", "Santoshi Nagar", "Saraswati Nagar", "Shankar Nagar", "Shanti Nagar", "Shastri Nagar", "Shiv Nagar", "Shivaji Nagar", "Sindhi Colony", "Station Road", "Subhash Chowk", "Subhash Colony", "Surya Nagar", "Tagore Nagar", "Talaiya Mohalla", "Tendukheda Road", "Tendukheda Road Area", "Tilak Nagar", "Town Hall Area", "Transport Nagar", "Uday Nagar", "Uma Nagar", "University Area", "Vaishali Nagar", "Vardhman Nagar", "Vijay Nagar", "Vinoba Nagar", "Vivekanand Nagar"
        ],
        "raipur": [
            "Aadarsh Nagar", "Aashiana Colony", "Abhanpur Road", "Amanaka", "Amaseoni", "Amlidih", "Ashirwad Colony", "Ashoka Park", "Ashoka Ratan", "Avanti Vihar", "Avinash Nagar", "Bhanpuri", "Bhanpuri Industrial Area", "Bharat Mata Chowk", "Bhatagaon", "Bhatagaon Chowk", "Bhilai Nagar Road", "Boria Kalan", "Boriyakhurd", "Budhapara", "Bundeli", "Byron Bazar", "Canal Road Area", "Central Avenue", "Changorabhatha", "Chhotapara", "Choubey Colony", "City Center Area", "Civil Lines", "Daldal Seoni", "DDU Nagar", "Deopuri", "Devendra Nagar", "Dhamtari Road", "Dumartarai", "Dunda", "Ekatm Parisar Area", "Ekta Nagar", "Engineering College Road", "Gandhi Nagar", "Gaurav Path", "Gayatri Nagar", "GE Road", "Gogaon", "Gogaon Industrial Area", "Gol Bazar", "Govind Nagar", "Gudhiyari", "Hemu Nagar", "Hirapur", "Hirmi", "Housing Board Colony", "I.G.V.P. Campus Area", "Indira Nagar", "Indraprastha", "Industrial Area", "Jagriti Nagar", "Jai Hind Nagar", "Jawahar Nagar", "Jivan Vihar", "Jora", "Jora Road", "Kabir Nagar", "Kachhari Chowk", "Kachna", "Kachna Road", "Katora Talab", "Khamardih", "Khamtarai", "Kota", "Kumhari Road", "Kushalpur", "Labhandi", "Lakhe Nagar", "Lalpur", "Laxmi Nagar", "Laxmi Nagar Extension", "Laxmi Vihar", "Lodhi Para", "Mahadev Ghat Road", "Mahamaya Nagar", "Mahaveer Nagar", "Mana", "Mana Road", "Mandir Hasaud", "Maruti Nagar", "Mathpurena", "Mohaba Bazar", "Motibagh", "Moudhapara", "Mowa", "Nandanvan", "Nardaha", "Nava Raipur Atal Nagar", "Navin Bazar", "Naya Raipur", "Nehru Nagar", "New Purena", "New Rajendra Nagar", "New Shanti Nagar", "Pachedi", "Pachpedi Naka", "Pandri", "Parvati Nagar", "Patel Para", "Priyadarshini Nagar", "Professor Colony", "Pujari Nagar", "Purani Basti", "Purena", "Raipur Railway Station Area", "Rajendra Nagar", "Ravigram", "Ravi Nagar", "Ravishankar Shukla University Area", "Rawanbhatha", "Rawabhata Industrial Area", "RDA Colony", "Ring Road", "Rishabh Nagar", "Rohinipuram", "Sadar Bazar", "Saddu", "Saddu Road", "Samta Colony", "Samta Colony Extension", "Santoshi Nagar", "Sarona", "Sector 27", "Sector 29", "Sector 30", "Sector 31", "Sejbahar", "Shailendra Nagar", "Shankar Nagar", "Shankar Nagar Extension", "Shanti Nagar", "Siltara", "Siltara Industrial Area", "Sunder Nagar", "Tatibandh", "Tatibandh Industrial Area", "Tatyapara", "Telibandha", "Telibandha Lake Area", "Telibandha Road", "Tikrapara", "Tilda Road", "Transport Nagar", "Udyan Nagar", "Urla", "Urla Industrial Area", "Vidhan Sabha Road", "Vinayak City", "VIP Road", "Vishal Nagar", "Vivekanand Nagar", "Vardhman Nagar", "Wallfort City Area", "WRS Colony"
        ],
        "ludhiana": [
            "Aarti Chowk Area", "Aaya Nagar", "Abhiwahan Nagar", "Aggar Nagar", "Aman Nagar", "Ambedkar Nagar", "Anand Nagar", "Ansal Enclave", "Arya Nagar", "Atam Nagar", "Ayali Kalan", "Ayali Khurd", "B.R.S. Nagar", "Bahadur Ke Road", "Barewal", "Barewal Road", "Basant Avenue", "Basant Nagar", "Basant Vihar", "Basti Jodhewal", "Basti Sheikh", "Bhagwan Nagar", "Bhai Bala Colony", "Bhai Himmat Singh Nagar", "Bhai Randhir Singh Nagar", "Bhamian Kalan", "Bhamian Khurd", "Bharat Nagar", "Bhattian", "Block A Sarabha Nagar", "Block B Sarabha Nagar", "Block C Sarabha Nagar", "Block D Sarabha Nagar", "Central Town", "Chander Nagar", "Chandigarh Road", "Chaura Bazar", "Cheema Chowk", "Chet Singh Nagar", "Civil Lines", "Clock Tower Area", "College Road", "Daresi", "Dashmesh Nagar", "Deep Nagar", "Dhandari Kalan", "Dhandari Khurd", "Dholewal", "Division No. 3 Area", "DMC Colony", "Dugri", "Dugri Road", "Dugri Urban Estate", "Ferozepur Road", "Ferozepur Road Area", "Field Ganj", "Focal Point", "Focal Point Phase 1", "Focal Point Phase 2", "Focal Point Phase 3", "Focal Point Phase 4", "Focal Point Phase 5", "Focal Point Phase 6", "Friends Colony", "G.T. Road", "Gaunspur", "Ghumar Mandi", "Giaspura", "Gill Chowk", "Gill Road", "Gill Road Industrial Area", "Gobind Nagar", "Gobindgarh", "Guru Arjan Dev Nagar", "Guru Nanak Nagar", "Guru Ram Das Nagar", "Haibowal Kalan", "Haibowal Khurd", "Hambran Road", "Hargobind Nagar", "Housing Board Colony", "Humbran Road Area", "Hussainpura", "Industrial Area", "Industrial Area A", "Industrial Area B", "Industrial Area C", "Industrial Area D", "Iqbal Nagar", "Ishwar Nagar", "Jamalpur", "Jamalpur Colony", "Jamalpur Industrial Area", "Janakpuri", "Jassian", "Jassian Road", "Jawaddi", "Jawaddi Kalan", "Jawaddi Khurd", "Joshi Nagar", "Kailash Nagar", "Kakowal", "Kakowal Road", "Kanganwal", "Kapoorthala Road", "Karabara", "Kesar Ganj", "Khanna Road", "Khud Mohalla", "Kidwai Nagar", "Kitchlu Nagar", "Kohara", "Krishna Nagar", "Kundan Puri", "Lajpat Nagar", "Lalton Kalan", "Lalton Khurd", "LIG Colony", "Link Road", "Ludhiana Junction Area", "Maharaj Nagar", "Mahavir Nagar", "Maya Nagar", "Meharban", "Miller Ganj", "Model Gram", "Model Town", "Model Town Extension", "Moon Nagar", "Moti Nagar", "Mundian Kalan", "Mundian Khurd", "Munshi Nagar", "Nanak Nagar", "Nanaksar", "New Aman Nagar", "New Haibowal", "New Janta Nagar", "New Kitchlu Nagar", "New Model Town", "New Prem Nagar", "New Rajguru Nagar", "New Shiv Puri", "New Subhash Nagar", "Noorwala Road", "P.A.U. Campus", "Pakhowal Road", "Partap Nagar", "PAU Area", "Pawan Nagar", "Phase 1 Urban Estate Dugri", "Phase 2 Urban Estate Dugri", "Phase 3 Urban Estate Dugri", "Preet Nagar", "Pritam Nagar", "Punjabi Bagh", "R.K. Road", "Raghunath Enclave", "Rahon Road", "Railway Colony", "Railway Station Area", "Rajendra Nagar", "Rajguru Nagar", "Ranjit Nagar", "Rasila Nagar", "Rattan Nagar", "Rishi Nagar", "Rose Garden Area", "Sant Ishar Singh Nagar", "Sarabha Nagar", "Sarabha Nagar Extension", "Sector 32", "Sector 39", "Sector 40", "Sector 41", "Sector 42", "Sector 43", "Shaheed Bhagat Singh Nagar", "Shakti Nagar", "Shastri Nagar", "Shimlapuri", "Shiv Puri", "Shivaji Nagar", "Sidhwan Bet Road", "South City", "South City Extension", "Sukhdev Nagar", "Sunder Nagar", "Sunder Nagar Extension", "Tagore Nagar", "Tajpur Road", "Tazpur Road Area", "Thakkarwal", "Tibba Road", "Transport Nagar", "Turi Road", "Urban Estate Dugri", "Urban Estate Phase 1", "Urban Estate Phase 2", "Urban Estate Phase 3", "Urban Estate Phase 4", "Vardhman Nagar", "Vasant Vihar", "Veer Nagar", "Vijay Nagar", "Vikas Nagar", "Village Varpal", "Vishal Nagar", "Ward No. Areas", "West End", "Whitefield"
        ],
        "gwalior": [
            "Achaleshwar Nagar", "Aditya Puram", "Airport Area", "Airport Road", "Anand Nagar", "Arjun Nagar", "Ashok Colony", "Ashok Vihar", "Awash Colony", "Badagaon", "Bahodapur", "Balwant Nagar", "Basant Vihar", "Bhind Road Area", "Bhopal Colony", "Birla Nagar", "C.P. Colony", "Chandr Nagar", "Char Shahar Ka Naka Area", "City Centre", "D.D. Nagar", "Dabra Road Area", "Darpan Colony", "Deen Dayal Colony", "Deen Dayal Nagar", "Dongarpur", "Gandhi Nagar", "Ghas Mandi", "Gole Ka Mandir", "Gole Ka Mandir Area", "Govardhan Colony", "Govindpuri", "Green Hills", "Gulmohar City", "Hariom Colony", "Hazira", "Hospital Road Area", "Housing Board Colony", "Hurawali", "Jaderua", "Jangipura", "Jhansi Link Road", "Jhansi Road Area", "Jiwaji Ganj", "Kampu", "Kedarpur", "Kheriya Mirdha", "Kotra Sultanabad Area", "Kuleshwar Nagar", "Lashkar", "Lashkar East", "Lashkar West", "Laxmi Ganj", "Lohamandi", "Madhav Nagar", "Madhuban Colony", "Mahalgaon", "Maharajpura", "Model Town", "Morar", "Morar Bazaar Area", "Moti Jheel Area", "Nai Sadak Area", "Naka Chandravadni", "Naya Bazaar", "New City Centre", "New City Centre Extension", "New Govindpuri", "New Thatipur", "Panchsheel Nagar", "Patel Nagar", "Phool Bagh Area", "Pinto Park", "Police Line", "Prem Nagar", "Purani Chhawani", "Raghavpuram", "Railway Colony", "Railway Station Area", "Rajeev Nagar", "Ramji Ka Pura", "Residency Road Area", "Sachin Tendulkar Marg Area", "Sarafa Bazaar Area", "Shanti Nagar", "Shinde Ki Chhawani", "Shiv Colony", "Shivpuri Link Road Area", "Sikandar Kampoo", "Sirol", "Sirol Road", "Suresh Nagar Colony", "Surya Nagar", "Tansen Nagar", "Thatipur", "Tighra Road Area", "Transport Nagar", "University Area", "University Road", "Vijay Nagar", "Vinay Nagar", "Vivek Nagar"
        ],
        "patna": [
            "Adalatganj", "Anandpuri", "Anisabad", "Arya Kumar Road", "Ashiana Nagar", "Ashok Nagar", "Ashok Rajpath", "Bahadurpur", "Bahadurpur Housing Colony", "Bailey Road", "Bakarganj", "Bankman Colony", "Bazar Samiti", "Beldarichak", "Beur", "Bhikhna Pahari", "Bhootnath Road", "Boring Canal Road", "Boring Road", "Buddha Colony", "Chajju Bagh", "Chiraiyatand", "Chitragupta Nagar", "Chhoti Pahari", "Chowk", "Civil Lines", "Dak Bunglow Road", "Danapur", "Digha", "Digha Ghat", "Digha-Ashiana Road", "Dujra", "Ekangarsarai Road Area", "Exhibition Road", "Fraser Road", "Fraser Road Area", "Fraser Road Extension", "Friends Colony", "Fulwari Sharif", "Gandhi Maidan", "Gandhi Nagar", "Gardanibagh", "Gaurichak", "Gola Road", "Gola Road Area", "Golambar", "Gopalpur", "Gulzarbagh", "Hajiganj", "Hanuman Nagar", "Haroon Nagar", "Hathua", "Hathua Market", "Housing Board Colony", "IAS Colony", "Income Tax Colony", "Indrapuri", "Jaganpura", "Jagdeo Path", "Jai Prakash Nagar", "Jakkanpur", "Jamal Road", "Kadamkuan", "Kankarbagh", "Kankarbagh Main Road", "Khagaul", "Khajpura", "Khemnichak", "Kidwaipuri", "Kumhrar", "Kurji", "Kurji More", "Lodipur", "Lohanipur", "Lohia Nagar", "Lok Nayak Jayaprakash Nagar", "Machhua Toli", "Mahendru", "Mahendru Ghat", "Mainpura", "Marufganj", "Maurya Lok", "Mithapur", "Mithapur Bus Stand Area", "Nageshwar Colony", "Nala Road", "Nehru Nagar", "New Dak Bunglow Road", "New Jakkanpur", "New Patliputra Colony", "North Mandiri", "Pahari", "Pataliputra", "Patliputra Colony", "Patliputra Industrial Area", "Patna City", "Patna Market", "Patna Sahib", "Patna University Area", "Phulwari Sharif", "Police Colony", "Postal Park", "Punaichak", "Raja Bazar", "Rajapur", "Rajbansi Nagar", "Rajendra Nagar", "Rajiv Nagar", "Ram Krishna Nagar", "Ramkrishna Nagar", "RPS More", "Rukanpura", "Sabzibagh", "Saguna More", "Saidpur", "Sampatchak", "Sandalpur", "Shastri Nagar", "Sheikhpura", "Shivpuri", "Sipara", "SK Puri", "South Mandiri", "Sri Krishna Puri", "Sultanganj", "Takht Sri Harmandir Sahib Area", "Transport Nagar", "Tripolia", "Uma Nagar", "Vijay Nagar", "Vishwa Vidyalaya Road Area"
        ],
        "bengaluru": [
            "AECS Layout", "Akshayanagar", "Amruthahalli", "Annapurneshwari Nagar", "Anjanapura", "Arekere", "Attibele", "Attiguppe", "Avalahalli", "Avenue Road", "Bagalur", "Balagere", "Banashankari", "Banaswadi", "Bannerghatta Road", "Basavanagudi", "Basaveshwaranagar", "Battarahalli", "Begur", "Bellandur", "Benson Town", "Bidadi", "Bilekahalli", "Bommanahalli", "Bommasandra", "Brigade Road", "Brookefield", "BTM 1st Stage", "BTM 2nd Stage", "BTM Layout", "Byatarayanapura", "Channasandra", "Chandra Layout", "Chandapura", "Chickpet", "Chikkajala", "Chikkalasandra", "Cox Town", "Cunningham Road", "CV Raman Nagar", "Deepanjali Nagar", "Devanahalli", "Devarabeesanahalli", "Doddaballapur Road", "Doddakallasandra", "Doddanekkundi", "Dollars Colony", "Domlur", "Electronic City", "Electronic City Phase 1", "Electronic City Phase 2", "Frazer Town", "Gandhi Nagar", "Ganganagar", "Gottigere", "Gunjur", "HAL", "Haralur", "Hebbal", "Hennur", "Hennur Road", "Herohalli", "High Grounds", "Hoodi", "Hope Farm", "Horamavu", "HSR Layout", "HSR Layout Sector 1", "HSR Layout Sector 2", "HSR Layout Sector 3", "HSR Layout Sector 4", "HSR Layout Sector 5", "HSR Layout Sector 6", "Hulimavu", "Immadihalli", "Indiranagar", "ISRO Layout", "ITPL", "Jakkur", "Jalahalli", "Jalahalli East", "Jalahalli West", "Jayanagar", "Jigani", "JP Nagar", "Kadubeesanahalli", "Kadugodi", "Kaggadasapura", "Kalasipalya", "Kalkere", "Kalyan Nagar", "Kamakshipalya", "Kammanahalli", "Kanakapura Road", "Kasturi Nagar", "Kengeri", "Kengeri Satellite Town", "Kodigehalli", "Konanakunte", "Koramangala", "Koramangala 1st Block", "Koramangala 2nd Block", "Koramangala 3rd Block", "Koramangala 4th Block", "Koramangala 5th Block", "Koramangala 6th Block", "Koramangala 7th Block", "Kottigepalya", "KR Puram", "Kumaraswamy Layout", "Kumbalgodu", "Kundalahalli", "Laggere", "Magadi Road", "Mahadevapura", "Mahalakshmi Layout", "Majestic", "Malleshwaram", "Marathahalli", "Mathikere", "MG Road", "Murugeshpalya", "Mysore Road", "Nagarbhavi", "Nagawara", "Nallurhalli", "Nandini Layout", "Nayandahalli", "New BEL Road", "Old Airport Road", "Outer Ring Road", "Padmanabhanagar", "Palace Road", "Panathur", "Peenya", "Raghuvanahalli", "Rajajinagar", "Rajarajeshwari Nagar", "Ramamurthy Nagar", "Residency Road", "Richmond Road", "Richmond Town", "RMV Extension", "RR Nagar", "RT Nagar", "Sahakar Nagar", "Sanjay Nagar", "Sarjapur", "Sarjapur Road", "Seshadripuram", "Shivajinagar", "Siddapura", "Silk Board", "Subramanyapura", "Sunkadakatte", "Talaghattapura", "Thanisandra", "Turahalli", "Ulsoor", "Uttarahalli", "Vajarahalli", "Varthur", "Vasanth Nagar", "Vidyaranyapura", "Vijayanagar", "Whitefield", "Whitefield Main Road", "Yelahanka", "Yelachenahalli", "Yemalur", "Yeshwanthpur"
        ],
        "bangalore": [
            "AECS Layout", "Akshayanagar", "Amruthahalli", "Annapurneshwari Nagar", "Anjanapura", "Arekere", "Attibele", "Attiguppe", "Avalahalli", "Avenue Road", "Bagalur", "Balagere", "Banashankari", "Banaswadi", "Bannerghatta Road", "Basavanagudi", "Basaveshwaranagar", "Battarahalli", "Begur", "Bellandur", "Benson Town", "Bidadi", "Bilekahalli", "Bommanahalli", "Bommasandra", "Brigade Road", "Brookefield", "BTM 1st Stage", "BTM 2nd Stage", "BTM Layout", "Byatarayanapura", "Channasandra", "Chandra Layout", "Chandapura", "Chickpet", "Chikkajala", "Chikkalasandra", "Cox Town", "Cunningham Road", "CV Raman Nagar", "Deepanjali Nagar", "Devanahalli", "Devarabeesanahalli", "Doddaballapur Road", "Doddakallasandra", "Doddanekkundi", "Dollars Colony", "Domlur", "Electronic City", "Electronic City Phase 1", "Electronic City Phase 2", "Frazer Town", "Gandhi Nagar", "Ganganagar", "Gottigere", "Gunjur", "HAL", "Haralur", "Hebbal", "Hennur", "Hennur Road", "Herohalli", "High Grounds", "Hoodi", "Hope Farm", "Horamavu", "HSR Layout", "HSR Layout Sector 1", "HSR Layout Sector 2", "HSR Layout Sector 3", "HSR Layout Sector 4", "HSR Layout Sector 5", "HSR Layout Sector 6", "Hulimavu", "Immadihalli", "Indiranagar", "ISRO Layout", "ITPL", "Jakkur", "Jalahalli", "Jalahalli East", "Jalahalli West", "Jayanagar", "Jigani", "JP Nagar", "Kadubeesanahalli", "Kadugodi", "Kaggadasapura", "Kalasipalya", "Kalkere", "Kalyan Nagar", "Kamakshipalya", "Kammanahalli", "Kanakapura Road", "Kasturi Nagar", "Kengeri", "Kengeri Satellite Town", "Kodigehalli", "Konanakunte", "Koramangala", "Koramangala 1st Block", "Koramangala 2nd Block", "Koramangala 3rd Block", "Koramangala 4th Block", "Koramangala 5th Block", "Koramangala 6th Block", "Koramangala 7th Block", "Kottigepalya", "KR Puram", "Kumaraswamy Layout", "Kumbalgodu", "Kundalahalli", "Laggere", "Magadi Road", "Mahadevapura", "Mahalakshmi Layout", "Majestic", "Malleshwaram", "Marathahalli", "Mathikere", "MG Road", "Murugeshpalya", "Mysore Road", "Nagarbhavi", "Nagawara", "Nallurhalli", "Nandini Layout", "Nayandahalli", "New BEL Road", "Old Airport Road", "Outer Ring Road", "Padmanabhanagar", "Palace Road", "Panathur", "Peenya", "Raghuvanahalli", "Rajajinagar", "Rajarajeshwari Nagar", "Ramamurthy Nagar", "Residency Road", "Richmond Road", "Richmond Town", "RMV Extension", "RR Nagar", "RT Nagar", "Sahakar Nagar", "Sanjay Nagar", "Sarjapur", "Sarjapur Road", "Seshadripuram", "Shivajinagar", "Siddapura", "Silk Board", "Subramanyapura", "Sunkadakatte", "Talaghattapura", "Thanisandra", "Turahalli", "Ulsoor", "Uttarahalli", "Vajarahalli", "Varthur", "Vasanth Nagar", "Vidyaranyapura", "Vijayanagar", "Whitefield", "Whitefield Main Road", "Yelahanka", "Yelachenahalli", "Yemalur", "Yeshwanthpur"
        ],
        "bengaluru-urban": [
            "AECS Layout", "Akshayanagar", "Amruthahalli", "Annapurneshwari Nagar", "Anjanapura", "Arekere", "Attibele", "Attiguppe", "Avalahalli", "Avenue Road", "Bagalur", "Balagere", "Banashankari", "Banaswadi", "Bannerghatta Road", "Basavanagudi", "Basaveshwaranagar", "Battarahalli", "Begur", "Bellandur", "Benson Town", "Bidadi", "Bilekahalli", "Bommanahalli", "Bommasandra", "Brigade Road", "Brookefield", "BTM 1st Stage", "BTM 2nd Stage", "BTM Layout", "Byatarayanapura", "Channasandra", "Chandra Layout", "Chandapura", "Chickpet", "Chikkajala", "Chikkalasandra", "Cox Town", "Cunningham Road", "CV Raman Nagar", "Deepanjali Nagar", "Devanahalli", "Devarabeesanahalli", "Doddaballapur Road", "Doddakallasandra", "Doddanekkundi", "Dollars Colony", "Domlur", "Electronic City", "Electronic City Phase 1", "Electronic City Phase 2", "Frazer Town", "Gandhi Nagar", "Ganganagar", "Gottigere", "Gunjur", "HAL", "Haralur", "Hebbal", "Hennur", "Hennur Road", "Herohalli", "High Grounds", "Hoodi", "Hope Farm", "Horamavu", "HSR Layout", "HSR Layout Sector 1", "HSR Layout Sector 2", "HSR Layout Sector 3", "HSR Layout Sector 4", "HSR Layout Sector 5", "HSR Layout Sector 6", "Hulimavu", "Immadihalli", "Indiranagar", "ISRO Layout", "ITPL", "Jakkur", "Jalahalli", "Jalahalli East", "Jalahalli West", "Jayanagar", "Jigani", "JP Nagar", "Kadubeesanahalli", "Kadugodi", "Kaggadasapura", "Kalasipalya", "Kalkere", "Kalyan Nagar", "Kamakshipalya", "Kammanahalli", "Kanakapura Road", "Kasturi Nagar", "Kengeri", "Kengeri Satellite Town", "Kodigehalli", "Konanakunte", "Koramangala", "Koramangala 1st Block", "Koramangala 2nd Block", "Koramangala 3rd Block", "Koramangala 4th Block", "Koramangala 5th Block", "Koramangala 6th Block", "Koramangala 7th Block", "Kottigepalya", "KR Puram", "Kumaraswamy Layout", "Kumbalgodu", "Kundalahalli", "Laggere", "Magadi Road", "Mahadevapura", "Mahalakshmi Layout", "Majestic", "Malleshwaram", "Marathahalli", "Mathikere", "MG Road", "Murugeshpalya", "Mysore Road", "Nagarbhavi", "Nagawara", "Nallurhalli", "Nandini Layout", "Nayandahalli", "New BEL Road", "Old Airport Road", "Outer Ring Road", "Padmanabhanagar", "Palace Road", "Panathur", "Peenya", "Raghuvanahalli", "Rajajinagar", "Rajarajeshwari Nagar", "Ramamurthy Nagar", "Residency Road", "Richmond Road", "Richmond Town", "RMV Extension", "RR Nagar", "RT Nagar", "Sahakar Nagar", "Sanjay Nagar", "Sarjapur", "Sarjapur Road", "Seshadripuram", "Shivajinagar", "Siddapura", "Silk Board", "Subramanyapura", "Sunkadakatte", "Talaghattapura", "Thanisandra", "Turahalli", "Ulsoor", "Uttarahalli", "Vajarahalli", "Varthur", "Vasanth Nagar", "Vidyaranyapura", "Vijayanagar", "Whitefield", "Whitefield Main Road", "Yelahanka", "Yelachenahalli", "Yemalur", "Yeshwanthpur"
        ],
        "mumbai": ["Bandra West", "Bandra East", "Andheri West", "Andheri East", "Juhu", "Powai", "Worli", "Lower Parel", "Colaba", "Dadar", "Malad West", "Goregaon West", "Kandivali", "Borivali", "Santacruz", "Khar West"],
        "pune": [
            "Akurdi", "Alandi Road", "Ambegaon Budruk", "Ambegaon Pathar", "Anand Nagar", "Aundh", "Aundh Road", "Balewadi", "Baner", "Bavdhan", "Bhosari", "Bibwewadi", "Budhwar Peth", "Camp", "Chandan Nagar", "Charholi", "Chinchwad", "Dapodi", "Deccan Gymkhana", "Dehu Road", "Dhankawadi", "Dhanori", "Dhayari", "Dighi", "Erandwane", "Fatima Nagar", "Fursungi", "Ghorpadi", "Gultekdi", "Hadapsar", "Handewadi", "Hinjawadi", "Kalas", "Kalyani Nagar", "Kalyani Nagar Annexe", "Karve Nagar", "Kasba Peth", "Katraj", "Keshav Nagar", "Khadki", "Kharadi", "Kharadi Bypass", "Kondhwa", "Kondhwa Budruk", "Koregaon Park", "Kothrud", "Law College Road", "Lohegaon", "Magarpatta", "Mahalunge", "Mandai", "Manjari", "Market Yard", "Model Colony", "Mohammed Wadi", "Moshi", "Mukund Nagar", "Mundhwa", "Nana Peth", "Nanded City", "Narayan Peth", "Narhe", "NIBM Annexe", "NIBM Road", "Nigdi", "Old Sangvi", "Padmavati", "Parvati", "Pashan", "Phursungi", "Pimple Gurav", "Pimple Nilakh", "Pimple Saudagar", "Pimpri", "Pirangut", "Pisoli", "Prabhat Road", "Punawale", "Rahatani", "Rasta Peth", "Ravet", "Sadashiv Peth", "Sahakar Nagar", "Sangamwadi", "Sangvi", "Senapati Bapat Road", "Shaniwar Peth", "Shivajinagar", "Sinhagad Road", "Sus", "Swargate", "Talegaon Dabhade", "Talegaon MIDC", "Tathawade", "Tingre Nagar", "Undri", "Vadgaon Budruk", "Vadgaon Sheri", "Viman Nagar", "Vishrantwadi", "Wagholi", "Wakad", "Wanowrie", "Warje", "Warje Malwadi", "Yerawada"
        ],
        "hyderabad": [
            "Abids", "Adibatla", "Adikmet", "Airport Road", "Aliabad", "Almasguda", "Alwal", "Amberpet", "Ameenpur", "Ameerpet", "AS Rao Nagar", "Ashok Nagar", "Attapur", "Bachupally", "Bahadurpally", "Bahadurpura", "Bandlaguda", "Banjara Hills", "Barkatpura", "Basheerbagh", "Beeramguda", "Begumpet", "BHEL", "BN Reddy Nagar", "Boduppal", "Bolarum", "Bollaram", "Bongloor", "Borabanda", "Botanical Garden", "Bowenpally", "Bowrampet", "Budvel", "Chaitanyapuri", "Chanchalguda", "Chandanagar", "Chandrayangutta", "Charminar", "Cherlapally", "Chevella Road", "Chikkadpally", "Chintal", "Champapet", "Darulshifa", "Dilsukhnagar", "DLF Cyber City", "Domalguda", "Dundigal", "East Marredpally", "ECIL", "Erragadda", "Falaknuma", "Film Nagar", "Financial District", "Gachibowli", "Gandamguda", "Ghatkesar", "Golconda", "Gundlapochampally", "Habsiguda", "Hafeezpet", "Hayathnagar", "Himayat Nagar", "Himayatsagar", "HITEC City", "HMT Hills", "Hussaini Alam", "Hyder Nagar", "Ibrahimpatnam", "IS Sadan", "Isnapur", "Jagadgirigutta", "JNTU", "Jubilee Hills", "Kachiguda", "Kalapather", "Kapra", "Karkhana", "Karmanghat", "Khairatabad", "Khajaguda", "Kokapet", "Kompally", "Kondapur", "Kothaguda", "Kothapet", "Kothwalguda", "Koti", "KPHB Colony", "Krishna Nagar", "Kukatpally", "Kushaiguda", "Lakdikapul", "Langar Houz", "LB Nagar", "Lingampally", "Madannapet", "Madhapur", "Madinaguda", "Maheshwaram", "Malakpet", "Malkajgiri", "Mallapur", "Mamidipally", "Manikonda", "Marredpally", "Masab Tank", "Medchal", "Medipally", "Mehdipatnam", "Miyapur", "Moghalpura", "Moosarambagh", "Moosapet", "Moula Ali", "Musheerabad", "Nacharam", "Nagaram", "Nagole", "Nallagandla", "Nallakunta", "Nampally", "Nanakramguda", "Narayanguda", "Narsingi", "Nawab Saheb Kunta", "Necklace Road", "Neredmet", "Nizampet", "Old Alwal", "Osman Nagar", "Outer Ring Road", "Padmarao Nagar", "Patancheru", "Peeramcheruvu", "Peerzadiguda", "Pocharam", "Pragathi Nagar", "Punjagutta", "Puppalaguda", "Quthbullapur", "Rahmat Nagar", "Raidurg", "Rajendranagar", "Ramnagar", "Ramanthapur", "Red Hills", "Rein Bazaar", "RTC X Roads", "Safilguda", "Saifabad", "Saidabad", "Sainikpuri", "Sanath Nagar", "Santosh Nagar", "Santoshnagar", "Saroornagar", "Sathamrai", "Secunderabad", "Serilingampally", "Shadnagar", "Shahalibanda", "Shaikpet", "Shamshabad", "Somajiguda", "SR Nagar", "Suchitra", "Suraram", "Tank Bund", "Tarnaka", "Telecom Nagar", "Tellapur", "Tolichowki", "Trimulgherry", "TSPA", "Tukkuguda", "Turkayamjal", "Uppal", "Vanasthalipuram", "Vidyanagar", "West Marredpally", "Whitefields", "Yakutpura", "Yapral", "Yousufguda"
        ],
        "secunderabad": [
            "Abids", "Adibatla", "Adikmet", "Airport Road", "Aliabad", "Almasguda", "Alwal", "Amberpet", "Ameenpur", "Ameerpet", "AS Rao Nagar", "Ashok Nagar", "Attapur", "Bachupally", "Bahadurpally", "Bahadurpura", "Bandlaguda", "Banjara Hills", "Barkatpura", "Basheerbagh", "Beeramguda", "Begumpet", "BHEL", "BN Reddy Nagar", "Boduppal", "Bolarum", "Bollaram", "Bongloor", "Borabanda", "Botanical Garden", "Bowenpally", "Bowrampet", "Budvel", "Chaitanyapuri", "Chanchalguda", "Chandanagar", "Chandrayangutta", "Charminar", "Cherlapally", "Chevella Road", "Chikkadpally", "Chintal", "Champapet", "Darulshifa", "Dilsukhnagar", "DLF Cyber City", "Domalguda", "Dundigal", "East Marredpally", "ECIL", "Erragadda", "Falaknuma", "Film Nagar", "Financial District", "Gachibowli", "Gandamguda", "Ghatkesar", "Golconda", "Gundlapochampally", "Habsiguda", "Hafeezpet", "Hayathnagar", "Himayat Nagar", "Himayatsagar", "HITEC City", "HMT Hills", "Hussaini Alam", "Hyder Nagar", "Ibrahimpatnam", "IS Sadan", "Isnapur", "Jagadgirigutta", "JNTU", "Jubilee Hills", "Kachiguda", "Kalapather", "Kapra", "Karkhana", "Karmanghat", "Khairatabad", "Khajaguda", "Kokapet", "Kompally", "Kondapur", "Kothaguda", "Kothapet", "Kothwalguda", "Koti", "KPHB Colony", "Krishna Nagar", "Kukatpally", "Kushaiguda", "Lakdikapul", "Langar Houz", "LB Nagar", "Lingampally", "Madannapet", "Madhapur", "Madinaguda", "Maheshwaram", "Malakpet", "Malkajgiri", "Mallapur", "Mamidipally", "Manikonda", "Marredpally", "Masab Tank", "Medchal", "Medipally", "Mehdipatnam", "Miyapur", "Moghalpura", "Moosarambagh", "Moosapet", "Moula Ali", "Musheerabad", "Nacharam", "Nagaram", "Nagole", "Nallagandla", "Nallakunta", "Nampally", "Nanakramguda", "Narayanguda", "Narsingi", "Nawab Saheb Kunta", "Necklace Road", "Neredmet", "Nizampet", "Old Alwal", "Osman Nagar", "Outer Ring Road", "Padmarao Nagar", "Patancheru", "Peeramcheruvu", "Peerzadiguda", "Pocharam", "Pragathi Nagar", "Punjagutta", "Puppalaguda", "Quthbullapur", "Rahmat Nagar", "Raidurg", "Rajendranagar", "Ramnagar", "Ramanthapur", "Red Hills", "Rein Bazaar", "RTC X Roads", "Safilguda", "Saifabad", "Saidabad", "Sainikpuri", "Sanath Nagar", "Santosh Nagar", "Santoshnagar", "Saroornagar", "Sathamrai", "Secunderabad", "Serilingampally", "Shadnagar", "Shahalibanda", "Shaikpet", "Shamshabad", "Somajiguda", "SR Nagar", "Suchitra", "Suraram", "Tank Bund", "Tarnaka", "Telecom Nagar", "Tellapur", "Tolichowki", "Trimulgherry", "TSPA", "Tukkuguda", "Turkayamjal", "Uppal", "Vanasthalipuram", "Vidyanagar", "West Marredpally", "Whitefields", "Yakutpura", "Yapral", "Yousufguda"
        ],
        "chennai": [
            "Abhiramapuram", "Adambakkam", "Adyar", "Akkarai", "Alandur", "Alwarpet", "Alwarthirunagar", "Ambattur", "Ambattur Industrial Estate", "Aminjikarai", "Anakaputhur", "Anna Nagar", "Anna Nagar East", "Anna Nagar West", "Annanur", "Arumbakkam", "Ashok Nagar", "Athipet", "Avadi", "Ayappakkam", "Ayanavaram", "Basin Bridge", "Besant Nagar", "Besant Nagar Extension", "Chengalpattu", "Chetpet", "Chintadripet", "Choolai", "Chromepet", "CIT Nagar", "East Tambaram", "Egattur", "Egmore", "Ennore", "Gandhi Nagar", "George Town", "Gopalapuram", "Guduvanchery", "Guindy", "Gummidipoondi", "Indira Nagar", "Injambakkam", "Iyyappanthangal", "K.K. Nagar", "Kadavur", "Kallikuppam", "Kamarajapuram", "Kanathur", "Kandigai", "Karambakkam", "Karapakkam", "Kasturba Nagar", "Kathivakkam", "Kattankulathur", "Kattupakkam", "Kazhipattur", "Keelkattalai", "Kelambakkam", "Kelambakkam Road", "Kilpauk", "KK Nagar", "Kodambakkam", "Kodungaiyur", "Kolathur", "Korattur", "Korukkupet", "Kottivakkam", "Kottur", "Kotturpuram", "Kovalam", "Kovur", "Koyambedu", "Kundrathur", "L.B. Road", "Lakshmipuram", "Little Mount", "Madhavaram", "Madipakkam", "Maduravoyal", "Mambakkam", "Manali", "Manapakkam", "Mandaveli", "Mangadu", "Mannurpet", "Maraimalai Nagar", "Medavakkam", "Meenambakkam", "Menambedu", "Minjur", "MKB Nagar", "Mogappair", "Mogappair East", "Mogappair West", "Moolakadai", "Moulivakkam", "Mudichur", "Mugalivakkam", "Muttukadu", "Mylapore", "Nandanam", "Nanganallur", "Navalur", "Neelankarai", "Nerkundram", "Nesapakkam", "New Washermanpet", "Nolambur", "Nungambakkam", "Old Washermanpet", "Oragadam", "Padi", "Padur", "Palavakkam", "Pallavaram", "Pallikaranai", "Pammal", "Park Town", "Pattabiram", "Perambur", "Periamet", "Perumbakkam", "Perungalathur", "Perungudi", "Ponniammanmedu", "Ponneri", "Poonamallee", "Porur", "Potheri", "Pozhichalur", "Pulianthope", "Purasawalkam", "Puzhal", "Puzhuthivakkam", "R.A. Puram", "R.K. Puram", "Raja Annamalaipuram", "Ramapuram", "Red Hills", "Royapettah", "Royapuram", "Saidapet", "Saligramam", "Sembium", "Semmenchery", "Shastri Nagar", "Shenoy Nagar", "Sholinganallur", "Singaperumal Koil", "Siruseri", "Sowcarpet", "Sriperumbudur", "St. Thomas Mount", "Surapet", "T. Nagar", "Tambaram", "Taramani", "Teynampet", "Thiruvanmiyur", "Thirumangalam", "Thirumudivakkam", "Thirumullaivoyal", "Thiruninravur", "Thiruvallur", "Thoraipakkam", "Thousand Lights", "Tiruninravur", "Tiruvottiyur", "Tondiarpet", "Triplicane", "Ullagaram", "Urapakkam", "Uthandi", "Vadapalani", "Valasaravakkam", "Vanagaram", "Vandalur", "Velachery", "Vettuvankeni", "Villivakkam", "Virugambakkam", "Vyasarpadi", "Washermanpet", "West Mambalam", "West Tambaram", "Wimco Nagar"
        ],
        "ahmedabad": [
            "AEC Cross Road", "Airport Road", "Ambawadi", "Ambli", "Amraiwadi", "Anandnagar", "Asarwa", "Ashram Road", "Aslali", "Bage Firdosh", "Bapunagar", "Behrampura", "Bodakdev", "Bopal", "CG Road", "Chandkheda", "Chandlodia", "CTM", "Danilimda", "Dariyapur", "Delhi Darwaja", "Ellis Bridge", "Fatehwadi", "Geratpur", "Ghatlodia", "Gheekanta", "Ghodasar", "Gomtipur", "Gota", "Gurukul", "Hansol", "Hatkeshwar", "Hathijan", "Hebatpur", "Income Tax", "Isanpur", "Jagatpur", "Jamalpur", "Jashodanagar", "Jodhpur", "Judges Bungalow Road", "Juhapura", "Kali", "Kalupur", "Kankaria", "Khanpur", "Khokhra", "Krishnanagar", "Kubernagar", "Lal Darwaja", "Lambha", "Law Garden", "Makarba", "Maninagar", "Maninagar East", "Meghaninagar", "Memnagar", "Motera", "Naranpura", "Naroda", "Narol", "Narol Road", "Nava Vadaj", "Navrangpura", "Nehrunagar", "New Ranip", "Nikol", "Odhav", "Paldi", "Panjrapole", "Polytechnic", "Prahlad Nagar", "Raikhad", "Rakhial", "Ramol", "Ranip", "Relief Road", "Riverfront", "Sabarmati", "Saijpur Bogha", "Sarangpur", "Sardarnagar", "Sarkhej", "Satellite", "Science City", "SG Highway", "Shah Alam", "Shahibaug", "Shahwadi", "Shela", "Shilaj", "Shyamal", "Sindhu Bhavan Road", "Sola", "South Bopal", "Stadium Road", "Thaltej", "Usmanpura", "Vadaj", "Vastral", "Vastrapur", "Vatva", "Vishala"
        ],
        "jaipur": [
            "Adarsh Nagar", "Agra Road", "Ajmer Road", "Ajmer Road Extension", "Ambabari", "Amer", "Amer Road", "Arjun Nagar", "Ashok Nagar", "Bagru", "Bajaj Nagar", "Bani Park", "Bapu Bazaar", "Barkat Nagar", "Bhankrota", "Bhatta Basti", "Bhawani Singh Road", "Bindayaka", "Brahmpuri", "C-Scheme", "Chandpole", "Chitrakoot", "Civil Lines", "Dadi Ka Phatak", "Delhi Road", "Dholai", "Durgapura", "Gandhi Path", "Ghat Gate", "Gopalbari", "Gopalpura Bypass", "Harmada", "Hathroi", "Heerapura", "Iskcon Road", "Jagatpura", "Jamdoli", "Jawahar Nagar", "Jaisinghpura", "Jaisinghpura Khor", "Jhotwara", "Jhotwara Industrial Area", "Johari Bazaar", "Kalwar Road", "Kanota", "Kesar Nagar", "Khatipura", "Kho Nagoriyan", "Kookas", "Lal Kothi", "Lalkothi", "Mahapura", "Mahaveer Nagar", "Mahesh Nagar", "Malviya Nagar", "Mansarovar", "Mansarovar Extension", "MI Road", "Motidungri", "Muhana", "Murlipura", "Nahargarh Road", "Nangal Jaisabohra", "New Sanganer Road", "Nirman Nagar", "Niwaru Road", "Panchyawala", "Patrakar Colony", "Patrakar Nagar", "Pratap Nagar", "Purani Basti", "Queens Road", "Raja Park", "Rambagh", "Ramganj", "Riddhi Siddhi", "Ring Road", "Sanganer", "Sanganer Road", "Sikar Road", "Shastri Nagar", "Shipra Path", "Shyam Nagar", "Sindhi Camp", "Sirsi Road", "Sitapura", "Sodala", "Station Road", "Tilak Nagar", "Tonk Road", "Transport Nagar", "Tripolia Bazaar", "Triveni Nagar", "Vaishali Nagar", "Vaishali Nagar Extension", "Vatika", "Vidyadhar Nagar"
        ],
        "chandigarh": [
            "Sector 1", "Sector 2", "Sector 3", "Sector 4", "Sector 5", "Sector 6", "Sector 7", "Sector 8", "Sector 9", "Sector 10",
            "Sector 11", "Sector 12", "Sector 14", "Sector 15", "Sector 16", "Sector 17", "Sector 18", "Sector 19", "Sector 20", "Sector 21",
            "Sector 22", "Sector 23", "Sector 24", "Sector 25", "Sector 26", "Sector 27", "Sector 28", "Sector 29", "Sector 30", "Sector 31",
            "Sector 32", "Sector 33", "Sector 34", "Sector 35", "Sector 36", "Sector 37", "Sector 38", "Sector 39", "Sector 40", "Sector 41",
            "Sector 42", "Sector 43", "Sector 44", "Sector 45", "Sector 46", "Sector 47", "Sector 48", "Sector 49", "Sector 50", "Sector 51",
            "Sector 52", "Sector 53", "Sector 54", "Sector 55", "Sector 56", "Sector 57", "Sector 58", "Sector 59", "Sector 60", "Manimajra",
            "Dhanas", "Maloya", "Hallo Majra", "Kishangarh", "Burail", "Palsora", "Behlana", "Ram Darbar", "Industrial Area Phase 1", "Industrial Area Phase 2",
            "IT Park", "Sukhna Enclave", "Lake Club Area", "Chandigarh Railway Station Area", "Airport Road Area"
        ]
    };

    const _dbData = window._dbLocationData || {};

    function mergeUniqueSorted(arr1, arr2) {
        const seen = new Set();
        const result = [];
        (arr1 || []).concat(arr2 || []).forEach(item => {
            if (typeof item === 'string') {
                const clean = item.trim();
                const lower = clean.toLowerCase();
                if (clean && !seen.has(lower)) {
                    seen.add(lower);
                    result.push(clean);
                }
            }
        });
        return result.sort((a, b) => a.localeCompare(b));
    }

    const _dbStates = _dbData.states || {};
    const _allStates = Object.assign({}, {
        "AP": "Andhra Pradesh", "AR": "Arunachal Pradesh", "AS": "Assam", "BR": "Bihar", "CT": "Chhattisgarh",
        "GA": "Goa", "GJ": "Gujarat", "HR": "Haryana", "HP": "Himachal Pradesh", "JH": "Jharkhand",
        "KA": "Karnataka", "KL": "Kerala", "MP": "Madhya Pradesh", "MH": "Maharashtra", "MN": "Manipur",
        "ML": "Meghalaya", "MZ": "Mizoram", "NL": "Nagaland", "OR": "Odisha", "PB": "Punjab",
        "RJ": "Rajasthan", "SK": "Sikkim", "TN": "Tamil Nadu", "TS": "Telangana", "TR": "Tripura",
        "UP": "Uttar Pradesh", "UK": "Uttarakhand", "WB": "West Bengal", "DL": "Delhi", "LA": "Ladakh", "CH": "Chandigarh"
    }, _dbStates);

    const _districtsMap = {};
    const allStateKeys = new Set([...Object.keys(_allStates), ...Object.keys(_standardDistrictsByState), ...Object.keys(_dbData.districts || {})]);

    allStateKeys.forEach(key => {
        const stateName = _allStates[key] || key;
        const dbList = (_dbData.districts && (_dbData.districts[key] || _dbData.districts[key.toUpperCase()] || _dbData.districts[stateName])) || [];
        const stdList = _standardDistrictsByState[key] || _standardDistrictsByState[key.toUpperCase()] || [];
        const combined = mergeUniqueSorted(dbList, stdList);

        if (combined.length > 0) {
            _districtsMap[key] = combined;
            _districtsMap[key.toUpperCase()] = combined;
            _districtsMap[key.toLowerCase()] = combined;
            _districtsMap[stateName] = combined;
            _districtsMap[stateName.toUpperCase()] = combined;
            _districtsMap[stateName.toLowerCase()] = combined;
        }
    });

    window.IndianLocationData = {
        states: _allStates,
        districts: _districtsMap,
        allDistricts: _dbData.allDistricts || [],
        localities: Object.assign({}, _dbData.localities || {}, _standardLocalities),
        localitiesByState: Object.assign({}, _dbData.localitiesByState || {})
    };

    for (const [cityKey, locs] of Object.entries(_standardLocalities)) {
        window.IndianLocationData.localities[cityKey.toLowerCase()] = locs;
        window.IndianLocationData.localities[cityKey.replace(/\s+/g, '-').toLowerCase()] = locs;
        window.IndianLocationData.localities[cityKey.replace(/-/g, ' ').toLowerCase()] = locs;
    }

    window.resolveStateCode = function(val) {
        if (!val) return '';
        const clean = val.toString().trim().toUpperCase();
        if (window.IndianLocationData.districts[clean]) return clean;
        if (_stateNameToCode[clean]) return _stateNameToCode[clean];
        for (const [code, name] of Object.entries(window.IndianLocationData.states)) {
            if (code.toUpperCase() === clean || name.toUpperCase() === clean) {
                return code;
            }
        }
        return clean;
    };

    window.handleLocationStateChange = function(stateSelectEl, citySelectId = 'city-select', localitySelectId = 'locality-select') {
        if (!stateSelectEl) return;
        const form = stateSelectEl.form || document;
        const cityEl = document.getElementById(citySelectId) || form.querySelector(`[name="district"]`) || form.querySelector(`[name="location"]`);
        const localityEl = document.getElementById(localitySelectId) || form.querySelector(`[name="locality"]`);

        if (!cityEl) return;

        const rawState = (stateSelectEl.value || '').trim();
        const stateCode = window.resolveStateCode(rawState);
        const stateName = (window.IndianLocationData.states && window.IndianLocationData.states[stateCode]) || rawState;

        cityEl.innerHTML = '';
        
        let districtList = null;
        if (stateCode) {
            districtList = window.IndianLocationData.districts[stateCode] 
                || window.IndianLocationData.districts[stateCode.toUpperCase()]
                || window.IndianLocationData.districts[stateName]
                || window.IndianLocationData.districts[stateName.toUpperCase()];
        }

        if (stateCode && districtList && districtList.length > 0) {
            const firstOpt = new Option(`\u00A0\u00A0All Cities in ${stateName}`, '');
            cityEl.add(firstOpt);

            districtList.forEach(distName => {
                const opt = new Option(`\u00A0\u00A0${distName}`, distName);
                cityEl.add(opt);
            });
        } else if (stateCode) {
            const firstOpt = new Option(`\u00A0\u00A0Loading ${stateName} Cities...`, '');
            cityEl.add(firstOpt);

            fetch(`/api/locations/districts?state=${encodeURIComponent(stateCode)}`)
                .then(r => r.json())
                .then(data => {
                    cityEl.innerHTML = '';
                    cityEl.add(new Option(`\u00A0\u00A0All Cities in ${stateName}`, ''));
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            cityEl.add(new Option(`\u00A0\u00A0${item.name}`, item.name));
                        });
                    } else {
                        cityEl.add(new Option(`\u00A0\u00A0Select City / District`, ''));
                    }
                })
                .catch(() => {
                    cityEl.innerHTML = `<option value="">\u00A0\u00A0Select District</option>`;
                });
        } else {
            cityEl.add(new Option('\u00A0\u00A0All Cities / Districts', ''));
            const all = window.IndianLocationData.allDistricts || [];
            if (all.length > 0) {
                all.forEach(item => {
                    const label = item.state_code ? `${item.name} (${item.state_code})` : item.name;
                    cityEl.add(new Option(`\u00A0\u00A0${label}`, item.name));
                });
            } else {
                ['Gurugram (HR)', 'New Delhi (DL)', 'South Delhi (DL)', 'Noida (UP)', 'Faridabad (HR)', 'Bengaluru (KA)', 'Mumbai (MH)', 'Pune (MH)', 'Hyderabad (TS)', 'Jaipur (RJ)', 'Chandigarh (CH)'].forEach(d => {
                    cityEl.add(new Option(`\u00A0\u00A0${d}`, d.split(' ')[0]));
                });
            }
        }

        window.handleLocationCityChange(cityEl, localitySelectId, stateSelectEl.id);
    };

    window.handleLocationCityChange = function(citySelectEl, localitySelectId = 'locality-select', stateSelectId = 'state-select') {
        if (!citySelectEl) return;
        const form = citySelectEl.form || document;
        const localityEl = document.getElementById(localitySelectId) || form.querySelector(`[name="locality"]`);
        const stateEl = document.getElementById(stateSelectId) || form.querySelector(`[name="state"]`);

        if (!localityEl) return;

        const cityVal = (citySelectEl.value || '').trim();
        const cityKey = cityVal.toLowerCase().replace(/ \([a-z]+\)$/i, '').trim();
        const rawState = stateEl ? (stateEl.value || '').trim() : '';
        const stateCode = window.resolveStateCode(rawState);

        localityEl.innerHTML = '';

        let locList = null;
        if (cityKey) {
            locList = window.IndianLocationData.localities[cityKey]
                || window.IndianLocationData.localities[cityKey.replace(/\s+/g, '-')]
                || window.IndianLocationData.localities[cityKey.replace(/-/g, ' ')];
        } else if (stateCode) {
            locList = window.IndianLocationData.localitiesByState[stateCode]
                || window.IndianLocationData.localitiesByState[stateCode.toUpperCase()];
        }

        const placeholder = cityVal ? `\u00A0\u00A0All Localities in ${cityVal}` : (stateCode ? `\u00A0\u00A0All Localities in ${rawState}` : '\u00A0\u00A0All Localities / Areas');
        localityEl.add(new Option(placeholder, ''));

        if (locList && locList.length > 0) {
            locList.forEach(loc => {
                localityEl.add(new Option(`\u00A0\u00A0${loc}`, loc));
            });
        } else if (cityVal) {
            fetch(`/api/locations/localities?district=${encodeURIComponent(cityVal)}`)
                .then(r => r.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            localityEl.add(new Option(`\u00A0\u00A0${item.name}`, item.name));
                        });
                    }
                })
                .catch(() => {});
        }
    };

    window.initLocationCascading = function(config) {
        const stateEl = document.getElementById(config.stateId);
        const cityEl = document.getElementById(config.cityId);
        const localityEl = document.getElementById(config.localityId);

        if (!stateEl || !cityEl) return;

        // Auto-populate states into select if only placeholder option exists
        if (stateEl.options.length <= 1 && window.IndianLocationData && window.IndianLocationData.states) {
            for (const [code, name] of Object.entries(window.IndianLocationData.states)) {
                stateEl.add(new Option(name, code));
            }
        }

        stateEl.addEventListener('change', function() {
            window.handleLocationStateChange(this, config.cityId, config.localityId);
        });

        cityEl.addEventListener('change', function() {
            window.handleLocationCityChange(this, config.localityId, config.stateId);
        });

        [stateEl, cityEl, localityEl].forEach(el => {
            if (!el) return;
            const group = el.closest('.filter-group') || el.closest('.filter-input-wrap');
            if (group && !group.dataset.hasClickListener) {
                group.dataset.hasClickListener = 'true';
                group.addEventListener('click', (e) => {
                    if (e.target !== el) el.focus();
                });
            }
        });

        // Initialize selected state
        if (config.selectedState) {
            stateEl.value = config.selectedState;
        }

        // Always run state change to populate matching cities (or all cities if no state selected)
        window.handleLocationStateChange(stateEl, config.cityId, config.localityId);

        // Initialize selected city
        if (config.selectedCity) {
            cityEl.value = config.selectedCity;
            window.handleLocationCityChange(cityEl, config.localityId, config.stateId);
        }

        // Initialize selected locality
        if (config.selectedLocality && localityEl) {
            localityEl.value = config.selectedLocality;
        }
    };

    window.setPill = function(btn, value, inputId) {
        if (!btn) return;
        const group = btn.closest('.pill-group');
        if (group) {
            const input = inputId ? document.getElementById(inputId) : group.querySelector('input[type="hidden"]');
            if (input) {
                input.value = value;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
            const buttons = group.querySelectorAll('.pill-btn');
            buttons.forEach(function(b) {
                b.classList.remove('active');
            });
        }
        btn.classList.add('active');
    };

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.pill-btn');
        if (!btn) return;
        const group = btn.closest('.pill-group');
        if (!group) return;
        const hiddenInput = group.querySelector('input[type="hidden"]');
        const val = btn.getAttribute('data-value') || btn.textContent.trim().toLowerCase();

        group.querySelectorAll('.pill-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');

        if (hiddenInput) {
            hiddenInput.value = btn.dataset.value || val;
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });

})(window);
