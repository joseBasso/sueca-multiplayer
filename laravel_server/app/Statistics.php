<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Statistics extends Model
{
    public function __construct(){

    }

    public function total_players(){
        $total_players = User::all()->where('blocked','=','0')->count();
        return $total_players;
    }

    public function total_games(){
        $total_games = Game::all()->count();
        return $total_games;
    }

    public function top5MostGames(){
        $top5 = User::where('blocked','=','0')
            ->orderBy('total_games_played', 'DESC')
            ->get()
            ->take(5);

        $top5MostGames = [];

        for ($i=0; $i < 5; $i++) {
            if(isset($top5[$i])){
                $top5MostGames[] = array('place'=>$i+1,'total_games_played'=>$top5[$i]->total_games_played, 'nickname'=>$top5[$i]->nickname);
            }else{
                $top5MostGames[] = array('place'=>$i+1,'total_games_played'=>0, 'nickname'=>'N/A');
            }
        }

        return $top5MostGames;
    }

    public function top5MostPoints(){
        $top5 = User::where('blocked','=','0')
            ->orderBy('total_points', 'DESC')
            ->get()
            ->take(5);

        $top5MostPoints = [];

        for ($i=0; $i < 5; $i++) {
            if(isset($top5[$i])){
                $top5MostPoints[] = array('place'=>$i+1,'total_points'=>$top5[$i]->total_points, 'nickname'=>$top5[$i]->nickname);
            }else{
                $top5MostPoints[] = array('place'=>$i+1,'total_points'=>0, 'nickname'=>'N/A');
            }
        }

        return $top5MostPoints;
    }

    public function top5BestAverage(){
        $top5 = DB::table('users')
            ->select(DB::raw('(total_points/total_games_played) as average, nickname'))
            ->where('blocked','=','0')
            ->orderBy('average', 'DESC')
            ->get()
            ->take(5);

        $top5BestAverage = [];

        for ($i=0; $i < 5; $i++) {
            if(isset($top5[$i])){
                $top5BestAverage[] = array('place'=>$i+1,'average'=>$top5[$i]->average, 'nickname'=>$top5[$i]->nickname);
            }else{
                $top5BestAverage[] = array('place'=>$i+1,'average'=>0, 'nickname'=>'N/A');
            }
        }

        return $top5BestAverage;
    }

    public function authenticatedUserStatistics(){

        $user = User::find(Auth::user()->id);

        if($user === null){
            return ['nickname'=>'N/A','user_total_games_played'=>'N/A','user_total_points'=>'N/A','user_average_points'=>'N/A'];
        }
        $user_nickname = $user->nickname;
        $user_total_games_played = $user->total_games_played;
        $user_total_points = $user->total_points;
        if($user_total_games_played > 0){
            $user_average_points = (($user->total_points)/($user->total_games_played));
        }else{
            $user_average_points = 0;
        }

        return ['user_nickname'=>$user_nickname,'user_total_games_played'=>$user_total_games_played,'user_total_points'=>$user_total_points,'user_average_points'=>$user_average_points];

    }

    public function getUserStats(Request $request)
    {
        $user = User::find($request->id);
        if ($user === null) {
            return ['nickname'=>'N/A','user_total_games_played'=>'N/A','user_total_points'=>'N/A','user_average_points'=>'N/A'];
        }

        $top5 = DB::table('users')
            ->select(DB::raw('(total_points/total_games_played) as average, nickname'))
            ->where('blocked','=','0')
            ->orderBy('average', 'DESC')
            ->get()
            ->take(5);
        return $top5;
    }

}