<script>
    window.IndianLocationData = {
        states: {
            'AP': 'Andhra Pradesh', 'AR': 'Arunachal Pradesh', 'AS': 'Assam', 'BR': 'Bihar', 'CT': 'Chhattisgarh',
            'GA': 'Goa', 'GJ': 'Gujarat', 'HR': 'Haryana', 'HP': 'Himachal Pradesh', 'JH': 'Jharkhand',
            'KA': 'Karnataka', 'KL': 'Kerala', 'MP': 'Madhya Pradesh', 'MH': 'Maharashtra', 'MN': 'Manipur',
            'ML': 'Meghalaya', 'MZ': 'Mizoram', 'NL': 'Nagaland', 'OR': 'Odisha', 'PB': 'Punjab',
            'RJ': 'Rajasthan', 'SK': 'Sikkim', 'TN': 'Tamil Nadu', 'TS': 'Telangana', 'TR': 'Tripura',
            'UP': 'Uttar Pradesh', 'UK': 'Uttarakhand', 'WB': 'West Bengal', 'AN': 'Andaman and Nicobar Islands',
            'CH': 'Chandigarh', 'DN': 'Dadra and Nagar Haveli and Daman and Diu', 'DL': 'Delhi', 'JK': 'Jammu and Kashmir',
            'LA': 'Ladakh', 'LD': 'Lakshadweep', 'PY': 'Puducherry'
        },
        districts: {
            'AP': ['Anantapur', 'Chittoor', 'East Godavari', 'Guntur', 'Krishna', 'Kurnool', 'Prakasam', 'Srikakulam', 'Sri Potti Sriramulu Nellore', 'Visakhapatnam', 'Vizianagaram', 'West Godavari', 'YSR Kadapa'],
            'PB': ['Amritsar', 'Barnala', 'Bathinda', 'Faridkot', 'Fatehgarh Sahib', 'Fazilka', 'Ferozepur', 'Gurdaspur', 'Hoshiarpur', 'Jalandhar', 'Kapurthala', 'Ludhiana', 'Mansa', 'Moga', 'Muktsar', 'Pathankot', 'Patiala', 'Rupnagar', 'Sahibzada Ajit Singh Nagar (Mohali)', 'Sangrur', 'Shahid Bhagat Singh Nagar (Nawanshahr)', 'Sri Muktsar Sahib', 'Tarn Taran'],
            'JK': ['Anantnag', 'Bandipora', 'Baramulla', 'Budgam', 'Doda', 'Ganderbal', 'Jammu', 'Kathua', 'Kishtwar', 'Kulgam', 'Kupwara', 'Poonch', 'Pulwama', 'Rajouri', 'Ramban', 'Reasi', 'Samba', 'Shopian', 'Srinagar', 'Udhampur'],
            'CT': ['Balod', 'Baloda Bazar', 'Balrampur', 'Bastar', 'Bemetara', 'Bijapur', 'Bilaspur', 'Dantewada', 'Dhamtari', 'Durg', 'Gariaband', 'Gaurela-Pendra-Marwahi', 'Janjgir-Champa', 'Jashpur', 'Kanker', 'Kawardha', 'Kondagaon', 'Korba', 'Koriya', 'Mahasamund', 'Mungeli', 'Narayanpur', 'Raigarh', 'Raipur', 'Rajnandgaon', 'Sukma', 'Surajpur', 'Surguja'],
            'AR': ['Anjaw', 'Changlang', 'Dibang Valley', 'East Kameng', 'East Siang', 'Kamle', 'Kra Daadi', 'Kurung Kumey', 'Lepa Rada', 'Lohit', 'Longding', 'Lower Dibang Valley', 'Lower Siang', 'Lower Subansiri', 'Namsai', 'Pakke Kessang', 'Papum Pare', 'Shi Yomi', 'Siang', 'Tawang', 'Tirap', 'Upper Siang', 'Upper Subansiri', 'West Kameng', 'West Siang'],
            'AS': ['Baksa', 'Barpeta', 'Biswanath', 'Bongaigaon', 'Cachar', 'Charaideo', 'Chirang', 'Darrang', 'Dhemaji', 'Dhubri', 'Dibrugarh', 'Dima Hasao', 'Goalpara', 'Golaghat', 'Hailakandi', 'Hojai', 'Jorhat', 'Kamrup', 'Kamrup Metropolitan', 'Karbi Anglong', 'Karimganj', 'Kokrajhar', 'Lakhimpur', 'Majuli', 'Morigaon', 'Nagaon', 'Nalbari', 'Sivasagar', 'Sonitpur', 'South Salmara-Mankachar', 'Tinsukia', 'Udalguri', 'West Karbi Anglong'],
            'BR': ['Araria', 'Arwal', 'Aurangabad', 'Banka', 'Begusarai', 'Bhagalpur', 'Bhojpur', 'Buxar', 'Darbhanga', 'East Champaran', 'Gaya', 'Gopalganj', 'Jamui', 'Jehanabad', 'Kaimur', 'Katihar', 'Khagaria', 'Kishanganj', 'Lakhisarai', 'Madhepura', 'Madhubani', 'Munger', 'Muzaffarpur', 'Nalanda', 'Nawada', 'Patna', 'Purnia', 'Rohtas', 'Saharsa', 'Samastipur', 'Saran', 'Sheikhpura', 'Sheohar', 'Sitamarhi', 'Siwan', 'Supaul', 'Vaishali', 'West Champaran'],
            'MH': ['Ahmednagar', 'Akola', 'Amravati', 'Aurangabad', 'Beed', 'Bhandara', 'Buldhana', 'Chandrapur', 'Dhule', 'Gadchiroli', 'Gondia', 'Hingoli', 'Jalgaon', 'Jalna', 'Kolhapur', 'Latur', 'Mumbai City', 'Mumbai Suburban', 'Nagpur', 'Nanded', 'Nandurbar', 'Nashik', 'Osmanabad', 'Palghar', 'Parbhani', 'Pune', 'Raigad', 'Ratnagiri', 'Sangli', 'Satara', 'Sindhudurg', 'Solapur', 'Thane', 'Wardha', 'Washim', 'Yavatmal'],
            'KA': ['Bagalkot', 'Ballari', 'Belagavi', 'Bengaluru Rural', 'Bengaluru Urban', 'Bidar', 'Chamarajanagar', 'Chikkaballapur', 'Chikkamagaluru', 'Chitradurga', 'Dakshina Kannada', 'Davanagere', 'Dharwad', 'Gadag', 'Hassan', 'Haveri', 'Kalaburagi', 'Kodagu', 'Kolar', 'Koppal', 'Mandya', 'Mysuru', 'Raichur', 'Ramanagara', 'Shivamogga', 'Tumakuru', 'Udupi', 'Uttara Kannada', 'Vijayapura', 'Yadgir'],
            'DL': ['Central Delhi', 'East Delhi', 'New Delhi', 'North Delhi', 'North East Delhi', 'North West Delhi', 'Shahdara', 'South Delhi', 'South East Delhi', 'South West Delhi', 'West Delhi'],
            'GJ': ['Ahmedabad', 'Amreli', 'Anand', 'Aravalli', 'Banaskantha', 'Bharuch', 'Bhavnagar', 'Botad', 'Chhota Udepur', 'Dahod', 'Dang', 'Devbhumi Dwarka', 'Gandhinagar', 'Gir Somnath', 'Jamnagar', 'Junagadh', 'Kheda', 'Kutch', 'Mahisagar', 'Mehsana', 'Morbi', 'Narmada', 'Navsari', 'Panchmahal', 'Patan', 'Porbandar', 'Rajkot', 'Sabarkantha', 'Surat', 'Surendranagar', 'Tapi', 'Vadodara', 'Valsad'],
            'UP': ['Agra', 'Aligarh', 'Prayagraj', 'Lucknow', 'Kanpur Nagar', 'Varanasi', 'Meerut', 'Ghaziabad', 'Gautam Buddha Nagar', 'Bareilly', 'Moradabad', 'Aligarh', 'Saharanpur', 'Gorakhpur', 'Jhansi', 'Mathura', 'Firozabad', 'Ayodhya'],
            'TS': ['Hyderabad', 'Ranga Reddy', 'Medchal-Malkajgiri', 'Karimnagar', 'Warangal', 'Nizamabad', 'Khammam'],
            'SK': ['Gangtok', 'Gyalshing', 'Mangan', 'Namchi', 'Pakyong', 'Soreng'],
            'TR': ['Dhalai', 'Gomati', 'Khowai', 'North Tripura', 'Sepahijala', 'South Tripura', 'Unakoti', 'West Tripura'],
            'TN': ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tiruppur', 'Erode', 'Vellore'],
            'MP': ['Indore', 'Bhopal', 'Jabalpur', 'Gwalior', 'Ujjain', 'Sagar', 'Rewa', 'Satna', 'Ratlam', 'Murwara (Katni)', 'Singrauli', 'Burhanpur', 'Khandwa', 'Bhind', 'Chhindwara', 'Guna', 'Shivpuri', 'Vidisha', 'Chhatarpur', 'Damoh', 'Mandsaur', 'Khargone', 'Neemuch', 'Pithampur', 'Narmadapuram', 'Itarsi', 'Sehore', 'Morena', 'Betul', 'Seoni', 'Datia', 'Nagda', 'Dindori'],
            'HR': ['Ambala', 'Bhiwani', 'Charkhi Dadri', 'Faridabad', 'Fatehabad', 'Gurugram', 'Hisar', 'Jhajjar', 'Jind', 'Kaithal', 'Karnal', 'Kurukshetra', 'Mahendragarh', 'Nuh', 'Palwal', 'Panchkula', 'Panipat', 'Rewari', 'Rohtak', 'Sirsa', 'Sonipat', 'Yamunanagar'],
            'RJ': ['Ajmer', 'Alwar', 'Banswara', 'Baran', 'Barmer', 'Bharatpur', 'Bhilwara', 'Bikaner', 'Bundi', 'Chittorgarh', 'Churu', 'Dausa', 'Dholpur', 'Dungarpur', 'Hanumangarh', 'Jaipur', 'Jaisalmer', 'Jalore', 'Jhalawar', 'Jhunjhunu', 'Jodhpur', 'Karauli', 'Kota', 'Nagaur', 'Pali', 'Pratapgarh', 'Rajsamand', 'Sawai Madhopur', 'Sikar', 'Sirohi', 'Sri Ganganagar', 'Tonk', 'Udaipur'],
            'WB': ['Alipurduar', 'Bankura', 'Birbhum', 'Cooch Behar', 'Dakshin Dinajpur', 'Darjeeling', 'Hooghly', 'Howrah', 'Jalpaiguri', 'Jhargram', 'Kalimpong', 'Kolkata', 'Malda', 'Murshidabad', 'Nadia', 'North 24 Parganas', 'Paschim Bardhaman', 'Paschim Medinipur', 'Purba Bardhaman', 'Purba Medinipur', 'Purulia', 'South 24 Parganas', 'Uttar Dinajpur'],
            'OR': ['Angul', 'Boudh', 'Bhadrak', 'Balasore', 'Bargarh', 'Balangir', 'Cuttack', 'Deogarh', 'Dhenkanal', 'Ganjam', 'Gajapati', 'Jharsuguda', 'Jajpur', 'Jagatsinghpur', 'Khordha', 'Kendujhar', 'Kalahandi', 'Kandhamal', 'Koraput', 'Kendrapara', 'Malkangiri', 'Mayurbhanj', 'Nabarangpur', 'Nuapada', 'Nayagarh', 'Puri', 'Rayagada', 'Sambalpur', 'Subarnapur', 'Sundargarh']
        },
        localities: {
            'mumbai-city': ['Colaba','Fort','Churchgate','Nariman Point','Marine Lines','Byculla','Mazgaon','Parel','Dadar','Mahim','Worli','Lower Parel','Mahalaxmi','Malabar Hill','Walkeshwar','Cuffe Parade'],
            'mumbai-suburban': ['Andheri West','Andheri East','Jogeshwari','Vile Parle','Santacruz','Khar','Bandra West','Malad','Borivali','Kandivali','Goregaon','Mulund','Ghatkopar','Powai','Vikhroli','Chembur','Govandi','Mankhurd','Kurla West','Bhandup'],
            'pune': ['Shivajinagar','Deccan','Kothrud','Aundh','Baner','Hinjewadi','Wakad','Pimpri','Chinchwad','Kharadi','Viman Nagar','Hadapsar','Kondhwa','Wanowrie','Katraj','Sinhagad Road','Bavdhan','Balewadi','Pimple Saudagar','Kalyani Nagar'],
            'nagpur': ['Dharampeth','Sadar','Sitabuldi','Ramdaspeth','Bajaj Nagar','Manish Nagar','Trimurti Nagar','Civil Lines','Shankar Nagar','Laxmi Nagar'],
            'thane': ['Thane West','Thane East','Kalwa','Mumbra','Diva','Bhiwandi','Ulhasnagar','Kalyan','Dombivli','Mira Road','Vasai','Virar','Navi Mumbai','Airoli','Vashi'],
            'new-delhi': ['Connaught Place','Karol Bagh','Saket','Dwarka','Rohini','Pitam Pura','Janakpuri','Rajouri Garden','Preet Vihar','Laxmi Nagar','Mayur Vihar'],
            'south-delhi': ['Lajpat Nagar','Defence Colony','Greater Kailash','Green Park','Hauz Khas','Malviya Nagar','Saket','Vasant Kunj','Vasant Vihar','Kalkaji','Okhla'],
            'north-delhi': ['Civil Lines','Kamla Nagar','Mukherjee Nagar','Model Town','Shalimar Bagh','Ashok Vihar','Pitam Pura'],
            'bengaluru-urban': ['Whitefield','Koramangala','Indiranagar','HSR Layout','BTM Layout','Jayanagar','JP Nagar','Banashankari','Rajajinagar','Malleswaram','Sadashivanagar','Hebbal','Yelahanka','Electronic City','Sarjapur','Marathahalli','Bellandur'],
            'hyderabad': ['Banjara Hills','Jubilee Hills','Madhapur','Gachibowli','Kondapur','Hitec City','Kukatpally','Miyapur','Ameerpet','Secunderabad','Uppal','LB Nagar'],
            'chennai': ['Anna Nagar','Adyar','Besant Nagar','Velachery','Tambaram','Chromepet','Porur','Madipakkam','Saidapet','T. Nagar','Nungambakkam'],
            'kolkata': ['Salt Lake','New Town','Rajarhat','Park Street','Ballygunge','Alipore','Behala','Jadavpur','Tollygunge','Dum Dum'],
            'ahmedabad': ['Satellite','Bopal','SG Highway','Prahlad Nagar','Vastrapur','Bodakdev','Thaltej','Maninagar','Chandkheda','Gota'],
            'jaipur': ['Malviya Nagar','Vaishali Nagar','Mansarovar','C-Scheme','Civil Lines','Bani Park','Jagatpura','Pratap Nagar'],
            'lucknow': ['Gomti Nagar','Hazratganj','Aliganj','Indira Nagar','Vibhuti Khand','Jankipuram','Alambagh','Ashiyana'],
            'chandigarh': ['Sector 17','Sector 22','Sector 35','Sector 43','Sector 8','Sector 9','Sector 11'],
            'gurugram': ['DLF Phase 1','DLF Phase 2','DLF Phase 3','DLF Phase 4','DLF Phase 5','Sector 14','Sector 50','Golf Course Road','Sohna Road','MG Road'],
            'noida': ['Sector 18','Sector 44','Sector 62','Sector 63','Sector 137','Greater Noida','Noida Extension','Sector 150'],
            'indore': ['Vijay Nagar','Bicholi Mardana','Nipania','Khajrana','Rau','AB Road','Bengali Square','Palasia','Rajwada','Annapurna','Mahalakshmi Nagar','LIG Colony','Sudama Nagar','Kanadiya','Bhanvarkuan'],
            'bhopal': ['MP Nagar','Arera Colony','Kolar Road','Hoshangabad Road','Ayodhya Bypass','Govindpura','Misrod','Habibganj','TT Nagar','Bairagarh','BHEL','Chuna Bhatti','Neelbad'],
            'jabalpur': ['Wright Town','Civil Lines','Adhartal','Ganjipura','Madan Mahal','Ranjhi','Gorakhpur','Vijay Nagar','Garha','Bilhari'],
            'gwalior': ['City Centre','Hazira','Gwalior Fort Area','DD Nagar','Morar','Lashkar','Thatipur','Padav','Phalka Bazar'],
            'ujjain': ['Freeganj','Nanakheda','Mahakal Area','Dewas Road','Seththi Nagar','Vasant Vihar'],
            'faridabad': ['Sector 15','Sector 16','Sector 21','NIT','Ballabhgarh','Greater Faridabad','Green Field','Surajkund','Sainik Colony','Sector 37'],
            'gurugram': ['DLF Phase 1','DLF Phase 2','DLF Phase 3','DLF Phase 4','DLF Phase 5','Sector 14','Sector 50','Golf Course Road','Sohna Road','MG Road','Palam Vihar','Sushant Lok','Udyog Vihar'],
            'ludhiana': ['Model Town','Civil Lines','Ferozepur Road','Sarabha Nagar','Dugri','Haibowal','Chandigarh Road','BRS Nagar','Pakhowal Road'],
            'amritsar': ['Ranjit Avenue','Mall Road','Putlighar','Civil Lines','Lawrence Road','Golden Temple Area','Majitha Road','Batala Road'],
            'jalandhar': ['Model Town','Urban Estate','Phagwara Road','Defence Colony','Lajpat Nagar','LPU Area','Rama Mandi'],
            'lucknow': ['Gomti Nagar','Hazratganj','Aliganj','Indira Nagar','Vibhuti Khand','Jankipuram','Alambagh','Ashiyana','Charbagh','Mahanagar'],
            'kanpur-nagar': ['Civil Lines','Kakadeo','Kalyanpur','Swarup Nagar','Govind Nagar','Shyam Nagar','Jajmau','Panki'],
            'prayagraj': ['Civil Lines','Katra','Allahpur','Teliyarganj','Jhusi','Naini','Phaphamau'],
            'varanasi': ['Sigra','Bhelupur','Lanka','Shivpur','Sarnath','Mahmoorganj','Paharia'],
            'agra': ['Sikandra','Fatehabad Road','Dayal Bagh','Kamla Nagar','Tajganj','Shahganj','Sanjay Place'],
            'hyderabad': ['Banjara Hills','Jubilee Hills','Madhapur','Gachibowli','Kondapur','Hitec City','Kukatpally','Miyapur','Ameerpet','Secunderabad','Uppal','LB Nagar','Manikonda','Hafeezpet','Nallagandla'],
            'coimbatore': ['RS Puram','Gandhipuram','Peelamedu','Saibaba Colony','Singanallur','Vadavalli','Thudiyalur'],
            'madurai': ['Anna Nagar','KK Nagar','Tallakulam','Madurai Main','Simmakkal','Ellis Nagar'],
            'surat': ['Adajan','Vesu','Piplod','Bhatar','Pal','Dumas','Ghod Dod Road','Althan','Katargam','Varachha'],
            'vadodara': ['Alkapuri','Sayajiganj','Manjalpur','Gotri','Vasna','Nizampura','Karelibaug'],
            'rajkot': ['Kalawad Road','University Road','150 Feet Ring Road','Munjka','Raiya Road','Gondal Road'],
            'mysuru': ['Kuvempunagar','Vijayanagar','Jayalakshmipuram','Hebbal','Gokulam','Siddhartha Layout'],
            'kochi': ['Marine Drive','Kakkanad','Edapally','Panampilly Nagar','Vyttila','MG Road','Fort Kochi'],
            'kozhikode': ['Mavoor Road','Beach Road','Nadakkavu','Arayidathupalam','Medical College'],
            'patna': ['Boring Road','Bailey Road','Kankarbagh','Rajendra Nagar','Patliputra Colony','Danapur'],
            'bhubaneswar': ['Patia','Jayadev Vihar','Nayapalli','Saheed Nagar','Khandagiri','Chandrasekharpur'],
            'ranchi': ['Lalpur','Bariatu','Ashok Nagar','Harmu','Doranda','Hinoo'],
            'dehradun': ['Rajpur Road','Dalanwala','Vasant Vihar','Prem Nagar','Clement Town','Sahastradhara Road']
        }
    };

    /**
     * Helper to init cascading dropdowns
     */
    window.initLocationCascading = function(config) {
        const stateEl = document.getElementById(config.stateId);
        const cityEl = document.getElementById(config.cityId);
        const localityEl = document.getElementById(config.localityId);
        const localityTextWrap = document.getElementById(config.localityTextWrapId);
        const localitySelectWrap = document.getElementById(config.localitySelectWrapId);

        if (!stateEl || !cityEl) return;

        // Populate States
        if (stateEl.options.length <= 1) {
            Object.entries(window.IndianLocationData.states).forEach(([code, name]) => {
                const opt = new Option(name, code);
                if (config.selectedState === code) opt.selected = true;
                stateEl.add(opt);
            });
        }

        const updateCities = (selectedCity = null) => {
            const stateCode = stateEl.value;
            cityEl.innerHTML = '<option value="">Select District / City</option>';
            cityEl.disabled = !stateCode;
            
            if (stateCode && window.IndianLocationData.districts[stateCode]) {
                window.IndianLocationData.districts[stateCode].forEach(city => {
                    const val = city.toLowerCase().replace(/\s+/g, '-');
                    const opt = new Option(city, val);
                    if (selectedCity === val || selectedCity === city) opt.selected = true;
                    cityEl.add(opt);
                });
            }
            updateLocalities();
        };

        const updateLocalities = (selectedLocality = null) => {
            if (!localityEl) return;
            const cityVal = cityEl.value;
            localityEl.innerHTML = '<option value="">Select Locality</option>';
            
            const list = window.IndianLocationData.localities[cityVal];
            
            if (list && list.length > 0) {
                list.forEach(loc => {
                    const opt = new Option(loc, loc);
                    if (selectedLocality === loc) opt.selected = true;
                    localityEl.add(opt);
                });
                localityEl.disabled = false;
            } else if (cityVal) {
                // Generic Fallback for cities without specific data
                ['Main Town','Market Area','Station Road','Civil Lines','Residential Area','Sector 1','Sector 2'].forEach(loc => {
                    localityEl.add(new Option(loc, loc));
                });
                localityEl.disabled = false;
            } else {
                localityEl.disabled = true;
            }
        };

        stateEl.addEventListener('change', () => updateCities());
        cityEl.addEventListener('change', () => updateLocalities());

        // Initial trigger if values exist
        if (stateEl.value) updateCities(config.selectedCity);
        if (cityEl.value) updateLocalities(config.selectedLocality);
    };
</script>
