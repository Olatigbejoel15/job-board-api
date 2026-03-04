<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // List all jobs
public function index(Request $request)
{
    // return Job::with('user')->latest()->get();

    $query = Job::query()->with('user');

    // search by title
    if ($request->search) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // filter by location
    if ($request->location) {
        $query->where('location', $request->location);
    }

    // filter by minimum salary
    if ($request->min_salary) {
        $query->where('salary', '>=', $request->min_salary);
    }

    return JobResource::collection(
        $query->latest()->paginate(5)
    );
}

    // Create job
    public function store(StoreJobRequest $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'location' => 'required'
        ]);

        if ($request->user()->role !== 'company') {
            return response()->json([
                'message' => 'Only companies can post jobs'
            ], 403);
        }

        $job = Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'company' => $request->company,
            'location' => $request->location,
            'salary' => $request->salary,
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Job created successfully',
            'data' => new JobResource($job)
        ], 201);
    }

    // Show single job
    public function show(Job $job)
    {
        return $job;
    }

    // Update job (only owner)
    public function update(Request $request, Job $job)
    {
        if ($job->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->update($request->all());

        return $job;

        return new JobResource($job->load('user'));
    }

    // Delete job (only owner)
    public function destroy(Request $request, Job $job)
    {
        if ($job->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }
}
