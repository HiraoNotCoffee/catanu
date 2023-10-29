<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cell;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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
