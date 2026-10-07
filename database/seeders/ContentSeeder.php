<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Office;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Site-wide settings
        Setting::updateOrCreate(
            ['key' => 'site_name'],
            [
                'value' => 'Update Architects & Engineering',
                'group' => 'general',
                'description' => 'Site name',
                'public' => true,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'tagline'],
            [
                'value' => 'Quality Design • Quality Construction',
                'group' => 'general',
                'description' => 'Company tagline',
                'public' => true,
            ]
        );

        // Seeded once only: these two are edited from the admin settings screen,
        // so re-running the seeder must not wipe what was entered there.
        Setting::firstOrCreate(
            ['key' => 'footer.tagline'],
            [
                'value' => 'Architecture • Engineering • Construction Consultancy • Interior Design',
                'group' => 'general',
                'description' => 'Discipline line shown under the logo in the footer',
                'public' => true,
            ]
        );

        Setting::firstOrCreate(
            ['key' => 'footer.social_links'],
            [
                'value' => ['whatsapp' => 'https://wa.me/8801751585650'],
                'group' => 'general',
                'description' => 'Social profiles in the footer, as JSON: {"facebook": "https://…", "linkedin": "https://…"}',
                'public' => true,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'whatsapp.number'],
            [
                'value' => '+8801751585650',
                'group' => 'general',
                'description' => 'Number that opens WhatsApp chat (with country code, e.g. +88017...',
                'public' => true,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'whatsapp.message'],
            [
                'value' => 'Hello! I would like to discuss a project with Update Architects.',
                'group' => 'general',
                'description' => 'Pre-filled message when a visitor taps the WhatsApp button',
                'public' => true,
            ]
        );

        // CMS-managed hero slides mirror the previous hard-coded hero.
        $this->call(HeroSlideSeeder::class);

        // Offices - the three locations provided in the briefing
        $offices = [
            [
                'name' => 'Kurigram Head Office',
                'address' => "Sonamoni Pump, C & B More,\nKurigram Sadar, Kurigram",
                'phone' => '+8801751585650',
                'email' => 'updatearchitects120@gmail.com',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kurigram Branch Office',
                'address' => "Alia Madrasha Market, 1st Floor,\nNageshwari, Kurigram",
                'phone' => '+8801751585650',
                'email' => 'updatearchitects120@gmail.com',
                'sort_order' => 2,
            ],
            [
                'name' => 'Rangpur Office',
                'address' => "Central Bus Terminal Road,\nRangpur City Corporation, Rangpur",
                'phone' => '+8801751585650',
                'email' => 'updatearchitects120@gmail.com',
                'sort_order' => 3,
            ],
        ];

        foreach ($offices as $office) {
            Office::updateOrCreate(
                ['slug' => Str::slug($office['name'])],
                array_merge($office, ['locale' => 'en', 'visibility' => true])
            );
        }

        // Team members (details as supplied by the practice)
        $team = [
            [
                'name' => 'Engr. Dr. Sharifullah Ahmed, P.Eng.',
                'designation' => 'Geotechnical & Structural Consultant',
                'qualification' => 'Ph.D. Scholar, Geotechnical, BUET',
                'expertise' => 'Geotechnical engineering',
                'registration' => 'FIEB/13409',
                'sort_order' => 1,
            ],
            [
                'name' => 'Engr. Md. Faruk Mia',
                'designation' => 'Managing Director',
                'qualification' => 'Update Architects & Engineering',
                'sort_order' => 2,
            ],
            [
                'name' => 'Engr. Rifat Hossain',
                'designation' => 'Site Engineer',
                'qualification' => 'B.Sc. in Civil Engineering — CUET',
                'expertise' => 'Site Supervision',
                'sort_order' => 6,
            ],
            [
                'name' => 'Engr. Sharmin Akter',
                'designation' => 'Interior Designer',
                'qualification' => 'B.Sc. in Architecture — KUET',
                'expertise' => 'Interior Design',
                'sort_order' => 7,
            ],
            [
                'name' => 'Arch. Palash Sheikh',
                'designation' => 'Architect',
                'qualification' => 'Bachelor of Architecture — DUET',
                'expertise' => 'Architectural Design',
                'sort_order' => 3,
            ],
            [
                'name' => 'Engr. Jobaidul Hoque',
                'designation' => 'Civil Engineer',
                'qualification' => 'B.Sc. in Civil Engineering — IUBAT',
                'registration' => 'IEB-48672',
                'sort_order' => 4,
            ],
            [
                'name' => 'Engr. Md. Rasel Mia',
                'designation' => 'Electrical Engineer',
                'qualification' => 'B.Sc. in EEE (AMIE)',
                'registration' => 'Enrolled, IEB',
                'expertise' => 'Electrical Design',
                'sort_order' => 5,
            ],
        ];

        foreach ($team as $member) {
            TeamMember::updateOrCreate(
                ['slug' => Str::slug($member['name'])],
                array_merge($member, ['locale' => 'en', 'visibility' => true])
            );
        }

        // Services
        $serviceCategories = [
            ['name' => 'Architecture', 'slug' => 'architecture'],
            ['name' => 'Structural Engineering', 'slug' => 'structural'],
            ['name' => 'Geotechnical Engineering', 'slug' => 'geotechnical'],
            ['name' => 'Construction Consultancy', 'slug' => 'construction'],
            ['name' => 'Interior Design', 'slug' => 'interior'],
            ['name' => 'MEP', 'slug' => 'mep'],
            ['name' => 'Approval', 'slug' => 'approval'],
            ['name' => 'Documents', 'slug' => 'documentation'],
        ];
        foreach ($serviceCategories as $sc) {
            ServiceCategory::firstOrCreate($sc);
        }

        // Services, one per discipline offered by the practice
        $services = [
            [
                'name' => 'Architectural Design',
                'slug' => 'architectural-design',
                'category' => 'architecture',
                'short_description' => 'Architectural planning and design for residential, commercial and institutional buildings.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Structural Design',
                'slug' => 'structural-design',
                'category' => 'structural',
                'short_description' => 'Structural design and detailing for safe, economical and durable buildings.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Geotechnical Engineering',
                'slug' => 'geotechnical-engineering',
                'category' => 'geotechnical',
                'short_description' => 'Sub-soil investigation and foundation engineering for sound ground conditions.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Construction Consultancy',
                'slug' => 'construction-consultancy',
                'category' => 'construction',
                'short_description' => 'Site supervision and construction management with continuous quality control.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Interior Design',
                'slug' => 'interior-design',
                'category' => 'interior',
                'short_description' => 'Interior design and space planning for homes, offices and hospitality spaces.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Electrical Design',
                'slug' => 'electrical-design',
                'category' => 'mep',
                'short_description' => 'Electrical design and load planning integrated with the architectural and structural design.',
                'sort_order' => 6,
            ],
            [
                'name' => 'RAJUK Approval',
                'slug' => 'rajuk-approval',
                'category' => 'approval',
                'short_description' => 'Preparation and processing of RAJUK approval drawings and documentation.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Bank Loan Sheet',
                'slug' => 'bank-loan-sheet',
                'category' => 'documentation',
                'short_description' => 'Accurate construction cost documentation prepared for bank loan processing.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Urban Planning',
                'slug' => 'urban-planning',
                'category' => 'architecture',
                'short_description' => 'Master planning, zoning analysis and urban design for towns and neighborhoods.',
                'sort_order' => 9,
            ],
            [
                'name' => 'Landscape Design',
                'slug' => 'landscape-design',
                'category' => 'interior',
                'short_description' => 'Outdoor spaces, gardens and site landscaping that complement the building design.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Project Management',
                'slug' => 'project-management',
                'category' => 'construction',
                'short_description' => 'End-to-end project management from inception to handover with clear reporting.',
                'sort_order' => 11,
            ],
            [
                'name' => 'Building Renovation',
                'slug' => 'building-renovation',
                'category' => 'construction',
                'short_description' => 'Structural assessment and renovation of existing buildings for new uses.',
                'sort_order' => 12,
            ],
            [
                'name' => 'MEP Design',
                'slug' => 'mep-design',
                'category' => 'mep',
                'short_description' => 'Mechanical, electrical and plumbing design integrated with the building systems.',
                'sort_order' => 13,
            ],
            [
                'name' => 'Fire Safety Design',
                'slug' => 'fire-safety-design',
                'category' => 'structural',
                'short_description' => 'Fire safety planning, egress design and compliance documentation for buildings.',
                'sort_order' => 14,
            ],
            [
                'name' => 'Acoustic Design',
                'slug' => 'acoustic-design',
                'category' => 'interior',
                'short_description' => 'Acoustic treatment and sound control for offices, schools and hospitality spaces.',
                'sort_order' => 15,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                [
                    'name' => $service['name'],
                    'category_id' => ServiceCategory::where('slug', $service['category'])->value('id'),
                    'short_description' => $service['short_description'],
                    'visibility' => true,
                    'sort_order' => $service['sort_order'],
                    'locale' => 'en',
                ]
            );
        }

        // Project categories (mirrors our project categories)
        $projectCategories = [
            ['name' => 'Residential', 'slug' => 'residential'],
            ['name' => 'Commercial', 'slug' => 'commercial'],
            ['name' => 'Industrial', 'slug' => 'industrial'],
            ['name' => 'Institutional', 'slug' => 'institutional'],
            ['name' => 'Infrastructure', 'slug' => 'infrastructure'],
            ['name' => 'Interior', 'slug' => 'interior'],
            ['name' => 'Construction', 'slug' => 'construction'],
            ['name' => 'Structural', 'slug' => 'structural'],
            ['name' => 'Geotechnical', 'slug' => 'geotechnical'],
        ];
        foreach ($projectCategories as $pc) {
            ProjectCategory::firstOrCreate($pc);
        }

        // The only project documented by the practice so far
        $project = Project::updateOrCreate(
            ['slug' => '2-storey-residential-building'],
            [
                'title' => '2-Storey Residential Building',
                'summary' => 'Ongoing 2-storey residential building at Palashbari West Para, Belgacha, Kurigram Sadar.',
                'category' => 'residential',
                'location' => 'Palashbari West Para, Belgacha, Kurigram Sadar, Kurigram',
                'area' => '1,550 sq.ft',
                'floors' => 2,
                'status' => 'ongoing',
                'featured' => true,
                'description' => 'Alhamdulillah, base casting successfully completed. Our team remains committed to maintaining quality control throughout every stage of construction.',
                'seo' => [
                    'title' => '2-Storey Residential Building — Update Architects & Engineering',
                    'description' => 'Base casting successfully completed for the 2-storey residential building at Palashbari West Para, Belgacha, Kurigram Sadar.',
                ],
                'published_at' => now(),
                'sort_order' => 1,
                'locale' => 'en',
            ]
        );

        // Construction stages reported by the site team
        $progressStages = [
            ['title' => 'Site Survey & Planning', 'status' => 'completed', 'is_completed' => true, 'sort_order' => 1],
            ['title' => 'Concept & Architectural Design', 'status' => 'completed', 'is_completed' => true, 'sort_order' => 2],
            ['title' => 'Structural Design', 'status' => 'completed', 'is_completed' => true, 'sort_order' => 3],
            ['title' => 'Sub-Soil Investigation', 'status' => 'completed', 'is_completed' => true, 'sort_order' => 4],
            ['title' => 'Base Casting', 'status' => 'completed', 'is_completed' => true, 'sort_order' => 5],
            ['title' => 'Column & Roof Slab', 'status' => 'in_progress', 'is_completed' => false, 'sort_order' => 6],
            ['title' => 'Finishing Works', 'status' => 'pending', 'is_completed' => false, 'sort_order' => 7],
            ['title' => 'Handover', 'status' => 'pending', 'is_completed' => false, 'sort_order' => 8],
        ];

        foreach ($progressStages as $stage) {
            $project->progressItems()->updateOrCreate(
                ['slug' => Str::slug($stage['title'])],
                array_merge($stage, ['visibility' => true, 'locale' => 'en'])
            );
        }

        // Demo portfolio fills the homepage grid and projects page
        $demoProjects = [
            [
                'slug' => 'kajamar-primary-school-extension',
                'title' => 'Kajamar Primary School Extension',
                'summary' => 'A two-storey classroom block designed for a dense Rangpur Sadar site, with deep, ventilated verandas on every floor.',
                'category' => 'institutional',
                'location' => 'Rangpur Sadar, Rangpur',
                'area' => '12,000 sq.ft',
                'floors' => 2,
                'status' => 'ongoing',
                'year' => 2026,
            ],
            [
                'slug' => 'highway-retail-plaza',
                'title' => 'Highway Retail Plaza',
                'summary' => 'Single-storey retail frontage along the Rangpur-Dhaka highway with Sultan-Alam market flows.',
                'category' => 'commercial',
                'location' => 'Rangpur Sadar, Rangpur',
                'area' => '28,500 sq.ft',
                'floors' => 1,
                'status' => 'completed',
                'year' => 2024,
            ],
            [
                'slug' => 'modopur-kindergarten-wing',
                'title' => 'Modopur Kindergarten Wing',
                'summary' => 'Creative play areas and a bright, day-lit classroom wing for a community kindergarten.',
                'category' => 'institutional',
                'location' => 'Kurigram Sadar, Kurigram',
                'area' => '6,400 sq.ft',
                'floors' => 2,
                'status' => 'completed',
                'year' => 2023,
            ],
            [
                'slug' => 'river-lane-rowhouses',
                'title' => 'River Lane Row Houses',
                'summary' => 'A cluster of row houses stepping down toward the river with shared courtyard spaces.',
                'category' => 'residential',
                'location' => 'Phulbari, Dinajpur',
                'area' => '4 x 1,800 sq.ft',
                'floors' => 3,
                'status' => 'completed',
                'year' => 2025,
            ],
            [
                'slug' => 'sunlight-interior-concept',
                'title' => 'Sun-Aligned Interior Concept',
                'summary' => 'An interior package for a private residence in Rangpur, driven to maximise winter sun and cool airflow.',
                'category' => 'interior',
                'location' => 'Horinc Limited, Rangpur',
                'area' => '3,600 sq.ft',
                'floors' => null,
                'status' => 'upcoming',
                'year' => null,
            ],
            [
                'slug' => 'community-health-center',
                'title' => 'Community Health Center',
                'summary' => 'A single-storey community health center with waiting halls, consultation rooms and a pharmacy.',
                'category' => 'institutional',
                'location' => 'Ulipur, Kurigram',
                'area' => '8,200 sq.ft',
                'floors' => 1,
                'status' => 'ongoing',
                'year' => 2026,
            ],
            [
                'slug' => 'riverside-mosque-complex',
                'title' => 'Riverside Mosque Complex',
                'summary' => 'A mosque complex with a prayer hall, ablution area and a small library, oriented toward the qibla.',
                'category' => 'institutional',
                'location' => 'Chilmari, Kurigram',
                'area' => '5,500 sq.ft',
                'floors' => 2,
                'status' => 'completed',
                'year' => 2024,
            ],
            [
                'slug' => 'downtown-office-tower',
                'title' => 'Downtown Office Tower',
                'summary' => 'A four-storey office tower with a double-height lobby and flexible floor plates.',
                'category' => 'commercial',
                'location' => 'Rangpur Sadar, Rangpur',
                'area' => '22,000 sq.ft',
                'floors' => 4,
                'status' => 'ongoing',
                'year' => 2026,
            ],
            [
                'slug' => 'heritage-hotel-renovation',
                'title' => 'Heritage Hotel Renovation',
                'summary' => 'Careful renovation of a heritage hotel facade with restored cornices and new services.',
                'category' => 'commercial',
                'location' => 'Rangpur Sadar, Rangpur',
                'area' => '15,000 sq.ft',
                'floors' => 3,
                'status' => 'upcoming',
                'year' => null,
            ],
        ];

        foreach ($demoProjects as $i => $p) {
            Project::updateOrCreate(
                ['slug' => $p['slug']],
                array_merge($p, [
                    'description' => $p['summary'],
                    'featured' => false,
                    // Spread across the last two months. The step must stay
                    // small enough that the last entry never lands in the
                    // future, otherwise it would silently stay unpublished.
                    'published_at' => now()->subDays(max(1, 60 - ($i * 6))),
                    'sort_order' => 10 + $i,
                    'locale' => 'en',
                    'seo' => [
                        'title' => $p['title'].' — Update Architects & Engineering',
                        'description' => str($p['summary'])->limit(150)->toString(),
                    ],
                ])
            );
        }

        // Demo insights to populate the homepage and /insights page
        $admin = User::where('email', 'admin@updatearchitects.com')->first();

        $insightPosts = [
            [
                'slug' => 'reading-a-soil-investigation-report',
                'title' => 'How to Read a Soil Investigation Report',
                'excerpt' => 'Understanding borehole logs, safe bearing capacity and foundation recommendations before construction.',
                'content' => 'A soil investigation summarises what lies under your plot. Key indicators include the SPT N-value and the allowable bearing capacity at foundation level. Reading it early lets you plan the right foundation and avoid expensive surprises on site.',
                'category_slug' => 'geotechnical-engineering',
                'reading_time' => 5,
                'days_ago' => 3,
            ],
            [
                'slug' => 'base-casting-checklist-before-concrete',
                'title' => 'Base Casting Checklist Before Concrete',
                'excerpt' => 'Five checks worth making before base casting — starter, shuttering, concreting and curing.',
                'content' => 'Before base casting begins, confirm reinforcement is properly tied, shuttering is tight and level, cover block cover is adequate, and a concrete pour plan is in place. Proper curing then starts within hours which determines long-term strength.',
                'category_slug' => 'construction',
                'reading_time' => 6,
                'days_ago' => 14,
            ],
            [
                'slug' => 'rajuk-approval-building-plan',
                'title' => 'What a RAJUK Building Plan Submission Needs',
                'excerpt' => 'A practical walkthrough of the drawings and supporting documents a typical submission package carries.',
                'content' => 'A RAJUK-style submission usually includes the cadastral map with land ownership, site plan, floor plans at a consistent scale, elevations, sections, and a life-safety note. We coordinate the package and revise it as the reviewing authority asks.',
                'category_slug' => 'architecture',
                'reading_time' => 7,
                'days_ago' => 28,
            ],
            [
                'slug' => 'five-questions-before-hiring-a-contractor',
                'title' => 'Five Questions to Ask Your Contractor',
                'excerpt' => 'Experience, crew, timeline and a contract sample — the questions that protect a client from the start.',
                'content' => 'Ask about current staff and past project references. Ask who signs for variations and how progress will be certified. Ask how long a similar project took and whether the crew is or subcontractors are employed. Clear answers save time later.',
                'category_slug' => 'construction',
                'reading_time' => 4,
                'days_ago' => 45,
            ],
            [
                'slug' => 'foundation-types-for-bangladesh-soil',
                'title' => 'Foundation Types for Bangladesh Soil',
                'excerpt' => 'Strip, raft or pile — how soil conditions and building loads decide the right foundation.',
                'content' => 'The right foundation depends on soil bearing capacity and the building load. Strip footings suit light loads on firm ground, raft foundations spread loads on softer soil, and piles transfer load to deeper strata. A proper soil investigation decides which is economical and safe.',
                'category_slug' => 'geotechnical-engineering',
                'reading_time' => 6,
                'days_ago' => 60,
            ],
            [
                'slug' => 'passive-cooling-in-hot-climates',
                'title' => 'Passive Cooling in Hot Climates',
                'excerpt' => 'Orientation, shading and cross-ventilation — design moves that reduce mechanical cooling.',
                'content' => 'In hot climates, passive cooling starts with building orientation and window placement. Deep overhangs block high summer sun, cross-ventilation flushes warm air, and thermal mass stabilises indoor temperature. These moves reduce reliance on air conditioning.',
                'category_slug' => 'architecture',
                'reading_time' => 5,
                'days_ago' => 75,
            ],
            [
                'slug' => 'reading-structural-drawings',
                'title' => 'How to Read Structural Drawings',
                'excerpt' => 'Beam schedules, column layouts and reinforcement notes — a practical guide for site teams.',
                'content' => 'Structural drawings communicate the skeleton of a building. Column layouts fix the grid, beam schedules size the members, and reinforcement notes specify bar sizes and spacing. Site teams use these to check formwork and steel before concrete is poured.',
                'category_slug' => 'structural-engineering',
                'reading_time' => 7,
                'days_ago' => 90,
            ],
            [
                'slug' => 'interior-lighting-design-basics',
                'title' => 'Interior Lighting Design Basics',
                'excerpt' => 'Ambient, task and accent lighting — the three layers that make a room work.',
                'content' => 'Good interior lighting combines three layers: ambient light for overall visibility, task light for specific activities, and accent light to highlight features. Choosing the right colour temperature and placement for each layer transforms how a space feels.',
                'category_slug' => 'interior-design',
                'reading_time' => 4,
                'days_ago' => 105,
            ],
            [
                'slug' => 'site-supervision-checklist',
                'title' => 'Site Supervision Checklist',
                'excerpt' => 'Daily checks that keep a construction site safe, on schedule and on spec.',
                'content' => 'Effective site supervision is a daily discipline. Check that formwork is plumb and braced, reinforcement is clean and correctly spaced, concrete is poured and vibrated properly, and curing starts on time. A simple daily checklist prevents most quality issues.',
                'category_slug' => 'construction',
                'reading_time' => 5,
                'days_ago' => 120,
            ],
            [
                'slug' => 'choosing-the-right-floor-tiles',
                'title' => 'Choosing the Right Floor Tiles',
                'excerpt' => 'Porcelain, ceramic or natural stone — matching tile type to room use and budget.',
                'content' => 'Floor tiles must match the room and the budget. Porcelain is dense and durable for high-traffic areas, ceramic is cost-effective for walls and light floors, and natural stone offers unique character with higher maintenance. Consider slip resistance for wet areas and underfloor heating compatibility.',
                'category_slug' => 'interior-design',
                'reading_time' => 4,
                'days_ago' => 135,
            ],
            [
                'slug' => 'waterproofing-bathrooms-basements',
                'title' => 'Waterproofing Bathrooms and Basements',
                'excerpt' => 'Where waterproofing fails first, and the details that keep it working.',
                'content' => 'Waterproofing fails most often at junctions — wall-to-floor corners, pipe penetrations and balcony edges. A continuous membrane, proper slope to drains and careful detailing at these junctions keep water out. Test with a 24-hour flood test before tiling.',
                'category_slug' => 'construction',
                'reading_time' => 6,
                'days_ago' => 150,
            ],
            [
                'slug' => 'staircase-design-basics',
                'title' => 'Staircase Design Basics',
                'excerpt' => 'Rise, run and headroom — the dimensions that make a staircase safe and comfortable.',
                'content' => 'A comfortable staircase balances rise and run. Standard residential stairs use a 170–180mm rise and 250–280mm run, with at least 2m headroom. Winder stairs save space but need careful tread geometry. Handrails at 900–1000mm and consistent riser heights prevent trips.',
                'category_slug' => 'architecture',
                'reading_time' => 5,
                'days_ago' => 165,
            ],
            [
                'slug' => 'rainwater-harvesting-for-buildings',
                'title' => 'Rainwater Harvesting for Buildings',
                'excerpt' => 'Simple systems that capture roof rainwater for reuse and groundwater recharge.',
                'content' => 'Rainwater harvesting starts with a clean roof catchment and a first-flush diverter. Stored water suits irrigation, toilet flushing and cleaning. In urban areas, recharge pits return water to the groundwater table. A basic system pays for itself within a few years.',
                'category_slug' => 'architecture',
                'reading_time' => 5,
                'days_ago' => 180,
            ],
            [
                'slug' => 'daylight-and-ventilation-in-homes',
                'title' => 'Daylight and Ventilation in Homes',
                'excerpt' => 'Window placement, cross ventilation and shading — comfort that does not need machinery.',
                'content' => 'Comfort starts with orientation. Placing openings on opposite walls creates cross ventilation that clears heat and humidity without mechanical help. Window-to-floor ratio, sill height and shading overhangs decide how much useful daylight reaches inside. Deep balconies and deciduous planting cut solar gain in summer while still admitting low winter sun.',
                'category_slug' => 'architecture',
                'reading_time' => 5,
                'days_ago' => 195,
            ],
            [
                'slug' => 'electrical-load-calculator-for-homes',
                'title' => 'Estimating Electrical Load for a Home',
                'excerpt' => 'How to size the main service and distribution before the electrician asks for a number.',
                'content' => 'A load schedule lists every circuit — lighting points, fans, sockets, water heaters, air conditioners and kitchen appliances — with their running load and starting load. Adding these gives the connected load; applying a diversity factor gives the diversified load the main service must carry. Sizing ahead avoids nuisance tripping and lets the distribution board be planned once rather than extended later.',
                'category_slug' => 'building-technology',
                'reading_time' => 6,
                'days_ago' => 210,
            ],
            [
                'slug' => 'septic-tank-and-soak-pit-sizing',
                'title' => 'Septic Tank and Soak Pit Sizing',
                'excerpt' => 'Working out tank volume and disposal area for a household before the slab is cast.',
                'content' => 'Tank volume is derived from the number of users and the detention period the system is designed for. The tank needs a watertight base, an inlet that is higher than the outlet, and a T-baffle so scum and sludge stay trapped. The soak pit or drain field that follows depends on soil percolation — a fast-draining sandy site needs far less area than heavy clay. Getting this right before the slab is cast avoids costly breaking later.',
                'category_slug' => 'building-technology',
                'reading_time' => 6,
                'days_ago' => 225,
            ],
            [
                'slug' => 'setting-out-a-site-before-excavation',
                'title' => 'Setting Out a Site Before Excavation',
                'excerpt' => 'Batter boards, offsets and check measurements that keep the building on its plot.',
                'content' => 'Setting out transfers the drawing onto the ground. Batter boards are fixed outside the excavation line so corner pegs and string lines can be restored after digging. Every column grid is checked diagonally for squareness, and the finished levels are taken from a benchmark well clear of the works. Checking diagonals and re-reading the offsets before excavation is far cheaper than cutting a foundation in the wrong place.',
                'category_slug' => 'construction',
                'reading_time' => 5,
                'days_ago' => 240,
            ],
            [
                'slug' => 'preparing-a-construction-cost-estimate',
                'title' => 'Preparing a Construction Cost Estimate',
                'excerpt' => 'Quantity take-off, rate assumptions and contingencies that make a budget believable.',
                'content' => 'An estimate starts with a quantity take-off — measured volumes of concrete, areas of finishes, lengths of services — taken off the drawings rather than guessed. Rates are then applied for labour, materials and equipment, with separate allowances for fixtures, external works and professional fees. A contingency covers what the drawings do not yet show. An estimate that states its assumptions openly is far more useful than a single optimistic number.',
                'category_slug' => 'construction',
                'reading_time' => 7,
                'days_ago' => 255,
            ],
            [
                'slug' => 'choosing-paints-and-finishes',
                'title' => 'Choosing Paints and Finishes',
                'excerpt' => 'Sheen, surface preparation and washability — what actually decides how a wall ages.',
                'content' => 'Finish matters as much as colour. Matte finishes hide surface imperfections but mark easily, while satin and semi-gloss take scrubbing and suit corridors, kitchens and bathrooms. The prep decides the life: cracks raked and filled, new plaster cured, and a primer matched to the substrate. Two thin coats of the right system outlast a single heavy coat of the wrong one.',
                'category_slug' => 'interior-design',
                'reading_time' => 4,
                'days_ago' => 270,
            ],
        ];

        // Round-robin across the supplied project photography so every article
        // card opens with a real image instead of a placeholder.
        $insightImages = glob(public_path('images/projects/*.jpeg'));
        sort($insightImages);

        foreach ($insightPosts as $i => $post) {
            $record = BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'author_id' => $admin?->id,
                    'category_id' => BlogCategory::where('slug', $post['category_slug'])->value('id'),
                    'tags' => null,
                    'seo' => [
                        'title' => $post['title'].' — Update Architects & Engineering',
                        'description' => $post['excerpt'],
                    ],
                    'reading_time' => $post['reading_time'],
                    'published_at' => now()->subDays($post['days_ago']),
                    'status' => 'published',
                    'locale' => 'en',
                ]
            );

            // Only seed an image once — anything the CMS has since set is kept.
            if (! $record->featured_image_id && $insightImages !== []) {
                $file = $insightImages[($i * 17 + 5) % count($insightImages)];

                $media = $record
                    ->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection('featured', 'public');

                $record->update(['featured_image_id' => $media->id]);
            }
        }

        // Blog categories from the briefing
        $blogCategories = [
            ['name' => 'Architecture', 'slug' => 'architecture'],
            ['name' => 'Structural Engineering', 'slug' => 'structural-engineering'],
            ['name' => 'Geotechnical Engineering', 'slug' => 'geotechnical-engineering'],
            ['name' => 'Construction', 'slug' => 'construction'],
            ['name' => 'Interior Design', 'slug' => 'interior-design'],
            ['name' => 'Building Technology', 'slug' => 'building-technology'],
            ['name' => 'Project Case Studies', 'slug' => 'project-case-studies'],
            ['name' => 'Company News', 'slug' => 'company-news'],
        ];
        foreach ($blogCategories as $bc) {
            BlogCategory::firstOrCreate($bc);
        }

        // Menu items for public navigation
        $menu = Menu::updateOrCreate(['slug' => 'main'], ['name' => 'Main Menu']);

        $menuItems = [
            ['title' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['title' => 'About', 'url' => '/about', 'sort_order' => 2],
            ['title' => 'Services', 'url' => '/services', 'type' => 'services', 'sort_order' => 3],
            ['title' => 'Projects', 'url' => '/projects', 'sort_order' => 4],
            ['title' => 'Team', 'url' => '/team', 'sort_order' => 5],
            ['title' => 'Insights', 'url' => '/insights', 'sort_order' => 6],
            ['title' => 'Contact', 'url' => '/contact', 'sort_order' => 7],
        ];

        foreach ($menuItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $menu->id, 'title' => $item['title']],
                array_merge($item, [
                    'menu_id' => $menu->id,
                    'parent_id' => null,
                    'type' => $item['type'] ?? 'custom',
                    'visibility' => true,
                    'locale' => 'en',
                ])
            );
        }

        // Consultation leads and contact messages are real inbound data and are
        // intentionally left empty. The admin dashboards render empty states
        // until genuine submissions arrive through the public forms.

        // Settings may have been added or changed above, so any settings map
        // already memoised for this process has to be re-read.
        Setting::flushResolvedMaps();
    }
}
