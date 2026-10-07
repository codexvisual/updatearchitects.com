<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $logs = Activity::latest()->paginate(20);

        return view('admin.activity.index', compact('logs'));
    }
}
