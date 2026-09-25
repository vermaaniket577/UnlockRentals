<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfessionalMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrician',
                'slug' => 'electrician',
                'icon' => 'ph-bold ph-lightning',
                'short_description' => 'Certified electricians for fan, wiring, switch, MCB, inverter & emergency electrical repair.',
                'description' => 'Connect with trusted and verified local electricians for all your home, office, and apartment electrical requirements. From emergency short-circuit repairs to full house rewiring and appliance installations.',
                'seo_title' => 'Electricians Near Me | Best Local Electricians & Electrical Services - UnlockRentals',
                'seo_description' => 'Find verified electricians near you for fan repair, switchboard installation, wiring, inverter setup, and 24/7 emergency electrical services on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 1,
                'services' => [
                    'Fan Installation & Repair',
                    'Switch & Socket Repair',
                    'House Wiring & Rewiring',
                    'MCB & Fuse Box Repair',
                    'Inverter & Battery Setup',
                    'Light & Chandelier Installation',
                    'Electrical Appliance Repair',
                    'Emergency Electrical Service',
                    'Geyser & Water Heater Wiring',
                ],
            ],
            [
                'name' => 'Plumber',
                'slug' => 'plumber',
                'icon' => 'ph-bold ph-drop',
                'short_description' => 'Expert plumbers for tap repair, pipe leakages, bathroom fittings & water tank cleaning.',
                'description' => 'Hire top-rated plumbers in your locality for quick leak fixes, toilet installations, drainage unblocking, sanitary fittings, and water motor connections.',
                'seo_title' => 'Plumbers Near Me | Local Plumbing Services & Repair - UnlockRentals',
                'seo_description' => 'Find licensed and verified plumbers near you for water leakage, bathroom fittings, pipe repair, and water tank cleaning on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 2,
                'services' => [
                    'Tap & Shower Repair',
                    'Pipe Leakage & Burst Repair',
                    'Bathroom Fitting Installation',
                    'Toilet & Commode Repair',
                    'Water Tank Cleaning & Setup',
                    'Drain & Sewer Blockage Clearing',
                    'Water Motor & Pump Installation',
                    'Kitchen Sink & Waste Pipe Plumbing',
                ],
            ],
            [
                'name' => 'Carpenter',
                'slug' => 'carpenter',
                'icon' => 'ph-bold ph-hammer',
                'short_description' => 'Skilled carpenters for furniture repair, modular kitchens, door fittings & custom woodwork.',
                'description' => 'Find skilled local carpenters for custom wood crafting, door lock repairs, bed assembly, cupboard adjustments, modular kitchen fittings, and termite damage restoration.',
                'seo_title' => 'Carpenters Near Me | Local Carpentry & Furniture Repair - UnlockRentals',
                'seo_description' => 'Connect with experienced carpenters in your city for door repairs, custom furniture, modular kitchen, and wood polishing on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 3,
                'services' => [
                    'Furniture Repair & Assembly',
                    'Door & Window Latch / Lock Repair',
                    'Cupboard & Wardrobe Repair',
                    'Bed & Sofa Frame Repair',
                    'Modular Kitchen Woodwork',
                    'Wood Polishing & Varnish',
                    'Custom Shelves & Cabinets',
                    'Mesh & Mosquito Net Fitting',
                ],
            ],
            [
                'name' => 'Painter',
                'slug' => 'painter',
                'icon' => 'ph-bold ph-paint-brush',
                'short_description' => 'Professional house painters for interior, exterior, texture painting & waterproofing.',
                'description' => 'Give your home, rental apartment, or office a fresh look with verified professional painters. Offering interior wall painting, exterior weatherproof coating, and damp proofing.',
                'seo_title' => 'Painters Near Me | Professional House Painting & Whitewash - UnlockRentals',
                'seo_description' => 'Hire trusted painters near you for interior, exterior, stencil, and waterproofing painting services with zero hassle on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 4,
                'services' => [
                    'Interior Wall Painting',
                    'Exterior Weatherproof Painting',
                    'Wall Texture & Stencil Art',
                    'Waterproofing & Damp Repair',
                    'Whitewash & Distemper',
                    'Wood & Metal Enamel Painting',
                    'Wallpaper Installation & Removal',
                    'Rental Move-Out Fresh Paint',
                ],
            ],
            [
                'name' => 'CCTV Professional',
                'slug' => 'cctv-professional',
                'icon' => 'ph-bold ph-video-camera',
                'short_description' => 'CCTV camera installation, DVR/NVR configuration & security surveillance maintenance.',
                'description' => 'Secure your residential property, PG, shop, or commercial office with certified CCTV camera technicians. Expert setup of dome cameras, bullet cameras, and mobile remote monitoring.',
                'seo_title' => 'CCTV Installation Near Me | Security Camera Services - UnlockRentals',
                'seo_description' => 'Find top-rated CCTV installation and repair technicians near you for home, flat, and office security surveillance on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 5,
                'services' => [
                    'Home CCTV Camera Installation',
                    'Commercial / Office Security Setup',
                    'DVR / NVR Configuration & Hard Disk Upgrade',
                    'WiFi / IP Wireless Camera Setup',
                    'Mobile Remote Viewing Setup',
                    'CCTV Repair & Cable Replacement',
                    'Biometric & Intercom Integration',
                ],
            ],
            [
                'name' => 'IT Professional',
                'slug' => 'it-professional',
                'icon' => 'ph-bold ph-laptop',
                'short_description' => 'IT support, computer repair, WiFi router setup, printer troubleshooting & data recovery.',
                'description' => 'Get fast doorstep and remote IT technical assistance for laptop repair, desktop troubleshooting, home office network setup, virus removal, and smart home device connectivity.',
                'seo_title' => 'IT Support & Computer Repair Near Me - UnlockRentals',
                'seo_description' => 'Hire certified IT professionals and computer technicians for laptop repair, WiFi setup, printer issues, and network support on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 6,
                'services' => [
                    'Laptop & Desktop Repair',
                    'Windows / Mac OS Installation & Tuning',
                    'WiFi Router & Mesh Network Setup',
                    'Printer Installation & Network Sharing',
                    'Virus & Malware Removal',
                    'Data Recovery & Backup Solutions',
                    'Smart TV & Smart Home Setup',
                ],
            ],
            [
                'name' => 'Labour',
                'slug' => 'labour',
                'icon' => 'ph-bold ph-hard-hat',
                'short_description' => 'Daily wage helpers, shifting labour, construction helpers & property maintenance labour.',
                'description' => 'Book reliable and hardworking daily labour helpers for home shifting, furniture loading/unloading, construction work, debris clearing, garden maintenance, and general manual work.',
                'seo_title' => 'Daily Labour Near Me | Shifting & Construction Helpers - UnlockRentals',
                'seo_description' => 'Find trusted local daily labour, house shifting helpers, and construction workers for hire on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 7,
                'services' => [
                    'Home Shifting & Loading/Unloading',
                    'Construction & Renovation Helper',
                    'Debris & Garbage Removal',
                    'Garden & Lawn Clearing',
                    'General Manual & Warehouse Helper',
                    'Heavy Furniture Moving',
                ],
            ],
            [
                'name' => 'Mason',
                'slug' => 'mason',
                'icon' => 'ph-bold ph-wall',
                'short_description' => 'Expert masons (mistri) for brickwork, plastering, tile fitting & concrete repairs.',
                'description' => 'Experienced masons (raj mistri) for floor tile installation, wall plastering, bathroom remodeling, boundary wall construction, and cracks restoration.',
                'seo_title' => 'Masons Near Me | Raj Mistri & Tile Fitting Services - UnlockRentals',
                'seo_description' => 'Hire experienced masons for brickwork, floor tiles, wall plaster, and home masonry repair on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 8,
                'services' => [
                    'Floor & Wall Tile Installation',
                    'Wall Plastering & Crack Repair',
                    'Brickwork & Partition Walls',
                    'Bathroom & Kitchen Tiling',
                    'Marble & Granite Fitting',
                    'Roof Waterproofing Plaster',
                ],
            ],
            [
                'name' => 'Mechanic',
                'slug' => 'mechanic',
                'icon' => 'ph-bold ph-wrench',
                'short_description' => 'Doorstep automobile mechanics for 2-wheeler & 4-wheeler repair, servicing & breakdown help.',
                'description' => 'Find verified mobile vehicle mechanics for on-the-spot puncture repair, battery jump-start, oil changes, brake adjustments, and emergency roadside breakdown assistance.',
                'seo_title' => 'Mechanics Near Me | Two & Four Wheeler Repair - UnlockRentals',
                'seo_description' => 'Find reliable automobile mechanics near you for doorstep bike/car servicing, battery jumpstart, and breakdown assistance on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 9,
                'services' => [
                    'Two-Wheeler Doorstep Servicing',
                    'Four-Wheeler Emergency Breakdown',
                    'Battery Jump-Start & Replacement',
                    'Brake & Clutch Repair',
                    'Puncture & Tyre Air Service',
                    'Engine Oil & Filter Change',
                ],
            ],
            [
                'name' => 'Driver',
                'slug' => 'driver',
                'icon' => 'ph-bold ph-steering-wheel',
                'short_description' => 'Verified personal drivers for daily commute, outstation trips & hourly hire.',
                'description' => 'Hire background-checked personal car drivers with clean driving records for city commuting, airport transfers, family vacations, or monthly dedicated driver arrangements.',
                'seo_title' => 'Drivers on Demand Near Me | Personal & Outstation Drivers - UnlockRentals',
                'seo_description' => 'Book verified personal car drivers for hourly, daily, outstation, or monthly requirements on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 10,
                'services' => [
                    'Hourly City Car Driver',
                    'Outstation / Highway Trip Driver',
                    'Monthly Dedicated Car Driver',
                    'Night / Party Pickup Driver',
                    'Airport Drop & Pickup Driver',
                ],
            ],
            [
                'name' => 'Security Guard',
                'slug' => 'security-guard',
                'icon' => 'ph-bold ph-shield-check',
                'short_description' => 'Trained security personnel for residential societies, apartments, villas & commercial properties.',
                'description' => 'Hire professional and alert security guards, bouncers, and gatekeepers for residential societies, individual bungalows, commercial offices, and private events.',
                'seo_title' => 'Security Guards Near Me | Residential & Commercial Security - UnlockRentals',
                'seo_description' => 'Connect with trained security personnel and guard agencies for apartments, gated societies, and offices on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 11,
                'services' => [
                    'Residential Apartment Security Guard',
                    'Commercial / Office Building Guard',
                    'Night Shift Gatekeeper',
                    'Personal Event Security / Bouncers',
                    'Society Gate Access Management',
                ],
            ],
            [
                'name' => 'Laundry',
                'slug' => 'laundry',
                'icon' => 'ph-bold ph-t-shirt',
                'short_description' => 'Doorstep laundry, dry cleaning, steam ironing & blanket washing services.',
                'description' => 'Convenient doorstep pickup and delivery for wash & fold, steam pressing, dry cleaning, curtain washing, and heavy quilt/blanket laundry for busy tenants and families.',
                'seo_title' => 'Laundry & Dry Cleaning Near Me - UnlockRentals',
                'seo_description' => 'Find top-rated laundry and dry cleaning services near you with free doorstep pickup and delivery on UnlockRentals.',
                'status' => 'active',
                'sort_order' => 12,
                'services' => [
                    'Wash & Fold Regular Laundry',
                    'Wash & Steam Ironing',
                    'Suit & Blazer Dry Cleaning',
                    'Curtain & Bedspread Washing',
                    'Heavy Quilt / Blanket Dry Clean',
                    'Express Same-Day Laundry',
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $services = $catData['services'];
            unset($catData['services']);

            $existingCategory = DB::table('professional_categories')->where('slug', $catData['slug'])->first();

            if ($existingCategory) {
                DB::table('professional_categories')->where('id', $existingCategory->id)->update(array_merge($catData, [
                    'updated_at' => now(),
                ]));
                $categoryId = $existingCategory->id;
            } else {
                $categoryId = DB::table('professional_categories')->insertGetId(array_merge($catData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            $serviceOrder = 1;
            foreach ($services as $serviceName) {
                $serviceSlug = Str::slug($serviceName);

                $existingService = DB::table('professional_services')
                    ->where('category_id', $categoryId)
                    ->where('slug', $serviceSlug)
                    ->first();

                if (!$existingService) {
                    DB::table('professional_services')->insert([
                        'category_id' => $categoryId,
                        'name' => $serviceName,
                        'slug' => $serviceSlug,
                        'description' => "Professional {$serviceName} service with verified local technicians.",
                        'status' => 'active',
                        'sort_order' => $serviceOrder++,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
