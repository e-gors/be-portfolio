<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index()
    {
        return new UserCollection(User::all());
    }

    public function store(Request $request)
    {
        //
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update($data);
        return new UserResource($user);
    }

    public function destory(User $user)
    {
        $user->delete();
    }
}
