<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function leaveGame(Request $request, $id){
        $game = Game::find($id);
        $user = User::byIdentifier($request->playerName)->id;
        $game->players()->detach([$id, $user]);
    }

    public function joinGame(Request $request, $id){
        $request->validate([
            'team_number' => 'required',
        ]);
        $game = Game::find($id);
        $user = User::byIdentifier($request->playerName)->id;
        $game->players()->attach($user, ['team_number' => $request->team_number]);
    }

    public function cancelGame($id)
    {
        $game = Game::find($id);
        $game->status = 'canceled';
        $game->save();
        return response()->json('Game Canceled', 200);
    }

    public function startGame(Request $request, $id) {
        $game = Game::find($id);
        $game->status = 'active';
        $game->save();
        return response()->json($game, 200);
    }

}
