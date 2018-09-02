<?php

namespace App\Http\Controllers;

use App\Card;
use App\Deck;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    public function getAllDecks(Request $request)
    {
        return Deck::all();
    }

    public function getDeck($id)
    {
        return Card::all()->where('deck_id', '=', $id);
    }

}
