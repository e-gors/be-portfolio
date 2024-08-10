<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function create(Request $request)
    {
        return new UserResource(User::create($request->all()));
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return new UserResource($user);
    }

    public function destory(User $user)
    {
        $user->delete();
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
            'token' => $token,
            'user' => $user
        ]);
    }
}
