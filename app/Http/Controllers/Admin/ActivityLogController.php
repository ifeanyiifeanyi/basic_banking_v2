<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;


class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(20);

        return view('admin.activity-log', compact('activities'));
    }
}
