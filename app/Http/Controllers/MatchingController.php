<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cell;
use App\Models\Game;
use App\Models\Board;
use App\Models\Dot;
use App\Models\Bar;
use App\Models\Player;
use App\Models\Turn;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\startGame;
use App\Events\MatchingRoom;

class MatchingController extends Controller
{
    public function create(){
        // ゲームの作成
        $game = new Game;
        $game->code = Str::random(6);
        $game->host_user_id = Auth::id();
        $game->save();

        // ボードの構造
        // ボードのマップ構成
        $board_conf = [
            /*
            [ cell_id, volume ]
            volumeの合計が19になるように
            */
            [ // 丘陵
                1, 3
            ], 
            [ // 森林
                2, 4
            ],
            [ // 牧草地
                3, 4
            ],
            [ // 畑
                4, 4
            ],
            [ // 山地
                5, 3
            ],
            [ // 荒地
                6, 1
            ]
        ];

        // ボードのマップ構成を配列に変換
        // idを用いた配列にする
        $board_base_ary = [];
        foreach($board_conf as $ary){
            for($i = 0; $i < $ary[1]; $i ++){
                $board_base_ary[] = $ary[0];
            }
        }

        // シャッフルする
        shuffle($board_base_ary);

        // dice_noの割り振り
        // 基準となる配列
        $criterion_ary = [0, 1, 3, 8, 13, 16, 18, 17, 15, 10, 5, 2, 4, 6, 11, 14, 12, 7, 9];
        $criterion_dice_ary = [
            5, 2, 6, 3, 8, 10, 9, 12, 11, 4, 8, 10, 9, 4, 5, 6, 3, 11
        ];

        $dice_counter = 0;

        foreach($criterion_ary as $i => $val){
            $board = new Board;
            $board->game_id = $game->id;
            $board->cell_id = $board_base_ary[$val];      // 種類
            $board->pos_id = $val; 
            if($board_base_ary[$val] != 6){
                $board->dice_id = $criterion_dice_ary[$dice_counter];
                $dice_counter ++;
            }
            $board->save();
        }  

        // 頂点の作成
        for($i = 0;$i < 54; $i++){
            $dot = new Dot;
            $dot->game_id = $game->id;
            $dot->pos_id = $i;
            $dot->save();
        }

        // 道の作成
        $bar_config = config('bar_config');
        foreach($bar_config as $key => $dot){
            $bar = new Bar;
            $bar->game_id = $game->id;
            $bar->pos_id = $key;
            $bar->dot1 = $dot[0];
            $bar->dot2 = $dot[1];
            $bar->save();
        }

        // プレイヤーの作成（ダミー）


        $player = new Player;
        $player->game_id = $game->id;
        $player->user_id = Auth::id();
        $player->player_no = 1;
        $player->order = 1;
        $player->save();


        // １ターン目のレコードを作成
        // 1番目のプレイヤーを取得
        $active_player = $game->players()->where('order', 1)->first();

        $turn = new Turn;
        $turn->player_id = $active_player->id;
        $turn->game_id = $game->id;
        $turn->turn = 1;
        $turn->type = 0;
        $turn->save();

        $players = $game->players;

        return Inertia::render('Preparation', [
            "game_id" => $game->id,
            "players" => $players,
            "code" => $game->code,
        ]);
    }

    public function join(){
        return Inertia::render('Join');
    }

    public function matching(Request $request){
        $code = $request->code;
        $game = Game::where('code', $code)->first();

        $players = $game->players;
        $player = $players->where('user_id', Auth::id())->first();
        $player_count = $players->count();
        \Log::debug("player_count");
        \Log::debug($player_count);

        if($player_count === 4){
            \Log::debug("登録上限に達しました");
            return redirect('game.matching');
        }



        if(!$player){

            // まだ参加していない
            $player = new Player;
            $player->game_id = $game->id;
            $player->user_id = Auth::id();
            $player->player_no = $player_count + 1;
            $player->order = $player_count + 1;
            $player->save();
        }




        $game = Game::where('code', $code)->first();
        $players = $game->players;


        broadcast(new MatchingRoom($players))->toOthers();
    
        
        return Inertia::render('Preparation', [
            "game_id" => $game->id,
            "players" => $players,
            "code" => $game->code,
        ]);
    }

    public function board(Request $request){
        if(!isset($request->game_id)){
            return redirect('game.matching');  
        }
        $game = Game::find($request->game_id);

        if($game->host_user_id == Auth::id()){
            broadcast(new startGame([]))->toOthers();
        }

        // データの取得
        $boards = $game->load('boards.cell')->boards;
        $bars = $game->bars;
        $dots = $game->dots;
        $players = $game->players;
        $turn = $game->latestTurn;
        $my_player = $players->where("user_id", Auth::id())->first();

        if(!$my_player){
            \Log::debug("自分が登録されていない");
            return redirect('game.matching');
        }
        $bar_config = config('bar_config');

        return Inertia::render('Game', [
            "game_id" => $game->id,
            "boards" => $boards,
            "bars" => $bars,
            "dots" => $dots,
            "players" => $players,
            "latest_turn" => $turn[0],
            "my_id" => $my_player->id,
            "my_order" => $my_player->order,
            "bar_config" => $bar_config,
        ]);
    }
}
