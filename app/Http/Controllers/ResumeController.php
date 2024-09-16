<?php

namespace App\Http\Controllers;

use App\Models\Resume;
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
        $request->validate([
            'type' => 'required|in:Dev, Non Dev',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $userId = Auth::id(); // Assuming user is logged in
        $file = $request->file('resume');
        $filePath = $file->store('resumes', 'public');

        $resume = Resume::create([
            'file_type' => $request->type,
            'file_path' => $filePath,
        ]);

        return response()->json($resume, 201);
    }

    public function download()
    {
        // Get the latest resume based on the created_at timestamp
        $resume = Resume::latest('created_at')->firstOrFail();

        return Storage::disk('public')->download($resume->file_path);
    }

    public function destroy($id)
    {
        $resume = Resume::findOrFail($id);
        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return response()->json(null, 204);
    }
}
