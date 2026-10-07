<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\Service;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $serviceNames = [
            'architectural-design' => 'Architectural Design',
            'structural-design' => 'Structural Design',
            'geotechnical-engineering' => 'Geotechnical Engineering',
        ];

        $slides = [
            [
                'eyebrow' => 'Architecture • Engineering • Construction Consultancy • Interior Design',
                'title' => 'QUALITY DESIGN. QUALITY CONSTRUCTION.',
                'subtitle' => 'Integrated architecture, engineering, construction consultancy and interior design services.',
                'cta_label' => 'Explore Projects',
                'cta_url' => '/projects',
                'secondary_cta_label' => 'Start Your Project',
                'secondary_cta_url' => '/consultation',
                'sort_order' => 1,
                'image' => 'images/optimized/hero-1440.jpg',
            ],
        ];

        $sortOrder = 2;

        foreach ($serviceNames as $slug => $name) {
            $service = Service::where('slug', $slug)->first();

            $slides[] = [
                'eyebrow' => 'Our Services',
                'title' => $name,
                'subtitle' => $service?->short_description,
                'cta_label' => "Explore {$name}",
                'cta_url' => "/services/{$slug}",
                'secondary_cta_label' => 'Start Your Project',
                'secondary_cta_url' => '/consultation',
                'sort_order' => $sortOrder++,
                'image' => null,
            ];
        }

        foreach ($slides as $data) {
            $slide = HeroSlide::updateOrCreate(
                ['title' => $data['title'], 'locale' => 'en'],
                [
                    'eyebrow' => $data['eyebrow'],
                    'subtitle' => $data['subtitle'],
                    'cta_label' => $data['cta_label'],
                    'cta_url' => $data['cta_url'],
                    'secondary_cta_label' => $data['secondary_cta_label'],
                    'secondary_cta_url' => $data['secondary_cta_url'],
                    'sort_order' => $data['sort_order'],
                    'visibility' => true,
                    'locale' => 'en',
                ]
            );

            if ($data['image'] && ! $slide->featuredImage && file_exists(public_path($data['image']))) {
                $media = $slide
                    ->addMedia(public_path($data['image']))
                    ->preservingOriginal()
                    ->toMediaCollection('featured', 'public');

                $slide->update(['image_id' => $media->id]);
            }
        }
    }
}
