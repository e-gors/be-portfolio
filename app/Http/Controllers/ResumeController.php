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
        $resumes = Resume::query()->orderBy('created_at', 'desc')->get();
        return view('resume.index', compact('resumes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $userId = Auth::id(); // Assuming user is logged in
        $file = $request->file('resume');
        $filePath = $file->store('resumes', 'public');

        // Delete old resume if exists
        $oldResume = Resume::where('user_id', $userId)->first();
        if ($oldResume) {
            Storage::disk('public')->delete($oldResume->file_path);
            $oldResume->delete();
        }

        $resume = Resume::create([
            'user_id' => $userId,
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
