<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Job;

class ApplicationController extends Controller
{
    // Apply to a job
    public function apply(Request $request, Job $job)
    {
        // prevent duplicate application
        $exists = Application::where('user_id', $request->user()->id)
            ->where('job_id', $job->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'You already applied to this job'
            ], 400);
        }

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_id' => $job->id
        ]);

        return response()->json([
            'message' => 'Application submitted',
            'application' => $application
        ], 201);
    }

    // Job owner views applicants
    public function applicants(Request $request, Job $job)
    {
        // only job owner allowed
        if ($job->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $job->applications()->with('user')->get();
    }
}
