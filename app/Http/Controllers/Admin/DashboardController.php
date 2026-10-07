<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ConsultationLead;
use App\Models\ContactMessage;
use App\Models\Office;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'projects' => [
                'total' => Project::count(),
                'ongoing' => Project::where('status', 'ongoing')->count(),
                'completed' => Project::where('status', 'completed')->count(),
                'upcoming' => Project::where('status', 'upcoming')->count(),
                'published' => Project::whereNotNull('published_at')->count(),
            ],
            'services' => Service::count(),
            'team' => TeamMember::count(),
            'offices' => Office::count(),
            'posts' => BlogPost::count(),
            'messages' => [
                'total' => ContactMessage::count(),
                'unread' => ContactMessage::where('status', 'unread')->count(),
            ],
            'consultations' => [
                'total' => ConsultationLead::count(),
                'open' => ConsultationLead::open()->count(),
                'new' => ConsultationLead::where('status', 'new')->count(),
            ],
        ];

        $recentLeads = ConsultationLead::latest()->limit(5)->get();
        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $recentProjects = Project::with('featuredImage')->orderBy('sort_order')->latest('id')->limit(4)->get();
        $recentActivity = Activity::with('causer')->latest()->limit(6)->get();

        $leadsByMonth = ConsultationLead::query()
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->pluck('created_at')
            ->groupBy(fn ($createdAt) => $createdAt->format('Y-m'))
            ->map->count();

        $chartData = collect(range(5, 0))->map(function (int $offset) use ($leadsByMonth) {
            $date = now()->subMonths($offset)->format('Y-m');

            return [
                'label' => now()->subMonths($offset)->format('M'),
                'total' => (int) $leadsByMonth->get($date, 0),
            ];
        });

        $maxLeads = max(1, $chartData->max('total'));

        return view('admin.dashboard', compact(
            'stats',
            'recentLeads',
            'recentMessages',
            'recentProjects',
            'recentActivity',
            'chartData',
            'maxLeads',
        ));
    }
}
