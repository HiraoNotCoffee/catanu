<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->integer('game_id');
            $table->integer('user_id');
            $table->integer('player_no');
            $table->integer('block')->default(0);
            $table->integer('lumber')->default(0);
            $table->integer('wool')->default(0);
            $table->integer('grain')->default(0);
            $table->integer('iron')->default(0);
            $table->integer('order');
            $table->integer('admin_player')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
