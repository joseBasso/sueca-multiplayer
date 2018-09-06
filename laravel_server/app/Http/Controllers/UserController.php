<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Game;
use App\Factories\ActivationFactory;

class UserController extends Controller
{

  protected $activationFactory;

    public function __construct(ActivationFactory $activationFactory)
  {
      $this->activationFactory = $activationFactory;
  }

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
        $this->activationFactory->sendActivationMail($user);

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

    public function getAllUsers(Request $request)
    {
        $this->authorize('administrate', Auth::user());
        return User::all();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('self', $user);
        $user->games()->detach();
        $user->delete();
        return response()->json(['message'=>'User deleted'], 204);
    }

    public function toggleBlockUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('self', $user);
        if($user->blocked == 1){
            $user->blocked = 0;
            $user->reason_reactivated = $request->reason_reactivated;

        }
        else{
            $user->blocked = 1;
            $user->reason_blocked = $request->reason_blocked;
        }

        $user->save();
        //$this->activationFactory->sendBlockedUnblockedMail($user);

    }

    public function activateUser($token)
    {
    if ($user = $this->activationFactory->activateUser($token)) {
        auth()->login($user);
        return redirect()->route('game');
    }
    abort(404);
    }

}
