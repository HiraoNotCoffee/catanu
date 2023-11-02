<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;


    protected $guarded = [
        "id"
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function boards(){
        return $this->hasMany(Board::class);
    }
    public function dots(){
        return $this->hasMany(Dot::class);
    }
    public function bars(){
        return $this->hasMany(Bar::class);
    }
    public function players(){
        return $this->hasMany(Player::class);
    }
    public function turns(){
        return $this->hasMany(Turn::class);
    }

    // 手番のプレイヤーを取得
    public function getNextPlayer($before_order)
    {
        $players_num = $this->players->count();
        $turn_num = $this->players->count();

        if($players_num <= $turn_num + $turn_num  &&  $turn_num < $players_num * 2){
            // 手番が逆転する
            $next_order = $players_num - ($turn_num - 1 % $players_num) ;
        }
        else{
            $next_order = $before_order + 1;
            if($next_order > $players_num){
                $next_order = 1;
            }
        }
        // 1番目のプレイヤーがこのゲームで１度しか手番が来てないか判定
        return $this->players()->where('order', $next_order)->first();
    }


    // 最新のターンを取得する
    public function latestTurn()
    {
        return $this->turns()->latest();
    }
}
