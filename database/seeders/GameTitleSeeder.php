<?php

namespace Database\Seeders;

use App\Models\GameTitle;
use Illuminate\Database\Seeder;

class GameTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // デフォルトのゲームタイトルを作成
        $game_title = GameTitle::create([
            "title" => "フォートナイト",
            "platform_id" => hash("sha256", "Nintendo Switch"),
            "description" => "世界的人気のTPSゲーム",
            "genre_id" => 0,
            "created_by" => null,
            "updated_by" => null,
        ]);
        print_r($game_title->toArray());

        $game_title = GameTitle::create([
            "title" => "Apex",
            "platform_id" => hash("sha256", "Nintendo Switch"),
            "description" => "世界的人気のFPSゲーム",
            "genre_id" => 0,
            "created_by" => null,
            "updated_by" => null,
        ]);
        print_r($game_title->toArray());
    }
}
