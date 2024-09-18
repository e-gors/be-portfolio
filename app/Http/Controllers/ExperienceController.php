<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Http\Resources\ExperienceCollection;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Start a query builder instance
        $query = Experience::query();

        // if search has value
        if (isset($search)) {
            $query->where('job_position', 'LIKE', "%$search%")
                ->orWhere('company_name', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
        }

        $query->orderBy('end_date', 'desc');

        // Paginate the results and append query params to pagination links
        $paginatedResults = $this->paginated($query, $request)->appends($request->query());

        // Return the results as a JSON response using the UserResource collection
        return new ExperienceCollection($paginatedResults);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ExperienceRequest $experienceRequest)
    {
        // Validate the experience request
        $experienceData = $experienceRequest->validated();

        // Check if already exists
        $oldExperience = Experience::where('job_position', $experienceData['jobPosition'])
            ->where('company_name', $experienceData['companyName'])->first();

        if ($oldExperience) {
            return response()->json(['status' => 401, 'message' => "You have already submitted this experience!"]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('companyLogo')) {
            $file = $request->file('companyLogo');
            $filePath = $file->store('public/logos'); // Store file and get path
        }

        $fileUrl = url('storage/' . str_replace('public/', '', $filePath));

        // Create new feedback entry
        $experience = Experience::create([
            'job_position' => $experienceData['jobPosition'],
            'company_name' => $experienceData['companyName'],
            'description' => $experienceData['description'],
            'link' => $experienceData['link'] ?? null,
            'start_date' => $experienceData['startDate'],
            'end_date' => $experienceData['endDate'] ?? null,
            'company_logo' => $fileUrl ?? null, // Save file path or URL
        ]);

        if (isset($experience)) {
            return response()->json([
                'status' => 201,
                'message' => 'Experience submitted successfully!',
                'experience' => new ExperienceResource($experience),
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to submit experience!',
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Experience $feedback)
    {
        return new ExperienceResource($feedback);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Experience $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Experience $feedback)
    {
        $feedback->update($request->all());
        return new ExperienceResource($feedback);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Experience $feedback)
    {
        $feedback->delete();
        return response()->json(['message' => 'Experience deleted successfully'], 200);
    }
}
