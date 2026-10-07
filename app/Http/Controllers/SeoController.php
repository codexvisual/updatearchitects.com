<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $locale = app()->getLocale();
        $baseUrl = url('/');

        $urls = [
            ['url' => $baseUrl, 'changefreq' => 'daily', 'priority' => 1.0],
            ['url' => route('about'), 'changefreq' => 'monthly', 'priority' => 0.8],
            ['url' => route('services'), 'changefreq' => 'weekly', 'priority' => 0.9],
            ['url' => route('projects'), 'changefreq' => 'daily', 'priority' => 0.9],
            ['url' => route('team'), 'changefreq' => 'monthly', 'priority' => 0.7],
            ['url' => route('blog'), 'changefreq' => 'daily', 'priority' => 0.8],
            ['url' => route('contact'), 'changefreq' => 'monthly', 'priority' => 0.7],
            ['url' => route('consultation'), 'changefreq' => 'monthly', 'priority' => 0.8],
            ['url' => route('privacy'), 'changefreq' => 'yearly', 'priority' => 0.3],
            ['url' => route('terms'), 'changefreq' => 'yearly', 'priority' => 0.3],
        ];

        // Projects
        $projects = Project::published()
            ->where('locale', $locale)
            ->select('slug', 'updated_at')
            ->get();
        foreach ($projects as $project) {
            $urls[] = [
                'url' => route('projects.show', $project->slug),
                'lastmod' => $project->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.7,
            ];
        }

        // Services
        $services = Service::visible()
            ->where('locale', $locale)
            ->select('slug', 'updated_at')
            ->get();
        foreach ($services as $service) {
            $urls[] = [
                'url' => route('services.show', $service->slug),
                'lastmod' => $service->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.6,
            ];
        }

        // Blog posts
        $posts = BlogPost::published()
            ->where('locale', $locale)
            ->select('slug', 'updated_at')
            ->get();
        foreach ($posts as $post) {
            $urls[] = [
                'url' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.6,
            ];
        }

        // Team
        $team = TeamMember::visible()
            ->where('locale', $locale)
            ->select('slug', 'updated_at')
            ->get();
        foreach ($team as $member) {
            $urls[] = [
                'url' => route('team.show', $member->slug),
                'lastmod' => $member->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.5,
            ];
        }

        // Pages
        $pages = Page::where('status', 'published')
            ->where('locale', $locale)
            ->select('slug', 'updated_at')
            ->get();
        foreach ($pages as $page) {
            $urls[] = [
                'url' => route('page.show', $page->slug),
                'lastmod' => $page->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.5,
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.htmlspecialchars($url['url']).'</loc>'."\n";
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>'.$url['lastmod'].'</lastmod>'."\n";
            }
            $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'."\n";
            $xml .= '    <priority>'.$url['priority'].'</priority>'."\n";
            $xml .= '  </url>'."\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n\n";
        $content .= 'Sitemap: '.url('/sitemap.xml')."\n";
        $content .= 'Host: '.url('/')."\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
