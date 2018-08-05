<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nickname' => 'required|unique:users,nickname',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:3'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->blocked = 1;
        $user->save();

        return response()->json(new UserResource($user), 201);
    }
}
