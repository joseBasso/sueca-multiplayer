<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Game as GameResource;
use App\Game;
use App\User;

class GameController extends Controller
{

    public function store(Request $request)
    {
        $game = new Game();
        $user = User::byIdentifier($request->player)->id;
        $game->created_by = $user;
        $game->deck_used = 1;
        $game->save();
        $game->players()->attach($user, ['team_number' => 1]);

        return response()->json($game, 200);
    }


}
