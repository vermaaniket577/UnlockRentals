<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Curated list of blog posts
     */
    protected function getPosts()
    {
        return collect([
            [
                'id' => 1,
                'slug' => 'top-tips-for-first-time-renters',
                'title' => 'Top 10 Essential Tips for First-Time Renters in 2026',
                'excerpt' => 'Navigating your first rental property? Learn everything about rental agreements, security deposits, inspections, and hidden costs to avoid.',
                'category' => 'Tenant Guide',
                'read_time' => '5 min read',
                'published_at' => 'Aug 24, 2026',
                'author' => 'Priya Sharma',
                'author_role' => 'Real Estate Advisor',
                'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80',
                'featured' => true,
                'content' => "
                    <p class='lead'>Renting your first apartment or house is an exhilarating milestone. However, without proper planning, the process can quickly become overwhelming with security deposits, legal clauses, and landlord negotiations.</p>
                    
                    <h3>1. Define Your Realistic Budget</h3>
                    <p>The standard rule of thumb is the 30% rule: spend no more than 30% of your gross monthly income on rent. Don't forget utilities (electricity, water, Wi-Fi), maintenance fees, and parking charges.</p>
                    
                    <h3>2. Always Perform a Pre-Move-In Inspection</h3>
                    <p>Before signing the final lease or paying the security deposit, thoroughly document the condition of the home. Take high-resolution photos and videos of existing scratches, plumbing fittings, wall paint, and electrical points.</p>
                    
                    <h3>3. Understand the Rental Agreement Clauses</h3>
                    <p>Carefully review the lock-in period, notice period (usually 30 to 60 days), maintenance responsibilities, pet policies, and annual rent escalation percentages (standard is 5%–10%).</p>
                    
                    <h3>4. Verify Direct Landlord Credentials</h3>
                    <p>On UnlockRentals, properties are verified with direct owner listings to save you from paying unnecessary brokerage fees. Always verify property ownership documents before transferring token amounts.</p>
                    
                    <h3>5. Test Mobile Network and Commute During Peak Hours</h3>
                    <p>Visit the neighborhood during rush hours to evaluate traffic flow, proximity to metro or bus stations, grocery availability, and mobile network strength inside the property.</p>
                "
            ],
            [
                'id' => 2,
                'slug' => 'commercial-real-estate-trends-2026',
                'title' => 'Commercial Real Estate Trends: Finding High-Footfall Retail Spaces',
                'excerpt' => 'Discover key metrics and strategies to choose the most profitable retail shop or office space for your business venture.',
                'category' => 'Commercial Hub',
                'read_time' => '6 min read',
                'published_at' => 'Aug 20, 2026',
                'author' => 'Rahul Verma',
                'author_role' => 'Commercial Property Strategist',
                'author_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'content' => "
                    <p class='lead'>Selecting the right commercial property is critical for retail shops, cafes, clinics, and modern offices. Here is how modern commercial leasing has shifted in 2026.</p>
                    
                    <h3>Footfall Density vs. Vehicle Traffic</h3>
                    <p>High vehicle speed corridors rarely convert into walking retail footfall. Opt for commercial hubs near residential clusters, transit hubs, and commercial office towers with ample customer parking.</p>
                    
                    <h3>Zoning and Commercial Licensing Compliance</h3>
                    <p>Ensure the property has sanctioned commercial NOCs, fire safety clearances, power backup load capacity, and signage permissions from the local municipality before committing to a lease.</p>
                    
                    <h3>Flexible Fit-out Periods and Lease Terms</h3>
                    <p>Negotiate a rent-free fit-out period (usually 30–90 days) while you complete interior design and branding setup. Ensure renewal clauses protect against sudden rent spikes.</p>
                "
            ],
            [
                'id' => 3,
                'slug' => 'landlord-guide-maximizing-rental-yield',
                'title' => 'How Property Owners Can Maximize Rental Yield by 25%',
                'excerpt' => 'Smart upgrades, professional photography, digital listing optimization, and tenant screening techniques for maximum ROI.',
                'category' => 'Owner Insights',
                'read_time' => '4 min read',
                'published_at' => 'Aug 15, 2026',
                'author' => 'Ananya Roy',
                'author_role' => 'Investment Analyst',
                'author_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=120&q=80',
                'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'content' => "
                    <p class='lead'>Vacant rental properties cost property owners thousands in lost revenue every month. Here is how top property owners keep vacancy rates below 2%.</p>
                    
                    <h3>1. High-Impact, Low-Cost Upgrades</h3>
                    <p>Fresh neutral paint, modern LED warm lighting, modular kitchen organizers, and deep sanitization instantly increase the perceived value and perceived rental rate of your property.</p>
                    
                    <h3>2. Professional High-Resolution Photography</h3>
                    <p>Properties with well-lit, clutter-free photographs receive 3.5x more direct tenant inquiries on UnlockRentals compared to listings with dark, blurry photos.</p>
                    
                    <h3>3. Responsive Tenant Screening</h3>
                    <p>Quick responses to inquiries and streamlined digital identity verification build trust early, securing high-quality long-term tenants with reliable income profiles.</p>
                "
            ],
            [
                'id' => 4,
                'slug' => 'understanding-rental-laws-and-agreements',
                'title' => 'Understanding Rental Agreements: Stamp Duty, Registration & Tenant Rights',
                'excerpt' => 'A comprehensive guide on legal obligations, electronic registration of leases, eviction rules, and deposit security regulations.',
                'category' => 'Legal & Finance',
                'read_time' => '7 min read',
                'published_at' => 'Aug 10, 2026',
                'author' => 'Vikram Singhania',
                'author_role' => 'Legal Consultant',
                'author_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'content' => "
                    <p class='lead'>A legally binding, registered rent agreement protects both landlords and tenants against unexpected disputes, non-payment, or unilateral lease terminations.</p>
                    
                    <h3>Why 11-Month Agreements are Common</h3>
                    <p>Under the Registration Act of 1908, leases exceeding 11 months require mandatory registration and higher stamp duty fees. However, longer leases provide greater tenure security for families and businesses.</p>
                    
                    <h3>Security Deposit Refund Terms</h3>
                    <p>The agreement must explicitly state the timeline for deposit refund post-vacation (typically within 7 to 30 days) and clearly define reasonable wear-and-tear deductions.</p>
                "
            ],
            [
                'id' => 5,
                'slug' => 'smart-home-features-modern-tenants-want',
                'title' => 'Smart Home Features Modern Tenants Are Willing to Pay More For',
                'excerpt' => 'From smart digital locks to EV charging points and energy-efficient appliances, discover the amenities driving rental demand.',
                'category' => 'Lifestyle & Tech',
                'read_time' => '4 min read',
                'published_at' => 'Aug 05, 2026',
                'author' => 'Priya Sharma',
                'author_role' => 'Real Estate Advisor',
                'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
                'image' => 'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'content' => "
                    <p class='lead'>Millennial and Gen-Z tenants prioritize convenience, smart automation, and sustainability when choosing their next home.</p>
                    
                    <h3>Key Amenities in Demand:</h3>
                    <ul>
                        <li><strong>Keyless Smart Door Locks:</strong> Fingerprint and PIN-based entry systems provide peace of mind and convenience.</li>
                        <li><strong>High-Speed Fiber Readiness:</strong> Dedicated work-from-home desk setups with multiple power ports.</li>
                        <li><strong>Inverter / Power Backup:</strong> Essential for uninterrupted remote working.</li>
                        <li><strong>EV Charging Points:</strong> Dedicated charging sockets in car parking bays are becoming a deciding factor.</li>
                    </ul>
                "
            ]
        ]);
    }

    /**
     * Blog listing page
     */
    public function index(Request $request)
    {
        $posts = $this->getPosts();

        if ($request->filled('category') && $request->category !== 'all') {
            $posts = $posts->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $posts = $posts->filter(function($post) use ($search) {
                return str_contains(strtolower($post['title']), $search) ||
                       str_contains(strtolower($post['excerpt']), $search) ||
                       str_contains(strtolower($post['category']), $search);
            });
        }

        $categories = $this->getPosts()->pluck('category')->unique();
        $featuredPost = $this->getPosts()->firstWhere('featured', true) ?? $this->getPosts()->first();

        return view('blog.index', compact('posts', 'categories', 'featuredPost'));
    }

    /**
     * Single blog post
     */
    public function show($slug)
    {
        $post = $this->getPosts()->firstWhere('slug', $slug);

        if (!$post) {
            abort(404, 'Blog post not found.');
        }

        $relatedPosts = $this->getPosts()
            ->where('slug', '!=', $slug)
            ->take(3);

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
