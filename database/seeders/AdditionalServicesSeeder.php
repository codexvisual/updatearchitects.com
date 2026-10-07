<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class AdditionalServicesSeeder extends Seeder
{
    public function run(): void
    {
        $locale = 'en';

        // Get category IDs
        $categories = ServiceCategory::where('locale', $locale)->pluck('id', 'slug')->toArray();

        // Additional services to add
        $additionalServices = [
            // Architecture category
            [
                'name' => 'Land Development & Engineering Services',
                'slug' => 'land-development-engineering',
                'category' => 'architecture',
                'short_description' => 'Comprehensive land development planning, site engineering, and infrastructure design for residential and commercial projects.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Detailed Engineering Assessment (DEA)',
                'slug' => 'detailed-engineering-assessment',
                'category' => 'architecture',
                'short_description' => 'In-depth engineering assessment of existing structures for condition evaluation and rehabilitation planning.',
                'sort_order' => 11,
            ],

            // Structural Engineering category
            [
                'name' => 'Building Retrofitting & Safety Assessment',
                'slug' => 'building-retrofitting-safety-assessment',
                'category' => 'structural',
                'short_description' => 'Structural retrofitting solutions and safety assessments for existing buildings to meet current codes.',
                'sort_order' => 15,
            ],
            [
                'name' => 'Structural Design & Analysis',
                'slug' => 'structural-design-analysis',
                'category' => 'structural',
                'short_description' => 'Advanced structural design and analysis for buildings, including seismic, wind, and load calculations.',
                'sort_order' => 16,
            ],

            // Geotechnical Engineering category
            [
                'name' => 'Soil & Foundation Engineering',
                'slug' => 'soil-foundation-engineering',
                'category' => 'geotechnical',
                'short_description' => 'Soil investigation, bearing capacity analysis, and foundation design for various soil conditions.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Ground Improvement & Geotextile Works',
                'slug' => 'ground-improvement-geotextile',
                'category' => 'geotechnical',
                'short_description' => 'Ground improvement techniques including geotextiles, soil stabilization, and reinforcement solutions.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Pile & Plate Load Testing',
                'slug' => 'pile-plate-load-testing',
                'category' => 'geotechnical',
                'short_description' => 'Pile load testing and plate load testing for foundation verification and design validation.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Liquefaction Hazard Analysis',
                'slug' => 'liquefaction-hazard-analysis',
                'category' => 'geotechnical',
                'short_description' => 'Liquefaction potential assessment and mitigation design for seismic-prone areas.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Geotechnical Investigation & Design',
                'slug' => 'geotechnical-investigation-design',
                'category' => 'geotechnical',
                'short_description' => 'Comprehensive sub-soil investigation and geotechnical design for foundations and earthworks.',
                'sort_order' => 8,
            ],

            // Construction Consultancy category
            [
                'name' => 'Residential & Commercial Construction Consultancy',
                'slug' => 'residential-commercial-construction-consultancy',
                'category' => 'construction',
                'short_description' => 'Full-spectrum construction consultancy for residential and commercial projects from planning to handover.',
                'sort_order' => 13,
            ],

            // MEP category
            [
                'name' => 'Electrical & Sanitary Design',
                'slug' => 'electrical-sanitary-design',
                'category' => 'mep',
                'short_description' => 'Integrated electrical and sanitary plumbing design for buildings ensuring code compliance and efficiency.',
                'sort_order' => 7,
            ],

            // Approval category
            [
                'name' => 'RAJUK / City Corporation / Municipality Approval Services',
                'slug' => 'rajuk-city-corporation-municipality-approval',
                'category' => 'approval',
                'short_description' => 'Complete approval processing for RAJUK, City Corporation, and Municipality building permits.',
                'sort_order' => 8,
            ],

            // Documentation category
            [
                'name' => 'Bank Loan Sheet Preparation',
                'slug' => 'bank-loan-sheet-preparation',
                'category' => 'documentation',
                'short_description' => 'Accurate construction cost documentation and loan sheet preparation for bank financing.',
                'sort_order' => 9,
            ],

            // Interior Design category
            [
                'name' => 'Residential & House Interior',
                'slug' => 'residential-house-interior',
                'category' => 'interior',
                'short_description' => 'Complete interior design solutions for homes, apartments, and residential spaces.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Duplex & Luxury Interior',
                'slug' => 'duplex-luxury-interior',
                'category' => 'interior',
                'short_description' => 'High-end interior design for duplex homes and luxury residences with premium finishes.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Hotel, Hospital & Auditorium Interior',
                'slug' => 'hotel-hospital-auditorium-interior',
                'category' => 'interior',
                'short_description' => 'Specialized interior design for hospitality, healthcare, and auditorium spaces.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Restaurant & Showroom Interior',
                'slug' => 'restaurant-showroom-interior',
                'category' => 'interior',
                'short_description' => 'Commercial interior design for restaurants, showrooms, and retail spaces.',
                'sort_order' => 9,
            ],
            [
                'name' => 'Kitchen & Modular Cabinet',
                'slug' => 'kitchen-modular-cabinet',
                'category' => 'interior',
                'short_description' => 'Custom kitchen design and modular cabinet solutions for modern living spaces.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Wall Cabinet & False Ceiling',
                'slug' => 'wall-cabinet-false-ceiling',
                'category' => 'interior',
                'short_description' => 'Custom wall cabinets and false ceiling designs with integrated lighting and storage.',
                'sort_order' => 11,
            ],
            [
                'name' => 'Shopping Mall & Commercial Interior',
                'slug' => 'shopping-mall-commercial-interior',
                'category' => 'interior',
                'short_description' => 'Large-scale commercial interior design for shopping malls and commercial complexes.',
                'sort_order' => 12,
            ],
            [
                'name' => 'Customized Interior Design Solutions',
                'slug' => 'customized-interior-design-solutions',
                'category' => 'interior',
                'short_description' => 'Bespoke interior design solutions tailored to unique client requirements and spatial challenges.',
                'sort_order' => 13,
            ],
        ];

        foreach ($additionalServices as $service) {
            // Check if service already exists
            $existingService = Service::where('slug', $service['slug'])->where('locale', $locale)->first();

            if ($existingService) {
                $this->command->info("Service already exists: {$service['name']} ({$service['slug']})");

                continue;
            }

            $categoryId = $categories[$service['category']] ?? null;

            if (! $categoryId) {
                $this->command->warn("Category not found: {$service['category']} for service {$service['name']}");

                continue;
            }

            Service::create([
                'name' => $service['name'],
                'slug' => $service['slug'],
                'category_id' => $categoryId,
                'short_description' => $service['short_description'],
                'visibility' => true,
                'sort_order' => $service['sort_order'],
                'locale' => $locale,
            ]);

            $this->command->info("Created service: {$service['name']} ({$service['slug']})");
        }

        $this->attachMissingFeaturedImages($locale);

        $this->command->info('Additional services seeding completed!');
    }

    /**
     * Give every service a featured image so the card grids never fall back to
     * a bare icon. Only untouched services are filled in — an image the CMS has
     * since been given is left alone.
     */
    private function attachMissingFeaturedImages(string $locale): void
    {
        $images = glob(public_path('images/projects/*.jpeg'));
        sort($images);

        if ($images === []) {
            $this->command->warn('No source images found in public/images/projects — skipping service images.');

            return;
        }

        $missing = Service::where('locale', $locale)
            ->whereNull('featured_image_id')
            ->whereNotNull('category_id')
            ->orderBy('id')
            ->get();

        foreach ($missing as $i => $service) {
            $file = $images[($service->id * 17 + $i * 5 + 11) % count($images)];

            $media = $service
                ->addMedia($file)
                ->preservingOriginal()
                ->toMediaCollection('featured', 'public');

            $service->update(['featured_image_id' => $media->id]);

            $this->command->info("Attached image to: {$service->name}");
        }

        $this->command->info("Featured images attached to {$missing->count()} service(s).");
    }
}
