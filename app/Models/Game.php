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
}
