<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function paginated($query, $request)
    {
        $limit = $request->limit ? $request->limit : 25;
        return $query->paginate($limit);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials'
            ]);
        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'status' => 200,
            'message' => "Logged in successfully!",
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function register(UserRequest $request)
    {
        $data = $request->validated(); // Get validated data

        // Check if a user with the given email already exists
        if (User::where('email', $data['email'])->exists()) {
            return response()->json(['error' => 'User with this email already exists.'], 400);
        }

        // Rename profilePicture to profile_picture for processing
        if ($request->hasFile('profilePicture')) {
            $file = $request->file('profilePicture');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('profiles', $fileName, 'public');
            $data['profile_picture'] = $filePath; // Use profile_picture in database
        }

        $data['password'] = Hash::make($data['password']);

        return new UserResource(User::create($data));
    }

    public function sendContactMail(Request $request)
    {
        // Validate incoming request data
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:1000',
        ]);

        try {
            // Send email to the specified recipient
            Mail::to(env('MAIL_SEND_TO'))->send(new ContactMail($data));

            // Return a success response
            return response()->json([
                'status' => 201,
                'message' => 'Your message has been sent successfully!'
            ]);
        } catch (\Exception $err) {
            // Return an error response with the message key
            return response()->json([
                'status' => 500,
                'message' => 'There was an error sending your message. Please try again later.',
                'error' => $err->getMessage()  // Optionally include the error message for debugging
            ]);
        }
    }
}
