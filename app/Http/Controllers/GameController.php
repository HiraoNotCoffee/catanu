<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cell;
use App\Models\Game;
use App\Models\Board;
use App\Models\Dot;
use App\Models\Bar;

class GameController extends Controller
{
    public function index(){
        \Log::debug("create_game");
        // ゲームの作成
        $game = new Game;
        $game->save();

        // ボードの構造
        // ボードのマップ構成
        $board_conf = [
            /*
            [ cell_id, volume ]
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
        $bar_base = config('bar_base');
        foreach($bar_base as $key => $dot){
            $bar = new Bar;
            $bar->game_id = $game->id;
            $bar->pos_id = $key;
            $bar->dot1 = $dot[0];
            $bar->dot2 = $dot[1];
            $bar->save();
        }

        // プライヤーの取得

        // データの取得
        $boards = $game->load('boards.cell')->boards;
        $bars = $game->bars;
        $dots = $game->dots;


        return Inertia::render('Game', [
            "boards" => $boards,    
            "bars" => $bars,    
            "dots" => $dots,    
        ]);
    }
}
