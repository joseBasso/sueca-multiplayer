<?php

namespace App\Http\Controllers;

use App\Config;
use App\Game;
use App\Statistics;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformController extends Controller
{
    public function getPlatformEmail(Request $request){
        $this->authorize('administrate', Auth::user());
        $email = Config::all()->first()->platform_email;
        return response()->json($email,200);
    }

    public function updatePlatformEmail(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'min:3'
        ]);
        $this->authorize('administrate', Auth::user());
        $config = Config::all()->first();
        $config->platform_email = $request->email;
        $config->platform_email_properties = json_encode(['driver'=>'smtp','host' =>'smtp.gmail.com','port'=>587, 'password'=>$request->password, 'encryption' => 'TLS']);
        $config->save();
        $this->changeEnv([
            'MAIL_USERNAME'   => $request->email,
            'MAIL_PASSWORD'   => $request->password,
        ]);
        return response()->json('Platform Email Changed', 200);
    }

    public function changeEnv($data = array()){
        if(count($data) > 0){
            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach((array)$data as $key => $value){

                // Loop through .env-data
                foreach($env as $env_key => $env_value){

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }

    public function userStatistics(Request $request){
        $stats = new Statistics();
        $total_players = $stats->total_players();
        $total_games = $stats->total_games();
        $top5MostGames = $stats->top5MostGames();
        $top5MostPoints = $stats->top5MostPoints();
        $top5BestAverage = $stats->top5BestAverage();


        return response()->json(array('total_players'=>$total_players, 'total_games'=>$total_games,'top5MostGames'=>$top5MostGames,
            'top5MostPoints'=>$top5MostPoints, 'top5BestAverage'=>$top5BestAverage),200);
    }

    public function authenticatedUserStatistics(Request $request){
        $stats = new Statistics();
        $authenticatedUserStatistics = $stats->authenticatedUserStatistics();

        return response()->json(array('authenticatedUserStatistics'=>$authenticatedUserStatistics),200);

    }

    public function userList(Request $request){
        $users = User::all();
        $games = Game::all();

        //esta explodiu-me o cérebro se precisarem de ajuda digam
        foreach ($users as $user){
            $user->wins = 0;
            $user->draws = 0;
            $user->losses = 0;
            foreach($user->games as $game){
                //para cada jogo em que o utilizador esteja (belongsToMany no modelo)
                $team = $game->pivot->team_number;
                //a equipa dele está na tabela da relação game_user como pivot (belongsToMany withPivot)
                if ($game->team_winner == $team){
                    $user->wins++;
                } elseif ($game->team_winner == 0){
                    $user->draws++;
                } else{
                    $user->losses++;
                }
            }
        }
        return response()->json($users,200);
    }

}
