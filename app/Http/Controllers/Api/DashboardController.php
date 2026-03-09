<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Company stats
        if ($user->role === 'company') {

            $jobsCount = Job::where('user_id', $user->id)->count();

            $applicationsCount = Application::whereHas('job', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            $recentJobs = Job::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            return response()->json([
                'role' => 'company',
                'jobs_posted' => $jobsCount,
                'applications_received' => $applicationsCount,
                'recent_jobs' => $recentJobs
            ]);
        }

        // Applicant stats
        if ($user->role === 'applicant') {

            $applications = Application::where('user_id', $user->id)->count();

            $recentApplications = Application::where('user_id', $user->id)
                ->with('job')
                ->latest()
                ->take(5)
                ->get();

            return response()->json([
                'role' => 'applicant',
                'applications_submitted' => $applications,
                'recent_applications' => $recentApplications
            ]);
        }
    }
}
