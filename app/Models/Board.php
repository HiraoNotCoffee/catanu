<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    public function cell(){
        return $this->belongsTo(Cell::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}
