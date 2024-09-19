<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function index()
    {
        // Get all resumes ordered by created_at in descending order
        $allResumes = Resume::query()->orderBy('created_at', 'desc')->get();

        // Group resumes by file_type and get the latest one for each type
        $latestResumes = $allResumes->groupBy('file_type')->map(function ($group) {
            return $group->first(); // Get the most recent resume in each group
        });

        return response()->json([
            'status' => 200,
            'resumes' => $latestResumes->values() // Convert the collection to an array
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'type' => 'required|in:Dev,Non Dev',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            ]);

            // Store the file and ensure a unique filename to avoid collisions
            $file = $request->file('resume');
            $filePath = $file->store('public/resumes'); // Store file and get path
            $originalFileName = $file->getClientOriginalName(); // Get the original file name

            // Create a new Resume record in the database
            $resume = Resume::create([
                'file_type' => $request->type,
                'file_path' => $filePath,
                'original_name' => $originalFileName
            ]);

            if ($resume) {
                // Return a success response with the resume data
                return response()->json([
                    'status' => 201,
                    'message' => 'Resume uploaded successfully.',
                    'resume' => $resume,
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to upload resume!',
                ]);
            }
        } catch (Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage(),
            ]);
        }
    }

    public function downloadLatest()
    {
        // Get the latest resume
        $resume = Resume::latest('created_at')->firstOrFail();

        // Build the full path to the file
        $filePath = storage_path('/app/' . $resume->file_path);

        // Check if the file exists on disk before attempting to download
        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        // Return the file for download with the original name
        return response()->download($filePath, $resume->original_name, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'attachment; filename="' . $resume->original_name . '"',
        ]);
    }


    public function destroy($id)
    {
        $resume = Resume::findOrFail($id);
        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return response()->json(null, 204);
    }
}
