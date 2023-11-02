<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Cell;
use App\Models\Game;
use App\Models\Board;
use App\Models\Dot;
use App\Models\Bar;
use App\Models\Player;
use App\Models\Turn;
use App\Models\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library\Message;
use App\Events\setData;
use App\Events\turnEnd;
use App\Events\MessageSent;

class GameApiController extends Controller
{
    public function setData(Request $request){
        $player_id = $request->player_id;
        $game_id = $request->game_id;
        $road = $request->road;
        $base = $request->base;

        // 現在のゲームを取得
        $game = Game::find($request->game_id);

        $turn = $game->latestTurn[0];
        $turn_id = $turn["id"];


        $action =  new Action;
        $action->game_id = $game_id;
        $action->player_id = $player_id;
        $action->dot_id = $base;
        $action->bar_id = $road;
        $action->type = 0;
        $action->save();

        // 家の作成
        if(!is_null($base)){
            $base = Dot::where('game_id', $game_id)
                        ->where('pos_id', $base)
                        ->first();
            $base->player_id = $player_id;
            $base->building = 0;            
            $base->save();

            // 隣接する家を判定し、置けなくする
            $dot_pair = config("dot_pair");
            foreach($dot_pair[$base["pos_id"]] as $value){
                 $base = Dot::where('game_id', $game_id)
                        ->where('pos_id', $value)
                        ->first();
                $base->player_id = -1;
                $base->building = 0;            
                $base->save();
            }
           
        }
        
        if(!is_null($road)){
             $base = Bar::where('game_id', $game_id)
                        ->where('pos_id', $road)
                        ->first();
            $base->player_id = $player_id;        
            $base->save();
        }

        $game = Game::find($request->game_id);
        $bars = $game->bars;
        $dots = $game->dots;

        $data = [
            "bars" => $bars,
            "dots" => $dots,
        ];

        $hoge = broadcast(new setData($data))->toOthers();



        return $data;
    }

    public function turnEnd(Request $request){
        \Log::debug($request);
        $player_id = $request->player_id;
        $game_id = $request->game_id;
        // 現在のゲームを取得
        $game = Game::find($request->game_id);
        
        $beforeTurn = $game->latestTurn[0];
        $before_order = $beforeTurn["player"]["order"];


        \Log::debug("beforeTurn");
        \Log::debug($beforeTurn);
        \Log::debug("before_order");
        \Log::debug($before_order);

        if($beforeTurn["player_id"] == $player_id){
            // 1番目のプレイヤーを取得

            $active_player = $game->getNextPlayer($before_order);

            \Log::debug("active_player");
            \Log::debug($active_player);

            $turn = new Turn;
            $turn->player_id = $active_player->id;
            $turn->game_id = $game->id;
            $turn->turn = $beforeTurn["turn"] + 1;
            $turn->type = 0;
            $turn->save();
        }

        

        $response = [
            "turn" => $turn,
        ];

        broadcast(new turnEnd($response))->toOthers();

        return $response;
    }

    public function test(Request $request){

        \Log::debug("event");
        $message = new Message;
        $message->message_id = 1;
        $message->message    = "テスト";

        broadcast(new MessageSent($message))->toOthers();
        
        return;    
    }
}
