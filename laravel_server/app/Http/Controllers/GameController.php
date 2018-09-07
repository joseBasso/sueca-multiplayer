<?php

namespace App\Http\Controllers;

use App\Deck;
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
        $deckList = Deck::where('active','=','1')->get();
        $decks = $deckList->pluck('id');
        $deck = null;
        $deck = $decks[0];
        $game->deck_used = $deck;
        $game->team1_points = 0;
        $game->team2_points = 0;
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
        $game = Game::findOrFail($id);
        $game->status = 'canceled';
        $game->save();
        return response()->json('Game Canceled', 200);
    }

    public function startGame(Request $request, $id) {
        $game = Game::findOrFail($id);
        $game->status = 'active';
        $game->save();
        return response()->json($game, 200);
    }

    public function endGame(Request $request, $id) {

        $game = Game::findOrFail($id);
        $game->status = 'terminated';
        $game->team_renunciou = $request->team_renunciou;
        $game->team_desconfiou = $request->team_desconfiou;
        $game->team1_cardpoints = $request->team1_cardpoints;
        $game->team2_cardpoints = $request->team2_cardpoints;
        $game->team1_points = $request->team1_points;
        $game->team2_points = $request->team2_points;
        $game->team_winner = $request->team_winner;
        $game->save();

        foreach ($game->team1 as $user)
        {
            $user->total_games_played++;
            $user->total_points = $request->team1_points;
            $user->save();
        }
        foreach ($game->team2 as $user)
        {
            $user->total_games_played++;
            $user->total_points = $request->team2_points;
            $user->save();
        }
        return response()->json($game, 201);
    }

}
