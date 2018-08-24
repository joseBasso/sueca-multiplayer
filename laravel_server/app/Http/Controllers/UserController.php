<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nickname' => 'max:15|required|unique:users,nickname',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:3,confirmed'
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'nickname' => 'max:15|required|unique:users,nickname,'.$id,
            'oldPassword' => 'nullable|string|min:6',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $this->authorize('self', $user);
        $user->update($request->except(['password','oldPassword','password_confirmation']));
        if ($request->password != null && strlen($request->password) != 0) {
            if(Hash::check($request->oldPassword, $user->password)){
                $user->password = Hash::make($request->password);
                $user->save();
            } else{
                return response()->json(['message'=>'Old Password is Wrong'], 401);
            }

        }

        return new UserResource($user);
    }

}
