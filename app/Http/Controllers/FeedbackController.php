<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\FeedbackCollection;
use App\Services\V1\FeedbackQuery;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $filter = new FeedbackQuery();
        $queryItems = $filter->transform($request);

        // Start a query builder instance
        $query = Feedback::query();

        if (count($queryItems) > 0) {
            $query->where($queryItems);
        }

        // if search has value
        if (isset($search)) {
            $query->where('guest_name', 'LIKE', "%$search%")
                ->orWhere('project', 'LIKE', "%$search%")
                ->orWhere('message', 'LIKE', "%$search%");
        }

        // Paginate the results and append query params to pagination links
        $paginatedResults = $this->paginated($query, $request)->appends($request->query());

        // Return the results as a JSON response using the UserResource collection
        return new FeedbackCollection($paginatedResults);
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
    public function store(Request $request, FeedbackRequest $feedbackRequest)
    {
        // Validate the feedback request
        $feedbackData = $feedbackRequest->validated();

        // If not authenticated, set userId to null and handle guest_name
        $userId = null;
        $guestName = $request->input('guest_name', 'Guest'); // Provide default guest name if not provided

        // Check if feedback for the project already exists
        $oldFeedback = Feedback::where('project', $feedbackData['project'])->first();

        if ($oldFeedback) {
            return response()->json(['status' => 401, 'message' => "You have already given feedback for this project!"]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filePath = $file->store('public/profiles'); // Store file and get path
        }

        $fileUrl = url('storage/' . str_replace('public/', '', $filePath));

        // Create new feedback entry
        $feedback = Feedback::create([
            'guest_name' => isset($guestName) ? $guestName : null,
            'project' => $feedbackData['project'],
            'message' => $feedbackData['message'],
            'rating' => $feedbackData['rating'],
            'profile_image' => $fileUrl, // Save file path or URL
        ]);

        if (isset($feedback)) {
            return response()->json([
                'status' => 201,
                'message' => 'Feedback submitted successfully',
                'feedback' => new FeedbackResource($feedback),
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Failed to submit feedback!',
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        return new FeedbackResource($feedback);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
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
    public function update(Request $request, Feedback $feedback)
    {
        $feedback->update($request->all());
        return new FeedbackResource($feedback);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return response()->json(['message' => 'Feedback deleted successfully'], 200);
    }
}
