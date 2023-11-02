<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    use HasFactory;

    public function player(){
        return $this->belongsTo(Player::class);
    }

    protected $guarded = [
        "id"
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = [
        "player_name",
    ];

    public function getPlayerNameAttribute()
    {
        return $this->player->user->name;
    }

}
