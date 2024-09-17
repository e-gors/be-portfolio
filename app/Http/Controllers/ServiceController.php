<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Start a query builder instance
        $query = Service::query();

        // if search has value
        if (isset($search)) {
            $query->where('service', 'LIKE', "%$search%")
                ->orWhere('descriptions', 'LIKE', "%$search%");
        }

        // Paginate the results and append query params to pagination links
        $paginatedResults = $this->paginated($query, $request)->appends($request->query());

        // Return the results as a JSON response using the UserResource collection
        return new ServiceCollection($paginatedResults);
    }

    public function store(ServiceRequest $request)
    {
        // Validate the feedback request
        $service = $request->validated();

        // Check if feedback for the project already exists
        $oldService = Service::where('service', $service['service'])->first();

        if ($oldService) {
            return response()->json(['status' => 401, 'message' => "You have already submitted this service!"]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filePath = $file->store('public/images'); // Store file and get path
        }

        $fileUrl = url('storage/' . str_replace('public/', '', $filePath));

        // Ensure descriptions is handled as an array
        $descriptionsArray = isset($service['descriptions']) ? explode(',', $service['descriptions']) : [];

        // Create new service entry
        $service = Service::create([
            'image' => $fileUrl, // Save file path or URL
            'service' => $service['service'],
            'descriptions' => json_encode($descriptionsArray),
        ]);

        if (isset($service)) {
            return response()->json([
                'status' => 201,
                'message' => 'Feedback submitted successfully',
                'service' => $service,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to submit service!',
            ]);
        }
    }

    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($request->all());
        return new ServiceResource($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
