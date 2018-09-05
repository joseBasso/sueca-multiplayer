<?php

namespace App\Http\Controllers;

use App\Card;
use App\Config;
use App\Deck;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;
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

    public function getBasePath()
    {
        $base_path = Config::all()->first()->img_base_path;
        return response()->json($base_path,200);
    }

    public function toggleActive($id){
        $deck = Deck::find($id);
        if ($deck->active == 1){
            $deck->active = 0;
            $deck->save();
            return response()->json('Deck is now inactive', 200);
        }
        $deck->active = 1;
        $deck->save();
        return response()->json('Deck is now active', 200);
    }

    public function changeCard(Request $request){
        $this->validate($request, [
            'file' => 'required|file'
        ]);
        $card = Card::find($request->id);
        $image = $request->file('file');
        $file = File::get($image);
        Storage::disk('local')->put('public/img/decks/'.$card->path, $file);

        return response()->json("Card Changed",200);
    }

    public function changeHiddenFace(Request $request, $id){
        $this->validate($request, [
            'file' => 'required|file'
        ]);
        $deck = Deck::find($id);
        $image = $request->file('file');
        $file = File::get($image);
        Storage::disk('local')->put('public/img/decks/'.$deck->hidden_face_image_path, $file);

        return response()->json("Card Changed",200);
    }

    public function createDeck(Request $request){

        $deck = new Deck();
        $deck->name = $request->name;
        $deck->active = 1;
        $deck->complete = 0;
        $deck->hidden_face_image_path = 'deck'.$deck->name.'/semFace.png';
        $deck->save();
        $suites = ['Heart', 'Club', 'Spade','Diamond'];
        $values = ['Ace', '2', '3', '4', '5', '6', '7', 'Jack','Queen','King'];

        // esparguete mas as migrations já vinham assim
        foreach ($suites as $suite){
            foreach ($values as $value){
                $card = new Card();
                $card->value = $value;
                $card->suite = $suite;
                $card->deck_id = $deck->id;
                $naipe = '';
                $valor = '';
                switch ($suite) {
                    case 'Heart':
                        $naipe= 'c';
                        break;
                    case 'Club':
                        $naipe= 'p';
                        break;
                    case 'Spade':
                        $naipe='e';
                        break;
                    case 'Diamond':
                        $naipe='o';
                        break;
                }
                switch ($value) {
                    case 'Ace':
                        $valor= '1';
                        break;
                    case '2':
                        $valor= '2';
                        break;
                    case '3':
                        $valor='3';
                        break;
                    case '4':
                        $valor='4';
                        break;
                    case '5':
                        $valor='5';
                        break;
                    case '6':
                        $valor='6';
                        break;
                    case '7':
                        $valor='7';
                        break;
                    case 'Jack':
                        $valor='11';
                        break;
                    case 'Queen':
                        $valor='12';
                        break;
                    case 'King':
                        $valor='13';
                        break;
                }
                $card->path = 'deck'.$deck->name.'/'.$naipe.$valor.'.png';
                $card->save();
            }
        }
        return response()->json("Deck Created", 200);
    }

}
