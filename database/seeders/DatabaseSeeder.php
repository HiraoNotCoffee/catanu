<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cell;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => '山田太郎',
            'email' => 'test@example.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => '鬼塚鬼吉',
            'email' => 'test2@example.com',
        ]);
        \App\Models\User::factory()->create([
            'name' => '桃白白',
            'email' => 'test3@example.com',
        ]);

        DB::table('users')->insert([
            'name' => "テスト1",
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
            'name' => "テスト2",
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);


        $cell = new Cell;
        $cell->name = "丘陵";
        $cell->img_path = "/storage/imgs/hill.png";
        $cell->save();
        $cell = new Cell;
        $cell->name = "森林";
        $cell->img_path = "/storage/imgs/forest.png";
        $cell->save();
        $cell = new Cell;
        $cell->name = "牧草地";
        $cell->img_path = "/storage/imgs/pasture.png";
        $cell->save();
        $cell = new Cell;
        $cell->name = "畑";
        $cell->img_path = "/storage/imgs/field.png";
        $cell->save();
        $cell = new Cell;
        $cell->name = "山地";
        $cell->img_path = "/storage/imgs/mountains.png";
        $cell->save();
        $cell = new Cell;
        $cell->name = "荒地";
        $cell->img_path = "/storage/imgs/wasteland.png";
        $cell->save();
    }
}
