<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
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
        $query = Project::query();

        // if search has value
        if (isset($search)) {
            $query->where('name', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
        }

        // Paginate the results and append query params to pagination links
        $paginatedResults = $this->paginated($query, $request)->appends($request->query());

        // Return the results as a JSON response using the UserResource collection
        return new ProjectCollection($paginatedResults);
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
    public function store(Request $request, ProjectRequest $projectRequest)
    {
        // Validate the feedback request
        $projectData = $projectRequest->validated();

        // Check if feedback for the project already exists
        $oldProject = Project::where('name', $projectData['name'])->first();

        if ($oldProject) {
            return response()->json(['status' => 401, 'message' => "You have already submitted this project!"]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filePath = $file->store('public/pictures'); // Store file and get path
        }

        $fileUrl = url('storage/' . str_replace('public/', '', $filePath));

        // Create new feedback entry
        $project = Project::create([
            'type' => $projectData['type'],
            'name' => $projectData['name'],
            'link' => $projectData['link'],
            'description' => $projectData['description'],
            'picture' => $fileUrl, // Save file path or URL
        ]);

        if (isset($project)) {
            return response()->json([
                'status' => 201,
                'message' => 'Project submitted successfully!',
                'project' => new ProjectResource($project),
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to submit project!',
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $feedback)
    {
        $feedback->update($request->all());
        return new ProjectResource($feedback);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}
