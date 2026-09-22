<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class OdishaLocalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds for all 30 Odisha Districts & their Localities.
     */
    public function run(): void
    {
        // 1. Ensure localities table exists with slug column
        if (!Schema::hasTable('localities')) {
            Schema::create('localities', function ($table) {
                $table->id();
                $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
                $table->string('name', 150);
                $table->string('slug', 150)->nullable()->index();
                $table->index('name');
            });
        } elseif (!Schema::hasColumn('localities', 'slug')) {
            Schema::table('localities', function ($table) {
                $table->string('slug', 150)->nullable()->index()->after('name');
            });
        }

        // 2. Fetch Odisha State
        $state = DB::table('states')->where('code', 'OR')->first();
        if (!$state) {
            $stateId = DB::table('states')->insertGetId([
                'code' => 'OR',
                'name' => 'Odisha',
            ]);
        } else {
            $stateId = $state->id;
        }

        // 3. Complete District => Localities map
        $dataset = [
            'Angul' => [
                'Angul', 'Talcher', 'Banarpal', 'Chhendipada', 'Athamallik', 'Pallahara',
                'Kaniha', 'Nalco Nagar', 'Hakimpara', 'Amalapada', 'Turanga', 'Similipada',
                'Hulurisingha', 'Gandhi Marg', 'Industrial Estate', 'Talcher Coalfields',
                'Deulbera', 'MCL Colony'
            ],
            'Balangir' => [
                'Balangir', 'Titlagarh', 'Patnagarh', 'Kantabanji', 'Saintala', 'Loisingha',
                'Bangomunda', 'Muribahal', 'Agalpur', 'Gudvela', 'Puintala', 'Deogaon',
                'Tusura', 'Sindhekela', 'Belpara', 'Khaprakhol'
            ],
            'Balasore' => [
                'Balasore', 'Baleshwar', 'Jaleswar', 'Soro', 'Nilagiri', 'Basta',
                'Bhograi', 'Remuna', 'Simulia', 'Khantapara', 'Chandipur', 'Balipal',
                'Sergarh', 'Mitrapur', 'Rupsa', 'Sahupada', 'Motiganj', 'Sunhat', 'Teligadia'
            ],
            'Bargarh' => [
                'Bargarh', 'Barpali', 'Padampur', 'Sohela', 'Attabira', 'Bijepur',
                'Bhatli', 'Ambabhona', 'Jharbandh', 'Gaisilet', 'Melchhamunda', 'Remunda',
                'Godbhaga', 'Bardol', 'Tora', 'Sambalpuri Colony'
            ],
            'Bhadrak' => [
                'Bhadrak', 'Basudevpur', 'Chandabali', 'Dhamnagar', 'Bhandari Pokhari',
                'Bonth', 'Tihidi', 'Agarpada', 'Charampa', 'Randia', 'Gelpur', 'Kuansh',
                'Nalanga', 'Bant', 'Eram', 'Dhamra'
            ],
            'Boudh' => [
                'Boudh', 'Kantamal', 'Harabhanga', 'Manamunda', 'Purunakatak', 'Tikarpada',
                'Baunsuni', 'Brahmani', 'Dhalpur', 'Charichhak'
            ],
            'Cuttack' => [
                'Cuttack', 'Choudwar', 'Athagarh', 'Banki', 'Salepur', 'Niali', 'Narsinghpur',
                'Tigiria', 'Naraj', 'Barang', 'Phulnakhara', 'Bidanasi', 'CDA Sector 1',
                'CDA Sector 2', 'CDA Sector 6', 'CDA Sector 7', 'CDA Sector 9', 'CDA Sector 10',
                'CDA Sector 11', 'CDA Sector 13', 'Link Road', 'College Square', 'Badambadi',
                'Buxi Bazaar', 'Chandinichowk', 'Ranihat', 'Mangalabag', 'Tulsipur', 'Jobra',
                'Khan Nagar', 'Dolamundai', 'Chauliaganj', 'Jagatpur', 'Madhupatna',
                'Shelter Chhak', 'Naya Bazaar'
            ],
            'Deogarh' => [
                'Deogarh', 'Barkote', 'Reamal', 'Tileibani', 'Kundheigola', 'Kankadahada',
                'Balam', 'Riamal'
            ],
            'Dhenkanal' => [
                'Dhenkanal', 'Kamakhyanagar', 'Hindol', 'Bhuban', 'Gondia', 'Parjang',
                'Odapada', 'Kankadahad', 'Balimi', 'Motanga', 'Mahabirod', 'Meramandali',
                'Bhapur', 'Tumusinga', 'Rasol'
            ],
            'Gajapati' => [
                'Paralakhemundi', 'Kashinagar', 'R. Udayagiri', 'Mohana', 'Gumma',
                'Nuagada', 'Rayagada', 'Garabandha', 'Adaba', 'Serango'
            ],
            'Ganjam' => [
                'Berhampur', 'Chhatrapur', 'Gopalpur', 'Aska', 'Hinjilicut', 'Bhanjanagar',
                'Buguda', 'Polasara', 'Khallikote', 'Purushottampur', 'Digapahandi',
                'Kabisuryanagar', 'Kodala', 'Rambha', 'Chatrapur', 'Sheragada',
                'Sanakhemundi', 'Patrapur', 'Belaguntha', 'Sorada', 'Dharakote',
                'Kukudakhandi', 'Rangeilunda', 'Lanjipalli', 'Gosaninua Gaon',
                'Engineering School Road', 'Gate Bazaar', 'Courtpeta', 'Kamapalli',
                'New Bus Stand', 'Hillpatna', 'Ankuli', 'Haladiapadar', 'Golanthara'
            ],
            'Jagatsinghpur' => [
                'Jagatsinghpur', 'Paradeep', 'Kujang', 'Balikuda', 'Tirtol', 'Raghunathpur',
                'Naugaon', 'Biridi', 'Ersama', 'Paradipgarh', 'Atharbanki',
                'Abhayachandrapur', 'Dolipur', 'Madhuban', 'Badapadia'
            ],
            'Jajpur' => [
                'Jajpur', 'Jajpur Road', 'Vyasanagar', 'Dharmasala', 'Panikoili',
                'Binjharpur', 'Bari', 'Rasulpur', 'Sukinda', 'Korei', 'Dasarathpur',
                'Danagadi', 'Chandikhol', 'Kalinganagar', 'Duburi', 'Jakhapura',
                'Byree', 'Mangalpur'
            ],
            'Jharsuguda' => [
                'Jharsuguda', 'Brajarajnagar', 'Belpahar', 'Lakhanpur', 'Laikera',
                'Kolabira', 'Kirimira', 'Kirmira', 'Bandhbahal', 'Orient Area',
                'Gandhi Chowk', 'Sarbahal', 'Beheramal', 'Station Road', 'Sarandamal',
                'Mangal Bazaar'
            ],
            'Kalahandi' => [
                'Bhawanipatna', 'Dharamgarh', 'Junagarh', 'Kesinga', 'Narla', 'Lanjigarh',
                'Golamunda', 'Kalampur', 'Kokasara', 'Thuamul Rampur', 'Jaipatna',
                'M. Rampur', 'Karlamunda', 'Madanpur Rampur', 'Sadar', 'Risida'
            ],
            'Kandhamal' => [
                'Phulbani', 'Baliguda', 'G. Udayagiri', 'Daringbadi', 'Tikabali', 'Raikia',
                'Tumudibandh', 'K. Nuagaon', 'Chakapad', 'Khajuripada', 'Phiringia',
                'Kotagarh', 'Brahmanigaon', 'Sarangada'
            ],
            'Kendrapara' => [
                'Kendrapara', 'Pattamundai', 'Rajkanika', 'Aul', 'Rajnagar', 'Mahakalapada',
                'Derabish', 'Marsaghai', 'Garadpur', 'Nikirai', 'Indupur',
                'Pattamundai Bazar', 'Sanamangala'
            ],
            'Keonjhar' => [
                'Keonjhar', 'Barbil', 'Joda', 'Anandapur', 'Champua', 'Ghatgaon', 'Patna',
                'Banspal', 'Telkoi', 'Hatadihi', 'Jhumpura', 'Saharpada', 'Harichandanpur',
                'Baria', 'Bolani', 'Kiriburu Road', 'Joda East', 'Joda West'
            ],
            'Khordha' => [
                'Bhubaneswar', 'Khordha', 'Jatani', 'Balipatna', 'Balianta', 'Banapur',
                'Tangi', 'Begunia', 'Bolagarh', 'Chilika', 'Balugaon', 'Khurda Road',
                'Patia', 'Chandrasekharpur', 'Sailashree Vihar', 'Damana', 'KIIT Square',
                'Nandan Kanan', 'Jaydev Vihar', 'Saheed Nagar', 'Sahid Nagar', 'Rasulgarh',
                'Mancheswar', 'Bomikhal', 'Laxmi Sagar', 'Old Town', 'Khandagiri',
                'Jagamara', 'Dumduma', 'Tamando', 'Patrapada', 'Ghatikia', 'Sundarpada',
                'Pokhariput', 'Airport Area', 'Baramunda', 'Nayapalli', 'IRC Village',
                'Unit 4', 'Unit 6', 'Unit 8', 'Unit 9', 'Unit 3', 'Unit 1', 'VSS Nagar',
                'Acharya Vihar', 'BJB Nagar', 'Forest Park', 'Shyamapur', 'Jagannath Vihar',
                'GGP Colony'
            ],
            'Koraput' => [
                'Koraput', 'Jeypore', 'Sunabeda', 'Semiliguda', 'Kotpad', 'Damanjodi',
                'Laxmipur', 'Nandapur', 'Pottangi', 'Borrigumma', 'Bandhugaon',
                'Narayanpatna', 'Lamtaput', 'Machkund', 'Deomali', 'Kakiriguma', 'Dudhari'
            ],
            'Malkangiri' => [
                'Malkangiri', 'Balimela', 'Korukonda', 'Chitrakonda', 'Kalimela', 'Mathili',
                'Khairput', 'Podia', 'Motu', 'MV-79', 'Govindapalli', 'Panasput'
            ],
            'Mayurbhanj' => [
                'Baripada', 'Rairangpur', 'Karanjia', 'Udala', 'Betnoti', 'Badasahi',
                'Bangriposi', 'Jashipur', 'Kaptipada', 'Khunta', 'Kuliana', 'Rasgovindpur',
                'Bisoi', 'Kusumi', 'Morada', 'Suliapada', 'Thakurmunda', 'Baripada Town',
                'Takatpur', 'Puruna Baripada'
            ],
            'Nabarangpur' => [
                'Nabarangpur', 'Umerkote', 'Dabugam', 'Raighar', 'Papadahandi', 'Jharigam',
                'Chandahandi', 'Nandahandi', 'Tentulikhunti', 'Kosagumuda', 'Kodinga'
            ],
            'Nayagarh' => [
                'Nayagarh', 'Khandapada', 'Dasapalla', 'Ranpur', 'Odagaon', 'Nuagaon',
                'Bhapur', 'Gania', 'Itamati', 'Sarankul', 'Dasapalla Town', 'Kuanria',
                'Nayagarh Town'
            ],
            'Nuapada' => [
                'Nuapada', 'Khariar', 'Khariar Road', 'Sinapali', 'Boden', 'Komna',
                'Jonk', 'Dharambandha', 'Tarbod', 'Duajhar', 'Sunabeda'
            ],
            'Puri' => [
                'Puri', 'Konark', 'Pipili', 'Nimapara', 'Sakhigopal', 'Brahmagiri',
                'Kakatpur', 'Astaranga', 'Delanga', 'Gop', 'Krushnaprasad', 'Chandanpur',
                'Balighai', 'Swargadwar', 'Grand Road', 'Sea Beach', 'Chakratirtha Road',
                'Baliapanda', 'VIP Road', 'Station Road', 'Dolamandap Sahi', 'Narendra Kona'
            ],
            'Rayagada' => [
                'Rayagada', 'Gunupur', 'Muniguda', 'Bissamcuttack', 'Kashipur',
                'Kalyansinghpur', 'Padmapur', 'Gudari', 'Ramanaguda', 'Kolnara',
                'Chandrapur', 'Tikiri', 'Ambadola', 'Doraguda'
            ],
            'Sambalpur' => [
                'Sambalpur', 'Burla', 'Hirakud', 'Rengali', 'Kuchinda', 'Bamra',
                'Rairakhol', 'Naktideul', 'Jamankira', 'Jujumura', 'Dhankauda',
                'Maneswar', 'Ainthapali', 'Dhanupali', 'Khetrajpur', 'Modipara',
                'Budharaja', 'Sakhipara', 'Farm Road', 'Hirakud Colony', 'Burla Town',
                'Sambalpur University Area'
            ],
            'Subarnapur' => [
                'Sonepur', 'Birmaharajpur', 'Tarbha', 'Ullunda', 'Binika', 'Dunguripali',
                'Rampur', 'Binka', 'Subalaya', 'Sonepur Town', 'Sindurpur'
            ],
            'Sundargarh' => [
                'Sundargarh', 'Rourkela', 'Rajgangpur', 'Biramitrapur', 'Bonai',
                'Sundargarh Town', 'Lathikata', 'Kutra', 'Koida', 'Gurundia', 'Hemgir',
                'Lephripara', 'Tangarpali', 'Balishankara', 'Bisra', 'Panposh',
                'Bondamunda', 'Chhend Colony', 'Civil Township', 'Sector 1', 'Sector 2',
                'Sector 3', 'Sector 4', 'Sector 5', 'Sector 6', 'Sector 7', 'Sector 8',
                'Sector 9', 'Sector 10', 'Sector 13', 'Sector 15', 'Udit Nagar',
                'Basanti Colony', 'Koel Nagar', 'Jagda', 'Kalunga', 'Fertilizer Township'
            ],
        ];

        // Fetch all Odisha districts mapped by name
        $dbDistricts = DB::table('districts')
            ->where('state_id', $stateId)
            ->pluck('id', 'name')
            ->toArray();

        foreach ($dataset as $districtName => $localities) {
            $districtId = $dbDistricts[$districtName] ?? null;
            if (!$districtId) {
                // Check case-insensitive match
                foreach ($dbDistricts as $dName => $dId) {
                    if (strcasecmp($dName, $districtName) === 0) {
                        $districtId = $dId;
                        break;
                    }
                }
            }

            if (!$districtId) {
                $districtId = DB::table('districts')->insertGetId([
                    'state_id' => $stateId,
                    'name' => $districtName,
                    'slug' => str_replace(' ', '-', strtolower($districtName)),
                ]);
                $dbDistricts[$districtName] = $districtId;
            }

            foreach ($localities as $locName) {
                $locTrim = trim($locName);
                $locSlug = str_replace([' ', '/', '(', ')', '.'], ['-', '-', '', '', ''], strtolower($locTrim));
                $locSlug = preg_replace('/-+/', '-', trim($locSlug, '-'));

                $existing = DB::table('localities')
                    ->where('district_id', $districtId)
                    ->where('name', $locTrim)
                    ->first();

                if ($existing) {
                    DB::table('localities')->where('id', $existing->id)->update([
                        'slug' => $locSlug,
                    ]);
                } else {
                    DB::table('localities')->insert([
                        'district_id' => $districtId,
                        'name' => $locTrim,
                        'slug' => $locSlug,
                    ]);
                }
            }
        }

        Cache::forget('indian_location_data');
        Cache::forget('db_districts_by_state_v3');
    }
}
