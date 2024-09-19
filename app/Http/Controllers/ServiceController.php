<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceCollection;
use Exception;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $descriptions = $request->input('descriptions');

        try {
            // Initialize variables
            $fileUrl = $service->image; // Keep existing image URL if no new image is uploaded

            // Handle file upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filePath = $file->store('public/images'); // Store file and get path
                $fileUrl = url('storage/' . str_replace('public/', '', $filePath)); // Generate URL
            }

            // Ensure descriptions is handled as an array
            $descriptionsArray = $request->input('descriptions') ? explode(',', $request->input('descriptions')) : [];

            // Prepare update data
            $updateData = $request->only(['service']);
            $updateData['image'] = $fileUrl; // Set the new image URL or existing one
            $updateData['descriptions'] = json_encode($descriptionsArray);

            // Update the service
            $updated = $service->update($updateData);

            if ($updated) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Service updated successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Service update failed!',
                ]);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ]);
        }
    }


    public function destroy(Service $service)
    {
        try {
            $deleted = $service->delete();
            if ($deleted) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Service deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to delete service!'
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => 'There was an error while deleting this service!'
            ]);
        }
    }
}
