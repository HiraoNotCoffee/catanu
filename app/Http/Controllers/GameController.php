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


class GameController extends Controller
{
    public function board(Request $request){
        if(!isset($request->game_code)){
            return redirect('dashboard');  
        }
        $game = Game::where("code", $request->game_code)->first();

        // データの取得
        $boards = $game->load('boards.cell')->boards;
        $bars = $game->bars;
        $dots = $game->dots;
        $players = $game->players;
        $turn = $game->latestTurn;
        $my_player = $players->where("user_id", Auth::id())->first();
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
